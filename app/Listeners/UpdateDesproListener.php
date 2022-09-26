<?php

namespace App\Listeners;

use App\Events\UpdateDesproEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateDesproListener
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
     * @param  \App\Events\UpdateDesproEvent  $event
     * @return void
     */
    public function handle(UpdateDesproEvent $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_produk')
            ->where('id', $data['id'])
            ->update([
            'judul_final' => $data['judul_final'],
            'alt_judul' => $data['alt_judul'],
            'format_buku' => $data['format_buku'],
            'jml_hal_perkiraan' => $data['jml_hal_perkiraan'],
            'imprint' => $data['imprint'],
            'kelengkapan' => $data['kelengkapan'],
            'editor' => $data['editor'],
            'catatan' => $data['catatan'],
            'bulan' => $data['bulan'],
            'status' => $data['status'],
            'updated_by'=> $data['updated_by']
        ]);
        return $res;
    }
}
