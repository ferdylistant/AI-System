<?php

namespace App\Listeners;

use App\Events\NaskahEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class NaskahListener
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
     * @param  \App\Events\NaskahEvent  $event
     * @return void
     */
    public function handle(NaskahEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Add Naskah':
                $res = DB::table('penerbitan_naskah')->insert([
                    'id' => $data['id'],
                    'kode' => $data['kode'],
                    'judul_asli' => $data['judul_asli'],
                    'tanggal_masuk_naskah' => $data['tanggal_masuk_naskah'],
                    'email' => $data['email'],
                    'kelompok_buku_id' => $data['kelompok_buku_id'],
                    'jalur_buku' => $data['jalur_buku'],
                    'tentang_penulis' => $data['tentang_penulis'],
                    'hard_copy' => $data['hard_copy'],
                    'soft_copy' => $data['soft_copy'],
                    'cdqr_code' => $data['cdqr_code'],
                    'keterangan' => $data['keterangan'],
                    'pic_prodev' => $data['pic_prodev'],
                    'url_file' => $data['url_file'],
                    'penilaian_naskah' => $data['penilaian_naskah'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Add Penilaian Status':
                $res = DB::table('penerbitan_pn_stts')->insert([
                    'id' => $data['id'],
                    'naskah_id' => $data['naskah_id'],
                    'tgl_naskah_masuk' => $data['tgl_naskah_masuk'],
                    'tgl_pn_selesai' => $data['tgl_pn_selesai']
                ]);
                break;
            case 'Edit Naskah':
                $res = DB::table('penerbitan_naskah')->where('id', $data['id'])->update([
                    'judul_asli' => $data['judul_asli'],
                    'tanggal_masuk_naskah' => $data['tanggal_masuk_naskah'],
                    'email' => $data['email'],
                    'kelompok_buku_id' => $data['kelompok_buku_id'],
                    'jalur_buku' => $data['jalur_buku'],
                    'tentang_penulis' => $data['tentang_penulis'],
                    'hard_copy' => $data['hard_copy'],
                    'soft_copy' => $data['soft_copy'],
                    'cdqr_code' => $data['cdqr_code'],
                    'keterangan' => $data['keterangan'],
                    'pic_prodev' => $data['pic_prodev'],
                    'url_file' => $data['url_file'],
                    'updated_by' => $data['updated_by'],
                    'updated_at' => $data['updated_at']
                ]);
                break;
            case 'Insert Edit Naskah History':

                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
