<?php

namespace App\Listeners;

use App\Events\PenjualanStokEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PenjualanStokListener
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
     * @param  \App\Events\PenjualanStokEvent  $event
     * @return void
     */
    public function handle(PenjualanStokEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Insert Stok Gudang':
                $res = DB::table('stok_produksi')->insert([
                    'id' => $data['id'],
                    'produksi_id' => $data['produksi_id']
                ]);
                break;
            default:
            return abort(500);
            break;
        }
        return $res;
    }
}
