<?php

namespace App\Http\Controllers\Produksi;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Events\convertNumberToRoman;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class ProsesProduksiController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
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
            ->orderBy('oc.kode_order','asc')
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
                        ->addColumn('kode_order', function($data) {
                            return $data->kode_order;
                        })
                        ->addColumn('kode', function($data) {
                            return $data->kode;
                        })
                        ->addColumn('naskah_dari_divisi', function($data) {
                            return $data->naskah_dari_divisi;
                        })
                        ->addColumn('judul_final', function($data) {
                            return $data->judul_final;
                        })
                        ->addColumn('status_cetak', function($data) {
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
                        ->addColumn('penulis', function($data) {
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
                        ->addColumn('edisi_cetak', function($data) {
                            $roman = event(new convertNumberToRoman($data->edisi_cetak));
                            $edisiCetak = implode('',$roman).'/'.$data->edisi_cetak;
                            return $edisiCetak.'/'.$data->tahun_terbit;
                        })
                        ->addColumn('jenis_jilid', function($data) {
                            if($data->jenis_jilid == 'Binding') {
                                $ukuran = $data->ukuran_jilid_binding ?? '-';
                                $res = $data->jenis_jilid.' '.$ukuran;
                            } else {
                                $res = $data->jenis_jilid;
                            }
                            return $res;
                        })
                        ->addColumn('buku_jadi', function($data) {
                            return $data->buku_jadi;
                        })
                        ->addColumn('tracking', function($data)  {
                            $badge = '';
                            $res = DB::table('proses_produksi_track')
                            ->where('produksi_id', $data->id)
                            ->orderBy('id','desc')
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
                            if($data->buku_jadi != 'Wrapping') {
                                $masterProses = Arr::except($masterProses, ['5']);
                            }
                            $dataId = 'data-id="'.$data->id.'"';
                            $tglMulai = 'data-tglmulai=""';
                            $tglSelesai = 'data-tglselesai=""';
                            foreach ($masterProses as $m) {
                                if (in_array($m, $collect)) {
                                    foreach ($res as $status) {
                                        if ($m == $status->proses_tahap) {
                                            switch ($status->status) {
                                                case 'pending':
                                                    $tglMulai = 'data-tglmulai="'.$status->tgl_mulai.'"';
                                                    $lbl = 'danger';
                                                    break;
                                                case 'sedang dalam proses':
                                                    $tglMulai = 'data-tglmulai="'.$status->tgl_mulai.'"';
                                                    $lbl = 'warning';
                                                    break;
                                                case 'selesai':
                                                    $tglMulai = 'data-tglmulai="'.$status->tgl_mulai.'"';
                                                    $tglSelesai = 'data-tglselesai="'.$tglSelesai.'"';
                                                    $lbl = 'success';
                                                    break;
                                            }
                                            $badge .= '<a href="javascript:void(0)"
                                            class="text-'.$lbl.' text-small text-decoration-none d-block font-600-bold"
                                            data-status="'.$status->status.'" data-type="'.$m.'" '.$dataId.' '.$tglMulai.' '.$tglSelesai.' data-toggle="modal" data-target="#modalTrackProduksi">
                                            <span class="bullet"></span> '.$m.'
                                            </a>';
                                        }
                                    }
                                } else {
                                    $badge .= '<a href="javascript:void(0)"
                                            class="text-muted text-small text-decoration-none d-block font-600-bold"
                                            data-status="belum selesai" data-type="'.$m.'" '.$dataId.' '.$tglMulai.' '.$tglSelesai.' data-toggle="modal" data-target="#modalTrackProduksi">
                                            <span class="bullet"></span> '.$m.'
                                            </a>';
                                }
                            }
                            return $badge;
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('produksi/proses/cetak/detail?no='.$data->kode_order.'&naskah='.$data->kode).'"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('produksi/proses/cetak/edit?no='.$data->kode_order.'&naskah='.$data->kode).'"
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
        if(is_null($data)) {
            return redirect()->route('proses.cetak.view');
        }

        return view('produksi.proses_cetak.detail', [
            'title' => 'Detail Data Proses Produksi Order Cetak',
            'data' => $data,
        ]);
    }
    public function updateProduksi(Request $request) {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                DB::table('proses_produksi_cetak')
                    ->where('id', $request->id)
                    ->update([
                        'katern' => $request->katern,
                        'mesin' => $request->mesin,
                        'plat' => $request->plat==''?NULL:date('Y-m-d',strtotime($request->plat)),
                        'cetak_isi' => $request->cetak_isi==''?NULL:date('Y-m-d',strtotime($request->cetak_isi)),
                        'cover' => $request->cover==''?NULL:date('Y-m-d',strtotime($request->cover)),
                        'lipat_isi' => $request->lipat_isi==''?NULL:date('Y-m-d',strtotime($request->lipat_isi)),
                        'jilid' => $request->jilid==''?NULL:date('Y-m-d',strtotime($request->jilid)),
                        'potong_3_sisi' => $request->potong_3_sisi==''?NULL:date('Y-m-d',strtotime($request->potong_3_sisi)),
                        'wrapping' => $request->wrapping==''?NULL:date('Y-m-d',strtotime($request->wrapping)),
                        'kirim_gudang' => $request->kirim_gudang==''?NULL:date('Y-m-d',strtotime($request->kirim_gudang)),
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
        $data = DB::table('proses_produksi_cetak as ppc')->join('produksi_order_cetak as poc', 'poc.id','=', 'ppc.order_cetak_id')
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
    protected function modalTrackProduksi($request)
    {
        try {
            $id = $request->id;
            $typeProses = $request->type;
            $status = $request->status;
            $tglmulai = $request->tglmulai;
            $tglselesai = $request->tglselesai;
            $data = DB::table('proses_produksi_cetak as ppc')
            ->join('order_cetak as oc','oc.id','=','ppc.order_cetak_id')
            ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
            ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
            ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
            ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->where('ppc.id',$id)
            ->select(
                'ppc.*',
                'oc.jumlah_cetak',
                'dp.judul_final'
            )->first();
            if (!$data) {
                return abort(404);
            }
            $content = '';
            $footer = '';
            $type = DB::select(DB::raw("SHOW COLUMNS FROM proses_produksi_track WHERE Field = 'status'"))[0]->Type;
            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
            $masterStatus = explode("','", $matches[1]);
            $masterStatus = Arr::except($masterStatus, ['0']);
            $sel = '';
            $disable = '';
            $addContent = '';
            if ($typeProses == 'Kirim Gudang') {
                switch ($status) {
                    case 'belum selesai':
                        $badge = 'badge-light';
                        $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                        break;
                    case 'sedang dalam proses':
                        $masterStatus = Arr::except($masterStatus, ['1']);
                        $addContent .='<div class="form-group">
                        <label for="tglMulai">Tanggal Mulai</label>
                        <p id="tglMulai">'.Carbon::parse($tglmulai)->translatedFormat('l d F Y, H:i').'</p>
                        </div>';
                        $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                        $badge = 'badge-warning';
                        break;
                    case 'pending':
                        $masterStatus = Arr::except($masterStatus, ['2','3']);
                        $addContent .='<div class="form-group">
                        <label for="tglMulai">Tanggal Mulai</label>
                        <p id="tglMulai">'.Carbon::parse($tglmulai)->translatedFormat('l d F Y, H:i').'</p>
                        </div>';
                        $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                        $badge = 'badge-danger';
                        break;
                    case 'success':
                        foreach($masterStatus as $ms) {
                            if ($status == $ms) {
                                $sel = ' selected="selected" ';
                            }
                        }
                        $addContent .='<div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="tglMulai">Tanggal Mulai</label>
                        <p id="tglMulai">'.Carbon::parse($tglmulai)->translatedFormat('l d F Y, H:i').'</p>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="tglSelesai">Tanggal Selesai</label>
                        <p id="tglSelesai">'.Carbon::parse($tglselesai)->translatedFormat('l d F Y, H:i').'</p>
                        </div>
                        </div>';
                        $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>';
                        $badge = 'badge-success';
                        $disable = 'disabled';
                        break;
                }
                $content .= '
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="jmlCetak">Jumlah Oplah</label>
                            <input name="jml_cetak" class="form-control" id="jmlCetak" value="'.$data->jumlah_cetak.'" readonly>
                            </div>
                            <div class="form-group col-md-6">
                            <label for="jmlDikirim">Jumlah Dikirim</label>
                            <input name="jml_kirim" class="form-control" id="jmlDikirim" '.$disable.'>
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="historyKirim">Riwayat Kirim</label>
                        <p class="text-danger">Belum ada riwayat pengiriman</p>';
                        // if ()
                $content .='</div>';
                        $content .= $addContent;
            } else {
                switch ($status) {
                    case 'belum selesai':
                        $badge = 'badge-light';
                        $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                        break;
                    case 'sedang dalam proses':
                        $masterStatus = Arr::except($masterStatus, ['1']);
                        $addContent .='<div class="form-group">
                        <label for="tglMulai">Tanggal Mulai</label>
                        <p id="tglMulai">'.Carbon::parse($tglmulai)->translatedFormat('l d F Y, H:i').'</p>
                        </div>';
                        $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                        $badge = 'badge-warning';
                        break;
                    case 'pending':
                        $masterStatus = Arr::except($masterStatus, ['2','3']);
                        $addContent .='<div class="form-group">
                        <label for="tglMulai">Tanggal Mulai</label>
                        <p id="tglMulai">'.Carbon::parse($tglmulai)->translatedFormat('l d F Y, H:i').'</p>
                        </div>';
                        $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                        $badge = 'badge-danger';
                        break;
                    case 'success':
                        foreach($masterStatus as $ms) {
                            if ($status == $ms) {
                                $sel = ' selected="selected" ';
                            }
                        }
                        $addContent .='<div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="tglMulai">Tanggal Mulai</label>
                        <p id="tglMulai">'.Carbon::parse($tglmulai)->translatedFormat('l d F Y, H:i').'</p>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="tglSelesai">Tanggal Selesai</label>
                        <p id="tglSelesai">'.Carbon::parse($tglselesai)->translatedFormat('l d F Y, H:i').'</p>
                        </div>
                        </div>';
                        $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>';
                        $badge = 'badge-success';
                        $disable = 'disabled';
                        break;
                }
                $content .= '
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="jenisMesin">Jenis Mesin</label>
                            <select name="mesin" class="form-control select-mesin" id="jenisMesin" '.$disable.'>
                                    <option label="Pilih mesin"></option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                            <label for="operatorId">Operator</label>
                            <select name="operator" class="form-control select-operator" id="operatorId" '.$disable.'>
                                    <option label="Pilih operator"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="actionStatus">Action</label>
                            <select name="status" class="form-control select-status" id="actionStatus" '.$disable.'>
                                <option label="Pilih status"></option>';
                                foreach($masterStatus as $ms) {
                                        $content .= '<option value="' . $ms . '" ' . $sel . '>
                                        ' . ucfirst($ms) . '&nbsp;&nbsp;
                                    </option>';
                                }
                            $content .= '</select>
                        </div>';
                        $content .= $addContent;
            }
            return [
                'badge' => $badge,
                'status' => $status,
                'sectionTitle' => 'Proses Tahap "<b>'.$typeProses.'</b>"',
                'data' => $data,
                'content' => $content,
                'footer' => $footer
            ];
        } catch (\Exception $e) {
            return abort(500,$e->getMessage());
        }
    }
}
