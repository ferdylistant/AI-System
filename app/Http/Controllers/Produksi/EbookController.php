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
                            if (is_null($data->tahun_terbit)) {
                                $res = $data->tahun_terbit;
                            } else {
                                $res = '-';
                            }
                            return $res;
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
                            if(!is_null($data->status_general)) {
                                $badge .= '<span class="badge badge-primary">Selesai</span>';
                            } else {
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
                                    $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
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
            'title' => 'Order Cetak Buku',
            'tipeOrd' => $tipeOrd,
            'platformDigital' => $platformDigital,
            'kbuku' => $kbuku,
            'imprint' => $imprint,
            'penulis' => $penulis,
        ]);
    }
}
