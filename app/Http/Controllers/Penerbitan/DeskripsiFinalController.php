<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use App\Events\DescovEvent;
use App\Events\DesfinEvent;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use Yajra\DataTables\DataTables;
use PhpParser\Node\Stmt\Continue_;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class DeskripsiFinalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('deskripsi_final as df')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->whereNull('df.deleted_at')
                ->select(
                    'df.*',
                    'pn.kode',
                    'pn.judul_asli',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'dp.naskah_id',
                    'dp.imprint',
                    'dp.nama_pena',
                    'dp.judul_final'
                )
                ->orderBy('df.tgl_deskripsi', 'ASC');
            if (in_array(auth()->id(),$data->pluck('pic_prodev')->toArray())) {
                $data->where('pn.pic_prodev', auth()->id());
            }
            $data->get();
            $update = Gate::allows('do_create', 'ubah-atau-buat-des-final');

            if ($request->has('count_data')) {
                return $data->count();
            } else {
                return DataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('kode', function ($data) {
                    return $data->kode;
                })
                ->addColumn('judul_asli', function ($data) {
                    return $data->judul_asli;
                })
                ->addColumn('penulis', function ($data) {
                    // return $data->penulis;
                    $result = '';
                    $res = DB::table('penerbitan_naskah_penulis as pnp')
                        ->join('penerbitan_penulis as pp', function ($q) {
                            $q->on('pnp.penulis_id', '=', 'pp.id')
                                ->whereNull('pp.deleted_at');
                        })
                        ->where('pnp.naskah_id', '=', $data->naskah_id)
                        ->select('pp.nama')
                        // ->pluck('pp.nama');
                        ->get();
                    foreach ($res as $q) {
                        $result .= '<span class="d-block">-&nbsp;' . $q->nama . '</span>';
                    }
                    return $result;
                    //  $res;
                })
                ->addColumn('nama_pena', function ($data) {
                    $result = '';
                    if (is_null($data->nama_pena)) {
                        $result .= "-";
                    } else {
                        foreach (json_decode($data->nama_pena) as $q) {
                            $result .= '<span class="d-block">-&nbsp;' . $q . '</span>';
                        }
                    }
                    return $result;
                })
                ->addColumn('jalur_buku', function ($data) {
                    if (!is_null($data->jalur_buku)) {
                        $res = $data->jalur_buku;
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
                ->addColumn('imprint', function ($data) {
                    if (!is_null($data->imprint)) {
                        $res = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
                ->addColumn('judul_final', function ($data) {
                    if (is_null($data->judul_final)) {
                        $res = '-';
                    } else {
                        $res = $data->judul_final;
                    }
                    return $res;
                })
                ->addColumn('tgl_deskripsi', function ($data) {
                    return Carbon::parse($data->tgl_deskripsi)->translatedFormat('l d M Y H:i');
                })
                ->addColumn('pic_prodev', function ($data) {
                    $res = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')
                        ->first()->nama;
                    return $res;
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('deskripsi_final_history')->where('deskripsi_final_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    $btn = '<a href="' . url('penerbitan/deskripsi/final/detail?desc=' . $data->id . '&kode=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                    if ($update) {
                        if ($data->status == 'Selesai') {
                            if (Gate::allows('do_approval', 'approval-deskripsi-produk')) {
                                $btn .= '<a href="' . url('penerbitan/deskripsi/final/edit?desc=' . $data->id . '&kode=' . $data->kode) . '"
                                        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                        <div><i class="fas fa-edit"></i></div></a>';
                            }
                        } else {
                            if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') || (Gate::allows('do_approval', 'approval-deskripsi-produk'))) {
                                $btn .= '<a href="' . url('penerbitan/deskripsi/final/edit?desc=' . $data->id . '&kode=' . $data->kode) . '"
                                        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                        <div><i class="fas fa-edit"></i></div></a>';
                            }
                        }
                    }
                    if (Gate::allows('do_approval', 'action-progress-des-final')) {
                        if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                            switch ($data->status) {
                                case 'Antrian':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-desfin" style="background:#34395E;color:white" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                            <div>' . $data->status . '</div></a>';
                                    break;
                                case 'Pending':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-desfin" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                            <div>' . $data->status . '</div></a>';
                                    break;
                                case 'Proses':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-desfin" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                            <div>' . $data->status . '</div></a>';
                                    break;
                                case 'Selesai':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-light btn-icon mr-1 mt-1 btn-status-desfin" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                            <div>' . $data->status . '</div></a>';
                                    break;
                                case 'Revisi':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-info btn-icon mr-1 mt-1 btn-status-desfin" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                            <div>' . $data->status . '</div></a>';
                                    break;
                                case 'Acc':
                                    $btn .= '<span class="d-block badge badge-light mr-1 mt-1">' . $data->status . '</span>';
                                    break;
                                default:
                                    return abort(410);
                                    break;
                            }
                        }
                    } else {
                        switch ($data->status) {
                            case 'Antrian':
                                $btn .= '<span class="d-block badge badge-secondary mr-1 mt-1">' . $data->status . '</span>';
                                break;
                            case 'Pending':
                                $btn .= '<span class="d-block badge badge-danger mr-1 mt-1">' . $data->status . '</span>';
                                break;
                            case 'Proses':
                                $btn .= '<span class="d-block badge badge-success mr-1 mt-1">' . $data->status . '</span>';
                                break;
                            case 'Selesai':
                                $btn .= '<span class="d-block badge badge-light mr-1 mt-1">' . $data->status . '</span>';
                                break;
                            case 'Revisi':
                                $btn .= '<span class="d-block badge badge-info mr-1 mt-1">' . $data->status . '</span>';
                                break;
                            case 'Acc':
                                $btn .= '<span class="d-block badge badge-light mr-1 mt-1">' . $data->status . '</span>';
                                break;
                            default:
                                return abort(410);
                                break;
                        }
                    }
                    return $btn;
                })
                ->rawColumns([
                    'kode',
                    'judul_asli',
                    'penulis',
                    'nama_pena',
                    'jalur_buku',
                    'imprint',
                    'judul_final',
                    'tgl_deskripsi',
                    'pic_prodev',
                    'history',
                    'action'
                ])
                ->make(true);
            }

        }
        //Isi Warna Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);

        return view('penerbitan.des_final.index', [
            'title' => 'Deskripsi Final',
            'status_progress' => Arr::sort($statusProgress),
        ]);
    }
    public function detailDeskripsiFinal(Request $request)
    {
        $id = $request->get('desc');
        $kode = $request->get('kode');
        $data = DB::table('deskripsi_final as df')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('df.id', $id)
            ->where('pn.kode', $kode)
            ->whereNull('dp.deleted_at')
            ->whereNull('pn.deleted_at')
            ->select(
                'df.*',
                'dp.naskah_id',
                'dp.nama_pena',
                'dp.format_buku',
                'dp.judul_final',
                'dp.imprint',
                'dp.jml_hal_perkiraan',
                'dp.kelengkapan',
                'dp.catatan',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev',
                'kb.nama'
            )
            ->first();
        if (is_null($data)) {
            return redirect()->route('desfin.view');
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.id','pp.nama')
            ->get();
        $pic = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')->select('id','nama')->first();
        $setter = DB::table('users')->where('id', $data->setter)->whereNull('deleted_at')->select('id','nama')->first();
        $korektor = DB::table('users')->where('id', $data->korektor)->whereNull('deleted_at')->select('id','nama')->first();
        $imprint = NULL;
        if (!is_null($data->imprint)) {
            $imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id',$data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        return view('penerbitan.des_final.detail', [
            'title' => 'Detail Deskripsi Final',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'setter' => $setter,
            'korektor' => $korektor,
            'imprint' => $imprint,
            'format_buku' => $format_buku,
        ]);
    }
    public function editDeskripsiFinal(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $history = DB::table('deskripsi_final as df')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->where('df.id', $request->id)
                    ->whereNull('df.deleted_at')
                    ->select('df.*', 'dp.judul_final', 'dp.nama_pena', 'dp.format_buku','dp.kelengkapan')
                    ->first();
                foreach ($request->bullet as $value) {
                    $bullet[] = $value;
                }
                $np = explode(",",$request->nama_pena);
                $n_pena = is_null($request->nama_pena)?NULL:json_encode($np);
                $bulletVariable = is_null($request->bullet)?NULL:json_encode(array_filter($bullet));
                $bulan = is_null($request->bulan)?NULL:Carbon::createFromDate($request->bulan);
                $update = [
                    'params' => 'Edit Desfin',
                    'id' => $request->id,
                    'judul_final' => $request->judul_final, //Di Deskripsi Produk
                    'nama_pena' => $n_pena, //Di Deskripsi Produk
                    'kelengkapan' => $request->kelengkapan, //Deskripsi Produk
                    'format_buku' => $request->format_buku,
                    'sub_judul_final' => $request->sub_judul_final,
                    'kertas_isi' => $request->kertas_isi,
                    // 'jml_hal_perkiraan' => $request->jml_hal_perkiraan, //Di Deskripsi Produk
                    'jml_hal_asli' => $request->jml_hal_asli,
                    'ukuran_asli' => $request->ukuran_asli,
                    'isi_warna' => $request->isi_warna,
                    'isi_huruf' => $request->isi_huruf,
                    'bullet' =>  $bulletVariable,
                    'setter' => $request->setter,
                    'korektor' => $request->korektor,
                    'sinopsis' => $request->sinopsis,
                    'catatan' => $request->catatan,
                    'bulan' => $bulan,
                    'updated_by' => auth()->id()
                ];
                event(new DesfinEvent($update));

                $insert = [
                    'params' => 'Insert History Desfin',
                    'deskripsi_final_id' => $request->id,
                    'type_history' => 'Update',
                    'nama_pena_his' => $history->nama_pena == $n_pena ? NULL : $history->nama_pena,
                    'nama_pena_new' => $history->nama_pena == $n_pena ? NULL : $n_pena,
                    'format_buku_his' => $history->format_buku == $request->format_buku ? null : $history->format_buku,
                    'format_buku_new' => $history->format_buku == $request->format_buku ? null : $request->format_buku,
                    'judul_final_his' => $history->judul_final == $request->judul_final ? null : $history->judul_final,
                    'judul_final_new' => $history->judul_final == $request->judul_final ? null : $request->judul_final,
                    'sub_judul_final_his' => $history->sub_judul_final == $request->sub_judul_final ? null : $history->sub_judul_final,
                    'sub_judul_final_new' => $history->sub_judul_final == $request->sub_judul_final ? null : $request->sub_judul_final,
                    'kertas_isi_his' => $history->kertas_isi == $request->kertas_isi ? null : $history->kertas_isi,
                    'kertas_isi_new' => $history->kertas_isi == $request->kertas_isi ? null : $request->kertas_isi,
                    // 'jml_hal_perkiraan_his' => $history->jml_hal_perkiraan == $request->jml_hal_perkiraan ? null : $history->jml_hal_perkiraan,
                    // 'jml_hal_perkiraan_new' => $history->jml_hal_perkiraan == $request->jml_hal_perkiraan ? null : $request->jml_hal_perkiraan,
                    'jml_hal_asli_his' => $history->jml_hal_asli == $request->jml_hal_asli ? null : $history->jml_hal_asli,
                    'jml_hal_asli_new' => $history->jml_hal_asli == $request->jml_hal_asli ? null : $request->jml_hal_asli,
                    'ukuran_asli_his' => $history->ukuran_asli == $request->ukuran_asli ? null : $history->ukuran_asli,
                    'ukuran_asli_new' => $history->ukuran_asli == $request->ukuran_asli ? null : $request->ukuran_asli,
                    'isi_warna_his' => $history->isi_warna == $request->isi_warna ? null : $history->isi_warna,
                    'isi_warna_new' => $history->isi_warna == $request->isi_warna ? null : $request->isi_warna,
                    'isi_huruf_his' => $history->isi_huruf == $request->isi_huruf ? null : $history->isi_huruf,
                    'isi_huruf_new' => $history->isi_huruf == $request->isi_huruf ? null : $request->isi_huruf,
                    'bullet_his' => $history->bullet == $bulletVariable ? null : $history->bullet,
                    'bullet_new' => $history->bullet == $bulletVariable ? null : $bulletVariable,
                    'setter_his' => $history->setter == $request->setter ? null : $history->setter,
                    'setter_new' => $history->setter == $request->setter ? null : $request->setter,
                    'korektor_his' => $history->korektor == $request->korektor ? null : $history->korektor,
                    'korektor_new' => $history->korektor == $request->korektor ? null : $request->korektor,
                    'sinopsis_his' => $history->sinopsis == $request->sinopsis ? null : $history->sinopsis,
                    'sinopsis_new' => $history->sinopsis == $request->sinopsis ? null : $request->sinopsis,
                    'kelengkapan_his' => $history->kelengkapan == $request->kelengkapan ? null : $history->kelengkapan,
                    'kelengkapan_new' => $history->kelengkapan == $request->kelengkapan ? null : $request->kelengkapan,
                    'catatan_his' => $history->catatan == $request->catatan ? null : $history->catatan,
                    'catatan_new' => $history->catatan == $request->catatan ? null : $request->catatan,
                    'bulan_his' => date('Y-m',strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $history->bulan,
                    'bulan_new' => date('Y-m',strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $bulan,
                    'author_id' => auth()->id(),
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new DesfinEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data deskripsi final berhasil ditambahkan',
                    'route' => route('desfin.view')
                ]);
            }
        }
        $id = $request->get('desc');
        $kodenaskah = $request->get('kode');
        $data = DB::table('deskripsi_final as df')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('df.id', $id)
            ->where('pn.kode', $kodenaskah)
            ->whereNull('dp.deleted_at')
            ->whereNull('pn.deleted_at')
            ->select(
                'df.*',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.kelengkapan',
                'dp.jml_hal_perkiraan',
                'dp.nama_pena',
                'dp.format_buku',
                'dp.imprint',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev',
                'kb.nama',
            )
            ->first();
        is_null($data) ? abort(404) :
            $setter = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Setter%')
            ->where('d.nama', 'LIKE', '%Penerbitan%')
            ->select('u.nama', 'u.id')
            ->get();
        if (!is_null($data->setter)) {
            $namaSetter = DB::table('users')
                ->where('id', $data->setter)
                ->first();
        } else {
            $namaSetter = null;
        }
        if (!is_null($data->korektor)) {
            $namaKorektor = DB::table('users')
                ->where('id', $data->korektor)
                ->first();
        } else {
            $namaKorektor = null;
        }
        $Korektor = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Korektor%')
            ->where('d.nama', 'LIKE', '%Penerbitan%')
            ->select('u.nama', 'u.id')
            ->get();
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();
        $format_buku_list = DB::table('format_buku')->whereNull('deleted_at')->get();
        $nama_pena = json_decode($data->nama_pena);
        //Kertas Isi Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'kertas_isi'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $kertas_isi = explode("','", $matches[1]);
        //Kelengkapan Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_produk WHERE Field = 'kelengkapan'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $kelengkapan = explode("','", $matches[1]);
        //Isi Warna Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'isi_warna'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $isi_warna = explode("','", $matches[1]);
        $nama_imprint = '-';
        if (!is_null($data->imprint)) {
            $nama_imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id',$data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        return view('penerbitan.des_final.edit', [
            'title' => 'Edit Deskripsi Final',
            'data' => $data,
            'setter' => $setter,
            'korektor' => $Korektor,
            'penulis' => $penulis,
            'format_buku_list' => $format_buku_list,
            'format_buku' => $format_buku,
            'kelengkapan' => $kelengkapan,
            'kertas_isi' => $kertas_isi,
            'isi_warna' => $isi_warna,
            'nama_setter' => $namaSetter,
            'nama_korektor' => $namaKorektor,
            'nama_imprint' => $nama_imprint,
            'nama_pena' => is_null($data->nama_pena)?NULL:implode(",",$nama_pena)
            // 'imprint' => $imprint
        ]);
    }
    public function updateStatusProgress(Request $request)
    {
        try {
            $id = $request->id;
            DB::beginTransaction();
            $data = DB::table('deskripsi_final as df')->join('deskripsi_produk as dp','df.deskripsi_produk_id','=','dp.id')
            ->where('df.id', $id)
            ->select('df.*','dp.naskah_id','dp.judul_final')
            ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ], 404);
            }
            if ($data->status == $request->status) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pilih status yang berbeda dengan status saat ini!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $update = [
                'params' => 'Update Status Desfin',
                'id' => $data->id,
                'status' => $request->status,
                'updated_by' => auth()->id()
            ];
            $insert = [
                'params' => 'Insert History Status Desfin',
                'deskripsi_final_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            $dataNas = DB::table('penerbitan_naskah')->where('id',$data->naskah_id)->select('pic_prodev')->first();
            if ($data->status == 'Selesai') {
                event(new DesfinEvent($update));
                event(new DesfinEvent($insert));
                $updateStatusDescov = [
                    'params' => 'Update Status Descov',
                    'deskripsi_produk_id' => $data->deskripsi_produk_id,
                    'status' => 'Terkunci',
                    'updated_by' => auth()->user()->id
                ];
                event(new DescovEvent($updateStatusDescov));
                $updateTimelineDesfin = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Deskripsi Final',
                    'tgl_selesai' => NULL,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineDesfin));
                $updateTimelineDescov = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Deskripsi Cover',
                    'tgl_selesai' => NULL,
                    'status' => 'Terkunci'
                ];
                event(new TimelineEvent($updateTimelineDescov));
                DB::table('todo_list')
                ->where('form_id',$data->id)
                ->where('users_id',$dataNas->pic_prodev)
                ->where('title','Proses deskripsi final naskah berjudul "'.$data->judul_final.'" perlu dilengkapi kelengkapan data nya.')
                ->update([
                    'status' => '0'
                ]);
                $msg = 'Status progress deskripsi final berhasil diupdate';
            } elseif ($request->status == 'Selesai') {
                event(new DesfinEvent($update));
                event(new DesfinEvent($insert));
                $updateStatusDescov = [
                    'params' => 'Update Status Descov',
                    'deskripsi_produk_id' => $data->deskripsi_produk_id,
                    'status' => 'Antrian',
                    'updated_by' => auth()->user()->id
                ];
                event(new DescovEvent($updateStatusDescov));
                $updateTimelineDesfin = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Deskripsi Final',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineDesfin));
                $updateTimelineDescov = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Deskripsi Cover',
                    'tgl_selesai' => NULL,
                    'status' => 'Antrian'
                ];
                event(new TimelineEvent($updateTimelineDescov));

                DB::table('todo_list')
                ->where('form_id',$data->id)
                ->where('users_id',$dataNas->pic_prodev)
                ->where('title','Proses deskripsi final naskah berjudul "'.$data->judul_final.'" perlu dilengkapi kelengkapan data nya.')
                ->update([
                    'status' => '1'
                ]);
                $msg = 'Deskripsi final selesai, silahkan lanjut ke proses Deskripsi Cover..';
            } else {
                event(new DesfinEvent($update));
                event(new DesfinEvent($insert));
                $updateTimelineDesfin = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Deskripsi Final',
                    'tgl_selesai' => NULL,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineDesfin));
                $msg = 'Status progress deskripsi final berhasil diupdate';
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $msg
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryDesfin(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('deskripsi_final_history as dfh')
                ->join('deskripsi_final as df', 'df.id', '=', 'dfh.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('users as u', 'dfh.author_id', '=', 'u.id')
                ->where('dfh.deskripsi_final_id', $id)
                ->select('dfh.*', 'dp.judul_final', 'u.nama')
                ->orderBy('dfh.id', 'desc')
                ->paginate(2);
            foreach ($data as $key => $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title">
                        <span><span class="bullet"></span> Status deskripsi final <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
                    </div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                    </div>
                    </span>';
                        break;
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">';
                    if (!is_null($d->judul_final_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Judul final <b class="text-dark">' . $d->judul_final_his . '</b> diubah menjadi <b class="text-dark">' . $d->judul_final_new . '</b>.</span></div>';
                    } elseif (!is_null($d->judul_final_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Judul final <b class="text-dark">' . $d->judul_final_new . '</b> ditambahkan.</span></div>';
                    }
                    if (!is_null($d->format_buku_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Format buku <b class="text-dark">' . DB::table('format_buku')->where('id',$d->format_buku_his)->first()->jenis_format . ' cm</b> diubah menjadi <b class="text-dark">' . DB::table('format_buku')->where('id',$d->format_buku_new)->first()->jenis_format . ' cm</b>.</span></div>';
                    } elseif (!is_null($d->format_buku_new)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Format buku <b class="text-dark">' . DB::table('format_buku')->where('id',$d->format_buku_new)->first()->jenis_format . ' cm</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if ((!is_null($d->nama_pena_his)) && ($d->nama_pena_his != '[""]')) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $loopNamaPenaHIS = '';
                        $loopNamaPenaNEW = '';
                        foreach (json_decode($d->nama_pena_his, true) as $namaPenahis) {
                            $loopNamaPenaHIS .= '<b class="text-dark">' . $namaPenahis . '</b>, ';
                        }
                        foreach (json_decode($d->nama_pena_new, true) as $namaPenanew) {
                            $loopNamaPenaNEW .= '<span class="bullet"></span>' . $namaPenanew;
                        }
                        $html .= ' Nama pena <b class="text-dark">' . $loopNamaPenaHIS . '</b> diubah menjadi <b class="text-dark">' . $loopNamaPenaNEW . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif ((!is_null($d->nama_pena_new)) && ($d->nama_pena_new != '[""]')) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $loopNamaPenaNEW = '';
                        foreach (json_decode($d->nama_pena_new, true) as $namaPenanew) {
                            $loopNamaPenaNEW .= '<b class="text-dark">' . $namaPenanew . '</b>, ';
                        }
                        $html .= ' Nama pena <b class="text-dark">' . $loopNamaPenaNEW . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->jml_hal_perkiraan_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Jumlah halaman perkiraan <b class="text-dark">' . $d->jml_hal_perkiraan_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_hal_perkiraan_new . '</b>.<br></span></div>';
                    } elseif (!is_null($d->jml_hal_perkiraan_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Jumlah halaman perkiraan <b class="text-dark">' . $d->jml_hal_perkiraan_new . '</b> ditambahkan.<br></span></div>';
                    }
                    if (!is_null($d->jml_hal_asli_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Jumlah halaman asli <b class="text-dark">' . $d->jml_hal_asli_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_hal_asli_new . '</b>.<br></span></div>';
                    } elseif (!is_null($d->jml_hal_asli_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Jumlah halaman asli <b class="text-dark">' . $d->jml_hal_asli_his . '</b> ditambahkan.<br></span></div>';
                    }
                    if (!is_null($d->ukuran_asli_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Ukuran asli <b class="text-dark">' . $d->ukuran_asli_his . '</b> diubah menjadi <b class="text-dark">' . $d->ukuran_asli_new . '</b>.<br></span></div>';
                    } elseif (!is_null($d->ukuran_asli_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Ukuran asli <b class="text-dark">' . $d->ukuran_asli_new . '</b> ditambahkan.<br></span></div>';
                    }
                    if (!is_null($d->isi_warna_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Isi warna <b class="text-dark">' . $d->isi_warna_his . '</b> diubah menjadi <b class="text-dark">' . $d->isi_warna_new . '</b>.<br></span></div>';
                    } elseif (!is_null($d->isi_warna_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Isi warna <b class="text-dark">' . $d->isi_warna_new . '</b> ditambahkan.<br></span></div>';
                    }
                    if (!is_null($d->isi_huruf_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Isi warna <b class="text-dark">' . $d->isi_huruf_his . '</b> diubah menjadi <b class="text-dark">' . $d->isi_huruf_new . '</b>.<br></span></div>';
                    } elseif (!is_null($d->isi_huruf_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Isi warna <b class="text-dark">' . $d->isi_huruf_new . '</b> ditambahkan.<br></span></div>';
                    }
                    if (!is_null($d->setter_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Setter <b class="text-dark">'
                            . DB::table('users')->where('id', $d->setter_his)->whereNull('deleted_at')->first()->nama .
                            '</b> diubah menjadi <b class="text-dark">' . DB::table('users')->where('id', $d->setter_new)->whereNull('deleted_at')->first()->nama . '</b>.<br></span></div>';
                    } elseif (!is_null($d->setter_new)) {
                    $html .= '<div class="ticket-title"><span><span class="bullet"></span> Setter <b class="text-dark">'
                        . DB::table('users')->where('id', $d->setter_new)->whereNull('deleted_at')->first()->nama .
                        '</b> ditambahkan.<br></span></div>';
                    }
                    if (!is_null($d->korektor_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Korektor <b class="text-dark">'
                            . DB::table('users')->where('id', $d->korektor_his)->whereNull('deleted_at')->first()->nama .
                            '</b> diubah menjadi <b class="text-dark">' . DB::table('users')->where('id', $d->korektor_new)->whereNull('deleted_at')->first()->nama . '</b>.<br></span></div>';
                    } elseif (!is_null($d->korektor_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Korektor <b class="text-dark">' . DB::table('users')->where('id', $d->korektor_new)->whereNull('deleted_at')->first()->nama . '</b> ditambahkan.<br></span></div>';
                    }
                    if (!is_null($d->kelengkapan_his)) {
                        $html .= '<div class="ticket-title">
                        <span>
                        <span class="bullet"></span> Kelengkapan <b class="text-dark">' . $d->kelengkapan_his . '</b> diubah menjadi <b class="text-dark">' . $d->kelengkapan_new . '</b>.<br>
                        </span></div>';
                    } elseif (!is_null($d->kelengkapan_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Kelengkapan <b class="text-dark">' . $d->kelengkapan_new . '</b> ditambahkan.<br></span></div>';
                    }
                    if (!is_null($d->catatan_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Catatan <b class="text-dark">' . $d->catatan_his . '</b> diubah menjadi <b class="text-dark">' . $d->catatan_new . '</b>.<br></span></div>';
                    } elseif (!is_null($d->catatan_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span> Catatan <b class="text-dark">' . $d->catatan_new . '</b> ditambahkan.<br></span></div>';
                    }
                    if (!is_null($d->bulan_his)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span> Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->bulan_new)->translatedFormat('F Y') . '</b>.</span></div>';
                    }
                    $html .= '<div class="ticket-info">
                    <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                    <div class="bullet pt-2"></div>
                    <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                </div>
                </span>';
                        break;
                }
            }
            return $html;
        }
    }
}
