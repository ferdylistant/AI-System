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
                    'produksi_id' => $data['produksi_id'],
                    'track_id' => $data['track_id'],
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
            case 'Insert Stock Detail In Rack':
                DB::beginTransaction();
                $res = DB::table('pj_st_rack_data_detail')->insert($data['data']);
                DB::commit();
                break;
            case 'Update Stock In Rack':
                DB::beginTransaction();
                $res = DB::table('pj_st_rack_data_detail')->where('id',$data['id'])
                ->update([
                    'jml_stok' => $data['jml_stok'],
                    'tgl_masuk_stok' => $data['tgl_masuk_stok'],
                    'operators_id' => $data['operators_id']
                ]);
                DB::commit();
                break;
            case 'Insert Riwayat Aktivitas Rak':
                DB::beginTransaction();
                $res = DB::table('pj_st_rack_data_activity')->insert([
                    'rack_id' => $data['rack_id'],
                    'stok_id' => $data['stok_id'],
                    'type_history' => $data['type_history'],
                    'type_activity' => $data['type_activity'],
                    'qty' => $data['qty'],
                    'sisa' => $data['sisa'],
                    'catatan' => $data['catatan'],
                    'created_by' => $data['created_by']
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
