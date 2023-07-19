<?php

namespace App\Http\Controllers\Produksi;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Events\TrackerEvent;
use Illuminate\Http\Request;
use App\Events\ProduksiEvent;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Events\convertNumberToRoman;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class ProsesProduksiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('proses_produksi_cetak as ppc')
                ->join('order_cetak as oc', 'oc.id', '=', 'ppc.order_cetak_id')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
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
                ->orderBy('oc.kode_order', 'asc')
                ->select(
                    'ppc.*',
                    'oc.kode_order',
                    'oc.status_cetak',
                    'oc.tahun_terbit',
                    'oc.tgl_permintaan_jadi',
                    'oc.jumlah_cetak',
                    'oc.ukuran_jilid_binding',
                    'oc.jenis_cover',
                    'oc.keterangan',
                    'oc.posisi_layout',
                    'oc.keterangan',
                    'oc.dami',
                    'oc.buku_jadi',
                    'ps.edisi_cetak',
                    'ps.pengajuan_harga',
                    'df.kertas_isi',
                    'dp.judul_final',
                    'dp.naskah_id',
                    'dp.format_buku',
                    'dp.jml_hal_perkiraan',
                    'dc.jilid as jenis_jilid',
                    'dc.finishing_cover',
                    'dc.warna',
                    'pn.kode',
                    'kb.nama'
                )
                ->get();
            if ($request->isMethod('GET')) {
                if ($request->has('modal')) {
                    $response = $this->modalTrackProduksi($request);
                    return $response;
                }
                $update = Gate::allows('do_update', 'pic-data-produksi');
                return DataTables::of($data)
                    ->addColumn('kode_order', function ($data) {
                        return $data->kode_order;
                    })
                    ->addColumn('kode', function ($data) {
                        return $data->kode;
                    })
                    ->addColumn('naskah_dari_divisi', function ($data) {
                        return $data->naskah_dari_divisi;
                    })
                    ->addColumn('judul_final', function ($data) {
                        return $data->judul_final;
                    })
                    ->addColumn('status_cetak', function ($data) {
                        switch ($data->status_cetak) {
                            case 1:
                                $res = 'Buku Baru';
                                break;
                            case 2:
                                $res = 'Cetak Ulang Revisi';
                                break;
                            case 3:
                                $res = 'Cetak Ulang';
                                break;
                            default:
                                $res = '-';
                                break;
                        }
                        return $res;
                    })
                    ->addColumn('penulis', function ($data) {
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
                    })
                    ->addColumn('edisi_cetak', function ($data) {
                        $roman = event(new convertNumberToRoman($data->edisi_cetak));
                        $edisiCetak = implode('', $roman) . '/' . $data->edisi_cetak;
                        return $edisiCetak . '/' . $data->tahun_terbit;
                    })
                    ->addColumn('jenis_jilid', function ($data) {
                        if ($data->jenis_jilid == 'Binding') {
                            $ukuran = $data->ukuran_jilid_binding ?? '-';
                            $res = $data->jenis_jilid . ' ' . $ukuran;
                        } else {
                            $res = $data->jenis_jilid;
                        }
                        return $res;
                    })
                    ->addColumn('buku_jadi', function ($data) {
                        return $data->buku_jadi;
                    })
                    ->addColumn('tracking', function ($data) use ($update) {
                        $badge = '';
                        $res = DB::table('proses_produksi_track')
                            ->where('produksi_id', $data->id)
                            ->orderBy('id', 'desc')
                            ->get();
                        if (!$res->isEmpty()) {
                            foreach ($res as $r) {
                                $collect[] = $r->proses_tahap;
                            }
                        } else {
                            $collect = [];
                        }
                        $type = DB::select(DB::raw("SHOW COLUMNS FROM proses_produksi_track WHERE Field = 'proses_tahap'"))[0]->Type;
                        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
                        $masterProses = explode("','", $matches[1]);
                        if ($data->buku_jadi != 'Wrapping') {
                            $masterProses = Arr::except($masterProses, ['5']);
                        }
                        $dataId = 'data-id="' . $data->id . '"';
                        $tglMulai = 'data-tglmulai=""';
                        $tglSelesai = 'data-tglselesai=""';
                        foreach ($masterProses as $m) {
                            if (in_array($m, $collect)) {
                                foreach ($res as $status) {
                                    if ($m == $status->proses_tahap) {
                                        switch ($status->status) {
                                            case 'pending':
                                                $tglMulai = 'data-tglmulai="' . $status->tgl_mulai . '"';
                                                $lbl = 'danger';
                                                break;
                                            case 'sedang dalam proses':
                                                $tglMulai = 'data-tglmulai="' . $status->tgl_mulai . '"';
                                                $lbl = 'warning';
                                                break;
                                            case 'selesai':
                                                $tglMulai = 'data-tglmulai="' . $status->tgl_mulai . '"';
                                                $tglSelesai = 'data-tglselesai="' . $status->tgl_selesai . '"';
                                                $lbl = 'success';
                                                break;
                                        }
                                        if ($update) {
                                            $badge .= '<a href="javascript:void(0)"
                                            class="text-' . $lbl . ' text-small text-decoration-none d-block font-600-bold"
                                            data-status="' . $status->status . '" data-type="' . $m . '" ' . $dataId . ' ' . $tglMulai . ' ' . $tglSelesai . ' data-toggle="modal" data-target="#modalTrackProduksi" data-backdrop="static">
                                            <span class="bullet"></span> ' . $m . '
                                            </a>';
                                        } else {
                                            $badge .= '<span class="text-' . $lbl . ' text-small text-decoration-none d-block font-600-bold">
                                            <span class="bullet"></span> ' . $m . '
                                            </span>';
                                        }
                                    }
                                }
                            } else {
                                if ($update) {
                                    $badge .= '<a href="javascript:void(0)"
                                    class="text-muted text-small text-decoration-none d-block font-600-bold"
                                    data-status="belum selesai" data-type="' . $m . '" ' . $dataId . ' ' . $tglMulai . ' ' . $tglSelesai . ' data-toggle="modal" data-target="#modalTrackProduksi" data-backdrop="static">
                                    <span class="bullet"></span> ' . $m . '
                                    </a>';
                                } else {
                                    $badge .= '<span class="text-muted text-small text-decoration-none d-block font-600-bold">
                                    <span class="bullet"></span> ' . $m . '
                                    </span>';
                                }
                            }
                        }
                        return $badge;
                    })
                    ->addColumn('tracking_timeline', function ($data) {
                        $historyData = DB::table('tracker')->where('section_id', $data->id)->get();
                        if ($historyData->isEmpty()) {
                            return '-';
                        } else {
                            $date = '<button type="button" class="btn btn-sm btn-info btn-icon mr-1 btn-tracker" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-file-signature"></i>&nbsp;Lihat Tracking</button>';
                            return $date;
                        }
                    })
                    ->addColumn('action', function ($data) use ($update) {
                        $btn = '<a href="' . url('produksi/proses/cetak/detail?no=' . $data->kode_order . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                        if ($update) {
                            $btn .= '<a href="' . url('produksi/proses/cetak/edit?no=' . $data->kode_order . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                        }
                        return $btn;
                    })
                    ->rawColumns([
                        'kode_order',
                        'kode',
                        'naskah_dari_divisi',
                        'judul_final',
                        'status_cetak',
                        'penulis',
                        'edisi_cetak',
                        'jenis_jilid',
                        'buku_jadi',
                        'tracking',
                        'tracking_timeline',
                        'action'
                    ])
                    ->make(true);
            }
        }

        return view('produksi.proses_cetak.index', [
            'title' => 'Proses Produksi Cetak'
        ]);
    }
    public function detailProduksi(Request $request)
    {
        $kode = $request->get('kode');
        $track = $request->get('track');
        $data = DB::table('proses_produksi_cetak as ppc')
            ->join('produksi_order_cetak as poc', 'poc.id', '=', 'ppc.order_cetak_id')
            ->where('ppc.id', $track)
            ->where('ppc.order_cetak_id', $kode)
            ->select(
                'ppc.*',
                'poc.id as order_cetak_id',
                'poc.tipe_order',
                'poc.kode_order',
                'poc.judul_buku',
                'poc.sub_judul',
                'poc.penulis',
                'poc.edisi_cetakan',
                'poc.buku_jadi',
                'poc.tahun_terbit',
                'poc.status_cetak',
                'poc.format_buku',
                'poc.created_at as tanggal_order',
                'poc.tgl_permintaan_jadi',
                'poc.jumlah_cetak',
                'poc.jumlah_halaman',
                'poc.jilid as jenis_jilid',
                'poc.kertas_isi',
                'poc.efek_cover',
                'poc.keterangan',
                'poc.warna_cover',
                'poc.ukuran_jilid_bending as ukuran_bending'
            )
            ->first();
        if (is_null($data)) {
            return redirect()->route('proses.cetak.view');
        }

        return view('produksi.proses_cetak.detail', [
            'title' => 'Detail Data Proses Produksi Order Cetak',
            'data' => $data,
        ]);
    }
    public function updateProduksi(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                DB::table('proses_produksi_cetak')
                    ->where('id', $request->id)
                    ->update([
                        'katern' => $request->katern,
                        'mesin' => $request->mesin,
                        'plat' => $request->plat == '' ? NULL : date('Y-m-d', strtotime($request->plat)),
                        'cetak_isi' => $request->cetak_isi == '' ? NULL : date('Y-m-d', strtotime($request->cetak_isi)),
                        'cover' => $request->cover == '' ? NULL : date('Y-m-d', strtotime($request->cover)),
                        'lipat_isi' => $request->lipat_isi == '' ? NULL : date('Y-m-d', strtotime($request->lipat_isi)),
                        'jilid' => $request->jilid == '' ? NULL : date('Y-m-d', strtotime($request->jilid)),
                        'potong_3_sisi' => $request->potong_3_sisi == '' ? NULL : date('Y-m-d', strtotime($request->potong_3_sisi)),
                        'wrapping' => $request->wrapping == '' ? NULL : date('Y-m-d', strtotime($request->wrapping)),
                        'kirim_gudang' => $request->kirim_gudang == '' ? NULL : date('Y-m-d', strtotime($request->kirim_gudang)),
                        'harga' => $request->harga,
                    ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil diubah',
                    'route' => route('proses.cetak.view')
                ]);
            }
        }
        $kodeOr = $request->get('kode');
        $track = $request->get('track');
        $data = DB::table('proses_produksi_cetak as ppc')->join('produksi_order_cetak as poc', 'poc.id', '=', 'ppc.order_cetak_id')
            ->where('ppc.order_cetak_id', $kodeOr)
            ->where('ppc.id', $track)
            ->select(
                'ppc.*',
                'poc.id as order_cetak_id',
                'poc.tipe_order',
                'poc.kode_order',
                'poc.judul_buku',
                'poc.sub_judul',
                'poc.penulis',
                'poc.edisi_cetakan',
                'poc.buku_jadi',
                'poc.tahun_terbit',
                'poc.status_cetak',
                'poc.format_buku',
                'poc.created_at as tanggal_order',
                'poc.tgl_permintaan_jadi',
                'poc.jumlah_cetak',
                'poc.jumlah_halaman',
                'poc.jilid as jenis_jilid',
                'poc.kertas_isi',
                'poc.efek_cover',
                'poc.keterangan',
                'poc.warna_cover',
                'poc.ukuran_jilid_bending as ukuran_bending'
            )
            ->first();
        return view('produksi.proses_cetak.update', [
            'title' => 'Update Proses Produksi Order Cetak',
            'data' => $data,
        ]);
    }
    public function ajaxCall(Request $request)
    {
        switch ($request->cat) {
            case 'select':
                return $this->selectMaster($request);
                break;
            case 'selected':
                return $this->selectedMaster($request);
                break;
            case 'submit-track':
                return $this->submitTrack($request);
                break;
            case 'submit-edit-riwayat':
                return $this->submitEditRiwayat($request);
                break;
            case 'delete-riwayat-track':
                return $this->deleteRiwayatTrack($request);
                break;
            case 'show-modal-edit-riwayat':
                return $this->showModalEditRiwayat($request);
                break;
            case 'lihat-tracking-timeline':
                return $this->lihatTrackingProduksi($request);
                break;
            default:
                return abort(400);
                break;
        }
    }
    protected function lihatTrackingProduksi($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('tracker')->where('section_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
            foreach ($data as $d) {
                $html .= '<div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">
                    <i class="' . $d->icon . '"></i>
                </div>
                <div class="activity-detail col">
                    <div class="mb-2">
                        <span class="text-job">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Jakarta')->diffForHumans() . '</span>
                        <span class="bullet"></span>
                        <span class="text-job">' . Carbon::parse($d->created_at)->translatedFormat('l d M Y, H:i') . '</span>
                    </div>
                    <p>' . $d->description . '</p>
                </div>
            </div>';
            }
            return $html;
        }
    }
    protected function modalTrackProduksi($request)
    {
        try {
            $id = $request->id;
            $typeProses = $request->type;
            $status = $request->status;
            $tglmulai = $request->tglmulai;
            $tglselesai = $request->tglselesai;
            $data = DB::table('proses_produksi_cetak as ppc')
                ->join('order_cetak as oc', 'oc.id', '=', 'ppc.order_cetak_id')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->where('ppc.id', $id)
                ->select(
                    'ppc.*',
                    'oc.jumlah_cetak',
                    'oc.buku_jadi',
                    'dp.judul_final'
                )->first();
            if (!$data) {
                return abort(404);
            }
            $trackData = DB::table('proses_produksi_track')->where('produksi_id', $id)->where('proses_tahap',$typeProses)->first();
            $mesin = DB::table('proses_produksi_master')->where('type', 'M')->get();
            $opr = DB::table('proses_produksi_master')->where('type', 'O')->get();
            $type = DB::select(DB::raw("SHOW COLUMNS FROM proses_produksi_track WHERE Field = 'status'"))[0]->Type;
            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
            $masterStatus = explode("','", $matches[1]);
            $masterStatus = Arr::except($masterStatus, ['0']);
            if ($typeProses == 'Kirim Gudang') {
                $res = $this->modalContentPrivateIf($disable = '', $id, $status, $footer = '', $masterStatus, $addContent = '', $content = '', $tglmulai, $tglselesai, $data, $trackData);
            } else {
                $res = $this->modalContentPrivateElse($status, $content = '', $addContent = '', $masterStatus, $footer = '', $sel = '', $disable = '', $tglselesai, $tglmulai, $trackData, $mesin, $opr);
            }
            return [
                'id' => $id,
                'proses_tahap' => $typeProses,
                'badge' => $res['badge'],
                'status' => $status,
                'sectionTitle' => 'Proses Tahap "<b>' . $typeProses . '</b>"',
                'data' => $data,
                'content' => $res['content'],
                'footer' => $res['footer'],
                'trackData'=> $trackData
            ];
        } catch (\Exception $e) {
            return abort(500, $e->getMessage());
        }
    }
    private function modalContentPrivateIf($disable, $id, $status, $footer, $masterStatus, $addContent, $content, $tglmulai, $tglselesai, $data, $trackData)
    {
        switch ($status) {
            case 'belum selesai':
                $badge = 'badge badge-light';
                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                break;
            case 'sedang dalam proses':
                $masterStatus = Arr::except($masterStatus, ['1']);
                $kirimGudang = DB::table('proses_produksi_track_riwayat')->where('track_id',$trackData->id)->where('track',$trackData->proses_tahap)
                ->orderBy('created_at','asc')->get();
                $totalDiterima = DB::table('proses_produksi_track_riwayat')
                ->whereNotNull('tgl_diterima')
                ->where('track_id',$trackData->id)
                ->where('track',$trackData->proses_tahap)
                ->orderBy('created_at','asc')
                ->select(DB::raw('IFNULL(SUM(jml_dikirim),0) as total_diterima'))
                ->get();
                $addContent .='<div class="form-group">
                        <label for="historyKirim">Riwayat Kirim</label>
                            <div class="scroll-riwayat">
                            <table class="table table-striped" style="width:100%" id="tableRiwayatKirim">
                            <thead style="position: sticky;top:0">
                              <tr>
                                <th scope="col" style="background: #eee;">No</th>
                                <th scope="col" style="background: #eee;">Tanggal Kirim</th>
                                <th scope="col" style="background: #eee;">Otorisasi Oleh</th>
                                <th scope="col" style="background: #eee;">Tanggal Diterima</th>
                                <th scope="col" style="background: #eee;">Penerima</th>
                                <th scope="col" style="background: #eee;">Jumlah Kirim</th>
                                <th scope="col" style="background: #eee;">Action</th>
                              </tr>
                            </thead>
                            <tbody>';
                            $totalKirim = NULL;
                            foreach ($kirimGudang as $i => $kg) {
                                $i++;
                                if(!is_null($kg->tgl_diterima)) {
                                    $btnAction = '<span class="badge badge-light">No action</span>';
                                } else {
                                    $btnAction = '<a href="javascript:void(0)"
                                    class="btn-block btn btn-sm btn-outline-warning btn-icon mr-1 mt-1" id="btnEditRiwayatKirim" data-id="'.$kg->id.'" data-dibuat="'.Carbon::parse($kg->created_at)->translatedFormat('l, d M Y - H:i:s').'" data-toggle="modal" data-target="#modalEditRiwayatKirim">
                                    <i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)"
                                    class="btn-block btn btn-sm btn-outline-danger btn-icon mr-1 mt-1" id="btnDeleteRiwayatKirim" data-id="'.$kg->id.'" data-toggle="tooltip" title="Hapus Data">
                                    <i class="fas fa-trash"></i></a>';
                                }
                                $kg = (object)collect($kg)->map(function($item,$key){
                                    switch ($key) {
                                        case 'users_id':
                                            return DB::table('users')->where('id',$item)->first()->nama;
                                            break;
                                        case 'diterima_oleh':
                                            return is_null($item) ? '-':DB::table('users')->where('id',$item)->first()->nama;
                                            break;
                                        case 'tgl_diterima':
                                            return is_null($item) ? '<small class="badge badge-danger">menunggu</small>':$item;
                                            break;
                                        case 'created_at':
                                            return Carbon::parse($item)->format('d-m-Y H:i:s');
                                            break;
                                        default:
                                            return is_null($item) ? '-':$item;
                                            break;
                                    }
                                })->all();
                                $addContent .='<tr id="index_'.$kg->id.'">
                                  <td id="row_num'. $i . '">'.$i.'<input type="hidden" name="task_number[]" value=' . $i . '></td>
                                  <td>'.$kg->created_at.'</td>
                                  <td>'.$kg->users_id.'</td>
                                  <td>'.$kg->tgl_diterima.'</td>
                                  <td>'.$kg->diterima_oleh.'</td>
                                  <td id="indexJmlKirim'.$kg->id.'">'.$kg->jml_dikirim.' eks</td>
                                  <td>'.$btnAction.'</td>
                                </tr>';
                                $totalKirim +=$kg->jml_dikirim;
                            }
                            $addContent .='</tbody>
                          </table>
                            </div>
                            </div>
                            <div class="alert d-flex justify-content-between" style="background: #141517;
                            background: -webkit-linear-gradient(to right, #6777ef, #141517);
                            background: linear-gradient(to right, #6777ef, #141517);
                            " role="alert">
                                <div class="col-auto">
                                    <span class="bullet"></span><span>Total Kirim</span><br>
                                    <span class="bullet"></span><span>Total Diterima  <a href="javascript:void(0)" class="text-warning" tabindex="0" role="button"
                                    data-toggle="popover" data-trigger="focus" title="Informasi"
                                    data-content="Total diterima adalah total yang diterima dan diotorisasi oleh departemen Penjualan & Stok.">
                                    <abbr title="">
                                    <i class="fas fa-info-circle me-3"></i>
                                    </abbr>
                                    </a></span>
                                </div>
                                <div class="col-auto">
                                    <span class="text-center" id="totDikirim">'.$totalKirim.' eks</span><br>
                                    <span class="text-center">'.$totalDiterima[0]->total_diterima.' eks</span>
                                </div>
                            </div>';
                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                $badge = 'badge badge-warning';
                break;
            case 'pending':
                $masterStatus = Arr::except($masterStatus, ['2', '3']);
                $addContent .= '<div class="form-group">
                        <label for="tglMulai">Tanggal Mulai</label>
                        <p id="tglMulai">' . Carbon::parse($tglmulai)->translatedFormat('l d F Y, H:i') . '</p>
                        </div>';
                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                $badge = 'badge badge-danger';
                break;
            case 'selesai':
                foreach ($masterStatus as $ms) {
                    if ($status == $ms) {
                        $sel = ' selected="selected" ';
                    }
                }
                $kirimGudang = DB::table('proses_produksi_track_riwayat')->where('track_id',$trackData->id)->where('track',$trackData->proses_tahap)
                ->orderBy('created_at','asc')->get();
                $totalDiterima = DB::table('proses_produksi_track_riwayat')
                ->whereNotNull('tgl_diterima')
                ->where('track_id',$trackData->id)
                ->where('track',$trackData->proses_tahap)
                ->orderBy('created_at','asc')
                ->select(DB::raw('IFNULL(SUM(jml_dikirim),0) as total_diterima'))
                ->get();
                $addContent .='<div class="form-group">
                        <label for="historyKirim">Riwayat Kirim</label>
                            <div class="scroll-riwayat">
                            <table class="table table-striped" style="width:100%" id="tableRiwayatKirim">
                            <thead style="position: sticky;top:0">
                              <tr>
                                <th scope="col" style="background: #eee;">No</th>
                                <th scope="col" style="background: #eee;">Tanggal Kirim</th>
                                <th scope="col" style="background: #eee;">Otorisasi Oleh</th>
                                <th scope="col" style="background: #eee;">Tanggal Diterima</th>
                                <th scope="col" style="background: #eee;">Penerima</th>
                                <th scope="col" style="background: #eee;">Jumlah Kirim</th>
                                <th scope="col" style="background: #eee;">Action</th>
                              </tr>
                            </thead>
                            <tbody>';
                            $totalKirim = NULL;
                            foreach ($kirimGudang as $i => $kg) {
                                $i++;
                                $kg = (object)collect($kg)->map(function($item,$key){
                                    switch ($key) {
                                        case 'users_id':
                                            return DB::table('users')->where('id',$item)->first()->nama;
                                            break;
                                        case 'diterima_oleh':
                                            return is_null($item) ? '-':DB::table('users')->where('id',$item)->first()->nama;
                                            break;
                                        case 'tgl_diterima':
                                            return is_null($item) ? '<small class="badge badge-danger">menunggu</small>':$item;
                                            break;
                                        case 'created_at':
                                            return Carbon::parse($item)->format('d-m-Y H:i:s');
                                            break;
                                        default:
                                            return is_null($item) ? '-':$item;
                                            break;
                                    }
                                })->all();
                                $addContent .='<tr id="index_'.$kg->id.'">
                                  <td id="row_num'. $i . '">'.$i.'<input type="hidden" name="task_number[]" value=' . $i . '></td>
                                  <td>'.$kg->created_at.'</td>
                                  <td>'.$kg->users_id.'</td>
                                  <td>'.$kg->tgl_diterima.'</td>
                                  <td>'.$kg->diterima_oleh.'</td>
                                  <td>'.$kg->jml_dikirim.' eks</td>
                                  <td><span class="badge badge-light">No action</span></td>
                                </tr>';
                                $totalKirim +=$kg->jml_dikirim;
                            }
                            $addContent .='</tbody>
                          </table>
                            </div>
                            </div>
                            <div class="alert d-flex justify-content-between" style="background: #141517;
                            background: -webkit-linear-gradient(to right, #6777ef, #141517);
                            background: linear-gradient(to right, #6777ef, #141517);
                            " role="alert">
                                <div class="col-auto">
                                    <span class="bullet"></span><span>Total Kirim</span><br>
                                    <span class="bullet"></span><span>Total Diterima  <a href="javascript:void(0)" class="text-warning" tabindex="0" role="button"
                                    data-toggle="popover" data-trigger="focus" title="Informasi"
                                    data-content="Jumlah oplah hanya dapat diinput oleh departemen penerbitan.">
                                    <abbr title="">
                                    <i class="fas fa-info-circle me-3"></i>
                                    </abbr>
                                    </a></span>
                                </div>
                                <div class="col-auto">
                                    <span class="text-center">'.$totalKirim.' eks</span><br>
                                    <span class="text-center">'.$totalDiterima[0]->total_diterima.' eks</span>
                                </div>
                            </div>';
                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>';
                $badge = 'badge badge-success';
                $disable = 'disabled';
                break;
        }
        $content .= '<input type="hidden" name="buku_jadi" value="'.$data->buku_jadi.'">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="jmlCetak">Jumlah Oplah <a href="javascript:void(0)" class="text-primary" tabindex="0" role="button"
                            data-toggle="popover" data-trigger="focus" title="Informasi"
                            data-content="Jumlah oplah hanya dapat diinput oleh departemen penerbitan.">
                            <abbr title="">
                            <i class="fas fa-info-circle me-3"></i>
                            </abbr>
                            </a></label>
                            <input name="jml_cetak" class="form-control" id="jmlCetak" value="' . $data->jumlah_cetak . '" readonly>
                            </div>
                            <div class="form-group col-md-6">
                            <label for="jmlDikirim">Jumlah Dikirim (<span class="text-danger">*</span>)</label>
                            <input name="jml_dikirim" class="form-control" id="jmlDikirim" ' . $disable . '>
                            <span id="err_jml_dikirim"></span>
                            </div>
                        </div>';
        $content .= $addContent;
        return [
            'badge' => $badge,
            'content' => $content,
            'footer' => $footer
        ];
    }
    private function modalContentPrivateElse($status, $content, $addContent, $masterStatus, $footer, $sel, $disable, $tglselesai, $tglmulai, $trackData, $mesin, $opr)
    {
        switch ($status) {
            case 'belum selesai':
                $masterStatus = Arr::except($masterStatus, ['3']);
                $badge = 'badge badge-light';
                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                break;
            case 'sedang dalam proses':
                // $masterStatus = Arr::except($masterStatus, ['1']);
                $riwayat= DB::table('proses_produksi_track_riwayat')->where('track_id',$trackData->id)->where('track',$trackData->proses_tahap)
                ->orderBy('created_at','desc')
                ->get();
                $addContent .= '<div class="form-group">
                <label for="tglMulai">Riwayat</label>
                <div class="tickets-list example-1 scrollbar-deep-purple bordered-deep-purple thin">';
                foreach ($riwayat as $d) {
                    $addContent .= '<span class="ticket-item">
                    <div class="ticket-title"><span class="bullet"></span>';
                    if (!is_null($d->mesin_new)) {
                        $addContent .= ' Jenis mesin (<b class="text-dark">' .
                        DB::table('proses_produksi_master')->where('id',$d->mesin_new)->first()->nama
                        . '</b>) ditambahkan.<br>';
                    }
                    if (!is_null($d->operator_new)) {
                        $textOpr = (array)collect(json_decode($d->operator_new))->map(function($item) {
                            return DB::table('proses_produksi_master')->where('type','O')->where('id',$item)->first()->nama;
                        })->all();
                        $addContent .= ' Operator mesin (<b class="text-dark">' . implode(', ',$textOpr) . '</b>) ditambahkan.<br>';
                    }
                    if (!is_null($d->status_new)) {
                        switch ($d->status_new) {
                            case 'sedang dalam proses':
                                $clr = 'warning';
                                break;
                            case 'pending':
                                $clr = 'danger';
                                break;
                            case 'selesai':
                                $clr = 'success';
                                break;
                        }
                        $addContent .= ' Status (<b class="text-'.$clr.'">' . $d->status_new . '</b>) ditambahkan.<br>';
                    }
                    if (!is_null($d->tgl_pending)) {
                        $addContent .= ' Tanggal dipending (<b class="text-dark">' . $d->tgl_pending . '</b>).<br>';
                    }
                    if (!is_null($d->tgl_pending)) {
                        $addContent .= ' Tanggal dipending (<b class="text-dark">' . $d->tgl_pending . '</b>).<br>';
                    }
                    if (!is_null($d->tgl_selesai)) {
                        $addContent .= ' Selesai pada tanggal (<b class="text-dark">' . $d->tgl_selesai . '</b>).<br>';
                    }
                    if (!is_null($d->tgl_mulai)) {
                        $addContent .= ' Pengerjaan dimulai pada tanggal (<b class="text-dark">' . $d->tgl_mulai . '</b>).<br>';
                    }
                    $addContent .= '</div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Otorisasi oleh <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . DB::table('users')->where('id',$d->users_id)->whereNull('deleted_at')->first()->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->created_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                }
                $addContent .='</div>
                </div>';
                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                $badge = 'badge badge-warning';
                break;
            case 'pending':
                $masterStatus = Arr::except($masterStatus, ['2', '3']);
                $riwayat= DB::table('proses_produksi_track_riwayat')->where('track_id',$trackData->id)->where('track',$trackData->proses_tahap)
                ->orderBy('created_at','desc')->get();
                $addContent .= '<div class="form-group">
                <label for="tglMulai">Riwayat</label>
                <div class="tickets-list example-1 scrollbar-deep-purple bordered-deep-purple thin">';
                foreach ($riwayat as $d) {
                    $addContent .= '<span class="ticket-item">
                    <div class="ticket-title"><span class="bullet"></span>';
                    if (!is_null($d->mesin_new)) {
                        $addContent .= ' Jenis mesin (<b class="text-dark">' .
                        DB::table('proses_produksi_master')->where('id',$d->mesin_new)->first()->nama
                        . '</b>) ditambahkan.<br>';
                    }
                    if (!is_null($d->operator_new)) {
                        $textOpr = (array)collect(json_decode($d->operator_new))->map(function($item) {
                            return DB::table('proses_produksi_master')->where('type','O')->where('id',$item)->first()->nama;
                        })->all();
                        $addContent .= ' Operator mesin (<b class="text-dark">' . implode(', ',$textOpr) . '</b>) ditambahkan.<br>';
                    }
                    if (!is_null($d->status_new)) {
                        switch ($d->status_new) {
                            case 'sedang dalam proses':
                                $clr = 'warning';
                                break;
                            case 'pending':
                                $clr = 'danger';
                                break;
                            case 'selesai':
                                $clr = 'success';
                                break;
                        }
                        $addContent .= ' Status (<b class="text-'.$clr.'">' . $d->status_new . '</b>) ditambahkan.<br>';
                    }
                    if (!is_null($d->tgl_pending)) {
                        $addContent .= ' Tanggal dipending (<b class="text-dark">' . $d->tgl_pending . '</b>).<br>';
                    }
                    if (!is_null($d->tgl_selesai)) {
                        $addContent .= ' Selesai pada tanggal (<b class="text-dark">' . $d->tgl_selesai . '</b>).<br>';
                    }
                    if (!is_null($d->tgl_mulai)) {
                        $addContent .= ' Pengerjaan dimulai pada tanggal (<b class="text-dark">' . $d->tgl_mulai . '</b>).<br>';
                    }
                    $addContent .= '</div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Otorisasi oleh <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . DB::table('users')->where('id',$d->users_id)->whereNull('deleted_at')->first()->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->created_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                }
                $addContent .='</div>
                </div>';
                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                $badge = 'badge badge-danger';
                break;
            case 'selesai':
                foreach ($masterStatus as $ms) {
                    if ($status == $ms) {
                        $sel = ' selected="selected" ';
                    }
                }
                $riwayat= DB::table('proses_produksi_track_riwayat')->where('track_id',$trackData->id)->where('track',$trackData->proses_tahap)
                ->orderBy('created_at','desc')->get();
                $addContent .= '<div class="form-group">
                <label for="tglMulai">Riwayat</label>
                <div class="tickets-list example-1 scrollbar-deep-purple bordered-deep-purple thin">';
                foreach ($riwayat as $d) {
                    $addContent .= '<span class="ticket-item">
                    <div class="ticket-title"><span class="bullet"></span>';
                    if (!is_null($d->mesin_new)) {
                        $addContent .= ' Jenis mesin (<b class="text-dark">' .
                        DB::table('proses_produksi_master')->where('id',$d->mesin_new)->first()->nama
                        . '</b>) ditambahkan.<br>';
                    }
                    if (!is_null($d->operator_new)) {
                        $textOpr = (array)collect(json_decode($d->operator_new))->map(function($item) {
                            return DB::table('proses_produksi_master')->where('type','O')->where('id',$item)->first()->nama;
                        })->all();
                        $addContent .= ' Operator mesin (<b class="text-dark">' . implode(', ',$textOpr) . '</b>) ditambahkan.<br>';
                    }
                    if (!is_null($d->status_new)) {
                        switch ($d->status_new) {
                            case 'sedang dalam proses':
                                $clr = 'warning';
                                break;
                            case 'pending':
                                $clr = 'danger';
                                break;
                            case 'selesai':
                                $clr = 'success';
                                break;
                        }
                        $addContent .= ' Status (<b class="text-'.$clr.'">' . $d->status_new . '</b>) ditambahkan.<br>';
                    }
                    if (!is_null($d->tgl_pending)) {
                        $addContent .= ' Tanggal dipending (<b class="text-dark">' . $d->tgl_pending . '</b>).<br>';
                    }
                    if (!is_null($d->tgl_selesai)) {
                        $addContent .= ' Selesai pada tanggal (<b class="text-dark">' . $d->tgl_selesai . '</b>).<br>';
                    }
                    if (!is_null($d->tgl_mulai)) {
                        $addContent .= ' Pengerjaan dimulai pada tanggal (<b class="text-dark">' . $d->tgl_mulai . '</b>).<br>';
                    }
                    $addContent .= '</div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Otorisasi oleh <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . DB::table('users')->where('id',$d->users_id)->whereNull('deleted_at')->first()->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->created_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                }
                $addContent .='</div>
                </div>';
                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>';
                $badge = 'badge badge-success';
                $disable = 'disabled';
                break;
        }
        $content .= '
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="jenisMesin">Jenis Mesin (<span class="text-danger">*</span>)</label>
                    <select name="mesin" class="form-control select-mesin" id="jenisMesin" ' . $disable . '>
                            <option label="Pilih mesin"></option>
                    </select>
                    <span id="err_mesin"></span>
                    </div>
                    <div class="form-group col-md-6">
                    <label for="operatorId">Operator (<span class="text-danger">*</span>)</label>
                    <select name="operator[]" class="form-control select-operator" id="operatorId" multiple="multiple" required ' . $disable . '>
                            <option label="Pilih operator"></option>
                    </select>
                    <span id="err_operator"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="actionStatus">Action (<span class="text-danger">*</span>)</label>
                    <select name="status" class="form-control select-status" id="actionStatus" ' . $disable . '>
                        <option label="Pilih status"></option>';
        foreach ($masterStatus as $ms) {
            $content .= '<option value="' . $ms . '" ' . $sel . '>
                                ' . ucfirst($ms) . '&nbsp;&nbsp;
                            </option>';
        }
        $content .= '</select>
        <span id="err_status"></span>
                </div>';
        $content .= $addContent;
        return [
            'badge' => $badge,
            'content' => $content,
            'footer' => $footer
        ];
    }
    protected function deleteRiwayatTrack($request)
    {
        try {
            $id = $request->id;
            $params = [
                'params' => 'Delete Riwayat Track',
                'id' => $id
            ];
            event(new ProduksiEvent($params));
            $totalDikirim = DB::table('proses_produksi_track_riwayat')
            ->select(DB::raw('IFNULL(SUM(jml_dikirim),0) as total_dikirim'))->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil dihapus!',
                'data' => $totalDikirim[0]->total_dikirim
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function selectMaster($request)
    {
        switch ($request->request_) {
            case 'selectMesin':
                $type = 'M';
                break;
            case 'selectOperator':
                $type = 'O';
                break;
        }
        $data = DB::table('proses_produksi_master')->where('type', $type)
            ->whereNull('deleted_at')
            ->where('nama', 'like', '%' . $request->input('term') . '%')
            ->get();
        return response()->json($data);
    }
    protected function selectedMaster($request)
    {
        $idM = $request->id_mesin;
        $idO = $request->id_operator;
        $dataMesin = DB::table('proses_produksi_master')->where('type', 'M')
                ->where('id',$idM)
                ->whereNull('deleted_at')
                ->first();
        $dataOperator = (object)collect(json_decode($idO))->map(function($id) {
            return DB::table('proses_produksi_master')->where('type', 'O')
            ->where('id',$id)
            ->whereNull('deleted_at')
            ->first();
        })->all();
        return response()->json([
            'mesin' => $dataMesin,
            'operator' => $dataOperator,
        ]);
    }
    protected function submitTrack($request)
    {
        try {
            $produksi_id = $request->produksi_id;
            $proses_tahap = $request->proses_tahap;
            $status = $request->status;
            $buku_jadi = $request->buku_jadi;
            $checkData = DB::table('proses_produksi_track')
                ->where('produksi_id',$produksi_id)
                ->where('proses_tahap',$proses_tahap)
                ->first();
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $tglselesai = $status == 'selesai' ? $tgl : NULL;
            switch ($status) {
                case 'sedang dalam proses':
                    $statBadge = '<small class="badge badge-warning">'.$status.'</small>';
                    break;
                case 'pending':
                    $statBadge = '<small class="badge badge-danger">'.$status.'</small>';
                    break;
                case 'selesai':
                    $statBadge = '<small class="badge badge-light">'.$status.'</small>';
                    break;
            }

            $icon = $proses_tahap == 'Kirim Gudang' ? 'fas fa-cubes' : 'fas fa-user-cog';
            $desc = $proses_tahap == 'Kirim Gudang' ?
            'Departemen produksi mengirimkan stok ke gudang sejumlah '.$request->jml_dikirim.' eks.':
            'Proses produksi tahap <b>'.$proses_tahap.'</b> berstatus '.$statBadge;
            $trackerProduksi = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $produksi_id,
                'section_name' => 'Produksi',
                'description' => $desc,
                'icon' => $icon,
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($trackerProduksi));
            if ($proses_tahap == 'Kirim Gudang') {
                $jml_dikirim = $request->jml_dikirim;
                $type = DB::select(DB::raw("SHOW COLUMNS FROM proses_produksi_track WHERE Field = 'proses_tahap'"))[0]->Type;
                preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
                $prosesTahap = explode("','", $matches[1]);
                $prosesTahap = Arr::except($prosesTahap, ['7']);
                if ($buku_jadi != 'Wrapping') {
                    $prosesTahap = Arr::except($prosesTahap, ['5']);
                }
                $totData = DB::table('proses_produksi_track')
                ->where('produksi_id',$produksi_id)
                ->where('status','selesai')
                ->get();
                if (count($totData) < count($prosesTahap)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses produksi belum selesai!'
                    ]);
                }
                return $this->submitKirimGudang($checkData,$tgl,$jml_dikirim,$produksi_id,$proses_tahap);
            } else {
                $mesin = $request->mesin;
                $operator = is_null($request->operator) ? NULL : json_encode($request->operator);
                DB::beginTransaction();

                if ($checkData) {
                    //UPDATE
                    $update = [
                        'params' => 'Update Track Produksi',
                        'produksi_id' => $produksi_id,
                        'proses_tahap' => $proses_tahap,
                        'mesin' => $mesin,
                        'operator' => $operator,
                        'status' => $status,
                        'tgl_pending' => $status == 'pending' ? $tgl:NULL,
                        'tgl_mulai' => $status == 'sedang dalam proses' ? $tgl:NULL,
                        'tgl_selesai' => $tglselesai,
                    ];
                    event(new ProduksiEvent($update));
                    $mesin = $mesin == $checkData->mesin ? NULL:$mesin;
                    $operator = $operator == $checkData->operator ? NULL:$operator;
                    $status = $status == $checkData->status ? NULL:$status;
                    $mesinD = $checkData->mesin;
                    $operatorD = $checkData->operator;
                    $statusD = $checkData->status;
                    $track_id = $checkData->id;
                } else {
                    //INSERT
                    $mesinD = '';
                    $operatorD = '';
                    $statusD = '';
                    $insert = [
                        'params' => 'Insert Track Produksi',
                        'produksi_id' => $produksi_id,
                        'proses_tahap' => $proses_tahap,
                        'mesin' => $mesin,
                        'operator' => $operator,
                        'status' => $status,
                        'tgl_pending' => $status == 'pending' ? $tgl:NULL,
                        'tgl_mulai' => $status == 'sedang dalam proses' ? $tgl:NULL,
                        'tgl_selesai' => $tglselesai,
                    ];
                    event(new ProduksiEvent($insert));
                    $track_id = DB::getPdo()->lastInsertId();
                }
                if (($mesin != $mesinD) || ($request->operator != $operatorD) || ($status != $statusD)) {
                    $insertRiwayat = [
                        'params' => 'Insert Riwayat Track',
                        'track_id' => $track_id,
                        'track' => $proses_tahap,
                        'mesin_new' => $mesin,
                        'operator_new' => $operator,
                        'status_new' => $status,
                        'tahap' => NULL,
                        'jml_dikirim' => NULL,
                        'tgl_pending' => $status == 'pending' ? $tgl:NULL,
                        'tgl_mulai' => $status == 'sedang dalam proses' ? $tgl:NULL,
                        'tgl_selesai' => $tglselesai,
                        'users_id' => auth()->user()->id
                    ];
                    event(new ProduksiEvent($insertRiwayat));
                }
                DB::commit();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Tracking "'.$proses_tahap.'" diperbarui!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    private function submitKirimGudang($checkData, $tgl, $jml_dikirim, $produksi_id, $proses_tahap)
    {
        try {
            DB::beginTransaction();
            if ($checkData) {
                $riwayat = DB::table('proses_produksi_track_riwayat')
                ->where('track_id',$checkData->id)
                ->where('track',$checkData->proses_tahap)
                ->max('tahap');
                //UPDATE
                $update = [
                    'params' => 'Update Track Produksi',
                    'produksi_id' => $checkData->produksi_id,
                    'proses_tahap' => $checkData->proses_tahap,
                    'mesin' => NULL,
                    'operator' => NULL,
                    'status' => $checkData->status,
                    'tgl_pending' => $checkData->tgl_pending,
                    'tgl_mulai' => $tgl,
                    'tgl_selesai' => $checkData->tgl_selesai,
                ];
                event(new ProduksiEvent($update));
                $track_id = $checkData->id;
                $tahap = $riwayat + 1;
            } else {
                //INSERT
                $insert = [
                    'params' => 'Insert Track Produksi',
                    'produksi_id' => $produksi_id,
                    'proses_tahap' => $proses_tahap,
                    'mesin' => NULL,
                    'operator' => NULL,
                    'status' => 'sedang dalam proses',
                    'tgl_pending' => NULL,
                    'tgl_mulai' => $tgl,
                    'tgl_selesai' => NULL,
                ];
                event(new ProduksiEvent($insert));
                $track_id = DB::getPdo()->lastInsertId();
                $tahap = 1;
            }
            $insertRiwayat = [
                'params' => 'Insert Riwayat Track',
                'track_id' => $track_id,
                'track' => $proses_tahap,
                'mesin_new' => NULL,
                'operator_new' => NULL,
                'status_new' => 'sedang dalam proses',
                'tahap' => $tahap,
                'jml_dikirim' => $jml_dikirim,
                'tgl_pending' => NULL,
                'tgl_mulai' => $tgl,
                'tgl_selesai' => NULL,
                'users_id' => auth()->user()->id
            ];
            event(new ProduksiEvent($insertRiwayat));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Tracking "'.$proses_tahap.'" diperbarui!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function submitEditRiwayat($request)
    {
        try {
            $jml_dikirim = $request->edit_jml_dikirim;
            $id = $request->id;
            $params = [
                'params' => 'Edit Riwayat Jumlah Kirim',
                'id' => $id,
                'jml_dikirim' => $jml_dikirim
            ];
            event(new ProduksiEvent($params));
            $totalDikirim = DB::table('proses_produksi_track_riwayat')
            ->select(DB::raw('IFNULL(SUM(jml_dikirim),0) as total_dikirim'))->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah jumlah kirim!',
                'data' => [
                    'jml_dikirim' => $jml_dikirim,
                    'total_dikirim' => $totalDikirim[0]->total_dikirim
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function showModalEditRiwayat($request)
    {
        try {
            $id = $request->id;
            $data = DB::table('proses_produksi_track_riwayat')->where('id',$id)->first();
            return response()->json($data);
        } catch (\Exception $e) {
            return abort(500);
        }
    }
}
