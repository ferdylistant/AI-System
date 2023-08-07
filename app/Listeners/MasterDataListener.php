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
                DB::beginTransaction();
                $res =  DB::table('platform_digital_ebook')
                    ->where('id', $data['platform_id'])
                    ->update([
                        'deleted_at' => $data['deleted_at'],
                        'deleted_by' => $data['author_id']
                    ]);
                DB::table('platform_digital_ebook_history')->insert([
                    'platform_id' => $data['platform_id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
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
                DB::beginTransaction();
                $res = DB::table('imprint')->where('id', $data['imprint_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['author_id']
                ]);
                DB::table('imprint_history')->insert([
                    'imprint_id' => $data['imprint_id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
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
            case 'Create Sub Kelompok Buku':
                DB::beginTransaction();
                $res = DB::table('penerbitan_m_s_kelompok_buku')->insert([
                    'id' => $data['id'],
                    'kelompok_id' => $data['kelompok_id'],
                    'kode_sub' => $data['kode_sub'],
                    'nama' => $data['nama'],
                    'created_by' => $data['created_by']
                ]);
                DB::table('penerbitan_m_s_kelompok_buku_history')->insert([
                    'sub_kelompok_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'sub_create' => $data['nama'],
                    'author_id' => $data['created_by'],
                ]);
                DB::commit();
                break;
            case 'Update Sub Kelompok Buku':
                $res = DB::table('penerbitan_m_s_kelompok_buku')
                    ->where('id', $data['id'])
                    ->update([
                        'nama' => $data['nama'],
                        'kelompok_id' => $data['kelompok_id'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'Insert History Update Sub Kelompok Buku':
                $res = DB::table('penerbitan_m_s_kelompok_buku_history')->insert([
                    'sub_kelompok_id' => $data['sub_kelompok_id'],
                    'type_history' => $data['type_history'],
                    'sub_his' => $data['sub_his'],
                    'sub_new' => $data['sub_new'],
                    'kb_his' => $data['kb_his'],
                    'kb_new' => $data['kb_new'],
                    'author_id' => $data['author_id'],
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
                DB::beginTransaction();
                $res = DB::table('penerbitan_m_kelompok_buku')->where('id', $data['kelompok_buku_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['author_id']
                ]);
                DB::table('penerbitan_m_kelompok_buku_history')->insert([
                    'kelompok_buku_id' => $data['kelompok_buku_id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
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
                DB::beginTransaction();
                $res = DB::table('format_buku')->where('id', $data['format_buku_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['author_id']
                ]);
                DB::table('format_buku_history')->insert([
                    'format_buku_id' => $data['format_buku_id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
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
            //PRODUKSI
            case 'Create Master Produksi':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_master')->insert([
                    'id' => $data['id'],
                    'type' => $data['type'],
                    'nama' => $data['nama'],
                    'created_by' => $data['created_by']
                ]);
                DB::commit();
                break;
            case 'Update Master Produksi':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_master')->where('id',$data['id'])->update([
                    'nama' => $data['nama'],
                    'updated_by' => $data['updated_by']
                ]);
                DB::table('proses_produksi_master_history')->insert([
                    'type_history' => $data['type_history'],
                    'master_id' => $data['id'],
                    'nama_his' => $data['nama_his'],
                    'nama_new' => $data['nama'],
                    'author_id' => $data['updated_by'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Insert History Delete Master Produksi':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_master')->where('id', $data['master_id'])->update([
                    'deleted_at' => $data['modified_at'],
                    'deleted_by' => $data['author_id']
                ]);
                DB::table('proses_produksi_master_history')->insert([
                    'master_id' => $data['master_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Restore Master Produksi':
                DB::beginTransaction();
                $res = DB::table('proses_produksi_master')->where('id',$data['master_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                DB::table('proses_produksi_master_history')->insert([
                    'master_id' => $data['master_id'],
                    'type_history' => $data['type_history'],
                    'author_id'=> $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'Create Master Rack':
                DB::beginTransaction();
                $res = DB::table('pj_st_rack_master')->insert([
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'location' => $data['location'],
                    'created_by' => $data['created_by']
                ]);
                DB::commit();
                break;
            case 'Update Master Rack':
                $res = DB::table('pj_st_rack_master')
                    ->where('id', $data['id'])
                    ->update([
                        'nama' => $data['nama'],
                        'location' => $data['location'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'Insert History Update Rack':
                $res = DB::table('pj_st_rack_master_history')->insert([
                    'rack_id' => $data['rack_id'],
                    'type_history' => $data['type_history'],
                    'nama_his' => $data['nama_his'],
                    'nama_new' => $data['nama_new'],
                    'location_his' => $data['location_his'],
                    'location_new' => $data['location_new'],
                    'author_id' => $data['author_id'],
                ]);
                break;
            case 'Insert History Delete Rack':
                DB::beginTransaction();
                $res = DB::table('pj_st_rack_master')->where('id', $data['rack_id'])->update([
                    'deleted_at' => $data['modified_at'],
                    'deleted_by' => $data['author_id']
                ]);
                DB::table('pj_st_rack_master_history')->insert([
                    'rack_id' => $data['rack_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                ]);
                DB::commit();
                break;
            case 'Restore Rack':
                DB::beginTransaction();
                $res = DB::table('pj_st_rack_master')->where('id',$data['rack_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                DB::table('pj_st_rack_master_history')->insert([
                    'rack_id' => $data['rack_id'],
                    'type_history' => $data['type_history'],
                    'author_id'=> $data['author_id'],
                ]);
                DB::commit();
                break;
            case 'Create Master Operator Gudang':
                DB::beginTransaction();
                $res = DB::table('pj_st_op_master')->insert([
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'created_by' => $data['created_by']
                ]);
                DB::commit();
                break;
            case 'Update Master Operator Gudang':
                $res = DB::table('pj_st_op_master')
                    ->where('id', $data['id'])
                    ->update([
                        'nama' => $data['nama'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'Insert History Update Operator Gudang':
                $res = DB::table('pj_st_op_master_history')->insert([
                    'operator_id' => $data['operator_id'],
                    'type_history' => $data['type_history'],
                    'nama_his' => $data['nama_his'],
                    'nama_new' => $data['nama_new'],
                    'author_id' => $data['author_id'],
                ]);
                break;
            case 'Insert History Delete Operator Gudang':
                DB::beginTransaction();
                $res = DB::table('pj_st_op_master')->where('id', $data['operator_id'])->update([
                    'deleted_at' => $data['modified_at'],
                    'deleted_by' => $data['author_id']
                ]);
                DB::table('pj_st_op_master_history')->insert([
                    'operator_id' => $data['operator_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                ]);
                DB::commit();
                break;
            case 'Restore Operator Gudang':
                DB::beginTransaction();
                $res = DB::table('pj_st_op_master')->where('id',$data['operator_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                DB::table('pj_st_op_master_history')->insert([
                    'operator_id' => $data['operator_id'],
                    'type_history' => $data['type_history'],
                    'author_id'=> $data['author_id'],
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
