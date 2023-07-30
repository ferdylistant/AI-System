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
            case 'Insert Penerimaan Stok':
                $res = DB::table('pj_st_penerimaan')->insert([
                    'id' => $data['id'],
                    'produksi_id' => $data['produksi_id']
                ]);
                break;
            case 'Insert Stok Andi':
                DB::beginTransaction();
                $res = DB::table('pj_st_andi')->insert([
                    'id' => $data['id'],
                    'naskah_id' => $data['naskah_id'],
                    'kode_sku' => $data['kode_sku'],
                    'total_stok' => $data['total_stok']
                ]);
                DB::commit();
                break;
            case 'Update Jumlah Stok Andi':
                DB::beginTransaction();
                $res = DB::table('pj_st_andi')->where('id',$data['id'])->update([
                    'total_stok' => $data['total_stok']
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
