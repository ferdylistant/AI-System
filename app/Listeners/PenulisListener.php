<?php

namespace App\Listeners;

use App\Events\penulisEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class penulisListener
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
     * @param  \App\Events\penulisEvent  $event
     * @return void
     */
    public function handle(penulisEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'History Create Penulis':
                $res = DB::table('penerbitan_penulis_history')->insert([
                    'penerbitan_penulis_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'nama_new' => $data['nama'],
                    'tempat_lahir_new' => $data['tempat_lahir'],
                    'tanggal_lahir_new' => $data['tanggal_lahir'],
                    'kewarganegaraan_new' => $data['kewarganegaraan'],
                    'alamat_domisili_new' => $data['alamat_domisili'],
                    'telepon_domisili_new' => $data['telepon_domisili'],
                    'ponsel_domisili_new' => $data['ponsel_domisili'],
                    'email_new' => $data['email'],
                    'nama_kantor_new' => $data['nama_kantor'],
                    'alamat_kantor_new' => $data['alamat_kantor'],
                    'jabatan_dikantor_new' => $data['jabatan_dikantor'],
                    'telepon_kantor_new' => $data['telepon_kantor'],
                    'sosmed_fb_new' => $data['sosmed_fb'],
                    'sosmed_ig_new' => $data['sosmed_ig'],
                    'sosmed_tw_new' => $data['sosmed_tw'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'History Update Penulis':
                $res = DB::table('penerbitan_penulis_history')->insert([
                    'penerbitan_penulis_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'nama_new' => $data['nama_new'],
                    'nama_his' => $data['nama_his'],
                    'tanggal_lahir_new' => $data['tanggal_lahir_new'],
                    'tanggal_lahir_his' => $data['tanggal_lahir_his'],
                    'tempat_lahir_new' => $data['tempat_lahir_new'],
                    'tempat_lahir_his' => $data['tempat_lahir_his'],
                    'kewarganegaraan_new' => $data['kewarganegaraan_new'],
                    'kewarganegaraan_his' => $data['kewarganegaraan_his'],
                    'alamat_domisili_new' => $data['alamat_domisili_new'],
                    'alamat_domisili_his' => $data['alamat_domisili_his'],
                    'telepon_domisili_new' => $data['telepon_domisili_new'],
                    'telepon_domisili_his' => $data['telepon_domisili_his'],
                    'ponsel_domisili_new' => $data['ponsel_domisili_new'],
                    'ponsel_domisili_his' => $data['ponsel_domisili_his'],
                    'email_new' => $data['email_new'],
                    'email_his' => $data['email_his'],
                    'nama_kantor_new' => $data['nama_kantor_new'],
                    'nama_kantor_his' => $data['nama_kantor_his'],
                    'jabatan_dikantor_new' => $data['jabatan_dikantor_new'],
                    'jabatan_dikantor_his' => $data['jabatan_dikantor_his'],
                    'alamat_kantor_new' => $data['alamat_kantor_new'],
                    'alamat_kantor_his' => $data['alamat_kantor_his'],
                    'telepon_kantor_new' => $data['telepon_kantor_new'],
                    'telepon_kantor_his' => $data['telepon_kantor_his'],
                    'sosmed_fb_new' => $data['sosmed_fb_new'],
                    'sosmed_fb_his' => $data['sosmed_fb_his'],
                    'sosmed_ig_new' => $data['sosmed_ig_new'],
                    'sosmed_ig_his' => $data['sosmed_ig_his'],
                    'sosmed_tw_new' => $data['sosmed_tw_new'],
                    'sosmed_tw_his' => $data['sosmed_tw_his'],
                    'url_tentang_penulis_new' => $data['url_tentang_penulis_new'],
                    'url_tentang_penulis_his' => $data['url_tentang_penulis_his'],
                    'bank_new' => $data['bank_new'],
                    'bank_his' => $data['bank_his'],
                    'bank_atasnama_new' => $data['bank_atasnama_new'],
                    'bank_atasnama_his' => $data['bank_atasnama_his'],
                    'no_rekening_new' => $data['no_rekening_new'],
                    'no_rekening_his' => $data['no_rekening_his'],
                    'npwp_new' => $data['npwp_new'],
                    'npwp_his' => $data['npwp_his'],
                    'ktp_new' => $data['ktp_new'],
                    'ktp_his' => $data['ktp_his'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'History Delete Penulis':
                $res = DB::table('penerbitan_penulis')
                    ->where('id', $data['id'])
                    ->update([
                        'deleted_at' => $data['deleted_at'],
                        'deleted_by' => $data['author_id']
                    ]);
                DB::table('penerbitan_penulis_history')->insert([
                    'penerbitan_penulis_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'History Restored Penulis':
                $res = DB::table('penerbitan_penulis_history')->insert([
                    'penerbitan_penulis_id' => $data['penerbitan_penulis_id'],
                    'type_history' => $data['type_history'],
                    'restored_at' => $data['restored_at'],
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
