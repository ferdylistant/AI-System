<?php

namespace App\Http\Controllers\ManWeb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Gate, Storage};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->input('request_') === 'table-users') {
                $data = DB::table('users as u')
                    ->leftJoin('cabang as c', 'u.cabang_id', '=', 'c.id')
                    ->leftJoin('divisi as d', 'u.divisi_id', '=', 'd.id')
                    ->leftJoin('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                    ->whereNull('u.deleted_at');
                if (auth()->id() != 'be8d42fa88a14406ac201974963d9c1b') { // Only Super Admin
                    $data = $data->where('u.id', '<>', 'be8d42fa88a14406ac201974963d9c1b');
                }
                $data = $data->orderBy('u.nama','asc')
                    ->select(DB::raw('
                            u.id, u.nama, u.email, (case when status > 0 then "Aktif" else "Non Aktif" end) as status,
                            c.nama as cabang, d.nama as divisi, j.nama as jabatan
                        '))
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('bagian', function ($data) {
                        if (is_null($data->divisi) or is_null($data->divisi)) {
                            return null;
                        }
                        return $data->divisi . ' - ' . $data->jabatan;
                    })
                    ->addColumn('action', function ($data) {
                        $btn = '<a href="' . url('manajemen-web/user/' . $data->id) . '"
                                    class="d-block btn btn-sm btn-primary mr-1"  data-toggle="tooltip" title="Lihat Data">
                                    <div><i class="fa fa-folder-open"></i></div></a>';
                        $btn .= '<a href="#" class="btn_DelUser d-block btn btn-sm btn-danger mr-1 mt-1"  data-toggle="tooltip" title="Hapus Data"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                                    <div><i class="fa fa-trash"></i></div></a>';
                        return $btn;
                    })
                    ->make(true);
            }
        }

        $lcabang = DB::table('cabang')->whereNull('deleted_at')
            ->select('id', 'nama')->get();
        $ldivisi = DB::table('divisi')->whereNull('deleted_at')
            ->select('id', 'nama')->get();
        $ljabatan = DB::table('jabatan')->whereNull('deleted_at')
            ->select('id', 'nama')->get();


        return view('manweb.users.index', [
            'title' => 'Users',
            'lcabang' => $lcabang,
            'ldivisi' => $ldivisi,
            'ljabatan' => $ljabatan,
        ]);
    }

    public function selectUser(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = DB::table('user_log')
                ->where('users_id',$id)
                ->orderBy('last_login','desc')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('ip', function ($data) {
                    return $data->ip_address;
                })
                ->addColumn('browser', function ($data) {
                    return $data->user_agent;
                })
                ->addColumn('terakhir_login', function ($data) {
                    return $data->last_login;
                })
                ->rawColumns([
                    'no',
                    'ip',
                    'browser',
                    'terakhir_login'
                ])
                ->make(true);
        }
        $urlPrev = url()->previous();
        $urlPrev = explode('/', $urlPrev);
        $btnPrev = end($urlPrev) == 'users' ? true : false;
        if($id == 'be8d42fa88a14406ac201974963d9c1b' AND auth()->id() != 'be8d42fa88a14406ac201974963d9c1b') {
            abort(403);
        }
        $q = DB::table('users')->where('id', $id)->first('status');
        $userStatus = $q->status == 1 ? 'Aktif' : 'Tidak Aktif';

        if (Gate::allows('do_update', 'ubah-data-user')) {
            $user = DB::table('users')->whereNull('deleted_at')
                ->where('id', $id)->first();
            $lcab = DB::table('cabang')->whereNull('deleted_at')->get();
            $ldiv = DB::table('divisi')->whereNull('deleted_at')->get();
            $ljab = DB::table('jabatan')->whereNull('deleted_at')->get();
        } else {
            $user = DB::table('users as u')
                ->leftJoin('cabang as c', 'u.cabang_id', '=', 'c.id')
                ->leftJoin('divisi as d', 'u.divisi_id', '=', 'd.id')
                ->leftJoin('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                ->select(DB::raw('u.*, c.nama as cabang, d.nama as divisi, j.nama as jabatan'))
                ->whereNull('u.deleted_at')
                ->where('u.id', $id)
                ->first();
        }

        $user = collect($user)->map(function ($item, $key) {
            if ($key == 'tanggal_lahir') {
                return $item != '' ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y')
                    : $item;
            } else {
                return $item;
            }
        })->all();

        if (Gate::allows('do_update', 'ubah-data-user')) {
            $userAccess = $this->getDataPermissions($id);

            return view('manweb.users.user-detail', [
                'btnPrev' => $btnPrev,
                'user' => (object)$user, 'lcab' => $lcab, 'ldiv' => $ldiv, 'ljab' => $ljab,
                'userStatus' => $userStatus,
                'accBagian' => collect($userAccess['accbag']),
                'access' => collect(['ls' => $userAccess['ls'], 'ld' => $userAccess['ld']]),
                'permissions' => $userAccess['perm'],
                'title' => 'User Detail',
            ]);
        } else {
            return view('manweb.users.user-detail', [
                'btnPrev' => $btnPrev,
                'user' => (object)$user,
                'userStatus' => $userStatus,
                'title' => 'Detail User'
            ]);
        }
    }

