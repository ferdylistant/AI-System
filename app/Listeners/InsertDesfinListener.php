<?php

namespace App\Listeners;

use App\Events\InsertDesfinEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertDesfinListener
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
     * @param  \App\Events\InsertDesfinEvent  $event
     * @return void
     */
    public function handle(InsertDesfinEvent $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_final')->insert([
            'id' => $data['id'],
            'deskripsi_produk_id' => $data['deskripsi_produk_id'],
            'tgl_deskripsi' => $data['tgl_deskripsi']
        ]);
        return $res;
    }
}
