<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use App\Events\InsertPlatformHistory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertPlatformHistoryListener
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
     * @param  \App\Events\InsertPlatformHistory  $event
     * @return void
     */
    public function handle(InsertPlatformHistory $event)
    {
        $data = $event->data;
        $res = DB::table('platform_digital_ebook_history')->insert([
            'platform_id' => $data['platform_id'],
            'platform_history' => $data['platform_history'],
            'platform_new' => $data['platform_new'],
            'author_id' => $data['author_id'],
            'modified_at' => $data['modified_at']
        ]);
        return $res;
    }
}
