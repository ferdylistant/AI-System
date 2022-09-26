<?php

namespace App\Listeners;

use App\Events\UpdateStatusEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateStatusListener
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
     * @param  \App\Events\UpdateStatusEvent  $event
     * @return void
     */
    public function handle(UpdateStatusEvent $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_produk')->where('id',$data['id'])->whereNull('deleted_at')->update([
            'status' => $data['status'],
            'updated_by' => $data['updated_by']
        ]);
        return $res;
    }
}
