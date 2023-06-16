<?php

namespace App\Listeners;

use App\Events\TrackerEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrackerListener
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
     * @param  \App\Events\TrackerEvent  $event
     * @return void
     */
    public function handle(TrackerEvent $event)
    {
        $data = $event->data;
        DB::beginTransaction();
        $res = DB::table('tracker')->insert([
            'id' => $data['id'],
            'section_id' => $data['section_id'],
            'section_name' => $data['section_name'],
            'description' => $data['description'],
            'icon' => $data['icon'],
            'created_by' => $data['created_by']
        ]);
        DB::commit();
        return $res;
    }
}
