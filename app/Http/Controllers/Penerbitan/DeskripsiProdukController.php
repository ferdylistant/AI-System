<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Events\DescovEvent;
use App\Events\DesfinEvent;
use App\Events\DesproEvent;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class DeskripsiProdukController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('deskripsi_produk as dp')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->whereNull('dp.deleted_at')
                ->select(
                    'dp.*',
                    'pn.kode',
                    'pn.judul_asli',
                    'pn.pic_prodev',
                    'pn.jalur_buku'
                )
                ->orderBy('dp.tgl_deskripsi', 'ASC')
                ->get();
            $update = Gate::allows('do_create', 'ubah-atau-buat-des-produk');

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
                    return Carbon::parse($data->tgl_deskripsi)->translatedFormat('l, d M Y h:i');
                })
                ->addColumn('pic_prodev', function ($data) {
                    $res = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')
                        ->first()->nama;
                    return $res;
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('deskripsi_produk_history')->where('deskripsi_produk_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulasli="' . $data->judul_asli . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    $btn = '<a href="' . url('penerbitan/deskripsi/produk/detail?desc=' . $data->id . '&kode=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                    if ($update) {
                        if ($data->status == 'Acc') {
                            if (Gate::allows('do_approval', 'approval-deskripsi-produk')) {
                                $btn .= '<a href="' . url('penerbitan/deskripsi/produk/edit?desc=' . $data->id . '&kode=' . $data->kode) . '"
                                        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                        <div><i class="fas fa-edit"></i></div></a>';
                            }
                        } else {
                            if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') || (Gate::allows('do_approval', 'approval-deskripsi-produk'))) {
                                $btn .= '<a href="' . url('penerbitan/deskripsi/produk/edit?desc=' . $data->id . '&kode=' . $data->kode) . '"
                                            class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                            <div><i class="fas fa-edit"></i></div></a>';
                            }
                        }
                    }
                    if (Gate::allows('do_approval', 'action-progress-des-produk')) {
                        if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                            switch ($data->status) {
                                case 'Antrian':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-despro" style="background:#34395E;color:white" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesProduk" title="Update Status">
                                            <div>' . $data->status . '</div></a>';
                                    break;
                                case 'Pending':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-despro" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesProduk" title="Update Status">
                                            <div>' . $data->status . '</div></a>';
                                    break;
                                case 'Proses':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-despro" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesProduk" title="Update Status">
                                            <div>' . $data->status . '</div></a>';
                                    break;
                                case 'Selesai':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-light btn-icon mr-1 mt-1 btn-status-despro" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesProduk" title="Update Status">
                                            <div>' . $data->status . '</div></a>';
                                    break;
                                case 'Revisi':
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-info btn-icon mr-1 mt-1 btn-status-despro" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" data-toggle="modal" data-target="#md_UpdateStatusDesProduk" title="Update Status">
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
        $data = DB::table('deskripsi_produk as dp')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->whereNull('dp.deleted_at')
            ->select(
                'dp.*',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev'
            )
            ->orderBy('dp.tgl_deskripsi', 'ASC')
            ->get();
        //Kelengkapan Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_produk WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        $statusAction = Arr::except($statusProgress, ['4', '5']);
        $statusProgress = Arr::sort($statusProgress);
        return view('penerbitan.des_produk.index', [
            'title' => 'Deskripsi Produk',
            'status_progress' => $statusProgress,
            'status_action' => Arr::sort($statusAction),
            'count' => count($data)
        ]);
    }
    public function detailDeskripsiProduk(Request $request)
    {
        $id = $request->get('desc');
        $kode = $request->get('kode');
        $data = DB::table('deskripsi_produk as dp')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('dp.id', $id)
            ->where('pn.kode', $kode)
            ->whereNull('dp.deleted_at')
            ->select(
                'dp.*',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev',
                'kb.nama'
            )
            ->first();
        if (is_null($data)) {
            return abort(404);
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
        $editor = DB::table('users')->where('id', $data->editor)->whereNull('deleted_at')->select('id','nama')->first();
        $imprint = NULL;
        if (!is_null($data->imprint)) {
            $imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        return view('penerbitan.des_produk.detail', [
            'title' => 'Detail Deskripsi Produk',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'editor' => $editor,
            'imprint' => $imprint
        ]);
    }
    public function editDeskripsiProduk(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                // return response()->json($es);
                $np = explode(",",$request->nama_pena);
                foreach ($request->alt_judul as $value) {
                    $altJudul[] = $value;
                }
                $history = DB::table('deskripsi_produk')
                    ->where('id', $request->id)
                    ->whereNull('deleted_at')
                    ->first();
                $update = [
                    'params' => 'Edit Despro',
                    'id' => $request->id,
                    'nama_pena' => is_null($request->nama_pena)?NULL:json_encode($np),
                    'alt_judul' => json_encode($altJudul),
                    'format_buku' => $request->format_buku,
                    'jml_hal_perkiraan' => $request->jml_hal_perkiraan,
                    'imprint' => $request->imprint,
                    'kelengkapan' => $request->kelengkapan,
                    'editor' => $request->editor,
                    'catatan' => $request->catatan,
                    'bulan' => Carbon::createFromDate($request->bulan),
                    'updated_by' => auth()->id()
                ];
                // event(new UpdateDesproEvent($update));
                event(new DesproEvent($update));

                $insert = [
                    'params' => 'Insert History Despro',
                    'deskripsi_produk_id' => $request->id,
                    'type_history' => 'Update',
                    'nama_pena_his' => $history->nama_pena == json_encode($np) ? NULL : $history->nama_pena,
                    'nama_pena_new' => $history->nama_pena == json_encode($np) ? NULL : json_encode($np),
                    'alt_judul_his' => $history->alt_judul == json_encode($altJudul) ? NULL : $history->alt_judul,
                    'alt_judul_new' => $history->alt_judul == json_encode($altJudul) ? NULL : json_encode($altJudul),
                    'format_buku_his' => $history->format_buku == $request->format_buku ? NULL : $history->format_buku,
                    'format_buku_new' => $history->format_buku == $request->format_buku ? NULL : $request->format_buku,
                    'jml_hal_his' => $history->jml_hal_perkiraan == $request->jml_hal_perkiraan ? NULL : $history->jml_hal_perkiraan,
                    'jml_hal_new' => $history->jml_hal_perkiraan == $request->jml_hal_perkiraan ? NULL : $request->jml_hal_perkiraan,
                    'imprint_his' => $history->imprint == $request->imprint ? NULL : $history->imprint,
                    'imprint_new' => $history->imprint == $request->imprint ? NULL : $request->imprint,
                    'editor_his' => $history->editor == $request->editor ? NULL : $history->editor,
                    'editor_new' => $history->editor == $request->editor ? NULL : $request->editor,
                    'kelengkapan_his' => $history->kelengkapan == $request->kelengkapan ? NULL : $history->kelengkapan,
                    'kelengkapan_new' => $history->kelengkapan == $request->kelengkapan ? NULL : $request->kelengkapan,
                    'catatan_his' => $history->catatan == $request->catatan ? NULL : $history->catatan,
                    'catatan_new' => $history->catatan == $request->catatan ? NULL : $request->catatan,
                    'bulan_his' => date('Y-m',strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $history->bulan,
                    'bulan_new' => date('Y-m',strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : Carbon::createFromDate($request->bulan),
                    'author_id' => auth()->id(),
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new DesproEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data deskripsi produk berhasil ditambahkan',
                    'route' => route('despro.view')
                ]);
            }
        }
        $id = $request->get('desc');
        $kodenaskah = $request->get('kode');
        $data = DB::table('deskripsi_produk as dp')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('dp.id', $id)
            ->where('pn.kode', $kodenaskah)
            ->whereNull('dp.deleted_at')
            ->select(
                'dp.*',
                'pn.kode',
                'pn.judul_asli',
                'kb.nama'
            )
            ->first();
        is_null($data) ? abort(404) :
            $editor = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Editor%')
            ->where('d.nama', 'LIKE', '%Penerbitan%')
            ->select('u.nama', 'u.id')
            ->orderBy('u.nama', 'ASC')
            ->get();
        if (!is_null($data->editor)) {
            $namaeditor = DB::table('users')->where('id', $data->editor)->whereNull('deleted_at')->first()->nama;
        } else {
            $namaeditor = NULL;
        }
        $nama_pena = json_decode($data->nama_pena);
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();
        $format_buku = DB::table('format_buku')->whereNull('deleted_at')->get();
        //Kelengkapan Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_produk WHERE Field = 'kelengkapan'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $kelengkapan = explode("','", $matches[1]);
        $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        $nama_imprint = NULL;
        if (!is_null($data->imprint)) {
            $nama_imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        return view('penerbitan.des_produk.edit', [
            'title' => 'Edit Deskripsi Produk',
            'data' => $data,
            'editor' => $editor,
            'nama_editor' => $namaeditor,
            'penulis' => $penulis,
            'format_buku' => $format_buku,
            'kelengkapan' => $kelengkapan,
            'imprint' => $imprint,
            'nama_imprint' => $nama_imprint,
            'nama_pena' => is_null($data->nama_pena)?NULL:implode(",",$nama_pena)
        ]);
    }
    public function requestAjax(Request $request)
    {
        try {
            switch ($request->cat) {
                case 'lihat-history':
                    return $this->lihatHistoryDespro($request);
                    break;
                case 'approve':
                    return $this->approveDespro($request);
                    break;
                case 'revisi':
                    return $this->revisiDespro($request);
                    break;
                case 'update-status-progress':
                    return $this->updateStatusProgress($request);
                    break;
            }
        } catch(\Exception $e) {
            return abort(500,$e->getMessage());
        }
    }
    protected function updateStatusProgress($request)
    {
        try {
            $id = $request->id;
            $data = DB::table('deskripsi_produk')->where('id', $id)->whereNull('deleted_at')->first();
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
            if ($data->status == "Revisi") {
                DB::table('deskripsi_produk')->where('id', $id)->update([
                    'alasan_revisi' => NULL,
                    'deadline_revisi' => NULL
                ]);
            }
            $update = [
                'params' => 'Update Status Despro',
                'id' => $data->id,
                'status' => $request->status,
                'updated_by' => auth()->id()
            ];
            event(new DesproEvent($update));
            $insert = [
                'params' => 'Insert History Status Despro',
                'deskripsi_produk_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            event(new DesproEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Status progress deskripsi produk berhasil diupdate'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function lihatHistoryDespro($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('deskripsi_produk_history as pd')
                ->join('users as u', 'pd.author_id', '=', 'u.id')
                ->where('pd.deskripsi_produk_id', $id)
                ->select('pd.*', 'u.nama')
                ->orderBy('pd.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title">
                        <span><span class="bullet"></span> Status deskripsi produk <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
                    </div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                    </div>
                    </span>';
                        break;

                    case 'Update':

                    if (!is_null($d->judul_final_his)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Judul final <b class="text-dark">' . $d->judul_final_his . '</b> diubah menjadi <b class="text-dark">' . $d->judul_final_new . '</b>.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    } elseif (!is_null($d->judul_final_new)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Judul alternatif <b class="text-dark">' . $d->judul_final_new . '</b> telah dipilih menjadi judul final.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    }
                    if (!is_null($d->nama_pena_his)) {
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                        $loopNamaPenaHIS = '';
                        $loopNamaPenaNEW = '';
                        foreach (json_decode($d->nama_pena_his, true) as $namaPenahis) {
                            $loopNamaPenaHIS .= '<b class="text-dark">' . $namaPenahis . '</b>, ';
                        }
                        foreach (json_decode($d->nama_pena_new, true) as $namaPenanew) {
                            $loopNamaPenaNEW .= '<span class="bullet"></span>' . $namaPenanew;
                        }
                        $html .= ' Nama pena <b class="text-dark">' . $loopNamaPenaHIS . '</b> diubah menjadi <b class="text-dark">' . $loopNamaPenaNEW . '</b>.<br>';
                        $html .= '</span></div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                    } elseif (!is_null($d->nama_pena_new)) {
                        $html .= '<span class="ticket-item">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $loopNamaPenaNEW = '';
                        foreach (json_decode($d->nama_pena_new, true) as $namaPenanew) {
                            $loopNamaPenaNEW .= '<b class="text-dark">' . $namaPenanew . '</b>, ';
                        }
                        $html .= ' Nama pena <b class="text-dark">' . $loopNamaPenaNEW . '</b> ditambahkan.<br>';
                        $html .= '</span></div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                    }
                    if (!is_null($d->format_buku_his)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Format buku <b class="text-dark">' . $d->format_buku_his . ' cm</b> diubah menjadi <b class="text-dark">' . $d->format_buku_new . ' cm</b>.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    } elseif (!is_null($d->format_buku_new)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Format buku <b class="text-dark">' . $d->format_buku_new . ' cm</b> ditambahkan.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    }
                    if (!is_null($d->jml_hal_his)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Jumlah halaman perkiraan <b class="text-dark">' . $d->jml_hal_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_hal_new . '</b>.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    } elseif (!is_null($d->jml_hal_new)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Jumlah halaman perkiraan <b class="text-dark">' . $d->jml_hal_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    }
                    if (!is_null($d->imprint_his)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Imprint <b class="text-dark">' . $d->imprint_his . '</b> diubah menjadi <b class="text-dark">' . $d->imprint_new . '</b>.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    } elseif (!is_null($d->imprint_new)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Imprint <b class="text-dark">' . $d->imprint_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    }
                    if (!is_null($d->editor_his)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Editor <b class="text-dark">'
                            . DB::table('users')->where('id', $d->editor_his)->whereNull('deleted_at')->first()->nama .
                            '</b> diubah menjadi <b class="text-dark">' . DB::table('users')->where('id', $d->editor_new)->whereNull('deleted_at')->first()->nama . '</b>.<br>';
                            $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    } elseif (!is_null($d->editor_new)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Editor <b class="text-dark">' . DB::table('users')->where('id', $d->editor_new)->whereNull('deleted_at')->first()->nama . '</b> ditambahkan.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    }
                    if (!is_null($d->kelengkapan_his)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Kelengkapan <b class="text-dark">' . $d->kelengkapan_his . '</b> diubah menjadi <b class="text-dark">' . $d->kelengkapan_new . '</b>.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    } elseif (!is_null($d->kelengkapan_new)) {
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Kelengkapan <b class="text-dark">' . $d->kelengkapan_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    }
                    if (!is_null($d->catatan_his)) {
                        $html .= '<span class="ticket-item">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Catatan <b class="text-dark">' . $d->catatan_his . '</b> diubah menjadi <b class="text-dark">' . $d->catatan_new . '</b>.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    } elseif (!is_null($d->catatan_new)) {
                        $html .= '<span class="ticket-item">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Catatan <b class="text-dark">' . $d->catatan_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    }
                    if (!is_null($d->bulan_his)) {
                        $html .= '<span class="ticket-item">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->bulan_new)->translatedFormat('F Y') . '</b>.';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    } elseif (!is_null($d->bulan_new)) {
                        $html .= '<span class="ticket-item">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> ditambahkan.';
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                    }
                        break;

                    case 'Revisi':
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title">
                        <span><span class="bullet"></span> Deskripsi produk naskah ini diminta untuk direvisi selambatnya tanggal <b class="text-dark">' . Carbon::parse($d->deadline_revisi_his)->translatedFormat('l d F Y, H:i') . '</b>. Direvisi dengan alasan <b class="text-dark">"' . $d->alasan_revisi_his . '"</b>.</span>
                    </div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                    </div>
                    </span>';
                        break;

                    case 'Approval':
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title">
                        <span><span class="bullet"></span> Deskripsi produk naskah ini telah disetujui direksi. <i class="fas fa-check text-success"></i></span>
                    </div>
                    <div class="ticket-info">
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
    protected function revisiDespro($request)
    {
        try {
            $kode = $request->input('kode');
            $judul_asli = $request->input('judul_asli');
            $data = DB::table('deskripsi_produk')->where('id', $request->input('id'))->where('status', 'Selesai')->whereNull('deadline_revisi')->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Deskripsi produk "' . $judul_asli . '" sedang direvisi'
                ]);
            }
            DB::table('deskripsi_produk')->where('id', $request->input('id'))->update([
                'status' => "Revisi",
                'deadline_revisi' => Carbon::createFromFormat('d F Y', $request->input('deadline_revisi'))->format('Y-m-d H:i:s'),
                'alasan_revisi' => $request->input('alasan'),
                'action_gm' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'updated_by' => auth()->id()
            ]);
            $insert = [
                'params' => 'Insert History Revisi Despro',
                'deskripsi_produk_id' => $request->input('id'),
                'type_history' => 'Revisi',
                'status_his' => $data->status,
                'deadline_revisi_his' => Carbon::createFromFormat('d F Y', $request->input('deadline_revisi'))->format('Y-m-d H:i:s'),
                'alasan_revisi_his' => $request->input('alasan'),
                'status_new' => 'Revisi',
                'author_id' => auth()->id(),
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            //EVENT
            event(new DesproEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Naskah "' . $kode . '" telah disetujui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function approveDespro($request)
    {
        try {
            DB::beginTransaction();
            $id = $request->id;
            $data = DB::table('deskripsi_produk')->where('id', $id)->whereNotNull('judul_final')->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Judul final belum ditentukan..'
                ]);
            }
            $despro = [
                'params' => 'Approval Despro',
                'id' => $data->id,
                'status' => 'Acc',
                'action_gm' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            ];
            // event(new DesproEvent($despro));
            // return response()->json($despro);
            DB::table('deskripsi_produk')->where('id',$despro['id'])->update([
                'status' => $despro['status'],
                'action_gm' => $despro['action_gm']
            ]);
            $history = [
                'params' => 'Insert History Status Despro',
                'deskripsi_produk_id' => $id,
                'type_history' => 'Approval',
                'status_his' => $data->status,
                'status_new' => 'Acc',
                'author_id' => auth()->id(),
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];

            $desFin = [
                'params' => 'Input Deskripsi',
                'id' => Uuid::uuid4()->toString(),
                'deskripsi_produk_id' => $id,
                'tgl_deskripsi' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];

            $desCov = [
                'params' => 'Input Deskripsi',
                'id' => Uuid::uuid4()->toString(),
                'deskripsi_produk_id' => $id,
                'tgl_deskripsi' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            event(new DesproEvent($history));
            event(new DesfinEvent($desFin));
            event(new DescovEvent($desCov));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Deskripsi produk telah disetujui, silahkan cek Deskripsi Final dan Deskripsi Cover untuk tracking lanjutan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function pilihJudul(Request $request)
    {
        try {
            $id = $request->id;
            $judul = $request->judul;
            $data = [
                'params' => 'Pilih Judul',
                'id' => $id,
                'judul' => $judul
            ];
            event(new DesproEvent($data));
            return response([
                'status' => 'success',
                'message' => 'Judul final telah dipilih',
                'data' => $judul
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
