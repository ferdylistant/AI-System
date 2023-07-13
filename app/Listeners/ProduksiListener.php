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
            case 'Update Track Produksi':
                DB::beginTransaction();
                $res = $data['query']->update([
                    'mesin' => $data['mesin'],
                    'operator' => $data['operator'],
                    'status' => $data['status'],
                    'tgl_selesai' => $data['tgl_selesai']
                ]);
                DB::commit();
                break;
            case 'Insert Track Produksi':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_track')->insert([
                    'produksi_id' => $data['produksi_id'],
                    'proses_tahap' => $data['proses_tahap'],
                    'mesin' => $data['mesin'],
                    'operator' => $data['operator'],
                    'status' => $data['status'],
                    'tgl_selesai' => $data['tgl_selesai']
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
