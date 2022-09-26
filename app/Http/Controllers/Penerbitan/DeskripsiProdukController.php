<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Events\UpdateFbEvent;
use Yajra\DataTables\DataTables;
use App\Events\UpdateStatusEvent;
use App\Events\InsertStatusHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class DeskripsiProdukController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
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
                        // ->addIndexColumn()
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
                        ->addColumn('history', function ($data) {
                            $historyData = DB::table('deskripsi_produk_history')->where('deskripsi_produk_id',$data->id)->get();
                            if($historyData->isEmpty()) {
                                return '-';
                            } else {
                                $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="'.$data->id.'" data-toggle="modal" data-target="#md_DesproHistory"><i class="fas fa-history"></i>&nbsp;History</button>';
                                return $date;
                            }
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('penerbitan/deskripsi/produk/detail?desc='.$data->id.'&kode='.$data->kode).'"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('penerbitan/deskripsi/produk/edit?desc='.$data->id.'&kode='.$data->kode).'"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            if (Gate::allows('do_approval','action-progress-des-produk')) {
                                if ($data->status == 'Antrian'){
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-despro" style="background:#34395E;color:white" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesProduk" title="Update Status">
                                    <div>'.$data->status.'</div></a>';
                                } elseif ($data->status == 'Pending') {
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-despro" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesProduk" title="Update Status">
                                    <div>'.$data->status.'</div></a>';
                                } elseif ($data->status == 'Proses') {
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-despro" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesProduk" title="Update Status">
                                    <div>'.$data->status.'</div></a>';
                                } elseif ($data->status == 'Selesai') {
                                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-light btn-icon mr-1 mt-1 btn-status-despro" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesProduk" title="Update Status">
                                    <div>'.$data->status.'</div></a>';
                                }
                            } else {
                                if($data->status == 'Antrian'){
                                    $btn .= '<span class="d-block badge badge-secondary">'.$data->status.'</span>';
                                } elseif ($data->status == 'Pending') {
                                    $btn .= '<span class="d-block badge badge-danger">'.$data->status.'</span>';
                                } elseif ($data->status == 'Proses') {
                                    $btn .= '<span class="d-block badge badge-success">'.$data->status.'</span>';
                                } elseif ($data->status == 'Selesai') {
                                    $btn .= '<span class="d-block badge badge-light">'.$data->status.'</span>';
                                }
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
                            'history',
                            'action'
                            ])
                        ->make(true);

        }
        $statusProgress = (array)[
            [
                'value' => 'Antrian'
            ],
            [
                'value' => 'Pending'
            ],
            [
                'value' => 'Proses'
            ],
            [
                'value' => 'Selesai'
            ]
        ];
        return view('penerbitan.des_produk.index', [
            'title' => 'Deskripsi Produk',
            'status_progress' => $statusProgress
        ]);
    }
    public function detailDeskripsiProduk(Request $request)
    {
        $id = $request->get('desc');
        $kode = $request->get('kode');
        $data = DB::table('deskripsi_produk as dp')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb',function($q){
                $q->on('pn.kelompok_buku_id','=','kb.id')
                ->whereNull('kb.deleted_at');
            })
            ->where('dp.id',$id)
            ->where('pn.kode',$kode)
            ->whereNull('dp.deleted_at')
            ->select(
                'dp.*',
                'pn.kode',
                'pn.judul_asli',
                'kb.nama'
                )
            ->first();
        if(is_null($data)) {
            return redirect()->route('despro.view');
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
        ->join('penerbitan_penulis as pp',function($q) {
            $q->on('pnp.penulis_id','=','pp.id')
            ->whereNull('pp.deleted_at');
        })
        ->where('pnp.naskah_id','=',$data->naskah_id)
        ->select('pp.nama')
        ->get();
        $pic = DB::table('users')->where('id',$data->pembuat_deskripsi)->whereNull('deleted_at')->select('nama')->first();
        $editor = DB::table('users')->where('id',$data->editor)->whereNull('deleted_at')->select('nama')->first();
        return view('penerbitan.des_produk.detail',[
            'title' => 'Detail Deskripsi Produk',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'editor' => $editor
        ]);
    }
    public function editDeskripsiProduk(Request $request)
    {
        if($request->ajax()) {
            if ($request->isMethod('POST')) {
                foreach ($request->alt_judul as $value) {
                    $altJudul[] = $value;
                }
                DB::table('deskripsi_produk')
                    ->where('id', $request->id)
                    ->update([
                    'judul_final' => $request->judul_final,
                    'alt_judul' => json_encode($altJudul),
                    'format_buku' => $request->format_buku,
                    'jml_hal_perkiraan' => $request->jml_hal_perkiraan,
                    'imprint' => $request->imprint,
                    'kelengkapan' => $request->kelengkapan,
                    'editor' => $request->editor,
                    'catatan' => $request->catatan,
                    'bulan' => date('Y-m-d',strtotime($request->bulan)),
                    'status' => 'Proses',
                    'updated_by'=> auth()->id()
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data deskripsi produk berhasil ditambahkan',
                    'route' => route('despro.view')
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
        $editor = DB::table('users as u')->join('jabatan as j','u.jabatan_id', '=', 'j.id')
            ->where('j.nama','LIKE','%Editor%')
            ->select('u.nama','u.id')
            ->get();
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
            'editor' => $editor,
            'penulis' => $penulis,
            'format_buku' => $format_buku,
            'kelengkapan' => $kelengkapan,
            'imprint' => $imprint
        ]);
    }
    public function updateStatusProgress(Request $request)
    {
        try{
            $id = $request->id;
            $data = DB::table('deskripsi_produk')->where('id',$id)->whereNull('deleted_at')->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ],404);
            }
            $update = [
                'id' => $data->id,
                'status' => $request->status,
                'updated_by' => auth()->id()
            ];
            event(new UpdateStatusEvent($update));
            $insert = [
                'deskripsi_produk_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            event(new InsertStatusHistory($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Status progress deskripsi produk berhasil diupdate'
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryDespro(Request $request) {
        if($request->ajax()){
            $html = '';
            $id = $request->input('id');
            $data = DB::table('deskripsi_produk_history as pd')->join('users as u','pd.author_id','=','u.id')
            ->where('pd.deskripsi_produk_id',$id)
            ->select('pd.*','u.nama')
            ->paginate(5);
            foreach ($data as $d){
                if($d->type_history == 'Status'){
                    $html .= '<span class="ticket-item">
                    <div class="ticket-title">
                        <h6><span class="bullet"></span> Status deskripsi produk '.$d->status_his.' diubah menjadi '.$d->status_new.'.</h6>
                    </div>
                    <div class="ticket-info">
                        <div class="text-muted">Modified by <a href="'.url('/manajemen-web/user/'.$d->author_id).'">'.$d->nama.'</a></div>
                        <div class="bullet"></div>
                        <div>'.Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans().' ('.Carbon::parse($d->modified_at)->translatedFormat('d M Y, H:i').')</div>
                    </div>
                </span>';

                }
            }
        }
        // foreach ($data as $d){
        //     $result[] = [
        //         'type_history' => $d->type_history,
        //         'judul_final_his' => $d->judul_final_his,
        //         'judul_final_new' => $d->judul_final_new,
        //         'alt_judul_his' => $d->alt_judul_his,
        //         'alt_judul_new' => $d->alt_judul_new,
        //         'format_buku_his' => $d->format_buku_his,
        //         'format_buku_new' => $d->format_buku_new,
        //         'jml_hal_his' => $d->jml_hal_his,
        //         'jml_hal_new' => $d->jml_hal_new,
        //         'imprint_his' => $d->imprint_his,
        //         'imprint_new' => $d->imprint_new,
        //         'editor_his' => DB::table('users')->where('id',$d->editor_his)->whereNull('deleted_at')->first(),
        //         'editor_new' => DB::table('users')->where('id',$d->editor_new)->whereNull('deleted_at')->first(),
        //         'kelengkapan_his' => $d->kelengkapan_his,
        //         'kelengkapan_new' => $d->kelengkapan_new,
        //         'catatan_his' => $d->catatan_his,
        //         'catatan_new' => $d->catatan_new,
        //         'bulan_his' => Carbon::parse($d->bulan_his)->translatedFormat('F Y'),
        //         'bulan_new' => Carbon::parse($d->bulan_new)->translatedFormat('F Y'),
        //         'status_his' => $d->status_his,
        //         'status_new' => $d->status_new,
        //         'author_id' => $d->author_id,
        //         'nama' => $d->nama,
        //         'modified_at' => Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans(),
        //         'format_tanggal' => Carbon::parse($d->modified_at)->translatedFormat('d M Y, H:i')
        //     ];
        // }
        return $html;
    }

}
