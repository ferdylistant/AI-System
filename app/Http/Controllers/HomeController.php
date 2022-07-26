<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index() {
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
        $userdata = DB::table('users')->where('id', auth()->user()->id)->first();
        return view('home', [
            'title' => 'Home',
            'id' => Str::uuid()->getHex(),
            'userdata' => $userdata

        ]);
    }

    public function ajaxPenerbitan(Request $request, $cat) {
        switch($cat) {
            case 'getNaskahData':
                return $this->dataNaskah($request);
        }
    }

    protected function dataNaskah($request) {
        $tahapan = is_null($request->input('tahapan'))?'all':$request->input('tahapan');
        $mulai = null; $selesai = null;
        if(!is_null($request->input('tanggal'))) {
            $tanggal = explode(' - ', $request->input('tanggal'));
            $mulai = Carbon::createFromFormat('d F Y', $tanggal[0])->format('Y-m-d');
            $selesai = Carbon::createFromFormat('d F Y', $tanggal[1])->format('Y-m-d');
        }

        $data = DB::table('penerbitan_naskah as pn')
                    ->whereNull('deleted_at')
                    ->leftJoin('timeline as t', 'pn.id', '=', 't.naskah_id');

        if($tahapan=='naskah-masuk') {
            if(!is_null($mulai)) {
                $data = $data->where('pn.tanggal_masuk_naskah', '>', $mulai)
                            ->where('pn.tanggal_masuk_naskah', '<', $selesai);
            }
        } elseif ($tahapan=='penerbitan') {
            if(!is_null($mulai)) {
                $data = $data->where('t.tgl_mulai_penerbitan', '>', $mulai)
                            ->where('t.tgl_mulai_penerbitan', '<', $selesai);
            }
        } elseif ($tahapan=='produksi') {
            if(!is_null($mulai)) {
                $data = $data->where('t.tgl_mulai_produksi', '>', $mulai)
                            ->where('t.tgl_mulai_produksi', '<', $selesai);
            }
        } elseif ($tahapan=='buku-jadi') {
            if(!is_null($mulai)) {
                $data = $data->where('t.tgl_buku_jadi', '>', $mulai)
                            ->where('t.tgl_buku_jadi', '<', $selesai);
            }
        } else {}

        $data = $data->select(DB::raw('pn.id, pn.kode, pn.judul_asli, pn.jalur_buku, pn.tanggal_masuk_naskah as tgl_masuk,
                    t.tgl_mulai_penerbitan as tgl_penerbitan, t.tgl_mulai_produksi as tgl_produksi,
                    t.tgl_buku_jadi as tgl_jadi'))
                ->get();

        $data = $data->map(function($arr) {
            $arr->judul_asli = substr($arr->judul_asli, 0, 30).(strlen($arr->judul_asli)>30?'...':'');
            $arr->tgl_masuk = Carbon::createFromFormat('Y-m-d', $arr->tgl_masuk)->format('d F Y');
            $arr->tgl_penerbitan = $arr->tgl_penerbitan!=''?Carbon::createFromFormat('Y-m-d H:i:s', $arr->tgl_penerbitan)->format('d F Y'):'-';
            $arr->tgl_produksi = $arr->tgl_produksi!=''?Carbon::createFromFormat('Y-m-d H:i:s', $arr->tgl_produksi)->format('d F Y'):'-';
            $arr->tgl_jadi = $arr->tgl_jadi!=''?Carbon::createFromFormat('Y-m-d H:i:s', $arr->tgl_jadi)->format('d F Y'):'-';
            return $arr;
        });

        return Datatables::of($data)
                ->addColumn('action', function($data) {
                    return '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$data->id).'"
                            class="btn btn-sm btn-primary btn-icon" target="_blank">
                            <div><i class="fas fa-envelope-open-text"></i></div></a>';
                })->make(true);
    }


}
