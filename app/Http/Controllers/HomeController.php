<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    public function index()
    {
        // var_dump(session('menus')); die();
        // $tahapan = is_null(null)?'all':'naskah-masuk';
        // $mulai = null; $selesai = null;
        // if(!is_null(null)) {
        //     $tanggal = explode(' - ', '27 May 2022 - 27 May 2022');
        //     $mulai = Carbon::createFromFormat('d F Y', $tanggal[0])->format('Y-m-d');
        //     $selesai = Carbon::createFromFormat('d F Y', $tanggal[1])->format('Y-m-d');
        // }

        // $data = DB::table('penerbitan_naskah as pn')
        //             ->whereNull('deleted_at')
        //             ->leftJoin('timeline as t', 'pn.id', '=', 't.naskah_id');

        // if($tahapan=='naskah-masuk') {
        //     if(!is_null($mulai)) {
        //         $data = $data->where('pn.tanggal_masuk_naskah', '>', $mulai)
        //                     ->where('pn.tanggal_masuk_naskah', '<', $selesai);
        //     }
        // } elseif ($tahapan=='penerbitan') {
        //     if(!is_null($mulai)) {
        //         $data = $data->where('t.tgl_mulai_penerbitan', '>', $mulai)
        //                     ->where('t.tgl_mulai_penerbitan', '<', $selesai);
        //     }
        // } elseif ($tahapan=='produksi') {
        //     if(!is_null($mulai)) {
        //         $data = $data->where('t.tgl_mulai_produksi', '>', $mulai)
        //                     ->where('t.tgl_mulai_produksi', '<', $selesai);
        //     }
        // } elseif ($tahapan=='buku-jadi') {
        //     if(!is_null($mulai)) {
        //         $data = $data->where('t.tgl_buku_jadi', '>', $mulai)
        //                     ->where('t.tgl_buku_jadi', '<', $selesai);
        //     }
        // } else {}

        // $data = $data->select(DB::raw('pn.id, pn.kode, pn.judul_asli, pn.jalur_buku, pn.tanggal_masuk_naskah as tgl_masuk,
        //             t.tgl_mulai_penerbitan as tgl_penerbitan, t.tgl_mulai_produksi as tgl_produksi,
        //             t.tgl_buku_jadi as tgl_jadi'))
        //         ->get();

        // $data = $data->map(function($arr) {
        //     $arr->tgl_masuk = Carbon::createFromFormat('Y-m-d', $arr->tgl_masuk)->format('d F Y');
        //     $arr->tgl_penerbitan = $arr->tgl_penerbitan!=''?Carbon::createFromFormat('Y-m-d H:i:s', $arr->tgl_penerbitan)->format('d F Y'):'-';
        //     $arr->tgl_produksi = $arr->tgl_produksi!=''?Carbon::createFromFormat('Y-m-d H:i:s', $arr->tgl_produksi)->format('d F Y'):'-';
        //     $arr->tgl_jadi = $arr->tgl_jadi!=''?Carbon::createFromFormat('Y-m-d H:i:s', $arr->tgl_jadi)->format('d F Y'):'-';
        //     return $arr;
        // });

        // return Datatables::of($data)->make(true);
        $timeline = FALSE;
        $naskahSelectTimeline = DB::table('penerbitan_naskah as pn');
        if (in_array(auth()->id(),$naskahSelectTimeline->pluck('pic_prodev')->toArray())) {
            $timeline = TRUE;
            $naskahSelectTimeline=$naskahSelectTimeline->where('pic_prodev', auth()->id());
        }
        $naskahSelectTimeline=$naskahSelectTimeline->get();
        if (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') {
            $timeline = TRUE;
        } elseif (Gate::allows('do_approval', 'approval-deskripsi-produk')) {
            $timeline = TRUE;
        } elseif (Gate::allows('do_create', 'ubah-atau-buat-setter-mou')) {
            $timeline = TRUE;
        } elseif (Gate::allows('do_create', 'ubah-atau-buat-setter-reguler')) {
            $timeline = TRUE;
        } elseif (Gate::allows('do_create', 'ubah-atau-buat-setter-smk')) {
            $timeline = TRUE;
        } elseif (Gate::allows('do_create', 'otorisasi-kabag-prades-mou')) {
            $timeline = TRUE;
        } elseif (Gate::allows('do_create', 'otorisasi-kabag-prades-reguler')) {
            $timeline = TRUE;
        } elseif (Gate::allows('do_create', 'otorisasi-kabag-prades-smk')) {
            $timeline = TRUE;
        } elseif (Gate::allows('do_create', 'ubah-atau-buat-editing-smk')) {
            $timeline = TRUE;
        } elseif (Gate::allows('do_create', 'ubah-atau-buat-editing-reguler')) {
            $timeline = TRUE;
        } elseif (Gate::allows('do_create', 'ubah-atau-buat-editing-mou')) {
            $timeline = TRUE;
        }
        $naskah = DB::table('penerbitan_naskah')->whereNull('deleted_at')->get();
        $userdata = DB::table('users')->where('id', auth()->user()->id)->first();
        $users = DB::table('users')->whereNotIn('id', [auth()->user()->id])->whereNull('deleted_at')->get();
        $imprint = DB::table('imprint')->get();
        $penulis = DB::table('penerbitan_penulis')->whereNull('deleted_at')->get();
        $divisi = DB::table('divisi')->whereNull('deleted_at')->get();
        $desprod = DB::table('deskripsi_produk')->get();
        $desfin = DB::table('deskripsi_final')->get();
        $descov = DB::table('deskripsi_cover')->get();
        $editing = DB::table('editing_proses')->get();
        $praset = DB::table('pracetak_setter')->get();
        $prades = DB::table('pracetak_cover')->get();
        $desturcet = DB::table('deskripsi_turun_cetak')->get();
        $or_ce = DB::table('order_cetak as poc')->join('order_cetak_action as ppoc','ppoc.order_cetak_id','=','poc.id')->get();
        $or_eb = DB::table('order_ebook as poe')->join('order_ebook_action as ppoe', 'ppoe.order_ebook_id', '=', 'poe.id')->get();
        $proses_cetak = DB::table('proses_produksi_cetak')->whereNull('kirim_gudang')->get();
        $upload_ebook = DB::table('proses_ebook_multimedia')->get();
        $semua = DB::table('todo_list')->where('users_id',auth()->id())->get();
        $belum = DB::table('todo_list')->where('users_id',auth()->id())->where('status','0')->get();
        $selesai = DB::table('todo_list')->where('users_id',auth()->id())->where('status','1')->get();
        return view('home', [
            'title' => 'Home',
            'id' => Str::uuid()->getHex(),
            'userdata' => $userdata,
            'users' => $users,
            'imprint' => $imprint,
            'penulis' => $penulis,
            'divisi' => $divisi,
            'naskah' => $naskah,
            'desprod' => $desprod,
            'desfin' => $desfin,
            'descov' => $descov,
            'editing' => $editing,
            'praset' => $praset,
            'prades' => $prades,
            'desturcet' => $desturcet,
            'or_ce' => $or_ce,
            'or_eb' => $or_eb,
            'proses_cetak' => $proses_cetak,
            'upload_ebook' => $upload_ebook,
            'timeline' => $timeline,
            'naskah_kode_timeline'=>$naskahSelectTimeline,
            'semua' => $semua,
            'belum' => $belum,
            'selesai' => $selesai
        ]);
    }

    public function ajaxPenerbitan(Request $request, $cat)
    {
        switch ($cat) {
            case 'getNaskahData':
                return $this->dataNaskah($request);
        }
    }

    protected function dataNaskah($request)
    {
        $tahapan = is_null($request->input('tahapan')) ? 'all' : $request->input('tahapan');
        $mulai = null;
        $selesai = null;
        if (!is_null($request->input('tanggal'))) {
            $tanggal = explode(' - ', $request->input('tanggal'));
            $mulai = Carbon::createFromFormat('d F Y', $tanggal[0])->format('Y-m-d');
            $selesai = Carbon::createFromFormat('d F Y', $tanggal[1])->format('Y-m-d');
        }

        $data = DB::table('penerbitan_naskah as pn')
            ->whereNull('deleted_at')
            ->leftJoin('timeline as t', 'pn.id', '=', 't.naskah_id');

        if ($tahapan == 'naskah-masuk') {
            if (!is_null($mulai)) {
                $data = $data->where('pn.tanggal_masuk_naskah', '>', $mulai)
                    ->where('pn.tanggal_masuk_naskah', '<', $selesai);
            }
        } elseif ($tahapan == 'penerbitan') {
            if (!is_null($mulai)) {
                $data = $data->where('t.tgl_mulai_penerbitan', '>', $mulai)
                    ->where('t.tgl_mulai_penerbitan', '<', $selesai);
            }
        } elseif ($tahapan == 'produksi') {
            if (!is_null($mulai)) {
                $data = $data->where('t.tgl_mulai_produksi', '>', $mulai)
                    ->where('t.tgl_mulai_produksi', '<', $selesai);
            }
        } elseif ($tahapan == 'buku-jadi') {
            if (!is_null($mulai)) {
                $data = $data->where('t.tgl_buku_jadi', '>', $mulai)
                    ->where('t.tgl_buku_jadi', '<', $selesai);
            }
        } else {
        }

        $data = $data->select(DB::raw('pn.id, pn.kode, pn.judul_asli, pn.jalur_buku, pn.tanggal_masuk_naskah as tgl_masuk,
                    t.tgl_mulai_penerbitan as tgl_penerbitan, t.tgl_mulai_produksi as tgl_produksi,
                    t.tgl_buku_jadi as tgl_jadi'))
            ->get();

        $data = $data->map(function ($arr) {
            $arr->judul_asli = substr($arr->judul_asli, 0, 30) . (strlen($arr->judul_asli) > 30 ? '...' : '');
            $arr->tgl_masuk = Carbon::createFromFormat('Y-m-d', $arr->tgl_masuk)->format('d F Y');
            $arr->tgl_penerbitan = $arr->tgl_penerbitan != '' ? Carbon::createFromFormat('Y-m-d H:i:s', $arr->tgl_penerbitan)->format('d F Y') : '-';
            $arr->tgl_produksi = $arr->tgl_produksi != '' ? Carbon::createFromFormat('Y-m-d H:i:s', $arr->tgl_produksi)->format('d F Y') : '-';
            $arr->tgl_jadi = $arr->tgl_jadi != '' ? Carbon::createFromFormat('Y-m-d H:i:s', $arr->tgl_jadi)->format('d F Y') : '-';
            return $arr;
        });

        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                return '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $data->id) . '"
                            class="btn btn-sm btn-primary btn-icon" target="_blank">
                            <div><i class="fas fa-envelope-open-text"></i></div></a>';
            })->make(true);
    }
    public function apiAjax(Request $request)
    {
        switch ($request->cat) {
            case 'recent-activity':
                return $this->recentActivity($request);
                break;
            case 'delete-todo':
                return $this->deleteTodo($request);
                break;
            default:
                return abort(500);
                break;
        }
    }
    protected function recentActivity($request)
    {
        try {
            $html ='';
            $data = DB::table('user_log as ul')
                ->join('users as u', 'u.id', '=', 'ul.users_id')
                ->join('jabatan as j','j.id','=','u.jabatan_id')
                ->join('divisi as d','d.id','=','u.divisi_id')
                ->select('ul.*', 'u.nama','u.email','u.avatar','j.nama as jabatan','d.nama as divisi')
                ->orderBy('ul.last_login', 'desc')
                ->paginate(4);
            foreach ($data as $key => $d) {
                if (auth()->id() == $d->users_id) {
                    $namaUser = 'Anda';
                } else {
                    $namaUser = ucwords($d->nama);
                }

                $html .='<li class="media">
                <img class="mr-3 rounded-circle" src="'.url('storage/users/' . $d->users_id . '/' . $d->avatar).'" alt="avatar"
                    width="50">
                <div class="media-body">
                    <div class="float-right text-primary">'.Carbon::parse($d->last_login,'Asia/Jakarta')->diffForHumans().'</div>
                    <div class="media-title">'.$namaUser.'</div>
                    <span class="text-small">'.$d->email.' - '.$d->ip_address.'</span>
                    <p class="text-small text-muted">'.$d->jabatan.' ('.$d->divisi.')</p>
                </div>
            </li>';
            }
            return $html;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function deleteTodo($request)
    {
        try {
            DB::table('todo_list')->where('id',$request->id)->delete();
            return;
        } catch (\Exception $e) {
            return abort(500);
        }
    }
}
