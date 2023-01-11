<?php

namespace App\Listeners;

use App\Events\OrderCetakEvent;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class OrderCetakListener
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
     * @param  \App\Events\OrderCetakEvent  $event
     * @return void
     */
    public function handle(OrderCetakEvent $event)
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
                $res = DB::table('deskripsi_turun_cetak')->where('id', $data['id'])->update([
                    'status' => $data['status'],
                    'tgl_status_selesai' => $data['tgl_status_selesai']
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
                        'dtc.tipe_order' => $data['tipe_order'],
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
                    'tipe_order_his' => $data['tipe_order_his'],
                    'tipe_order_new' => $data['tipe_order_new'],
                    'bulan_his' => $data['bulan_his'],
                    'bulan_new' => $data['bulan_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert Pilihan Terbit Desturcet':
                $res = DB::table('pilihan_penerbitan')->insert([
                    'id' => $data['id'],
                    'deskripsi_turun_cetak_id' => $data['deskripsi_turun_cetak_id'],
                    'pilihan_terbit' => $data['pilihan_terbit'],
                    'platform_digital_ebook_id' => $data['platform_ebook']
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
            case 'Insert Order':
                DB::beginTransaction();
                $table = 'order_' . $data['type'];
                $res = DB::table($table)->insert([
                    'id' => $data['id'],
                    'deskripsi_turun_cetak_id' => $data['deskripsi_turun_cetak_id'],
                    'kode_order' => $data['kode_order'],
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
