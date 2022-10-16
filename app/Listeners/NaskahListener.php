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
                $res = DB::table('penerbitan_naskah_history')->insert([
                    'naskah_id' => $data['naskah_id'],
                    'judul_asli_his' => $data['judul_asli_his'],
                    'judul_asli_new' => $data['judul_asli_new'],
                    'email_his' => $data['email_his'],
                    'email_new' => $data['email_new'],
                    'kelompok_buku_his' => $data['kelompok_buku_his'],
                    'kelompok_buku_new' => $data['kelompok_buku_new'],
                    'tgl_masuk_nas_his' => $data['tgl_masuk_nas_his'],
                    'tgl_masuk_nas_new' => $data['tgl_masuk_nas_new'],
                    'tentang_penulis_his' => $data['tentang_penulis_his'],
                    'tentang_penulis_new' => $data['tentang_penulis_new'],
                    'hard_copy_his' => $data['hard_copy_his'],
                    'hard_copy_new' => $data['hard_copy_new'],
                    'soft_copy_his' => $data['soft_copy_his'],
                    'soft_copy_new' => $data['soft_copy_new'],
                    'cdqr_code_his' => $data['cdqr_code_his'],
                    'cdqr_code_new' => $data['cdqr_code_new'],
                    'pic_prodev_his' => $data['pic_prodev_his'],
                    'pic_prodev_new' => $data['pic_prodev_new'],
                    'penulis_new' => $data['penulis_new'],
                    'modified_at' => $data['modified_at'],
                    'author_id' => $data['author_id']
                ]);
                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
