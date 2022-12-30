<?php

namespace App\Listeners;

use App\Events\PracetakCoverEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PracetakCoverListener
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
     * @param  \App\Events\PracetakCoverEvent  $event
     * @return void
     */
    public function handle(PracetakCoverEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Insert Pracetak Cover':
                $res = DB::table('pracetak_cover')->insert([
                    'id' => $data['id'],
                    'deskripsi_cover_id' => $data['deskripsi_cover_id'],
                    'desainer' => $data['desainer'],
                    'editor' => $data['editor'],
                    'tgl_masuk_cover' => $data['tgl_masuk_cover']
                ]);
                break;
            case 'Update Status Pracetak Desainer':
                DB::beginTransaction();
                $res = DB::table('pracetak_cover')->where('id', $data['id'])->update([
                    // 'proses_saat_ini' => $data['proses_saat_ini'],
                    'turun_cetak' => $data['turun_cetak'],
                    'status' => $data['status']
                ]);
                DB::commit();
                break;
            case 'Insert History Status Pracetak Desainer':
                DB::beginTransaction();
                $res = DB::table('pracetak_cover_history')->insert([
                    'pracetak_cover_id' => $data['pracetak_cover_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Edit Pracetak Desainer':
                DB::beginTransaction();
                $res = DB::table('pracetak_cover')->where('id', $data['id'])->update([
                    'catatan' => $data['catatan'],
                    'desainer' => $data['desainer'],
                    'korektor' => $data['korektor'],
                    'proses_saat_ini' => $data['proses_saat_ini'],
                    'bulan' => $data['bulan']
                ]);
                DB::commit();
                break;
            case 'Insert History Edit Pracetak Desainer':
                DB::beginTransaction();
                $res = DB::table('pracetak_cover_history')->insert([
                    'pracetak_cover_id' => $data['pracetak_cover_id'],
                    'type_history' => $data['type_history'],
                    'desainer_his' => $data['desainer_his'],
                    'desainer_new' => $data['desainer_new'],
                    'korektor_his' => $data['korektor_his'],
                    'korektor_new' => $data['korektor_new'],
                    'catatan_his' => $data['catatan_his'],
                    'catatan_new' => $data['catatan_new'],
                    'bulan_his' => $data['bulan_his'],
                    'bulan_new' => $data['bulan_new'],
                    'proses_ini_his' => $data['proses_ini_his'],
                    'proses_ini_new' => $data['proses_ini_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
