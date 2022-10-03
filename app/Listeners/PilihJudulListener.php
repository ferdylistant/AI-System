<?php

namespace App\Listeners;

use App\Events\PilihJudulEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class PilihJudulListener
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
     * @param  \App\Events\PilihJudulEvent  $event
     * @return void
     */
    public function handle(PilihJudulEvent $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_produk')->where('id',$data['id'])->update([
            'judul_final' => $data['judul']
        ]);
        return $res;
    }
}
