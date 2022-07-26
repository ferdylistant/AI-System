<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class ProduksiController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-produksi') {
                $data = DB::table('produksi_order_cetak as poc')
                    ->whereNull('deleted_at')
                    ->get();
                $update = Gate::allows('do_update', 'ubah-data-naskah');

                return Datatables::of($data)
                        ->addColumn('stts_penilaian', function($data) {
                            if(is_null($data->tgl_pn_selesai)) {
                                $btn = '<span class="badge badge-dark">Belum Selesai</span>';
                            }else {
                                $btn = Carbon::createFromFormat('Y-m-d h:i:s',$data->tgl_pn_selesai)->format('d F Y');
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_editor_setter', function($data) {
                            if(is_null($data->tgl_pn_editor)) {
                                if ($data->jalur_buku=='Pro Literasi') {
                                    $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                }
                                else{
                                    $btn = '<span class="badge badge-warning">Tidak perlu penilaian</span>';
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_m_penerbitan', function($data) {
                            if(is_null($data->tgl_pn_m_penerbitan)) {
                                if($data->jalur_buku=='Reguler' OR $data->jalur_buku=='MoU-Reguler' OR $data->jalur_buku=='SMK/NonSMK')
                                {
                                    if(is_null($data->tgl_pn_prodev)) {
                                        $btn = "<span class='badge badge-primary'>Menunggu penilaian Prodev</span>";
                                    }
                                    else {
                                        $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                    }
                                } else {
                                    $btn = "<span class='badge badge-warning'>Tidak perlu penilaian</span>";
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_m_pemasaran', function($data) {
                            if(is_null($data->tgl_pn_m_pemasaran)) {
                                if($data->jalur_buku=='Reguler' OR $data->jalur_buku=='MoU-Reguler') {
                                    $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                } else{
                                    $btn = "<span class='badge badge-warning'>Tidak perlu penilaian</span>";
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_d_pemasaran', function($data) {
                            if(is_null($data->tgl_pn_d_pemasaran)) {
                                if ($data->jalur_buku=='Reguler' or $data->jalur_buku=='MoU-Reguler') {
                                    $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                } else {
                                    $btn = "<span class='badge badge-warning'>Tidak perlu penilaian</span>";
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_prodev', function($data) {
                            if(is_null($data->tgl_pn_prodev)) {
                                $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_direksi', function($data) {
                            if(is_null($data->tgl_pn_direksi)) {
                                if($data->jalur_buku=='Reguler' OR $data->jalur_buku=='MoU-Reguler')
                                {
                                    $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                } else {
                                    $btn = "<span class='badge badge-warning'>Tidak perlu penilaian</span>";
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('action', function($data) use($update) {
                            $btn = '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$data->id).'"
                                    class="btn btn-sm btn-primary btn-icon mr-1">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('penerbitan/naskah/mengubah-naskah/'.$data->id).'"
                                    class="btn btn-sm btn-warning btn-icon mr-1">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns(['penilaian_editor_setter','penilaian_m_penerbitan','penilaian_m_pemasaran','penilaian_d_pemasaran','penilaian_prodev','penilaian_direksi', 'stts_penilaian', 'action'])
                        ->make(true);
            } elseif($request->input('request_') === 'selectPenulis') {
                $data = DB::table('penerbitan_penulis')
                        ->whereNull('deleted_at')
                        ->where('nama', 'like', '%'.$request->input('term').'%')
                        ->get();

                return $data;
            } elseif($request->input('request_') === 'selectedPenulis') {
                $data = DB::table('penerbitan_penulis')
                            ->where('id', $request->input('id'))
                            ->first();
                return $data;
            } elseif($request->input('request_') === 'getKodeNaskah') {
                return self::generateId();
            } elseif($request->input('request_') === 'getPICTimeline') {
                $data = DB::table('users')->whereNull('deleted_at')->where('status', '1')
                            ->where('nama', 'like', '%'.$request->input('term').'%')->select('id', 'nama')
                            ->get();
                return $data;
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
        ]);
    }

    public function detailProduksi(Request $request)
    {
        return view('produksi.detail_produksi', [
            'title' => 'Detail Order Cetak Buku'
        ]);
    }
}
