<?php

namespace App\Listeners;

use App\Events\UpdateRevisiDesproEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateRevisiDesproListener
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
     * @param  \App\Events\UpdateRevisiDesproEvent  $event
     * @return void
     */
    public function handle(UpdateRevisiDesproEvent $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_produk')->where('id',$data->id)->update([
            'status' => $data->status,
            'deadline_revisi' => $data->deadline_revisi,
            'alasan_revisi' => $data->alasan_revisi,
            'action_gm' => $data->action_gm,
            'updated_by' => $data->updated_by
        ]);
        return $res;
    }
}
