<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use App\Events\PracetakSetterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PracetakSetterListener
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
     * @param  \App\Events\PracetakSetterEvent  $event
     * @return void
     */
    public function handle(PracetakSetterEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Insert Pracetak Setter':
                $res = DB::table('pracetak_setter')->insert([
                    'id' => $data['id'],
                    'deskripsi_final_id' => $data['deskripsi_final_id'],
                    'setter' => $data['setter'],
                    'korektor' => $data['korektor'],
                    'jml_hal_final' => $data['jml_hal_final'],
                    'tgl_masuk_pracetak' => $data['tgl_masuk_pracetak']
                ]);
                break;
            case 'Update Status Pracetak Setter':
                $res = DB::table('pracetak_setter')->where('id', $data['id'])->update([
                    'proses_saat_ini' => $data['proses_saat_ini'],
                    'turun_cetak' => $data['turun_cetak'],
                    'status' => $data['status']
                ]);
                break;
            case 'Insert History Status Pracetak Setter':
                $res = DB::table('pracetak_setter_history')->insert([
                    'pracetak_setter_id' => $data['pracetak_setter_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Progress Setter':
                $res = DB::table('pracetak_setter')->where('id', $data['id'])->update([
                    'mulai_setting' => $data['mulai_setting'],
                    'proses_saat_ini' => $data['proses_saat_ini'],
                    'proses' => $data['proses'],
                ]);
                DB::table('pracetak_setter_history')->insert([
                    'pracetak_setter_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'progress' => $data['proses'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Progress Korektor':
                $res =  DB::table('pracetak_setter')->where('id', $data['id'])->update([
                    'mulai_koreksi' => $data['mulai_koreksi'],
                    'proses_saat_ini' => $data['proses_saat_ini'],
                    'proses' => $data['proses'],
                ]);
                DB::table('editing_proses_history')->insert([
                    'editing_proses_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'progress' => $data['proses'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