    public function ajaxUser(Request $request)
    {
        if ($request->act == 'create') {
            return $this->createUser($request);
        } elseif ($request->act == 'update') {
            return $this->updateUser($request);
        } elseif ($request->act == 'delete') {
            return $this->deleteUser($request);
        } elseif ($request->act == 'update-password') {
            return $this->updatePassword($request);
        } elseif ($request->act == 'update-access') {
            return $this->updateAccess($request);
        } else {
            return abort(404);
        }
    }

    protected function createUser(Request $request)
    {
        $request->validate([
            'adduser_nama' => 'required',
            'adduser_email' => 'required|unique:users,email',
            'adduser_cabang' => 'required',
            'adduser_divisi' => 'required',
            'adduser_jabatan' => 'required',
        ]);

        $password = is_null($request->input('adduser_password')) ? 'password'
            : $request->input('adduser_password');
        $id = Str::uuid()->getHex();

        Storage::copy("users/avatars/default.jpg", "users/" . $id . "/default.jpg");

        DB::table('users')->insert([
            'id' => $id,
            'nama' => $request->input('adduser_nama'),
            'email' => $request->input('adduser_email'),
            'password' => Hash::make($password),
            'cabang_id' => $request->input('adduser_cabang'),
            'divisi_id' => $request->input('adduser_divisi'),
            'jabatan_id' => $request->input('adduser_jabatan'),
            'created_by' => auth()->id()
        ]);

        return;
    }

