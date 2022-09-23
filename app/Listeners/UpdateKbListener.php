<?php

namespace App\Listeners;

use App\Events\UpdateKbEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateKbListener
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
     * @param  \App\Events\UpdateKbEvent  $event
     * @return void
     */
    public function handle(UpdateKbEvent $event)
    {
        $data = $event->data;
        $res = DB::table('penerbitan_m_kelompok_buku')
                    ->where('id', $data['id'])
                    ->update([
                    'nama' => $data['nama'],
                    'updated_by' => $data['updated_by']
                ]);
        return $res;
    }
}
