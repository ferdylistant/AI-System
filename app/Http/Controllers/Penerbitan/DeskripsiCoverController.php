<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use App\Events\DescovEvent;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class DeskripsiCoverController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
                $data = DB::table('deskripsi_cover as dc')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->join('deskripsi_final as df', 'dp.id','=','df.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->whereNull('dc.deleted_at')
                    ->select(
                        'dc.*',
                        'pn.kode',
                        'pn.judul_asli',
                        'pn.pic_prodev',
                        'pn.kelompok_buku_id',
                        'dp.naskah_id',
                        'dp.judul_final',
                        'df.sub_judul_final',
                        'df.bullet'
                        )
                    ->orderBy('dc.tgl_deskripsi','ASC')
                    ->get();
                $update = Gate::allows('do_create', 'ubah-atau-buat-des-cover');

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
                        ->addColumn('kelompok_buku', function($data) {
                            if (!is_null($data->kelompok_buku_id)) {
                                $res = DB::table('penerbitan_m_kelompok_buku')->where('id',$data->kelompok_buku_id)->first()->nama;
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
                            return Carbon::parse($data->tgl_deskripsi)->translatedFormat('l d M Y H:i');

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
                            $historyData = DB::table('deskripsi_cover_history')->where('deskripsi_cover_id',$data->id)->get();
                            if($historyData->isEmpty()) {
                                return '-';
                            } else {
                                $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="'.$data->id.'" data-judulfinal="'.$data->judul_final.'"><i class="fas fa-history"></i>&nbsp;History</button>';
                                return $date;
                            }
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('penerbitan/deskripsi/cover/detail?desc='.$data->id.'&kode='.$data->kode).'"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';

                            if($data->status != 'Terkunci'){
                                if($update) {
                                    if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                                        $btn .= '<a href="'.url('penerbitan/deskripsi/cover/edit?desc='.$data->id.'&kode='.$data->kode).'"
                                            class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                            <div><i class="fas fa-edit"></i></div></a>';
                                    }
                                }
                            }

                            if (Gate::allows('do_approval','action-progress-des-cover')) {
                                if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                                    if ($data->status == 'Terkunci') {
                                        $btn .= '<span class="d-block badge badge-dark mr-1 mt-1"><i class="fas fa-lock"></i>&nbsp;'.$data->status.'</span>';
                                    } elseif ($data->status == 'Antrian'){
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-descov" style="background:#34395E;color:white" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesCover" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Pending') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-descov" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesCover" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Proses') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-descov" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesCover" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Selesai') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-light btn-icon mr-1 mt-1 btn-status-descov" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesCover" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    }
                                }
                            } else {
                                if($data->status == 'Terkunci'){
                                    $btn .= '<span class="d-block badge badge-dark mr-1 mt-1"><i class="fas fa-lock"></i>&nbsp;'.$data->status.'</span>';
                                } elseif($data->status == 'Antrian'){
                                    $btn .= '<span class="d-block badge badge-secondary mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Pending') {
                                    $btn .= '<span class="d-block badge badge-danger mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Proses') {
                                    $btn .= '<span class="d-block badge badge-success mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Selesai') {
                                    $btn .= '<span class="d-block badge badge-light mr-1 mt-1">'.$data->status.'</span>';
                                }
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'kode',
                            'judul_asli',
                            'penulis',
                            'kelompok_buku',
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
            $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_cover WHERE Field = 'status'"))[0]->Type;
            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
            $statusProgress = explode("','", $matches[1]);
            $statusFilter = Arr::sort($statusProgress);
            $statusAction = Arr::except($statusFilter,['4']);
        return view('penerbitan.des_cover.index', [
            'title' => 'Deskripsi Cover',
            'status_progress' => $statusFilter,
            'status_action' => $statusAction,
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
    public function editDeskripsiCover(Request $request)
    {
        if($request->ajax()) {
            if ($request->isMethod('POST')) {
                $history = DB::table('deskripsi_cover as dc')
                ->join('deskripsi_produk as dp','dp.id','=','dc.deskripsi_produk_id')
                ->join('deskripsi_final as df','df.deskripsi_produk','=','dp.id')
                ->where('dc.id', $request->id)
                ->whereNull('dc.deleted_at')
                ->select('dc.*','dp.judul_final','dp.format_buku','df.sub_judul_final','df.bullet')
                ->first();
                foreach ($request->bullet as $value) {
                    $bullet[] = $value;
                }
                $update = [
                    'params' => 'Edit Desfin',
                    'id' => $request->id,
                    'judul_final' => $request->judul_final,//Di Deskripsi Produk
                    'format_buku' => $request->format_buku,
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
                // event(new DesfinEvent($update));

                $insert = [
                    'params' => 'Insert History Desfin',
                    'deskripsi_final_id' => $request->id,
                    'type_history' => 'Update',
                    'format_buku_his' => $history->format_buku==$request->format_buku?NULL:$history->format_buku,
                    'format_buku_new' => $history->format_buku==$request->format_buku?NULL:$request->format_buku,
                    'judul_final_his' => $history->judul_final==$request->judul_final?NULL:$history->judul_final,
                    'judul_final_new' => $history->judul_final==$request->judul_final?NULL:$request->judul_final,
                    'sub_judul_final_his' => $history->sub_judul_final==$request->sub_judul_final?NULL:$history->sub_judul_final,
                    'sub_judul_final_new' => $history->sub_judul_final==$request->sub_judul_final?NULL:$request->sub_judul_final,
                    'kertas_isi_his' => $history->kertas_isi==$request->kertas_isi?NULL:$history->kertas_isi,
                    'kertas_isi_new' => $history->kertas_isi==$request->kertas_isi?NULL:$request->kertas_isi,
                    'jml_hal_asli_his' => $history->jml_hal_asli==$request->jml_hal_asli?NULL:$history->jml_hal_asli,
                    'jml_hal_asli_new' => $history->jml_hal_asli==$request->jml_hal_asli?NULL:$request->jml_hal_asli,
                    'ukuran_asli_his' => $history->ukuran_asli==$request->ukuran_asli?NULL:$history->ukuran_asli,
                    'ukuran_asli_new' => $history->ukuran_asli==$request->ukuran_asli?NULL:$request->ukuran_asli,
                    'isi_warna_his' => $history->isi_warna==$request->isi_warna?NULL:$history->isi_warna,
                    'isi_warna_new' => $history->isi_warna==$request->isi_warna?NULL:$request->isi_warna,
                    'isi_huruf_his' => $history->isi_huruf==$request->isi_huruf?NULL:$history->isi_huruf,
                    'isi_huruf_new' => $history->isi_huruf==$request->isi_huruf?NULL:$request->isi_huruf,
                    'bullet_his' => $history->bullet==json_encode(array_filter($bullet))?NULL:$history->bullet,
                    'bullet_new' => $history->bullet==json_encode(array_filter($bullet))?NULL:json_encode(array_filter($bullet)),
                    'setter_his' => $history->setter==$request->setter?NULL:$history->setter,
                    'setter_new' => $history->setter==$request->setter?NULL:$request->setter,
                    'korektor_his' => $history->korektor==$request->korektor?NULL:$history->korektor,
                    'korektor_new' => $history->korektor==$request->korektor?NULL:$request->korektor,
                    'sinopsis_his' => $history->sinopsis==$request->sinopsis?NULL:$history->sinopsis,
                    'sinopsis_new' => $history->sinopsis==$request->sinopsis?NULL:$request->sinopsis,
                    'bulan_his' => Carbon::createFromFormat('Y-m-d', $history->bulan)->format('Y-m-d')==Carbon::createFromDate($request->bulan)?NULL:Carbon::createFromFormat('Y-m-d', $history->bulan)->format('Y-m-d'),
                    'bulan_new' => Carbon::createFromFormat('Y-m-d', $history->bulan)->format('Y-m-d')==Carbon::createFromDate($request->bulan)?NULL:Carbon::createFromDate($request->bulan),
                    'author_id' => auth()->id(),
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                // event(new DesfinEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data deskripsi final berhasil ditambahkan',
                    'route' => route('desfin.view')
                ]);
            }
        }
        $id = $request->get('desc');
        $kodenaskah = $request->get('kode');
        $data = DB::table('deskripsi_cover as dc')
            ->join('deskripsi_produk as dp','dp.id','=','dc.deskripsi_produk_id')
            ->join('deskripsi_final as df','df.deskripsi_produk_id','=','dp.id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb',function($q){
                $q->on('pn.kelompok_buku_id','=','kb.id')
                ->whereNull('kb.deleted_at');
            })
            ->where('dc.id',$id)
            ->where('pn.kode',$kodenaskah)
            ->whereNull('dc.deleted_at')
            ->whereNull('pn.deleted_at')
            ->select(
                'dc.*',
                'df.sub_judul_final',
                'df.bullet',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.format_buku',
                'dp.kelengkapan',
                'dp.catatan',
                'dp.imprint',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev',
                'kb.nama',
                )
            ->first();
        is_null($data)?abort(404):
        $desainer = DB::table('users as u')->join('jabatan as j','u.jabatan_id', '=', 'j.id')
            ->where('j.nama','LIKE','%Desainer%')
            ->orWhere('j.nama','LIKE','%desainer%')
            ->orWhere('u.email','LIKE','%Desainer%')
            ->orWhere('u.email','LIKE','%desainer%')
            ->select('u.nama','u.id')
            ->get();
        if(!is_null($data->desainer)){
            $namaDesainer = DB::table('users')
                ->where('id',$data->desainer)
                ->first();
        } else {
            $namaDesainer = NULL;
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
        ->join('penerbitan_penulis as pp',function($q) {
            $q->on('pnp.penulis_id','=','pp.id')
            ->whereNull('pp.deleted_at');
        })
        ->where('pnp.naskah_id','=',$data->naskah_id)
        ->select('pp.nama')
        ->get();
        $format_buku = DB::table('format_buku')->whereNull('deleted_at')->get();

        //Jilid Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_cover WHERE Field = 'jilid'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $jilid = explode("','", $matches[1]);
        //Finishing Cover
        $finishingCover = ['Embosh','Foil','Glossy','Laminasi Dof','UV','UV Spot'];
        //Kelengkapan Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_produk WHERE Field = 'kelengkapan'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $kelengkapan = explode("','", $matches[1]);
        // $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        return view('penerbitan.des_cover.edit',[
            'title' => 'Edit Deskripsi Cover',
            'data' => $data,
            'desainer' => $desainer,
            'penulis' => $penulis,
            'format_buku' => $format_buku,
            'jilid' => $jilid,
            'nama_desainer' => $namaDesainer,
            'finishing_cover' => $finishingCover,
            'kelengkapan' => $kelengkapan
            // 'imprint' => $imprint
        ]);
    }
    public function updateStatusProgress(Request $request)
    {
        try{
            $id = $request->id;
            $data = DB::table('deskripsi_cover')->where('id',$id)->whereNull('deleted_at')->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ],404);
            }
            if ($data->status == $request->status) {
                return response()->json([
                   'status' => 'error',
                   'message' => 'Pilih status yang berbeda dengan status saat ini!'
                ]);
            }
            $update = [
                'params' => 'Update Status Descov',
                'deskripsi_produk_id' => $data->deskripsi_produk_id,
                'status' => $request->status,
                'updated_by' => auth()->id()
            ];
            $insert = [
                'params' => 'Insert History Status Descov',
                'deskripsi_cover_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            if ($data->status == 'Selesai') {
                // event(new DesfinEvent($update));
                // event(new DesfinEvent($insert));
                // $updateStatusDescov = [
                //     'params' => 'Update Status Descov',
                //     'deskripsi_produk_id' => $data->deskripsi_produk_id,
                //     'status' => 'Terkunci',
                //     'updated_by' => auth()->user()->id
                // ];
                // event(new DescovEvent($updateStatusDescov));
                // $msg = 'Status progress deskripsi cover berhasil diupdate';
            }
            elseif ($request->status == 'Selesai') {
                // event(new DesfinEvent($update));
                // event(new DesfinEvent($insert));
                // $updateStatusDescov = [
                //     'params' => 'Update Status Descov',
                //     'deskripsi_produk_id' => $data->deskripsi_produk_id,
                //     'status' => 'Antrian',
                //     'updated_by' => auth()->user()->id
                // ];
                // event(new DescovEvent($updateStatusDescov));
                // $msg = 'Deskripsi cover selesai, silahkan lanjut ke proses Deskripsi Cover..';
            }
            else {
                event(new DescovEvent($update));
                event(new DescovEvent($insert));
                $msg = 'Status progress deskripsi cover berhasil diupdate';
            }
            return response()->json([
                'status' => 'success',
                'message' => $msg
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryDescov(Request $request)
    {
        if($request->ajax()){
            $html = '';
            $id = $request->id;
            $data = DB::table('deskripsi_cover_history as dch')
                ->join('deskripsi_cover as dc','dc.id','=','dch.deskripsi_cover_id')
                ->join('deskripsi_produk as dp','dp.id','=','dc.deskripsi_produk_id')
                ->join('users as u','dch.author_id','=','u.id')
                ->where('dch.deskripsi_cover_id',$id)
                ->select('dch.*','dp.judul_final','u.nama',)
                ->orderBy('dch.id', 'desc')
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
                        $html .=' Judul final <b class="text-dark">'.$d->judul_final_his.'</b> diubah menjadi <b class="text-dark">'.$d->judul_final_new.'</b>.<br>';
                    }
                    if (!is_null($d->format_buku_his)) {
                        $html .=' Format buku <b class="text-dark">'.$d->format_buku_his.' cm</b> diubah menjadi <b class="text-dark">'.$d->format_buku_new.' cm</b>.<br>';
                    }
                    if (!is_null($d->jml_hal_his)) {
                        $html .=' Jumlah halaman perkiraan <b class="text-dark">'.$d->jml_hal_his.'</b> diubah menjadi <b class="text-dark">'.$d->jml_hal_new.'</b>.<br>';
                    }
                    if (!is_null($d->jml_hal_asli_his)) {
                        $html .=' Jumlah halaman asli <b class="text-dark">'.$d->jml_hal_asli_his.'</b> diubah menjadi <b class="text-dark">'.$d->jml_hal_asli_new.'</b>.<br>';
                    }
                    if (!is_null($d->ukuran_asli_his)) {
                        $html .=' Ukuran asli <b class="text-dark">'.$d->ukuran_asli_his.'</b> diubah menjadi <b class="text-dark">'.$d->ukuran_asli_new.'</b>.<br>';
                    }
                    if (!is_null($d->isi_warna_his)) {
                        $html .=' Isi warna <b class="text-dark">'.$d->isi_warna_his.'</b> diubah menjadi <b class="text-dark">'.$d->isi_warna_new.'</b>.<br>';
                    }
                    if (!is_null($d->setter_his)) {
                        $html .=' Setter <b class="text-dark">'
                            .DB::table('users')->where('id',$d->setter_his)->whereNull('deleted_at')->first()->nama.
                            '</b> diubah menjadi <b class="text-dark">'.DB::table('users')->where('id',$d->setter_new)->whereNull('deleted_at')->first()->nama.'</b>.<br>';
                    }
                    if (!is_null($d->korektor_his)) {
                        $html .=' Korektor <b class="text-dark">'
                            .DB::table('users')->where('id',$d->korektor_his)->whereNull('deleted_at')->first()->nama.
                            '</b> diubah menjadi <b class="text-dark">'.DB::table('users')->where('id',$d->korektor_new)->whereNull('deleted_at')->first()->nama.'</b>.<br>';
                    }
                    if (!is_null($d->kelengkapan_his)) {
                        $html .=' Kelengkapan <b class="text-dark">'.$d->kelengkapan_his.'</b> diubah menjadi <b class="text-dark">'.$d->kelengkapan_new.'</b>.<br>';
                    }
                    if (!is_null($d->catatan_his)) {
                        $html .=' Catatan <b class="text-dark">'.$d->catatan_his.'</b> diubah menjadi <b class="text-dark">'.$d->catatan_new.'</b>.<br>';
                    }
                    if (!is_null($d->bulan_his)) {
                            $html .=' Bulan <b class="text-dark">'.Carbon::parse($d->bulan_his)->translatedFormat('F Y').'</b> diubah menjadi <b class="text-dark">'.Carbon::parse($d->bulan_new)->translatedFormat('F Y').'</b>.';
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
