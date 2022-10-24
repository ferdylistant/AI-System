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

            default:
                abort(500);
                break;
        }
        return $res;
    }
}
