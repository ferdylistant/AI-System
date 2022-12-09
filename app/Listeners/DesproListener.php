<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Events\DesproEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DesproListener
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
     * @param  \App\Events\DesproEvent  $event
     * @return void
     */
    public function handle(DesproEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Create Despro':
                $res = DB::table('deskripsi_produk')->insert([
                    'id' => $data['id'],
                    'naskah_id' => $data['naskah_id'],
                    'status' => $data['status']
                ]);
                break;
            case 'Edit Despro':
                $res = DB::table('deskripsi_produk')
                    ->where('id', $data['id'])
                    ->update([
                    // 'judul_final' => $data['judul_final'],
                    'alt_judul' => $data['alt_judul'],
                    'format_buku' => $data['format_buku'],
                    'jml_hal_perkiraan' => $data['jml_hal_perkiraan'],
                    'imprint' => $data['imprint'],
                    'kelengkapan' => $data['kelengkapan'],
                    'editor' => $data['editor'],
                    'catatan' => $data['catatan'],
                    'bulan' => $data['bulan'],
                    'updated_by'=> $data['updated_by']
                ]);
                break;
            case 'Insert History Despro':
                $res = DB::table('deskripsi_produk_history')->insert([
                    'deskripsi_produk_id' => $data['deskripsi_produk_id'],
                    'type_history' => $data['type_history'],
                    // 'judul_final_his' => $data['judul_final_his'],
                    // 'judul_final_new' => $data['judul_final_new'],
                    'alt_judul_his' => $data['alt_judul_his'],
                    'alt_judul_new' => $data['alt_judul_new'],
                    'format_buku_his' => $data['format_buku_his'],
                    'format_buku_new' => $data['format_buku_new'],
                    'jml_hal_his' => $data['jml_hal_his'],
                    'jml_hal_new' => $data['jml_hal_new'],
                    'imprint_his' => $data['imprint_his'],
                    'imprint_new' => $data['imprint_new'],
                    'editor_his' => $data['editor_his'],
                    'editor_new' => $data['editor_new'],
                    'kelengkapan_his' => $data['kelengkapan_his'],
                    'kelengkapan_new' => $data['kelengkapan_new'],
                    'catatan_his' => $data['catatan_his'],
                    'catatan_new' => $data['catatan_new'],
                    'bulan_his' => $data['bulan_his'],
                    'bulan_new' => $data['bulan_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Update Status Despro':
                $res = DB::table('deskripsi_produk')->where('id',$data['id'])->whereNull('deleted_at')->update([
                    'status' => $data['status'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Insert History Status Despro':
                $res = DB::table('deskripsi_produk_history')->insert([
                    'deskripsi_produk_id' => $data['deskripsi_produk_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Revisi Despro':
                $res = DB::table('deskripsi_produk_history')->insert([
                    'deskripsi_produk_id' => $data['deskripsi_produk_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'deadline_revisi_his' => $data['deadline_revisi_his'],
                    'alasan_revisi_his' => $data['alasan_revisi_his'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Pilih Judul':
                 DB::table('deskripsi_produk')->where('id',$data['id'])->update([
                    'judul_final' => $data['judul']
                ]);
                $res = DB::table('deskripsi_produk_history')->insert([
                    'deskripsi_produk_id' => $data['id'],
                    'type_history' => 'Update',
                    'judul_final_new' => $data['judul'],
                    'author_id' => auth()->id(),
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ]);
                break;
            case 'Approval Despro':
                $res = DB::table('deskripsi_produk')->where('id',$data['id'])->update([
                    'status' => $data['status'],
                    'action_gm' => $data['action_gm']
                ]);
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
