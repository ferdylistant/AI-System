<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

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
        if (in_array(auth()->id(), $naskahSelectTimeline->pluck('pic_prodev')->toArray())) {
            $timeline = TRUE;
            $naskahSelectTimeline = $naskahSelectTimeline->where('pic_prodev', auth()->id());
        }
        $naskahSelectTimeline = $naskahSelectTimeline->get();
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
        $naskah = Cache::rememberForever('naskahHome', function() {
            return DB::table('penerbitan_naskah')->whereNull('deleted_at')->get();
        });
        $userdata =  DB::table('users')->where('id', Auth::id())->first();
        $imprint = Cache::rememberForever('imprintHome', function() {
            return DB::table('imprint')->get();
        });
        $penulis = Cache::rememberForever('penulisHome', function() {
            return DB::table('penerbitan_penulis')->whereNull('deleted_at')->get();
        });
        $users = DB::table('users')->whereNotIn('id', [Auth::id()])->whereNull('deleted_at')->get();
        $divisi = DB::table('divisi')->whereNull('deleted_at')->get();
        $desprod = DB::table('deskripsi_produk')->get();
        $desfin = DB::table('deskripsi_final')->get();
        $descov = DB::table('deskripsi_cover')->get();
        $editing = DB::table('editing_proses')->get();
        $praset = DB::table('pracetak_setter')->get();
        $prades = DB::table('pracetak_cover')->get();
        $desturcet = DB::table('deskripsi_turun_cetak')->get();
        $or_ce = DB::table('order_cetak as poc')->join('order_cetak_action as ppoc', 'ppoc.order_cetak_id', '=', 'poc.id')->get();
        $or_eb = DB::table('order_ebook as poe')->join('order_ebook_action as ppoe', 'ppoe.order_ebook_id', '=', 'poe.id')->get();
        $proses_cetak = DB::table('proses_produksi_cetak')->whereNull('kirim_gudang')->get();
        $upload_ebook = DB::table('proses_ebook_multimedia')->get();
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
            'naskah_kode_timeline' => $naskahSelectTimeline,
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
            case 'semua':
                $semua = DB::table('todo_list')->where('users_id', auth()->user()->id)->get();
                return $this->loadTodoList($semua);
                break;
            case 'belum-selesai':
                $belumSelesai = DB::table('todo_list')->where('users_id', auth()->user()->id)->where('status', '0')->get();
                return $this->loadTodoList($belumSelesai);
                break;
            case 'selesai':
                $selesai = DB::table('todo_list')->where('users_id', auth()->user()->id)->where('status', '1')->get();
                return $this->loadTodoList($selesai);
                break;
            case 'select-judul':
                return self::selectMasterJudul($request);
                break;
            default:
                return abort(500);
                break;
        }
    }
    protected function recentActivity($request)
    {
        try {
            $html = '';
            $data = DB::table('user_log as ul')
                ->join('users as u', 'u.id', '=', 'ul.users_id')
                ->join('jabatan as j', 'j.id', '=', 'u.jabatan_id')
                ->join('divisi as d', 'd.id', '=', 'u.divisi_id')
                ->whereDate('ul.last_login', Carbon::today())
                ->orderBy('ul.last_login', 'desc')
                ->groupBy('ul.users_id')
                ->select('ul.*', 'u.nama', 'u.email', 'u.avatar', 'j.nama as jabatan', 'd.nama as divisi')
                ->paginate(4);
            if ($data->total() > 1) {
                $exist = TRUE;
                foreach ($data as $key => $d) {
                    if (auth()->id() == $d->users_id) {
                        $namaUser = 'Anda';
                    } else {
                        $namaUser = ucwords($d->nama);
                    }

                    $html .= '<li class="media">
                    <img class="mr-3 rounded-circle" src="' . url('storage/users/' . $d->users_id . '/' . $d->avatar) . '" alt="avatar"
                        width="50">
                    <div class="media-body">
                        <div class="float-right text-primary">' . Carbon::parse($d->last_login, 'Asia/Jakarta')->diffForHumans() . '</div>
                        <div class="media-title">' . $namaUser . '</div>
                        <span class="text-small">' . $d->email . ' - ' . $d->ip_address . '</span>
                        <p class="text-small text-muted">' . $d->jabatan . ' (' . $d->divisi . ')</p>
                    </div>
                    </li>';
                }
            } else {
                $exist = FALSE;
            }
            return ['html' => $html,'exist' => $exist];
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function loadTodoList($data)
    {
        try {
            // return response()->json($data);
            $html = '';
            $html .= '<ul class="list-group mb-0">';
            if ($data->isEmpty()) {
                $html .= '<div class="col-12 offset-3 mt-5">
                    <div class="row">
                        <div class="col-4 offset-1">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png"
                                width="100%">
                        </div>
                    </div>
                </div>';
            } else {
                foreach ($data as $d) {
                    $html .= '<li class="list-group-item d-flex justify-content-between align-items-center border-start-0 border-top-0 border-end-0 border-bottom rounded-0 mb-2 delete_list_'.$d->id.'"
                        style="box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;">
                        <div class="d-flex align-items-center">';
                    if ($d->status == '0') {
                        $html .= '<div class="beep-danger mb-2 ml-3"></div>';
                    } else {
                        $html .= '<div class="bullet"></div>';
                    }
                    $html .= '<a href="'. url($d->link) .'" class="text-dark text-decoration-none">'. $d->title .'</a>
                        </div>
                        <div class="justify-content-end mb-1">';
                    if ($d->status == '0') {
                        $html .= '<a href="javascript:void(0)" class="text-primary" tabindex="0" role="button"
                            data-toggle="popover" data-trigger="focus" title="'. $d->title .'"
                            data-content="Fitur ini akan tetap menjadi pengingat untuk selalu memperhatikan tugas dan kegiatan yang akan Anda lakukan.">
                            <abbr title="">
                            <i class="fas fa-question-circle me-3"></i>
                            </abbr>
                            </a>';
                    } else {
                        $html .= '<a href="javascript:void(0)" class="text-danger delete-todo" data-toggle="tooltip"
                            title="Hapus pengingat" data-id="'.$d->id.'">
                            <i class="fas fa-times me-3"></i>
                            </a>';
                    }

                    $html .= '</div>
                    </li>';
                }
            }
            $html .= '</ul>';
            return $html;
        } catch (\Exception $e) {
            return abort(500);
        }
    }
    protected function deleteTodo($request)
    {
        try {
            DB::table('todo_list')->where('id', $request->id)->delete();
            return;
        } catch (\Exception $e) {
            return abort(500);
        }
    }
    protected function selectMasterJudul($request)
    {
        $data = DB::table('pj_st_andi as st')->join('penerbitan_naskah as pn','st.naskah_id','=','pn.id')->join('deskripsi_produk as dp','pn.id','=','dp.naskah_id')
        ->where('dp.judul_final','like','%'.$request->term.'%')
        ->select('dp.judul_final')
        ->get();
        return response()->json($data);
    }
}
