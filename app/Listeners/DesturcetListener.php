<?php

namespace App\Listeners;

use App\Events\DesturcetEvent;
use Illuminate\Support\Facades\DB;

class DesturcetListener
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
     * @param  \App\Events\DesturcetEvent  $event
     * @return void
     */
    public function handle(DesturcetEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Input Deskripsi':
                $res = DB::table('deskripsi_turun_cetak')->insert([
                    'id' => $data['id'],
                    'deskripsi_produk_id' => $data['deskripsi_produk_id'],
                    'tgl_deskripsi' => $data['tgl_deskripsi']
                ]);
                break;
            case 'Update Status Desturcet':
                $res = DB::table('deskripsi_turun_cetak')->where('deskripsi_produk_id', $data['deskripsi_produk_id'])->update([
                    'status' => $data['status'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Edit Desturcet':
                DB::beginTransaction();
                $res = DB::table('deskripsi_turun_cetak as dtc')
                    ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                    ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                    ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->where('dtc.id', $data['id'])
                    ->update([
                        'dtc.bulan' => $data['bulan'],
                        'ps.edisi_cetak' => $data['edisi_cetak'],
                        'dp.format_buku' => $data['format_buku'],
                    ]);
                DB::commit();
                break;
            case 'Insert History Edit Desturcet':
                $res = DB::table('deskripsi_turun_cetak_history')->insert([
                    'deskripsi_turun_cetak_id' => $data['deskripsi_turun_cetak_id'],
                    'type_history' => $data['type_history'],
                    'edisi_cetak_his' => $data['edisi_cetak_his'],
                    'edisi_cetak_new' => $data['edisi_cetak_new'],
                    'format_buku_his' => $data['format_buku_his'],
                    'format_buku_new' => $data['format_buku_new'],
                    'bulan_his' => $data['bulan_his'],
                    'bulan_new' => $data['bulan_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Status Desturcet':
                $res = DB::table('deskripsi_turun_cetak_history')->insert([
                    'deskripsi_turun_cetak_id' => $data['deskripsi_turun_cetak_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert Turun Cetak':
                DB::beginTransaction();
                $res = DB::table('deskripsi_turun_cetak')->insert([
                    'id' => $data['id'],
                    'pracetak_cover_id' => $data['pracetak_cover_id'],
                    'pracetak_setter_id' => $data['pracetak_setter_id'],
                    'tgl_masuk' => $data['tgl_masuk']
                ]);
                DB::commit();
                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
