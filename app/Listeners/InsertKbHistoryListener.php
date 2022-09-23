<?php

namespace App\Listeners;

use App\Events\InsertKbHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertKbHistoryListener
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
     * @param  \App\Events\InsertKbHistory  $event
     * @return void
     */
    public function handle(InsertKbHistory $event)
    {
        $data = $event->data;
        $res = DB::table('penerbitan_m_kelompok_buku_history')->insert([
            'kelompok_buku_id' => $data['kelompok_buku_id'],
            'kelompok_buku_history' => $data['kelompok_buku_history'],
            'kelompok_buku_new' => $data['kelompok_buku_new'],
            'author_id' => $data['author_id'],
            'modified_at' => $data['modified_at']
        ]);
        return $res;
    }
}
