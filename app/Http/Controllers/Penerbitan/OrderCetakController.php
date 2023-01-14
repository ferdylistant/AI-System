<?php

namespace App\Http\Controllers\Penerbitan;

use App\Events\NotifikasiPenyetujuan;
use App\Events\OrderCetakEvent;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class OrderCetakController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('order_cetak as oc')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->select(
                    'oc.*',
                    'pn.kode',
                    'pn.jalur_buku',
                    'pn.id as naskah_id',
                    'dtc.tipe_order',
                    'dp.judul_final'
                    // 'pyoc.m_penerbitan',
                    // 'pyoc.m_stok',
                    // 'pyoc.d_operasional',
                    // 'pyoc.d_keuangan',
                    // 'pyoc.d_utama',
                    // 'pyoc.m_penerbitan_act',
                    // 'pyoc.m_stok_act',
                    // 'pyoc.d_operasional_act',
                    // 'pyoc.d_keuangan_act',
                    // 'pyoc.d_utama_act',
                    // 'pyoc.pending_sampai',
                )
                ->get();
            $update = Gate::allows('do_update', 'update-produksi');
            return DataTables::of($data)
                ->addColumn('no_order', function ($data) {
                    return $data->kode_order;
                })
                ->addColumn('kode', function ($data) {
                    return $data->kode;
                })
                ->addColumn('tipe_order', function ($data) {
                    $res = $data->tipe_order == 1 ? 'Umum' : 'Rohani';
                    return $res;
                })
                ->addColumn('judul_final', function ($data) {
                    return $data->judul_final;
                })
                ->addColumn('jalur_buku', function ($data) {
                    return $data->jalur_buku;
                })
                ->addColumn('status_penyetujuan', function ($data) {
                    $res = DB::table('order_cetak_action')->where('order_cetak_id', $data->id)->get();
                    if (!$res->isEmpty()) {
                        foreach ($res as $r) {
                            $collect[] = $r->type_jabatan;
                        }
                    }
                    $badge = '';
                    $type = DB::select(DB::raw("SHOW COLUMNS FROM order_cetak_action WHERE Field = 'type_jabatan'"))[0]->Type;
                    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
                    $jabatan = explode("','", $matches[1]);
                    foreach ($jabatan as $j) {
                        if (!$res->isEmpty()) {
                            if (in_array($j, $collect)) {
                                foreach ($res as $action) {
                                    switch ($action->type_action) {
                                        case 'Approval':
                                            $badge .= '<div class="text-success text-small font-600-bold"><i class="bullet"></i> ' . $j . '</div>';
                                            break;
                                        case 'Decline':
                                            $badge .= '<div class="text-danger text-small font-600-bold"><i class="bullet"></i> ' . $j . '</div>';
                                            break;
                                    }
                                }
                            } else {
                                $badge .= '<div class="text-muted text-small font-600-bold"><i class="bullet"></i> ' . $j . '</div>';
                            }
                        } else {
                            $badge .= '<div class="text-muted text-small font-600-bold"><i class="bullet"></i> ' . $j . '</div>';
                        }
                    }
                    return $badge;
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('order_cetak_history')->where('order_cetak_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    $btn = '<a href="' . url('penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode) . '"
                                            class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                            <div><i class="fas fa-envelope-open-text"></i></div></a>';
                    $btn = $this->logicPermissionAction($update, $data->status, $data->id, $data->kode, $data->judul_final, $btn);
                    return $btn;
                })
                ->rawColumns([
                    'no_order',
                    'kode',
                    'tipe_order',
                    'judul_final',
                    'jalur_buku',
                    'status_penyetujuan',
                    'history',
                    'action'
                ])
                ->make(true);
        }
        $data = DB::table('order_cetak as oc')
            ->orderBy('tgl_masuk', 'ASC')
            ->get();
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM order_cetak WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        return view('penerbitan.order_cetak.index', [
            'title' => 'Order Cetak Naskah',
            'count' => count($data),
            'status_progress' => $statusProgress
        ]);
    }
    public function updateOrderCetak(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('order_cetak as oc')
                        ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
                        ->join('pilihan_penerbitan as pp', 'pp.deskripsi_turun_cetak_id', '=', 'dtc.id')
                        ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                        ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                        ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                        ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                        ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                        ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                            $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                                ->whereNull('kb.deleted_at');
                        })
                        ->where('oc.id', $request->id)
                        ->select(
                            'oc.*',
                            'dtc.tipe_order',
                            'pp.platform_digital_ebook_id',
                            'df.sub_judul_final',
                            'dp.judul_final',
                            'dp.format_buku',
                            'dp.imprint',
                            'dp.jml_hal_perkiraan',
                            'kb.id as kelompok_buku_id',
                            'pn.id as naskah_id',
                            'pn.jalur_buku',
                            'ps.edisi_cetak'
                        )
                        ->first();
                    if (is_null($history)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Terjadi kesalahan!'
                        ]);
                    }

                    $update = [
                        'params' => 'Update Order Cetak',
                        'id' => $request->id,
                        'tipe_order' => $request->up_tipe_order, //Deskripsi Turcet
                        'edisi_cetak' => $request->up_edisi_cetak, //Pracetak Setter
                        'jml_hal_perkiraan' => $request->up_jml_hal_perkiraan, //Deskripsi Produk
                        'kelompok_buku_id' => $request->up_kelompok_buku, //Penerbitan Naskah
                        'tahun_terbit' => Carbon::createFromFormat('Y', $request->up_tahun_terbit)->format('Y'),
                        'tgl_upload' => Carbon::createFromFormat('d F Y', $request->up_tgl_upload)->format('Y-m-d H:i:s'),
                        'spp' => $request->up_spp,
                        'keterangan' => $request->up_keterangan,
                        'perlengkapan' => $request->up_perlengkapan,
                    ];
                    event(new OrderCetakEvent($update));
                    $insert = [
                        'params' => 'Insert History Update Order Cetak',
                        'type_history' => 'Update',
                        'order_cetak_id' => $request->id,
                        'tipe_order_his' => $request->up_tipe_order == $history->tipe_order ? NULL : $history->tipe_order,
                        'tipe_order_new' => $request->up_tipe_order == $history->tipe_order ? NULL : $request->tipe_order,
                        'edisi_cetak_his' => $request->up_edisi_cetak == $history->edisi_cetak ? NULL : $history->edisi_cetak,
                        'edisi_cetak_new' => $request->up_edisi_cetak == $history->edisi_cetak ? NULL : $request->edisi_cetak,
                        'jml_hal_perkiraan_his' => $request->up_jml_hal_perkiraan == $history->jml_hal_perkiraan ? NULL : $history->jml_hal_perkiraan,
                        'jml_hal_perkiraan_new' => $request->up_jml_hal_perkiraan == $history->jml_hal_perkiraan ? NULL : $request->jml_hal_perkiraan,
                        'kelompok_buku_id_his' => $request->up_kelompok_buku == $history->kelompok_buku_id ? NULL : $history->kelompok_buku_id,
                        'kelompok_buku_id_new' => $request->up_kelompok_buku == $history->kelompok_buku_id ? NULL : $request->kelompok_buku,
                        'tahun_terbit_his' => date('Y-m', strtotime($request->up_tahun_terbit)) == date('Y-m', strtotime($history->tahun_terbit)) ? NULL : $history->tahun_terbit,
                        'tahun_terbit_new' => date('Y-m', strtotime($request->up_tahun_terbit)) == date('Y-m', strtotime($history->tahun_terbit)) ? NULL : Carbon::createFromFormat('Y', $request->up_tahun_terbit)->format('Y'),
                        'tgl_upload_his' => date('Y-m-d H:i:s', strtotime($request->up_tgl_upload)) == date('Y-m-d H:i:s', strtotime($history->tgl_upload)) ? NULL : date('Y-m-d H:i:s', strtotime($history->tgl_upload)),
                        'tgl_upload_new' => date('Y-m-d H:i:s', strtotime($request->up_tgl_upload)) == date('Y-m-d H:i:s', strtotime($history->tgl_upload)) ? NULL : Carbon::createFromFormat('d F Y', $request->up_tgl_upload)->format('Y-m-d H:i:s'),
                        'spp_his' => $request->up_spp == $history->spp ? NULL : $history->spp,
                        'spp_new' => $request->up_spp == $history->spp ? NULL : $request->spp,
                        'keterangan_his' => $request->up_keterangan == $history->keterangan ? NULL : $history->keterangan,
                        'keterangan_new' => $request->up_keterangan == $history->keterangan ? NULL : $request->keterangan,
                        'perlengkapan_his' => $request->up_perlengkapan == $history->perlengkapan ? NULL : $history->perlengkapan,
                        'perlengkapan_new' => $request->up_perlengkapan == $history->perlengkapan ? NULL : $request->perlengkapan,
                        'eisbn_his' => $request->up_eisbn == $history->eisbn ? NULL : $history->eisbn,
                        'eisbn_new' => $request->up_eisbn == $history->eisbn ? NULL : $request->eisbn,
                        'author_id' => auth()->id(),
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new OrderCetakEvent($insert));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data order cetak berhasil diubah'
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
        $id = $request->get('order');
        $naskah = $request->get('naskah');
        $data = DB::table('order_cetak as oc')
            ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
            ->join('pilihan_penerbitan as pp', 'pp.deskripsi_turun_cetak_id', '=', 'dtc.id')
            ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
            ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
            ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('oc.id', $id)
            ->where('pn.kode', $naskah)
            ->select(
                'oc.*',
                'dtc.tipe_order',
                'pp.platform_digital_ebook_id',
                'pp.pilihan_terbit',
                'df.sub_judul_final',
                'dp.judul_final',
                'dp.format_buku',
                'dp.imprint',
                'dp.jml_hal_perkiraan',
                'kb.nama',
                'pn.id as naskah_id',
                'pn.jalur_buku',
                'ps.edisi_cetak'
            )
            ->first();
        $tipeOrd = array(['id' => 1, 'name' => 'Umum'], ['id' => 2, 'name' => 'Rohani']);
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.id', 'pp.nama')
            ->get();
        $penulisList = DB::table('penerbitan_penulis')
            ->whereNull('deleted_at')
            ->get();
        foreach ($penulisList as $pl) {
            $collectPenulis[] = $pl->id;
        }
        $listPilTerbit = ['cetak', 'ebook'];
        $pilihanTerbit = DB::table('pilihan_penerbitan')->get();
        $platformDigital = DB::table('platform_digital_ebook')->whereNull('deleted_at')->get();
        $kbuku = DB::table('penerbitan_m_kelompok_buku')
            ->get();
        return view('penerbitan.order_cetak.edit', [
            'title' => 'Update Order Cetak',
            'tipeOrd' => $tipeOrd,
            'pilihanTerbit' => $pilihanTerbit,
            'list_pilihanterbit' => $listPilTerbit,
            'platformDigital' => $platformDigital,
            'kbuku' => $kbuku,
            'penulis' => $penulis,
            'collect_penulis' => $collectPenulis,
            'data' => $data,
        ]);
    }
    public function detailOrderCetak(Request $request)
    {
        $id = $request->get('order');
        $naskah = $request->get('naskah');
        $data = DB::table('order_cetak as oc')
            ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
            ->join('pilihan_penerbitan as pp', 'pp.deskripsi_turun_cetak_id', '=', 'dtc.id')
            ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
            ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
            ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('oc.id', $id)
            ->where('pn.kode', $naskah)
            ->select(
                'oc.*',
                'dtc.tipe_order',
                'pp.platform_digital_ebook_id',
                'pp.pilihan_terbit',
                'dc.jilid',
                'dc.warna',
                'dc.finishing_cover',
                'df.sub_judul_final',
                'df.isi_warna',
                'df.kertas_isi',
                'dp.judul_final',
                'dp.format_buku',
                'dp.imprint',
                'dp.jml_hal_perkiraan',
                'kb.nama',
                'pn.id as naskah_id',
                'pn.jalur_buku',
                'ps.isbn',
                'ps.jml_hal_final',
                'ps.edisi_cetak'
            )
            ->first();
        if (is_null($data)) {
            return redirect()->route('cetak.view');
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();

        //Data Action
        $act = DB::table('order_cetak_action')->where('order_cetak_id', $data->id)->get();
        if (!$act->isEmpty()) {
            foreach ($act as $a) {
                $act_j[] = $a->type_jabatan;
                //Dirop dan Dirke sudah
                if ($a->type_jabatan == 'Dir. Operasional' || $a->type_jabatan == 'Dir. Keuangan') {
                    $diropDirke = TRUE;
                } else {
                    $diropDirke = FALSE;
                }
            }
        } else {
            $act_j = NULL;
            $diropDirke = NULL;
        }
        //List Jabatan Approval
        $type = DB::select(DB::raw("SHOW COLUMNS FROM order_cetak_action WHERE Field = 'type_jabatan'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $jabatan = explode("','", $matches[1]);

        is_null($data) ? abort(404) :
            $sasaranPasar = DB::table('penerbitan_pn_prodev')
            ->where('naskah_id', $data->naskah_id)
            ->first();
        $sasaran_pasar = is_null($sasaranPasar) ? null : $sasaranPasar->sasaran_pasar;

        return view('penerbitan.order_cetak.detail', [
            'title' => 'Detail Order Cetak Buku',
            'data' => $data,
            'sasaran_pasar' => $sasaran_pasar,
            'penulis' => $penulis,
            'act' => $act,
            'act_j' => $act_j,
            'jabatan' => $jabatan
            // 'id' => $kode,
            // 'prod_penyetujuan' => $prodPenyetujuan,
            // 'data_penolakan' => $dataPenolakan,
            // 'author' => $author,
            // 'm_stok' => $m_stok,
            // 'm_penerbitan' => $m_penerbitan,
            // 'dirop' => $dirop,
            // 'dirke' => $dirke,
            // 'dirut' => $dirut,
            // 'p_mstok' => $p_mstok,
            // 'p_mp' => $p_mp,
            // 'p_dirop' => $p_dirop,
            // 'p_dirke' => $p_dirke,
            // 'p_dirut' => $p_dirut,
        ]);
    }
    protected function logicPermissionAction($update, $status = null, $id, $kode, $judul_final, $btn)
    {
        if ($update) {
            $btn = $this->buttonEdit($id, $kode, $btn);
        } else {
            if ($status == 'Selesai') {
                if (Gate::allows('do_approval', 'approval-deskripsi-produk') || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                    $btn = $this->buttonEdit($id, $kode, $btn);
                }
            }
        }

        if ($update) {
            $btn = $this->panelStatusAdmin($status, $id, $kode, $judul_final, $btn);
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
            default:
                return abort(410);
                break;
        }
        return $btn;
    }
    protected function panelStatusAdmin($status = null, $id, $kode, $judul_final, $btn)
    {
        switch ($status) {
            case 'Antrian':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-orcetak" style="background:#34395E;color:white" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusOrderCetak" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Pending':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-orcetak" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusOrderCetak" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Proses':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-orcetak" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusOrderCetak" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Selesai':
                $btn .= '<span class="d-block badge badge-light mr-1 mt-1">' . $status . '</span>';
                break;
            default:
                return abort(410);
                break;
        }
        return $btn;
    }
    protected function buttonEdit($id, $kode, $btn)
    {
        $btn .= '<a href="' . url('penerbitan/order-cetak/edit?order=' . $id . '&naskah=' . $kode) . '"
        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
        <div><i class="fas fa-edit"></i></div></a>';
        return $btn;
    }
    public function ajaxRequest(Request $request, $cat)
    {
        switch ($cat) {
            case 'approve':
                return $this->approvalOrderCetak($request);
                break;
            case 'decline':
                return $this->declineOrderCetak($request);
                break;
            case 'approve-detail':
                return $this->approvalDetailOrderCetak($request);
                break;
            case 'decline-detail':
                return $this->declineDetailOrderCetak($request);
                break;
            case 'update-status-progress':
                return $this->updateStatusProgress($request);
                break;
            case 'lihat-history-order-cetak':
                return $this->lihatHistoryOrderCetak($request);
                break;
            default:
                abort(500);
        }
    }
    protected function updateStatusProgress($request)
    {
        try {
            $id = $request->id;
            $data = DB::table('order_cetak')
                ->where('id', $id)
                ->select('*')
                ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ], 404);
            }
            $penilaian = DB::table('order_cetak_action as oca')
                ->where('order_cetak_id', $data->id)->where('type_jabatan', 'Dir. Utama')
                ->orderBy('id', 'desc')
                ->first();
            if ($data->status == $request->status) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pilih status yang berbeda dengan status saat ini!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $update = [
                'params' => 'Update Status Order Cetak',
                'id' => $data->id,
                'tgl_selesai_order' => $request->status == 'Selesai' ? $tgl : NULL,
                'status' => $request->status,
            ];
            $insert = [
                'params' => 'Insert History Status Order Cetak',
                'type_history' => 'Status',
                'order_cetak_id' => $data->id,
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            if ($request->status == 'Selesai') {
                if (is_null($penilaian)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Approval belum selesai!'
                    ]);
                }
                if (is_null($data->eisbn)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'E-ISBN belum diinput!'
                    ]);
                }
                if (is_null($data->tgl_upload)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tanggal upload belum diinput!'
                    ]);
                }
                if (is_null($data->spp)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'SPP belum diinput!'
                    ]);
                }
                event(new OrderCetakEvent($update));
                event(new OrderCetakEvent($insert));
                $msg = 'Order Cetak selesai, silahkan lanjut ke proses produksi upload ke platform..';
            } else {
                event(new OrderCetakEvent($update));
                event(new OrderCetakEvent($insert));
                $msg = 'Status progress order cetak berhasil diupdate';
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
    protected function approvalOrderCetak($request)
    {
        try {
            $permission = DB::table('permissions')
                ->where('raw', $request->type_jabatan)
                ->first();
            // return response()->json($request->type_jabatan);
            $insert = [
                'params' => 'Insert Action',
                'order_cetak_id' => $request->id,
                'type_jabatan' => $request->type_jabatan,
                'type_action' => 'Approval',
                'users_id' => auth()->id(),
                'catatan_action' => $request->catatan_action,
                'tgl_action' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            ];
            switch ($request->type_jabatan) {
                case 'GM Penerbitan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Dir. Operasional'];
                    break;
                case 'Dir. Operasional':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Dir. Keuangan'];
                    break;
                case 'Dir. Keuangan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Dir. Utama'];
                    break;
                case 'Dir. Utama':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['GM Penerbitan', 'Dir. Operasional', 'Dir. Keuangan'];
                    break;
            }
            $userPermission = DB::table('permissions as p', 'p.id', '=', 'up.permission_id')
                ->where('p.access_id', $permission->access_id)
                ->whereIn('p.raw', $raw)
                ->get();
            foreach ($userPermission as $up) {
                $userPermissions[] = DB::table('user_permission')->where('permission_id', $up->id)->first();
            }
            $notif = [
                'params' => 'Insert Notif',
                'id' => $id_notif,
                'section' => 'Penerbitan',
                'type' => 'Terima Order E-Book',
                'permission_id' => is_null($permission->id) ? NULL : $permission->id,
                'form_id' => $request->id,
                'users_id' =>  $userPermissions
            ];
            event(new OrderCetakEvent($insert));
            event(new NotifikasiPenyetujuan($notif));
            return response()->json([
                'status' => 'success',
                'message' => 'Approval berhasil dilakukan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function declineOrderCetak($request)
    {
        try {
            $permission = DB::table('permissions')
                ->where('raw', $request->type_jabatan)
                ->first();
                // return response()->json($request->type_jabatan);
            $insert = [
                'params' => 'Insert Action',
                'order_cetak_id' => $request->id,
                'type_jabatan' => $request->type_jabatan,
                'type_action' => 'Decline',
                'users_id' => auth()->id(),
                'catatan_action' => $request->catatan_action,
                'tgl_action' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            ];
            switch ($request->type_jabatan) {
                case 'GM Penerbitan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Dir. Operasional'];
                    break;
                case 'Dir. Operasional':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Dir. Keuangan'];
                    break;
                case 'Dir. Keuangan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Dir. Utama'];
                    break;
                case 'Dir. Utama':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['GM Penerbitan', 'Dir. Operasional', 'Dir. Keuangan'];
                    break;
            }
            $userPermission = DB::table('permissions as p', 'p.id', '=', 'up.permission_id')
                ->where('p.access_id', $permission->access_id)
                ->whereIn('p.raw', $raw)
                ->get();
            foreach ($userPermission as $up) {
                $userPermissions[] = DB::table('user_permission')->where('permission_id', $up->id)->first();
            }
            $notif = [
                'params' => 'Insert Notif',
                'id' => $id_notif,
                'section' => 'Penerbitan',
                'type' => 'Tolak Order E-Book',
                'permission_id' => is_null($permission->id) ? NULL : $permission->id,
                'form_id' => $request->id,
                'users_id' =>  $userPermissions
            ];
            event(new OrderCetakEvent($insert));
            event(new NotifikasiPenyetujuan($notif));
            return response()->json([
                'status' => 'success',
                'message' => 'Penolakan berhasil dilakukan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function approvalDetailOrderCetak($request)
    {
        $id = $request->id;
        $data = DB::table('order_cetak_action')
        ->where('id',$id)
        ->first();
        $fetch = [
            'jabatan' => $data->type_jabatan,
            'users' => DB::table('users')->where('id',$data->users_id)->first()->nama,
            'catatan' => $data->catatan_action,
            'tgl' => Carbon::parse($data->tgl_action)->translatedFormat('l d F Y, H:i')
        ];
        return response()->json($fetch);
    }
    protected function declineDetailOrderCetak($request)
    {
        $id = $request->id;
        $data = DB::table('order_cetak_action')
        ->where('id',$id)
        ->first();
        $fetch = [
            'jabatan' => $data->type_jabatan,
            'users' => DB::table('users')->where('id',$data->users_id)->first()->nama,
            'catatan' => $data->catatan_action,
            'tgl' => Carbon::parse($data->tgl_action)->translatedFormat('l d F Y, H:i')
        ];
        return response()->json($fetch);
    }
    protected function lihatHistoryOrderCetak(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('order_cetak_history as och')
                ->join('order_cetak as dtc', 'dtc.id', '=', 'och.order_cetak_id')
                ->join('users as u', 'u.id', '=', 'och.author_id')
                ->where('och.order_cetak_id', $id)
                ->select('och.*', 'u.nama')
                ->orderBy('och.id', 'desc')
                ->paginate(2);

            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status order cetak <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';

                        if (!is_null($d->edisi_cetak_his)) {
                            $html .= ' Edisi cetak tahun <b class="text-dark">' . $d->edisi_cetak_his . ' </b> diubah menjadi <b class="text-dark">' . $d->edisi_cetak_new . ' </b>.<br>';
                        } elseif (!is_null($d->edisi_cetak_new)) {
                            $html .= ' Edisi cetak tahun <b class="text-dark">' . $d->edisi_cetak_new . ' </b> ditambahkan.<br>';
                        }
                        if (!is_null($d->format_buku_his)) {
                            $html .= ' Format buku <b class="text-dark">' . $d->format_buku_his . ' cm</b> diubah menjadi <b class="text-dark">' . $d->format_buku_new . ' cm</b>.<br>';
                        } elseif (!is_null($d->format_buku_new)) {
                            $html .= ' Format buku <b class="text-dark">' . $d->format_buku_new . ' cm</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->bulan_his)) {
                            $html .= ' Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->bulan_new)->translatedFormat('F Y') . '</b>.';
                        }
                        $html .= '</span></div>
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
}