    protected function updateUser(Request $request)
    {
        $tgl_lahir = is_null($request->input('uedit_tanggal_lahir')) ? $request->input('uedit_tanggal_lahir')
            : Carbon::createFromFormat('d F Y', $request->input('uedit_tanggal_lahir'))->format('Y-m-d');

        try {
            $user = DB::table('users')->where('id', $request->input('uedit_id'))->first();

            $avatar = $user->avatar;
            if (!is_null($request->file('uedit_pp'))) {
                $avatar = explode('/', $request->file('uedit_pp')->store('users/' . $user->id . '/'));
                $avatar = end($avatar);
            }

            if (Gate::allows('do_update', 'ubah-data-user')) {
                $request->validate([
                    'uedit_nama' => 'required',
                    'uedit_email' => 'required|unique:users,email,' . $request->input('uedit_id'),
                    'uedit_cabang' => 'required',
                    'uedit_divisi' => 'required',
                    'uedit_jabatan' => 'required',
                ]);

                DB::table('users')->where('id', $request->input('uedit_id'))
                    ->update([
                        'avatar' => $avatar,
                        'nama' => $request->input('uedit_nama'),
                        'tanggal_lahir' => $tgl_lahir,
                        'tempat_lahir' => $request->input('uedit_tempat_lahir'),
                        'alamat' => $request->input('uedit_alamat'),
                        'email' => $request->input('uedit_email'),
                        'cabang_id' => $request->input('uedit_cabang'),
                        'divisi_id' => $request->input('uedit_divisi'),
                        'jabatan_id' => $request->input('uedit_jabatan'),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            } else {
                $request->validate([
                    'uedit_nama' => 'required',
                    'uedit_email' => 'required|unique:users,email,' . $request->input('uedit_id'),
                ]);

                DB::table('users')->where('id', $request->input('uedit_id'))
                    ->update([
                        'avatar' => $avatar,
                        'nama' => $request->input('uedit_nama'),
                        'tanggal_lahir' => $tgl_lahir,
                        'tempat_lahir' => $request->input('uedit_tempat_lahir'),
                        'alamat' => $request->input('uedit_alamat'),
                        'email' => $request->input('uedit_email'),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
        } catch (\Exception $e) {
            if ($avatar !== $user->avatar) {
                Storage::delete('users/' . $user->id . '/' . $avatar);
            }
            return abort(500, $e->getMessage());
        }

        if (!is_null($request->file('uedit_pp'))) {
            Storage::delete('users/' . $user->id . '/' . $user->avatar);
        }
        return;
    }

    protected function updatePassword(Request $request)
    {
        if (auth()->user()->id != $request->input('uedit_pwd_id')) {
            return abort(403);
        }
        $user = DB::table('users')->where('id', $request->input('uedit_pwd_id'))->first();
        if (is_null($user)) {
            return abort(404);
        }

        $request->validate([
            'uedit_pwd_old' => ['required', function ($attr, $val, $fail) use ($request, $user) {
                if (!Hash::check($request->input('uedit_pwd_old'), $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }]
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->input('uedit_pwd_new'))
        ]);
        return;
    }

    protected function deleteUser(Request $request)
    {
        DB::table('users')->where('id', $request->input('id'))
            ->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => auth()->id()
            ]);
        return;
    }

    protected function updateAccess(Request $request)
    {
        DB::transaction(function () use ($request) {
            try {
                DB::table('user_permission')
                    ->where('user_id', $request->input('user_id'))
                    ->delete();

                if ($request->has('access')) {
                    foreach ($request->input('access') as $a) {
                        $access[] = [
                            'user_id' => $request->input('user_id'),
                            'permission_id' => $a
                        ];
                    }

                    DB::table('user_permission')->insert($access);
                }

                DB::commit();
                return;
            } catch (\Exception $e) {
                DB::rollBack();
                return abort(500);
            }
        });
    }

    protected function getDataPermissions($id)
    {
        $userPermissions = DB::table('user_permission')
            ->where('user_id', $id)
            ->get();;
        $accessBagian   = DB::table('access_bagian')
            ->orderBy('order_ab', 'asc')
            ->get();
        $access         = DB::table('access')
            ->select(
                'id',
                'parent_id',
                'bagian_id',
                'level',
                'order_menu',
                'url',
                'name'
            )
            ->orderBy('order_menu', 'asc')
            // ->orderBy('level', 'desc')
            ->get();
        $permissions    = DB::table('permissions as p')
            ->join('access as a', 'p.access_id', '=', 'a.id')
            ->select(DB::raw('p.*, a.bagian_id'))
            ->get();

        $temp = 0;
        foreach ($permissions as $p) {
            foreach ($userPermissions as $up) {
                if ($p->id == $up->permission_id) {
                    $perm[] = (object)[
                        'id' => $p->id,
                        'access_id' => $p->access_id,
                        'url' => $p->url,
                        'type' => $p->type,
                        'name' => $p->name,
                        'bagian_id' => $p->bagian_id,
                        'checked' => true
                    ];
                    $temp += 1;
                    break;
                }
            }
            if ($temp < 1) {
                $perm[] = (object)[
                    'id' => $p->id,
                    'access_id' => $p->access_id,
                    'url' => $p->url,
                    'type' => $p->type,
                    'name' => $p->name,
                    'bagian_id' => $p->bagian_id,
                    'checked' => false
                ];
            }
            $temp = 0;
        }

        $ld = [];
        foreach ($access as $a) {
            if ($a->level == 2) {
                foreach ($perm as $p) {
                    if ($a->id == $p->access_id and $p->checked) {
                        $a->checked = true;
                        $ld[] = $a;
                        $temp += 1;
                        break;
                    }
                }
                if ($temp < 1) {
                    $a->checked = false;
                    $ld[] = $a;
                }
            } elseif ($a->level == 1 and $a->url == '#') {
                foreach ($ld as $d) {
                    if ($a->id == $d->parent_id and $d->checked) {
                        $a->checked = true;
                        $ls[] = $a;
                        $temp += 1;
                        break;
                    }
                }
                if ($temp < 1) {
                    $a->checked = false;
                    $ls[] = $a;
                }
            } elseif ($a->level == 1 and $a->url != '#') {
                foreach ($perm as $p) {
                    if ($a->id == $p->access_id and $p->checked) {
                        $a->checked = true;
                        $ls[] = $a;
                        $temp += 1;
                        break;
                    }
                }
                if ($temp < 1) {
                    $a->checked = false;
                    $ls[] = $a;
                }
            } else {
                continue;
            }
            $temp = 0;
        }

        foreach ($accessBagian as $ab) {
            foreach ($ls as $s) {
                if ($ab->id == $s->bagian_id and $s->checked) {
                    $accbag[] = (object)[
                        'id' => $ab->id,
                        'name' => $ab->name,
                        'checked' => true
                    ];
                    $temp += 1;
                    break;
                }
            }
            if ($temp < 1) {
                $accbag[] = (object)[
                    'id' => $ab->id,
                    'name' => $ab->name,
                    'checked' => false
                ];
            }
            $temp = 0;
        }
        // dd($ld);
        return [
            'accbag' => $accbag,
            'ls' => $ls,
            'ld' => $ld,
            'perm' => $perm
        ];
    }
}
