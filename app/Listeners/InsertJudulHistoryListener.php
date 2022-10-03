<?php

namespace App\Listeners;

use App\Events\InsertJudulHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertJudulHistoryListener
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
     * @param  \App\Events\InsertJudulHistory  $event
     * @return void
     */
    public function handle(InsertJudulHistory $event)
    {
        $data = $event->data;
        $res = DB::table('deskripsi_produk_history')->insert([
            'deskripsi_produk_id' => $data['id'],
            'type_history' => 'Update',
            'judul_final_new' => $data['judul'],
            'author_id' => auth()->id(),
            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
        ]);
        return $res;
    }
}
