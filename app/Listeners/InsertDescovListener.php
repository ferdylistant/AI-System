<?php

namespace App\Listeners;

use App\Events\InsertDescovEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class InsertDescovListener
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
     * @param  \App\Events\InsertDescovEvent  $event
     * @return void
     */
    public function handle(InsertDescovEvent $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_cover')->insert([
            'id' => $data['id'],
            'deskripsi_produk_id' => $data['deskripsi_produk_id'],
            'tgl_deskripsi' => $data['tgl_deskripsi']
        ]);
        return $res;
    }
}
