<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use App\Events\DesturcetEvent;
use Yajra\DataTables\DataTables;
use App\Events\PracetakSetterEvent;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class PracetakSetterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->whereNull('dp.deleted_at')
                ->select(
                    'ps.*',
                    'pn.kode',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'pn.kelompok_buku_id',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'dp.nama_pena',
                    'df.bullet'
                )
                ->orderBy('ps.tgl_masuk_pracetak', 'ASC')
                ->get();

            return DataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('kode', function ($data) {
                    $tandaProses = '';
                    $dataKode = $data->kode;
                    if ($data->status == 'Proses') {
                        if ($data->proses_saat_ini == 'Siap Turcet') {
                            $dataKode = '<span class="text-primary">' . $data->kode . '</span>';
                            $tandaProses = '<span class="beep-primary d-table"></span>';
                        } else {
                            $dataKode = $data->proses == '1'  ? '<span class="text-success">' . $data->kode . '</span>' : '<span class="text-danger">' . $data->kode . '</span>';
                            $tandaProses = $data->proses == '1' ? '<span class="beep-success d-table"></span>' : '<span class="beep-danger d-table"></span>';
                        }
                    }
                    return $dataKode . $tandaProses;
                })
                ->addColumn('judul_final', function ($data) {
                    if (is_null($data->judul_final)) {
                        $res = '-';
                    } else {
                        $res = $data->judul_final;
                    }
                    return $res;
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
                ->addColumn('tgl_masuk_pracetak', function ($data) {
                    return Carbon::parse($data->tgl_masuk_pracetak)->translatedFormat('l d M Y H:i');
                })
                ->addColumn('pic_prodev', function ($data) {
                    $result = '';
                    $res = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')
                        ->select('nama')
                        ->get();
                    foreach (json_decode($res) as $q) {
                        $result .= $q->nama;
                    }
                    return $result;
                })
                ->addColumn('proses_saat_ini', function ($data) {
                    if (!is_null($data->proses_saat_ini)) {
                        $res = '<span class="badge badge-light">' . $data->proses_saat_ini . '</span>';
                    } else {
                        $res = '<span class="text-danger"><i class="fas fa-exclamation-circle"></i>&nbsp;Belum ada proses</span>';
                    }
                    return $res;
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('pracetak_setter_history')->where('pracetak_setter_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                    switch ($data->jalur_buku) {
                        case 'Reguler':
                            $jb = 'reguler';
                            $btn = $this->logicPermissionAction($data->status, $jb, $data->pic_prodev, $data->id, $data->kode, $data->judul_final, $btn);
                            break;
                        case 'MoU':
                            $jb = 'mou';
                            $btn = $this->logicPermissionAction($data->status, $jb, $data->pic_prodev, $data->id, $data->kode, $data->judul_final, $btn);
                            break;
                        case 'SMK/NonSMK':
                            $jb = 'smk';
                            $btn = $this->logicPermissionAction($data->status, $jb, $data->pic_prodev, $data->id, $data->kode, $data->judul_final, $btn);
                            break;
                        default:
                            $jb = 'MoU-Reguler';
                            $btn = $this->logicPermissionAction($data->status, $jb, $data->pic_prodev, $data->id, $data->kode, $data->judul_final, $btn);
                            break;
                    }
                    return $btn;
                })
                ->rawColumns([
                    'kode',
                    'judul_final',
                    'penulis',
                    'nama_pena',
                    'jalur_buku',
                    'tgl_masuk_pracetak',
                    'pic_prodev',
                    'proses_saat_ini',
                    'history',
                    'action'
                ])
                ->make(true);
        }
        $data = DB::table('pracetak_setter as ps')
            ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->whereNull('dp.deleted_at')
            ->select(
                'ps.*',
                'pn.kode',
                'pn.pic_prodev',
                'pn.jalur_buku',
                'dp.naskah_id',
                'dp.judul_final',
            )
            ->orderBy('ps.tgl_masuk_pracetak', 'ASC')
            ->get();
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_setter WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        $statusAction = Arr::except($statusProgress, ['4']);
        $statusProgress = Arr::sort($statusProgress);
        return view('penerbitan.pracetak_setter.index', [
            'title' => 'Pracetak Setter',
            'status_action' => $statusAction,
            'status_progress' => $statusProgress,
            'count' => count($data)
        ]);
    }
    public function editSetter(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    DB::beginTransaction();
                    $history = DB::table('pracetak_setter as ps')
                        ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                        ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                        ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                        ->where('ps.id', $request->id)
                        ->select('ps.*', 'dp.naskah_id','dp.judul_final','pn.kode')
                        ->first();
                    if ($request->has('korektor')) {
                        foreach ($request->korektor as $ce) {
                            $req_korektor[] = $ce;
                        }
                        $korektor = json_encode($req_korektor);
                    } else {
                        $korektor = null;
                    }
                    if ($history->proses == '1') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Hentikan proses untuk update data'
                        ]);
                    }
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    if ($request->proses_saat_ini == 'Turun Cetak') {
                        if (is_null($history->selesai_setting) || is_null($history->selesai_koreksi)) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Belum melakukan proses setting/koreksi!'
                            ]);
                        }
                        if ($history->status == 'Proses') {
                            $cekTidakKosong = DB::table('pracetak_setter')
                                ->where('id', $request->id)
                                ->whereNotNull('isbn')
                                ->whereNotNull('pengajuan_harga')
                                ->first();
                            if (is_null($cekTidakKosong)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'ISBN, atau Pengajuan Harga belum diinput!'
                                ]);
                            }
                            $cekSelesaiCover = DB::table('pracetak_cover')
                                ->where('id', $request->id_pracov)
                                ->where('status', 'Selesai')
                                ->first();
                            if (!is_null($cekSelesaiCover)) {
                                $id_turcet = Uuid::uuid4()->toString();
                                $turcet = [
                                    'params' => 'Insert Turun Cetak',
                                    'id' => $id_turcet,
                                    'pracetak_cover_id' => $request->id_pracov,
                                    'pracetak_setter_id' => $history->id,
                                    'tgl_masuk' => $tgl
                                ];
                                event(new DesturcetEvent($turcet));
                                //INSERT TIMELINE TURUN CETAK
                                $insertTimelinePraset = [
                                    'params' => 'Insert Timeline',
                                    'id' => Uuid::uuid4()->toString(),
                                    'progress' => 'Deskripsi Turun Cetak',
                                    'naskah_id' => $history->naskah_id,
                                    'tgl_mulai' => $tgl,
                                    'url_action' => urlencode(URL::to('/penerbitan/deskripsi/turun-cetak/detail?desc=' . $id_turcet . '&kode=' . $history->kode)),
                                    'status' => 'Antrian'
                                ];
                                event(new TimelineEvent($insertTimelinePraset));
                            }
                            DB::table('pracetak_setter')->where('id', $request->id)->update([
                                'turun_cetak' => $tgl,
                                'status' => 'Selesai'
                            ]);
                        }
                    }
                    $mulaiCopyright = $request->mulai_p_copyright == '' ? NULL : Carbon::createFromFormat('d F Y', $request->mulai_p_copyright)->format('Y-m-d H:i:s');
                    $selesaiCopyright = $request->selesai_p_copyright == '' ? NULL : Carbon::createFromFormat('d F Y', $request->selesai_p_copyright)->format('Y-m-d H:i:s');
                    $update = [
                        'params' => 'Edit Pracetak Setter',
                        'id' => $request->id,
                        'jml_hal_final' => $request->jml_hal_final,
                        'catatan' => $request->catatan,
                        'setter' => json_encode($request->setter),
                        'korektor' => $request->has('korektor') ? json_encode($request->korektor) : null,
                        'edisi_cetak' => $request->edisi_cetak,
                        'isbn' => $request->isbn,
                        'pengajuan_harga' => $request->pengajuan_harga,
                        'mulai_p_copyright' => $mulaiCopyright,
                        'selesai_p_copyright' => $selesaiCopyright,
                        'proses_saat_ini' => $request->proses_saat_ini,
                        'bulan' => Carbon::createFromDate($request->bulan)
                    ];
                    event(new PracetakSetterEvent($update));
                    $insert = [
                        'params' => 'Insert History Edit Pracetak Setter',
                        'pracetak_setter_id' => $request->id,
                        'type_history' => 'Update',
                        'setter_his' => $history->setter == json_encode(array_filter($request->setter)) ? null : $history->setter,
                        'setter_new' => $history->setter == json_encode(array_filter($request->setter)) ? null : json_encode(array_filter($request->setter)),
                        'jml_hal_final_his' => $history->jml_hal_final == $request->jml_hal_final ? null : $history->jml_hal_final,
                        'jml_hal_final_new' => $history->jml_hal_final == $request->jml_hal_final ? null : $request->jml_hal_final,
                        'edisi_cetak_his' => $history->edisi_cetak == $request->edisi_cetak ? null : $history->edisi_cetak,
                        'edisi_cetak_new' => $history->edisi_cetak == $request->edisi_cetak ? null : $request->edisi_cetak,
                        'isbn_his' => $history->isbn == $request->isbn ? null : $history->isbn,
                        'isbn_new' => $history->isbn == $request->isbn ? null : $request->isbn,
                        'pengajuan_harga_his' => $history->pengajuan_harga == $request->pengajuan_harga ? null : $history->pengajuan_harga,
                        'pengajuan_harga_new' => $history->pengajuan_harga == $request->pengajuan_harga ? null : $request->pengajuan_harga,
                        'korektor_his' => $history->korektor == $korektor ? null : $history->korektor,
                        'korektor_new' => $history->korektor == $korektor ? null : $korektor,
                        'catatan_his' => $history->catatan == $request->catatan ? null : $history->catatan,
                        'catatan_new' => $history->catatan == $request->catatan ? null : $request->catatan,
                        'mulai_p_copyright' => $history->mulai_p_copyright == $mulaiCopyright ? null : $history->mulai_p_copyright,
                        'selesai_p_copyright' => $history->selesai_p_copyright == $selesaiCopyright ? null : $history->selesai_p_copyright,
                        'bulan_his' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $history->bulan,
                        'bulan_new' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : Carbon::createFromDate($request->bulan),
                        'proses_ini_his' => $history->proses_saat_ini == $request->proses_saat_ini ? null : $history->proses_saat_ini,
                        'proses_ini_new' => $history->proses_saat_ini == $request->proses_saat_ini ? null : $request->proses_saat_ini,
                        'author_id' => auth()->id(),
                        'modified_at' => $tgl
                    ];
                    event(new PracetakSetterEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data setting proses berhasil ditambahkan',
                        'route' => route('setter.view')
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
            }
        }
        $id = $request->get('setter');
        $kodenaskah = $request->get('kode');
        $data = DB::table('pracetak_setter as ps')
            ->join('deskripsi_final as df', 'ps.deskripsi_final_id', '=', 'df.id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('deskripsi_cover as dc', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('pracetak_cover as pc', 'dc.id', '=', 'pc.deskripsi_cover_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('ps.id', $id)
            ->where('pn.kode', $kodenaskah)
            ->select(
                'ps.*',
                'df.sub_judul_final',
                'df.bullet',
                'df.sinopsis',
                'df.kertas_isi',
                'df.isi_warna',
                'df.isi_huruf',
                'df.ukuran_asli',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.nama_pena',
                'dp.imprint',
                'dp.format_buku',
                'dp.jml_hal_perkiraan',
                'pn.kode',
                'pn.jalur_buku',
                'pn.pic_prodev',
                'kb.nama',
                'pc.id as id_pracov'
            )
            ->first();
        is_null($data) ? abort(404) :
            $setter = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Setter%')
            ->where('d.nama', 'LIKE', '%Penerbitan%')
            ->select('u.nama', 'u.id')
            ->orderBy('u.nama', 'Asc')
            ->get();
        if (!is_null($data->setter)) {
            foreach (json_decode($data->setter) as $e) {
                $namaSetter[] = DB::table('users')->where('id', $e)->first()->nama;
            }
            $korektor = DB::table('users as u')
                ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
                ->where('j.nama', 'LIKE', '%Setter%')
                ->where('d.nama', 'LIKE', '%Penerbitan%')
                ->whereNotIn('u.id', json_decode($data->setter))
                ->orWhere('j.nama', 'LIKE', '%Korektor%')
                ->select('u.nama', 'u.id')
                ->orderBy('u.nama', 'Asc')
                ->get();
        } else {
            $namaSetter = null;
            $korektor = null;
        }
        if (!is_null($data->korektor)) {
            foreach (json_decode($data->korektor) as $ce) {
                $namakorektor[] = DB::table('users')->where('id', $ce)->first()->nama;
            }
        } else {
            $namakorektor = null;
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();
        $nama_imprint = '-';
        if (!is_null($data->imprint)) {
            $nama_imprint = DB::table('imprint')->where('id', $data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id', $data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_setter WHERE Field = 'proses_saat_ini'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $prosesSaatIni = explode("','", $matches[1]);
        $prosesFilter = Arr::except($prosesSaatIni, ['1', '4', '7']);
        return view('penerbitan.pracetak_setter.edit', [
            'title' => 'Pracetak Setter Proses',
            'data' => $data,
            'setter' => $setter,
            'nama_setter' => $namaSetter,
            'korektor' => $korektor,
            'nama_korektor' => $namakorektor,
            'proses_saat_ini' => $prosesFilter,
            'penulis' => $penulis,
            'nama_imprint' => $nama_imprint,
            'format_buku' => $format_buku,
        ]);
    }
    public function detailSetter(Request $request)
    {
        $id = $request->get('pra');
        $kode = $request->get('kode');
        $data = DB::table('pracetak_setter as ps')
            ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('ps.id', $id)
            ->where('pn.kode', $kode)
            ->select(
                'ps.*',
                'df.sub_judul_final',
                'df.bullet',
                'df.sinopsis',
                'df.kertas_isi',
                'df.isi_warna',
                'df.isi_huruf',
                'df.ukuran_asli',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.imprint',
                'dp.format_buku',
                'dp.nama_pena',
                'dp.jml_hal_perkiraan',
                'pn.kode',
                'pn.jalur_buku',
                'pn.pic_prodev',
                'kb.nama',
            )
            ->first();
        if (is_null($data)) {
            return abort(404);
        }
        $proofRevisi = DB::table('pracetak_setter_proof')->where('pracetak_setter_id', $id)->where('type_action', 'Revisi')->get();
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.id', 'pp.nama')
            ->get();
        $pic = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')->first();
        if (!is_null($data->setter)) {
            foreach (json_decode($data->setter, true) as $set) {
                $namaSetter[] = DB::table('users')->where('id', $set)->first();
            }
        } else {
            $namaSetter = null;
        }
        if (!is_null($data->korektor)) {
            foreach (json_decode($data->korektor, true) as $kor) {
                $namaKorektor[] = DB::table('users')->where('id', $kor)->first();
            }
        } else {
            $namaKorektor = null;
        }
        $label = is_null($data->selesai_setting) ? "setter" : "korektor";
        $dataRole  = is_null($data->selesai_setting) ? $data->setter : $data->korektor;
        $doneProses = DB::table('pracetak_setter_selesai')
            ->where('type', ucfirst($label))
            ->where('pracetak_setter_id', $data->id)
            ->where('users_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->first();
        switch ($label) {
            case 'setter':
                if (is_null($doneProses)) {
                    $result = FALSE;
                } else {
                    if (!is_null($data->selesai_koreksi) && is_null($data->selesai_setting)) {
                        switch ($doneProses->section) {
                            case 'Proof Setting':
                                $result = $data->proses_saat_ini == 'Setting Revisi' ? FALSE : TRUE;
                                break;
                            case 'Setting Revision':
                                $lastKoreksi = DB::table('pracetak_setter_selesai')
                                    ->where('type', 'Korektor')
                                    ->where('section', 'Koreksi')
                                    ->where('pracetak_setter_id', $data->id)
                                    ->orderBy('id', 'desc')
                                    ->first();
                                if ($lastKoreksi->tahap > 1) {
                                    // $tahapKoreksi = $lastKoreksi->tahap + 1;
                                    $doneSelf = DB::table('pracetak_setter_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Setting Revision')
                                        ->where('tahap', $lastKoreksi->tahap)
                                        ->where('pracetak_setter_id', $data->id)
                                        ->get();
                                    if (!$doneSelf->isEmpty()) {
                                        foreach ($doneSelf as $d) {
                                            $userId[] = $d->users_id;
                                        }
                                        if (in_array(auth()->user()->id, $userId)) {
                                            $result = TRUE;
                                        } else {
                                            $tahap = $doneProses->tahap + 1;
                                            $doneNext = DB::table('pracetak_setter_selesai')
                                                ->where('type', ucfirst($label))
                                                ->where('section', 'Setting Revision')
                                                ->where('tahap', $tahap)
                                                ->where('pracetak_setter_id', $data->id)
                                                ->where('users_id', auth()->user()->id)
                                                ->first();
                                            if (!is_null($doneNext)) {
                                                $result = TRUE;
                                            } else {
                                                $result = FALSE;
                                            }
                                        }
                                    } else {
                                        $result = FALSE;
                                    }
                                } else {
                                    $doneSelf = DB::table('pracetak_setter_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Setting Revision')
                                        ->where('tahap', $doneProses->tahap)
                                        ->where('pracetak_setter_id', $data->id)
                                        ->get();
                                    if (!$doneSelf->isEmpty()) {
                                        foreach ($doneSelf as $d) {
                                            $userId[] = $d->users_id;
                                        }
                                        if (in_array(auth()->user()->id, $userId)) {
                                            $result = TRUE;
                                        } else {
                                            $tahap = $doneProses->tahap + 1;
                                            $doneNext = DB::table('pracetak_setter_selesai')
                                                ->where('type', ucfirst($label))
                                                ->where('section', 'Setting Revision')
                                                ->where('tahap', $tahap)
                                                ->where('pracetak_setter_id', $data->id)
                                                ->where('users_id', auth()->user()->id)
                                                ->first();
                                            if (!is_null($doneNext)) {
                                                $result = TRUE;
                                            } else {
                                                $result = FALSE;
                                            }
                                        }
                                    } else {
                                        $result = FALSE;
                                    }
                                }
                                break;
                            default:
                                $result = FALSE;
                                break;
                        }
                    } else {
                        $result = TRUE;
                    }
                }
                break;
            default:
                if (is_null($doneProses)) {
                    $result = FALSE;
                } else {
                    if (is_null($data->selesai_koreksi) && !is_null($data->selesai_setting)) {
                        $lastSetting = DB::table('pracetak_setter_selesai')
                            ->where('type', 'Setter')
                            ->where('pracetak_setter_id', $data->id)
                            ->orderBy('id', 'desc')
                            ->first();
                        if ($lastSetting->tahap > 1) {
                            $doneSelf = DB::table('pracetak_setter_selesai')
                                ->where('type', ucfirst($label))
                                ->where('section', 'Koreksi')
                                ->where('tahap', $lastSetting->tahap + 1)
                                ->where('pracetak_setter_id', $data->id)
                                ->get();
                            if (!$doneSelf->isEmpty()) {
                                foreach ($doneSelf as $d) {
                                    $userId[] = $d->users_id;
                                }
                                if (in_array(auth()->user()->id, $userId)) {
                                    $result = TRUE;
                                } else {
                                    $tahap = $doneProses->tahap + 1;
                                    $doneNext = DB::table('pracetak_setter_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Koreksi')
                                        ->where('tahap', $tahap)
                                        ->where('pracetak_setter_id', $data->id)
                                        ->where('users_id', auth()->user()->id)
                                        ->first();
                                    if (!is_null($doneNext)) {
                                        $result = TRUE;
                                    } else {
                                        $result = FALSE;
                                    }
                                }
                            } else {
                                $result = FALSE;
                            }
                        } else {
                            $doneSelf = DB::table('pracetak_setter_selesai')
                                ->where('type', ucfirst($label))
                                ->where('section', 'Koreksi')
                                ->where('tahap', $doneProses->tahap)
                                ->where('pracetak_setter_id', $data->id)
                                ->get();
                            if (!$doneSelf->isEmpty()) {
                                foreach ($doneSelf as $d) {
                                    $userId[] = $d->users_id;
                                }
                                if (in_array(auth()->user()->id, $userId)) {
                                    $result = FALSE;
                                } else {
                                    $tahap = $doneProses->tahap + 1;
                                    $doneNext = DB::table('pracetak_setter_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Koreksi')
                                        ->where('tahap', $tahap)
                                        ->where('pracetak_setter_id', $data->id)
                                        ->where('users_id', auth()->user()->id)
                                        ->first();
                                    if (!is_null($doneNext)) {
                                        $result = TRUE;
                                    } else {
                                        $result = FALSE;
                                    }
                                }
                            } else {
                                $result = FALSE;
                            }
                        }
                    } else {
                        $result = TRUE;
                    }
                }
                break;
        }
        $imprint = NULL;
        if (!is_null($data->imprint)) {
            $imprint = DB::table('imprint')->where('id', $data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id', $data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        return view('penerbitan.pracetak_setter.detail', [
            'title' => 'Detail Pracetak Setter',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'nama_setter' => $namaSetter,
            'nama_korektor' => $namaKorektor,
            'label' => $label,
            'dataRole' => $dataRole,
            'done_proses' => $result,
            'proof_revisi' => $proofRevisi,
            'imprint' => $imprint,
            'format_buku' => $format_buku,
        ]);
    }
    public function actionAjax(Request $request)
    {
        try {
            switch ($request->act) {
                case 'proses-kerja':
                    return $this->prosesKerjaSetter($request);
                    break;
                case 'update-status-progress':
                    return $this->updateStatusProgress($request);
                    break;
                case 'revision':
                    return $this->revisionActProdev($request);
                    break;
                case 'approve':
                    return $this->approveActProdev($request);
                    break;
                case 'revision-done':
                    return $this->donerevisionActKabag($request);
                    break;
                case 'lihat-history':
                    return $this->lihatHistorySetter($request);
                    break;
                case 'lihat-informasi-proof':
                    return $this->lihatInformasiProof($request);
                    break;
                case 'lihat-proses-setkor':
                    return $this->lihatProgressSetterKorektor($request);
                    break;
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan!'
                    ]);
                    break;
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function prosesKerjaSetter($request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->id;
                $value = $request->proses;
                $data = DB::table('pracetak_setter')->where('id', $id)->first();
                if (is_null($data)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'naskah sudah tidak ada'
                    ]);
                }
                if ($value == '0') {
                    if (is_null($data->selesai_setting)) {
                        $dataProgress = [
                            'params' => 'Progress Setter-Korektor',
                            'label' => 'setting',
                            'id' => $id,
                            'mulai_setting' => NULL,
                            'proses' => $value,
                            'proses_saat_ini' => NULL,
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                    } else {
                        $dataProgress = [
                            'params' => 'Progress Setter-Korektor',
                            'label' => 'koreksi',
                            'id' => $id,
                            'mulai_koreksi' => NULL,
                            'proses' => $value,
                            'proses_saat_ini' => NULL,
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                    }
                    event(new PracetakSetterEvent($dataProgress));
                    $msg = 'Proses diberhentikan!';
                } else {
                    if (is_null($data->proses_saat_ini)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Pilih proses saat ini!'
                        ]);
                    }
                    switch ($data->proses_saat_ini) {
                        case 'Antrian Setting':
                            if (!is_null($data->selesai_setting)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses setting selesai, silahkan ubah proses saat ini menjadi "Proof Prodev".'
                                ]);
                            }
                            break;
                        case 'Antrian Koreksi':
                            if (is_null($data->selesai_setting)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses setting belum selesai. Belum bisa melakukan proses "Antrian Koreksi".'
                                ]);
                            }
                            if (is_null($data->mulai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses proof belum dilakukan, silahkan ubah proses saat ini menjadi "Proof Prodev".'
                                ]);
                            }
                            if (!is_null($data->selesai_koreksi)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses koreksi selesai, silahkan ubah proses saat ini!.'
                                ]);
                            }
                            break;
                        case 'Proof Prodev':
                            if (is_null($data->selesai_setting)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses setting belum selesai. Belum bisa melakukan proses "Proof Prodev".'
                                ]);
                            }
                            if (!is_null($data->selesai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses proof prodev selesai, silahkan ubah proses saat ini menjadi proses selanjutnya atau mengubah status proses menjadi selesai.'
                                ]);
                            }
                            if (!is_null($data->mulai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses proof prodev sudah dimulai.'
                                ]);
                            }
                            break;
                    }
                    if ((!is_null($data->selesai_koreksi)) && ($data->proses_saat_ini == 'Setting Revisi')) {
                        DB::table('pracetak_setter')->where('id', $id)->update([
                            'selesai_setting' => NULL
                        ]);
                        if (!$request->has('setter')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih setter terlebih dahulu!'
                            ]);
                        } else {
                            $tglSetter = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            $dataSetter = [
                                'params' => 'Progress Setter-Korektor',
                                'label' => 'setting',
                                'id' => $id,
                                'mulai_setting' => $tglSetter,
                                'proses' => $value,
                                'proses_saat_ini' => 'Setting Revisi',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakSetterEvent($dataSetter));
                        }
                    } elseif (is_null($data->selesai_setting)) {
                        if (!$request->has('setter')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih setter terlebih dahulu!'
                            ]);
                        } else {
                            if (json_decode($data->setter, true) == $request->setter) {
                                if (is_null($data->mulai_setting)) {
                                    $tglSetter = Carbon::now('Asia/Jakarta')->toDateTimeString();
                                } else {
                                    $tglSetter = $data->mulai_setting;
                                }
                            } else {
                                $tglSetter = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            }
                            $dataSetter = [
                                'params' => 'Progress Setter-Korektor',
                                'label' => 'setting',
                                'id' => $id,
                                'mulai_setting' => $tglSetter,
                                'proses' => $value,
                                'proses_saat_ini' => 'Setting',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakSetterEvent($dataSetter));
                        }
                    } elseif (is_null($data->selesai_proof)) {
                        if (is_null($data->mulai_proof)) {
                            $tglProof = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        } else {
                            $tglProof = $data->mulai_proof;
                        }
                        $dataProof = [
                            'params' => 'Progress Proof Prodev',
                            'id' => $id,
                            'mulai_proof' => $tglProof,
                            'proses' => $value,
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        event(new PracetakSetterEvent($dataProof));
                    } else {
                        if (!$request->has('korektor')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih korektor terlebih dahulu!'
                            ]);
                        } else {
                            if (json_decode($data->korektor, true) == $request->korektor) {
                                if (is_null($data->mulai_koreksi)) {
                                    $tglKorektor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                                } else {
                                    $tglKorektor = $data->mulai_koreksi;
                                }
                            } else {
                                $tglKorektor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            }
                            $dataKorektor = [
                                'params' => 'Progress Setter-Korektor',
                                'label' => 'koreksi',
                                'id' => $id,
                                'mulai_koreksi' => $tglKorektor,
                                'proses' => $value,
                                'proses_saat_ini' => 'Koreksi',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakSetterEvent($dataKorektor));
                        }
                    }
                    $msg = 'Proses dimulai!';
                }
                return response()->json([
                    'status' => 'success',
                    'message' => $msg
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }
    protected function updateStatusProgress($request)
    {
        try {
            $id = $request->id;
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('deskripsi_cover as dc', 'dc.deskripsi_produk_id', '=', 'dp.id')
                ->join('pracetak_cover as pc', 'pc.deskripsi_cover_id', '=', 'dc.id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                    $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                        ->whereNull('kb.deleted_at');
                })
                ->where('ps.id', $id)
                ->select(
                    'ps.*',
                    'df.id as deskripsi_final_id',
                    'dp.naskah_id',
                    'dp.format_buku',
                    'dp.judul_final',
                    'dp.imprint',
                    'dp.kelengkapan',
                    'dp.catatan',
                    'pn.kode',
                    'pn.judul_asli',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'kb.nama',
                    'pc.id as pracov_id'
                )
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
                'params' => 'Update Status Pracetak Setter',
                'id' => $data->id,
                'turun_cetak' => $request->status == 'Selesai' ? $tgl : NULL,
                'status' => $request->status,
            ];
            $insert = [
                'params' => 'Insert History Status Pracetak Setter',
                'pracetak_setter_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            if ($data->proses == '1') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak bisa mengubah status, karena sedang proses kerja.'
                ]);
            }
            if ($request->status == 'Selesai') {
                if (is_null($data->selesai_setting)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses setting belum selesai'
                    ]);
                }
                if (is_null($data->selesai_proof)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses proof prodev belum selesai'
                    ]);
                }
                if (is_null($data->selesai_koreksi)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses koreksi belum selesai'
                    ]);
                }
                if (is_null($data->selesai_p_copyright)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses copyright belum selesai'
                    ]);
                }
                if (is_null($data->pengajuan_harga)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses pengajuan harga belum selesai'
                    ]);
                }
                if (is_null($data->isbn)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'ISBN belum ditambahkan.'
                    ]);
                }
                event(new PracetakSetterEvent($update));
                event(new PracetakSetterEvent($insert));
                //UPDATE TIMELINE PRACETAK SETTER
                $updateTimelinePracetakSetter = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Pracetak Setter',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelinePracetakSetter));
                $dataPraset = DB::table('pracetak_cover')
                    ->where('id', $data->pracov_id)
                    ->where('status', 'Selesai')
                    ->first();
                if (!is_null($dataPraset)) {
                    DB::table('pracetak_setter')->where('id', $data->id)->update([
                        'proses_saat_ini' => 'Turun Cetak'
                    ]);
                    //Insert Deskripsi Turun Cetak
                    $id_turcet = Uuid::uuid4()->toString();
                    $in = [
                        'params' => 'Insert Turun Cetak',
                        'id' => $id_turcet,
                        'pracetak_cover_id' => $data->pracov_id,
                        'pracetak_setter_id' => $data->id,
                        'tgl_masuk' => $tgl,
                    ];
                    event(new DesturcetEvent($in));
                    //INSERT TIMELINE TURUN CETAK
                    $insertTimelinePraset = [
                        'params' => 'Insert Timeline',
                        'id' => Uuid::uuid4()->toString(),
                        'progress' => 'Deskripsi Turun Cetak',
                        'naskah_id' => $data->naskah_id,
                        'tgl_mulai' => $tgl,
                        'url_action' => urlencode(URL::to('/penerbitan/deskripsi/turun-cetak/detail?desc=' . $id_turcet . '&kode=' . $data->kode)),
                        'status' => 'Antrian'
                    ];
                    event(new TimelineEvent($insertTimelinePraset));
                    $msg = 'Pracetak Setter selesai, silahkan lanjut ke proses deskripsi turun cetak..';
                } else {
                    $msg = 'Pracetak Setter selesai, silahkan selesaikan proses pracetak cover..';
                }
            } else {
                event(new PracetakSetterEvent($update));
                event(new PracetakSetterEvent($insert));
                $updateTimelinePracetakSetter = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Pracetak Setter',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelinePracetakSetter));
                $msg = 'Status progress pracetak setter berhasil diupdate';
            }
            return response()->json([
                'status' => 'success',
                'message' => $msg
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function lihatHistorySetter($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pracetak_setter_history as psh')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'psh.pracetak_setter_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('users as u', 'psh.author_id', '=', 'u.id')
                ->where('psh.pracetak_setter_id', $id)
                ->select('psh.*', 'dp.judul_final', 'u.nama')
                ->orderBy('psh.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status pracetak setter <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Update':

                        if (!is_null($d->status_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Status pracetak setter <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->ket_revisi)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Status pracetak setter <b class="text-dark">' . $d->status_new . '</b> dengan keterangan revisi: <b class="text-dark">' . $d->ket_revisi . '</b>.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->setter_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $loopSetHIS = '';
                            $loopSetNEW = '';
                            foreach (json_decode($d->setter_his, true) as $sethis) {
                                $loopSetHIS .= '<b class="text-dark">' . DB::table('users')->where('id', $sethis)->first()->nama . '</b>, ';
                            }
                            foreach (json_decode($d->setter_new, true) as $setnew) {
                                $loopSetNEW .= '<span class="bullet"></span>' . DB::table('users')->where('id', $setnew)->first()->nama;
                            }
                            $html .= ' Setter <b class="text-dark">' . $loopSetHIS . '</b> diubah menjadi <b class="text-dark">' . $loopSetNEW . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->setter_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $loopSetNEW = '';
                            foreach (json_decode($d->setter_new, true) as $setnew) {
                                $loopSetNEW .= '<b class="text-dark">' . DB::table('users')->where('id', $setnew)->first()->nama . '</b>, ';
                            }
                            $html .= ' Setter <b class="text-dark">' . $loopSetNEW . '</b> ditambahkan.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->korektor_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $loopKorHIS = '';
                            $loopKorNEW = '';
                            foreach (json_decode($d->korektor_his, true) as $korhis) {
                                $loopKorHIS .= '<b class="text-dark">' . DB::table('users')->where('id', $korhis)->first()->nama . '</b>, ';
                            }
                            foreach (json_decode($d->korektor_new, true) as $kornew) {
                                $loopKorNEW .= '<span class="bullet"></span>' . DB::table('users')->where('id', $kornew)->first()->nama;
                            }
                            $html .= ' Korektot <b class="text-dark">' . $loopKorHIS . '</b> diubah menjadi <b class="text-dark">' . $loopKorNEW . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->korektor_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $loopKorNEW = '';
                            foreach (json_decode($d->korektor_new, true) as $kornew) {
                                $loopKorNEW .= '<b class="text-dark">' . DB::table('users')->where('id', $kornew)->first()->nama . '</b>, ';
                            }
                            $html .= ' Korektor <b class="text-dark">' . $loopKorNEW . '</b> ditambahkan.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->jml_hal_final_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_final_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_hal_final_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->jml_hal_final_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_final_new . '</b> ditambahkan.';
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
                        if (!is_null($d->edisi_cetak_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Edisi cetak <b class="text-dark">' . $d->edisi_cetak_his . '</b> diubah menjadi <b class="text-dark">' . $d->edisi_cetak_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->edisi_cetak_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Edisi cetak <b class="text-dark">' . $d->edisi_cetak_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->proses_ini_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses saat ini <b class="text-dark">' . $d->proses_ini_his . '</b> diubah menjadi <b class="text-dark">' . $d->proses_ini_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->proses_ini_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses saat ini <b class="text-dark">' . $d->proses_ini_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->isbn_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' ISBN <b class="text-dark">' . $d->isbn_his . '</b> diubah menjadi <b class="text-dark">' . $d->isbn_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->isbn_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' ISBN <b class="text-dark">' . $d->isbn_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->pengajuan_harga_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Pengajuan harga <b class="text-dark">' . $d->pengajuan_harga_his . '</b> diubah menjadi <b class="text-dark">' . $d->pengajuan_harga_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->pengajuan_harga_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Pengajuan harga <b class="text-dark">' . $d->pengajuan_harga_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->mulai_setting)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Setting dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_setting)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->selesai_setting)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Setting selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_setting)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->mulai_proof)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proof prodev dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_proof)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->selesai_proof)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proof prodev selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_proof)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->mulai_koreksi)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Koreksi dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_koreksi)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->selesai_koreksi)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Koreksi selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_koreksi)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->mulai_p_copyright)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses copyright dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_p_copyright)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->selesai_p_copyright)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses copyright selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_p_copyright)->translatedFormat('l d F Y, H:i') . '</b>.';
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
                    case 'Progress':
                        $ket = $d->progress == 1 ? 'Dimulai' : 'Dihentikan';
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Progress pracetak  setter <b class="text-dark">' . $ket . '</b>.</span>
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
    protected function lihatInformasiProof($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pracetak_setter_proof as psp')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'psp.pracetak_setter_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('users as u', 'psp.users_id', '=', 'u.id')
                ->where('psp.pracetak_setter_id', $id)
                ->select('psp.*', 'dp.judul_final', 'u.nama')
                ->orderBy('psp.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_action) {
                    case 'Proof':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Prodev menyetujui hasil setting.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_action, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_action)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Revisi':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status pracetak setter <b>Revisi</b> dengan keterangan revisi: <b>' . $d->ket_revisi . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_action, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_action)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Selesai Revisi':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Setter telah menyelesaikan revisi dengan persetujuan kabag.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_action, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_action)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                }
            }
            return $html;
        }
    }
    protected function lihatProgressSetterKorektor($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pracetak_setter_selesai as pss')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'pss.pracetak_setter_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('users as u', 'pss.users_id', '=', 'u.id')
                ->where('pss.pracetak_setter_id', $id)
                ->select('pss.*', 'dp.judul_final', 'u.nama')
                ->orderBy('pss.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                $labelSetkor = $d->type == 'Setter' ? 'Setting' : 'Koreksi';
                switch ($d->section) {
                    case 'Proof Setting':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelSetkor . ' untuk proof oleh prodev telah diselesaikan.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Proof Setting Revision':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelSetkor . ' hasil revisi untuk proof oleh prodev telah diselesaikan.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Setting Revision':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelSetkor . ' hasil revisi tahap ' . $d->tahap . ' dari korektor telah diselesaikan setter.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Koreksi':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelSetkor . ' setting naskah tahap ' . $d->tahap . ' oleh korektor telah diselesaikan.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                }
            }
            return $html;
        }
    }
    protected function revisionActProdev($request)
    {
        try {
            $data = DB::table('pracetak_setter')->where('id', $request->id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
            $rev = [
                'params' => 'Act Prodev',
                'type_user' => 'Setter',
                'type_action' => 'Revisi',
                'pracetak_setter_id' => $request->id, //?Pracetak Setter, Pracetak Setter Proof, & Pracetak Setter History
                'users_id' => auth()->id(), //?Pracetak Setter Proof & Pracetak Setter History
                'ket_revisi' => $request->ket_revisi, //?Pracetak Setter Proof, & Pracetak Setter History
                'tgl_action' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'selesai_proof' => NULL, //?Pracetak Setter & Pracetak Setter History
                'proses_saat_ini' => 'Setting Revisi', //?Pracetak Setter
                'proses' => '1', //?Pracetak Setter
                'type_history' => 'Update', //? Pracetak Setter History
                'status_his' => $data->status,
                'status' => 'Revisi' //?Pracetak Setter & Pracetak Setter History
            ];
            event(new PracetakSetterEvent($rev));
            return response()->json([
                'status' => 'success',
                'message' => 'Setting naskah direvisi!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function approveActProdev($request)
    {
        try {
            $data = DB::table('pracetak_setter')->where('id', $request->id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $done = [
                'params' => 'Act Prodev',
                'type_user' => 'Setter',
                'type_action' => 'Proof',
                'pracetak_setter_id' => $request->id, //?Pracetak Setter, Pracetak Setter Proof, & Pracetak Setter History
                'users_id' => auth()->id(), //?Pracetak Setter Proof & Pracetak Setter History
                'ket_revisi' => NULL, //?Pracetak Setter Proof, & Pracetak Setter History
                'tgl_action' => $tgl,
                'selesai_proof' => $tgl, //?Pracetak Setter & Pracetak Setter History
                'proses_saat_ini' => NULL, //?Pracetak Setter
                'proses' => '0', //?Pracetak Setter
                'type_history' => 'Update', //? Pracetak Setter History
                'status_his' => $data->status, //? Pracetak Setter History
                'status' => 'Proses' //?Pracetak Setter & Pracetak Setter History
            ];
            event(new PracetakSetterEvent($done));
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function donerevisionActKabag($request)
    {
        try {
            $data = DB::table('pracetak_setter')->where('id', $request->id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $done = [
                'params' => 'Act Prodev',
                'type_user' => 'Setter',
                'type_action' => 'Selesai Revisi',
                'pracetak_setter_id' => $request->id, //?Pracetak Setter, Pracetak Setter Proof, & Pracetak Setter History
                'users_id' => auth()->id(), //?Pracetak Setter Proof & Pracetak Setter History
                'ket_revisi' => NULL, //?Pracetak Setter Proof, & Pracetak Setter History
                'tgl_action' => $tgl,
                'selesai_proof' => NULL, //?Pracetak Setter & Pracetak Setter History
                'proses_saat_ini' => 'Proof Prodev', //?Pracetak Setter
                'proses' => '1', //?Pracetak Setter
                'type_history' => 'Update', //? Pracetak Setter History
                'status_his' => $data->status, //? Pracetak Setter History
                'status' => 'Proses' //?Pracetak Setter & Pracetak Setter History
            ];
            event(new PracetakSetterEvent($done));
            return response()->json([
                'status' => 'success',
                'message' => 'Setting naskah telah selesai direvisi!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function logicPermissionAction($status = null, $jb = null, $pic_prodev, $id, $kode, $judul_final, $btn)
    {
        switch ($jb) {
            case 'MoU-Reguler':
                $gate = Gate::allows('do_create', 'ubah-atau-buat-setter-reguler') || Gate::allows('do_create', 'ubah-atau-buat-setter-mou');
                break;
            default:
                $gate = Gate::allows('do_create', 'ubah-atau-buat-setter-' . $jb);
                break;
        }
        if ($gate) {
            $btn = $this->buttonEdit($id, $kode, $btn);
        } else {
            if ($status == 'Selesai') {
                if (Gate::allows('do_approval', 'approval-deskripsi-produk') || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                    $btn = $this->buttonEdit($id, $kode, $btn);
                }
            }
        }

        if ($gate) {
            $btn = $this->panelStatusKabag($status, $id, $kode, $judul_final, $btn);
        } else {
            $btn = $this->panelStatusGuest($status, $btn);
        }
        return $btn;
    }
    protected function panelStatusGuest($status = null, $btn)
    {
        switch ($status) {
            case 'Antrian':
                $btn .= '<span class="d-block badge badge-secondary mr-1 mt-1">' . $status . '</span>';
                break;
            case 'Pending':
                $btn .= '<span class="d-block badge badge-danger mr-1 mt-1">' . $status . '</span>';
                break;
            case 'Proses':
                $btn .= '<span class="d-block badge badge-success mr-1 mt-1">' . $status . '</span>';
                break;
            case 'Selesai':
                $btn .= '<span class="d-block badge badge-light mr-1 mt-1">' . $status . '</span>';
                break;
            case 'Revisi':
                $btn .= '<span class="d-block badge badge-info mr-1 mt-1">' . $status . '</span>';
                break;
            default:
                return abort(410);
                break;
        }
        return $btn;
    }
    protected function panelStatusKabag($status = null, $id, $kode, $judul_final, $btn)
    {
        switch ($status) {
            case 'Antrian':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-setter" style="background:#34395E;color:white" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusSetter" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Pending':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-setter" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusSetter" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Proses':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-setter" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusSetter" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Selesai':
                $btn .= '<span class="d-block badge badge-light mr-1 mt-1">' . $status . '</span>';
                break;
            case 'Revisi':
                $btn .= '<span class="d-block badge badge-info mr-1 mt-1">' . $status . '</span>';
                break;
            default:
                return abort(410);
                break;
        }
        return $btn;
    }
    protected function buttonEdit($id, $kode, $btn)
    {
        $btn .= '<a href="' . url('penerbitan/pracetak/setter/edit?setter=' . $id . '&kode=' . $kode) . '"
                class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                <div><i class="fas fa-edit"></i></div></a>';
        return $btn;
    }
    public function prosesSelesaiSetter($autor, $id)
    {
        switch ($autor) {
            case 'setter':
                return $this->selesaiSetter($id);
                break;
            case 'korektor':
                return $this->selesaiKorektor($id);
                break;
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi Kesalahan!'
                ]);
                break;
        }
    }
    protected function selesaiSetter($id)
    {
        try {
            $data = DB::table('pracetak_setter')->where('id', $id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ada'
                ]);
            }
            $dataProsesSelf = DB::table('pracetak_setter_selesai')
                ->where('type', 'Setter')
                ->where('section', 'Proof Setting')
                ->where('pracetak_setter_id', $data->id)
                ->where('users_id', auth()->id())
                ->first();
            if (!is_null($dataProsesSelf)) {
                $dataProsesSettRevision = DB::table('pracetak_setter_selesai')
                    ->where('type', 'Setter')
                    ->where('section', 'Setting Revision')
                    ->where('pracetak_setter_id', $data->id)
                    ->where('users_id', auth()->id())
                    ->orderBy('id', 'desc')
                    ->first();
                if (!is_null($dataProsesSettRevision)) {
                    $tahapSelanjutnya = $dataProsesSettRevision->tahap + 1;
                    $dataNew = DB::table('pracetak_setter_selesai')
                        ->where('type', 'Setter')
                        ->where('section', 'Setting Revision')
                        ->where('tahap', $tahapSelanjutnya)
                        ->where('pracetak_setter_id', $data->id)
                        ->get();
                    $setPros = count($dataNew) + 1;
                    if ($setPros == count(json_decode($data->setter, true))) {
                        $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        $pros = [
                            'params' => 'Proses Setting-Koreksi Selesai',
                            'type' => 'Setter',
                            'section' => 'Setting Revision',
                            'tahap' => $tahapSelanjutnya,
                            'pracetak_setter_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_selesai' => $tgl
                        ];
                        event(new PracetakSetterEvent($pros));
                        $done = [
                            'params' => 'Setting-Koreksi Selesai',
                            'label' => 'setting',
                            'id' => $data->id,
                            'selesai_setting' => $tgl,
                            'proses' => '0',
                            'proses_saat_ini' => 'Antrian Koreksi',
                            //Pracetak Setter History
                            'type_history' => 'Update',
                            'author_id' => auth()->id()
                        ];
                        event(new PracetakSetterEvent($done));
                        DB::table('pracetak_setter')->where('id', $data->id)->update([
                            'selesai_koreksi' => NULL
                        ]);
                    } else {
                        $pros = [
                            'params' => 'Proses Setting-Koreksi Selesai',
                            'type' => 'Setter',
                            'section' => 'Setting Revision',
                            'tahap' => $tahapSelanjutnya,
                            'pracetak_setter_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        event(new PracetakSetterEvent($pros));
                    }
                } else {
                    $dataProses = DB::table('pracetak_setter_selesai')
                        ->where('type', 'Setter')
                        ->where('section', 'Setting Revision')
                        ->where('tahap', 1)
                        ->where('pracetak_setter_id', $data->id)
                        ->get();
                    $setPros = count($dataProses) + 1;
                    if ($setPros == count(json_decode($data->setter, true))) {
                        $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        $pros = [
                            'params' => 'Proses Setting-Koreksi Selesai',
                            'type' => 'Setter',
                            'section' => 'Setting Revision',
                            'tahap' => 1,
                            'pracetak_setter_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_selesai' => $tgl
                        ];
                        event(new PracetakSetterEvent($pros));
                        $done = [
                            'params' => 'Setting-Koreksi Selesai',
                            'label' => 'setting',
                            'id' => $data->id,
                            'selesai_setting' => $tgl,
                            'proses' => '0',
                            'proses_saat_ini' => 'Antrian Koreksi',
                            //Pracetak Setter History
                            'type_history' => 'Update',
                            'author_id' => auth()->id()
                        ];
                        event(new PracetakSetterEvent($done));
                        DB::table('pracetak_setter')->where('id', $data->id)->update([
                            'selesai_koreksi' => NULL
                        ]);
                    } else {
                        $pros = [
                            'params' => 'Proses Setting-Koreksi Selesai',
                            'type' => 'Setter',
                            'section' => 'Setting Revision',
                            'tahap' => 1,
                            'pracetak_setter_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        event(new PracetakSetterEvent($pros));
                    }
                }
            } else {
                $dataProses = DB::table('pracetak_setter_selesai')
                    ->where('type', 'Setter')
                    ->where('section', 'Proof Setting')
                    ->where('tahap', 1)
                    ->where('pracetak_setter_id', $data->id)
                    ->get();
                $setPros = count($dataProses) + 1;
                if ($setPros == count(json_decode($data->setter, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Setter',
                        'section' =>  'Proof Setting',
                        'tahap' => 1,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new PracetakSetterEvent($pros));
                    $done = [
                        'params' => 'Setting-Koreksi Selesai',
                        'label' => 'setting',
                        'id' => $data->id,
                        'selesai_setting' => $tgl,
                        'proses' => '0',
                        'proses_saat_ini' => NULL,
                        //Pracetak Setter History
                        'type_history' => 'Update',
                        'author_id' => auth()->id()
                    ];
                    event(new PracetakSetterEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Setter',
                        'section' =>  'Proof Setting',
                        'tahap' => 1,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new PracetakSetterEvent($pros));
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pengerjaan selesai'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function selesaiKorektor($id)
    {
        try {
            $data = DB::table('pracetak_setter')->where('id', $id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ada'
                ]);
            }
            $dataProsesSelf = DB::table('pracetak_setter_selesai')
                ->where('type', 'Korektor')
                ->where('section', 'Koreksi')
                ->where('pracetak_setter_id', $data->id)
                ->where('users_id', auth()->id())
                ->orderBy('id', 'desc')
                ->first();

            if (!is_null($dataProsesSelf)) {
                $tahapSelanjutnya = $dataProsesSelf->tahap + 1;
                $dataNew = DB::table('pracetak_setter_selesai')
                    ->where('type', 'Korektor')
                    ->where('section', 'Koreksi')
                    ->where('tahap', $tahapSelanjutnya)
                    ->where('pracetak_setter_id', $data->id)
                    ->get();
                $korPros = count($dataNew) + 1;
                if ($korPros == count(json_decode($data->korektor, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => $tahapSelanjutnya,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new PracetakSetterEvent($pros));
                    $done = [
                        'params' => 'Setting-Koreksi Selesai',
                        'label' => 'koreksi',
                        'id' => $data->id,
                        'selesai_koreksi' => $tgl,
                        'proses' => '0',
                        'proses_saat_ini' => 'Setting Revisi',
                        //Pracetak Setter History
                        'type_history' => 'Update',
                        'author_id' => auth()->id()
                    ];
                    event(new PracetakSetterEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => $tahapSelanjutnya,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new PracetakSetterEvent($pros));
                }
            } else {
                $dataProses = DB::table('pracetak_setter_selesai')
                    ->where('type', 'Korektor')
                    ->where('section', 'Koreksi')
                    ->where('tahap', 1)
                    ->where('pracetak_setter_id', $data->id)
                    ->get();
                $korPros = count($dataProses) + 1;
                if ($korPros == count(json_decode($data->korektor, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => 1,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new PracetakSetterEvent($pros));
                    $done = [
                        'params' => 'Setting-Koreksi Selesai',
                        'label' => 'koreksi',
                        'id' => $data->id,
                        'selesai_koreksi' => $tgl,
                        'proses' => '0',
                        'proses_saat_ini' => 'Setting Revisi',
                        //Pracetak Setter History
                        'type_history' => 'Update',
                        'author_id' => auth()->id()
                    ];
                    event(new PracetakSetterEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => 1,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new PracetakSetterEvent($pros));
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pengerjaan selesai'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function notifikasi()
    {
        try {
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
