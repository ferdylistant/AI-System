<?php

namespace App\Listeners;

use App\Events\UserEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserListener
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
     * @param  \App\Events\UserEvent  $event
     * @return void
     */
    public function handle(UserEvent $event)
    {
        $data = $event->data;
        switch ($data['params']) {
            case 'Create User':
                $res = DB::table('users')->insert([
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'cabang_id' => $data['cabang_id'],
                    'divisi_id' => $data['divisi_id'],
                    'jabatan_id' => $data['jabatan_id'],
                    'created_by' => $data['created_by']
                ]);
                break;
            case 'Update User':
                $res = DB::table('users')
                    ->where('id', $data['id'])
                    ->update([
                        'nama' => $data['nama'],
                        'tanggal_lahir' => $data['tanggal_lahir'],
                        'tempat_lahir' => $data['tempat_lahir'],
                        'alamat' => $data['alamat'],
                        'email' => $data['email'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'History Create User':
                $res = DB::table('user_history')->insert([
                    'type_history' => $data['type_history'],
                    'user_id' => $data['user_id'],
                    'nama_new' => $data['nama'],
                    'email_new' => $data['email'],
                    'cabang_id_new' => $data['cabang_id'],
                    'divisi_id_new' => $data['divisi_id'],
                    'jabatan_id_new' => $data['jabatan_id'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'History Update User':
                $res = DB::table('user_history')->insert([
                    'user_id' => $data['id'],
                    'type_history' => $data['type_history'],
                    'nama_his' => $data['nama_his'],
                    'nama_new' => $data['nama_new'],
                    'tanggal_lahir_his' => $data['tanggal_lahir_his'],
                    'tanggal_lahir_new' => $data['tanggal_lahir_new'],
                    'tempat_lahir_his' => $data['tempat_lahir_his'],
                    'tempat_lahir_new' => $data['tempat_lahir_new'],
                    'alamat_his' => $data['alamat_his'],
                    'alamat_new' => $data['alamat_new'],
                    'email_his' => $data['email_his'],
                    'email_new' => $data['email_new'],
                    'author_id' => $data['updated_by'],
                    'modified_at' => $data['updated_at']
                ]);
                break;
            case 'History Delete User':
                DB::beginTransaction();
                $res =  DB::table('users')
                    ->where('id', $data['user_id'])
                    ->update([
                        'deleted_at' => $data['deleted_at'],
                        'deleted_by' => $data['author_id']
                    ]);
                DB::table('user_history')->insert([
                    'user_id' => $data['user_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                DB::commit();
                break;
            case 'History Restored User':
                $res = DB::table('user_history')->insert([
                    'user_id' => $data['user_id'],
                    'type_history' => $data['type_history'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'History Status User':
                $res = DB::table('user_history')->insert([
                    'user_id' => $data['user_id'],
                    'type_history' => $data['type_history'],
                    'status_his' => $data['status_his'],
                    'status_new' => $data['status_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'Update User Job':
                $res = DB::table('user_job')
                    ->updateOrInsert(['users_id' => $data['users_id']], [
                        'users_id' => $data['users_id'],
                        'id_karyawan' => $data['id_karyawan'],
                        'level_pekerjaan_id' => $data['level_pekerjaan_id'],
                        'status_pekerjaan' => $data['status_pekerjaan'],
                        'tgl_bergabung' => $data['tgl_bergabung'],
                        'tgl_berakhir' => $data['tgl_berakhir'],
                        'approval_absensi' => $data['approval_absensi'],
                        'approval_shift' => $data['approval_shift'],
                        'approval_lembur' => $data['approval_lembur'],
                        'approval_izin_kembali' => $data['approval_izin_kembali'],
                        'approval_istirahat_telat' => $data['approval_istirahat_telat']
                    ]);
                break;
            default:
                abort(500);
                break;
        }
        return $res;
    }
}
