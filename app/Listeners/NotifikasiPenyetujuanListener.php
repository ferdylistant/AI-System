<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Events\NotifikasiPenyetujuan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifikasiPenyetujuanListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NotifikasiPenyetujuanListener  $event
     * @return void
     */
    public function handle(NotifikasiPenyetujuan $event)
    {
        $data = $event->data;
        if($data['status_cetak']=='1' OR $data['status_cetak']=='2') {
            $dataLoop = DB::table('user_permission as up')
                ->join('users as u', 'u.id', 'up.user_id')
                ->join('jabatan as j', 'j.id', 'u.jabatan_id')
                ->where('up.permission_id', '=', '09179170e6e643eca66b282e2ffae1f8')
                ->whereNotIn('j.nama', ['Manajer Stok','Direktur Keuangan', 'Direktur Utama'])
                ->get();
            if($data['type']=='create-notif') {
                $id_notif = Str::uuid()->getHex();
                $sC = $data['status_cetak']=='1'?'Persetujuan Order Buku Baru':'Persetujuan Order Cetak Ulang Revisi';
                DB::table('notif')->insert([
                    [   'id' => $id_notif,
                        'section' => 'Penerbitan',
                        'type' => $sC,
                        'permission_id' => '09179170e6e643eca66b282e2ffae1f8',
                        'form_id' => $data['form_id'],
                    ],
                ]);
                foreach($dataLoop as $d) {
                    DB::table('notif_detail')->insert([
                        [   'notif_id' => $id_notif,
                            'user_id' => $d->user_id,
                            'raw_data' => 'Penyetujuan Cetak',
                        ],
                    ]);
                }
                return;
            } elseif($data['type']=='update-notif') {
                if($data['nama_notif'] == 'Approval Penerbitan'){
                    $notif = DB::table('notif')->whereNull('expired')->where('permission_id', '09179170e6e643eca66b282e2ffae1f8')
                            ->where('form_id', $data['form_id'])->first();
                    DB::table('notif_detail')->where('notif_id', $notif->id)
                                ->where('user_id', $data['user_id'])->delete();
                }
                return;
            }

        } elseif($data['status_cetak'] == '3') {
            $dataLoop = DB::table('user_permission as up')
                ->join('users as u', 'u.id', 'up.user_id')
                ->join('jabatan as j', 'j.id', 'u.jabatan_id')
                ->where('up.permission_id', '=', '09179170e6e643eca66b282e2ffae1f8')
                ->whereIn('j.nama', ['Manajer Stok'])
                ->get();
            if($data['type']=='create-notif') {
                $id_notif = Str::uuid()->getHex();
                $sC = 'Persetujuan Order Cetak Ulang';
                DB::table('notif')->insert([
                    [   'id' => $id_notif,
                        'section' => 'Penerbitan',
                        'type' => $sC,
                        'permission_id' => '09179170e6e643eca66b282e2ffae1f8',
                        'form_id' => $data['form_id'],
                    ],
                ]);
                foreach($dataLoop as $d) {
                    DB::table('notif_detail')->insert([
                        [   'notif_id' => $id_notif,
                            'user_id' => $d->user_id,
                            'raw_data' => 'Penyetujuan Cetak',
                        ],
                    ]);
                }
                return;
            }

        }

    }
}
