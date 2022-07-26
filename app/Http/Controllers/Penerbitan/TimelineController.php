<?php

namespace App\Http\Controllers\Penerbitan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Gate};
use Illuminate\Support\Str;
use Carbon\Carbon;

class TimelineController extends Controller
{
    public function index(Request $request) 
    {
        switch ($request->cat) {
            case 'timeline':
                return $this->timeline($request);
            case 'content-subtimeline':
                return $this->contentSubtimeline($request);
            case 'update-subtimeline':
                return $this->updateSubTimeline($request);
            default:
                abort(404);
        }
    }

    protected function timeline($request) 
    {
        if($request->input('request_')=='form') {
            $naskah = DB::table('penerbitan_naskah as pn')->whereNull('deleted_at')
                            ->where('id', $request->input('id'))->first();
            $timeline = [];

            $naskah = (object)collect($naskah)->map(function($v, $i) {
                if($i=='tanggal_masuk_naskah') {
                    return Carbon::createFromFormat('Y-m-d', $v)->format('d M Y');
                } else {
                    return $v;
                }
            })->all();
            

            return view('penerbitan.naskah.page.modal-timeline', [
                'naskah' => $naskah,
                'method' => $request->input('method_'),
                'timeline' => $timeline
            ]);

        } elseif($request->input('request_')=='submit') {
            $idTl = Str::uuid()->getHex();

            try {
                DB::table('timeline')->insert([
                    'id' => $idTl,
                    'naskah_id' => $request->input('add_tl_naskah_id'),
                    'tgl_naskah_masuk' => Carbon::createFromFormat('d M Y', 
                                            $request->input('add_naskah_masuk'))->format('Y-m-d'),
                    'tgl_mulai_penerbitan' => Carbon::createFromFormat('d M Y', 
                                            $request->input('add_proses_penerbitan'))->format('Y-m-d'),
                    'tgl_mulai_produksi' => Carbon::createFromFormat('d M Y', 
                                            $request->input('add_proses_produksi'))->format('Y-m-d'),
                    'tgl_buku_jadi' => Carbon::createFromFormat('d M Y', 
                                            $request->input('add_buku_jadi'))->format('Y-m-d'),
                    'ttl_hari_penerbitan' => $request->input('add_estimasi_penerbitan'),
                    'ttl_hari_produksi' => $request->input('add_estimasi_produksi'),
                    'created_by' => auth()->id()
                ]);

                DB::commit();
                return;
            }catch(\Exception $e) {
                return $e->getMessage();
            }
        
        } else {
            abort(404);
        }
    }

    protected function contentSubtimeline($request) 
    {
        $request->validate([
            'id' => 'required'
        ]);

        $timeline = DB::table('timeline')->where('id', $request->input('id'))
                        ->first();
        $timeline = (object)collect($timeline)->map(function($v, $i) {
            switch($i) {
                case 'tgl_naskah_masuk':
                    return Carbon::createFromFormat('Y-m-d H:i:s', $v)->format('d M Y');
                case 'tgl_mulai_penerbitan':
                    return Carbon::createFromFormat('Y-m-d H:i:s', $v)->format('d M Y');
                case 'tgl_mulai_produksi':
                    return Carbon::createFromFormat('Y-m-d H:i:s', $v)->format('d M Y');
                case 'tgl_buku_jadi':
                    return Carbon::createFromFormat('Y-m-d H:i:s', $v)->format('d M Y');
                default:
                    return $v;
            }
        })->all();
                  

        return view('penerbitan.naskah.page.content-subtimeline', [
            'timeline' => $timeline
        ]);
    }

    protected function updateSubTimeline($request)
    {
        return $request->all();
    }

}
