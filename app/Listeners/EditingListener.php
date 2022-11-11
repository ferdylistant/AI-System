<?php

namespace App\Listeners;

use App\Events\EditingEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EditingListener
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
     * @param  \App\Events\EditingEvent  $event
     * @return void
     */
    public function handle(EditingEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Insert Editing':
                $res = DB::table('editing_proses')->insert([
                    'id' => $data['id'],
                    'deskripsi_final_id' => $data['deskripsi_final_id'],
                    'editor' => $data['editor'],
                    'tgl_masuk_editing' => $data['tgl_masuk_editing']
                ]);
                break;
            case 'Update Status Editing':
                $res = DB::table('editing_proses')->where('id', $data['id'])->whereNull('deleted_at')->update([
                    'status' => $data['status']
                ]);
                break;
            case 'Insert History Status Editing':
                $res = DB::table('editing_proses_history')->insert([
                    'deskripsi_final_id' => $data['deskripsi_final_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Edit Editing':
                $res = DB::table('editing_proses as ep')
                    ->join('deskripsi_final as df', 'df.id', '=', 'ep.deskripsi_final_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->where('ep.id', $data['id'])
                    ->update([
                        'dp.jml_hal_perkiraan' => $data['jml_hal_perkiraan'],
                        'df.bullet' => $data['bullet'],
                        'ep.catatan' => $data['catatan'],
                        'ep.editor' => $data['editor'],
                        'ep.copy_editor' => $data['copy_editor'],
                        'ep.bulan' => $data['bulan']
                    ]);
                break;
            case 'Insert History Edit Editing':
                $res = DB::table('editing_proses_history')->insert([
                    'editing_proses_id' => $data['editing_proses_id'],
                    'type_history' => $data['type_history'],
                    'editor_his' => $data['editor_his'],
                    'editor_new' => $data['editor_new'],
                    'jml_hal_perkiraan_his' => $data['jml_hal_perkiraan_his'],
                    'jml_hal_perkiraan_new' => $data['jml_hal_perkiraan_new'],
                    'bullet_his' => $data['bullet_his'],
                    'bullet_new' => $data['bullet_new'],
                    'copy_editor_his' => $data['copy_editor_his'],
                    'copy_editor_new' => $data['copy_editor_new'],
                    'catatan_his' => $data['catatan_his'],
                    'catatan_new' => $data['catatan_new'],
                    'bulan_his' => $data['bulan_his'],
                    'bulan_new' => $data['bulan_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Progress Editor':
                $res = DB::table('editing_proses')->where('id',$data['id'])->update([
                    'tgl_mulai_edit' => $data['tgl_mulai_edit'],
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
            case 'Progress Copy Editor':
                $res =  DB::table('editing_proses')->where('id',$data['id'])->update([
                    'tgl_mulai_copyeditor' => $data['tgl_mulai_copyeditor'],
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
                return abort(500);
                break;
        }
        return $res;
    }
}
