<?php

namespace App\Listeners;

use App\Events\DescovEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DescovListener
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
     * @param  \App\Events\DescovEvent  $event
     * @return void
     */
    public function handle(DescovEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Input Deskripsi':
                $res = DB::table('deskripsi_cover')->insert([
                    'id' => $data['id'],
                    'deskripsi_produk_id' => $data['deskripsi_produk_id'],
                    'tgl_deskripsi' => $data['tgl_deskripsi']
                ]);
                break;

            default:
                abort(500);
                break;
        }
        return $res;
    }
}
