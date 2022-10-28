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
                    'korektor_komp' => $data['korektor_komp'],
                    'korektor_manual' => $data['korektor_manual'],
                    'jml_hal_final' => $data['jml_hal_final'],
                    'tgl_masuk_pracetak' => $data['tgl_masuk_pracetak']
                ]);
                break;

            default:
                abort(500);
                break;
        }
    }
}
