<?php

namespace App\Listeners;

use App\Events\SessionExpired;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateStatusToOffline
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
     * @param  \App\Events\SessionExpired  $event
     * @return void
     */
    public function handle(SessionExpired $event)
    {
        $id = $event->userId;
        $res = DB::table('users')->where('id',$id)->update([
            'status_activity' => 'offline'
        ]);
        return $res;
    }
}
