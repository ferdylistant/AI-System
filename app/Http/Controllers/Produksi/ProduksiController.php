<?php

namespace App\Http\Controllers\Produksi;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class ProduksiController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-produksi') {
                $data = DB::table('produksi_order_cetak as poc')
                    ->whereNull('deleted_at')
                    ->get();
                $update = Gate::allows('do_update', 'update-produksi');

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
                        ->addColumn('urgent', function($data) {
                            if ($data->urgent == 1) {
                                $res = '<span class="badge badge-danger">Urgent</span>';
                            } else {
                                $res = '<span class="badge badge-success">Tidak Urgent</span>';
                            }
                            return $res;
                        })
                        ->addColumn('isbn', function($data) {
                            return $data->isbn;
                        })
                        ->addColumn('eisbn', function($data) {
                            return $data->eisbn;
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('produksi/order-cetak/detail?kode='.$data->id .'&author='.$data->created_by).'"
                                    class="btn btn-sm btn-primary btn-icon mr-1">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('produksi/order-cetak/edit?kode='.$data->id.'&author='.$data->created_by).'"
                                    class="btn btn-sm btn-warning btn-icon mr-1">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'no_order',
                            'tipe_order',
                            'status_cetak',
                            'judul_buku',
                            'urgent',
                            'isbn',
                            'eisbn',
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

                $tipeOrder = $request->add_tipe_order;
                foreach ($request->add_platform_digital as $key => $value) {
                    $platformDigital[$key] = $value;
                }
                $getId = $this->getOrderId($tipeOrder);
                DB::table('produksi_order_cetak')->insert([
                    'id' => $getId,
                    'tipe_order' => $tipeOrder,
                    'status_cetak' => $request->add_status_cetak,
                    'judul_buku' => $request->add_judul_buku,
                    'sub_judul' => $request->add_sub_judul_buku,
                    'platform_digital' => json_encode($platformDigital),
                    'urgent' => $request->add_urgent,
                    'penulis' => $request->add_penulis,
                    'imprint' => $request->add_imprint,
                    'isbn' => $request->add_isbn,
                    'eisbn' => $request->add_eisbn,
                    'edisi_cetakan' => $request->add_edisi.'/'.$request->add_cetakan,
                    'format_buku' => $request->add_format_buku_1.' x '.$request->add_format_buku_2.' cm',
                    'jumlah_halaman' => $request->add_jumlah_halaman_1.' + '.$request->add_jumlah_halaman_2,
                    'kelompok_buku' => $request->add_kelompok_buku,
                    'kertas_isi' => $request->add_kertas_isi,
                    'warna_isi' => $request->add_warna_isi,
                    'kertas_cover' => $request->add_kertas_cover,
                    'warna_cover' => $request->add_warna_cover,
                    'efek_cover' => $request->add_efek_cover,
                    'jenis_cover' => $request->add_jenis_cover,
                    'jahit_kawat' => $request->add_jahit_kawat,
                    'jahit_benang' => $request->add_jahit_benang,
                    'bending' => $request->add_bending,
                    'tanggal_terbit' => Carbon::createFromFormat('d F Y', $request->add_tanggal_terbit)->format('Y-m-d'),
                    'buku_jadi' => $request->add_buku_jadi,
                    'jumlah_cetak' => $request->add_jumlah_cetak.'eks',
                    'buku_contoh' => $request->add_buku_contoh,
                    'keterangan' => $request->add_keterangan,
                    'perlengkapan' => $request->add_perlengkapan,
                    'created_by' => auth()->id()
                ]);
            }
        }
        $tipeOrd = array(['id' => 1,'name' => 'Umum'], ['id' => 2,'name' => 'Rohani'], ['id' => 3,'name' => 'POD']);
        $statusCetak = array(['id' => 1,'name' => 'Buku Baru'], ['id' => 2,'name' => 'Cetak Ulang Revisi'],
        ['id' => 3,'name' => 'Cetak Ulang']);
        $urgent = array(['id' => 0,'name' => 'Ya'],['id' => 1,'name' => 'Tidak']);
        $imprint = array(
            ['name' => 'Andi'],['name' => 'G-Media'],['name' => 'NAIN'],['name' => 'Nigtoon Cookery'],
            ['name' => 'YesCom'],['name' => 'Rapha'],['name' => 'Rainbow'],['name' => 'Lautan Pustaka'],
            ['name' => 'Sheila'],['name' => 'MOU Pro Literasi'],['name' => 'Rumah Baca'],['name' => 'NyoNyo'],
            ['name' => 'Mou Perorangan'],['name' => 'PBMR Andi'],['name' => 'Garam Media'],['name' => 'Lily Publisher'],
            ['name' => 'Sigma'],['name' => 'Pustaka Referensi'],['name' => 'Cahaya Harapan']);
        $platformDigital = array(['name'=>'Moco'],['name'=>'Google Book'],['name'=>'Gramedia'],['name'=>'Esentral'],
        ['name'=>'Bahanaflik'],['name'=> 'Indopustaka']);
        $kbuku = DB::table('penerbitan_m_kelompok_buku')
                    ->get();
        $jahitKawat = array(['id' => 0,'name' => 'Ya'],['id' => 1,'name' => 'Tidak']);
        $jahitBenang = array(['id' => 0,'name' => 'Ya'],['id' => 1,'name' => 'Tidak']);
        return view('produksi.create_produksi', [
            'title' => 'Order Cetak Buku',
            'tipeOrd' => $tipeOrd,
            'statusCetak' => $statusCetak,
            'platformDigital' => $platformDigital,
            'urgent' => $urgent,
            'kbuku' => $kbuku,
            'imprint' => $imprint,
            'jahitKawat' => $jahitKawat,
            'jahitBenang' => $jahitBenang
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
        $author = DB::table('users')
                    ->where('id', $author)
                    ->first();
        return view('produksi.detail_produksi', [
            'title' => 'Detail Order Cetak Buku',
            'data' => $data,
            'author' => $author
        ]);
    }
    public function ajaxRequest(Request $request, $cat){
        switch($cat) {
            case 'approval-pic':
                return $this->approvalOrder($request);
                break;
            default:
                abort(500);
        }
    }

    protected function approvalOrder($request)
    {

    }

    protected function getOrderId($tipeOrder)
    {
        $year = date('y');
        switch($tipeOrder) {
            case 1: $lastId = DB::table('produksi_order_cetak')
                                ->where('id', 'like', $year.'-%')
                                ->whereRaw("SUBSTRING_INDEX(id, '-', -1) >= 1000 and SUBSTRING_INDEX(id, '-', -1) <= 2999")
                                ->orderBy('id', 'desc')->first();

                    $firstId = '1000';
            break;
            case 2: $lastId = DB::table('produksi_order_cetak')
                                ->where('id', 'like', $year.'-%')
                                ->whereRaw("SUBSTRING_INDEX(id, '-', -1) >= 3000 and SUBSTRING_INDEX(id, '-', -1) <= 3999")
                                ->orderBy('id', 'desc')->first();
                    $firstId = '3000';
            break;
            case 3: $lastId = DB::table('produksi_order_cetak')
                                ->where('id', 'like', $year.'-%')
                                ->whereRaw("SUBSTRING_INDEX(id, '-', -1) >= 4000")
                                ->orderBy('id', 'desc')->first();
                    $firstId = '4000';
            break;
            default: abort(500);
        }

        if(is_null($lastId)) {
            return $year.'-'.$firstId;
        } else {
            $lastId_ = (int)substr($lastId->id, 3);
            if($lastId_ == 2999) {
                abort(500);
            } elseif($lastId_ == 3999) {
                abort(500);
            } else {

                return $year.'-'.strval($lastId_+1);
            }
        }

    }
}
