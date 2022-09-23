<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use App\Events\UpdatePlatformEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePlatformListener
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
     * @param  \App\Events\UpdatePlatformEvent  $event
     * @return void
     */
    public function handle(UpdatePlatformEvent $event)
    {
        $data = $event->data;
        $res = DB::table('platform_digital_ebook')
                    ->where('id', $data['id'])
                    ->update([
                    'nama' => $data['nama'],
                    'updated_by' => $data['updated_by']
                ]);
        return $res;
    }
}
