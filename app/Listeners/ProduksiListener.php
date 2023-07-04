<?php

namespace App\Listeners;

use App\Events\ProduksiEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProduksiListener
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
     * @param  \App\Events\ProduksiEvent  $event
     * @return void
     */
    public function handle(ProduksiEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Insert Produksi':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_cetak')->insert([
                    'id' => $data['id'],
                    'order_cetak_id' => $data['order_cetak_id'],
                    'naskah_dari_divisi' => $data['naskah_dari_divisi']
                ]);
                DB::commit();
                break;
            default:
                return abort(500);
                break;
        }
        return $res;
    }
}
