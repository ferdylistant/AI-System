<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use App\Events\OrderCetakEvent;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Events\NotifikasiPenyetujuan;
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
                ->orderBy('kode_order','asc')
                ->select(
                    'oc.*',
                    'pn.kode',
                    'pn.jalur_buku',
                    'pn.id as naskah_id',
                    'dtc.tipe_order',
                    'dp.judul_final'
                )
                ->get();
            if ($request->has('count_data')) {
                return $data->count();
            } else {
                $update = Gate::allows('do_update', 'update-order-cetak');
                return DataTables::of($data)
                    ->addColumn('no_order', function ($data) {
                        $act = DB::table('order_cetak_action')
                            ->where('order_cetak_id', $data->id)
                            ->orderBy('id', 'desc')
                            ->first();
                        if (is_null($act)) {
                            if ($data->status_cetak == '3') {
                                if ((Gate::allows('do_approval', 'Persetujuan Penjualan & Stok')) && ($data->status == 'Proses')) {
                                    $tandaProses = '<span class="beep-danger"></span>';
                                    $dataKode = '<span class="text-danger">' . $data->kode_order . '</span>';
                                } else {
                                    $tandaProses = '';
                                    $dataKode = $data->kode_order;
                                }
                            } else {
                                if ((Gate::allows('do_approval', 'Persetujuan Penerbitan')) && ($data->status == 'Proses')) {
                                    $tandaProses = '<span class="beep-danger"></span>';
                                    $dataKode = '<span class="text-danger">' . $data->kode_order . '</span>';
                                } else {
                                    $tandaProses = '';
                                    $dataKode = $data->kode_order;
                                }
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
                                case 'Penjualan & Stok':
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
                    ->addColumn('status_cetak', function ($data) {
                        switch ($data->status_cetak) {
                            case '1':
                                $res = 'Buku Baru';
                                break;
                            case '2':
                                $res = 'Cetak Ulang Revisi';
                                break;
                            case '3':
                                $res = 'Cetak Ulang';
                                break;
                            default:
                                $res = '-';
                                break;
                        }
                        return $res;
                    })
                    ->addColumn('status_penyetujuan', function ($data) {
                        $res = DB::table('order_cetak_action')
                            ->where('order_cetak_id', $data->id)
                            ->get();
                        if (!$res->isEmpty()) {
                            foreach ($res as $r) {
                                $collect[] = $r->type_departemen;
                            }
                        } else {
                            $collect = [];
                        }
                        $type = DB::select(DB::raw("SHOW COLUMNS FROM order_cetak_action WHERE Field = 'type_departemen'"))[0]->Type;
                        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
                        $jabatan = explode("','", $matches[1]);
                        $statCetak = $data->status_cetak == '3' ? '0':'1'; //Except
                        $jabatan = Arr::except($jabatan,[$statCetak]);
                        $badge = '';
                        foreach ($jabatan as $jb => $j) {
                            if (in_array($j, $collect)) {
                                foreach ($res as $i => $action) {
                                    if ($j == $action->type_departemen) {
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
                        'status_cetak',
                        'status_penyetujuan',
                        'history',
                        'action'
                    ])
                    ->make(true);
            }
        }
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM order_cetak WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        return view('penerbitan.order_cetak.index', [
            'title' => 'Order Cetak Naskah',
            'status_progress' => $statusProgress
        ]);
    }
    public function updateOrderCetak(Request $request)
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
                'dc.warna as warna_cover',
                'dc.finishing_cover',
                'df.sub_judul_final',
                'df.isi_warna',
                'df.kertas_isi',
                'dp.judul_final',
                'dp.format_buku',
                'dp.nama_pena',
                'dp.imprint',
                'dp.jml_hal_perkiraan',
                'kb.nama',
                'pn.id as naskah_id',
                'pn.kelompok_buku_id',
                'pn.jalur_buku',
                'ps.isbn',
                'ps.jml_hal_final',
                'ps.edisi_cetak'
            )
            ->first();
        if ($request->ajax()) {
            if ($request->isMethod('GET')) {
                $penulis = DB::table('penerbitan_naskah_penulis as pnp')
                    ->join('penerbitan_penulis as pp', function ($q) {
                        $q->on('pnp.penulis_id', '=', 'pp.id')
                            ->whereNull('pp.deleted_at');
                    })
                    ->where('pnp.naskah_id', '=', $data->naskah_id)
                    ->select('pp.id', 'pp.nama')
                    ->get();
                $data = (object)collect($data)->map(function ($item, $key) {
                    switch ($key) {
                        case 'imprint':
                            return !is_null($item) ? DB::table('imprint')->where('id', $item)->whereNull('deleted_at')->first()->nama : '-';
                            break;
                        // case 'format_buku':
                        //     return !is_null($item) ? DB::table('format_buku')->where('id', $item)->whereNull('deleted_at')->first()->jenis_format : '-';
                        //     break;
                        case 'tgl_permintaan_jadi':
                            return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : '-';
                            break;
                        case 'status_cetak':
                            switch ($item) {
                                case '1':
                                    $item = 'Buku Baru';
                                    break;
                                case '2':
                                    $item = 'Cetak Ulang Revisi';
                                    break;
                                case '3':
                                    $item = 'Cetak Ulang';
                                    break;
                                default:
                                    $item = NULL;
                                    break;
                            }
                            return $item;
                            break;
                        default:
                            $item;
                            break;
                    }
                    return $item;
                })->all();
                if ($data->status == 'Proses' || ($data->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))) {
                    $disableBtn = false;
                    $cursorBtn = 'pointer';
                } else {
                    $disableBtn = true;
                    $cursorBtn = 'not-allowed';
                }
                return ['data' => $data, 'penulis' => $penulis,'disable' => $disableBtn,'cursor' => $cursorBtn];
            }
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('order_cetak as oc')
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
                        ->where('oc.id', $request->id)
                        ->select(
                            'oc.*',
                            'dtc.tipe_order',
                            'pp.platform_digital_ebook_id',
                            'pp.pilihan_terbit',
                            'dc.jilid',
                            'dc.warna as warna_cover',
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
                            'pn.kelompok_buku_id',
                            'pn.jalur_buku',
                            'ps.isbn',
                            'ps.jml_hal_final',
                            'ps.edisi_cetak'
                        )
                        ->first();
                    if (is_null($history)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Terjadi kesalahan!'
                        ]);
                    }
                    $fc = $request->up_finishing_cover ? json_encode(array_filter($request->up_finishing_cover)) : null;
                    $update = [
                        'params' => 'Update Order Cetak',
                        'id' => $request->id,
                        'edisi_cetak' => $request->up_edisi_cetak, //Pracetak Setter
                        'jml_hal_perkiraan' => $request->up_jml_hal_perkiraan, //Deskripsi Produk
                        'kelompok_buku_id' => $request->up_kelompok_buku, //Penerbitan Naskah
                        'tipe_order' => $request->up_tipe_order, //Deskripsi Turcet
                        'posisi_layout' => $request->up_posisi_layout,
                        'dami' => $request->up_dami,
                        'format_buku' => $request->up_format_buku, //Deskripsi Produk
                        'jilid' => $request->up_jilid, //Deskripsi Cover
                        'ukuran_jilid_binding' => $request->up_ukuran_jilid_binding,
                        'kertas_isi' => $request->up_kertas_isi, //Deskripsi Final
                        'isi_warna' => $request->up_isi_warna, //Deskripsi Final
                        'jenis_cover' => $request->up_jenis_cover,
                        'kertas_cover' => $request->up_kertas_cover,
                        'warna' => $request->up_warna_cover, //Deskripsi Cover
                        'finishing_cover' => $fc, //Deskripsi Cover
                        'buku_jadi' => $request->up_buku_jadi,
                        'jumlah_cetak' => $request->up_jumlah_cetak,
                        'tahun_terbit' => Carbon::createFromFormat('Y', $request->up_tahun_terbit)->format('Y'),
                        'tgl_permintaan_jadi' => Carbon::createFromFormat('d F Y', $request->up_tgl_permintaan_jadi)->format('Y-m-d'),
                        'spp' => $request->up_spp,
                        'buku_contoh' => $request->up_buku_contoh,
                        'keterangan' => $request->up_keterangan,
                        'perlengkapan' => $request->up_perlengkapan,
                    ];
                    event(new OrderCetakEvent($update));
                    $insert = [
                        'params' => 'Insert History Update Order Cetak',
                        'type_history' => 'Update',
                        'order_cetak_id' => $request->id,
                        'edisi_cetak_his' => $request->up_edisi_cetak == $history->edisi_cetak ? NULL : $history->edisi_cetak,
                        'edisi_cetak_new' => $request->up_edisi_cetak == $history->edisi_cetak ? NULL : $request->up_edisi_cetak,
                        'jml_hal_perkiraan_his' => $request->up_jml_hal_perkiraan == $history->jml_hal_perkiraan ? NULL : $history->jml_hal_perkiraan,
                        'jml_hal_perkiraan_new' => $request->up_jml_hal_perkiraan == $history->jml_hal_perkiraan ? NULL : $request->up_jml_hal_perkiraan,
                        'kelompok_buku_id_his' => $request->up_kelompok_buku == $history->kelompok_buku_id ? NULL : $history->kelompok_buku_id,
                        'kelompok_buku_id_new' => $request->up_kelompok_buku == $history->kelompok_buku_id ? NULL : $request->up_kelompok_buku,
                        'tipe_order_his' => $request->up_tipe_order == $history->tipe_order ? NULL : $history->tipe_order,
                        'tipe_order_new' => $request->up_tipe_order == $history->tipe_order ? NULL : $request->up_tipe_order,
                        'posisi_layout_his' => $request->up_posisi_layout == $history->posisi_layout ? NULL : $history->posisi_layout,
                        'posisi_layout_new' => $request->up_posisi_layout == $history->posisi_layout ? NULL : $request->up_posisi_layout,
                        'dami_his' => $request->up_dami == $history->dami ? NULL : $history->dami,
                        'dami_new' => $request->up_dami == $history->dami ? NULL : $request->up_dami,
                        'format_buku_his' => $request->up_format_buku == $history->format_buku ? NULL : $history->format_buku,
                        'format_buku_new' => $request->up_format_buku == $history->format_buku ? NULL : $request->up_format_buku,
                        'jilid_his' => $request->up_jilid == $history->jilid ? NULL : $history->jilid,
                        'jilid_new' => $request->up_jilid == $history->jilid ? NULL : $request->up_jilid,
                        'ukuran_binding_his' => $request->up_ukuran_jilid_binding == $history->ukuran_jilid_binding ? NULL : $history->ukuran_jilid_binding,
                        'ukuran_binding_new' => $request->up_ukuran_jilid_binding == $history->ukuran_jilid_binding ? NULL : $request->up_ukuran_jilid_binding,
                        'kertas_isi_his' => $request->up_kertas_isi == $history->kertas_isi ? NULL : $history->kertas_isi,
                        'kertas_isi_new' => $request->up_kertas_isi == $history->kertas_isi ? NULL : $request->up_kertas_isi,
                        'isi_warna_his' => $request->up_isi_warna == $history->isi_warna ? NULL : $history->isi_warna,
                        'isi_warna_new' => $request->up_isi_warna == $history->isi_warna ? NULL : $request->up_isi_warna,
                        'jenis_cover_his' => $request->up_jenis_cover == $history->jenis_cover ? NULL : $history->jenis_cover,
                        'jenis_cover_new' => $request->up_jenis_cover == $history->jenis_cover ? NULL : $request->up_jenis_cover,
                        'kertas_cover_his' => $request->up_kertas_cover == $history->kertas_cover ? NULL : $history->kertas_cover,
                        'kertas_cover_new' => $request->up_kertas_cover == $history->kertas_cover ? NULL : $request->up_kertas_cover,
                        'warna_cover_his' => $request->up_warna_cover == $history->warna_cover ? NULL : $history->warna_cover,
                        'warna_cover_new' => $request->up_warna_cover == $history->warna_cover ? NULL : $request->up_warna_cover,
                        'finishing_cover_his' => $history->finishing_cover == $fc ? NULL : $history->finishing_cover,
                        'finishing_cover_new' => $history->finishing_cover == $fc ? NULL : $fc,
                        'buku_jadi_his' => $request->up_buku_jadi == $history->buku_jadi ? NULL : $history->buku_jadi,
                        'buku_jadi_new' => $request->up_buku_jadi == $history->buku_jadi ? NULL : $request->up_buku_jadi,
                        'jumlah_cetak_his' => $request->up_jumlah_cetak == $history->jumlah_cetak ? NULL : $history->jumlah_cetak,
                        'jumlah_cetak_new' => $request->up_jumlah_cetak == $history->jumlah_cetak ? NULL : $request->up_jumlah_cetak,
                        'tahun_terbit_his' => date('Y-m', strtotime($request->up_tahun_terbit)) == date('Y-m', strtotime($history->tahun_terbit)) ? NULL : $history->tahun_terbit,
                        'tahun_terbit_new' => date('Y-m', strtotime($request->up_tahun_terbit)) == date('Y-m', strtotime($history->tahun_terbit)) ? NULL : Carbon::createFromFormat('Y', $request->up_tahun_terbit)->format('Y'),
                        'tgl_permintaan_jadi_his' => date('Y-m-d H:i:s', strtotime($request->up_tgl_permintaan_jadi)) == date('Y-m-d H:i:s', strtotime($history->tgl_permintaan_jadi)) ? NULL : date('Y-m-d H:i:s', strtotime($history->tgl_permintaan_jadi)),
                        'tgl_permintaan_jadi_new' => date('Y-m-d H:i:s', strtotime($request->up_tgl_permintaan_jadi)) == date('Y-m-d H:i:s', strtotime($history->tgl_permintaan_jadi)) ? NULL : Carbon::createFromFormat('d F Y', $request->up_tgl_permintaan_jadi)->format('Y-m-d'),
                        'spp_his' => $request->up_spp == $history->spp ? NULL : $history->spp,
                        'spp_new' => $request->up_spp == $history->spp ? NULL : $request->up_spp,
                        'buku_contoh_his' => $request->up_buku_contoh == $history->buku_contoh ? NULL : $history->buku_contoh,
                        'buku_contoh_new' => $request->up_buku_contoh == $history->buku_contoh ? NULL : $request->up_buku_contoh,
                        'keterangan_his' => $request->up_keterangan == $history->keterangan ? NULL : $history->keterangan,
                        'keterangan_new' => $request->up_keterangan == $history->keterangan ? NULL : $request->up_keterangan,
                        'perlengkapan_his' => $request->up_perlengkapan == $history->perlengkapan ? NULL : $history->perlengkapan,
                        'perlengkapan_new' => $request->up_perlengkapan == $history->perlengkapan ? NULL : $request->up_perlengkapan,
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
        $tipeOrd = array(['id' => 1, 'name' => 'Umum'], ['id' => 2, 'name' => 'Rohani']);
        $pilihanTerbit = DB::table('pilihan_penerbitan')->get();
        $platformDigital = DB::table('platform_digital_ebook')->whereNull('deleted_at')->get();
        $kbuku = DB::table('penerbitan_m_kelompok_buku')
            ->get();
        $nama_pena = json_decode($data->nama_pena);
        $format_buku_list = DB::table('format_buku')->whereNull('deleted_at')->get();
        $buku_jadi = ['Wrapping', 'Tidak Wrapping'];
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_cover WHERE Field = 'jilid'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $jilid = explode("','", $matches[1]);
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'kertas_isi'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $kertas_isi = explode("','", $matches[1]);
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'isi_warna'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $isi_warna = explode("','", $matches[1]);
        $finishing_cover = ['Embosh', 'Foil', 'Glossy', 'Laminasi Dof', 'UV', 'UV Spot'];
        return view('penerbitan.order_cetak.edit', [
            'title' => 'Update Order Cetak',
            'tipeOrd' => $tipeOrd,
            'pilihanTerbit' => $pilihanTerbit,
            'platformDigital' => $platformDigital,
            'kbuku' => $kbuku,
            'data' => $data,
            'jilid' => $jilid,
            'format_buku_list' => $format_buku_list,
            'kertas_isi' => $kertas_isi,
            'finishing_cover' => $finishing_cover,
            'isi_warna' => $isi_warna,
            'buku_jadi' => $buku_jadi,
            'nama_pena' => is_null($data->nama_pena)?"-":implode(",",$nama_pena)

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
                'dp.nama_pena',
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
            return abort(404);
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->get();

        //Data Action
        $act = DB::table('order_cetak_action')->where('order_cetak_id', $data->id)->get();
        if (!$act->isEmpty()) {
            foreach ($act as $a) {
                $act_j[] = $a->type_departemen;
            }
        } else {
            $act_j = NULL;
        }
        //List Jabatan Approval
        $type = DB::select(DB::raw("SHOW COLUMNS FROM order_cetak_action WHERE Field = 'type_departemen'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $departemen = explode("','", $matches[1]);
        $statCetak = $data->status_cetak == '3' ? '0':'1'; //Except
        $departemen = Arr::except($departemen,[$statCetak]);
        $imprint = NULL;
        if (!is_null($data->imprint)) {
            $imprint = DB::table('imprint')->where('id', $data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id',$data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        return view('penerbitan.order_cetak.detail', [
            'title' => 'Detail Order Cetak Buku',
            'data' => $data,
            'penulis' => $penulis,
            'act' => $act,
            'act_j' => $act_j,
            'departemen' => $departemen,
            'imprint' => $imprint,
            'format_buku' => $format_buku,
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
                $role = DB::table('order_cetak_action')->where('order_cetak_id', $id)->groupBy('id')->get();
                if (!$role->isEmpty()) {
                    foreach ($role as $r) {
                        if (!Gate::allows('do_approval', 'Persetujuan ' . $r->type_departemen)) {
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
            if (Gate::allows('do_approval', 'Persetujuan Penerbitan')) {
                $btn = $this->panelStatusGuest($status, $btn);
            } elseif (Gate::allows('do_approval', 'Persetujuan Marketing & Ops')) {
                $btn = $this->panelStatusGuest($status, $btn);
            } elseif (Gate::allows('do_approval', 'Persetujuan Keuangan')) {
                $btn = $this->panelStatusGuest($status, $btn);
            } elseif (Gate::allows('do_approval', 'Persetujuan Direktur Utama')) {
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
        $id = $request->id;
        $data = DB::table('order_cetak as oc')
            ->join('deskripsi_turun_cetak as dtc','dtc.id','=','oc.deskripsi_turun_cetak_id')
            ->join('pracetak_cover as pc','pc.id','=','dtc.pracetak_cover_id')
            ->join('deskripsi_cover as dc','dc.id','=','pc.deskripsi_cover_id')
            ->join('deskripsi_produk as dp','dp.id','=','dc.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn','pn.id','=','dp.naskah_id')
            ->where('oc.id', $id)
            ->select(
                'oc.*',
                'dp.naskah_id',
                'dp.judul_final',
                'pn.kode'
                )
            ->first();
        switch ($cat) {
            case 'approve':
                return $this->approvalOrderCetak($request,$data);
                break;
            case 'decline':
                return $this->declineOrderCetak($request,$data);
                break;
            case 'approve-detail':
                return $this->approvalDetailOrderCetak($request);
                break;
            case 'decline-detail':
                return $this->declineDetailOrderCetak($request);
                break;
            case 'update-status-progress':
                return $this->updateStatusProgress($request,$data);
                break;
            case 'lihat-history-order-cetak':
                return $this->lihatHistoryOrderCetak($request);
                break;
            case 'modal-cetak-ulang':
                return $this->showModalTambahCetakUlang();
                break;
            case 'submit-cetul':
                return $this->submitCetakUlang($request);
                break;
            default:
                abort(500);
        }
    }
    protected function updateStatusProgress($request,$data)
    {
        try {
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ], 404);
            }
            $penilaian = DB::table('order_cetak_action as oca')
                ->where('order_cetak_id', $data->id)->where('type_departemen', 'Direktur Utama')
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
                if (is_null($data->spp)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'SPP belum diinput!'
                    ]);
                }
                event(new OrderCetakEvent($update));
                event(new OrderCetakEvent($insert));
                //Update Todo List Admin
                switch ($data->status_cetak) {
                    case '1':
                        $titleTodo = 'Lengkapi data order cetak untuk naskah "'.$data->judul_final.'".';
                        break;
                    case '2':
                        $titleTodo = 'Lengkapi data order cetak untuk naskah "'.$data->judul_final.'".';
                        break;
                    case '3':
                        $titleTodo = '(Cetak Ulang) Lengkapi data order cetak untuk naskah "'.$data->judul_final.'".';
                        break;
                    default:
                        $titleTodo = '';
                        break;
                }
                $data = collect($data)->put('title_todo',$titleTodo);
                $permissionAdmin = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                        ->join('users as u','u.id','=','up.user_id')
                        ->join('jabatan as j','j.id','=','u.jabatan_id')
                        ->where('p.id','=','e0860766d564483e870b5974a601649c')
                        ->where('j.nama','LIKE','%Administrasi%')
                        ->select('up.user_id')
                        ->get();
                $permissionAdmin = (object)collect($permissionAdmin)->map(function($item) use ($data) {
                    $cekTodo = DB::table('todo_list')
                    ->where('form_id',$data->id)
                    ->where('users_id',$item->user_id)
                    ->where('title',$data->title_todo);
                    if (is_null($cekTodo->first())) {
                        return DB::table('todo_list')->insert([
                            'form_id' => $data->id,
                            'users_id' => $item->user_id,
                            'title' => $data->title_todo,
                            'link' => '/penerbitan/order-cetak/edit?order='.$data->id.'&naskah='.$data->kode,
                            'status' => '1'
                        ]);
                    } else {
                        $cekTodo->update([
                            'status' => '1'
                        ]);
                    }
                })->all();
                //INSERT TODO LIST PRODUKSI CETAK
                    //? BELUM

                //INSERT TIMELINE
                $updateTimelineOrderCetak = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Order Cetak',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineOrderCetak));
                $msg = 'Order Cetak selesai, silahkan lanjut ke proses produksi cetak..';
            } else {
                if ($request->status == 'Proses') {
                    //TODO LIST
                    //?Dep Penerbitan
                    $depPenerbitan = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','09179170e6e643eca66b282e2ffae1f8')
                    ->select('up.user_id')
                    ->get();
                    $depPenerbitan = (object)collect($depPenerbitan)->map(function($item) use ($data) {
                        $cekEksisPenerbitan = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Departemen Penerbitan untuk naskah "'.$data->judul_final.'".');
                        $cekAction = DB::table('order_cetak_action')
                        ->where('order_cetak_id',$data->id)
                        ->where('users_id',$item->user_id)->first();
                        $status = is_null($cekAction) ? '0':'1';
                        if (!is_null($cekEksisPenerbitan->first())){
                            $cekEksisPenerbitan->update([
                                'status' => $status
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Departemen Penerbitan untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => $status
                            ]);
                        }
                    })->all();
                    //?Dep Pemasaran
                    $depPemasaran = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','4d64a842e08344b9aeec88ed9eb2eb72')
                    ->select('up.user_id')
                    ->get();
                    $depPemasaran = (object)collect($depPemasaran)->map(function($item) use ($data) {
                        $cekEksisPemasaran = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Departemen Operasional dan Pemasaran untuk naskah "'.$data->judul_final.'".');
                        $cekAction = DB::table('order_cetak_action')
                        ->where('order_cetak_id',$data->id)
                        ->where('users_id',$item->user_id)->first();
                        $status = is_null($cekAction) ? '0':'1';
                        if (!is_null($cekEksisPemasaran->first())){
                            $cekEksisPemasaran->update([
                                'status' => $status
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Departemen Operasional dan Pemasaran untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => $status
                            ]);
                        }
                    })->all();
                    //?Dep Keuangan
                    $depKeuangan = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','5a009edf56ba4c64a7df1820a5fea34f')
                    ->select('up.user_id')
                    ->get();
                    $depKeuangan = (object)collect($depKeuangan)->map(function($item) use ($data) {
                        $cekEksisKeuangan = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Departemen Keuangan untuk naskah "'.$data->judul_final.'".');
                        $cekAction = DB::table('order_cetak_action')
                        ->where('order_cetak_id',$data->id)
                        ->where('users_id',$item->user_id)->first();
                        $status = is_null($cekAction) ? '0':'1';
                        if (!is_null($cekEksisKeuangan->first())){
                            $cekEksisKeuangan->update([
                                'status' => $status
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Departemen Keuangan untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => $status
                            ]);
                        }
                    })->all();
                    //?Dir Utama
                    $dirUtama = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','9b4e52c30f974844ac7a050000a0ee6a')
                    ->select('up.user_id')
                    ->get();
                    $dirUtama = (object)collect($dirUtama)->map(function($item) use ($data) {
                        $cekEksisDirUtama = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Direktur Utama untuk naskah "'.$data->judul_final.'".');
                        $cekAction = DB::table('order_cetak_action')
                        ->where('order_cetak_id',$data->id)
                        ->where('users_id',$item->user_id)->first();
                        $status = is_null($cekAction) ? '0':'1';
                        if (!is_null($cekEksisDirUtama->first())){
                            $cekEksisDirUtama->update([
                                'status' => $status
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Direktur Utama untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => $status
                            ]);
                        }
                    })->all();
                }
                event(new OrderCetakEvent($update));
                event(new OrderCetakEvent($insert));
                $updateTimelineOrderCetak = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Order Cetak',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineOrderCetak));
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
    protected function approvalOrderCetak($request,$data)
    {
        try {
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ], 404);
            }
            $permission = DB::table('permissions')
                ->where('raw', $request->type_departemen)
                ->first();
            // return response()->json($request->type_departemen);
            $insert = [
                'params' => 'Insert Action',
                'order_cetak_id' => $request->id,
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
                    //?Dep Penerbitan
                    $depPenerbitan = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','09179170e6e643eca66b282e2ffae1f8')
                    ->select('up.user_id')
                    ->get();
                    $depPenerbitan = (object)collect($depPenerbitan)->map(function($item) use ($data) {
                        $cekEksisPenerbitan = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Departemen Penerbitan untuk naskah "'.$data->judul_final.'".');
                        if (!is_null($cekEksisPenerbitan->first())){
                            $cekEksisPenerbitan->update([
                                'status' => '1'
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Departemen Penerbitan untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => '1'
                            ]);
                        }
                    })->all();
                    break;
                case 'Marketing & Ops':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Keuangan'];
                    //?Dep Pemasaran
                    $depPemasaran = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','4d64a842e08344b9aeec88ed9eb2eb72')
                    ->select('up.user_id')
                    ->get();
                    $depPemasaran = (object)collect($depPemasaran)->map(function($item) use ($data) {
                        $cekEksisPemasaran = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Departemen Operasional dan Pemasaran untuk naskah "'.$data->judul_final.'".');
                        if (!is_null($cekEksisPemasaran->first())){
                            $cekEksisPemasaran->update([
                                'status' => '1'
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Departemen Operasional dan Pemasaran untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => '1'
                            ]);
                        }
                    })->all();
                    break;
                case 'Keuangan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Direktur Utama'];
                    //?Dep Keuangan
                    $depKeuangan = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','5a009edf56ba4c64a7df1820a5fea34f')
                    ->select('up.user_id')
                    ->get();
                    $depKeuangan = (object)collect($depKeuangan)->map(function($item) use ($data) {
                        $cekEksisKeuangan = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Departemen Keuangan untuk naskah "'.$data->judul_final.'".');
                        if (!is_null($cekEksisKeuangan->first())){
                            $cekEksisKeuangan->update([
                                'status' => '1'
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Departemen Keuangan untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => '1'
                            ]);
                        }
                    })->all();
                    break;
                case 'Direktur Utama':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Penerbitan', 'Marketing & Ops', 'Direktur Keuangan'];
                    //?Update Todo List
                    $dirUtama = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','9b4e52c30f974844ac7a050000a0ee6a')
                    ->select('up.user_id')
                    ->get();
                    $dirUtama = (object)collect($dirUtama)->map(function($item) use ($data) {
                        $cekEksisDirUtama = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Direktur Utama untuk naskah "'.$data->judul_final.'".');
                        if (!is_null($cekEksisDirUtama->first())){
                            $cekEksisDirUtama->update([
                                'status' => '1'
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Direktur Utama untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => '1'
                            ]);
                        }
                    })->all();
                    break;
            }
            $userPermission = DB::table('permissions as p', 'p.id', '=', 'up.permission_id')
                ->where('p.access_id', $permission->access_id)
                ->whereIn('p.raw', $raw)
                ->get();
            $userPermission = (object)collect($userPermission)->map(function($item) {

                return DB::table('user_permission')->where('permission_id', $item->id)->get();
            });
            $notif = [
                'params' => 'Insert Notif',
                'id' => $id_notif,
                'section' => 'Penerbitan',
                'type' => 'Terima Order E-Book',
                'permission_id' => is_null($permission->id) ? NULL : $permission->id,
                'form_id' => $request->id,
                'users_id' =>  $userPermission
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
    protected function declineOrderCetak($request,$data)
    {
        try {
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ], 404);
            }
            $permission = DB::table('permissions')
                ->where('raw', $request->type_departemen)
                ->first();
            // return response()->json($request->type_departemen);
            $insert = [
                'params' => 'Insert Action',
                'order_cetak_id' => $request->id,
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
                    //?Dep Penerbitan
                    $depPenerbitan = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','09179170e6e643eca66b282e2ffae1f8')
                    ->select('up.user_id')
                    ->get();
                    $depPenerbitan = (object)collect($depPenerbitan)->map(function($item) use ($data) {
                        $cekEksisPenerbitan = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Departemen Penerbitan untuk naskah "'.$data->judul_final.'".');
                        if (!is_null($cekEksisPenerbitan->first())){
                            $cekEksisPenerbitan->update([
                                'status' => '1'
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Departemen Penerbitan untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => '1'
                            ]);
                        }
                    })->all();
                    break;
                case 'Marketing & Ops':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Keuangan'];
                    //?Dep Pemasaran
                    $depPemasaran = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','4d64a842e08344b9aeec88ed9eb2eb72')
                    ->select('up.user_id')
                    ->get();
                    $depPemasaran = (object)collect($depPemasaran)->map(function($item) use ($data) {
                        $cekEksisPemasaran = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Departemen Operasional dan Pemasaran untuk naskah "'.$data->judul_final.'".');
                        if (!is_null($cekEksisPemasaran->first())){
                            $cekEksisPemasaran->update([
                                'status' => '1'
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Departemen Operasional dan Pemasaran untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => '1'
                            ]);
                        }
                    })->all();
                    break;
                case 'Keuangan':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Direktur Utama'];
                    //?Dep Keuangan
                    $depKeuangan = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','5a009edf56ba4c64a7df1820a5fea34f')
                    ->select('up.user_id')
                    ->get();
                    $depKeuangan = (object)collect($depKeuangan)->map(function($item) use ($data) {
                        $cekEksisKeuangan = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Departemen Keuangan untuk naskah "'.$data->judul_final.'".');
                        if (!is_null($cekEksisKeuangan->first())){
                            $cekEksisKeuangan->update([
                                'status' => '1'
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Departemen Keuangan untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => '1'
                            ]);
                        }
                    })->all();
                    break;
                case 'Direktur Utama':
                    $id_notif = Str::uuid()->getHex();
                    $raw = ['Penerbitan', 'Marketing & Ops', 'Keuangan'];
                    //?Update Todo List
                    $dirUtama = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                    ->where('p.id','9b4e52c30f974844ac7a050000a0ee6a')
                    ->select('up.user_id')
                    ->get();
                    $dirUtama = (object)collect($dirUtama)->map(function($item) use ($data) {
                        $cekEksisDirUtama = DB::table('todo_list')
                        ->where('form_id',$data->id)
                        ->where('users_id',$item->user_id)
                        ->where('title','Persetujuan order cetak sebagai perwakilan Direktur Utama untuk naskah "'.$data->judul_final.'".');
                        if (!is_null($cekEksisDirUtama->first())){
                            $cekEksisDirUtama->update([
                                'status' => '1'
                            ]);
                        } else {
                            DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Persetujuan order cetak sebagai perwakilan Direktur Utama untuk naskah "'.$data->judul_final.'".',
                                'link' => '/penerbitan/order-cetak/detail?order=' . $data->id . '&naskah=' . $data->kode,
                                'status' => '1'
                            ]);
                        }
                    })->all();
                    break;
            }
            $userPermission = DB::table('permissions as p', 'p.id', '=', 'up.permission_id')
                ->where('p.access_id', $permission->access_id)
                ->whereIn('p.raw', $raw)
                ->get();
            $userPermission = (object)collect($userPermission)->map(function($item) {

                return DB::table('user_permission')->where('permission_id', $item->id)->get();
            });
            $notif = [
                'params' => 'Insert Notif',
                'id' => $id_notif,
                'section' => 'Penerbitan',
                'type' => 'Tolak Order E-Book',
                'permission_id' => is_null($permission->id) ? NULL : $permission->id,
                'form_id' => $request->id,
                'users_id' =>  $userPermission
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
            ->where('id', $id)
            ->first();
        $users = DB::table('users')->where('id', $data->users_id)->first();
        $users = (object)collect($users)->map(function($item,$key) {
            if ($key == 'nama') {
                return strtoupper($item);
            }  elseif ($key == 'jabatan_id') {
                return DB::table('jabatan')->where('id',$item)->first()->nama;
            } else {
                return $item;
            }
        })->all();
        $fetch = [
            // 'jabatan' => $data->type_departemen,
            'users' => $users,
            'catatan' => $data->catatan_action,
            'tgl' => Carbon::parse($data->tgl_action)->translatedFormat('l d F Y, H:i')
        ];
        return response()->json($fetch);
    }
    protected function declineDetailOrderCetak($request)
    {
        $id = $request->id;
        $data = DB::table('order_cetak_action')
            ->where('id', $id)
            ->first();
        $users = DB::table('users')->where('id', $data->users_id)->first();
        $users = (object)collect($users)->map(function($item,$key) {
            if ($key == 'nama') {
                return strtoupper($item);
            } elseif ($key == 'jabatan_id') {
                return DB::table('jabatan')->where('id',$item)->first()->nama;
            } else {
                return $item;
            }
        })->all();
        $fetch = [
            'jabatan' => $data->type_departemen,
            'users' => $users,
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
                    case 'Approval':
                        $lbl = is_null($d->catatan_action) ? '' : 'dengan catatan: ';
                        $catatan = is_null($d->catatan_action) ? 'tanpa catatan' : $d->catatan_action;
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Order cetak telah disetujui ' . $lbl . '<b class="text-dark">' . $catatan . '</b>.</span>
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
                            <span><span class="bullet"></span> Order cetak telah ditolak ' . $lbl . '<b class="text-dark">' . $catatan . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Update':
                        $html .= '<span class="ticket-item">';
                        if (!is_null($d->status_cetak_his)) {
                            switch ($d->status_cetak_his) {
                                case 1:
                                    $scHis = 'Buku Baru';
                                    break;
                                case 2:
                                    $scHis = 'Cetak Ulang Revisi';
                                    break;
                                case 3:
                                    $scHis = 'Cetak Ulang';
                                    break;
                            }
                            switch ($d->status_cetak_new) {
                                case 1:
                                    $scNew = 'Buku Baru';
                                    break;
                                case 2:
                                    $scNew = 'Cetak Ulang Revisi';
                                    break;
                                case 3:
                                    $scNew = 'Cetak Ulang';
                                    break;
                            }
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Status cetak <b class="text-dark">' . $scHis . '</b> diubah menjadi <b class="text-dark">' . $scNew . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->status_cetak_new)) {
                            switch ($d->status_cetak_new) {
                                case 1:
                                    $scNew = 'Buku Baru';
                                    break;
                                case 2:
                                    $scNew = 'Cetak Ulang Revisi';
                                    break;
                                case 3:
                                    $scNew = 'Cetak Ulang';
                                    break;
                            }
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Status cetak <b class="text-dark">' . $scNew . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->jml_hal_perkiraan_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman <b class="text-dark">' . $d->jml_hal_perkiraan_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_hal_perkiraan_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->jml_hal_perkiraan_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman <b class="text-dark">' . $d->jml_hal_perkiraan_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->kelompok_buku_id_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Kelompok buku <b class="text-dark">' . DB::table('penerbitan_m_kelompok_buku')->where('id', $d->kelompok_buku_id_his)->first()->nama . '</b> diubah menjadi <b class="text-dark">' . $d->kelompok_buku_id_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->kelompok_buku_id_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Kelompok buku <b class="text-dark">' . DB::table('penerbitan_m_kelompok_buku')->where('id', $d->kelompok_buku_id_new)->first()->nama . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->tipe_order_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Tipe order <b class="text-dark">' . $d->tipe_order_his . '</b> diubah menjadi <b class="text-dark">' . $d->tipe_order_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->tipe_order_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Tipe order <b class="text-dark">' . $d->tipe_order_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->posisi_layout_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Posisi layout <b class="text-dark">' . $d->posisi_layout_his . '</b> diubah menjadi <b class="text-dark">' . $d->posisi_layout_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->posisi_layout_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Posisi layout <b class="text-dark">' . $d->posisi_layout_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->dami_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Dami <b class="text-dark">' . $d->dami_his . '</b> diubah menjadi <b class="text-dark">' . $d->dami_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->dami_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Dami <b class="text-dark">' . $d->dami_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->jilid_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jilid <b class="text-dark">' . $d->jilid_his . '</b> diubah menjadi <b class="text-dark">' . $d->jilid_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->jilid_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jilid <b class="text-dark">' . $d->jilid_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->ukuran_binding_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Kertas isi <b class="text-dark">' . $d->ukuran_binding_his . '</b> diubah menjadi <b class="text-dark">' . $d->ukuran_binding_new . ' cm</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->ukuran_binding_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Kertas isi <b class="text-dark">' . $d->ukuran_binding_new . ' cm</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->kertas_isi_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Kertas isi <b class="text-dark">' . $d->kertas_isi_his . '</b> diubah menjadi <b class="text-dark">' . $d->kertas_isi_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->kertas_isi_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Kertas isi <b class="text-dark">' . $d->kertas_isi_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->isi_warna_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Isi warna <b class="text-dark">' . $d->isi_warna_his . '</b> diubah menjadi <b class="text-dark">' . $d->isi_warna_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->isi_warna_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Isi warna <b class="text-dark">' . $d->isi_warna_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->jenis_cover_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jenis cover <b class="text-dark">' . $d->jenis_cover_his . '</b> diubah menjadi <b class="text-dark">' . $d->jenis_cover_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->jenis_cover_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jenis cover <b class="text-dark">' . $d->jenis_cover_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->kertas_cover_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Kertas cover <b class="text-dark">' . $d->kertas_cover_his . '</b> diubah menjadi <b class="text-dark">' . $d->kertas_cover_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->kertas_cover_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Kertas cover <b class="text-dark">' . $d->kertas_cover_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->warna_cover_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Warna cover <b class="text-dark">' . $d->warna_cover_his . '</b> diubah menjadi <b class="text-dark">' . $d->warna_cover_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->warna_cover_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Warna cover <b class="text-dark">' . $d->warna_cover_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->finishing_cover_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $loopEfekHIS = '';
                            $loopEfekNEW = '';
                            foreach (json_decode($d->finishing_cover_his, true) as $efekthis) {
                                $loopEfekHIS .= '<b class="text-dark">' . $efekthis . '</b>, ';
                            }
                            foreach (json_decode($d->finishing_cover_new, true) as $efeknew) {
                                $loopEfekNEW .= '<span class="bullet"></span>' . $efeknew;
                            }
                            $html .= ' Efek cover <b class="text-dark">' . $loopEfekHIS . '</b> diubah menjadi <b class="text-dark">' . $loopEfekNEW . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->finishing_cover_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $loopEfekNEW = '';
                            foreach (json_decode($d->finishing_cover_new, true) as $efeknew) {
                                $loopEfekNEW .= '<b class="text-dark">' . $efeknew . '</b>, ';
                            }
                            $html .= ' Efek cover <b class="text-dark">' . $loopEfekNEW . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->buku_jadi_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Buku jadi <b class="text-dark">' . $d->buku_jadi_his . '</b> diubah menjadi <b class="text-dark">' . $d->buku_jadi_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->buku_jadi_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Buku jadi <b class="text-dark">' . $d->buku_jadi_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->jumlah_cetak_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah cetak <b class="text-dark">' . $d->jumlah_cetak_his . '</b> diubah menjadi <b class="text-dark">' . $d->jumlah_cetak_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->jumlah_cetak_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah cetak <b class="text-dark">' . $d->jumlah_cetak_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->format_buku_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Format buku <b class="text-dark">' . DB::table('format_buku')->where('id',$d->format_buku_his)->first()->jenis_format . ' cm</b> diubah menjadi <b class="text-dark">' . DB::table('format_buku')->where('id',$d->format_buku_new)->first()->jenis_format . ' cm</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->format_buku_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Format buku <b class="text-dark">' . DB::table('format_buku')->where('id',$d->format_buku_new)->first()->jenis_format . ' cm</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->edisi_cetak_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Edisi cetak <b class="text-dark">' . $d->edisi_cetak_his . '</b> diubah menjadi <b class="text-dark">' . $d->edisi_cetak_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->edisi_cetak_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Edisi cetak <b class="text-dark">' . $d->edisi_cetak_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->spp_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' SPP <b class="text-dark">' . $d->spp_his . '</b> diubah menjadi <b class="text-dark">' . $d->spp_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->spp_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' SPP <b class="text-dark">' . $d->spp_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->keterangan_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Keterangan <b class="text-dark">' . $d->keterangan_his . '</b> diubah menjadi <b class="text-dark">' . $d->keterangan_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->keterangan_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Keterangan <b class="text-dark">' . $d->keterangan_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->perlengkapan_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Perlengkapan <b class="text-dark">' . $d->perlengkapan_his . '</b> diubah menjadi <b class="text-dark">' . $d->perlengkapan_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->perlengkapan_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Perlengkapan <b class="text-dark">' . $d->perlengkapan_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->buku_contoh_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Buku contoh <b class="text-dark">' . $d->buku_contoh_his . '</b> diubah menjadi <b class="text-dark">' . $d->buku_contoh_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->buku_contoh_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' buku_contoh <b class="text-dark">' . $d->buku_contoh_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
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
    protected function showModalTambahCetakUlang()
    {
        try {
            $data = DB::table('order_cetak as oc')
            ->join('deskripsi_turun_cetak as dtc','dtc.id','=','oc.deskripsi_turun_cetak_id')
            ->join('pracetak_cover as pc','pc.id','=','dtc.pracetak_cover_id')
            ->join('deskripsi_cover as dc','dc.id','=','pc.deskripsi_cover_id')
            ->join('deskripsi_produk as dp','dp.id','=','dc.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn','pn.id','=','dp.naskah_id')
            ->groupBy('pn.kode')
            ->select(
                'oc.*',
                'dp.naskah_id',
                'dp.judul_final',
                'pn.kode'
                )
            ->get();
            $html = '';
            $btn = '';
            if ($data->isEmpty()) {
                $html .= '<div class="col-12 offset-3 mt-5">
                <div class="row">
                    <div class="col-4 offset-1">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" width="100%">
                        <p class="pt-2 font-weight-bold">Naskah order cetak belum ada</p>
                    </div>
                </div>
            </div>';
            $btn .= '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>';
            } else {
                $html .= '<div class="form-group">
                  <label for="naskahOrderCetak" class="col-form-label">Naskah:<span class="text-danger">*</span></label>
                  <select class="form-control select-naskah-cetul" name="naskah_baru" id="naskahOrderCetak" required>
                  <option label="Pilih naskah"></option> ';
                foreach($data as $d) {
                    $html .= '<option value="naskah='.$d->kode.'&orcet_id='.$d->id.'">'.$d->kode.' &mdash; '.$d->judul_final.'</option>';
                }
                $html .= '</select>
                </div>';
            $btn .= '<button type="submit" class="d-block btn btn-sm btn-outline-primary btn-block btn-cetak-ulang" id="btnRedirectCetul">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>';
            }
            return [
                'data' => $html,
                'btn' => $btn
            ];
        } catch (\Exception $e) {
            return abort(500);
        }
    }
    protected function submitCetakUlang($request)
    {
        try {
            DB::beginTransaction();
            $id = $request->orcet_id;
            $naskah = $request->naskah;
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
                'dc.warna as warna_cover',
                'dc.finishing_cover',
                'df.sub_judul_final',
                'df.isi_warna',
                'df.kertas_isi',
                'dp.judul_final',
                'dp.format_buku',
                'dp.nama_pena',
                'dp.imprint',
                'dp.jml_hal_perkiraan',
                'kb.nama',
                'pn.id as naskah_id',
                'pn.kelompok_buku_id',
                'pn.jalur_buku',
                'pn.kode',
                'ps.isbn',
                'ps.jml_hal_final',
                'ps.edisi_cetak'
            )
            ->first();
            if (is_null($data)) {
                return abort(404);
            }
            $idOrder = Uuid::uuid4()->toString();
            $order = [
                'id' => $idOrder,
                'deskripsi_turun_cetak_id' => $data->deskripsi_turun_cetak_id,
                'kode_order' => self::getOrderCetakId($data->tipe_order),
                'status_cetak' => '3',
                'posisi_layout' => $data->posisi_layout,
                'dami' => $data->dami,
                'jenis_cover' => $data->jenis_cover,
                'kertas_cover' => $data->kertas_cover,
                'ukuran_jilid_binding' => $data->ukuran_jilid_binding,
                'tahun_terbit' => $data->tahun_terbit,
                'buku_jadi' => $data->buku_jadi,
                'jumlah_cetak' => $data->jumlah_cetak,
                'buku_contoh' => $data->buku_contoh,
                'spp' => $data->spp,
                'keterangan' => $data->keterangan,
                'perlengkapan' => $data->perlengkapan,
                'tgl_permintaan_jadi' => $data->tgl_permintaan_jadi,
                'tgl_masuk' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            ];
            DB::table('order_cetak')->insert($order);
            //INSERT TODO LIST ADMIN
            $permissionAdmin = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
            ->join('users as u','u.id','=','up.user_id')
            ->join('jabatan as j','j.id','=','u.jabatan_id')
            ->where('p.id','=','e0860766d564483e870b5974a601649c')
            ->where('j.nama','LIKE','%Administrasi%')
            ->select('up.user_id')
            ->get();
            $data = collect($data)->put('id_order',$idOrder);
            $permissionAdmin = (object)collect($permissionAdmin)->map(function($item) use ($data) {
                return DB::table('todo_list')->insert([
                    'form_id' => $data['id_order'],
                    'users_id' => $item->user_id,
                    'title' => '(Cetak Ulang) Lengkapi data order cetak untuk naskah "'.$data['judul_final'].'".',
                    'link' => '/penerbitan/order-cetak/edit?order='.$data['id_order'].'&naskah='.$data['kode'],
                    'status' => '0'
                ]);
            })->all();
            DB::commit();
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500,$e->getMessage());
        }
    }
    protected function getOrderCetakId($tipeOrder)
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
}
