<?php

namespace App\Listeners;

use App\Events\DesfinEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DesfinListener
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
     * @param  \App\Events\DesfinEvent  $event
     * @return void
     */
    public function handle(DesfinEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Input Deskripsi':
                $res = DB::table('deskripsi_final')->insert([
                    'id' => $data['id'],
                    'deskripsi_produk_id' => $data['deskripsi_produk_id'],
                    'tgl_deskripsi' => $data['tgl_deskripsi']
                ]);
                break;
            case 'Edit Desfin':
                $res = DB::table('deskripsi_final as df')
                    ->join('deskripsi_produk as dp','dp.id','=','df.deskripsi_produk_id')
                    ->where('df.id', $data['id'])
                    ->update([
                    'dp.judul_final' => $data['judul_final'],
                    'df.sub_judul_final' => $data['sub_judul_final'],
                    'df.kertas_isi' => $data['kertas_isi'],
                    'df.jml_hal_asli' => $data['jml_hal_asli'],
                    'df.ukuran_asli' => $data['ukuran_asli'],
                    'df.isi_warna' => $data['isi_warna'],
                    'df.isi_huruf' => $data['isi_huruf'],
                    'df.bullet' => $data['bullet'],
                    'df.setter' => $data['setter'],
                    'df.korektor' => $data['korektor'],
                    'df.bulan' => $data['bulan'],
                    'df.updated_by'=> $data['updated_by']
                ]);
                break;
            case 'Insert History Desfin':
                $res = DB::table('deskripsi_final_history')->insert([
                    'deskripsi_final_id' => $data['deskripsi_final_id'],
                    'type_history' => $data['type_history'],
                    'judul_final_his' => $data['judul_final_his'],
                    'judul_final_new' => $data['judul_final_new'],
                    'sub_judul_final_his' => $data['sub_judul_final_his'],
                    'sub_judul_final_new' => $data['sub_judul_final_new'],
                    'kertas_isi_his' => $data['kertas_isi_his'],
                    'kertas_isi_new' => $data['kertas_isi_new'],
                    'jml_hal_asli_his' => $data['jml_hal_asli_his'],
                    'jml_hal_asli_new' => $data['jml_hal_asli_new'],
                    'ukuran_asli_his' => $data['ukuran_asli_his'],
                    'ukuran_asli_new' => $data['ukuran_asli_new'],
                    'isi_warna_his' => $data['isi_warna_his'],
                    'isi_warna_new' => $data['isi_warna_new'],
                    'isi_huruf_his' => $data['isi_huruf_his'],
                    'isi_huruf_new' => $data['isi_huruf_new'],
                    'bullet_his' => $data['bullet_his'],
                    'bullet_new' => $data['bullet_new'],
                    'setter_his' => $data['setter_his'],
                    'setter_new' => $data['setter_new'],
                    'korektor_his' => $data['korektor_his'],
                    'korektor_new' => $data['korektor_new'],
                    'bulan_his' => $data['bulan_his'],
                    'bulan_new' => $data['bulan_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Update Status Desfin':
                $res = DB::table('deskripsi_final')->where('id',$data['id'])->whereNull('deleted_at')->update([
                    'status' => $data['status'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Insert History Status Desfin':
                $res = DB::table('deskripsi_final_history')->insert([
                    'deskripsi_final_id' => $data['deskripsi_final_id'],
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
