<?php

namespace App\Http\Controllers\Penerbitan;

use App\Events\NotifikasiPenyetujuan;
use App\Events\OrderCetakEvent;
use Carbon\Carbon;
// use App\Models\User;
use Ramsey\Uuid\Uuid;
// use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
// use App\Events\NotifikasiPenyetujuan;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

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
                                            $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> ' . $j . '</div>';
                                            break;
                                        case 'Decline':
                                            $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> ' . $j . '</div>';
                                            break;
                                    }
                                }
                            } else {
                                $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> ' . $j . '</div>';
                            }
                        } else {
                            $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> ' . $j . '</div>';
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
                        'eisbn' => $request->up_eisbn,
                    ];
                    event(new OrderCetakEvent($update));
                    $insert = [
                        'params' => 'Insert History Update Order Cetak',
                        'type_history' => 'Update',
                        'order_ebook_id' => $request->id,
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
        $platformDigital = DB::table('platform_digital_ebook')->whereNull('deleted_at')->get();
        $kbuku = DB::table('penerbitan_m_kelompok_buku')
            ->get();
        return view('penerbitan.order_cetak.edit', [
            'title' => 'Update Order Cetak',
            'tipeOrd' => $tipeOrd,
            'platformDigital' => $platformDigital,
            'kbuku' => $kbuku,
            'penulis' => $penulis,
            'collect_penulis' => $collectPenulis,
            'data' => $data,
        ]);
    }
    public function detailProduksi(Request $request)
    {
        $order = $request->get('order');
        $naskah = $request->get('naskah');
        $data = DB::table('order_cetak as oc')
            ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
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
            ->where('oc.id', $order)
            ->where('pn.kode', $naskah)
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

        is_null($data) ? abort(404) :
            $sasaranPasar = DB::table('penerbitan_pn_prodev')
            ->where('naskah_id', $data->naskah_id)
            ->first();
        $sasaran_pasar = is_null($sasaranPasar) ? null : $sasaranPasar->sasaran_pasar;
        $pilihan_terbit = DB::table('pilihan_penerbitan')
            ->where('deskripsi_turun_cetak_id', $data->deskripsi_turun_cetak_id)
            ->first();

        // $dataPenolakan = DB::table('order_cetak_penyetujuan as pny')
        //     ->where('pny.order_cetak_id', $kode)
        //     ->where(function ($query) {
        //         $query->where('pny.d_operasional_act', '=', '2')
        //             ->orWhere('pny.d_keuangan_act', '=', '2')
        //             ->orWhere('pny.d_utama_act', '=', '2');
        //     })
        //     ->whereNotNull('pny.pending_sampai')
        //     ->first();

        // if (!is_null($dataPenolakan)) {
        //     $bool = $dataPenolakan->pending_sampai <= Carbon::now('Asia/Jakarta')->format('Y-m-d') ? true : false;
        //     $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
        //         ->where('form_id', $kode)->first();
        //     if ($bool == true) {
        //         if ($dataPenolakan->d_operasional_act == '2') {
        //             DB::table('order_cetak_penyetujuan')
        //                 ->where('order_cetak_id', $kode)
        //                 ->where('d_operasional_act', '2')
        //                 ->update([
        //                     'd_operasional_act' => '1',
        //                     'status_general' => 'Proses',
        //                     'ket_pending' => NULL,
        //                     'pending_sampai' => NULL,
        //                 ]);
        //             if (is_null($dataPenolakan->m_stok)) {
        //                 DB::table('notif_detail')->where('notif_id', $notif->id)
        //                     ->where('user_id', '=', $dataPenolakan->m_penerbitan)
        //                     ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             } else {
        //                 DB::table('notif_detail')->where('notif_id', $notif->id)
        //                     ->where('user_id', '=', $dataPenolakan->m_stok)
        //                     ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             }
        //             DB::table('notif_detail')->where('notif_id', $notif->id)
        //                 ->where('user_id', '=', $dataPenolakan->d_operasional)
        //                 ->update(['raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             DB::table('notif_detail')->where('notif_id', $notif->id)
        //                 ->where('user_id', '=', $dataPenolakan->d_keuangan)
        //                 ->delete();
        //             DB::table('notif_detail')->where('notif_id', $notif->id)
        //                 ->where('user_id', '=', $dataPenolakan->d_utama)
        //                 ->delete();
        //         } elseif ($dataPenolakan->d_keuangan_act == '2') {
        //             DB::table('order_cetak_penyetujuan')
        //                 ->where('order_cetak_id', $kode)
        //                 ->where('d_keuangan_act', '2')
        //                 ->update([
        //                     'd_keuangan_act' => '1',
        //                     'status_general' => 'Proses',
        //                     'ket_pending' => NULL,
        //                     'pending_sampai' => NULL,
        //                 ]);
        //             if (is_null($dataPenolakan->m_stok)) {
        //                 DB::table('notif_detail')->where('notif_id', $notif->id)
        //                     ->where('user_id', '=', $dataPenolakan->m_penerbitan)
        //                     ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             } else {
        //                 DB::table('notif_detail')->where('notif_id', $notif->id)
        //                     ->where('user_id', '=', $dataPenolakan->m_stok)
        //                     ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             }
        //             DB::table('notif_detail')->where('notif_id', $notif->id)
        //                 ->where('user_id', '=', $dataPenolakan->d_operasional)
        //                 ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             DB::table('notif_detail')->where('notif_id', $notif->id)
        //                 ->where('user_id', '=', $dataPenolakan->d_keuangan)
        //                 ->update(['raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             DB::table('notif_detail')->where('notif_id', $notif->id)
        //                 ->where('user_id', '=', $dataPenolakan->d_utama)
        //                 ->delete();
        //         } elseif ($dataPenolakan->d_utama_act == '2') {
        //             DB::table('order_cetak_penyetujuan')
        //                 ->where('order_cetak_id', $kode)
        //                 ->where('d_operasional_act', '2')
        //                 ->update([
        //                     'd_keuangan_act' => '1',
        //                     'status_general' => 'Proses',
        //                     'ket_pending' => NULL,
        //                     'pending_sampai' => NULL,
        //                 ]);
        //             if (is_null($dataPenolakan->m_stok)) {
        //                 DB::table('notif_detail')->where('notif_id', $notif->id)
        //                     ->where('user_id', '=', $dataPenolakan->m_penerbitan)
        //                     ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             } else {
        //                 DB::table('notif_detail')->where('notif_id', $notif->id)
        //                     ->where('user_id', '=', $dataPenolakan->m_stok)
        //                     ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             }
        //             DB::table('notif_detail')->where('notif_id', $notif->id)
        //                 ->where('user_id', '=', $dataPenolakan->d_operasional)
        //                 ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             DB::table('notif_detail')->where('notif_id', $notif->id)
        //                 ->where('user_id', '=', $dataPenolakan->d_keuangan)
        //                 ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //             DB::table('notif_detail')->where('notif_id', $notif->id)
        //                 ->where('user_id', '=', $dataPenolakan->d_utama)
        //                 ->update(['raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
        //         }
        //     }
        // }
        // $prodPenyetujuan = DB::table('order_cetak_penyetujuan')
        //     ->where('order_cetak_penyetujuan.order_cetak_id', '=', $prod->id)
        //     ->select('order_cetak_penyetujuan.*')
        //     ->first();
        // $author = DB::table('users')
        //     ->where('id', $author)
        //     ->first();
        // $m_stok = DB::table('users as u')
        //     ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
        //     ->where('j.nama', 'LIKE', '%Manajer Stok%')
        //     ->select('u.nama', 'u.id as id')
        //     ->first();
        // if (is_null($m_stok)) {
        //     $m_stok = '';
        // }
        // $m_penerbitan = DB::table('users as u')
        //     ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
        //     ->where('j.nama', 'LIKE', '%Manajer Penerbitan%')
        //     ->select('u.nama', 'u.id as id')
        //     ->first();
        // if (is_null($m_penerbitan)) {
        //     $m_penerbitan = '';
        // }
        // $dirop = DB::table('users as u')
        //     ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
        //     ->where('j.nama', 'LIKE', '%Direktur Operasional%')
        //     ->select('u.nama', 'u.id as id')
        //     ->first();
        // if (is_null($dirop)) {
        //     $dirop = '';
        // }
        // $dirke = DB::table('users as u')
        //     ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
        //     ->where('j.nama', 'LIKE', '%Direktur Keuangan%')
        //     ->select('u.nama', 'u.id as id')
        //     ->first();
        // if (is_null($dirke)) {
        //     $dirke = '';
        // }
        // $dirut = DB::table('users as u')
        //     ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
        //     ->where('j.nama', 'LIKE', '%Direktur Utama%')
        //     ->select('u.nama', 'u.id as id')
        //     ->first();
        // if (is_null($dirut)) {
        //     $dirut = '';
        // }
        // if ($prod->status_cetak == '3') {
        //     DB::table('order_cetak_penyetujuan')
        //         ->where('order_cetak_penyetujuan.order_cetak_id', $prod->id)
        //         ->update([
        //             'm_stok' => $m_stok->id,
        //             'd_operasional' => $dirop->id,
        //             'd_keuangan' => $dirke->id,
        //             'd_utama' => $dirut->id
        //         ]);
        // } else {
        //     DB::table('order_cetak_penyetujuan')
        //         ->where('order_cetak_penyetujuan.order_cetak_id', $prod->id)
        //         ->update([
        //             'm_penerbitan' => $m_penerbitan->id,
        //             'd_operasional' => $dirop->id,
        //             'd_keuangan' => $dirke->id,
        //             'd_utama' => $dirut->id
        //         ]);
        // }

        // $p_mstok = DB::table('order_cetak_penyetujuan')
        //     ->join('users', 'order_cetak_penyetujuan.m_stok', '=', 'users.id')
        //     ->where('order_cetak_penyetujuan.m_stok', '=', $m_stok->id)
        //     ->where('order_cetak_penyetujuan.order_cetak_id', '=', $prod->id)
        //     ->select('order_cetak_penyetujuan.*', 'users.nama')
        //     ->first();

        // $p_mp = DB::table('order_cetak_penyetujuan')
        //     ->join('users', 'order_cetak_penyetujuan.m_penerbitan', '=', 'users.id')
        //     ->where('order_cetak_penyetujuan.m_penerbitan', '=', $m_penerbitan->id)
        //     ->where('order_cetak_penyetujuan.order_cetak_id', '=', $prod->id)
        //     ->select('order_cetak_penyetujuan.*', 'users.nama')
        //     ->first();

        // $p_dirop = DB::table('order_cetak_penyetujuan')
        //     ->join('users', 'order_cetak_penyetujuan.d_operasional', '=', 'users.id')
        //     ->where('order_cetak_penyetujuan.d_operasional', '=', $dirop->id)
        //     ->where('order_cetak_penyetujuan.order_cetak_id', '=', $prod->id)
        //     ->select('order_cetak_penyetujuan.*', 'users.nama')
        //     ->first();

        // $p_dirke = DB::table('order_cetak_penyetujuan')
        //     ->join('users', 'order_cetak_penyetujuan.d_keuangan', '=', 'users.id')
        //     ->where('order_cetak_penyetujuan.d_keuangan', '=', $dirke->id)
        //     ->where('order_cetak_penyetujuan.order_cetak_id', '=', $prod->id)
        //     ->select('order_cetak_penyetujuan.*', 'users.nama')
        //     ->first();

        // $p_dirut = DB::table('order_cetak_penyetujuan')
        //     ->join('users', 'order_cetak_penyetujuan.d_utama', '=', 'users.id')
        //     ->where('order_cetak_penyetujuan.d_utama', '=', $dirut->id)
        //     ->where('order_cetak_penyetujuan.order_cetak_id', '=', $prod->id)
        //     ->select('order_cetak_penyetujuan.*', 'users.nama')
        //     ->first();
        return view('penerbitan.order_cetak.detail', [
            'title' => 'Detail Order Cetak Buku',
            'data' => $data,
            'sasaran_pasar' => $sasaran_pasar,
            'penulis' => $penulis,
            'pilihan_terbit' => $pilihan_terbit,
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
            case 'approval':
                return $this->approvalOrder($request);
                break;
            case 'pending':
                return $this->pendingOrder($request);
                break;
            case 'update-status-progress':
                return $this->updateStatusProgress($request);
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
                'order_cetak_id' => $data->id,
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
    protected function notifPersetujuan($dataEvent)
    {
        /*
        Persetujuan Direktur harus berurutan dari Operasional ke Utama.
        Status Cetak (Buku Baru & Cetak Ulang Revisi) ::
                M.Penerbit (bisa lewat langsung direktur) -> D.Operasional -> D.Keuangan -> D.Utama
        Status Cetak (Cetak Ulang) ::
                M.Stok (bisa lewat langsung direktur) -> D.Operasional -> D.Keuangan -> D.Utama
        =============================================================================================
            Permission :: 09179170e6e643eca66b282e2ffae1f8

        */

        if ($dataEvent['status_cetak'] == '1' or $dataEvent['status_cetak'] == '2') {
            $dataLoop = DB::table('user_permission as up')
                ->join('users as u', 'u.id', 'up.user_id')
                ->join('jabatan as j', 'j.id', 'u.jabatan_id')
                ->where('up.permission_id', '=', '09179170e6e643eca66b282e2ffae1f8')
                ->whereNotIn('j.nama', ['Manajer Stok', 'Direktur Keuangan', 'Direktur Utama'])
                ->get();
            if ($dataEvent['type'] == 'create-notif') {
                $id_notif = Str::uuid()->getHex();
                $sC = $dataEvent['status_cetak'] == '1' ? 'Persetujuan Order Buku Baru' : 'Persetujuan Order Cetak Ulang Revisi';
                DB::table('notif')->insert([
                    [
                        'id' => $id_notif,
                        'section' => 'Penerbitan',
                        'type' => $sC,
                        'permission_id' => '09179170e6e643eca66b282e2ffae1f8',
                        'form_id' => $dataEvent['form_id'],
                    ],
                ]);
                foreach ($dataLoop as $d) {
                    DB::table('notif_detail')->insert([
                        [
                            'notif_id' => $id_notif,
                            'user_id' => $d->user_id,
                        ],
                    ]);
                }
                return;
            } elseif ($dataEvent['type'] == 'update-notif') {
                if ($dataEvent['m_penerbitan']) {
                    $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8') // Hanya untuk prodev
                        ->where('form_id', $dataEvent['form_id'])->first();
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataEvent['m_penerbitan'])
                        ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                }

                return;
            }
        } elseif ($dataEvent['status_cetak'] == '3') {
            if ($dataEvent['type'] == 'create-notif') {
                DB::table('notif')->insert([
                    [
                        'id' => Str::uuid()->getHex(),
                        'section' => 'Order Cetak Buku',
                        'type' => 'Persetujuan Order Cetak Ulang',
                        'permission_id' => '09179170e6e643eca66b282e2ffae1f8', // M.Stok
                        'form_id' => $dataEvent['form_id'],
                    ],
                ]);
                return;
            }
        }
    }
    protected function approvalOrder($request)
    {
        try {
            $dataUser = DB::table('users')
                ->where('id', auth()->id())
                ->first();
            $dataPenyetujuan = DB::table('order_cetak_penyetujuan')
                ->where('order_cetak_id', $request->id)
                ->select('order_cetak_penyetujuan.*')
                ->first();
            if ($dataPenyetujuan->status_general == 'Selesai') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data sudah selesai di Approve'
                ]);
            } elseif ($dataPenyetujuan->status_general == 'Pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data di Pending sampai ' . date('d F Y', strtotime($dataPenyetujuan->pending_sampai))
                ]);
            }
            if ($request->status_cetak == '3') {
                if ($dataUser->id == $dataPenyetujuan->m_stok) {
                    if ($dataPenyetujuan->m_stok_act == '3') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data sudah Anda Approve'
                        ]);
                    } elseif ($dataPenyetujuan->m_stok_act == '2') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data sudah dipending sampai ' . date('d F Y', strtotime($dataPenyetujuan->pending_sampai))
                        ]);
                    }
                    DB::table('order_cetak_penyetujuan')
                        ->where('order_cetak_id', $request->id)
                        ->update([
                            'm_stok_act' => '3',
                        ]);
                    $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                        ->where('form_id', $request->id)->first();
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_stok)
                        ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                    DB::table('notif_detail')
                        ->insert([
                            'notif_id' => $notif->id,
                            'user_id' => $dataPenyetujuan->d_operasional,
                            'raw_data' => 'Disetujui Cetak',
                        ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil diupdate'
                    ]);
                } elseif ($dataUser->id == $dataPenyetujuan->d_operasional) {
                    if ($dataPenyetujuan->m_stok_act == '1') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Manajer Stok belum melakukan persetujuan'
                        ]);
                    }
                    DB::table('order_cetak_penyetujuan')
                        ->where('order_cetak_id', $request->id)
                        ->update([
                            'd_operasional_act' => '3',
                        ]);
                    $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                        ->where('form_id', $request->id)->first();
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                        ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                    DB::table('notif_detail')
                        ->insert([
                            'notif_id' => $notif->id,
                            'user_id' => $dataPenyetujuan->d_keuangan,
                            'raw_data' => 'Disetujui Cetak',
                        ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil diupdate'
                    ]);
                } elseif ($dataUser->id == $dataPenyetujuan->d_keuangan) {
                    if ($dataPenyetujuan->d_operasional_act == '3') {
                        DB::table('order_cetak_penyetujuan')
                            ->where('order_cetak_id', $request->id)
                            ->update([
                                'd_keuangan_act' => '3',
                            ]);
                        $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                            ->where('form_id', $request->id)->first();
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->d_keuangan)
                            ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                        DB::table('notif_detail')
                            ->insert([
                                'notif_id' => $notif->id,
                                'user_id' => $dataPenyetujuan->d_utama,
                                'raw_data' => 'Disetujui Cetak',
                            ]);
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Data berhasil diupdate'
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Operasional'
                        ]);
                    }
                } elseif ($dataUser->id == $dataPenyetujuan->d_utama) {
                    if ($dataPenyetujuan->d_operasional_act == '1') {
                        if ($dataPenyetujuan->d_keuangan_act == '1') {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Data belum di Approve oleh Direktur Operasional dan Direktur Keuangan'
                            ]);
                        }
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Operasional'
                        ]);
                    } elseif ($dataPenyetujuan->d_keuangan_act == '1') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Keuangan'
                        ]);
                    } else {
                        DB::table('order_cetak_penyetujuan')
                            ->where('order_cetak_id', $request->id)
                            ->update([
                                'd_utama_act' => '3',
                                'status_general' => 'Selesai',
                            ]);
                        $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                            ->where('form_id', $request->id)->first();
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->m_stok)
                            ->update([
                                'seen' => '0',
                                'raw_data' => 'Selesai Cetak',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                            ->update([
                                'seen' => '0',
                                'raw_data' => 'Selesai Cetak',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->d_keuangan)
                            ->update([
                                'seen' => '0',
                                'raw_data' => 'Selesai Cetak',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->d_utama)
                            ->update([
                                'seen' => '0',
                                'raw_data' => 'Selesai Cetak',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        $idProsesProduksi = Uuid::uuid4()->toString();
                        DB::table('proses_produksi_cetak')->insert([
                            'id' => $idProsesProduksi,
                            'order_cetak_id' => $request->id,
                        ]);
                        $dataLoop = DB::table('user_permission')
                            ->where('permission_id', 'a91ee437-1e08-11ed-87ce-1078d2a38ee5')
                            ->get();
                        $id_notif = Str::uuid()->getHex();
                        $sC = 'Proses Produksi Order Cetak';
                        DB::table('notif')->insert([
                            [
                                'id' => $id_notif,
                                'section' => 'Produksi',
                                'type' => $sC,
                                'permission_id' => 'a91ee437-1e08-11ed-87ce-1078d2a38ee5',
                                'form_id' => $idProsesProduksi,
                            ],
                        ]);
                        foreach ($dataLoop as $d) {
                            DB::table('notif_detail')->insert([
                                [
                                    'notif_id' => $id_notif,
                                    'user_id' => $d->user_id,
                                    // 'raw_data' => 'Produksi Baru',
                                ],
                            ]);
                        }
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Data berhasil diupdate'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda tidak memiliki akses'
                    ]);
                }
            } else {
                if ($dataUser->id == $dataPenyetujuan->m_penerbitan) {
                    if ($dataPenyetujuan->m_penerbitan_act == '3') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data sudah Anda Approve'
                        ]);
                    } elseif ($dataPenyetujuan->m_penerbitan_act == '2') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data sudah dipending sampai ' . date('d F Y', strtotime($dataPenyetujuan->pending_sampai))
                        ]);
                    }
                    DB::table('order_cetak_penyetujuan')
                        ->where('order_cetak_id', $request->id)
                        ->update([
                            'm_penerbitan_act' => '3',
                        ]);
                    $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                        ->where('form_id', $request->id)->first();
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_penerbitan)
                        ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                        ->update(['raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil diupdate'
                    ]);
                } elseif ($dataUser->id == $dataPenyetujuan->d_operasional) {
                    DB::table('order_cetak_penyetujuan')
                        ->where('order_cetak_id', $request->id)
                        ->update([
                            'd_operasional_act' => '3',
                        ]);
                    $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                        ->where('form_id', $request->id)->first();
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                        ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                    DB::table('notif_detail')
                        ->insert([
                            'notif_id' => $notif->id,
                            'user_id' => $dataPenyetujuan->d_keuangan,
                            'raw_data' => 'Disetujui Cetak',
                        ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil diupdate'
                    ]);
                } elseif ($dataUser->id == $dataPenyetujuan->d_keuangan) {
                    if ($dataPenyetujuan->d_operasional_act == '3') {
                        DB::table('order_cetak_penyetujuan')
                            ->where('order_cetak_id', $request->id)
                            ->update([
                                'd_keuangan_act' => '3',
                            ]);
                        $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                            ->where('form_id', $request->id)->first();
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->d_keuangan)
                            ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                        DB::table('notif_detail')
                            ->insert([
                                'notif_id' => $notif->id,
                                'user_id' => $dataPenyetujuan->d_utama,
                                'raw_data' => 'Disetujui Cetak',
                            ]);
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Data berhasil diupdate'
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Operasional'
                        ]);
                    }
                } elseif ($dataUser->id == $dataPenyetujuan->d_utama) {
                    if ($dataPenyetujuan->d_operasional_act == '1') {
                        if ($dataPenyetujuan->d_keuangan_act == '1') {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Data belum di Approve oleh Direktur Operasional dan Direktur Keuangan'
                            ]);
                        }
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Operasional'
                        ]);
                    } elseif ($dataPenyetujuan->d_keuangan_act == '1') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Keuangan'
                        ]);
                    } else {
                        DB::table('order_cetak_penyetujuan')
                            ->where('order_cetak_id', $request->id)
                            ->update([
                                'd_utama_act' => '3',
                                'status_general' => 'Selesai',
                            ]);
                        $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                            ->where('form_id', $request->id)->first();
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->m_penerbitan)
                            ->update([
                                'seen' => '0',
                                'raw_data' => 'Selesai Cetak',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                            ->update([
                                'seen' => '0',
                                'raw_data' => 'Selesai Cetak',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->d_keuangan)
                            ->update([
                                'seen' => '0',
                                'raw_data' => 'Selesai Cetak',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->where('user_id', '=', $dataPenyetujuan->d_utama)
                            ->update([
                                'seen' => '0',
                                'raw_data' => 'Selesai Cetak',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);

                        $idProsesProduksi = Uuid::uuid4()->toString();
                        DB::table('proses_produksi_cetak')->insert([
                            'id' => $idProsesProduksi,
                            'order_cetak_id' => $request->id,
                        ]);
                        $dataLoop = DB::table('user_permission')
                            ->where('permission_id', 'a91ee437-1e08-11ed-87ce-1078d2a38ee5')
                            ->get();
                        $id_notif = Str::uuid()->getHex();
                        $sC = 'Proses Produksi Order Cetak';
                        DB::table('notif')->insert([
                            [
                                'id' => $id_notif,
                                'section' => 'Produksi',
                                'type' => $sC,
                                'permission_id' => 'a91ee437-1e08-11ed-87ce-1078d2a38ee5',
                                'form_id' => $idProsesProduksi,
                            ],
                        ]);
                        foreach ($dataLoop as $d) {
                            DB::table('notif_detail')->insert([
                                [
                                    'notif_id' => $id_notif,
                                    'user_id' => $d->user_id,
                                    // 'raw_data' => 'Produksi Baru',
                                ],
                            ]);
                        }
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Data berhasil diupdate'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda tidak memiliki akses'
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function pendingOrder($request)
    {
        try {
            $dataUser = DB::table('users')
                ->where('id', auth()->id())
                ->first();
            $dataPenyetujuan = DB::table('order_cetak_penyetujuan')
                ->where('order_cetak_id', $request->id)
                ->select('order_cetak_penyetujuan.*')
                ->first();
            if ($request->status_cetak == '3') {
                if ($dataPenyetujuan->m_stok_act == '1') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Manajer Stok belum melakukan persetujuan'
                    ]);
                }
            }
            if ($dataPenyetujuan->status_general == 'Selesai') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data sudah selesai di Approve'
                ]);
            } elseif ($dataPenyetujuan->status_general == 'Pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data dipending sampai ' . Carbon::parse($dataPenyetujuan->pending_sampai)->translatedFormat('d F Y')
                ]);
            }
            if ($dataUser->id == $dataPenyetujuan->d_operasional) {
                if ($dataPenyetujuan->d_operasional_act == '2') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data sudah Anda Pending'
                    ]);
                } elseif ($dataPenyetujuan->d_operasional_act == '3') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data sudah di Approve'
                    ]);
                }
                DB::table('order_cetak_penyetujuan')
                    ->where('order_cetak_id', $request->id)
                    ->update([
                        'd_operasional_act' => '2',
                        'ket_pending' => $request->keterangan,
                        'pending_sampai' => date('Y-m-d', strtotime($request->pending_sampai)),
                        'status_general' => 'Pending',
                    ]);
                $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                    ->where('form_id', $request->id)->first();
                if ($request->status_cetak == '3') {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_stok)
                        ->update([
                            'seen' => '0',
                            'raw_data' => 'Pending Cetak',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_penerbitan)
                        ->update([
                            'seen' => '0',
                            'raw_data' => 'Pending Cetak',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                }

                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                    ->update([
                        'seen' => '0',
                        'raw_data' => 'Pending Cetak',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                DB::table('notif_detail')
                    ->insert([
                        'notif_id' => $notif->id,
                        'user_id' => $dataPenyetujuan->d_keuangan,
                        'raw_data' => 'Pending Cetak',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                DB::table('notif_detail')
                    ->insert([
                        'notif_id' => $notif->id,
                        'user_id' => $dataPenyetujuan->d_utama,
                        'raw_data' => 'Pending Cetak',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil dipending'
                ]);
            } elseif ($dataUser->id == $dataPenyetujuan->d_keuangan) {
                if ($dataPenyetujuan->d_operasional_act == '1') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data belum di Approve oleh Direktur Operasional'
                    ]);
                } elseif ($dataPenyetujuan->d_keuangan_act == '2') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data sudah Anda Pending'
                    ]);
                } elseif ($dataPenyetujuan->d_keuangan_act == '3') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data sudah di Approve'
                    ]);
                }
                DB::table('order_cetak_penyetujuan')
                    ->where('order_cetak_id', $request->id)
                    ->update([
                        'd_keuangan_act' => '2',
                        'ket_pending' => $request->keterangan,
                        'pending_sampai' => date('Y-m-d', strtotime($request->pending_sampai)),
                        'status_general' => 'Pending',
                    ]);
                $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                    ->where('form_id', $request->id)->first();
                if ($request->status_cetak == '3') {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_stok)
                        ->update([
                            'seen' => '0',
                            'raw_data' => 'Pending Cetak',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_penerbitan)
                        ->update([
                            'seen' => '0',
                            'raw_data' => 'Pending Cetak',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                }

                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                    ->update([
                        'seen' => '0',
                        'raw_data' => 'Pending Cetak',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_keuangan)
                    ->update([
                        'seen' => '0',
                        'raw_data' => 'Pending Cetak',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                DB::table('notif_detail')
                    ->insert([
                        'notif_id' => $notif->id,
                        'user_id' => $dataPenyetujuan->d_utama,
                        'raw_data' => 'Pending Cetak',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil dipending'
                ]);
            } elseif ($dataUser->id == $dataPenyetujuan->d_utama) {
                if ($dataPenyetujuan->d_operasional_act == '1') {
                    if ($dataPenyetujuan->d_keuangan_act == '1') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Operasional dan Direktur Keuangan'
                        ]);
                    }
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data belum di Approve oleh Direktur Operasional'
                    ]);
                }
                DB::table('order_cetak_penyetujuan')
                    ->where('order_cetak_id', $request->id)
                    ->update([
                        'd_utama_act' => '2',
                        'ket_pending' => $request->keterangan,
                        'pending_sampai' => date('Y-m-d', strtotime($request->pending_sampai)),
                        'status_general' => 'Pending',
                    ]);
                $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                    ->where('form_id', $request->id)->first();
                if ($request->status_cetak == '3') {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_stok)
                        ->update([
                            'seen' => '0',
                            'raw_data' => 'Pending Cetak',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_penerbitan)
                        ->update([
                            'seen' => '0',
                            'raw_data' => 'Pending Cetak',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                }
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                    ->update([
                        'seen' => '0',
                        'raw_data' => 'Pending Cetak',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_keuangan)
                    ->update([
                        'seen' => '0',
                        'raw_data' => 'Pending Cetak',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_utama)
                    ->update([
                        'seen' => '0',
                        'raw_data' => 'Pending Cetak',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil dipending'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function getOrderId($tipeOrder)
    {
        $year = date('y');
        switch ($tipeOrder) {
            case 1:
                $lastId = DB::table('order_cetak')
                    ->where('kode_order', 'like', $year . '-%')
                    ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 1000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 2999")
                    ->orderBy('kode_order', 'desc')->first();

                $firstId = '1000';
                break;
            case 2:
                $lastId = DB::table('order_cetak')
                    ->where('kode_order', 'like', $year . '-%')
                    ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 3000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 3999")
                    ->orderBy('kode_order', 'desc')->first();
                $firstId = '3000';
                break;
            case 3:
                $lastId = DB::table('order_cetak')
                    ->where('kode_order', 'like', $year . '-%')
                    ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 4000")
                    ->orderBy('kode_order', 'desc')->first();
                $firstId = '4000';
                break;
            default:
                abort(500);
        }

        if (is_null($lastId)) {
            return $year . '-' . $firstId;
        } else {
            $lastId_ = (int)substr($lastId->kode_order, 3);
            if ($lastId_ == 2999) {
                abort(500);
            } elseif ($lastId_ == 3999) {
                abort(500);
            } else {

                return $year . '-' . strval($lastId_ + 1);
            }
        }
    }
    public function batalPendingOrderCetak($id)
    {
        try {
            $dataCetak = DB::table('order_cetak')
                ->where('id', $id)
                ->first();
            $dataPenyetujuan = DB::table('order_cetak_penyetujuan')
                ->where('order_cetak_id', $id)
                ->where('status_general', '=', 'Pending')
                ->first();
            if (is_null($dataPenyetujuan)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mungkin data sudah di Approve'
                ]);
            }
            $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                ->where('form_id', $id)->first();
            $dataUser = auth()->id();
            if ($dataUser == $dataPenyetujuan->d_operasional) {
                DB::table('order_cetak_penyetujuan')
                    ->where('order_cetak_id', $id)
                    ->update([
                        'd_operasional_act' => '1',
                        'ket_pending' => NULL,
                        'pending_sampai' => NULL,
                        'status_general' => 'Proses'
                    ]);
                if (is_null($dataPenyetujuan->m_stok)) {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_penerbitan)
                        ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                } else {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_stok)
                        ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                }
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                    ->update(['raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_keuangan)
                    ->delete();
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_utama)
                    ->delete();
            } elseif ($dataUser == $dataPenyetujuan->d_keuangan) {
                DB::table('order_cetak_penyetujuan')
                    ->where('order_cetak_id', $id)
                    ->update([
                        'd_keuangan_act' => '1',
                        'ket_pending' => NULL,
                        'pending_sampai' => NULL,
                        'status_general' => 'Proses'
                    ]);
                if (is_null($dataPenyetujuan->m_stok)) {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_penerbitan)
                        ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                } else {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_stok)
                        ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                }
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                    ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_keuangan)
                    ->update(['raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_utama)
                    ->delete();
            } elseif ($dataUser == $dataPenyetujuan->d_utama) {
                DB::table('order_cetak_penyetujuan')
                    ->where('order_cetak_id', $id)
                    ->update([
                        'd_utama_act' => '1',
                        'ket_pending' => NULL,
                        'pending_sampai' => NULL,
                        'status_general' => 'Proses'
                    ]);
                if (is_null($dataPenyetujuan->m_stok)) {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_penerbitan)
                        ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                } else {
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                        ->where('user_id', '=', $dataPenyetujuan->m_stok)
                        ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                }
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_operasional)
                    ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_keuangan)
                    ->update(['seen' => '1', 'raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->where('user_id', '=', $dataPenyetujuan->d_utama)
                    ->update(['raw_data' => 'Disetujui Cetak', 'updated_at' => date('Y-m-d H:i:s')]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses'
                ]);
            }
            return redirect()->to('/penerbitan/order-cetak/detail?kode=' . $dataCetak->id . '&author=' . $dataCetak->created_by);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
