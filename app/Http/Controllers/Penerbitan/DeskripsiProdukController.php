<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class DeskripsiProdukController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            if($request->input('request_') === 'table-deskripsi-produk') {
                $data = DB::table('deskripsi_produk as dp')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->whereNull('dp.deleted_at')
                    ->select(
                        'dp.*',
                        'pn.kode',
                        'pn.judul_asli'
                        )
                    ->get();
                $update = Gate::allows('do_create', 'ubah-atau-buat-des-produk');

                return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('kode', function($data) {
                            return $data->kode;
                        })
                        ->addColumn('judul_asli', function($data) {
                            return $data->judul_asli;
                        })
                        ->addColumn('penulis', function($data) {
                            // return $data->penulis;
                            $result = '';
                            $res = DB::table('penerbitan_naskah_penulis as pnp')
                            ->join('penerbitan_penulis as pp',function($q) {
                                $q->on('pnp.penulis_id','=','pp.id')
                                ->whereNull('pp.deleted_at');
                            })
                            ->where('pnp.naskah_id','=',$data->naskah_id)
                            ->select('pp.nama')
                            // ->pluck('pp.nama');
                            ->get();
                            foreach ($res as $q) {
                                $result .= '<span class="d-block">-&nbsp;'.$q->nama.'</span>';
                            }
                            return $result;
                            //  $res;
                        })
                        ->addColumn('imprint', function($data) {
                            if (!is_null($data->imprint)) {
                                $res = $data->imprint;
                            } else {
                                $res = '-';
                            }
                            return $res;
                        })
                        ->addColumn('judul_final', function($data) {
                            if(is_null($data->judul_final)) {
                                $res = '-';
                            } else {
                                $res = $data->judul_final;
                            }
                            return $res;
                        })
                        ->addColumn('tgl_deskripsi', function($data) {
                            return Carbon::parse($data->tgl_deskripsi)->translatedFormat('d M Y');

                        })
                        ->addColumn('pembuat_deskripsi', function($data) {
                            $result = '';
                            $res = DB::table('users')->where('id',$data->pembuat_deskripsi)->whereNull('deleted_at')
                            ->select('nama')
                            ->get();
                            foreach (json_decode($res) as $q) {
                                $result .= $q->nama;
                            }
                            return $result;
                        })
                        // ->addColumn('status_penyetujuan', function($data)  {
                        //     $badge = '';
                        //     if($data->status_general == 'Selesai') {
                        //         $badge .= '<span class="badge badge-primary">Selesai</span>';
                        //     } elseif($data->status_general == 'Pending') {
                        //         $badge .= '<span class="badge badge-warning">Pending sampai '.Carbon::parse($data->pending_sampai)->translatedFormat('d M Y').'</span>';
                        //     }
                        //     else {
                        //         //Manajer Penerbitan
                        //         if($data->m_penerbitan_act == '1') {
                        //             $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> M.Penerbitan</div>';
                        //         } elseif($data->m_penerbitan_act == '3') {
                        //             $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> M.Penerbitan</div>';
                        //         }
                        //         //Direksi Operasional
                        //         if($data->d_operasional_act == '1') {
                        //             $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
                        //         } elseif($data->d_operasional_act == '2') {
                        //             $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
                        //         } elseif($data->d_operasional_act == '3') {
                        //             $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> D.Operasional</div>';
                        //         }
                        //         //Direksi Keuangan
                        //         if($data->d_keuangan_act == '1') {
                        //             $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Keuangan</div>';
                        //         } elseif($data->d_keuangan_act == '2') {
                        //             $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> D.Keuangan</div>';
                        //         } elseif($data->d_keuangan_act == '3') {
                        //             $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> D.Keuangan</div>';
                        //         }
                        //         //Direksi Utama
                        //         if($data->d_utama_act == '1') {
                        //             $badge .= '<div class="text-muted text-small font-600-bold"><i class="fas fa-circle"></i> D.Utama</div>';
                        //         } elseif($data->d_utama_act == '2') {
                        //             $badge .= '<div class="text-danger text-small font-600-bold"><i class="fas fa-circle"></i> D.Utama</div>';
                        //         } elseif($data->d_utama_act == '3') {
                        //             $badge .= '<div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i> D.Utama</div>';
                        //         }
                        //     }
                        //     return $badge;
                        // })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('penerbitan/deskripsi/produk/edit?kode='.$data->id ).'"
                                    class="d-flex btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('penerbitan/deskripsi/produk/edit?desc='.$data->id.'&kode='.$data->kode).'"
                                    class="d-flex btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'kode',
                            'judul_asli',
                            'penulis',
                            'imprint',
                            'judul_final',
                            'tgl_deskripsi',
                            'pembuat_deskripsi',
                            'action'
                            ])
                        ->make(true);
            }
        }

        return view('penerbitan.des_produk.index', [
            'title' => 'Deskripsi Produk',
        ]);
    }
    public function editDeskripsiProduk(Request $request)
    {
        if($request->ajax()) {
            if ($request->isMethod('POST')) {
                foreach ($request->bukti_upload as $value) {
                    $buktiUpload[] = $value;
                }
                DB::table('proses_ebook_multimedia')
                    ->where('id', $request->id)
                    ->update([
                    'bukti_upload' => json_encode($buktiUpload),
                    'updated_by'=> auth()->id()
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data bukti upload e-book berhasil diposting',
                    'route' => route('proses.ebook.view')
                ]);
            }
        }
        $id = $request->get('desc');
        $kodenaskah = $request->get('kode');
        $data = DB::table('deskripsi_produk as dp')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb',function($q){
                $q->on('pn.kelompok_buku_id','=','kb.id')
                ->whereNull('kb.deleted_at');
            })
            ->where('dp.id',$id)
            ->where('pn.kode',$kodenaskah)
            ->whereNull('dp.deleted_at')
            ->select(
                'dp.*',
                'pn.kode',
                'pn.judul_asli',
                'kb.nama'
                )
            ->first();
        is_null($data)?abort(404):
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
        ->join('penerbitan_penulis as pp',function($q) {
            $q->on('pnp.penulis_id','=','pp.id')
            ->whereNull('pp.deleted_at');
        })
        ->where('pnp.naskah_id','=',$data->naskah_id)
        ->select('pp.nama')
        ->get();
        $format_buku = DB::table('format_buku')->whereNull('deleted_at')->get();
        $kelengkapan = (object)[
            [
                'value' => 'CD'
            ],
            [
                'value' => 'Disket'
            ],
            [
                'value' => 'VCD'
            ],
        ];
        $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        return view('penerbitan.des_produk.edit',[
            'title' => 'Edit Deskripsi Produk',
            'data' => $data,
            'penulis' => $penulis,
            'format_buku' => $format_buku,
            'kelengkapan' => $kelengkapan,
            'imprint' => $imprint
        ]);
    }
}
