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
            case 'show':
                return $this->getTimeline($request);
            case 'content-subtimeline':
                return $this->contentSubtimeline($request);
            case 'update-subtimeline':
                return $this->updateSubTimeline($request);
            default:
                abort(404);
        }
    }
    protected function getTimeline($request) {
        $html = '';
        $id = $request->id;
        $data = DB::table('timeline as t')
        ->join('penerbitan_naskah as pn','t.naskah_id','=','pn.id')
        ->join('users as u','pn.pic_prodev','=','u.id')
        ->where('t.naskah_id',$id)
        ->orderBy('t.tgl_mulai','ASC')
        ->select(
            't.*',
            'pn.kode',
            'pn.judul_asli',
            'pn.jalur_buku',
            'u.nama'
        )->get();
        $html .='<div class="col-12 horizontal-timeline" id="timelineNaskah">
        <div class="events-content">
           <ol>';
        foreach ($data as $key => $val) {
                $select = $key == 0 ? "class='selected'":"";
                $json = [
                    "date" => date('Y-m-d H:i:s',strtotime($val->tgl_mulai)),
                    "customDisplay" => $val->progress
                ];
                switch($val->status){
                    case 'Pending':
                        $lbl = 'danger';
                        break;
                    case 'Antrian':
                        $lbl = 'warning';
                        break;
                    case 'Proses':
                        $lbl = 'success';
                        break;
                    case 'Selesai':
                        $lbl = 'dark';
                        break;
                    case 'Revisi':
                        $lbl = 'info';
                        break;
                    case 'Acc':
                        $lbl = 'secondary';
                        break;
                }
                  $html .= "<li ".$select." data-horizontal-timeline='".json_encode($json)."'>
                     <h5 class='text-dark'>".$val->kode."_".$val->judul_asli."</h5>
                     <span class='badge badge-".$lbl."'>".$val->status."</span>
                     <small class='d-block'>Naskah: ".$val->jalur_buku."</small>
                     <small class='d-block'>PIC Prodev: ".$val->nama."</small>
                     <small  class='d-block'>Tanggal mulai: ".Carbon::parse($val->tgl_mulai)->translatedFormat('l d F Y, H:i')."</small>";
                     if (!is_null($val->tgl_selesai)) {
                        $html .= "<small  class='d-block'>Tanggal selesai: ".Carbon::parse($val->tgl_selesai)->translatedFormat('l d F Y, H:i')."</small>";
                     }
                   $html .= "<a href='".urldecode($val->url_action)."' class='btn btn-primary d-block mt-3'><i class='fas fa-eye'></i>&nbsp;Lihat Detail</a>
                  </li>";
                }
        $html .= "</ol>
             </div>
         </div>";
        //  return $html;
        return response()->json(
            ['status' => $data->isEmpty() ? 'error' : 'success',
            'data' => $html]
        );
    }
}
