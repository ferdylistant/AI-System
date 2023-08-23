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
                $res = DB::table('proses_produksi_track')
                ->where('produksi_id',$data['produksi_id'])
                ->where('proses_tahap',$data['proses_tahap'])
                ->update([
                    'mesin' => $data['mesin'],
                    'operator' => $data['operator'],
                    'status' => $data['status'],
                    'tgl_pending' => $data['tgl_pending'],
                    'tgl_mulai' => $data['tgl_mulai'],
                    'tgl_selesai' => $data['tgl_selesai'],
                ]);
                break;
            case 'Insert Track Produksi':
                $res = DB::table('proses_produksi_track')->insert([
                    'produksi_id' => $data['produksi_id'],
                    'proses_tahap' => $data['proses_tahap'],
                    'mesin' => $data['mesin'],
                    'operator' => $data['operator'],
                    'status' => $data['status'],
                    'tgl_pending' => $data['tgl_pending'],
                    'tgl_mulai' => $data['tgl_mulai'],
                    'tgl_selesai' => $data['tgl_selesai'],
                ]);
                break;
            case 'Insert Riwayat Track':
                $res = DB::table('proses_produksi_track_riwayat')->insert([
                    'track_id' => $data['track_id'],
                    'track' => $data['track'],
                    'mesin_new' => $data['mesin_new'],
                    'operator_new' => $data['operator_new'],
                    'status_new' => $data['status_new'],
                    'tahap' => $data['tahap'],
                    'jml_dikirim' => $data['jml_dikirim'],
                    'tgl_pending' => $data['tgl_pending'],
                    'tgl_mulai' => $data['tgl_mulai'],
                    'tgl_selesai' => $data['tgl_selesai'],
                    'users_id' => $data['users_id']
                ]);
                break;
            case 'Delete Riwayat Track':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_track_riwayat')->delete($data['id']);
                DB::commit();
                break;
            case 'Edit Riwayat Jumlah Kirim':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_track_riwayat')->where('id',$data['id'])->update([
                    'jml_dikirim' => $data['jml_dikirim'],
                    'catatan' => $data['catatan'],
                    'updated_at' => $data['updated_at'],
                ]);
                DB::commit();
                break;
            case 'Update Penerimaan Buku':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_track_riwayat')->where('id',$data['id'])->update([
                    'catatan' => $data['catatan'],
                    'tgl_diterima' => $data['tgl_diterima'],
                    'diterima_oleh' => $data['diterima_oleh'],
                    'status_new' => $data['status_new'],
                ]);
                DB::commit();
                break;
            case 'Selesai Penerimaan Buku':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_track')->where('id',$data['id'])->update([
                    'status' => $data['status'],
                    'tgl_selesai' => $data['tgl_selesai']
                ]);
                DB::commit();
                break;
            case 'Create Data Rekondisi':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_rekondisi')->insert([
                    'id' => $data['id'],
                    'kode' => $data['kode'],
                    'produksi_id' => $data['produksi_id'],
                    'jml_rekondisi' => $data['jml_rekondisi'],
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
