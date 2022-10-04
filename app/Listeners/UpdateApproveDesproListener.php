<?php

namespace App\Listeners;

use App\Events\UpdateApproveDesproEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateApproveDesproListener
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
     * @param  \App\Events\UpdateApproveDesproEvent  $event
     * @return void
     */
    public function handle(UpdateApproveDesproEvent $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_produk')->where('id',$data['id'])->update([
            'status' => $data['status'],
            'action_gm' => $data['action_gm']
        ]);
        return $res;
    }
}
