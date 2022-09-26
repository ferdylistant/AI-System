<?php

namespace App\Listeners;

use App\Events\InsertStatusHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class InsertStatusHistoryListener
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
     * @param  \App\Events\InsertStatusHistory  $event
     * @return void
     */
    public function handle(InsertStatusHistory $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_produk_history')->insert([
            'deskripsi_produk_id' => $data['deskripsi_produk_id'],
            'type_history' => $data['type_history'],
            'status_his' => $data['status_his'],
            'status_new' => $data['status_new'],
            'author_id' => $data['author_id'],
            'modified_at' => $data['modified_at']
        ]);
        return $res;
    }
}
