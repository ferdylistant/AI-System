<?php

namespace App\Http\Controllers\Produksi;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class ProsesProduksiController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-proses-produksi-cetak') {
                $data = DB::table('proses_produksi_cetak as ppc')
                    ->join('produksi_order_cetak as poc', 'poc.id', '=', 'ppc.order_cetak_id')
                    ->select(
                        'ppc.*',
                        'poc.id as order_cetak_id',
                        'poc.tipe_order',
                        'poc.kode_order',
                        'poc.judul_buku',
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
                        'poc.ukuran_jilid_bending',
                        'poc.kertas_isi',
                        'poc.efek_cover',
                        'poc.keterangan',
                        'poc.warna_cover',
                        )
                    ->get();
                $update = Gate::allows('do_update', 'ubah-lanjutan-data-produksi');
                // $start=1;
                return DataTables::of($data)
                        // ->addColumn('no', function($no) use (&$start) {
                        //     return $start++;
                        // })
                        ->addColumn('no_order', function($data) {
                            return $data->kode_order;
                        })
                        ->addColumn('tipe_order', function($data) {
                            if($data->tipe_order == 1) {
                                $res = 'Umum';
                            } elseif($data->tipe_order == 2) {
                                $res = 'Rohani';
                            }
                            return $res;
                        })
                        ->addColumn('status_cetak', function($data) {
                            if($data->status_cetak == 1) {
                                $res = 'Buku Baru';
                            } elseif($data->status_cetak == 2) {
                                $res = 'Cetak Ulang Revisi';
                            } elseif($data->status_cetak == 3) {
                                $res = 'Cetak Ulang';
                            }
                            return $res;
                        })
                        ->addColumn('judul_buku', function($data) {
                            return $data->judul_buku;
                        })
                        // ->addColumn('penulis', function($data) {
                        //     return $data->penulis;
                        // })
                        ->addColumn('edisi_cetakan', function($data) {
                            return $data->edisi_cetakan.'/'.$data->tahun_terbit;
                        })
                        ->addColumn('jenis_jilid', function($data) {
                            if ($data->jenis_jilid == 1) {
                                $res = 'Bending '.$data->ukuran_jilid_bending;
                            } elseif ($data->jenis_jilid == 2) {
                                $res = 'Jahit kawat';
                            }
                            elseif ($data->jenis_jilid == 3) {
                                $res = 'Jahit Benang';
                            }
                            elseif ($data->jenis_jilid == 4) {
                                $res = 'Hardcover';
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
                                $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> Plat</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> Plat</div>';
                            }
                            //Cetak Isi
                            if(is_null($data->cetak_isi)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> Cetak Isi</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> Cetak Isi</div>';
                            }
                            //Cover
                            if(is_null($data->cover)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> Cover</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> Cover</div>';
                            }
                            //Lipat Isi
                            if(is_null($data->lipat_isi)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> Lipat Isi</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> Lipat Isi</div>';
                            }
                            //Jilid
                            if(is_null($data->jilid)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> Jilid</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> Jilid</div>';
                            }
                            //Potong 3 Sisi
                            if(is_null($data->potong_3_sisi)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> Potong 3 Sisi</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> Potong 3 Sisi</div>';
                            }
                            if($data->buku_jadi == 'Wrapping') {
                                //Wrapping
                                if(is_null($data->wrapping)) {
                                    $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> Wrapping</div>';
                                } else {
                                    $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> Wrapping</div>';
                                }
                            }
                            //Kirim Gudang
                            if(is_null($data->kirim_gudang)) {
                                $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> Kirim Gudang</div>';
                            } else {
                                $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> Kirim Gudang</div>';
                            }
                            return $badge;
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('produksi/proses/cetak/detail?kode='.$data->order_cetak_id.'&track='.$data->id).'"
                                    class="d-flex btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('produksi/proses/cetak/edit?kode='.$data->order_cetak_id.'&track='.$data->id).'"
                                    class="d-flex btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'no_order',
                            'tipe_order',
                            'status_cetak',
                            'judul_buku',
                            // 'penulis',
                            'edisi_cetakan',
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
                        'plat' => $request->plat==''?NULL:Carbon::createFromFormat('d F Y', $request->plat)->format('Y-m-d'),
                        'cetak_isi' => $request->cetak_isi==''?NULL:Carbon::createFromFormat('d F Y', $request->cetak_isi)->format('Y-m-d'),
                        'cover' => $request->cover==''?NULL:Carbon::createFromFormat('d F Y', $request->cover)->format('Y-m-d'),
                        'lipat_isi' => $request->lipat_isi==''?NULL:Carbon::createFromFormat('d F Y', $request->lipat_isi)->format('Y-m-d'),
                        'jilid' => $request->jilid==''?NULL:Carbon::createFromFormat('d F Y', $request->jilid)->format('Y-m-d'),
                        'potong_3_sisi' => $request->potong_3_sisi==''?NULL:Carbon::createFromFormat('d F Y', $request->potong_3_sisi)->format('Y-m-d'),
                        'wrapping' => $request->wrapping==''?NULL:Carbon::createFromFormat('d F Y', $request->wrapping)->format('Y-m-d'),
                        'kirim_gudang' => $request->kirim_gudang==''?NULL:Carbon::createFromFormat('d F Y', $request->kirim_gudang)->format('Y-m-d'),
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
