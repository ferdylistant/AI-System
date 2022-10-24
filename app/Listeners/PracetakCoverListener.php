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

            default:
                abort(500);
                break;
        }
    }
}
