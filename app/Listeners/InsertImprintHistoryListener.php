<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use App\Events\InsertImprintHistory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertImprintHistoryListener
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
     * @param  \App\Events\InsertImprintHistory  $event
     * @return void
     */
    public function handle(InsertImprintHistory $event)
    {
        $data = $event->data;
        $res = DB::table('imprint_history')->insert([
            'imprint_id' => $data['imprint_id'],
            'imprint_history' => $data['imprint_history'],
            'imprint_new' => $data['imprint_new'],
            'author_id' => $data['author_id'],
            'modified_at' => $data['modified_at']
        ]);
        return $res;
    }
}
