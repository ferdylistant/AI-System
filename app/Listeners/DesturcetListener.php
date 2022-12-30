<?php

namespace App\Listeners;

use App\Events\DesturcetEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DesturcetListener
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
     * @param  \App\Events\DesturcetEvent  $event
     * @return void
     */
    public function handle(DesturcetEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Insert Turun Cetak':
                DB::beginTransaction();
                $res = DB::table('deskripsi_turun_cetak')->insert([
                    'id' => $data['id'],
                    'pracetak_cover_id' => $data['pracetak_cover_id'],
                    'pracetak_setter_id' => $data['pracetak_setter_id'],
                    'tgl_masuk' => $data['tgl_masuk']
                ]);
                DB::commit();
                break;
            default:
            abort(500);
                break;
        }
        return $res;
    }
}
