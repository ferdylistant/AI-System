<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use App\Events\DesturcetEvent;
use Yajra\DataTables\DataTables;
use App\Events\PracetakCoverEvent;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class PracetakDesainerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pracetak_cover as pc')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->whereNull('df.deleted_at')
                ->select(
                    'pc.*',
                    'pn.kode',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'dp.naskah_id',
                    'dp.imprint',
                    'dp.judul_final',
                    'dp.nama_pena',
                )
                ->orderBy('pc.tgl_masuk_cover', 'ASC')
                ->get();
            return DataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('kode', function ($data) {
                    $tandaProses = '';
                    $dataKode = $data->kode;
                    if ($data->status == 'Proses') {
                        if ($data->proses_saat_ini == 'Siap Turcet') {
                            $tandaProses = '<span class="beep-primary"></span>';
                            $dataKode = '<span class="text-primary">' . $data->kode . '</span>';
                        } else {
                            $tandaProses = $data->proses == '1' ? '<span class="beep-success"></span>' : '<span class="beep-danger"></span>';
                            $dataKode = $data->proses == '1'  ? '<span class="text-success">' . $data->kode . '</span>' : '<span class="text-danger">' . $data->kode . '</span>';
                        }
                    }
                    return $tandaProses . $dataKode;
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
                ->addColumn('tgl_masuk_cover', function ($data) {
                    return Carbon::parse($data->tgl_masuk_cover)->translatedFormat('l d M Y H:i');
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
                    $historyData = DB::table('pracetak_cover_history')->where('pracetak_cover_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('penerbitan/pracetak/designer/detail?pra=' . $data->id . '&kode=' . $data->kode) . '"
                        class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                        <div><i class="fas fa-envelope-open-text"></i></div></a>';
                    switch ($data->jalur_buku) {
                        case 'Reguler':
                            $jb = 'reguler';
                            $btn = $this->logicPermissionAction($data->status, $jb, $data->id, $data->kode, $data->judul_final, $btn);
                            break;
                        case 'MoU':
                            $jb = 'mou';
                            $btn = $this->logicPermissionAction($data->status, $jb, $data->id, $data->kode, $data->judul_final, $btn);
                            break;
                        case 'SMK/NonSMK':
                            $jb = 'smk';
                            $btn = $this->logicPermissionAction($data->status, $jb, $data->id, $data->kode, $data->judul_final, $btn);
                            break;
                        default:
                            $jb = 'MoU-Reguler';
                            $btn = $this->logicPermissionAction($data->status, $jb, $data->id, $data->kode, $data->judul_final, $btn);
                            break;
                    }
                    return $btn;
                    return $btn;
                })
                ->rawColumns([
                    'kode',
                    'judul_final',
                    'penulis',
                    'nama_pena',
                    'jalur_buku',
                    'tgl_masuk_cover',
                    'pic_prodev',
                    'proses_saat_ini',
                    'history',
                    'action'
                ])
                ->make(true);
        }
        $data = DB::table('pracetak_cover as pc')
            ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->whereNull('dp.deleted_at')
            ->select(
                'pc.*',
                'pn.kode',
                'pn.pic_prodev',
                'pn.jalur_buku',
                'dp.naskah_id',
                'dp.judul_final'
            )
            ->orderBy('pc.tgl_masuk_cover', 'ASC')
            ->get();
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_cover WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        $statusAction = Arr::except($statusProgress, ['4']);
        $statusProgress = Arr::sort($statusProgress);

        return view('penerbitan.pracetak_desainer.index', [
            'title' => 'Pracetak Desainer',
            'status_action' => $statusAction,
            'status_progress' => $statusProgress,
            'count' => count($data)
        ]);
    }
    protected function logicPermissionAction($status = null, $jb = null, $id, $kode, $judul_final, $btn)
    {
        switch ($jb) {
            case 'MoU-Reguler':
                $gate = Gate::allows('do_create', 'otorisasi-kabag-prades-reguler') || Gate::allows('do_create', 'otorisasi-kabag-prades-mou');
                break;
            default:
                $gate = Gate::allows('do_create', 'otorisasi-kabag-prades-' . $jb);
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
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-designer" style="background:#34395E;color:white" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusPracetakDesainer" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Pending':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-designer" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusPracetakDesainer" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Proses':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-designer" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusPracetakDesainer" title="Update Status">
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
        $btn .= '<a href="' . url('penerbitan/pracetak/designer/edit?cover=' . $id . '&kode=' . $kode) . '"
                class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                <div><i class="fas fa-edit"></i></div></a>';
        return $btn;
    }
    public function editDesigner(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    DB::beginTransaction();
                    $history = DB::table('pracetak_cover as pc')
                        ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                        ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                        ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                        ->where('pc.id', $request->id)
                        ->select('pc.*', 'dp.naskah_id','dp.judul_final','pn.kode')
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
                        if (is_null($history->selesai_pengajuan_cover) || is_null($history->selesai_cover) || is_null($history->selesai_koreksi)) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Belum melakukan proses pengajuan cover/desain back cover/koreksi!'
                            ]);
                        }
                        if ($history->status == 'Proses') {
                            $cekTidakKosong = DB::table('pracetak_setter')
                                ->where('id', $request->id_praset)
                                ->where('status', 'Selesai')
                                ->first();
                            if (!is_null($cekTidakKosong)) {
                                $id_turcet = Uuid::uuid4()->toString();
                                $turcet = [
                                    'params' => 'Insert Turun Cetak',
                                    'id' => $id_turcet,
                                    'pracetak_cover_id' => $history->id,
                                    'pracetak_setter_id' => $request->id_praset,
                                    'tgl_masuk' => $tgl,
                                ];
                                event(new DesturcetEvent($turcet));
                                //INSERT TIMELINE TURUN CETAK
                                $insertTimelinePracov = [
                                    'params' => 'Insert Timeline',
                                    'id' => Uuid::uuid4()->toString(),
                                    'progress' => 'Deskripsi Turun Cetak',
                                    'naskah_id' => $history->naskah_id,
                                    'tgl_mulai' => $tgl,
                                    'url_action' => urlencode(URL::to('/penerbitan/deskripsi/turun-cetak/detail?desc=' . $id_turcet . '&kode=' . $history->kode)),
                                    'status' => 'Antrian'
                                ];
                                event(new TimelineEvent($insertTimelinePracov));
                            }
                            DB::table('pracetak_cover')->where('id', $history->id)->update([
                                'turun_cetak' => $tgl,
                                'status' => 'Selesai',
                            ]);
                        }
                    }
                    $update = [
                        'params' => 'Edit Pracetak Desainer',
                        'id' => $request->id,
                        'catatan' => $request->catatan,
                        'desainer' => json_encode($request->desainer),
                        'korektor' => $request->has('korektor') ? json_encode($request->korektor) : null,
                        'proses_saat_ini' => $request->proses_saat_ini,
                        'bulan' => Carbon::createFromDate($request->bulan)
                    ];
                    event(new PracetakCoverEvent($update));
                    $insert = [
                        'params' => 'Insert History Edit Pracetak Desainer',
                        'pracetak_cover_id' => $request->id,
                        'type_history' => 'Update',
                        'desainer_his' => $history->desainer == json_encode(array_filter($request->desainer)) ? null : $history->desainer,
                        'desainer_new' => $history->desainer == json_encode(array_filter($request->desainer)) ? null : json_encode(array_filter($request->desainer)),
                        'korektor_his' => $history->korektor == $korektor ? null : $history->korektor,
                        'korektor_new' => $history->korektor == $korektor ? null : $korektor,
                        'catatan_his' => $history->catatan == $request->catatan ? null : $history->catatan,
                        'catatan_new' => $history->catatan == $request->catatan ? null : $request->catatan,
                        'bulan_his' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $history->bulan,
                        'bulan_new' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : Carbon::createFromDate($request->bulan),
                        'proses_ini_his' => $history->proses_saat_ini == $request->proses_saat_ini ? null : $history->proses_saat_ini,
                        'proses_ini_new' => $history->proses_saat_ini == $request->proses_saat_ini ? null : $request->proses_saat_ini,
                        'author_id' => auth()->id(),
                        'modified_at' => $tgl
                    ];
                    event(new PracetakCoverEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data proses desain berhasil ditambahkan',
                        'route' => route('prades.view')
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
        $id = $request->get('cover');
        $kodenaskah = $request->get('kode');
        $data = DB::table('pracetak_cover as pc')
            ->join('deskripsi_cover as dc', 'pc.deskripsi_cover_id', '=', 'dc.id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('pracetak_setter as ps', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('pc.id', $id)
            ->where('pn.kode', $kodenaskah)
            ->select(
                'pc.*',
                'df.sub_judul_final',
                'df.bullet',
                'df.sinopsis',
                'dc.des_front_cover',
                'dc.des_back_cover',
                'dc.finishing_cover',
                'dc.jilid',
                'dc.tipografi',
                'dc.warna',
                'dc.contoh_cover',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.nama_pena',
                'dp.imprint',
                'dp.format_buku',
                'pn.kode',
                'pn.jalur_buku',
                'pn.pic_prodev',
                'kb.nama',
                'ps.id as id_praset'

            )
            ->first();
        is_null($data) ? abort(404) :
            $desainer = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Design%')
            ->where('d.nama', 'LIKE', '%Penerbitan%')
            ->select('u.nama', 'u.id')
            ->orderBy('u.nama', 'Asc')
            ->get();
        if (!is_null($data->desainer)) {
            foreach (json_decode($data->desainer) as $e) {
                $namaDesainer[] = DB::table('users')->where('id', $e)->first()->nama;
            }
            $korektor = DB::table('users as u')
                ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
                ->where('j.nama', 'LIKE', '%Design%')
                ->where('d.nama', 'LIKE', '%Penerbitan%')
                ->whereNotIn('u.id', json_decode($data->desainer))
                ->orWhere('j.nama', 'LIKE', '%Korektor%')
                ->select('u.nama', 'u.id')
                ->orderBy('u.nama', 'Asc')
                ->get();
        } else {
            $namaDesainer = null;
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
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_cover WHERE Field = 'proses_saat_ini'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $prosesSaatIni = explode("','", $matches[1]);
        $prosesFilter = Arr::except($prosesSaatIni, ['1', '4', '6', '9']);
        $nama_imprint = '-';
        if (!is_null($data->imprint)) {
            $nama_imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id',$data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        return view('penerbitan.pracetak_desainer.edit', [
            'title' => 'Pracetak Setter Proses',
            'data' => $data,
            'desainer' => $desainer,
            'nama_desainer' => $namaDesainer,
            'korektor' => $korektor,
            'nama_korektor' => $namakorektor,
            'proses_saat_ini' => $prosesFilter,
            'penulis' => $penulis,
            'nama_imprint' => $nama_imprint,
            'format_buku' => $format_buku,
        ]);
    }
    public function detailPracetakDesainer(Request $request)
    {
        $id = $request->get('pra');
        $kode = $request->get('kode');
        $data = DB::table('pracetak_cover as pc')
            ->join('deskripsi_cover as dc', 'pc.deskripsi_cover_id', '=', 'dc.id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('pracetak_setter as ps', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('pc.id', $id)
            ->where('pn.kode', $kode)
            ->select(
                'pc.*',
                'df.sub_judul_final',
                'df.bullet',
                'df.sinopsis',
                'dc.des_front_cover',
                'dc.des_back_cover',
                'dc.finishing_cover',
                'dc.jilid',
                'dc.tipografi',
                'dc.warna',
                'dc.contoh_cover',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.nama_pena',
                'dp.imprint',
                'dp.format_buku',
                'pn.kode',
                'pn.jalur_buku',
                'pn.pic_prodev',
                'kb.nama',

            )
            ->first();
        if (is_null($data)) {
            return abort(404);
        }
        $proofRevisi = DB::table('pracetak_cover_proof')->where('pracetak_cover_id', $id)->where('type_action', 'Revisi')->get();
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.id', 'pp.nama')
            ->get();
        $pic = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')->first();
        if (!is_null($data->desainer)) {
            foreach (json_decode($data->desainer, true) as $set) {
                $namaDesainer[] = DB::table('users')->where('id', $set)->first();
            }
        } else {
            $namaDesainer = null;
        }
        if (!is_null($data->korektor)) {
            foreach (json_decode($data->korektor, true) as $kor) {
                $namaKorektor[] = DB::table('users')->where('id', $kor)->first();
            }
        } else {
            $namaKorektor = null;
        }
        if (is_null($data->selesai_pengajuan_cover)) {
            $label = "desainer";
            $dataRole  = $data->desainer;
        } elseif (is_null($data->selesai_cover)) {
            $label = "desainer";
            $dataRole  = $data->desainer;
        } else {
            $label = "korektor";
            $dataRole  = $data->korektor;
        }
        $doneProses = DB::table('pracetak_cover_selesai')
            ->where('type', ucfirst($label))
            ->where('pracetak_cover_id', $data->id)
            ->where('users_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->first();
        switch ($label) {
            case 'desainer':
                if (is_null($doneProses)) {
                    $result = FALSE;
                } else {
                    if (is_null($data->selesai_pengajuan_cover) || is_null($data->selesai_cover)) {
                        switch ($doneProses->section) {
                            case 'Pengajuan Cover':
                                $result = $data->proses_saat_ini == 'Desain Revisi' ? TRUE : FALSE;
                                break;
                            case 'Back Cover Design Revision':
                                $lastKoreksi = DB::table('pracetak_cover_selesai')
                                    ->where('type', 'Korektor')
                                    ->where('section', 'Koreksi')
                                    ->where('pracetak_cover_id', $data->id)
                                    ->orderBy('id', 'desc')
                                    ->first();
                                if ($lastKoreksi->tahap > 1) {
                                    // $tahapKoreksi = $lastKoreksi->tahap + 1;
                                    $doneSelf = DB::table('pracetak_cover_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Back Cover Design')
                                        ->where('tahap', $lastKoreksi->tahap)
                                        ->where('pracetak_cover_id', $data->id)
                                        ->get();
                                    if (!$doneSelf->isEmpty()) {

                                        foreach ($doneSelf as $d) {
                                            $userId[] = $d->users_id;
                                        }
                                        if (in_array(auth()->user()->id, $userId)) {
                                            $result = TRUE;
                                        } else {
                                            $tahap = $doneProses->tahap + 1;
                                            $doneNext = DB::table('pracetak_cover_selesai')
                                                ->where('type', ucfirst($label))
                                                ->where('section', 'Back Cover Design')
                                                ->where('tahap', $tahap)
                                                ->where('pracetak_cover_id', $data->id)
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
                                    $doneSelf = DB::table('pracetak_cover_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Back Cover Design')
                                        ->where('tahap', $doneProses->tahap)
                                        ->where('pracetak_cover_id', $data->id)
                                        ->get();
                                    if (!$doneSelf->isEmpty()) {

                                        foreach ($doneSelf as $d) {
                                            $userId[] = $d->users_id;
                                        }
                                        if (in_array(auth()->user()->id, $userId)) {

                                            $result = TRUE;
                                        } else {
                                            $tahap = $doneProses->tahap + 1;
                                            $doneNext = DB::table('pracetak_cover_selesai')
                                                ->where('type', ucfirst($label))
                                                ->where('section', 'Back Cover Design')
                                                ->where('tahap', $tahap)
                                                ->where('pracetak_cover_id', $data->id)
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
                    if (is_null($data->selesai_koreksi) && !is_null($data->selesai_cover)) {
                        $lastDesign = DB::table('pracetak_cover_selesai')
                            ->where('type', 'Desainer')
                            ->where('pracetak_cover_id', $data->id)
                            ->orderBy('id', 'desc')
                            ->first();
                        if (($lastDesign->tahap >= 1) && ($lastDesign->section == 'Back Cover Design Revision')) {
                            $doneSelf = DB::table('pracetak_cover_selesai')
                                ->where('type', ucfirst($label))
                                ->where('section', 'Koreksi')
                                ->where('tahap', $lastDesign->tahap + 1)
                                ->where('pracetak_cover_id', $data->id)
                                ->get();
                            if (!$doneSelf->isEmpty()) {
                                foreach ($doneSelf as $d) {
                                    $userId[] = $d->users_id;
                                }
                                if (in_array(auth()->user()->id, $userId)) {
                                    $result = TRUE;
                                } else {
                                    $tahap = $doneProses->tahap + 1;
                                    $doneNext = DB::table('pracetak_cover_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Koreksi')
                                        ->where('tahap', $tahap)
                                        ->where('pracetak_cover_id', $data->id)
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
                            $doneSelf = DB::table('pracetak_cover_selesai')
                                ->where('type', ucfirst($label))
                                ->where('section', 'Koreksi')
                                ->where('tahap', $doneProses->tahap)
                                ->where('pracetak_cover_id', $data->id)
                                ->get();
                            if (!$doneSelf->isEmpty()) {
                                foreach ($doneSelf as $d) {
                                    $userId[] = $d->users_id;
                                }
                                if (in_array(auth()->user()->id, $userId)) {
                                    $result = TRUE;
                                } else {
                                    $tahap = $doneProses->tahap + 1;
                                    $doneNext = DB::table('pracetak_cover_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Koreksi')
                                        ->where('tahap', $tahap)
                                        ->where('pracetak_cover_id', $data->id)
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
            $imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id',$data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        return view('penerbitan.pracetak_desainer.detail', [
            'title' => 'Detail Pracetak Cover',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'nama_desainer' => $namaDesainer,
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
                    return $this->prosesKerjaDesigner($request);
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
                    return $this->lihatHistoryPrades($request);
                    break;
                case 'lihat-informasi-proof':
                    return $this->lihatInformasiProof($request);
                    break;
                case 'lihat-proses-deskor':
                    return $this->lihatProgressDesainerKorektor($request);
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
    protected function prosesKerjaDesigner($request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->id;
                $value = $request->proses;
                $data = DB::table('pracetak_cover')->where('id', $id)->first();
                if (is_null($data)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'naskah sudah tidak ada'
                    ]);
                }
                if ($value == '0') {
                    if (is_null($data->selesai_pengajuan_cover) || is_null($data->selesai_cover)) {
                        $part = is_null($data->selesai_pengajuan_cover) ? 'pengajuan_cover' : 'cover';
                        $mulai = 'mulai_' . $part;
                        $dataProgress = [
                            'params' => 'Progress Designer-Korektor',
                            'label' => $part,
                            'id' => $id,
                            $mulai => NULL,
                            'proses' => $value,
                            'proses_saat_ini' => NULL,
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                    } else {
                        $dataProgress = [
                            'params' => 'Progress Designer-Korektor',
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
                    event(new PracetakCoverEvent($dataProgress));
                    $msg = 'Proses diberhentikan!';
                } else {
                    if (is_null($data->proses_saat_ini)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Pilih proses saat ini!'
                        ]);
                    }
                    switch ($data->proses_saat_ini) {
                        case 'Antrian Pengajuan Desain':
                            if (!is_null($data->selesai_pengajuan_cover)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses pengajuan cover selesai, silahkan ubah proses saat ini menjadi "Approval Prodev".'
                                ]);
                            }
                            break;
                        case 'Antrian Desain Back Cover':
                            if (!is_null($data->selesai_cover)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses back cover selesai, silahkan ubah proses saat ini menjadi "Antrian Koreksi".'
                                ]);
                            }
                            break;
                        case 'Antrian Koreksi':
                            if (is_null($data->selesai_pengajuan_cover)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses pengajuan cover belum selesai. Belum bisa melakukan proses "Antrian Koreksi".'
                                ]);
                            }
                            if (is_null($data->selesai_cover)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses back cover belum selesai. Belum bisa melakukan proses "Antrian Koreksi".'
                                ]);
                            }
                            if (is_null($data->mulai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses proof belum dilakukan, silahkan ubah proses saat ini menjadi "Approval Prodev".'
                                ]);
                            }
                            if (!is_null($data->selesai_koreksi)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses koreksi selesai, silahkan ubah proses saat ini!.'
                                ]);
                            }
                            break;
                        case 'Approval Prodev':
                            if (!is_null($data->selesai_cover)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses approval telah berakhir, silahkan ubah proses saat ini ke proses selanjutnya.'
                                ]);
                            }
                            if (is_null($data->selesai_pengajuan_cover)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses pengajuan cover belum selesai. Belum bisa melakukan proses "Approval Prodev".'
                                ]);
                            }
                            if (!is_null($data->selesai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses approval prodev selesai, silahkan ubah proses saat ini menjadi proses selanjutnya atau mengubah status proses menjadi selesai.'
                                ]);
                            }
                            if (!is_null($data->mulai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses approval prodev sudah dimulai.'
                                ]);
                            }
                            break;
                    }
                    if ((!is_null($data->selesai_koreksi)) && ($data->proses_saat_ini == 'Desain Revisi')) {
                        DB::table('pracetak_cover')->where('id', $id)->update([
                            'selesai_cover' => NULL
                        ]);
                        if (!$request->has('desainer')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih desainer terlebih dahulu!'
                            ]);
                        } else {
                            $tglCover = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            $dataCover = [
                                'params' => 'Progress Designer-Korektor',
                                'label' => 'cover',
                                'id' => $id,
                                'mulai_cover' => $tglCover,
                                'proses' => $value,
                                'proses_saat_ini' => 'Desain Revisi',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakCoverEvent($dataCover));
                        }
                    } elseif (is_null($data->selesai_pengajuan_cover)) {
                        if (!$request->has('desainer')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih desainer terlebih dahulu!'
                            ]);
                        } else {
                            if (json_decode($data->desainer, true) == $request->desainer) {
                                if (is_null($data->mulai_pengajuan_cover)) {
                                    $tglPengajuan = Carbon::now('Asia/Jakarta')->toDateTimeString();
                                } else {
                                    $tglPengajuan = $data->mulai_pengajuan_cover;
                                }
                            } else {
                                $tglPengajuan = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            }
                            $dataPengajuan = [
                                'params' => 'Progress Designer-Korektor',
                                'label' => 'pengajuan_cover',
                                'id' => $id,
                                'mulai_pengajuan_cover' => $tglPengajuan,
                                'proses' => $value,
                                'proses_saat_ini' => 'Pengajuan Desain',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakCoverEvent($dataPengajuan));
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
                        event(new PracetakCoverEvent($dataProof));
                    } elseif (is_null($data->selesai_cover)) {
                        if (!$request->has('desainer')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih desainer terlebih dahulu!'
                            ]);
                        } else {
                            if (json_decode($data->desainer, true) == $request->desainer) {
                                if (is_null($data->mulai_cover)) {
                                    $tglPengajuan = Carbon::now('Asia/Jakarta')->toDateTimeString();
                                } else {
                                    $tglPengajuan = $data->mulai_cover;
                                }
                            } else {
                                $tglPengajuan = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            }
                            $dataPengajuan = [
                                'params' => 'Progress Designer-Korektor',
                                'label' => 'cover',
                                'id' => $id,
                                'mulai_cover' => $tglPengajuan,
                                'proses' => $value,
                                'proses_saat_ini' => 'Desain Back Cover',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakCoverEvent($dataPengajuan));
                        }
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
                                'params' => 'Progress Designer-Korektor',
                                'label' => 'koreksi',
                                'id' => $id,
                                'mulai_koreksi' => $tglKorektor,
                                'proses' => $value,
                                'proses_saat_ini' => 'Koreksi',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakCoverEvent($dataKorektor));
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
            $data = DB::table('pracetak_cover as pc')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('deskripsi_final as df', 'df.deskripsi_produk_id', '=', 'dp.id')
                ->join('pracetak_setter as ps', 'ps.deskripsi_final_id', '=', 'df.id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                    $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                        ->whereNull('kb.deleted_at');
                })
                ->where('pc.id', $id)
                ->select(
                    'pc.*',
                    'dc.id as deskripsi_cover_id',
                    'dp.naskah_id',
                    'dp.format_buku',
                    'dp.judul_final',
                    // 'dp.editor',
                    'dp.imprint',
                    'dp.kelengkapan',
                    'dp.catatan',
                    'pn.kode',
                    'pn.judul_asli',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'kb.nama',
                    'ps.id as praset_id'
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
                'params' => 'Update Status Pracetak Desainer',
                'id' => $data->id,
                'turun_cetak' => $request->status == 'Selesai' ? $tgl : NULL,
                'status' => $request->status,
            ];
            $insert = [
                'params' => 'Insert History Status Pracetak Desainer',
                'pracetak_cover_id' => $data->id,
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
                if (is_null($data->selesai_pengajuan_cover)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses pengajuan cover belum selesai diajukan'
                    ]);
                }
                if (is_null($data->selesai_proof_cover)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses proof prodev belum selesai'
                    ]);
                }
                if (is_null($data->selesai_cover)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses back cover belum selesai'
                    ]);
                }
                if (is_null($data->selesai_koreksi)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses koreksi belum selesai'
                    ]);
                }
                event(new PracetakCoverEvent($update));
                event(new PracetakCoverEvent($insert));
                //UPDATE TIMELINE PRACETAK COVER
                $updateTimelinePracetakCover = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Pracetak Cover',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelinePracetakCover));
                $dataPraset = DB::table('pracetak_setter')
                    ->where('id', $data->praset_id)
                    ->where('status', 'Selesai')
                    ->first();
                if (!is_null($dataPraset)) {
                    DB::table('pracetak_cover')->where('id', $data->id)->update([
                        'proses_saat_ini' => 'Turun Cetak'
                    ]);
                    //Insert Deskripsi Turun Cetak
                    $id_turcet = Uuid::uuid4()->toString();
                    $in = [
                        'params' => 'Insert Turun Cetak',
                        'id' => $id_turcet,
                        'pracetak_cover_id' => $data->id,
                        'pracetak_setter_id' => $data->praset_id,
                        'tgl_masuk' => $tgl,
                    ];
                    event(new DesturcetEvent($in));
                    //INSERT TIMELINE TURUN CETAK
                    $insertTimelinePracov = [
                        'params' => 'Insert Timeline',
                        'id' => Uuid::uuid4()->toString(),
                        'progress' => 'Deskripsi Turun Cetak',
                        'naskah_id' => $data->naskah_id,
                        'tgl_mulai' => $tgl,
                        'url_action' => urlencode(URL::to('/penerbitan/deskripsi/turun-cetak/detail?desc=' . $id_turcet . '&kode=' . $data->kode)),
                        'status' => 'Antrian'
                    ];
                    event(new TimelineEvent($insertTimelinePracov));
                    $msg = 'Pracetak Cover selesai, silahkan lanjut ke proses deskripsi turun cetak..';
                } else {
                    $msg = 'Pracetak Cover selesai, silahkan selesaikan proses pracetak setter..';
                }
            } else {
                event(new PracetakCoverEvent($update));
                event(new PracetakCoverEvent($insert));
                //UPDATE TIMELINE PRACETAK COVER
                $updateTimelinePracetakCover = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Pracetak Cover',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelinePracetakCover));
                $msg = 'Status progress pracetak cover berhasil diupdate';
            }
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
    protected function lihatHistoryPrades($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pracetak_cover_history as pch')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'pch.pracetak_cover_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('users as u', 'pch.author_id', '=', 'u.id')
                ->where('pch.pracetak_cover_id', $id)
                ->select('pch.*', 'dp.judul_final', 'u.nama')
                ->orderBy('pch.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status pracetak cover <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
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
                            $html .= ' Status pracetak cover <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.';
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
                            $html .= ' Status pracetak cover <b class="text-dark">' . $d->status_new . '</b> dengan keterangan revisi: <b class="text-dark">' . $d->ket_revisi . '</b>.';
                            $html .= '</span></div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                        }
                        if (!is_null($d->desainer_his)) {
                            $html .= '<span class="ticket-item">
                            <div class="ticket-title"><span><span class="bullet"></span>';
                            $loopDesainerHIS = '';
                            $loopDesainNEW = '';
                            foreach (json_decode($d->desainer_his, true) as $sethis) {
                                $loopDesainerHIS .= '<b class="text-dark">' . DB::table('users')->where('id', $sethis)->first()->nama . '</b>, ';
                            }
                            foreach (json_decode($d->desainer_new, true) as $setnew) {
                                $loopDesainNEW .= '<span class="bullet"></span>' . DB::table('users')->where('id', $setnew)->first()->nama;
                            }
                            $html .= ' Desainer <b class="text-dark">' . $loopDesainerHIS . '</b> diubah menjadi <b class="text-dark">' . $loopDesainNEW . '</b>.<br>';
                            $html .= '</span></div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                        } elseif (!is_null($d->desainer_new)) {
                            $html .= '<span class="ticket-item">
                            <div class="ticket-title"><span><span class="bullet"></span>';
                            $loopDesainNEW = '';
                            foreach (json_decode($d->desainer_new, true) as $setnew) {
                                $loopDesainNEW .= '<b class="text-dark">' . DB::table('users')->where('id', $setnew)->first()->nama . '</b>, ';
                            }
                            $html .= ' Desainer <b class="text-dark">' . $loopDesainNEW . '</b> ditambahkan.<br>';
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
                            $html .= ' Korektor <b class="text-dark">' . $loopKorHIS . '</b> diubah menjadi <b class="text-dark">' . $loopKorNEW . '</b>.<br>';
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
                        if (!is_null($d->mulai_pengajuan_cover)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Pengajuan desain cover dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_pengajuan_cover)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                        } elseif (!is_null($d->selesai_pengajuan_cover)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Pengajuan desain cover selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_pengajuan_cover)->translatedFormat('l d F Y, H:i') . '</b>.';
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
                            $html .= ' Approval desain cover oleh prodev dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_proof)->translatedFormat('l d F Y, H:i') . '</b>.';
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
                            $html .= ' Desain telah dipilih pada <b class="text-dark">' . Carbon::parse($d->selesai_proof)->translatedFormat('l d F Y, H:i') . '</b>.';
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
                        if (!is_null($d->mulai_cover)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses back cover dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_cover)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                        } elseif (!is_null($d->selesai_cover)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses back cover selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_cover)->translatedFormat('l d F Y, H:i') . '</b>.';
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
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Progress pracetak cover <b class="text-dark">' . $ket . '</b>.</span>
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
            $data = DB::table('pracetak_cover_proof as psp')
                ->join('pracetak_cover as ps', 'ps.id', '=', 'psp.pracetak_cover_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'ps.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('users as u', 'psp.users_id', '=', 'u.id')
                ->where('psp.pracetak_cover_id', $id)
                ->select('psp.*', 'dp.judul_final', 'u.nama')
                ->orderBy('psp.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_action) {
                    case 'Proof':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Prodev menyetujui pengajuan desain.</span>
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
                            <span><span class="bullet"></span> Status pracetak cover <b>Revisi</b> dengan keterangan revisi: <b>' . $d->ket_revisi . '</b>.</span>
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
                            <span><span class="bullet"></span> Desainer telah menyelesaikan revisi dengan persetujuan kabag.</span>
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
    protected function lihatProgressDesainerKorektor($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pracetak_cover_selesai as pss')
                ->join('pracetak_cover as ps', 'ps.id', '=', 'pss.pracetak_cover_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'ps.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('users as u', 'pss.users_id', '=', 'u.id')
                ->where('pss.pracetak_cover_id', $id)
                ->select('pss.*', 'dp.judul_final', 'u.nama')
                ->orderBy('pss.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                $labelDeskor = $d->type == 'Desainer' ? 'Desain' : 'Koreksi';
                switch ($d->section) {
                    case 'Pengajuan Cover':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelDeskor . ' untuk approval oleh prodev telah diselesaikan.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Back Cover Design':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelDeskor . ' cover selesai untuk dikoreksi.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Back Cover Design Revision':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelDeskor . ' hasil revisi tahap ' . $d->tahap . ' dari korektor telah diselesaikan setter.</span>
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
                            <span><span class="bullet"></span> ' . $labelDeskor . ' desain cover tahap ' . $d->tahap . ' oleh korektor telah diselesaikan.</span>
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
            $data = DB::table('pracetak_cover')->where('id', $request->id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
            $rev = [
                'params' => 'Act Prodev',
                'type_user' => 'Desainer',
                'type_action' => 'Revisi',
                'pracetak_cover_id' => $request->id, //?Pracetak Cover, Pracetak Cover Proof, & Pracetak Cover History
                'users_id' => auth()->id(), //?Pracetak Cover Proof & Pracetak Cover History
                'ket_revisi' => $request->ket_revisi, //?Pracetak Cover Proof, & Pracetak Cover History
                'tgl_action' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'selesai_proof' => NULL, //?Pracetak Cover & Pracetak Cover History
                'proses_saat_ini' => 'Desain Revisi', //?Pracetak Cover
                'proses' => '1', //?Pracetak Cover
                'type_history' => 'Update', //? Pracetak Cover History
                'status_his' => $data->status,
                'status' => 'Revisi' //?Pracetak Cover & Pracetak Cover History
            ];
            event(new PracetakCoverEvent($rev));
            return response()->json([
                'status' => 'success',
                'message' => 'Desain cover direvisi dengan keterangan!'
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
            $data = DB::table('pracetak_cover')->where('id', $request->id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $done = [
                'params' => 'Act Prodev',
                'type_user' => 'Desainer',
                'type_action' => 'Proof',
                'pracetak_cover_id' => $request->id, //?Pracetak Cover, Pracetak Cover Proof, & Pracetak Cover History
                'users_id' => auth()->id(), //?Pracetak Cover Proof & Pracetak Cover History
                'ket_revisi' => NULL, //?Pracetak Cover Proof, & Pracetak Cover History
                'tgl_action' => $tgl,
                'selesai_proof' => $tgl, //?Pracetak Cover & Pracetak Cover History
                'proses_saat_ini' => NULL, //?Pracetak Cover
                'proses' => '0', //?Pracetak Cover
                'type_history' => 'Update', //? Pracetak Cover History
                'status_his' => $data->status, //? Pracetak Cover History
                'status' => 'Proses' //?Pracetak Cover & Pracetak Cover History
            ];
            event(new PracetakCoverEvent($done));
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
            $data = DB::table('pracetak_cover')->where('id', $request->id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $done = [
                'params' => 'Act Prodev',
                'type_user' => 'Desainer',
                'type_action' => 'Selesai Revisi',
                'pracetak_cover_id' => $request->id, //?Pracetak Cover, Pracetak Cover Proof, & Pracetak Cover History
                'users_id' => auth()->id(), //?Pracetak Cover Proof & Pracetak Cover History
                'ket_revisi' => NULL, //?Pracetak Cover Proof, & Pracetak Cover History
                'tgl_action' => $tgl,
                'selesai_proof' => NULL, //?Pracetak Cover & Pracetak Cover History
                'proses_saat_ini' => 'Approval Prodev', //?Pracetak Cover
                'proses' => '1', //?Pracetak Cover
                'type_history' => 'Update', //? Pracetak Cover History
                'status_his' => $data->status, //? Pracetak Cover History
                'status' => 'Proses' //?Pracetak Cover & Pracetak Cover History
            ];
            event(new PracetakCoverEvent($done));
            return response()->json([
                'status' => 'success',
                'message' => 'Desain cover telah selesai direvisi!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function prosesSelesaiDesigner($autor, $id)
    {
        switch ($autor) {
            case 'desainer':
                return $this->selesaiDesainer($id);
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
    protected function selesaiDesainer($id)
    {
        try {
            $data = DB::table('pracetak_cover')->where('id', $id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ada'
                ]);
            }
            $dataProsesSelf = DB::table('pracetak_cover_selesai')
                ->where('type', 'Desainer')
                ->where('section', 'Pengajuan Cover')
                ->where('pracetak_cover_id', $data->id)
                ->where('users_id', auth()->id())
                ->first();
            if (!is_null($dataProsesSelf)) {
                $dataProsesBackCover = DB::table('pracetak_cover_selesai')
                    ->where('type', 'Desainer')
                    ->where('section', 'Back Cover Design')
                    ->where('pracetak_cover_id', $data->id)
                    ->where('users_id', auth()->id())
                    ->orderBy('id', 'desc')
                    ->first();
                if (!is_null($dataProsesBackCover)) {
                    $dataProsesBackCoverRevisi = DB::table('pracetak_cover_selesai')
                        ->where('type', 'Desainer')
                        ->where('section', 'Back Cover Design Revision')
                        ->where('pracetak_cover_id', $data->id)
                        ->where('users_id', auth()->id())
                        ->orderBy('id', 'desc')
                        ->first();
                    if (!is_null($dataProsesBackCoverRevisi)) {
                        $tahapSelanjutnya = $dataProsesBackCoverRevisi->tahap + 1;
                        $dataNew = DB::table('pracetak_cover_selesai')
                            ->where('type', 'Desainer')
                            ->where('section', 'Back Cover Design Revision')
                            ->where('tahap', $tahapSelanjutnya)
                            ->where('pracetak_cover_id', $data->id)
                            ->get();
                        $desPros = count($dataNew) + 1;
                        if ($desPros == count(json_decode($data->desainer, true))) {
                            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            $pros = [
                                'params' => 'Proses Desain-Koreksi Selesai',
                                'type' => 'Desainer',
                                'section' => 'Back Cover Design Revision',
                                'tahap' => $tahapSelanjutnya,
                                'pracetak_cover_id' => $data->id,
                                'users_id' => auth()->id(),
                                'tgl_proses_selesai' => $tgl
                            ];
                            event(new PracetakCoverEvent($pros));
                            $done = [
                                'params' => 'Desain-Koreksi Selesai',
                                'label' => 'cover',
                                'id' => $data->id,
                                'selesai_cover' => $tgl,
                                'proses' => '0',
                                'proses_saat_ini' => 'Antrian Koreksi',
                                //Pracetak Desainer History
                                'type_history' => 'Update',
                                'author_id' => auth()->id()
                            ];
                            event(new PracetakCoverEvent($done));
                            DB::table('pracetak_cover')->where('id', $data->id)->update([
                                'selesai_koreksi' => NULL
                            ]);
                        } else {
                            $pros = [
                                'params' => 'Proses Desain-Koreksi Selesai',
                                'type' => 'Desainer',
                                'section' => 'Back Cover Design Revision',
                                'tahap' => $tahapSelanjutnya,
                                'pracetak_cover_id' => $data->id,
                                'users_id' => auth()->id(),
                                'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakCoverEvent($pros));
                        }
                    } else {
                        $dataProses = DB::table('pracetak_cover_selesai')
                            ->where('type', 'Desainer')
                            ->where('section', 'Back Cover Design Revision')
                            ->where('tahap', 1)
                            ->where('pracetak_cover_id', $data->id)
                            ->get();
                        $desPros = count($dataProses) + 1;
                        if ($desPros == count(json_decode($data->desainer, true))) {
                            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            $pros = [
                                'params' => 'Proses Desain-Koreksi Selesai',
                                'type' => 'Desainer',
                                'section' => 'Back Cover Design Revision',
                                'tahap' => 1,
                                'pracetak_cover_id' => $data->id,
                                'users_id' => auth()->id(),
                                'tgl_proses_selesai' => $tgl
                            ];
                            event(new PracetakCoverEvent($pros));
                            $done = [
                                'params' => 'Desain-Koreksi Selesai',
                                'label' => 'cover',
                                'id' => $data->id,
                                'selesai_cover' => $tgl,
                                'proses' => '0',
                                'proses_saat_ini' => 'Antrian Koreksi',
                                //Pracetak Desainer History
                                'type_history' => 'Update',
                                'author_id' => auth()->id()
                            ];
                            event(new PracetakCoverEvent($done));
                            DB::table('pracetak_cover')->where('id', $data->id)->update([
                                'selesai_koreksi' => NULL
                            ]);
                        } else {
                            $pros = [
                                'params' => 'Proses Desain-Koreksi Selesai',
                                'type' => 'Desainer',
                                'section' => 'Back Cover Design Revision',
                                'tahap' => 1,
                                'pracetak_cover_id' => $data->id,
                                'users_id' => auth()->id(),
                                'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakCoverEvent($pros));
                        }
                    }
                } else {
                    $dataProses = DB::table('pracetak_cover_selesai')
                        ->where('type', 'Desainer')
                        ->where('section', 'Back Cover Design')
                        ->where('tahap', 1)
                        ->where('pracetak_cover_id', $data->id)
                        ->get();
                    $desPros = count($dataProses) + 1;
                    if ($desPros == count(json_decode($data->desainer, true))) {
                        $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        $pros = [
                            'params' => 'Proses Desain-Koreksi Selesai',
                            'type' => 'Desainer',
                            'section' => 'Back Cover Design',
                            'tahap' => 1,
                            'pracetak_cover_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_selesai' => $tgl
                        ];
                        event(new PracetakCoverEvent($pros));
                        $done = [
                            'params' => 'Desain-Koreksi Selesai',
                            'label' => 'cover',
                            'id' => $data->id,
                            'selesai_cover' => $tgl,
                            'proses' => '0',
                            'proses_saat_ini' => 'Antrian Koreksi',
                            //Pracetak Desainer History
                            'type_history' => 'Update',
                            'author_id' => auth()->id()
                        ];
                        event(new PracetakCoverEvent($done));
                        DB::table('pracetak_cover')->where('id', $data->id)->update([
                            'selesai_koreksi' => NULL
                        ]);
                    } else {
                        $pros = [
                            'params' => 'Proses Desain-Koreksi Selesai',
                            'type' => 'Desainer',
                            'section' => 'Back Cover Design',
                            'tahap' => 1,
                            'pracetak_cover_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        event(new PracetakCoverEvent($pros));
                    }
                }
            } else {
                $dataProses = DB::table('pracetak_cover_selesai')
                    ->where('type', 'Desainer')
                    ->where('section', 'Pengajuan Cover')
                    ->where('tahap', 1)
                    ->where('pracetak_cover_id', $data->id)
                    ->get();
                $desPros = count($dataProses) + 1;
                if ($desPros == count(json_decode($data->desainer, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Desain-Koreksi Selesai',
                        'type' => 'Desainer',
                        'section' => 'Pengajuan Cover',
                        'tahap' => 1,
                        'pracetak_cover_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new PracetakCoverEvent($pros));
                    $done = [
                        'params' => 'Desain-Koreksi Selesai',
                        'label' => 'pengajuan_cover',
                        'id' => $data->id,
                        'selesai_pengajuan_cover' => $tgl,
                        'proses' => '0',
                        'proses_saat_ini' => NULL,
                        //Pracetak Desainer History
                        'type_history' => 'Update',
                        'author_id' => auth()->id()
                    ];
                    event(new PracetakCoverEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Desain-Koreksi Selesai',
                        'type' => 'Desainer',
                        'section' => 'Pengajuan Cover',
                        'tahap' => 1,
                        'pracetak_cover_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new PracetakCoverEvent($pros));
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
            $data = DB::table('pracetak_cover')->where('id', $id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ada'
                ]);
            }
            $dataProsesSelf = DB::table('pracetak_cover_selesai')
                ->where('type', 'Korektor')
                ->where('section', 'Koreksi')
                ->where('pracetak_cover_id', $data->id)
                ->where('users_id', auth()->id())
                ->orderBy('id', 'desc')
                ->first();

            if (!is_null($dataProsesSelf)) {
                $tahapSelanjutnya = $dataProsesSelf->tahap + 1;
                $dataNew = DB::table('pracetak_cover_selesai')
                    ->where('type', 'Korektor')
                    ->where('section', 'Koreksi')
                    ->where('tahap', $tahapSelanjutnya)
                    ->where('pracetak_cover_id', $data->id)
                    ->get();
                $korPros = count($dataNew) + 1;
                if ($korPros == count(json_decode($data->korektor, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Desain-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => $tahapSelanjutnya,
                        'pracetak_cover_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new PracetakCoverEvent($pros));
                    $done = [
                        'params' => 'Desain-Koreksi Selesai',
                        'label' => 'koreksi',
                        'id' => $data->id,
                        'selesai_koreksi' => $tgl,
                        'proses' => '0',
                        'proses_saat_ini' => 'Desain Revisi',
                        //Pracetak Desain History
                        'type_history' => 'Update',
                        'author_id' => auth()->id()
                    ];
                    event(new PracetakCoverEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Desain-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => $tahapSelanjutnya,
                        'pracetak_cover_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new PracetakCoverEvent($pros));
                }
            } else {
                $dataProses = DB::table('pracetak_cover_selesai')
                    ->where('type', 'Korektor')
                    ->where('section', 'Koreksi')
                    ->where('tahap', 1)
                    ->where('pracetak_cover_id', $data->id)
                    ->get();
                $korPros = count($dataProses) + 1;
                if ($korPros == count(json_decode($data->korektor, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Desain-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => 1,
                        'pracetak_cover_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new PracetakCoverEvent($pros));
                    $done = [
                        'params' => 'Desain-Koreksi Selesai',
                        'label' => 'koreksi',
                        'id' => $data->id,
                        'selesai_koreksi' => $tgl,
                        'proses' => '0',
                        'proses_saat_ini' => 'Desain Revisi',
                        //Pracetak Desainer History
                        'type_history' => 'Update',
                        'author_id' => auth()->id()
                    ];
                    event(new PracetakCoverEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Desain-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => 1,
                        'pracetak_cover_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new PracetakCoverEvent($pros));
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
}
