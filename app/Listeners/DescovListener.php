<?php

namespace App\Listeners;

use App\Events\DescovEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DescovListener
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
     * @param  \App\Events\DescovEvent  $event
     * @return void
     */
    public function handle(DescovEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Input Deskripsi':
                $res = DB::table('deskripsi_cover')->insert([
                    'id' => $data['id'],
                    'deskripsi_produk_id' => $data['deskripsi_produk_id'],
                    'tgl_deskripsi' => $data['tgl_deskripsi']
                ]);
                break;
            case 'Update Status Descov':
                $res = DB::table('deskripsi_cover')->where('deskripsi_produk_id',$data['deskripsi_produk_id'])->update([
                    'status' => $data['status'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Insert History Edit Descov':

                break;
            case 'Insert History Status Descov':
                $res = DB::table('deskripsi_cover_history')->insert([
                    'deskripsi_cover_id' => $data['deskripsi_cover_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
