<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Gate, DB};
use Carbon\Carbon;

class NotificationController extends Controller {
    public function index(Request $request) {
        $html = '';

        if(Gate::allows('do_update', 'naskah-pn-prodev')) {
            $notif = DB::table('notif as n')
                        ->join('notif_detail as nd', function($j) {
                            $j->on('n.id', '=', 'nd.notif_id')
                                ->where('nd.user_id', auth()->id())
                                ->where('nd.seen', '0');
                        })
                        ->join('penerbitan_naskah as pn', function($j) {
                            $j->on('n.form_id', '=', 'pn.id')
                                ->where('pn.pic_prodev', auth()->id())
                                ->whereNull('deleted_at');
                        })
                        ->where('n.permission_id', 'ebca07da8aad42c4aee304e3a6b81001')
                        ->whereNull('n.expired')
                        ->select(DB::raw('nd.created_at as tgl_notif, pn.id, pn.judul_asli'))
                        ->get();

            if(!$notif->isEmpty()) {
                foreach($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$n->id).'" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>'.$n->judul_asli.'</i></strong> perlu dinilai (<strong>Prodev</strong>).
                                        <div class="time text-primary">'.$craetedAt.'</div>
                                    </div>
                                </a>';
                } 
            }
        }

        if(Gate::allows('do_update', 'naskah-pn-editor')) {
            $notifPenilaian = DB::table('notif')
                            ->where('type', 'Penilaian Naskah')->whereNull('expired')
                            ->where('permission_id', '5d793b19c75046b9a4d75d067e8e33b2')->orderBy('form_id', 'desc')
                            ->select('permission_id', 'form_id', 'created_at as ndate')->get();
            
            $idNaskah = [];
            $notifPenilaian = (object)collect($notifPenilaian)->map(function($item, $key) use(&$idNaskah) {
                                    $idNaskah[$key] = $item->form_id;
                                    return $item;
                                })->all();

            if(!is_null($notifPenilaian)) {
                $naskah = DB::table('penerbitan_naskah')->whereIn('id', $idNaskah)->whereNull('deleted_at')
                            ->orderBy('id', 'desc')->select('id', 'judul_asli')->get();
                foreach($naskah as $key => $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $notifPenilaian->{$key}->ndate, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$n->id).'" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>'.$n->judul_asli.'</i></strong> perlu dinilai (<strong>Editor</strong>).
                                        <div class="time text-primary">'.$craetedAt.'</div>
                                    </div>
                                </a>';
                } 
            }
        }

        if(Gate::allows('do_update', 'naskah-pn-setter')) {
            $notifPenilaian = DB::table('notif')
                            ->where('type', 'Penilaian Naskah')->whereNull('expired')
                            ->where('permission_id', '33c3711d787d416082c0519356547b0c')->orderBy('form_id', 'desc')
                            ->select('permission_id', 'form_id', 'created_at as ndate')->get();
            
            $idNaskah = [];
            $notifPenilaian = (object)collect($notifPenilaian)->map(function($item, $key) use(&$idNaskah) {
                                    $idNaskah[$key] = $item->form_id;
                                    return $item;
                                })->all();

            if(!is_null($notifPenilaian)) {
                $naskah = DB::table('penerbitan_naskah')->whereIn('id', $idNaskah)->whereNull('deleted_at')
                            ->orderBy('id', 'desc')->select('id', 'judul_asli')->get();
                foreach($naskah as $key => $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $notifPenilaian->{$key}->ndate, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$n->id).'" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>'.$n->judul_asli.'</i></strong> perlu dinilai (<strong>Setter</strong>).
                                        <div class="time text-primary">'.$craetedAt.'</div>
                                    </div>
                                </a>';
                } 
            }
        }

        if(Gate::allows('do_update', 'naskah-pn-mpemasaran')) {
            $notif = DB::table('notif as n')
                        ->join('penerbitan_naskah as pn', function($j) {
                            $j->on('n.form_id', '=', 'pn.id')
                                ->whereNull('pn.deleted_at');
                        })
                        ->where('n.permission_id', 'a213b689b8274f4dbe19b3fb24d66840')
                        ->whereNull('n.expired')
                        ->select(DB::raw('n.created_at as tgl_notif, pn.id, pn.judul_asli'))
                        ->get();

            if(!$notif->isEmpty()) {
                foreach($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$n->id).'" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>'.$n->judul_asli.'</i></strong> perlu dinilai (<strong>M.Pemasaran</strong>).
                                        <div class="time text-primary">'.$craetedAt.'</div>
                                    </div>
                                </a>';
                } 
            }
        }
        
        if(Gate::allows('do_update', 'naskah-pn-mpenerbitan')) {
            $notif = DB::table('notif as n')
                        ->join('penerbitan_naskah as pn', function($j) {
                            $j->on('n.form_id', '=', 'pn.id')
                                ->whereNull('pn.deleted_at');
                        })
                        ->join('penerbitan_pn_stts as pns', function($j) {
                            $j->on('pn.id', '=', 'pns.naskah_id');
                        })
                        ->where('n.permission_id', '12b852d92d284ab5a654c26e8856fffd')
                        ->whereNull('n.expired')
                        ->select(DB::raw('pns.tgl_pn_prodev as tgl_notif, pn.id, pn.judul_asli'))
                        ->get();
                        
            if(!$notif->isEmpty()) {
                foreach($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$n->id).'" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>'.$n->judul_asli.'</i></strong> perlu dinilai (<strong>M.Penerbitan</strong>).
                                        <div class="time text-primary">'.$craetedAt.'</div>
                                    </div>
                                </a>';
                } 
            }
        }

        if(Gate::allows('do_update', 'naskah-pn-direksi')) {
            $notifPenilaian = DB::table('notif')
                            ->where('type', 'Penilaian Naskah')->whereNull('expired')
                            ->where('permission_id', '8791f143a90e42e2a4d1d0d6b1254bad')->orderBy('form_id', 'desc')
                            ->select('permission_id', 'form_id', 'created_at as ndate')->get();
            
            $idNaskah = [];
            $notifPenilaian = (object)collect($notifPenilaian)->map(function($item, $key) use(&$idNaskah) {
                                    $idNaskah[$key] = $item->form_id;
                                    return $item;
                                })->all();

            if(!is_null($notifPenilaian)) {
                $naskah = DB::table('penerbitan_naskah')->whereIn('id', $idNaskah)->whereNull('deleted_at')
                            ->orderBy('id', 'desc')->select('id', 'judul_asli')->get();
                foreach($naskah as $key => $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $notifPenilaian->{$key}->ndate, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$n->id).'" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>'.$n->judul_asli.'</i></strong> perlu dinilai (<strong>Direksi</strong>).
                                        <div class="time text-primary">'.$craetedAt.'</div>
                                    </div>
                                </a>';
                } 
            }
        }

        // if(Gate::allows('do_update', 'timeline-naskah-update-date')) {
        //     $notifTimeline = DB::table('notif as n')
        //                         ->leftJoin('notif_detail as nd', 'n.id', '=', 'nd.notif_id')
        //                         ->whereNull('n.expired')->where('n.permission_id', '8d9b1da4234f46eb858e1ea490da6348') // Date Timeline
        //                         ->where('nd.user_id', auth()->id())->where('nd.seen', '0')->orderBy('n.form_id', 'desc')
        //                         ->select('n.permission_id', 'n.form_id', 'n.created_at as ndate', 
        //                                 'nd.user_id', 'nd.created_at as nddate')->get();
            
        //     $idNaskah = [];
        //     $notifTimeline = (object)$notifTimeline->map(function($item, $key) use(&$idNaskah) {
        //                             $idNaskah[$key] = $item->form_id;
        //                             return $item;
        //                         })->all();
            
        //     if(!is_null($notifTimeline)) {
        //         $naskah = DB::table('penerbitan_naskah as pn')->join('timeline as t', 'pn.id', '=', 't.naskah_id')
        //                     ->whereIn('pn.id', $idNaskah)->whereNull('pn.deleted_at')
        //                     ->orderBy('t.created_at', 'desc')->select('pn.id', 'pn.judul_asli')->get();
        //         foreach($naskah as $key => $n) {
        //             $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $notifTimeline->{$key}->nddate, 'Asia/Jakarta')->diffForHumans();
        //             $html .=    '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$n->id).'" class="dropdown-item dropdown-item-unread">
        //                             <div class="dropdown-item-icon bg-primary text-white">
        //                                 <i class="fas fa-calendar-alt"></i>
        //                             </div>
        //                             <div class="dropdown-item-desc">
        //                                 Naskah <strong><i>'.$n->judul_asli.'</i></strong> perlu dibuat timeline.
        //                                 <div class="time text-primary">'.$craetedAt.'</div>
        //                             </div>
        //                         </a>';
        //         } 
        //     }
        // }

        return $html;
    }
}
