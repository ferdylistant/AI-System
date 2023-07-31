<?php

namespace App\Listeners;

use App\Events\OrderCetakEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
            case 'Update Status Order Cetak':
                DB::beginTransaction();
                $res = DB::table('order_cetak')->where('id', $data['id'])->update([
                    'tgl_selesai_order' => $data['tgl_selesai_order'],
                    'status' => $data['status']
                ]);
                DB::commit();
                break;
            case 'Insert History Status Order Cetak':
                DB::beginTransaction();
                $res = DB::table('order_cetak_history')->insert([
                    'order_cetak_id' => $data['order_cetak_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Update Order Cetak':
                DB::beginTransaction();
                $res = DB::table('order_cetak as oc')
                    ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
                    ->join('pilihan_penerbitan as pp', 'pp.deskripsi_turun_cetak_id', '=', 'dtc.id')
                    ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                    ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                    ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                    ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->where('oc.id', $data['id'])
                    ->update([
                        'oc.posisi_layout' => $data['posisi_layout'],
                        'oc.dami' => $data['dami'],
                        'oc.ukuran_jilid_binding' => $data['ukuran_jilid_binding'],
                        'oc.jenis_cover' => $data['jenis_cover'],
                        'oc.kertas_cover' => $data['kertas_cover'],
                        'oc.buku_jadi' => $data['buku_jadi'],
                        'oc.jumlah_cetak' => $data['jumlah_cetak'],
                        'oc.tahun_terbit' => $data['tahun_terbit'],
                        'oc.tgl_permintaan_jadi' => $data['tgl_permintaan_jadi'],
                        'oc.spp' => $data['spp'],
                        'oc.buku_contoh' => $data['buku_contoh'],
                        'oc.keterangan' => $data['keterangan'],
                        'oc.perlengkapan' => $data['perlengkapan'],
                        'dtc.tipe_order' => $data['tipe_order'],
                        'ps.edisi_cetak' => $data['edisi_cetak'],
                        'ps.jml_hal_final' => $data['jml_hal_final'],
                        'dp.format_buku' => $data['format_buku'],
                        'pn.kelompok_buku_id' => $data['kelompok_buku_id'],
                        'pn.sub_kelompok_buku_id' => $data['sub_kelompok_buku_id'],
                        'dc.jilid' => $data['jilid'],
                        'dc.warna' => $data['warna'],
                        'dc.finishing_cover' => $data['finishing_cover'],
                        'df.kertas_isi' => $data['kertas_isi'],
                        'df.isi_warna' => $data['isi_warna'],
                    ]);
                DB::commit();
                break;
            case 'Insert History Update Order Cetak':
                DB::beginTransaction();
                $res = DB::table('order_cetak_history')->insert([
                    'type_history' => $data['type_history'],
                    'order_cetak_id' => $data['order_cetak_id'],
                    'edisi_cetak_his' => $data['edisi_cetak_his'],
                    'edisi_cetak_new' => $data['edisi_cetak_new'],
                    'jml_hal_final_his' => $data['jml_hal_final_his'],
                    'jml_hal_final_new' => $data['jml_hal_final_new'],
                    'kelompok_buku_id_his' => $data['kelompok_buku_id_his'],
                    'kelompok_buku_id_new' => $data['kelompok_buku_id_new'],
                    'tipe_order_his' => $data['tipe_order_his'],
                    'tipe_order_new' => $data['tipe_order_new'],
                    'posisi_layout_his' => $data['posisi_layout_his'],
                    'posisi_layout_new' => $data['posisi_layout_new'],
                    'dami_his' => $data['dami_his'],
                    'dami_new' => $data['dami_new'],
                    'format_buku_his' => $data['format_buku_his'],
                    'format_buku_new' => $data['format_buku_new'],
                    'jilid_his' => $data['jilid_his'],
                    'jilid_new' => $data['jilid_new'],
                    'ukuran_binding_his' => $data['ukuran_binding_his'],
                    'ukuran_binding_new' => $data['ukuran_binding_new'],
                    'kertas_isi_his' => $data['kertas_isi_his'],
                    'kertas_isi_new' => $data['kertas_isi_new'],
                    'isi_warna_his' => $data['isi_warna_his'],
                    'isi_warna_new' => $data['isi_warna_new'],
                    'jenis_cover_his' => $data['jenis_cover_his'],
                    'jenis_cover_new' => $data['jenis_cover_new'],
                    'kertas_cover_his' => $data['kertas_cover_his'],
                    'kertas_cover_new' => $data['kertas_cover_new'],
                    'warna_cover_his' => $data['warna_cover_his'],
                    'warna_cover_new' => $data['warna_cover_new'],
                    'finishing_cover_his' => $data['finishing_cover_his'],
                    'finishing_cover_new' => $data['finishing_cover_new'],
                    'buku_jadi_his' => $data['buku_jadi_his'],
                    'buku_jadi_new' => $data['buku_jadi_new'],
                    'jumlah_cetak_his' => $data['jumlah_cetak_his'],
                    'jumlah_cetak_new' => $data['jumlah_cetak_new'],
                    'tahun_terbit_his' => $data['tahun_terbit_his'],
                    'tahun_terbit_new' => $data['tahun_terbit_new'],
                    'tgl_permintaan_jadi_his' => $data['tgl_permintaan_jadi_his'],
                    'tgl_permintaan_jadi_new' => $data['tgl_permintaan_jadi_new'],
                    'spp_his' => $data['spp_his'],
                    'spp_new' => $data['spp_new'],
                    'buku_contoh_his' => $data['buku_contoh_his'],
                    'buku_contoh_new' => $data['buku_contoh_new'],
                    'keterangan_his' => $data['keterangan_his'],
                    'keterangan_new' => $data['keterangan_new'],
                    'perlengkapan_his' => $data['perlengkapan_his'],
                    'perlengkapan_new' => $data['perlengkapan_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Insert Action':
                DB::beginTransaction();
                $res = DB::table('order_cetak_action')->insert([
                    'order_cetak_id' => $data['order_cetak_id'],
                    'type_departemen' => $data['type_departemen'],
                    'type_action' => $data['type_action'],
                    'users_id' => $data['users_id'],
                    'catatan_action' => $data['catatan_action'],
                    'tgl_action' => $data['tgl_action']
                ]);
                //?HISTORY
                DB::table('order_cetak_history')->insert([
                    'order_cetak_id' => $data['order_cetak_id'],
                    'type_history' => $data['type_action'],
                    'type_departemen' => $data['type_departemen'],
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
