<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use App\Events\OrderEbookEvent;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Events\NotifikasiPenyetujuan;
use Illuminate\Support\Facades\{DB, Gate};

class OrderEbookController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('order_ebook as ob')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'ob.deskripsi_turun_cetak_id')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->select(
                    'ob.*',
                    'pn.kode',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'pn.id as naskah_id',
                    'dtc.tipe_order',
                    'dp.judul_final'
                )
                ->get();
            $update = Gate::allows('do_update', 'update-order-ebook');

            return DataTables::of($data)
                ->addColumn('no_order', function ($data) {
                    $act = DB::table('order_ebook_action')
                        ->where('order_ebook_id', $data->id)
                        ->orderBy('id', 'desc')
                        ->first();
                    if (is_null($act)) {
                        if ((Gate::allows('do_approval', 'Penerbitan')) && ($data->status == 'Proses')) {
                            $tandaProses = '<span class="beep-danger"></span>';
                            $dataKode = '<span class="text-danger">' . $data->kode_order . '</span>';
                        } else {
                            $tandaProses = '';
                            $dataKode = $data->kode_order;
                        }
                    } else {
                        switch ($act->type_departemen) {
                            case 'Penerbitan':
                                if ((Gate::allows('do_approval', 'Persetujuan Marketing & Ops')) && ($data->status == 'Proses')) {
                                    $tandaProses = '<span class="beep-danger"></span>';
                                    $dataKode = '<span class="text-danger">' . $data->kode_order . '</span>';
                                } else {
                                    $tandaProses = '';
                                    $dataKode = $data->kode_order;
                                }
                                break;
                            case 'Marketing & Ops':
                                if ((Gate::allows('do_approval', 'Persetujuan Keuangan')) && ($data->status == 'Proses')) {
                                    $tandaProses = '<span class="beep-danger"></span>';
                                    $dataKode = '<span class="text-danger">' . $data->kode_order . '</span>';
                                } else {
                                    $tandaProses = '';
                                    $dataKode = $data->kode_order;
                                }
                                break;
                            case 'Keuangan':
                                if ((Gate::allows('do_approval', 'Persetujuan Direktur Utama')) && ($data->status == 'Proses')) {
                                    $tandaProses = '<span class="beep-danger"></span>';
                                    $dataKode = '<span class="text-danger">' . $data->kode_order . '</span>';
                                } else {
                                    $tandaProses = '';
                                    $dataKode = $data->kode_order;
                                }
                                break;
                            default:
                            $tandaProses = '';
                            $dataKode = $data->kode_order;
                                break;
                        }
                    }

                    return $tandaProses . $dataKode;
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
                    $res = DB::table('order_ebook_action')
                        ->where('order_ebook_id', $data->id)
                        ->get();
                    if (!$res->isEmpty()) {
                        foreach ($res as $r) {
                            $collect[] = $r->type_departemen;
                        }
                    }
                    $badge = '';
                    $type = DB::select(DB::raw("SHOW COLUMNS FROM order_ebook_action WHERE Field = 'type_departemen'"))[0]->Type;
                    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
                    $departemen = explode("','", $matches[1]);
                    foreach ($departemen as $jb => $j) {
                        if (!$res->isEmpty()) {
                            if (in_array($j, $collect)) {
                                foreach ($res as $i => $action) {
                                    if ($i == $jb) {
                                        switch ($action->type_action) {
                                            case 'Approval':
                                                $lbl = 'success';
                                                break;
                                            case 'Decline':
                                                $lbl = 'danger';
                                                break;
                                        }
                                        $badge .= '<div class="text-' . $lbl . ' text-small font-600-bold"><span class="bullet"></span> ' . $j . '</div>';
                                    }
                                }
                            } else {
                                $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> ' . $j . '</div>';
                            }
                        } else {
                            $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> ' . $j . '</div>';
                        }
                    }
                    return $badge;
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('order_ebook_history')->where('order_ebook_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    $btn = '<a href="' . url('penerbitan/order-ebook/detail?order=' . $data->id . '&naskah=' . $data->kode) . '"
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
        $data = DB::table('order_ebook as ps')
            ->orderBy('tgl_masuk', 'ASC')
            ->get();
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM order_ebook WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        return view('penerbitan.order_ebook.index', [
            'title' => 'Order E-Book',
            'count' => count($data),
            'status_progress' => $statusProgress
        ]);
    }
    public function updateOrderEbook(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('order_ebook as oe')
                        ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oe.deskripsi_turun_cetak_id')
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
                        ->where('oe.id', $request->id)
                        ->select(
                            'oe.*',
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
                        'params' => 'Update Order E-book',
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
                        'eisbn' => $request->up_eisbn,
                    ];
                    event(new OrderEbookEvent($update));
                    $insert = [
                        'params' => 'Insert History Update Order E-book',
                        'type_history' => 'Update',
                        'order_ebook_id' => $request->id,
                        'tipe_order_his' => $request->up_tipe_order == $history->tipe_order ? NULL : $history->tipe_order,
                        'tipe_order_new' => $request->up_tipe_order == $history->tipe_order ? NULL : $request->up_tipe_order,
                        'edisi_cetak_his' => $request->up_edisi_cetak == $history->edisi_cetak ? NULL : $history->edisi_cetak,
                        'edisi_cetak_new' => $request->up_edisi_cetak == $history->edisi_cetak ? NULL : $request->up_edisi_cetak,
                        'jml_hal_perkiraan_his' => $request->up_jml_hal_perkiraan == $history->jml_hal_perkiraan ? NULL : $history->jml_hal_perkiraan,
                        'jml_hal_perkiraan_new' => $request->up_jml_hal_perkiraan == $history->jml_hal_perkiraan ? NULL : $request->up_jml_hal_perkiraan,
                        'kelompok_buku_id_his' => $request->up_kelompok_buku == $history->kelompok_buku_id ? NULL : $history->kelompok_buku_id,
                        'kelompok_buku_id_new' => $request->up_kelompok_buku == $history->kelompok_buku_id ? NULL : $request->up_kelompok_buku,
                        'tahun_terbit_his' => date('Y-m', strtotime($request->up_tahun_terbit)) == date('Y-m', strtotime($history->tahun_terbit)) ? NULL : $history->tahun_terbit,
                        'tahun_terbit_new' => date('Y-m', strtotime($request->up_tahun_terbit)) == date('Y-m', strtotime($history->tahun_terbit)) ? NULL : Carbon::createFromFormat('Y', $request->up_tahun_terbit)->format('Y'),
                        'tgl_upload_his' => date('Y-m-d H:i:s', strtotime($request->up_tgl_upload)) == date('Y-m-d H:i:s', strtotime($history->tgl_upload)) ? NULL : date('Y-m-d H:i:s', strtotime($history->tgl_upload)),
                        'tgl_upload_new' => date('Y-m-d H:i:s', strtotime($request->up_tgl_upload)) == date('Y-m-d H:i:s', strtotime($history->tgl_upload)) ? NULL : Carbon::createFromFormat('d F Y', $request->up_tgl_upload)->format('Y-m-d H:i:s'),
                        'spp_his' => $request->up_spp == $history->spp ? NULL : $history->spp,
                        'spp_new' => $request->up_spp == $history->spp ? NULL : $request->up_spp,
                        'keterangan_his' => $request->up_keterangan == $history->keterangan ? NULL : $history->keterangan,
                        'keterangan_new' => $request->up_keterangan == $history->keterangan ? NULL : $request->up_keterangan,
                        'perlengkapan_his' => $request->up_perlengkapan == $history->perlengkapan ? NULL : $history->perlengkapan,
                        'perlengkapan_new' => $request->up_perlengkapan == $history->perlengkapan ? NULL : $request->up_perlengkapan,
                        'eisbn_his' => $request->up_eisbn == $history->eisbn ? NULL : $history->eisbn,
                        'eisbn_new' => $request->up_eisbn == $history->eisbn ? NULL : $request->up_eisbn,
                        'author_id' => auth()->id(),
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new OrderEbookEvent($insert));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data order e-book berhasil diubah'
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
        $data = DB::table('order_ebook as oe')
            ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oe.deskripsi_turun_cetak_id')
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
            ->where('oe.id', $id)
            ->where('pn.kode', $naskah)
            ->select(
                'oe.*',
                'dtc.tipe_order',
                'pp.platform_digital_ebook_id',
                'pp.pilihan_terbit',
                'df.sub_judul_final',
                'dp.judul_final',
                'dp.nama_pena',
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
        if (!$penulisList->isEmpty()) {
            foreach ($penulisList as $pl) {
                $collectPenulis[] = $pl->id;
            }
        } else {
            $collectPenulis = [];
        }

        $platformDigital = DB::table('platform_digital_ebook')->whereNull('deleted_at')->get();
        $kbuku = DB::table('penerbitan_m_kelompok_buku')
            ->get();
        $nama_pena = json_decode($data->nama_pena);
        $imprint = "-";
        if (!is_null($data->imprint)) {
            $imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        return view('penerbitan.order_ebook.edit', [
            'title' => 'Update E-book',
            'tipeOrd' => $tipeOrd,
            'platformDigital' => $platformDigital,
            'kbuku' => $kbuku,
            'penulis' => $penulis,
            'collect_penulis' => $collectPenulis,
            'data' => $data,
            'imprint' => $imprint,
            'nama_pena' => is_null($data->nama_pena)?"-":implode(",",$nama_pena)
        ]);
    }
    public function detailOrderEbook(Request $request)
    {
        $id = $request->get('order');
        $naskah = $request->get('naskah');
        $data = DB::table('order_ebook as oe')
            ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oe.deskripsi_turun_cetak_id')
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
            ->where('oe.id', $id)
            ->where('pn.kode', $naskah)
            ->select(
                'oe.*',
                'dtc.tipe_order',
                'pp.platform_digital_ebook_id',
                'pp.pilihan_terbit',
                'df.sub_judul_final',
                'dp.judul_final',
                'dp.format_buku',
                'dp.nama_pena',
                'dp.imprint',
                'dp.jml_hal_perkiraan',
                'kb.nama',
                'pn.id as naskah_id',
                'pn.jalur_buku',
                'ps.edisi_cetak'
            )
            ->first();
        if (is_null($data)) {
            return abort(404);
        }
        //Penulis
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.id', 'pp.nama')
            ->get();
        //Data Action
        $act = DB::table('order_ebook_action')->where('order_ebook_id', $data->id)->get();
        if (!$act->isEmpty()) {
            foreach ($act as $a) {
                $act_j[] = $a->type_departemen;
            }
        } else {
            $act_j = NULL;
        }
        //List departemen Approval
        $type = DB::select(DB::raw("SHOW COLUMNS FROM order_ebook_action WHERE Field = 'type_departemen'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $departemen = explode("','", $matches[1]);
        $imprint = NULL;
        if (!is_null($data->imprint)) {
            $imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        foreach(array_filter(json_decode($data->platform_digital_ebook_id)) as $pd) {
            $platformD[] = DB::table('platform_digital_ebook')->where('id',$pd)->whereNull('deleted_at')->first()->nama;
        }
        // return response()->json($platformD);
        return view('penerbitan.order_ebook.detail', [
            'title' => 'Detail Order E-Book',
            'data' => $data,
            'act' => $act,
            'act_j' => $act_j,
            'departemen' => $departemen,
            'penulis' => $penulis,
            'imprint' => $imprint,
            'platform_digital' => $platformD
        ]);
    }
    protected function logicPermissionAction($update, $status = null, $id, $kode, $judul_final, $btn)
    {
        if ($status == 'Selesai') {
            if (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') {
                $btn = $this->buttonEdit($id, $kode, $btn);
            }
        } else {
            if ($update) {
                $role = DB::table('order_ebook_action')->where('order_ebook_id', $id)->get();
                if (!$role->isEmpty()) {
                    foreach ($role as $r) {
                        if (!Gate::allows('do_approval', $r->type_departemen)) {
                            $btn = $this->buttonEdit($id, $kode, $btn);
                            break;
                        }
                    }
                }  else {
                    $btn = $this->buttonEdit($id, $kode, $btn);
                }
            }
        }

        if ($update) {
            if (Gate::allows('do_approval', 'Penerbitan')) {
                $btn = $this->panelStatusGuest($status, $btn);
            } elseif (Gate::allows('do_approval', 'Marketing & Ops')) {
                $btn = $this->panelStatusGuest($status, $btn);
            } elseif (Gate::allows('do_approval', 'Keuangan')) {
                $btn = $this->panelStatusGuest($status, $btn);
            } elseif (Gate::allows('do_approval', 'Direktur Utama')) {
                $btn = $this->panelStatusGuest($status, $btn);
            } else {
                $btn = $this->panelStatusAdmin($status, $id, $kode, $judul_final, $btn);
            }
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
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-orebook" style="background:#34395E;color:white" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusOrderEbook" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Pending':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-orebook" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusOrderEbook" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Proses':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-orebook" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusOrderEbook" title="Update Status">
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
        $btn .= '<a href="' . url('penerbitan/order-ebook/edit?order=' . $id . '&naskah=' . $kode) . '"
        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
        <div><i class="fas fa-edit"></i></div></a>';
        return $btn;
    }
    public function ajaxRequest(Request $request, $cat)
    {
        switch ($cat) {
            case 'approve':
                return $this->approvalOrderEbook($request);
                break;
            case 'decline':
                return $this->declineOrderEbook($request);
                break;
            case 'approve-detail':
                return $this->approvalDetailOrderEbook($request);
                break;
            case 'decline-detail':
                return $this->declineDetailOrderEbook($request);
                break;
            case 'update-status-progress':
                return $this->updateStatusProgress($request);
                break;
            case 'lihat-history-order-ebook':
                return $this->lihatHistoryOrderEbook($request);
                break;
            default:
                abort(500);
        }
    }
    protected function updateStatusProgress($request)
    {
        try {
            $id = $request->id;
            $data = DB::table('order_ebook as ob')
                ->join('deskripsi_turun_cetak as dtc','dtc.id','=','ob.deskripsi_turun_cetak_id')
                ->join('pracetak_cover as pc','pc.id','=','dtc.pracetak_cover_id')
                ->join('deskripsi_cover as dc','dc.id','=','pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp','dp.id','=','dc.deskripsi_produk_id')
                ->where('ob.id', $id)
                ->select(
                    'ob.*',
                    'dp.naskah_id',
                    )
                ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ], 404);
            }
            $penilaian = DB::table('order_ebook_action as oea')
                ->where('order_ebook_id', $data->id)->where('type_departemen', 'Direktur Utama')
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
                'params' => 'Update Status Order Ebook',
                'id' => $data->id,
                'tgl_selesai_order' => $request->status == 'Selesai' ? $tgl : NULL,
                'status' => $request->status,
            ];
            $insert = [
                'params' => 'Insert History Status Order Ebook',
                'order_ebook_id' => $data->id,
                'type_history' => 'Status',
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
                event(new OrderEbookEvent($update));
                event(new OrderEbookEvent($insert));
                $updateTimelineOrderEbook = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Order Ebook',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineOrderEbook));
                $msg = 'Order E-book selesai, silahkan lanjut ke proses produksi upload ke platform..';
            } else {
                event(new OrderEbookEvent($update));
                event(new OrderEbookEvent($insert));
                $updateTimelineOrderEbook = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Order Ebook',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineOrderEbook));
                $msg = 'Status progress order e-book berhasil diupdate';
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
    protected function approvalOrderEbook($request)
    {
        try {
            $permission = DB::table('permissions')
                ->where('raw', $request->type_departemen)
                ->first();
            // return response()->json($request->type_departemen);
            $insert = [
                'params' => 'Insert Action',
                'order_ebook_id' => $request->id,
                'type_departemen' => $request->type_departemen,
                'type_action' => 'Approval',
                'users_id' => auth()->id(),
                'catatan_action' => $request->catatan_action,
                'tgl_action' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            ];
            switch ($request->type_departemen) {
                case 'Penerbitan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Marketing & Ops'];
                    break;
                case 'Marketing & Ops':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Keuangan'];
                    break;
                case 'Keuangan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Direktur Utama'];
                    break;
                case 'Direktur Utama':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Penerbitan', 'Marketing & Ops', 'Keuangan'];
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
            event(new OrderEbookEvent($insert));
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
    protected function declineOrderEbook($request)
    {
        try {
            $permission = DB::table('permissions')
                ->where('raw', $request->type_departemen)
                ->first();
            // return response()->json($request->type_departemen);
            $insert = [
                'params' => 'Insert Action',
                'order_ebook_id' => $request->id,
                'type_departemen' => $request->type_departemen,
                'type_action' => 'Decline',
                'users_id' => auth()->id(),
                'catatan_action' => $request->catatan_action,
                'tgl_action' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            ];
            switch ($request->type_departemen) {
                case 'Penerbitan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Marketing & Ops'];
                    break;
                case 'Marketing & Ops':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Keuangan'];
                    break;
                case 'Keuangan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Direktur Utama'];
                    break;
                case 'Direktur Utama':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Penerbitan', 'Marketing & Ops', 'Keuangan'];
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
            event(new OrderEbookEvent($insert));
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
    protected function approvalDetailOrderEbook($request)
    {
        $id = $request->id;
        $data = DB::table('order_ebook_action')
            ->where('id', $id)
            ->first();
        $fetch = [
            // 'jabatan' => $data->type_departemen,
            'users' => DB::table('users')->where('id', $data->users_id)->first()->nama,
            'catatan' => $data->catatan_action,
            'tgl' => Carbon::parse($data->tgl_action)->translatedFormat('l d F Y, H:i')
        ];
        return response()->json($fetch);
    }
    protected function declineDetailOrderEbook($request)
    {
        $id = $request->id;
        $data = DB::table('order_ebook_action')
            ->where('id', $id)
            ->first();
        $fetch = [
            'departemen' => $data->type_departemen,
            'users' => DB::table('users')->where('id', $data->users_id)->first()->nama,
            'catatan' => $data->catatan_action,
            'tgl' => Carbon::parse($data->tgl_action)->translatedFormat('l d F Y, H:i')
        ];
        return response()->json($fetch);
    }
    protected function lihatHistoryOrderEbook($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('order_ebook_history as oeh')
                ->join('order_ebook as oe', 'oe.id', '=', 'oeh.order_ebook_id')
                ->join('users as u', 'oeh.author_id', '=', 'u.id')
                ->where('oeh.order_ebook_id', $id)
                ->select('oeh.*', 'u.nama')
                ->orderBy('oeh.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status order e-book <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Approval':
                        $lbl = is_null($d->catatan_action) ? '' : 'dengan catatan: ';
                        $catatan = is_null($d->catatan_action) ? 'tanpa catatan' : $d->catatan_action;
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Order e-book telah disetujui ' . $lbl . '<b class="text-dark">' . $catatan . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Decline':
                        $lbl = is_null($d->catatan_action) ? '' : 'dengan catatan: ';
                        $catatan = is_null($d->catatan_action) ? 'tanpa catatan' : $d->catatan_action;
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Order e-book telah ditolak ' . $lbl . '<b class="text-dark">' . $catatan . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Update':

                        if (!is_null($d->tipe_order_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Tipe order <b class="text-dark">' . $d->tipe_order_his . '</b> diubah menjadi <b class="text-dark">' . $d->tipe_order_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->tipe_order_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Tipe order <b class="text-dark">' . $d->tipe_order_new . '</b> ditambahkan.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->tgl_upload_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Tanggal upload <b class="text-dark">' . Carbon::parse($d->tgl_upload_his)->translatedFormat('d F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->tgl_upload_new)->translatedFormat('d F Y') . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->tgl_upload_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Tanggal upload <b class="text-dark">' . Carbon::parse($d->tgl_upload_new)->translatedFormat('d F Y') . '</b> ditambahkan.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->jml_hal_perkiraan_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_perkiraan_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_hal_perkiraan_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->jml_hal_perkiraan_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_perkiraan_new . '</b> ditambahkan.';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->tahun_terbit_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Tahun terbit <b class="text-dark">' . $d->tahun_terbit_his . '</b> diubah menjadi <b class="text-dark">' . $d->tahun_terbit_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->tahun_terbit_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Tahun terbit <b class="text-dark">' . $d->tahun_terbit_new . '</b> ditambahkan.<br>';
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
                        if (!is_null($d->spp_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' SPP <b class="text-dark">' . $d->spp_his . '</b> diubah menjadi <b class="text-dark">' . $d->spp_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->spp_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' SPP <b class="text-dark">' . $d->spp_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->keterangan_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Keterangan <b class="text-dark">' . $d->keterangan_his . '</b> diubah menjadi <b class="text-dark">' . $d->keterangan_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->keterangan_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Keterangan <b class="text-dark">' . $d->keterangan_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->perlengkapan_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Perlengkapan <b class="text-dark">' . $d->perlengkapan_his . '</b> diubah menjadi <b class="text-dark">' . $d->perlengkapan_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->perlengkapan_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Perlengkapan <b class="text-dark">' . $d->perlengkapan_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        }
                        if (!is_null($d->eisbn_his)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' EISBN <b class="text-dark">' . $d->eisbn_his . '</b> diubah menjadi <b class="text-dark">' . $d->eisbn_new . '</b>.<br>';
                            $html .= '</span></div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                            </div>
                            </span>';
                        } elseif (!is_null($d->eisbn_new)) {
                            $html .= '<span class="ticket-item">
                        <div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' EISBN <b class="text-dark">' . $d->eisbn_new . '</b> ditambahkan.<br>';
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
}
