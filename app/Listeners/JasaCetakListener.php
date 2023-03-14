<?php

namespace App\Listeners;

use App\Events\JasaCetakEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class JasaCetakListener
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
     * @param  \App\Events\JasaCetakEvent  $event
     * @return void
     */
    public function handle(JasaCetakEvent $event)
    {
        $data = $event->data;
        switch($data['params']) {
            case 'Add Order Buku':
                $res = DB::table('jasa_cetak_order_buku')->insert([
                    'id' => $data['id'],
                    'no_order' => $data['no_order'],
                    'judul_buku' => $data['judul_buku'],
                    'tgl_order' => $data['tgl_order'],
                    'tgl_permintaan_selesai' => $data['tgl_permintaan_selesai'],
                    'nama_pemesan' => $data['nama_pemesan'],
                    'pengarang' => $data['pengarang'],
                    'format' => $data['format'],
                    'jml_halaman' => $data['jml_halaman'],
                    'kertas_isi' => $data['kertas_isi'],
                    'kertas_isi_tinta' => $data['kertas_isi_tinta'],
                    'kertas_cover' => $data['kertas_cover'],
                    'kertas_cover_tinta' => $data['kertas_cover_tinta'],
                    'jml_order' => $data['jml_order'],
                    'status_cetak' => $data['status_cetak'],
                    'keterangan' => $data['keterangan'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Edit Order Buku':
                $res = DB::table('jasa_cetak_order_buku')->where('id',$data['id'])->where('no_order',$data['no_order'])
                ->update([
                    'judul_buku' => $data['judul_buku'],
                    'tgl_order' => $data['tgl_order'],
                    'tgl_permintaan_selesai' => $data['tgl_permintaan_selesai'],
                    'nama_pemesan' => $data['nama_pemesan'],
                    'pengarang' => $data['pengarang'],
                    'format' => $data['format'],
                    'jml_halaman' =>  $data['jml_halaman'],
                    'kertas_isi' => $data['kertas_isi'],
                    'kertas_isi_tinta' => $data['kertas_isi_tinta'],
                    'kertas_cover' => $data['kertas_cover'],
                    'kertas_cover_tinta' => $data['kertas_cover_tinta'],
                    'jml_order' => $data['jml_order'],
                    'status_cetak' => $data['status_cetak'],
                    'keterangan' => $data['keterangan']
                ]);
                break;
            case 'History Edit Order Buku':
                $res = DB::table('jasa_cetak_order_buku_history')->insert([
                    'type_history' => $data['type_history'],
                    'order_buku_id' => $data['order_buku_id'],
                    'judul_buku_his' => $data['judul_buku_his'],
                    'judul_buku_new' => $data['judul_buku_new'],
                    'tgl_order_his' => $data['tgl_order_his'],
                    'tgl_order_new' => $data['tgl_order_new'],
                    'tgl_permintaan_selesai_his' => $data['tgl_permintaan_selesai_his'],
                    'tgl_permintaan_selesai_new' => $data['tgl_permintaan_selesai_new'],
                    'nama_pemesan_his' => $data['nama_pemesan_his'],
                    'nama_pemesan_new' => $data['nama_pemesan_new'],
                    'pengarang_his' => $data['pengarang_his'],
                    'pengarang_new' => $data['pengarang_new'],
                    'format_his' => $data['format_his'],
                    'format_new' => $data['format_new'],
                    'jml_halaman_his' => $data['jml_halaman_his'],
                    'jml_halaman_new' => $data['jml_halaman_new'],
                    'kertas_isi_his' => $data['kertas_isi_his'],
                    'kertas_isi_new' => $data['kertas_isi_new'],
                    'kertas_isi_tinta_his' => $data['kertas_isi_tinta_his'],
                    'kertas_isi_tinta_new' => $data['kertas_isi_tinta_new'],
                    'kertas_cover_his' => $data['kertas_cover_his'],
                    'kertas_cover_new' => $data['kertas_cover_new'],
                    'kertas_cover_tinta_his' => $data['kertas_cover_tinta_his'],
                    'kertas_cover_tinta_new' => $data['kertas_cover_tinta_new'],
                    'jml_order_his' => $data['jml_order_his'],
                    'jml_order_new' => $data['jml_order_new'],
                    'status_cetak_his' => $data['status_cetak_his'],
                    'status_cetak_new' => $data['status_cetak_new'],
                    'keterangan_his' => $data['keterangan_his'],
                    'keterangan_new' => $data['keterangan_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Edit Or Add Kalkulasi Harga':
                $res = DB::table('jasa_cetak_order_buku')->where('id',$data['id'])->where('no_order',$data['no_order'])
                ->update([
                    'kalkulasi_harga' => $data['kalkulasi_harga'],
                ]);
                break;
            case 'History Edit Or Add Kalkulasi Harga':
                $res = DB::table('jasa_cetak_order_buku_history')->insert([
                    'type_history' => $data['type_history'],
                    'order_buku_id' => $data['order_buku_id'],
                    'kalkulasi_harga_his' => $data['kalkulasi_harga_his'],
                    'kalkulasi_harga_new' => $data['kalkulasi_harga_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Update Status Order Buku':
                $res = DB::table('jasa_cetak_order_buku')->where('id', $data['id'])->update([
                    'tgl_selesai_order' => $data['tgl_selesai_order'],
                    'status' => $data['status']
                ]);
                break;
            case 'Insert History Status Order Buku':
                $res = DB::table('jasa_cetak_order_buku_history')->insert([
                    'order_buku_id' => $data['order_buku_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Update Jalur Proses Order Buku':
                $res = $data['data']->update([
                    'jalur_proses' => $data['jalur_proses']
                ]);
                DB::table('jasa_cetak_order_buku_history')->insert([
                    'order_buku_id' => $data['data']->first()->id,
                    'type_history' => $data['type_history'],
                    'jalur_proses' => $data['jalur_proses'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Pilih Harga Order Buku':
                $res = $data['data']->update([
                    'harga_final' => $data['harga_final']
                ]);
                DB::table('jasa_cetak_order_buku_history')->insert([
                    'order_buku_id' => $data['data']->first()->id,
                    'type_history' => 'Pilih Harga',
                    'harga_final_his' => $data['harga_final_his'],
                    'harga_final_new' => $data['harga_final_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Approval Order Buku':
                $res = $data['data']->update([
                    'approve' => $data['approve'],
                    'decline' => $data['decline'],
                    'keterangan_decline' => $data['keterangan_decline'],
                    'decline_by' => $data['decline_by'],
                    'status' => $data['status'],
                ]);
                DB::table('jasa_cetak_order_buku_history')->insert([
                    'order_buku_id' => $data['data']->first()->id,
                    'type_history' => 'Approval',
                    'approve' => $data['approve'],
                    'decline' => $data['decline'],
                    'keterangan_decline' => $data['keterangan_decline'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Progress Desain-Proof-Korektor-Pracetak':
                DB::beginTransaction();
                $label = 'mulai_'.$data['label'];
                $res = DB::table('jasa_cetak_order_buku')->where('id', $data['id'])->update([
                    $label => $data[$label],
                    'proses' => $data['proses'],
                ]);
                DB::table('jasa_cetak_order_buku_history')->insert([
                    'order_buku_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'progress' => $data['proses'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
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
