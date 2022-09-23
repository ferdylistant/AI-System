<?php

namespace App\Listeners;

use App\Events\UpdateFbEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateFbListener
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
     * @param  \App\Events\UpdateFbEvent  $event
     * @return void
     */
    public function handle(UpdateFbEvent $event)
    {
        $data = $event->data;
        $res = DB::table('format_buku')
                    ->where('id', $data['id'])
                    ->update([
                    'jenis_format' => $data['jenis_format'],
                    'updated_by' => $data['updated_by']
                ]);
        return $res;
    }
}
