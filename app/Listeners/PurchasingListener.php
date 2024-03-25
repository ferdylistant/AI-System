<?php

namespace App\Listeners;

use App\Events\PurchasingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class PurchasingListener
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
     * @param  \App\Events\PurchasingEvent  $event
     * @return void
     */
    public function handle(PurchasingEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Create Permintaan Pembelian':
                DB::beginTransaction();
                $res = DB::table('purchase_permintaan_gudang')->insert([
                    'id' => $data['id'],
                    'kode_permintaan' => $data['kodePermintaan'],
                    'created_by' => $data['created_by'],
                ]);
                foreach ($data['dataKode'] as $key => $value) {
                    $satuan = DB::table('satuan')->where('nama', 'like', '%' . $data['dataPermintaan'][$key]->unit . '%')->first();
                    DB::table('purchase_permintaan_gudang_detail')->insert([
                        'kode_barang' => $data['dataPermintaan'][$key]->type.'-'.$data['dataPermintaan'][$key]->stype.'-'.$data['dataPermintaan'][$key]->golongan.'-'.$data['dataPermintaan'][$key]->sgolongan.'-'.$value,
                        'permintaan_id' => $data['id'],
                        'nama_barang' => $data['dataPermintaan'][$key]->nama,
                        'kuantitas' => $data['dataPermintaan'][$key]->qty,
                        'satuan_id' => $satuan->id,
                    ]);
                }
                DB::commit();
                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
