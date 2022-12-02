<?php

namespace App\Listeners;

use App\Events\SettingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class SettingListener
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
     * @param  \App\Events\SettingEvent  $event
     * @return void
     */
    public function handle(SettingEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Add Section Menu':
                $res = DB::table('access_bagian')->insert([
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'order_ab' => $data['order_ab']
                ]);
                break;
            case 'Edit Section Menu':
                $res = DB::table('access_bagian')->where('id', $data['id'])->update([
                    'name' => $data['name'],
                    'order_ab' => $data['order_ab']
                ]);
                break;
            case 'Add Menu':
                $res = DB::table('access')->insert([
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'bagian_id' => $data['bagian_id'],
                    'level' => $data['level'],
                    'parent_id' => $data['parent_id'],
                    'order_menu' => $data['order_menu'],
                    'url' => $data['url'],
                    'icon' => $data['icon']
                ]);
                break;
            default:
                # code...
                break;
        }
        return $res;
    }
}
