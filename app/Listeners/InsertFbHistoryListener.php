<?php

namespace App\Listeners;

use App\Events\InsertFbHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertFbHistoryListener
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
     * @param  \App\Events\InsertFbHistory  $event
     * @return void
     */
    public function handle(InsertFbHistory $event)
    {
        $data = $event->data;
        $res = DB::table('format_buku_history')->insert([
            'format_buku_id' => $data['format_buku_id'],
            'jenis_format_history' => $data['jenis_format_history'],
            'jenis_format_new' => $data['jenis_format_new'],
            'author_id' => $data['author_id'],
            'modified_at' => $data['modified_at']
        ]);
        return $res;
    }
}
