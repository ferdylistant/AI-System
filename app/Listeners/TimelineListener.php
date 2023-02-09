<?php

namespace App\Listeners;

use App\Events\TimelineEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TimelineListener
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
     * @param  \App\Events\TimelineEvent  $event
     * @return void
     */
    public function handle(TimelineEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Insert Timeline':
                $res = DB::table('timeline')->insert([
                    'id' => $data['id'],
                    'progress' => $data['progress'],
                    'naskah_id' => $data['naskah_id'],
                    'tgl_mulai' => $data['tgl_mulai'],
                    'url_action' => $data['url_action'],
                    'status' => $data['status']
                ]);
                break;
            case 'Update Timeline':
                $res = DB::table('timeline')->where('naskah_id',$data['naskah_id'])->where('progress',$data['progress'])->update([
                    'tgl_selesai' => $data['tgl_selesai'],
                    'status' => $data['status']
                ]);
                break;
            default:
                return abort(500);
                break;
        }
        return $res;
    }
}
