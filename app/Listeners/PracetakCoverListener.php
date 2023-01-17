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
            case 'Progress Proof Prodev':
                DB::beginTransaction();
                $res = DB::table('pracetak_cover')->where('id', $data['id'])->update([
                    'mulai_proof' => $data['mulai_proof'],
                    'proses' => $data['proses']
                ]);
                DB::table('pracetak_cover_history')->insert([
                    'pracetak_cover_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'progress' => $data['proses'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Progress Designer-Korektor':
                DB::beginTransaction();
                $label = 'mulai_' . $data['label'];
                $res = DB::table('pracetak_cover')->where('id', $data['id'])->update([
                    $label => $data[$label],
                    'proses_saat_ini' => $data['proses_saat_ini'],
                    'proses' => $data['proses'],
                ]);
                DB::table('pracetak_cover_history')->insert([
                    'pracetak_cover_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'progress' => $data['proses'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Proses Desain-Koreksi Selesai':
                DB::beginTransaction();
                $res = DB::table('pracetak_cover_selesai')->insert([
                    'type' => $data['type'],
                    'section' => $data['section'],
                    'tahap' => $data['tahap'],
                    'pracetak_cover_id' => $data['pracetak_cover_id'],
                    'users_id' => $data['users_id'],
                    'tgl_proses_selesai' => $data['tgl_proses_selesai']
                ]);
                DB::commit();
                break;
            case 'Desain-Koreksi Selesai':
                DB::beginTransaction();
                $label = 'selesai_' . $data['label'];
                $res = DB::table('pracetak_cover')->where('id', $data['id'])->update([
                    $label => $data[$label],
                    'proses' => $data['proses'],
                    'proses_saat_ini' => $data['proses_saat_ini']
                ]);
                DB::table('pracetak_cover_history')->insert([
                    'type_history' => $data['type_history'],
                    'pracetak_cover_id' => $data['id'],
                    $label => $data[$label],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data[$label]
                ]);
                DB::commit();
                break;
            case 'Act Prodev':
                DB::beginTransaction();
                $res = DB::table('pracetak_cover')->where('id', $data['pracetak_cover_id'])->update([
                    'selesai_proof' => $data['selesai_proof'],
                    'proses_saat_ini' => $data['proses_saat_ini'],
                    'proses' => $data['proses'],
                    'status' => $data['status']
                ]);
                DB::table('pracetak_cover_proof')->insert([
                    'type_user' => $data['type_user'],
                    'type_action' => $data['type_action'],
                    'pracetak_cover_id' => $data['pracetak_cover_id'],
                    'users_id' => $data['users_id'],
                    'ket_revisi' => $data['ket_revisi'],
                    'tgl_action' => $data['tgl_action'],
                ]);
                DB::table('pracetak_cover_history')
                    ->insert([
                        'type_history' => $data['type_history'],
                        'pracetak_cover_id' => $data['pracetak_cover_id'],
                        'ket_revisi' => $data['ket_revisi'],
                        'selesai_proof' => $data['selesai_proof'],
                        'status_his' => $data['status_his'],
                        'status_new' => $data['status'],
                        'author_id' => $data['users_id'],
                        'modified_at' => $data['tgl_action']
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
