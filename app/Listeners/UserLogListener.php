<?php

namespace App\Listeners;

use App\Events\UserLogEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UserLogListener
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
     * @param  \App\Events\UserLogEvent  $event
     * @return void
     */
    public function handle(UserLogEvent $event)
    {
        $data = $event->data;
        $res = DB::table('user_log')->insert([
            'users_id' => $data['users_id'],
            'ip_address' => $data['ip_address'],
            'last_login' => $data['last_login'],
            'user_agent' => $data['user_agent']
        ]);
        return $res;
    }
}
