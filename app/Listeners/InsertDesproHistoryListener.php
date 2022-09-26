<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use App\Events\InsertDesproHistory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertDesproHistoryListener
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
     * @param  \App\Events\InsertDesproHistory  $event
     * @return void
     */
    public function handle(InsertDesproHistory $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_produk_history')->insert([
            'deskripsi_produk_id' => $data['deskripsi_produk_id'],
            'type_history' => $data['type_history'],
            'judul_final_his' => $data['judul_final_his'],
            'judul_final_new' => $data['judul_final_new'],
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
        return $res;
    }
}
