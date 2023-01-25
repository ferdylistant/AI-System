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
            case 'Create Platform':
                $res = DB::table('platform_digital_ebook')->insert([
                    'id' => $data['id'],
                    'nama' => $data['nama_platform'],
                    'created_by' => $data['created_by']
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
            case 'Insert History Create Platform':
                $res = DB::table('platform_digital_ebook_history')->insert([
                    'platform_id' => $data['platform_id'],
                    'type_history' => $data['type_history'],
                    'platform_name' => $data['platform_name'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Update Platform':
                $res = DB::table('platform_digital_ebook_history')->insert([
                    'platform_id' => $data['platform_id'],
                    'type_history' => $data['type_history'],
                    'platform_history' => $data['platform_history'],
                    'platform_new' => $data['platform_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Delete Platform':
                $res = DB::table('platform_digital_ebook_history')->insert([
                    'platform_id' => $data['platform_id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Restored Platform':
                $res = DB::table('platform_digital_ebook_history')->insert([
                    'platform_id' => $data['platform_id'],
                    'type_history' => $data['type_history'],
                    'restored_at' => $data['restored_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Create Imprint':
                $res = DB::table('imprint')->insert([
                    'id' => $data['id'],
                    'nama' => $data['nama_imprint'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update Imprint':
                $res = DB::table('imprint')
                    ->where('id', $data['id'])
                    ->update([
                        'nama' => $data['edit_nama'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'Insert History Create Imprint':
                $res = DB::table('imprint_history')->insert([
                    'imprint_id' => $data['imprint_id'],
                    'type_history' => $data['type_history'],
                    'imprint_name' => $data['nama_imprint'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Update Imprint':
                $res = DB::table('imprint_history')->insert([
                    'imprint_id' => $data['imprint_id'],
                    'type_history' => $data['type_history'],
                    'imprint_history' => $data['imprint_history'],
                    'imprint_new' => $data['imprint_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Delete Imprint':
                $res = DB::table('imprint_history')->insert([
                    'imprint_id' => $data['imprint_id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Restored Imprint':
                $res = DB::table('imprint_history')->insert([
                    'imprint_id' => $data['imprint_id'],
                    'type_history' => $data['type_history'],
                    'restored_at' => $data['restored_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Create Kelompok Buku':
                $res = DB::table('penerbitan_m_kelompok_buku')->insert([
                    'id' => $data['id'],
                    'kode' => $data['kode'],
                    'nama' => $data['nama_kelompok_buku'],
                    'created_by' => $data['created_by']
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
            case 'Insert History Create Kelompok Buku':
                $res = DB::table('penerbitan_m_kelompok_buku_history')->insert([
                    'kelompok_buku_id' => $data['kelompok_buku_id'],
                    'type_history' => $data['type_history'],
                    'kode' => $data['kode'],
                    'kelompok_buku_name' => $data['nama_kelompok_buku'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Update Kelompok Buku':
                $res = DB::table('penerbitan_m_kelompok_buku_history')->insert([
                    'kelompok_buku_id' => $data['kelompok_buku_id'],
                    'type_history' => $data['type_history'],
                    'kelompok_buku_history' => $data['kelompok_buku_history'],
                    'kelompok_buku_new' => $data['kelompok_buku_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Delete Kelompok Buku':
                $res = DB::table('penerbitan_m_kelompok_buku_history')->insert([
                    'kelompok_buku_id' => $data['kelompok_buku_id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Restored Kelompok Buku':
                $res = DB::table('penerbitan_m_kelompok_buku_history')->insert([
                    'kelompok_buku_id' => $data['kelompok_buku_id'],
                    'type_history' => $data['type_history'],
                    'restored_at' => $data['restored_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Create Format Buku':
                $res = DB::table('format_buku')->insert([
                    'id' => $data['id'],
                    'jenis_format' => $data['nama_format_buku'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update Format Buku':
                $res = DB::table('format_buku')
                    ->where('id', $data['id'])
                    ->update([
                        'jenis_format' => $data['jenis_format'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'Insert History Create Format Buku':
                $res = DB::table('format_buku_history')->insert([
                    'format_buku_id' => $data['format_buku_id'],
                    'type_history' => $data['type_history'],
                    'jenis_format_name' => $data['nama_format_buku'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Update Format Buku':
                $res = DB::table('format_buku_history')->insert([
                    'format_buku_id' => $data['format_buku_id'],
                    'type_history' => $data['type_history'],
                    'jenis_format_history' => $data['jenis_format_history'],
                    'jenis_format_new' => $data['jenis_format_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Delete Format Buku':
                $res = DB::table('format_buku_history')->insert([
                    'format_buku_id' => $data['format_buku_id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Restored Format Buku':
                $res = DB::table('format_buku_history')->insert([
                    'format_buku_id' => $data['format_buku_id'],
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
