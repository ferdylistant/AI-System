<?php

namespace App\Listeners;

use App\Events\UpdateImprintEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateImprintListener
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
     * @param  \App\Events\UpdateImprintEvent  $event
     * @return void
     */
    public function handle(UpdateImprintEvent $event)
    {
        $data = $event->data;
        $res = DB::table('imprint')
                    ->where('id', $data['id'])
                    ->update([
                    'nama' => $data['nama'],
                    'updated_by' => $data['updated_by']
                ]);
        return $res;
    }
}
