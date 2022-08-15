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

class EbookController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-produksi-ebook') {
                $data = DB::table('produksi_order_ebook as poc')
                    ->join('produksi_penyetujuan_order_ebook as pyoc', 'pyoc.produksi_order_ebook_id', '=', 'poc.id')
                    ->whereNull('poc.deleted_at')
                    ->select(
                        'poc.*',
                        'pyoc.m_penerbitan',
                        'pyoc.d_operasional',
                        'pyoc.d_keuangan',
                        'pyoc.d_utama',
                        'pyoc.m_penerbitan_act',
                        'pyoc.d_operasional_act',
                        'pyoc.d_keuangan_act',
                        'pyoc.d_utama_act',
                        'pyoc.status_general'
                        )
                    ->get();
                $update = Gate::allows('do_update', 'update-produksi-ebook');

                return DataTables::of($data)
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
                        ->addColumn('judul_buku', function($data) {
                            return $data->judul_buku;
                        })
                        ->addColumn('tahun_terbit', function($data) {
                            if (!is_null($data->tahun_terbit)) {
                                $res = $data->tahun_terbit;
                            } else {
                                $res = '-';
                            }
                            return $res;
                        })
                        ->addColumn('tgl_upload', function($data) {
                            return date('d F Y', strtotime($data->tgl_upload));
                        })
                        ->addColumn('eisbn', function($data) {
                            if(is_null($data->eisbn)) {
                                $res = '-';
                            } else {
                                $res = $data->eisbn;
                            }
                            return $res;
                        })
                        ->addColumn('status_penyetujuan', function($data)  {
                            $badge = '';
                            if($data->status_general == 'Selesai') {
                                $badge .= '<span class="badge badge-primary">Selesai</span>';
                            } elseif($data->status_general == 'Pending') {
                                $badge .= '<span class="badge badge-warning">Pending sampai '.date('d F Y', strtotime($data->pending_sampai)).'</span>';
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
                            return $badge;
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('produksi/order-ebook/detail?kode='.$data->id .'&author='.$data->created_by).'"
                                    class="d-flex btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('produksi/order-ebook/edit?kode='.$data->id.'&author='.$data->created_by).'"
                                    class="d-flex btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'no_order',
                            'tipe_order',
                            'judul_buku',
                            'tahun_terbit',
                            'tgl_upload',
                            'eisbn',
                            'status_penyetujuan',
                            'action'
                            ])
                        ->make(true);
            }
        }

        return view('produksi.ebook.index', [
            'title' => 'Order E-Book',
        ]);
    }
    public function createProduksi(Request $request) {
        if($request->ajax()) {
            if($request->isMethod('POST')) {
                $request->validate([
                    'add_tipe_order' => 'required',
                    'add_judul_buku' => 'required',
                    'add_sub_judul_buku' => 'required',
                    'add_platform_digital.*' => 'required',
                    'add_edisi' => 'required|regex:/^[a-zA-Z]+$/u',
                    'add_cetakan' => 'required|numeric',
                ], [
                    'required' => 'This field is requried'
                ]);
                $tipeOrder = $request->add_tipe_order;
                foreach ($request->add_platform_digital as $key => $value) {
                    $platformDigital[$key] = $value;
                }
                $imprintName = DB::table('imprint')
                    ->where('id', $request->add_imprint)
                    ->whereNull('deleted_at')
                    ->first();
                $penulisName = DB::table('penerbitan_penulis')
                    ->where('id', $request->add_penulis)
                    ->whereNull('deleted_at')
                    ->first();
                $idO = Uuid::uuid4()->toString();
                $getId = $this->getOrderId($tipeOrder);
                DB::table('produksi_order_ebook')->insert([
                    'id' => $idO,
                    'kode_order' => $getId,
                    'tipe_order' => $tipeOrder,
                    'judul_buku' => $request->add_judul_buku,
                    'sub_judul' => $request->add_sub_judul_buku,
                    'platform_digital' => json_encode($platformDigital),
                    'penulis' => $penulisName->nama,
                    'penerbit' => $request->add_penerbit,
                    'imprint' => $imprintName->nama,
                    'eisbn' => $request->add_eisbn,
                    'edisi_cetakan' => $request->add_edisi.'/'.$request->add_cetakan,
                    'jumlah_halaman' => $request->add_jumlah_halaman_1.' + '.$request->add_jumlah_halaman_2,
                    'kelompok_buku' => $request->add_kelompok_buku,
                    'tahun_terbit' => Carbon::createFromFormat('Y', $request->add_tahun_terbit)->format('Y'),
                    'tgl_upload' => Carbon::createFromFormat('d F Y', $request->add_tgl_upload)->format('Y-m-d H:i:s'),
                    'status_buku' => $request->add_status_buku,
                    'spp' => $request->add_spp,
                    'keterangan' => $request->add_keterangan,
                    'perlengkapan' => $request->add_perlengkapan,
                    'created_by' => auth()->id()
                ]);
                DB::table('produksi_penyetujuan_order_ebook')->insert([
                    'id' => Uuid::uuid4()->toString(),
                    'produksi_order_ebook_id' => $idO,
                ]);
                // $this->notifPersetujuan($request->add_status_cetak,'create-notif', [
                //         'form_id' => $idO
                //     ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil ditambahkan',
                    'redirect' => route('ebook.view')
                ]);
            }
        }
        $tipeOrd = array(['id' => 1,'name' => 'Umum'], ['id' => 2,'name' => 'Rohani']);
        $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        $penulis = DB::table('penerbitan_penulis')->whereNull('deleted_at')->get();
        $platformDigital = array(['name'=>'Moco'],['name'=>'Google Book'],['name'=>'Gramedia'],['name'=>'Esentral'],
        ['name'=>'Bahanaflik'],['name'=> 'Indopustaka']);
        $kbuku = DB::table('penerbitan_m_kelompok_buku')
                    ->get();
        return view('produksi.ebook.create_ebook', [
            'title' => 'Order E-book',
            'tipeOrd' => $tipeOrd,
            'platformDigital' => $platformDigital,
            'kbuku' => $kbuku,
            'imprint' => $imprint,
            'penulis' => $penulis,
        ]);
    }
    public function updateProduksi(Request $request) {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'up_tipe_order' => 'required',
                    'up_judul_buku' => 'required',
                    'up_sub_judul_buku' => 'required',
                    'up_platform_digital.*' => 'required',
                    'up_edisi' => 'required|regex:/^[a-zA-Z]+$/u',
                    'up_cetakan' => 'required|numeric',
                ], [
                    'required' => 'This field is requried'
                ]);
                $tipeOrder = $request->up_tipe_order;
                foreach ($request->up_platform_digital as $key => $value) {
                    $platformDigital[$key] = $value;
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

                DB::table('produksi_order_ebook')
                    ->where('id', $request->id)
                    ->update([
                    'kode_order' => $getKode,
                    'tipe_order' => $tipeOrder,
                    'judul_buku' => $request->up_judul_buku,
                    'sub_judul' => $request->up_sub_judul_buku,
                    'platform_digital' => json_encode($platformDigital),
                    'penulis' => $penulisName->nama,
                    'penerbit' => $request->up_penerbit,
                    'imprint' => $imprintName->nama,
                    'eisbn' => $request->up_eisbn,
                    'edisi_cetakan' => $request->up_edisi.'/'.$request->up_cetakan,
                    'jumlah_halaman' => $request->up_jumlah_halaman_1.' + '.$request->up_jumlah_halaman_2,
                    'kelompok_buku' => $request->up_kelompok_buku,
                    'status_buku' => $request->up_status_buku,
                    'tahun_terbit' => Carbon::createFromFormat('Y', $request->up_tahun_terbit)->format('Y'),
                    'tgl_upload' => Carbon::createFromFormat('d F Y', $request->up_tgl_upload)->format('Y-m-d H:i:s'),
                    'spp' => $request->up_spp,
                    'keterangan' => $request->up_keterangan,
                    'perlengkapan' => $request->up_perlengkapan,
                    'updated_by' => auth()->id()
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data e-book berhasil diubah',
                    'route' => route('ebook.view')
                ]);
            }
        }
        $kodeOr = $request->get('kode');
        $author = $request->get('author');
        $data = DB::table('produksi_order_ebook')->join('users', 'produksi_order_ebook.created_by', '=', 'users.id')
                    ->where('produksi_order_ebook.id', $kodeOr)
                    ->where('users.id', $author)
                    ->select('produksi_order_ebook.*', 'users.nama')
                    ->first();
        $tipeOrd = array(['id' => 1,'name' => 'Umum'], ['id' => 2,'name' => 'Rohani']);
        $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        $penulis = DB::table('penerbitan_penulis')->whereNull('deleted_at')->get();
        $platformDigital = array(['name'=>'Moco'],['name'=>'Google Book'],['name'=>'Gramedia'],['name'=>'Esentral'],
        ['name'=>'Bahanaflik'],['name'=> 'Indopustaka']);
        $kbuku = DB::table('penerbitan_m_kelompok_buku')
                    ->get();
        return view('produksi.ebook.update_ebook', [
            'title' => 'Update E-book',
            'tipeOrd' => $tipeOrd,
            'imprint' => $imprint,
            'penulis' => $penulis,
            'platformDigital' => $platformDigital,
            'kbuku' => $kbuku,
            'data' => $data,
            'edisi' => Str::before($data->edisi_cetakan, '/'),
            'cetakan' => Str::after($data->edisi_cetakan, '/'),
            'jmlHalaman1' => Str::before($data->jumlah_halaman, ' +'),
            'jmlHalaman2' => Str::after($data->jumlah_halaman, '+ '),
        ]);
    }
    public function detailProduksi(Request $request) {
        $kode = $request->get('kode');
        $author = $request->get('author');
        $prod = DB::table('produksi_order_ebook')
                    ->where('id', $kode)
                    ->where('created_by', $author)
                    ->first();
        if(is_null($prod)) {
            return redirect()->route('ebook.view');
        }
        $dataPenolakan = DB::table('produksi_penyetujuan_order_ebook as pny')
        ->where('pny.produksi_order_ebook_id', $kode)
        ->where(function($query) {
            $query->where('pny.d_operasional_act', '=', '2')
                  ->orWhere('pny.d_keuangan_act', '=', '2')
                  ->orWhere('pny.d_utama_act', '=', '2');
        })
        ->whereNull('pny.pending_sampai')
        ->first();
        $prodPenyetujuan = DB::table('produksi_penyetujuan_order_ebook')
                    ->where('produksi_penyetujuan_order_ebook.produksi_order_ebook_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_ebook.*')
                    ->first();
        $author = DB::table('users')
                    ->where('id', $author)
                    ->first();
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
        DB::table('produksi_penyetujuan_order_ebook')
            ->where('produksi_penyetujuan_order_ebook.produksi_order_ebook_id', $prod->id)
            ->update([
                'm_penerbitan' => $m_penerbitan->id,
                'd_operasional' => $dirop->id,
                'd_keuangan' => $dirke->id,
                'd_utama' => $dirut->id
            ]);

        $p_mp = DB::table('produksi_penyetujuan_order_ebook')
                    ->join('users', 'produksi_penyetujuan_order_ebook.m_penerbitan', '=', 'users.id')
                    ->where('produksi_penyetujuan_order_ebook.m_penerbitan','=', $m_penerbitan->id)
                    ->where('produksi_penyetujuan_order_ebook.produksi_order_ebook_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_ebook.*', 'users.nama')
                    ->first();

        $p_dirop = DB::table('produksi_penyetujuan_order_ebook')
                    ->join('users', 'produksi_penyetujuan_order_ebook.d_operasional', '=', 'users.id')
                    ->where('produksi_penyetujuan_order_ebook.d_operasional','=', $dirop->id)
                    ->where('produksi_penyetujuan_order_ebook.produksi_order_ebook_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_ebook.*', 'users.nama')
                    ->first();

        $p_dirke = DB::table('produksi_penyetujuan_order_ebook')
                    ->join('users', 'produksi_penyetujuan_order_ebook.d_keuangan', '=', 'users.id')
                    ->where('produksi_penyetujuan_order_ebook.d_keuangan','=', $dirke->id)
                    ->where('produksi_penyetujuan_order_ebook.produksi_order_ebook_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_ebook.*', 'users.nama')
                    ->first();

        $p_dirut = DB::table('produksi_penyetujuan_order_ebook')
                    ->join('users', 'produksi_penyetujuan_order_ebook.d_utama', '=', 'users.id')
                    ->where('produksi_penyetujuan_order_ebook.d_utama','=', $dirut->id)
                    ->where('produksi_penyetujuan_order_ebook.produksi_order_ebook_id','=', $prod->id)
                    ->select('produksi_penyetujuan_order_ebook.*', 'users.nama')
                    ->first();
        return view('produksi.ebook.detail_ebook', [
            'title' => 'Detail Order E-Book',
            'data' => $prod,
            'id' => $kode,
            'prod_penyetujuan' => $prodPenyetujuan,
            'data_penolakan' => $dataPenolakan,
            'author' => $author,
            'm_penerbitan' => $m_penerbitan,
            'dirop' => $dirop,
            'dirke' => $dirke,
            'dirut' => $dirut,
            'p_mp' => $p_mp,
            'p_dirop' => $p_dirop,
            'p_dirke' => $p_dirke,
            'p_dirut' => $p_dirut,
        ]);
    }
    public function ajaxRequest(Request $request, $cat){
        switch($cat) {
            case 'approve':
                return $this->approvalOrder($request);
                break;
            case 'pending':
                return $this->declineOrder($request);
                break;
            default:
                abort(500);
        }
    }
    protected function approvalOrder($request)
    {
        try{
            $dataUser = DB::table('users')
                        ->where('id', auth()->id())
                        ->first();
            $dataPenyetujuan = DB::table('produksi_penyetujuan_order_ebook')
                ->where('produksi_order_ebook_id', $request->id)
                ->select('produksi_penyetujuan_order_ebook.*')
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
                DB::table('produksi_penyetujuan_order_ebook')
                    ->where('produksi_order_ebook_id', $request->id)
                    ->update([
                        'm_penerbitan_act' => '3',
                    ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
                ]);
            } elseif($dataUser->id == $dataPenyetujuan->d_operasional) {
                DB::table('produksi_penyetujuan_order_ebook')
                    ->where('produksi_order_ebook_id', $request->id)
                    ->update([
                        'd_operasional_act' => '3',
                    ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
                ]);
            } elseif($dataUser->id == $dataPenyetujuan->d_keuangan) {
                if($dataPenyetujuan->d_operasional_act == '3'){
                    DB::table('produksi_penyetujuan_order_ebook')
                        ->where('produksi_order_ebook_id', $request->id)
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
                    DB::table('produksi_penyetujuan_order_ebook')
                        ->where('produksi_order_ebook_id', $request->id)
                        ->update([
                            'd_utama_act' => '3',
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
        } catch(\Exception $e){
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
            case 1: $lastId = DB::table('produksi_order_ebook')
                                ->where('kode_order', 'like', $year.'-%')
                                ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 1000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 2999")
                                ->orderBy('kode_order', 'desc')->first();

                    $firstId = '1000';
            break;
            case 2: $lastId = DB::table('produksi_order_ebook')
                                ->where('kode_order', 'like', $year.'-%')
                                ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 3000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 3999")
                                ->orderBy('kode_order', 'desc')->first();
                    $firstId = '3000';
            break;
            case 3: $lastId = DB::table('produksi_order_ebook')
                                ->where('kode_order', 'like', $year.'-%')
                                ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 4000")
                                ->orderBy('kode_order', 'desc')->first();
                    $firstId = '4000';
            break;
            default: abort(500);
        }

        if(is_null($lastId)) {
            return 'E'.$year.'-'.$firstId;
        } else {
            $lastId_ = (int)substr($lastId->kode_order, 3);
            if($lastId_ == 2999) {
                abort(500);
            } elseif($lastId_ == 3999) {
                abort(500);
            } else {

                return 'E'.$year.'-'.strval($lastId_+1);
            }
        }

    }
}
