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
                $res = DB::table('proses_produksi_master')->where('id', $data['id'])->update([
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
                $res = DB::table('proses_produksi_master')->where('id', $data['master_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                DB::table('proses_produksi_master_history')->insert([
                    'master_id' => $data['master_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
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
                $res = DB::table('pj_st_rack_master')->where('id', $data['rack_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                DB::table('pj_st_rack_master_history')->insert([
                    'rack_id' => $data['rack_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
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
                $res = DB::table('pj_st_op_master')->where('id', $data['operator_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                DB::table('pj_st_op_master_history')->insert([
                    'operator_id' => $data['operator_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                ]);
                DB::commit();
                break;
            case 'Create Master Harga Jual':
                DB::beginTransaction();
                $res = DB::table('pj_st_master_harga_jual')->insert([
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'created_by' => $data['created_by']
                ]);
                DB::commit();
                break;
            case 'Update Master Harga Jual':
                $res = DB::table('pj_st_master_harga_jual')
                    ->where('id', $data['id'])
                    ->update([
                        'nama' => $data['nama'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'Insert History Update Harga Jual':
                $res = DB::table('pj_st_master_harga_jual_history')->insert([
                    'master_harga_jual_id' => $data['master_harga_jual_id'],
                    'type_history' => $data['type_history'],
                    'nama_his' => $data['nama_his'],
                    'nama_new' => $data['nama_new'],
                    'author_id' => $data['author_id'],
                ]);
                break;
            case 'Insert History Delete Harga Jual':
                DB::beginTransaction();
                $res = DB::table('pj_st_master_harga_jual')->where('id', $data['master_harga_jual_id'])->update([
                    'deleted_at' => $data['modified_at'],
                    'deleted_by' => $data['author_id']
                ]);
                DB::table('pj_st_master_harga_jual_history')->insert([
                    'master_harga_jual_id' => $data['master_harga_jual_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                ]);
                DB::commit();
                break;
            case 'Restore Harga Jual':
                DB::beginTransaction();
                $res = DB::table('pj_st_master_harga_jual')->where('id', $data['master_harga_jual_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                DB::table('pj_st_master_harga_jual_history')->insert([
                    'master_harga_jual_id' => $data['master_harga_jual_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                ]);
                DB::commit();
                break;
            case 'Insert History Delete Harga Jual':
                DB::beginTransaction();
                $res = DB::table('pj_st_master_harga_jual')->where('id', $data['master_harga_jual_id'])->update([
                    'deleted_at' => $data['modified_at'],
                    'deleted_by' => $data['author_id']
                ]);
                DB::table('pj_st_master_harga_jual_history')->insert([
                    'master_harga_jual_id' => $data['master_harga_jual_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                ]);
                DB::commit();
                break;
            case 'Restore Harga Jual':
                DB::beginTransaction();
                $res = DB::table('pj_st_master_harga_jual')->where('id', $data['master_harga_jual_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                DB::table('pj_st_master_harga_jual_history')->insert([
                    'master_harga_jual_id' => $data['master_harga_jual_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                ]);
                DB::commit();
                break;
            case 'Create Golongan':
                $res = DB::table('golongan')->insert([
                    'kode' => $data['content']['kode'],
                    'nama' => $data['content']['nama'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update Golongan':
                $res = DB::table('golongan')
                    ->where('id', $data['id'])
                    ->update([
                        'nama' => $data['content']['nama_new'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'Insert History Create or Update Golongan':
                $res = DB::table('golongan_history')->insert([
                    'golongan_id' => $data['golongan_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Delete Golongan':
                DB::beginTransaction();
                $res = DB::table('golongan')->where('id', $data['golongan_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['author_id']
                ]);
                DB::table('golongan_history')->insert([
                    'golongan_id' => $data['golongan_id'],
                    'type_history' => $data['type_history'],
                    'deleted_at' => $data['deleted_at'],
                    'author_id' => $data['author_id'],
                ]);
                DB::commit();
                break;
            case 'Insert History Restored Golongan':
                $res = DB::table('golongan_history')->insert([
                    'golongan_id' => $data['golongan_id'],
                    'type_history' => $data['type_history'],
                    'restored_at' => $data['restored_at'],
                    'author_id' => $data['author_id'],
                ]);
                break;
            case 'Create Sub Golongan':
                $res = DB::table('golongan_sub')->insert([
                    'golongan_id' => $data['golongan_id'],
                    'kode' => $data['content']['kode_sub_golongan'],
                    'nama' => $data['content']['nama_sub_golongan'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update Sub Golongan':
                $res = DB::table('golongan_sub')
                    ->where('id', $data['id'])
                    ->update([
                        'golongan_id' => $data['content']['golongan_id_new'],
                        'nama' => $data['content']['nama_sub_golongan_new'],
                        'updated_by' => $data['updated_by'],
                        'updated_at' => $data['updated_at']
                    ]);
                break;
            case 'Insert History Create or Update Sub Golongan':
                $res = DB::table('golongan_sub_history')->insert([
                    'golongan_sub_id' => $data['golongan_sub_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Delete Sub Golongan':
                DB::beginTransaction();
                DB::table('golongan_sub')->where('id', $data['golongan_sub_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['author_id']
                ]);
                $res = DB::table('golongan_sub_history')->insert([
                    'golongan_sub_id' => $data['golongan_sub_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'deleted_at' => $data['deleted_at'],
                    'modified_at' => $data['deleted_at']
                ]);
                DB::commit();
                break;
            case 'Insert History Restored Sub Golongan':
                DB::beginTransaction();
                DB::table('golongan_sub')->where('id', $data['golongan_sub_id'])->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);
                $res = DB::table('golongan_sub_history')->insert([
                    'golongan_sub_id' => $data['golongan_sub_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'restored_at' => $data['restored_at'],
                    'modified_at' => $data['restored_at']
                ]);
                DB::commit();
                break;
            case 'Create Type':
                $res = DB::table('type')->insert([
                    'kode' => $data['content']['kode_type'],
                    'nama' => $data['content']['nama_type'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update Type':
                $res = DB::table('type')
                    ->where('id', $data['id'])
                    ->update([
                        'nama' => $data['content']['nama_type_new'],
                        'updated_by' => $data['updated_by'],
                        'updated_at' => $data['updated_at']
                    ]);
                break;
            case 'Insert History Create or Update Type':
                $res = DB::table('type_history')->insert([
                    'type_id' => $data['type_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Delete Type':
                DB::beginTransaction();
                DB::table('type')->where('id', $data['type_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['author_id']
                ]);
                $res = DB::table('type_history')->insert([
                    'type_id' => $data['type_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'deleted_at' => $data['deleted_at'],
                    'modified_at' => $data['deleted_at']
                ]);
                DB::commit();
                break;
            case 'Insert History Restored Type':
                DB::beginTransaction();
                DB::table('type')->where('id', $data['type_id'])->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);
                $res = DB::table('type_history')->insert([
                    'type_id' => $data['type_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'restored_at' => $data['restored_at'],
                    'modified_at' => $data['restored_at']
                ]);
                DB::commit();
                break;
            case 'Create Sub Type':
                $res = DB::table('type_sub')->insert([
                    'type_id' => $data['type_id'],
                    'kode' => $data['content']['kode_sub_type'],
                    'nama' => $data['content']['nama_sub_type'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update Sub Type':
                $res = DB::table('type_sub')
                    ->where('id', $data['id'])
                    ->update([
                        'type_id' => $data['content']['type_id_new'],
                        'nama' => $data['content']['nama_sub_type_new'],
                        'updated_by' => $data['updated_by'],
                        'updated_at' => $data['updated_at']
                    ]);
                break;
            case 'Insert History Create or Update Sub Type':
                $res = DB::table('type_sub_history')->insert([
                    'type_sub_id' => $data['type_sub_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Insert History Delete Sub Type':
                DB::beginTransaction();
                DB::table('type_sub')->where('id', $data['type_sub_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['author_id']
                ]);
                $res = DB::table('type_sub_history')->insert([
                    'type_sub_id' => $data['type_sub_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'deleted_at' => $data['deleted_at'],
                    'modified_at' => $data['deleted_at']
                ]);
                DB::commit();
                break;
            case 'Insert History Restored Sub Type':
                DB::beginTransaction();
                DB::table('type_sub')->where('id', $data['type_sub_id'])->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);
                $res = DB::table('type_sub_history')->insert([
                    'type_sub_id' => $data['type_sub_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id'],
                    'restored_at' => $data['restored_at'],
                    'modified_at' => $data['restored_at']
                ]);
                DB::commit();
                break;
            case 'Create Supplier':
                $res = DB::table('supplier')->insert([
                    'id' => $data['id'],
                    'kode' => $data['kode_supplier'],
                    'nama' => $data['nama_supplier'],
                    'alamat' => $data['alamat_supplier'],
                    'wilayah' => $data['wilayah_supplier'],
                    'sub_wilayah' => $data['sub_wilayah_supplier'],
                    'telepon' => $data['telepon_supplier'],
                    'fax' => $data['fax_supplier'],
                    'kontak' => $data['kontak_supplier'],
                    'npwp' => $data['npwp_supplier'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update Supplier':
                $res = DB::table('supplier')->where('id', $data['id'])->update([
                    'nama' => $data['nama_supplier'],
                    'alamat' => $data['alamat_supplier'],
                    'wilayah' => $data['wilayah_supplier'],
                    'sub_wilayah' => $data['sub_wilayah_supplier'],
                    'telepon' => $data['telepon_supplier'],
                    'fax' => $data['fax_supplier'],
                    'kontak' => $data['kontak_supplier'],
                    'npwp' => $data['npwp_supplier'],
                    'updated_at' => $data['updated_at'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Delete Supplier':
                $res = DB::table('supplier')->where('id', $data['supplier_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                break;
            case 'Restore Supplier':
                $res = DB::table('supplier')->where('id', $data['supplier_id'])->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);
                break;
            case 'Create Satuan':
                $res = DB::table('satuan')->insert([
                    'nama' => $data['nama_satuan'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update Satuan':
                $res = DB::table('satuan')->where('id', $data['id'])->update([
                    'nama' => $data['nama_satuan'],
                    'updated_by' => $data['updated_by']
                ]);
                break;
            case 'Delete Satuan':
                $res = DB::table('satuan')->where('id', $data['id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['deleted_by']
                ]);
                break;
            case 'Restore Satuan':
                $res = DB::table('satuan')->where('id', $data['id'])->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);
                break;
            case 'Create Rack Pembelian':
                $res = DB::table('purchase_rack_master')->insert([
                    'id' => $data['id'],
                    'nama' => $data['content']['nama_rack'],
                    'location' => $data['content']['location_rack'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update Rack Pembelian':
                $res = DB::table('purchase_rack_master')->where('id', $data['id'])->update([
                        'nama' => $data['content']['nama_rack_new'],
                        'location' => $data['content']['location_rack_new'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'Insert History Create or Update Rack Pembelian':
                $res = DB::table('purchase_rack_master_history')->insert([
                    'rack_id' => $data['rack_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id']
                ]);
                break;
            case 'Insert History Delete Rack Pembelian':
                DB::beginTransaction();
                DB::table('purchase_rack_master')->where('id', $data['rack_id'])->update([
                    'deleted_at' => $data['deleted_at'],
                    'deleted_by' => $data['author_id']
                ]);
                $res = DB::table('purchase_rack_master_history')->insert([
                    'rack_id' => $data['rack_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id']
                ]);
                DB::commit();
                break;
            case 'Insert History Restored Rack Pembelian':
                DB::beginTransaction();
                DB::table('purchase_rack_master')->where('id', $data['rack_id'])->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);
                $res = DB::table('purchase_rack_master_history')->insert([
                    'rack_id' => $data['rack_id'],
                    'type_history' => $data['type_history'],
                    'content' => $data['content'],
                    'author_id' => $data['author_id']
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
