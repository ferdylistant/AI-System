<?php

namespace App\Listeners;

use App\Events\DescovEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DescovListener
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
     * @param  \App\Events\DescovEvent  $event
     * @return void
     */
    public function handle(DescovEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Input Deskripsi':
                $res = DB::table('deskripsi_cover')->insert([
                    'id' => $data['id'],
                    'deskripsi_produk_id' => $data['deskripsi_produk_id'],
                    'tgl_deskripsi' => $data['tgl_deskripsi']
                ]);
                break;
            case 'Update Status Descov':
                $res = DB::table('deskripsi_cover')->where('deskripsi_produk_id',$data['deskripsi_produk_id'])->update([
                    'status' => $data['status'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Edit Descov':
                $res = DB::table('deskripsi_cover as dc')
                ->join('deskripsi_produk as dp','dp.id','=','dc.deskripsi_produk_id')
                ->join('deskripsi_final as df','dp.id','=','df.deskripsi_produk_id')
                ->where('dc.id',$data['id'])
                ->update([
                    'df.sub_judul_final' => $data['sub_judul_final'],
                    'df.bullet' => $data['bullet'],
                    'dp.nama_pena' => $data['nama_pena'],
                    'dp.format_buku' => $data['format_buku'],
                    'dc.des_front_cover' => $data['des_front_cover'],
                    'dc.des_back_cover' => $data['des_back_cover'],
                    'dc.finishing_cover' => $data['finishing_cover'],
                    'dc.jilid' => $data['jilid'],
                    'dc.tipografi' => $data['tipografi'],
                    'dc.warna' => $data['warna'],
                    'dc.kelengkapan' => $data['kelengkapan'],
                    'dc.catatan' => $data['catatan'],
                    'dc.desainer' => $data['desainer'],
                    'dc.contoh_cover' => $data['contoh_cover'],
                    'dc.bulan' => $data['bulan'],
                    'dc.updated_by' => $data['updated_by']
                ]);
                break;
            case 'Insert History Edit Descov':
                $res = DB::table('deskripsi_cover_history')->insert([
                    'deskripsi_cover_id' => $data['deskripsi_cover_id'],
                    'type_history' => $data['type_history'],
                    'nama_pena_his' => $data['nama_pena_his'],
                    'nama_pena_new' => $data['nama_pena_new'],
                    'format_buku_his' => $data['format_buku_his'],
                    'format_buku_new' => $data['format_buku_new'],
                    'sub_judul_final_his' => $data['sub_judul_final_his'],
                    'sub_judul_final_new' => $data['sub_judul_final_new'],
                    'des_front_cover_his' => $data['des_front_cover_his'],
                    'des_front_cover_new' => $data['des_front_cover_new'],
                    'des_back_cover_his' => $data['des_back_cover_his'],
                    'des_back_cover_new' => $data['des_back_cover_new'],
                    'finishing_cover_his' => $data['finishing_cover_his'],
                    'finishing_cover_new' => $data['finishing_cover_new'],
                    'jilid_his' => $data['jilid_his'],
                    'jilid_new' => $data['jilid_new'],
                    'tipografi_his' => $data['tipografi_his'],
                    'tipografi_new' => $data['tipografi_new'],
                    'warna_his' => $data['warna_his'],
                    'warna_new' => $data['warna_new'],
                    'bullet_his' => $data['bullet_his'],
                    'bullet_new' => $data['bullet_new'],
                    'desainer_his' => $data['desainer_his'],
                    'desainer_new' => $data['desainer_new'],
                    'bulan_his' => $data['bulan_his'],
                    'bulan_new' => $data['bulan_new'],
                    'contoh_cover_his' => $data['contoh_cover_his'],
                    'contoh_cover_new' => $data['contoh_cover_new'],
                    'kelengkapan_his' => $data['kelengkapan_his'],
                    'kelengkapan_new' => $data['kelengkapan_new'],
                    'catatan_his' => $data['catatan_his'],
                    'catatan_new' => $data['catatan_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Status Descov':
                $res = DB::table('deskripsi_cover_history')->insert([
                    'deskripsi_cover_id' => $data['deskripsi_cover_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
