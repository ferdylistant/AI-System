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

class ProduksiController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-produksi') {
                $data = DB::table('produksi_order_cetak as poc')
                    ->join('produksi_penyetujuan_order_cetak as pyoc', 'pyoc.produksi_order_cetak_id', '=', 'poc.id')
                    ->whereNull('poc.deleted_at')
                    ->select(
                        'poc.*',
                        'pyoc.m_penerbitan',
                        'pyoc.m_stok',
                        'pyoc.d_operasional',
                        'pyoc.d_keuangan',
                        'pyoc.d_utama',
                        'pyoc.m_penerbitan_act',
                        'pyoc.m_stok_act',
                        'pyoc.d_operasional_act',
                        'pyoc.d_keuangan_act',
                        'pyoc.d_utama_act',
                        'pyoc.pending_sampai',
                        'pyoc.status_general'
                        )
                    ->get();
                $update = Gate::allows('do_update', 'update-produksi');

                return DataTables::of($data)
                        ->addColumn('no_order', function($data) {
                            return $data->kode_order;
                        })
                        ->addColumn('pilihan_terbit', function($data) {
                            if($data->pilihan_terbit == '1') {
                                $res = 'Cetak Fisik';
                            } else {
                                $res = 'Cetak Fisik + E-Book';
                            }
                            return $res;
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
                        ->addColumn('status_penyetujuan', function($data)  {
                            $badge = '';
                            if(in_array($data->status_cetak, ['1', '2'])) {
                                if($data->status_general == 'Selesai') {
                                    $badge .= '<span class="badge badge-primary">Selesai</span>';
                                } elseif($data->status_general == 'Pending') {
                                    $badge .= '<span class="badge badge-warning">Pending sampai '.Carbon::parse($data->pending_sampai)->translatedFormat('d M Y').'</span>';
                                }
                                else {
                                    //Manajer Penerbitan
                                    if($data->m_penerbitan_act == '1') {
                                        $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> M.Penerbitan</div>';
                                    } elseif($data->m_penerbitan_act == '3') {
                                        $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> M.Penerbitan</div>';
                                    }
                                    //Direksi Operasional
                                    if($data->d_operasional_act == '1') {
                                        $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
                                    } elseif($data->d_operasional_act == '2') {
                                        $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
                                    } elseif($data->d_operasional_act == '3') {
                                        $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
                                    }
                                    //Direksi Keuangan
                                    if($data->d_keuangan_act == '1') {
                                        $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Keuangan</div>';
                                    } elseif($data->d_keuangan_act == '2') {
                                        $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> D.Keuangan</div>';
                                    } elseif($data->d_keuangan_act == '3') {
                                        $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> D.Keuangan</div>';
                                    }
                                    //Direksi Utama
                                    if($data->d_utama_act == '1') {
                                        $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Utama</div>';
                                    } elseif($data->d_utama_act == '2') {
                                        $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> D.Utama</div>';
                                    } elseif($data->d_utama_act == '3') {
                                        $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> D.Utama</div>';
                                    }
                                }
                            } elseif($data->status_cetak == '3') {
                                if($data->status_general == 'Selesai') {
                                    $badge .= '<span class="badge badge-primary">Selesai</span>';
                                } elseif($data->status_general == 'Pending') {
                                    $badge .= '<span class="badge badge-warning">Pending sampai '.Carbon::parse($data->pending_sampai)->translatedFormat('d M Y').'</span>';
                                } else {
                                    //Manajer Stok
                                    if($data->m_stok_act == '1') {
                                        $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> M.Stok</div>';
                                    } elseif($data->m_stok_act == '2') {
                                        $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> M.Stok</div>';
                                    } elseif($data->m_stok_act == '3') {
                                        $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> M.Stok</div>';
                                    }
                                    //Direksi Operasional
                                    if($data->d_operasional_act == '1') {
                                        $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
                                    } elseif($data->d_operasional_act == '2') {
                                        $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
                                    } elseif($data->d_operasional_act == '3') {
                                        $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
                                    }
                                    //Direksi Keuangan
                                    if($data->d_keuangan_act == '1') {
                                        $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Keuangan</div>';
                                    } elseif($data->d_keuangan_act == '2') {
                                        $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> D.Keuangan</div>';
                                    } elseif($data->d_keuangan_act == '3') {
                                        $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> D.Keuangan</div>';
                                    }
                                    //Direksi Utama
                                    if($data->d_utama_act == '1') {
                                        $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Utama</div>';
                                    } elseif($data->d_utama_act == '2') {
                                        $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> D.Utama</div>';
                                    } elseif($data->d_utama_act == '3') {
                                        $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> D.Utama</div>';
                                    }
                                }
                            }

                            return $badge;
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('penerbitan/order-cetak/detail?kode='.$data->id .'&author='.$data->created_by).'"
                                    class="d-flex btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('penerbitan/order-cetak/edit?kode='.$data->id.'&author='.$data->created_by).'"
                                    class="d-flex btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'no_order',
                            'pilihan_terbit',
                            'tipe_order',
                            'status_cetak',
                            'judul_buku',
                            'urgent',
                            'isbn',
                            'status_penyetujuan',
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
                    'add_tgl_permintaan_jadi' => 'required|date',
                    'add_pilihan_terbit' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);
                $jenisMesin = $request->add_jenis_mesin;
                $tipeOrder = $request->add_tipe_order;
                if ($request->add_pilihan_terbit == '2') {
                    foreach ($request->add_platform_digital as $key => $value) {
                        $platformDigital[$key] = $value;
                    }
                } else {
                    $platformDigital = [];
                }
                $imprintName = DB::table('imprint')
                    ->where('id', $request->add_imprint)
                    ->whereNull('deleted_at')
                    ->first();
                $penulisName = DB::table('penerbitan_penulis')
                    ->where('id', $request->add_penulis)
                    ->whereNull('deleted_at')
                    ->first();
                $idO = Str::uuid()->getHex();
                $getId = $this->getOrderId($tipeOrder);
                DB::table('produksi_order_cetak')->insert([
                    'id' => $idO,
                    'kode_order' => $getId,
                    'tipe_order' => $tipeOrder,
                    'jenis_mesin' => $jenisMesin,
                    'status_cetak' => $request->add_status_cetak,
                    'judul_buku' => $request->add_judul_buku,
                    'sub_judul' => $request->add_sub_judul_buku,
                    'platform_digital' => json_encode($platformDigital),
                    'pilihan_terbit' => $request->add_pilihan_terbit,
                    'urgent' => $request->add_urgent,
                    'penulis' => $penulisName->nama,
                    'penerbit' => $request->add_penerbit,
                    'imprint' => $imprintName->nama,
                    'isbn' => $request->add_isbn,
                    'eisbn' => $request->add_eisbn,
                    'edisi_cetakan' => $request->add_edisi.'/'.$request->add_cetakan,
                    'posisi_layout' => $request->add_posisi_layout,
                    'dami' => $request->add_dami,
                    'format_buku' => $request->add_format_buku.' cm',
                    'jumlah_halaman' => $request->add_jumlah_halaman_1.' + '.$request->add_jumlah_halaman_2,
                    'kelompok_buku' => $request->add_kelompok_buku,
                    'kertas_isi' => $request->add_kertas_isi,
                    'warna_isi' => $request->add_warna_isi,
                    'kertas_cover' => $request->add_kertas_cover,
                    'warna_cover' => $request->add_warna_cover,
                    'efek_cover' => $request->add_efek_cover,
                    'jenis_cover' => $request->add_jenis_cover,
                    'tahun_terbit' => Carbon::createFromFormat('Y', $request->add_tahun_terbit)->format('Y'),
                    'tgl_permintaan_jadi' => Carbon::createFromFormat('d F Y', $request->add_tgl_permintaan_jadi)->format('Y-m-d'),
                    'buku_jadi' => $request->add_buku_jadi,
                    'status_buku' => $request->add_status_buku,
                    'jumlah_cetak' => $request->add_jumlah_cetak,
                    'buku_contoh' => $request->add_buku_contoh,
                    'jilid' => $request->add_jilid,
                    'ukuran_jilid_bending' => $request->add_ukuran_bending.' cm',
                    'spp' => $request->add_spp,
                    'keterangan' => $request->add_keterangan,
                    'perlengkapan' => $request->add_perlengkapan,
                    'created_by' => auth()->id()
                ]);
                DB::table('produksi_penyetujuan_order_cetak')->insert([
                    'id' => Uuid::uuid4()->toString(),
                    'produksi_order_cetak_id' => $idO,
                ]);
                $this->notifPersetujuan($request->add_status_cetak,'create-notif', [
                        'form_id' => $idO
                    ]);
                // return response()->json([
                //     'success' => true,
                //     'message' => 'Data berhasil ditambahkan',
                //     'data' => $dd
                // ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil ditambahkan',
                    'redirect' => route('cetak.view')
                ]);
            }
        }
        $tipeOrd = array(['id' => 1,'name' => 'Umum'], ['id' => 2,'name' => 'Rohani']);
        $urgent = array(['id' => 1,'name' => 'Ya'],['id' => 0,'name' => 'Tidak']);
        $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        $penulis = DB::table('penerbitan_penulis')->whereNull('deleted_at')->get();
        $platformDigital = array(['name'=>'Moco'],['name'=>'Google Book'],['name'=>'Gramedia'],['name'=>'Esentral'],
        ['name'=>'Bahanaflik'],['name'=> 'Indopustaka']);
        $kbuku = DB::table('penerbitan_m_kelompok_buku')
                    ->get();
        $jilid = array(['id'=>'1','name'=>'Bending'],['id'=>'2','name'=>'Jahit Benang'],['id'=>'3','name'=>'Jahit Kawat'],['id'=>'4','name'=>'Hardcover']);
        return view('produksi.create_produksi', [
            'title' => 'Order Cetak Buku',
            'tipeOrd' => $tipeOrd,
            'platformDigital' => $platformDigital,
            'urgent' => $urgent,
            'kbuku' => $kbuku,
            'imprint' => $imprint,
            'penulis' => $penulis,
            'jilid' => $jilid,
        ]);
    }
    public function updateProduksi(Request $request) {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'up_tipe_order' => 'required',
                    'up_status_cetak' => 'required',
                    'up_judul_buku' => 'required',
                    'up_sub_judul_buku' => 'required',
                    'up_platform_digital.*' => 'required',
                    'up_urgent' => 'required',
                    'up_isbn' => 'required',
                    'up_edisi' => 'required|regex:/^[a-zA-Z]+$/u',
                    'up_cetakan' => 'required|numeric',
                ], [
                    'required' => 'This field is requried'
                ]);
                $tipeOrder = $request->up_tipe_order;
                $pilihanTerbit = $request->up_pilihan_terbit;
                if ($pilihanTerbit != '1') {
                    foreach ($request->up_platform_digital as $key => $value) {
                        $platformDigital[$key] = $value;
                    }
                } else {
                    $platformDigital = [];
                }
                if ($request->tipe_order == $tipeOrder)
                {
                    $getKode = $request->kode_order;
                } else {
                    $getKode = $this->getOrderId($tipeOrder);
                }
                $imprintName = DB::table('imprint')
                    ->where('id', $request->up_imprint)
                    ->whereNull('deleted_at')
                    ->first();
                $penulisName = DB::table('penerbitan_penulis')
                    ->where('id', $request->up_penulis)
                    ->whereNull('deleted_at')
                    ->first();

                DB::table('produksi_order_cetak')
                    ->where('id', $request->id)
                    ->update([
                    'kode_order' => $getKode,
                    'tipe_order' => $tipeOrder,
                    'status_cetak' => $request->up_status_cetak,
                    'judul_buku' => $request->up_judul_buku,
                    'sub_judul' => $request->up_sub_judul_buku,
                    'pilihan_terbit' => $pilihanTerbit,
                    'jenis_mesin' => $request->up_jenis_mesin,
                    'platform_digital' => json_encode($platformDigital),
                    'urgent' => $request->up_urgent,
                    'penulis' => $penulisName->nama,
                    'penerbit' => $request->up_penerbit,
                    'imprint' => $imprintName->nama,
                    'isbn' => $request->up_isbn,
                    'eisbn' => $request->up_eisbn,
                    'edisi_cetakan' => $request->up_edisi.'/'.$request->up_cetakan,
                    'jumlah_halaman' => $request->up_jumlah_halaman_1.' + '.$request->up_jumlah_halaman_2,
                    'kelompok_buku' => $request->up_kelompok_buku,
                    'posisi_layout' => $request->up_posisi_layout,
                    'dami' => $request->up_dami,
                    'format_buku' => $request->up_format_buku.' cm',
                    'kertas_isi' => $request->up_kertas_isi,
                    'warna_isi' => $request->up_warna_isi,
                    'kertas_cover' => $request->up_kertas_cover,
                    'warna_cover' => $request->up_warna_cover,
                    'efek_cover' => $request->up_efek_cover,
                    'jenis_cover' => $request->up_jenis_cover,
                    'jilid' => $request->up_jilid,
                    'ukuran_jilid_bending' => empty($request->up_ukuran_bending)?$request->up_ukuran_bending:$request->up_ukuran_bending.' cm',
                    'status_buku' => $request->up_status_buku,
                    'tahun_terbit' => Carbon::createFromFormat('Y', $request->up_tahun_terbit)->format('Y'),
                    'tgl_permintaan_jadi' => Carbon::createFromFormat('d F Y', $request->up_tgl_permintaan_jadi)->format('Y-m-d'),
                    'buku_jadi' => $request->up_buku_jadi,
                    'jumlah_cetak' => $request->up_jumlah_cetak,
                    'buku_contoh' => $request->up_buku_contoh,
                    'spp' => $request->up_spp,
                    'keterangan' => $request->up_keterangan,
                    'perlengkapan' => $request->up_perlengkapan,
                    'updated_by' => auth()->id()
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil diubah',
                    'route' => route('produksi.view')
                ]);
            }
        }
        $kodeOr = $request->get('kode');
        $author = $request->get('author');
        $data = DB::table('produksi_order_cetak')->join('users', 'produksi_order_cetak.created_by', '=', 'users.id')
                    ->where('produksi_order_cetak.id', $kodeOr)
                    ->where('users.id', $author)
                    ->select('produksi_order_cetak.*', 'users.nama')
                    ->first();
        $tipeOrd = array(['id' => 1,'name' => 'Umum'], ['id' => 2,'name' => 'Rohani']);
        $statusCetak = array(['id' => 1,'name' => 'Buku Baru'], ['id' => 2,'name' => 'Cetak Ulang Revisi'],
        ['id' => 3,'name' => 'Cetak Ulang']);
        $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        $penulis = DB::table('penerbitan_penulis')->whereNull('deleted_at')->get();
        $urgent = array(['id' => 1,'name' => 'Ya'],['id' => 0,'name' => 'Tidak']);
        $platformDigital = array(['name'=>'Moco'],['name'=>'Google Book'],['name'=>'Gramedia'],['name'=>'Esentral'],
        ['name'=>'Bahanaflik'],['name'=> 'Indopustaka']);
        $kbuku = DB::table('penerbitan_m_kelompok_buku')
                    ->get();
        $edisi = Str::before($data->edisi_cetakan, '/');
        $cetakan = Str::after($data->edisi_cetakan, '/');
        $bending = Str::before($data->ukuran_jilid_bending, ' cm');
        $jmlHalaman1 = Str::before($data->jumlah_halaman, ' +');
        $jmlHalaman2 = Str::after($data->jumlah_halaman, '+ ');
        $jilid = array(['id'=>'1','name'=>'Bending'],['id'=>'2','name'=>'Jahit Benang'],['id'=>'3','name'=>'Jahit Kawat'],['id'=>'4','name'=>'Hardcover']);
        return view('produksi.update_produksi', [
            'title' => 'Update Cetak Buku',
            'tipeOrd' => $tipeOrd,
            'statusCetak' => $statusCetak,
            'imprint' => $imprint,
            'penulis' => $penulis,
            'platformDigital' => $platformDigital,
            'urgent' => $urgent,
            'kbuku' => $kbuku,
            'data' => $data,
            'edisi' => $edisi,
            'cetakan' => $cetakan,
            'bending' => $bending,
            'jmlHalaman1' => $jmlHalaman1,
            'jmlHalaman2' => $jmlHalaman2,
            'jilid' => $jilid
        ]);
    }
    public function detailProduksi(Request $request)
    {
        $kode = $request->get('kode');
        $author = $request->get('author');
        $prod = DB::table('produksi_order_cetak')
                ->where('id', $kode)
                ->where('created_by', $author)
                ->first();
        $dataPenolakan = DB::table('produksi_penyetujuan_order_cetak as pny')
                ->where('pny.produksi_order_cetak_id', $kode)
                ->where(function($query) {
                    $query->where('pny.d_operasional_act', '=', '2')
                            ->orWhere('pny.d_keuangan_act', '=', '2')
                            ->orWhere('pny.d_utama_act', '=', '2');
                })
                ->whereNotNull('pny.pending_sampai')
                ->first();

        if(!is_null($dataPenolakan)){
            $bool = $dataPenolakan->pending_sampai<=Carbon::now('Asia/Jakarta')->format('Y-m-d')?true:false;
            if($bool == true){
                if($dataPenolakan->d_operasional_act == '2'){
                    DB::table('produksi_penyetujuan_order_cetak')
                        ->where('produksi_order_cetak_id', $kode)
                        ->where('d_operasional_act', '2')
                        ->update([
                            'd_operasional_act' => '1',
                            'status_general' => 'Proses',
                            'ket_pending' => NULL,
                            'pending_sampai' => NULL,
                        ]);
                } elseif($dataPenolakan->d_keuangan_act == '2'){
                    DB::table('produksi_penyetujuan_order_cetak')
                        ->where('produksi_order_cetak_id', $kode)
                        ->where('d_keuangan_act', '2')
                        ->update([
                            'd_keuangan_act' => '1',
                            'status_general' => 'Proses',
                            'ket_pending' => NULL,
                            'pending_sampai' => NULL,
                        ]);
                } elseif($dataPenolakan->d_utama_act == '2'){
                    DB::table('produksi_penyetujuan_order_cetak')
                        ->where('produksi_order_cetak_id', $kode)
                        ->where('d_operasional_act', '2')
                        ->update([
                            'd_keuangan_act' => '1',
                            'status_general' => 'Proses',
                            'ket_pending' => NULL,
                            'pending_sampai' => NULL,
                    ]);
                }
            }
        }
        $prodPenyetujuan = DB::table('produksi_penyetujuan_order_cetak')
                    ->where('produksi_penyetujuan_order_cetak.produksi_order_cetak_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_cetak.*')
                    ->first();
        $author = DB::table('users')
                    ->where('id', $author)
                    ->first();
        $m_stok = DB::table('users as u')
                    ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                    ->where('j.nama', 'LIKE', '%Manajer Stok%')
                    ->select('u.nama', 'u.id as id')
                    ->first();
        if(is_null($m_stok)){
            $m_stok = '';
        }
        $m_penerbitan = DB::table('users as u')
                    ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                    ->where('j.nama', 'LIKE', '%Manajer Penerbitan%')
                    ->select('u.nama', 'u.id as id')
                    ->first();
        if(is_null($m_penerbitan)){
            $m_penerbitan = '';
        }
        $dirop = DB::table('users as u')
                    ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                    ->where('j.nama', 'LIKE', '%Direktur Operasional%')
                    ->select('u.nama', 'u.id as id')
                    ->first();
        if(is_null($dirop)){
            $dirop = '';
        }
        $dirke = DB::table('users as u')
                    ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                    ->where('j.nama', 'LIKE', '%Direktur Keuangan%')
                    ->select('u.nama', 'u.id as id')
                    ->first();
        if(is_null($dirke)){
            $dirke = '';
        }
        $dirut = DB::table('users as u')
                    ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                    ->where('j.nama', 'LIKE', '%Direktur Utama%')
                    ->select('u.nama', 'u.id as id')
                    ->first();
        if(is_null($dirut)){
            $dirut = '';
        }
        DB::table('produksi_penyetujuan_order_cetak')
            ->where('produksi_penyetujuan_order_cetak.produksi_order_cetak_id', $prod->id)
            ->update([
                'm_stok' => $m_stok->id,
                'm_penerbitan' => $m_penerbitan->id,
                'd_operasional' => $dirop->id,
                'd_keuangan' => $dirke->id,
                'd_utama' => $dirut->id
            ]);
        $p_mstok = DB::table('produksi_penyetujuan_order_cetak')
                    ->join('users', 'produksi_penyetujuan_order_cetak.m_stok', '=', 'users.id')
                    ->where('produksi_penyetujuan_order_cetak.m_stok','=', $m_stok->id)
                    ->where('produksi_penyetujuan_order_cetak.produksi_order_cetak_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_cetak.*', 'users.nama')
                    ->first();

        $p_mp = DB::table('produksi_penyetujuan_order_cetak')
                    ->join('users', 'produksi_penyetujuan_order_cetak.m_penerbitan', '=', 'users.id')
                    ->where('produksi_penyetujuan_order_cetak.m_penerbitan','=', $m_penerbitan->id)
                    ->where('produksi_penyetujuan_order_cetak.produksi_order_cetak_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_cetak.*', 'users.nama')
                    ->first();

        $p_dirop = DB::table('produksi_penyetujuan_order_cetak')
                    ->join('users', 'produksi_penyetujuan_order_cetak.d_operasional', '=', 'users.id')
                    ->where('produksi_penyetujuan_order_cetak.d_operasional','=', $dirop->id)
                    ->where('produksi_penyetujuan_order_cetak.produksi_order_cetak_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_cetak.*', 'users.nama')
                    ->first();

        $p_dirke = DB::table('produksi_penyetujuan_order_cetak')
                    ->join('users', 'produksi_penyetujuan_order_cetak.d_keuangan', '=', 'users.id')
                    ->where('produksi_penyetujuan_order_cetak.d_keuangan','=', $dirke->id)
                    ->where('produksi_penyetujuan_order_cetak.produksi_order_cetak_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_cetak.*', 'users.nama')
                    ->first();

        $p_dirut = DB::table('produksi_penyetujuan_order_cetak')
                    ->join('users', 'produksi_penyetujuan_order_cetak.d_utama', '=', 'users.id')
                    ->where('produksi_penyetujuan_order_cetak.d_utama','=', $dirut->id)
                    ->where('produksi_penyetujuan_order_cetak.produksi_order_cetak_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_cetak.*', 'users.nama')
                    ->first();
        return view('produksi.detail_produksi', [
            'title' => 'Detail Order Cetak Buku',
            'data' => $prod,
            'id' => $kode,
            'prod_penyetujuan' => $prodPenyetujuan,
            'data_penolakan' => $dataPenolakan,
            'author' => $author,
            'm_stok' => $m_stok,
            'm_penerbitan' => $m_penerbitan,
            'dirop' => $dirop,
            'dirke' => $dirke,
            'dirut' => $dirut,
            'p_mstok' => $p_mstok,
            'p_mp' => $p_mp,
            'p_dirop' => $p_dirop,
            'p_dirke' => $p_dirke,
            'p_dirut' => $p_dirut,
        ]);
    }
    public function ajaxRequest(Request $request, $cat){
        switch($cat) {
            case 'approval':
                return $this->approvalOrder($request);
                break;
            case 'pending':
                return $this->pendingOrder($request);
                break;
            default:
                abort(500);
        }
    }
    protected function notifPersetujuan($statusCetak,$action=null, $data=null){
        /*
    Persetujuan Direktur harus berurutan dari Operasional ke Utama.
    Status Cetak (Buku Baru & Cetak Ulang Revisi) ::
            M.Penerbit (bisa lewat langsung direktur) -> D.Operasional -> D.Keuangan -> D.Utama
    Status Cetak (Cetak Ulang) ::
            M.Stok (bisa lewat langsung direktur) -> D.Operasional -> D.Keuangan -> D.Utama
    =============================================================================================
            Manajer Penerbitan :: 1b842575174242cf83f949f262900570
            Manajer Stok :: 09179170e6e643eca66b282e2ffae1f8
            D.Operasional :: 2826b627ccc34fad84470c4b7534da0d
            D.Keuangan:: 6b95b4e041e04d61a91422fe3d06fd8d
            D.Utama :: 87f03d6f4cb54135b451528bc1a9d0f5

        */

        if($statusCetak=='1' OR $statusCetak=='2') {
            if($action=='create-notif') {
                $id_notif = Str::uuid()->getHex();
                $sC = $statusCetak=='1'?'Persetujuan Order Buku Baru':'Persetujuan Order Cetak Ulang Revisi';
                DB::table('notif')->insert([
                    [   'id' => $id_notif,
                        'section' => 'Order Cetak Buku',
                        'type' => $sC,
                        'permission_id' => '1b842575174242cf83f949f262900570', // manajer penerbitan
                        'form_id' => $data['form_id'], ],
                    [   'id' => Str::uuid()->getHex(),
                        'section' => 'Order Cetak Buku',
                        'type' => $sC,
                        'permission_id' => '2826b627ccc34fad84470c4b7534da0d', // D.Operasional
                        'form_id' => $data['form_id'], ],
                ]);
                return;
            } elseif($action=='update-notif-from-naskah') {
                $notif = DB::table('notif')->whereNull('expired')->where('permission_id', 'ebca07da8aad42c4aee304e3a6b81001') // Hanya untuk prodev
                            ->where('form_id', $data['form_id'])->first();
                DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                DB::table('notif_detail')->insert([
                    'notif_id' => $notif->id,
                    'user_id' => $data['id_prodev']
                ]);
                return;
            }

        } elseif($statusCetak == '3') {
            if($action=='create-notif') {
                DB::table('notif')->insert([
                    [   'id' => Str::uuid()->getHex(),
                        'section' => 'Order Cetak Buku',
                        'type' => 'Persetujuan Order Cetak Ulang',
                        'permission_id' => '09179170e6e643eca66b282e2ffae1f8', // M.Stok
                        'form_id' => $data['form_id'], ],
                ]);
                return;
            }

        }
    }
    protected function approvalOrder($request)
    {
        try{
            $dataUser = DB::table('users')
                        ->where('id', auth()->id())
                        ->first();
            $dataPenyetujuan = DB::table('produksi_penyetujuan_order_cetak')
                ->where('produksi_order_cetak_id', $request->id)
                ->select('produksi_penyetujuan_order_cetak.*')
                ->first();
            if($dataPenyetujuan->status_general == 'Selesai'){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data sudah selesai di Approve'
                ]);
            } elseif($dataPenyetujuan->status_general == 'Pending'){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data di Pending sampai '.date('d F Y',strtotime($dataPenyetujuan->pending_sampai))
                ]);
            }
            if($request->status_cetak == '3'){
                if($dataUser->id == $dataPenyetujuan->m_stok) {
                    if($dataPenyetujuan->m_stok_act == '3'){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data sudah Anda Approve'
                        ]);
                    } elseif($dataPenyetujuan->m_stok_act == '2'){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data sudah dipending sampai '.date('d F Y',strtotime($dataPenyetujuan->pending_sampai))
                        ]);
                    }
                    DB::table('produksi_penyetujuan_order_cetak')
                        ->where('produksi_order_cetak_id', $request->id)
                        ->update([
                            'm_stok_act' => '3',
                        ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil diupdate'
                    ]);
                } elseif($dataUser->id == $dataPenyetujuan->d_operasional) {
                    if($dataPenyetujuan->m_stok_act == '1'){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Manajer Stok belum melakukan persetujuan'
                        ]);
                    }
                    DB::table('produksi_penyetujuan_order_cetak')
                        ->where('produksi_order_cetak_id', $request->id)
                        ->update([
                            'd_operasional_act' => '3',
                        ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil diupdate'
                    ]);
                } elseif($dataUser->id == $dataPenyetujuan->d_keuangan) {
                    if($dataPenyetujuan->d_operasional_act == '3'){
                        DB::table('produksi_penyetujuan_order_cetak')
                            ->where('produksi_order_cetak_id', $request->id)
                            ->update([
                                'd_keuangan_act' => '3',
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
                } elseif($dataUser->id == $dataPenyetujuan->d_utama) {
                    if($dataPenyetujuan->d_operasional_act == '1') {
                        if($dataPenyetujuan->d_keuangan_act == '1') {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Data belum di Approve oleh Direktur Operasional dan Direktur Keuangan'
                            ]);
                        }
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Operasional'
                        ]);
                    } elseif($dataPenyetujuan->d_keuangan_act == '1'){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Keuangan'
                        ]);
                    } else {
                        DB::table('produksi_penyetujuan_order_cetak')
                            ->where('produksi_order_cetak_id', $request->id)
                            ->update([
                                'd_utama_act' => '3',
                                'status_general' => 'Selesai',
                            ]);
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
            } else{
                if($dataUser->id == $dataPenyetujuan->m_penerbitan) {
                    if($dataPenyetujuan->m_penerbitan_act == '3'){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data sudah Anda Approve'
                        ]);
                    } elseif($dataPenyetujuan->m_penerbitan_act == '2'){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data sudah dipending sampai '.date('d F Y',strtotime($dataPenyetujuan->pending_sampai))
                        ]);
                    }
                    DB::table('produksi_penyetujuan_order_cetak')
                        ->where('produksi_order_cetak_id', $request->id)
                        ->update([
                            'm_penerbitan_act' => '3',
                        ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil diupdate'
                    ]);
                } elseif($dataUser->id == $dataPenyetujuan->d_operasional) {
                    DB::table('produksi_penyetujuan_order_cetak')
                        ->where('produksi_order_cetak_id', $request->id)
                        ->update([
                            'd_operasional_act' => '3',
                        ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil diupdate'
                    ]);
                } elseif($dataUser->id == $dataPenyetujuan->d_keuangan) {
                    if($dataPenyetujuan->d_operasional_act == '3'){
                        DB::table('produksi_penyetujuan_order_cetak')
                            ->where('produksi_order_cetak_id', $request->id)
                            ->update([
                                'd_keuangan_act' => '3',
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
                } elseif($dataUser->id == $dataPenyetujuan->d_utama) {
                    if($dataPenyetujuan->d_operasional_act == '1') {
                        if($dataPenyetujuan->d_keuangan_act == '1') {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Data belum di Approve oleh Direktur Operasional dan Direktur Keuangan'
                            ]);
                        }
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Operasional'
                        ]);
                    } elseif($dataPenyetujuan->d_keuangan_act == '1'){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data belum di Approve oleh Direktur Keuangan'
                        ]);
                    } else {
                        DB::table('produksi_penyetujuan_order_cetak')
                            ->where('produksi_order_cetak_id', $request->id)
                            ->update([
                                'd_utama_act' => '3',
                                'status_general' => 'Selesai',
                            ]);
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
        } catch(\Exception $e){
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
            $dataPenyetujuan = DB::table('produksi_penyetujuan_order_cetak')
                ->where('produksi_order_cetak_id', $request->id)
                ->select('produksi_penyetujuan_order_cetak.*')
                ->first();
            if ($request->status_cetak == '3') {
                if ($dataPenyetujuan->m_stok_act == '1'){
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
                    'message' => 'Data dipending sampai '.Carbon::parse($dataPenyetujuan->pending_sampai)->translatedFormat('d F Y')
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
                DB::table('produksi_penyetujuan_order_cetak')
                    ->where('produksi_order_cetak_id', $request->id)
                    ->update([
                        'd_operasional_act' => '2',
                        'ket_pending' => $request->keterangan,
                        'pending_sampai' => date('Y-m-d', strtotime($request->pending_sampai)),
                        'status_general' => 'Pending',
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
                DB::table('produksi_penyetujuan_order_cetak')
                    ->where('produksi_order_cetak_id', $request->id)
                    ->update([
                        'd_keuangan_act' => '2',
                        'ket_pending' => $request->keterangan,
                        'pending_sampai' => date('Y-m-d', strtotime($request->pending_sampai)),
                        'status_general' => 'Pending',
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
                DB::table('produksi_penyetujuan_order_cetak')
                    ->where('produksi_order_cetak_id', $request->id)
                    ->update([
                        'd_utama_act' => '2',
                        'ket_pending' => $request->keterangan,
                        'pending_sampai' => date('Y-m-d', strtotime($request->pending_sampai)),
                        'status_general' => 'Pending',
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
        switch($tipeOrder) {
            case 1: $lastId = DB::table('produksi_order_cetak')
                                ->where('kode_order', 'like', $year.'-%')
                                ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 1000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 2999")
                                ->orderBy('kode_order', 'desc')->first();

                    $firstId = '1000';
            break;
            case 2: $lastId = DB::table('produksi_order_cetak')
                                ->where('kode_order', 'like', $year.'-%')
                                ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 3000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 3999")
                                ->orderBy('kode_order', 'desc')->first();
                    $firstId = '3000';
            break;
            case 3: $lastId = DB::table('produksi_order_cetak')
                                ->where('kode_order', 'like', $year.'-%')
                                ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 4000")
                                ->orderBy('kode_order', 'desc')->first();
                    $firstId = '4000';
            break;
            default: abort(500);
        }

        if(is_null($lastId)) {
            return $year.'-'.$firstId;
        } else {
            $lastId_ = (int)substr($lastId->kode_order, 3);
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
