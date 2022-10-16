<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use App\Events\DesfinEvent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};
use PhpParser\Node\Stmt\Continue_;
use Illuminate\Support\Arr;

class DeskripsiFinalController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
                $data = DB::table('deskripsi_final as df')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->whereNull('df.deleted_at')
                    ->select(
                        'df.*',
                        'pn.kode',
                        'pn.judul_asli',
                        'pn.pic_prodev',
                        'dp.naskah_id',
                        'dp.imprint',
                        'dp.judul_final'
                        )
                    ->orderBy('df.tgl_deskripsi','ASC')
                    ->get();
                $update = Gate::allows('do_create', 'ubah-atau-buat-des-final');

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
                        ->addColumn('pic_prodev', function($data) {
                            $result = '';
                            $res = DB::table('users')->where('id',$data->pic_prodev)->whereNull('deleted_at')
                            ->select('nama')
                            ->get();
                            foreach (json_decode($res) as $q) {
                                $result .= $q->nama;
                            }
                            return $result;
                        })
                        ->addColumn('history', function ($data) {
                            $historyData = DB::table('deskripsi_final_history')->where('deskripsi_final_id',$data->id)->get();
                            if($historyData->isEmpty()) {
                                return '-';
                            } else {
                                $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="'.$data->id.'" data-judulasli="'.$data->judul_asli.'"><i class="fas fa-history"></i>&nbsp;History</button>';
                                return $date;
                            }
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('penerbitan/deskripsi/final/detail?desc='.$data->id.'&kode='.$data->kode).'"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                                    $btn .= '<a href="'.url('penerbitan/deskripsi/final/edit?desc='.$data->id.'&kode='.$data->kode).'"
                                        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                        <div><i class="fas fa-edit"></i></div></a>';
                                }
                            }
                            if (Gate::allows('do_approval','action-progress-des-produk')) {
                                if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                                    if ($data->status == 'Antrian'){
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-desfin" style="background:#34395E;color:white" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Pending') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-desfin" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Proses') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-desfin" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Selesai') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-light btn-icon mr-1 mt-1 btn-status-desfin" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Revisi') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-info btn-icon mr-1 mt-1 btn-status-desfin" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Acc') {
                                        $btn .= '<span class="d-block badge badge-light mr-1 mt-1">'.$data->status.'</span>';
                                    }
                                }

                            } else {
                                if($data->status == 'Antrian'){
                                    $btn .= '<span class="d-block badge badge-secondary mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Pending') {
                                    $btn .= '<span class="d-block badge badge-danger mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Proses') {
                                    $btn .= '<span class="d-block badge badge-success mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Selesai') {
                                    $btn .= '<span class="d-block badge badge-light mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Revisi') {
                                    $btn .= '<span class="d-block badge badge-info mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Acc') {
                                    $btn .= '<span class="d-block badge badge-light mr-1 mt-1">'.$data->status.'</span>';
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
                            'pic_prodev',
                            'history',
                            'action'
                            ])
                        ->make(true);

        }
        $data = DB::table('deskripsi_final as df')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->whereNull('df.deleted_at')
                    ->select(
                        'df.*',
                        'pn.kode',
                        'pn.judul_asli',
                        'pn.pic_prodev',
                        'dp.naskah_id',
                        'dp.imprint',
                        'dp.judul_final'
                        )
                    ->orderBy('df.tgl_deskripsi','ASC')
                    ->get();
                    //Isi Warna Enum
            $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'status'"))[0]->Type;
            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
            $statusProgress = explode("','", $matches[1]);

        return view('penerbitan.des_final.index', [
            'title' => 'Deskripsi Final',
            'status_progress' => Arr::sort($statusProgress),
            'count' => count($data)
        ]);
    }
    public function detailDeskripsiFinal(Request $request)
    {
        $id = $request->get('desc');
        $kode = $request->get('kode');
        $data = DB::table('deskripsi_final as df')
            ->join('deskripsi_produk as dp','dp.id','=','df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb',function($q){
                $q->on('pn.kelompok_buku_id','=','kb.id')
                ->whereNull('kb.deleted_at');
            })
            ->where('df.id',$id)
            ->where('pn.kode',$kode)
            ->whereNull('dp.deleted_at')
            ->whereNull('pn.deleted_at')
            ->select(
                'df.*',
                'dp.naskah_id',
                'dp.format_buku',
                'dp.judul_final',
                'dp.imprint',
                'dp.jml_hal_perkiraan',
                'dp.kelengkapan',
                'dp.catatan',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev',
                'kb.nama'
                )
            ->first();
        if(is_null($data)) {
            return redirect()->route('desfin.view');
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
        ->join('penerbitan_penulis as pp',function($q) {
            $q->on('pnp.penulis_id','=','pp.id')
            ->whereNull('pp.deleted_at');
        })
        ->where('pnp.naskah_id','=',$data->naskah_id)
        ->select('pp.nama')
        ->get();
        $pic = DB::table('users')->where('id',$data->pic_prodev)->whereNull('deleted_at')->select('nama')->first();
        $setter = DB::table('users')->where('id',$data->setter)->whereNull('deleted_at')->select('nama')->first();
        $korektor = DB::table('users')->where('id',$data->korektor)->whereNull('deleted_at')->select('nama')->first();
        return view('penerbitan.des_final.detail',[
            'title' => 'Detail Deskripsi Final',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'setter' => $setter,
            'korektor' => $korektor
        ]);
    }
    public function editDeskripsiFinal(Request $request)
    {
        if($request->ajax()) {
            if ($request->isMethod('POST')) {
                $history = DB::table('deskripsi_final as df')
                ->join('deskripsi_produk as dp','dp.id','=','df.deskripsi_produk_id')
                ->where('df.id', $request->id)
                ->whereNull('df.deleted_at')
                ->select('df.*','dp.judul_final')
                ->first();
                foreach ($request->bullet as $value) {
                    $bullet[] = $value;
                }
                $update = [
                    'params' => 'Edit Desfin',
                    'id' => $request->id,
                    'judul_final' => $request->judul_final,//Di Deskripsi Produk
                    'sub_judul_final' => $request->sub_judul_final,
                    'kertas_isi' => $request->kertas_isi,
                    'jml_hal_asli' =>$request->jml_hal_asli,
                    'ukuran_asli' => $request->ukuran_asli,
                    'isi_warna' => $request->isi_warna,
                    'isi_huruf' => $request->isi_huruf,
                    'bullet' =>  json_encode(array_filter($bullet)),
                    'setter' => $request->setter,
                    'korektor' => $request->korektor,
                    'sinopsis' => $request->sinopsis,
                    'bulan' => Carbon::createFromDate($request->bulan),
                    'updated_by'=> auth()->id()
                ];
                event(new DesfinEvent($update));

                $insert = [
                    'params' => 'Insert History Desfin',
                    'deskripsi_final_id' => $request->id,
                    'type_history' => 'Update',
                    'judul_final_his' => $history->judul_final,
                    'judul_final_new' => $request->judul_final,
                    'sub_judul_final_his' => $history->sub_judul_final,
                    'sub_judul_final_new' => $request->sub_judul_final,
                    'kertas_isi_his' => $history->kertas_isi,
                    'kertas_isi_new' => $request->kertas_isi,
                    'jml_hal_asli_his' => $history->jml_hal_asli,
                    'jml_hal_asli_new' => $request->jml_hal_asli,
                    'ukuran_asli_his' => $history->ukuran_asli,
                    'ukuran_asli_new' => $request->ukuran_asli,
                    'isi_warna_his' => $history->isi_warna,
                    'isi_warna_new' => $request->isi_warna,
                    'isi_huruf_his' => $history->isi_huruf,
                    'isi_huruf_new' => $request->isi_huruf,
                    'bullet_his' => $history->bullet,
                    'bullet_new' => json_encode(array_filter($bullet)),
                    'setter_his' => $history->setter,
                    'setter_new' => $request->setter,
                    'korektor_his' => $history->korektor,
                    'korektor_new' => $request->korektor,
                    'sinopsis_his' => $history->sinopsis,
                    'sinopsis_new' => $request->sinopsis,
                    'bulan_his' => $history->bulan,
                    'bulan_new' => Carbon::createFromDate($request->bulan),
                    'author_id' => auth()->id(),
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new DesfinEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data deskripsi final berhasil ditambahkan',
                    'route' => route('desfin.view')
                ]);
            }
        }
        $id = $request->get('desc');
        $kodenaskah = $request->get('kode');
        $data = DB::table('deskripsi_final as df')
            ->join('deskripsi_produk as dp','dp.id','=','df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb',function($q){
                $q->on('pn.kelompok_buku_id','=','kb.id')
                ->whereNull('kb.deleted_at');
            })
            ->where('df.id',$id)
            ->where('pn.kode',$kodenaskah)
            ->whereNull('dp.deleted_at')
            ->whereNull('pn.deleted_at')
            ->select(
                'df.*',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.jml_hal_perkiraan',
                'dp.format_buku',
                'dp.kelengkapan',
                'dp.catatan',
                'dp.imprint',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev',
                'kb.nama'
                )
            ->first();
        is_null($data)?abort(404):
        $setter = DB::table('users as u')->join('jabatan as j','u.jabatan_id', '=', 'j.id')
            ->where('j.nama','LIKE','%Setter%')
            ->orWhere('u.email','LIKE','%setter%')
            ->select('u.nama','u.id')
            ->get();
        if(!is_null($data->setter)){
            $namaSetter = DB::table('users')
                ->where('id',$data->setter)
                ->first();
        }
        $Korektor = DB::table('users as u')->join('jabatan as j','u.jabatan_id', '=', 'j.id')
            ->where('j.nama','LIKE','%Korektor%')
            ->orWhere('u.email','LIKE', '%korektor%')
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

        //Kertas Isi Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'kertas_isi'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $kertas_isi = explode("','", $matches[1]);
        //Kelengkapan Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_produk WHERE Field = 'kelengkapan'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $kelengkapan = explode("','", $matches[1]);
        //Isi Warna Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'isi_warna'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $isi_warna = explode("','", $matches[1]);

        // $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        return view('penerbitan.des_final.edit',[
            'title' => 'Edit Deskripsi Final',
            'data' => $data,
            'setter' => $setter,
            'korektor' => $Korektor,
            'penulis' => $penulis,
            'format_buku' => $format_buku,
            'kelengkapan' => $kelengkapan,
            'kertas_isi' => $kertas_isi,
            'isi_warna' => $isi_warna,
            'nama_setter' => $namaSetter
            // 'imprint' => $imprint
        ]);
    }
    public function updateStatusProgress(Request $request)
    {
        try{
            $id = $request->id;
            $data = DB::table('deskripsi_final')->where('id',$id)->whereNull('deleted_at')->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ],404);
            }
            if (is_null($data->sinopsis)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Harap input sinopis dahulu!'
                ]);
            }
            $update = [
                'params' => 'Update Status Desfin',
                'id' => $data->id,
                'status' => $request->status,
                'updated_by' => auth()->id()
            ];
            event(new DesfinEvent($update));
            $insert = [
                'params' => 'Insert History Status Desfin',
                'deskripsi_final_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            event(new DesfinEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Status progress deskripsi final berhasil diupdate'
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryDesfin(Request $request)
    {
        if($request->ajax()){
            $html = '';
            $id = $request->id;
            $data = DB::table('deskripsi_final_history as dfh')
                ->join('deskripsi_final as df','df.id','=','dfh.deskripsi_final_id')
                ->join('deskripsi_produk as dp','dp.id','=','df.deskripsi_produk_id')
                ->join('users as u','dfh.author_id','=','u.id')
                ->where('dfh.deskripsi_final_id',$id)
                ->select('dfh.*','dp.judul_final','u.nama')
                ->orderBy('dfh.id', 'desc')
                ->paginate(2);
            foreach ($data as $key => $d){
                if($d->type_history == 'Status'){
                    $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title">
                        <span><span class="bullet"></span> Status deskripsi final <b class="text-dark">'.$d->status_his.'</b> diubah menjadi <b class="text-dark">'.$d->status_new.'</b>.</span>
                    </div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="'.url('/manajemen-web/user/'.$d->author_id).'">'.$d->nama.'</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">'.Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans().' ('.Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i').')</div>
                    </div>
                    </span>';

                } elseif ($d->type_history == 'Update') {
                    $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                    if (!is_null($d->judul_final_his)) {
                        if($d->judul_final_his != $d->judul_final_new){
                            $html .=' Judul final <b class="text-dark">'.$d->judul_final_his.'</b> diubah menjadi <b class="text-dark">'.$d->judul_final_new.'</b>.<br>';
                        }
                    }
                    if (!is_null($d->format_buku_his)) {
                        if($d->format_buku_his != $d->format_buku_new){
                            $html .=' Format buku <b class="text-dark">'.$d->format_buku_his.' cm</b> diubah menjadi <b class="text-dark">'.$d->format_buku_new.' cm</b>.<br>';
                        }
                    }
                    if (!is_null($d->jml_hal_his)) {
                        if($d->jml_hal_his != $d->jml_hal_new){
                            $html .=' Jumlah halaman perkiraan <b class="text-dark">'.$d->jml_hal_his.'</b> diubah menjadi <b class="text-dark">'.$d->jml_hal_new.'</b>.<br>';
                        }
                    }
                    if (!is_null($d->jml_hal_asli_his)) {
                        if($d->jml_hal_asli_his != $d->jml_hal_asli_new){
                            $html .=' Jumlah halaman asli <b class="text-dark">'.$d->jml_hal_asli_his.'</b> diubah menjadi <b class="text-dark">'.$d->jml_hal_asli_new.'</b>.<br>';
                        }
                    }
                    if (!is_null($d->ukuran_asli_his)) {
                        if($d->ukuran_asli_his != $d->ukuran_asli_new){
                            $html .=' Ukuran asli <b class="text-dark">'.$d->ukuran_asli_his.'</b> diubah menjadi <b class="text-dark">'.$d->ukuran_asli_new.'</b>.<br>';
                        }
                    }
                    if (!is_null($d->isi_warna_his)) {
                        if($d->isi_warna_his != $d->isi_warna_new) {
                            $html .=' Isi warna <b class="text-dark">'.$d->isi_warna_his.'</b> diubah menjadi <b class="text-dark">'.$d->isi_warna_new.'</b>.<br>';
                        }
                    }
                    if (!is_null($d->setter_his)) {
                        if ($d->setter_his != $d->setter_new) {
                            $html .=' Setter <b class="text-dark">'
                            .DB::table('users')->where('id',$d->setter_his)->whereNull('deleted_at')->first()->nama.
                            '</b> diubah menjadi <b class="text-dark">'.DB::table('users')->where('id',$d->setter_new)->whereNull('deleted_at')->first()->nama.'</b>.<br>';
                        }
                    }
                    if (!is_null($d->korektor_his)) {
                        if ($d->korektor_his != $d->korektor_new) {
                            $html .=' Korektor <b class="text-dark">'
                            .DB::table('users')->where('id',$d->korektor_his)->whereNull('deleted_at')->first()->nama.
                            '</b> diubah menjadi <b class="text-dark">'.DB::table('users')->where('id',$d->korektor_new)->whereNull('deleted_at')->first()->nama.'</b>.<br>';
                        }
                    }
                    if (!is_null($d->kelengkapan_his)) {
                        $html .=' Kelengkapan <b class="text-dark">'.$d->kelengkapan_his.'</b> diubah menjadi <b class="text-dark">'.$d->kelengkapan_new.'</b>.<br>';
                    }
                    if (!is_null($d->catatan_his)) {
                        $html .=' Catatan <b class="text-dark">'.$d->catatan_his.'</b> diubah menjadi <b class="text-dark">'.$d->catatan_new.'</b>.<br>';
                    }
                    if (!is_null($d->bulan_his)) {
                        if ($d->bulan_his != $d->bulan_new) {
                            $html .=' Bulan <b class="text-dark">'.Carbon::parse($d->bulan_his)->translatedFormat('F Y').'</b> diubah menjadi <b class="text-dark">'.Carbon::parse($d->bulan_new)->translatedFormat('F Y').'</b>.';
                        }
                    }
                    $html .='</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="'.url('/manajemen-web/user/'.$d->author_id).'">'.$d->nama.'</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">'.Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans().' ('.Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i').')</div>

                    </div>
                    </span>';
                }
            }
            return $html;
        }
    }
}
