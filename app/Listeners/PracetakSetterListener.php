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
                    // 'proses_saat_ini' => $data['proses_saat_ini'],
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
            case 'Progress Proof Prodev':
                $res = DB::table('pracetak_setter')->where('id',$data['id'])->update([
                    'mulai_proof' => $data['mulai_proof'],
                    'proses' => $data['proses']
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
            case 'Edit Pracetak Setter':
                $res = DB::table('pracetak_setter')->where('id',$data['id'])->update([
                    'jml_hal_final' => $data['jml_hal_final'],
                    'catatan' => $data['catatan'],
                    'setter' => $data['setter'],
                    'korektor' => $data['korektor'],
                    'edisi_cetak' => $data['edisi_cetak'],
                    'isbn' => $data['isbn'],
                    'pengajuan_harga' => $data['pengajuan_harga'],
                    'mulai_p_copyright' => $data['mulai_p_copyright'],
                    'selesai_p_copyright' => $data['selesai_p_copyright'],
                    'proses_saat_ini' => $data['proses_saat_ini'],
                    'bulan' => $data['bulan']
                ]);
                break;
            case 'Insert History Edit Pracetak Setter':
                $res = DB::table('pracetak_setter_history')->insert([
                    'pracetak_setter_id' => $data['pracetak_setter_id'],
                    'type_history' => $data['type_history'],
                    'setter_his' => $data['setter_his'],
                    'setter_new' => $data['setter_new'],
                    'jml_hal_final_his' => $data['jml_hal_final_his'],
                    'jml_hal_final_new' => $data['jml_hal_final_new'],
                    'edisi_cetak_his' => $data['edisi_cetak_his'],
                    'edisi_cetak_new' => $data['edisi_cetak_new'],
                    'korektor_his' => $data['korektor_his'],
                    'korektor_new' => $data['korektor_new'],
                    'catatan_his' => $data['catatan_his'],
                    'catatan_new' => $data['catatan_new'],
                    'mulai_p_copyright' => $data['mulai_p_copyright'],
                    'selesai_p_copyright' => $data['selesai_p_copyright'],
                    'isbn_his' => $data['isbn_his'],
                    'isbn_new' => $data['isbn_new'],
                    'pengajuan_harga_his' => $data['pengajuan_harga_his'],
                    'pengajuan_harga_new' => $data['pengajuan_harga_new'],
                    'bulan_his' => $data['bulan_his'],
                    'bulan_new' => $data['bulan_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Proses Setting Selesai':
                $res = DB::table('pracetak_setter_selesai')->insert([
                    'type' => $data['type'],
                    'pracetak_setter_id' => $data['pracetak_setter_id'],
                    'users_id' => $data['users_id'],
                    'tgl_proses_selesai' => $data['tgl_proses_selesai']
                ]);
                break;
            case 'Setting Selesai':
                $res = DB::table('pracetak_setter')->where('id',$data['id'])->update([
                    'selesai_setting' => $data['selesai_setting'],
                    'proses' => $data['proses'],
                    'proses_saat_ini' => $data['proses_saat_ini']
                ]);
                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
