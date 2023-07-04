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
    public function index(Request $request) {
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
                            //Plat
                            if(is_null($data->plat)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> Plat</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><span class="bullet"></span> Plat</div>';
                            }
                            //Cetak Isi
                            if(is_null($data->cetak_isi)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> Cetak Isi</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><span class="bullet"></span> Cetak Isi</div>';
                            }
                            //Cover
                            if(is_null($data->cover)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> Cover</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><span class="bullet"></span> Cover</div>';
                            }
                            //Lipat Isi
                            if(is_null($data->lipat_isi)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> Lipat Isi</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><span class="bullet"></span> Lipat Isi</div>';
                            }
                            //Jilid
                            if(is_null($data->jilid)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> Jilid</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><span class="bullet"></span> Jilid</div>';
                            }
                            //Potong 3 Sisi
                            if(is_null($data->potong_3_sisi)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> Potong 3 Sisi</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><span class="bullet"></span> Potong 3 Sisi</div>';
                            }
                            if($data->buku_jadi == 'Wrapping') {
                                //Wrapping
                                if(is_null($data->wrapping)) {
                                    $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> Wrapping</div>';
                                } else {
                                    $badge .= '<div class="text-success text-small font-600-bold"><span class="bullet"></span> Wrapping</div>';
                                }
                            }
                            //Kirim Gudang
                            if(is_null($data->kirim_gudang)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><span class="bullet"></span> Kirim Gudang</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><span class="bullet"></span> Kirim Gudang</div>';
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
}
