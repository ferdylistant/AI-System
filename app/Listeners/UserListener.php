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
                $res = DB::table('platform_digital_ebook')
                    ->where('id', $data['id'])
                    ->update([
                        'nama' => $data['nama'],
                        'updated_by' => $data['updated_by']
                    ]);
                break;
            case 'History Create User':
                $res = DB::table('user_history')->insert([
                    'type_history' => $data['type_history'],
                    'user_id' => $data['user_id'],
                    'nama_new' => $data['nama'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'History Update User':
                $res = DB::table('platform_digital_ebook_history')->insert([
                    'platform_id' => $data['platform_id'],
                    'type_history' => $data['type_history'],
                    'platform_history' => $data['platform_history'],
                    'platform_new' => $data['platform_new'],
                    'author_id' => $data['author_id'],
                    'modified_at' => $data['modified_at']
                ]);
                break;
            case 'History Delete User':
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
            case 'History Restored User':
                $res = DB::table('platform_digital_ebook_history')->insert([
                    'platform_id' => $data['platform_id'],
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
