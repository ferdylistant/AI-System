<?php

namespace App\Listeners;

use App\Events\OrderEbookEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderEbookListener
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
     * @param  \App\Events\OrderEbookEvent  $event
     * @return void
     */
    public function handle(OrderEbookEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Update Status Order Ebook':
                DB::beginTransaction();
                $res = DB::table('order_ebook')->where('id', $data['id'])->update([
                    'tgl_selesai_order' => $data['tgl_selesai_order'],
                    'status' => $data['status']
                ]);
                DB::commit();
                break;
            case 'Insert History Status Order Ebook':
                DB::beginTransaction();
                $res = DB::table('order_ebook_history')->insert([
                    'order_ebook_id' => $data['order_ebook_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Update Order E-book':
                DB::beginTransaction();
                $res = DB::table('order_ebook as oe')
                    ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oe.deskripsi_turun_cetak_id')
                    ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                    ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->where('oe.id', $data['id'])
                    ->update([
                        'oe.tahun_terbit' => $data['tahun_terbit'],
                        'oe.tgl_upload' => $data['tgl_upload'],
                        'oe.spp' => $data['spp'],
                        'oe.keterangan' => $data['keterangan'],
                        'oe.perlengkapan' => $data['perlengkapan'],
                        'oe.eisbn' => $data['eisbn'],
                        'dtc.tipe_order' => $data['tipe_order'],
                        'ps.edisi_cetak' => $data['edisi_cetak'],
                        'dp.jml_hal_perkiraan' => $data['jml_hal_perkiraan'],
                        'pn.kelompok_buku_id' => $data['kelompok_buku_id'],
                    ]);
                DB::commit();
                break;
            case 'Insert History Update Order E-book':
                DB::beginTransaction();
                $res = DB::table('order_ebook_history')->insert([
                    'type_history' => $data['type_history'],
                    'order_ebook_id' => $data['order_ebook_id'],
                    'tipe_order_his' => $data['tipe_order_his'],
                    'tipe_order_new' => $data['tipe_order_new'],
                    'edisi_cetak_his' => $data['edisi_cetak_his'],
                    'edisi_cetak_new' => $data['edisi_cetak_new'],
                    'jml_hal_perkiraan_his' => $data['jml_hal_perkiraan_his'],
                    'jml_hal_perkiraan_new' => $data['jml_hal_perkiraan_new'],
                    'kelompok_buku_id_his' => $data['kelompok_buku_id_his'],
                    'kelompok_buku_id_new' => $data['kelompok_buku_id_new'],
                    'tahun_terbit_his' => $data['tahun_terbit_his'],
                    'tahun_terbit_new' => $data['tahun_terbit_new'],
                    'tgl_upload_his' => $data['tgl_upload_his'],
                    'tgl_upload_new' => $data['tgl_upload_new'],
                    'spp_his' => $data['spp_his'],
                    'spp_new' => $data['spp_new'],
                    'keterangan_his' => $data['keterangan_his'],
                    'keterangan_new' => $data['keterangan_new'],
                    'perlengkapan_his' => $data['perlengkapan_his'],
                    'perlengkapan_new' => $data['perlengkapan_new'],
                    'eisbn_his' => $data['eisbn_his'],
                    'eisbn_new' => $data['eisbn_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Insert Action':
                DB::beginTransaction();
                $res = DB::table('order_ebook_action')->insert([
                    'order_ebook_id' => $data['order_ebook_id'],
                    'type_jabatan' => $data['type_jabatan'],
                    'type_action' => $data['type_action'],
                    'users_id' => $data['users_id'],
                    'catatan_action' => $data['catatan_action'],
                    'tgl_action' => $data['tgl_action']
                ]);
                //?HISTORY
                DB::table('order_ebook_history')->insert([
                    'order_ebook_id' => $data['order_ebook_id'],
                    'type_history' => $data['type_action'],
                    'type_jabatan' => $data['type_jabatan'],
                    'catatan_action' => $data['catatan_action'],
                    'author_id' => $data['users_id'],
                    'modified_at' => $data['tgl_action']
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
