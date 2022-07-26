<?php

namespace App\Http\Controllers\Produksi;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class ProduksiController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-produksi') {
                $data = DB::table('produksi_order_cetak as poc')
                    ->whereNull('deleted_at')
                    ->select(
                        'poc.id',
                        'poc.tipe_order',
                        'poc.status_cetak',
                        'poc.urgent',
                        'poc.judul_buku',
                        'poc.sub_judul',
                        'poc.penulis',
                        'poc.penerbit',
                        'poc.isbn',
                        'poc.eisbn',
                        'poc.imprint',
                        'poc.platform_digital',
                        'poc.status_buku',
                        'poc.kelompok_buku',
                        'poc.edisi_cetakan',
                        'poc.format_buku',
                        'poc.jumlah_halaman',
                        'poc.kertas_isi',
                        'poc.warna_isi',
                        'poc.kertas_cover',
                        'poc.warna_cover',
                        'poc.efek_cover',
                        'poc.jenis_cover',
                        'poc.jahit_kawat',
                        'poc.jahit_benang',
                        'poc.bending',
                        'poc.tanggal_terbit',
                        'poc.buku_jadi',
                        'poc.jumlah_cetak',
                        'poc.buku_contoh',
                        'poc.keterangan',
                        'poc.perlengkapan',
                        'poc.created_at',
                        'poc.created_by',
                    )
                    ->get();
                // $update = Gate::allows('do_update', 'ubah-data-naskah');

                return DataTables::of($data)
                        ->addColumn('no_order', function($data) {
                            return $data->id;
                        })
                        ->addColumn('tipe_order', function($data) {
                            if($data->tipe_order == 1) {
                                $res = 'Umum';
                            } elseif($data->tipe_order == 2) {
                                $res = 'Rohani';
                            } elseif($data->tipe_order == 3) {
                                $res = 'POD';
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
                        ->addColumn('sub_judul_buku', function($data) {
                            return $data->sub_judul;
                        })
                        ->addColumn('urgent', function($data) {
                            if ($data->urgent == 1) {
                                $res = '<span class="badge badge-danger">Urgent</span>';
                            } else {
                                $res = '<span class="badge badge-success">Tidak Urgent</span>';
                            }
                            return $res;
                        })
                        ->addColumn('penulis', function($data) {
                            return $data->penulis;
                        })
                        ->addColumn('isbn', function($data) {
                            return $data->isbn;
                        })
                        ->addColumn('eisbn', function($data) {
                            return $data->eisbn;
                        })
                        ->addColumn('penerbit', function($data) {
                            return $data->penerbit;
                        })
                        ->addColumn('imprint', function($data) {
                            return $data->imprint;
                        })
                        ->addColumn('platform_digital', function($data) {
                            $array = explode(',', $data->platform_digital);
                            return implode(', ', $array);
                        })
                        ->addColumn('status_buku', function($data){
                            return $data->status_buku;
                        })
                        ->addColumn('kelompok_buku', function($data){
                            return $data->kelompok_buku;
                        })
                        ->addColumn('edisi_cetakan', function($data){
                            return $data->edisi_cetakan;
                        })
                        ->addColumn('format_buku', function($data){
                            return $data->format_buku;
                        })
                        ->addColumn('jumlah_halaman', function($data){
                            return $data->jumlah_halaman;
                        })
                        ->addColumn('kertas_isi', function($data){
                            return $data->kertas_isi;
                        })
                        ->addColumn('warna_isi', function($data){
                            return $data->warna_isi;
                        })
                        ->addColumn('kertas_cover', function($data){
                            return $data->kertas_cover;
                        })
                        ->addColumn('warna_cover', function($data){
                            return $data->warna_cover;
                        })
                        ->addColumn('efek_cover', function($data){
                            return $data->efek_cover;
                        })
                        ->addColumn('jenis_cover', function($data){
                            return $data->jenis_cover;
                        })
                        ->addColumn('jahit_kawat', function($data){
                            if($data->jahit_kawat == 1) {
                                $res = '<span class="badge badge-success">Ada</span>';
                            } else {
                                $res = '<span class="badge badge-danger">Tidak Ada</span>';
                            }
                            return $res;
                        })
                        ->addColumn('jahit_benang', function($data){
                            if($data->jahit_benang == 1) {
                                $res = '<span class="badge badge-success">Ada</span>';
                            } else {
                                $res = '<span class="badge badge-danger">Tidak Ada</span>';
                            }
                            return $res;
                        })
                        ->addColumn('bending', function($data){
                            return $data->bending;
                        })
                        ->addColumn('tanggal_terbit', function($data){
                            if (is_null($data->tanggal_terbit)) {
                                $res = '-';
                            } else {
                                $res = Carbon::createFromFormat('Y-m-d',$data->tanggal_terbit)->format('d F Y');
                            }
                            return $res;
                        })
                        ->addColumn('buku_jadi', function($data){
                            return $data->buku_jadi;
                        })
                        ->addColumn('jumlah_cetak', function($data){
                            return $data->jumlah_cetak;
                        })
                        ->addColumn('jumlah_cetak', function($data){
                            return $data->jumlah_cetak;
                        })
                        ->addColumn('buku_contoh', function($data){
                            return $data->buku_contoh;
                        })
                        ->addColumn('keterangan', function($data){
                            return $data->keterangan;
                        })
                        ->addColumn('perlengkapan', function($data){
                            return $data->perlengkapan;
                        })
                        ->addColumn('created_at', function($data){
                            return Carbon::createFromFormat('Y-m-d h:i:s',$data->created_at)->format('d F Y');
                        })
                        ->addColumn('created_by', function($data){
                            $user = User::find($data->created_by);
                            return $user->nama;
                        })
                        ->addColumn('action', function($data) {
                            $btn = '<a href="'.url('produksi/order-cetak/detail?kode='.$data->id .'&author='.$data->created_by).'"
                                    class="btn btn-sm btn-primary btn-icon mr-1">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            // if($update) {
                            //     $btn .= '<a href="'.url('penerbitan/naskah/mengubah-naskah/'.$data->id).'"
                            //         class="btn btn-sm btn-warning btn-icon mr-1">
                            //         <div><i class="fas fa-edit"></i></div></a>';
                            // }
                            return $btn;
                        })
                        ->rawColumns([
                            'no_order',
                            'tipe_order',
                            'status_cetak',
                            'judul_buku',
                            'sub_judul_buku',
                            'urgent',
                            'penulis',
                            'isbn',
                            'eisbn',
                            'penerbit',
                            'imprint',
                            'platform_digital',
                            'status_buku',
                            'kelompok_buku',
                            'edisi_cetakan',
                            'format_buku',
                            'jumlah_halaman',
                            'kertas_isi',
                            'warna_isi',
                            'kertas_cover',
                            'warna_cover',
                            'efek_cover',
                            'jenis_cover',
                            'jahit_kawat',
                            'jahit_benang',
                            'bending',
                            'tanggal_terbit',
                            'buku_jadi',
                            'jumlah_cetak',
                            'buku_contoh',
                            'keterangan',
                            'perlengkapan',
                            'created_at',
                            'created_by',
                            'action'
                            ])
                        ->make(true);
            }
        }

        return view('produksi.index', [
            'title' => 'Order Cetak Buku'
        ]);
    }

    public function createProduksi(Request $request) {
        if($request->ajax()) {
            if($request->isMethod('POST')) {
                $request->validate([
                    'add_tipe_order' => 'required',
                    'add_status_cetak' => 'required',
                    'add_judul_buku' => 'required',
                    'add_sub_judul_buku' => 'required',
                    'add_platform_digital.*' => 'required',
                    'add_urgent' => 'required',
                    'add_isbn' => 'required',
                    'add_edisi' => 'required|regex:/^[a-zA-Z]+$/u',
                    'add_cetakan' => 'required|numeric',
                    'add_tanggal_terbit' => 'required|date',
                ], [
                    'required' => 'This field is requried'
                ]);
                //BACKEND CODE

            } else {
                return abort('404');
            }
        }

        $kbuku = DB::table('penerbitan_m_kelompok_buku')
                    ->get();
        $user = DB::table('users')->get();

        return view('produksi.create_produksi', [
            'kbuku' => $kbuku,
            'user' => $user,
            'title' => 'Menambah Data Produksi'
        ]);
    }

    public function detailProduksi(Request $request)
    {
        $kode = $request->get('kode');
        $author = $request->get('author');
        $data = DB::table('produksi_order_cetak')
                    ->where('id', $kode)
                    ->where('created_by', $author)
                    ->first();
        return view('produksi.detail_produksi', [
            'title' => 'Detail Order Cetak Buku',
            'data' => $data
        ]);
    }
}
