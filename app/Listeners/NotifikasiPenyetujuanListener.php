<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Events\NotifikasiPenyetujuan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifikasiPenyetujuanListener
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
     * @param  \App\Events\NotifikasiPenyetujuanListener  $event
     * @return void
     */
    public function handle(NotifikasiPenyetujuan $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Insert Notif':
                DB::beginTransaction();
                $res = DB::table('notif')->insert([
                    'id' => $data['id'],
                    'section' => $data['section'],
                    'type' => $data['type'],
                    'permission_id' => $data['permission_id'],
                    'form_id' => $data['form_id']
                ]);
                if (!is_null($data['users_id'])) {
                    (object)collect($data['users_id'])->map(function($item) use($data) {
                        DB::table('notif_detail')->insert([
                            'notif_id' => $data['id'],
                            'user_id' => $item->user_id
                        ]);
                    })->all();
                }
                DB::commit();
                break;

            default:
                return abort(500);
                break;
        }
        return $res;
    }
}
