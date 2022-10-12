<?php

namespace App\Listeners;

use App\Events\MasterDataEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MasterDataListener
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
     * @param  \App\Events\MasterDataEvent  $event
     * @return void
     */
    public function handle(MasterDataEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Update Format Buku':
                $res = DB::table('format_buku')
                    ->where('id', $data['id'])
                    ->update([
                    'jenis_format' => $data['jenis_format'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Insert History Format Buku':
                $res = DB::table('format_buku_history')->insert([
                    'format_buku_id' => $data['format_buku_id'],
                    'jenis_format_history' => $data['jenis_format_history'],
                    'jenis_format_new' => $data['jenis_format_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Update Kelompok Buku':
                $res = DB::table('penerbitan_m_kelompok_buku')
                    ->where('id', $data['id'])
                    ->update([
                    'nama' => $data['nama'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Insert History Kelompok Buku':
                $res = DB::table('penerbitan_m_kelompok_buku_history')->insert([
                    'kelompok_buku_id' => $data['kelompok_buku_id'],
                    'kelompok_buku_history' => $data['kelompok_buku_history'],
                    'kelompok_buku_new' => $data['kelompok_buku_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Update Imprint':
                $res = DB::table('imprint')
                    ->where('id', $data['id'])
                    ->update([
                    'nama' => $data['nama'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Insert History Imprint':
                $res = DB::table('imprint_history')->insert([
                    'imprint_id' => $data['imprint_id'],
                    'imprint_history' => $data['imprint_history'],
                    'imprint_new' => $data['imprint_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Update Platform':
                $res = DB::table('platform_digital_ebook')
                    ->where('id', $data['id'])
                    ->update([
                    'nama' => $data['nama'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Insert History Platform':
                $res = DB::table('platform_digital_ebook_history')->insert([
                    'platform_id' => $data['platform_id'],
                    'platform_history' => $data['platform_history'],
                    'platform_new' => $data['platform_new'],
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
