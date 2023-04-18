<?php

namespace App\Http\Controllers\ManWeb;

use App\Events\UserEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
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
                $data = $data->orderBy('u.nama', 'asc')
                    ->select(DB::raw('
                            u.id, u.nama, u.email, (case when status  <= 1 then "Aktif" else "Non Aktif" end) as status,
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
                    ->addColumn('history', function ($data) {
                        $historyData = DB::table('user_history')->where('user_id', $data->id)->get();
                        if ($historyData->isEmpty()) {
                            return '-';
                        } else {
                            $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-nama="' . $data->nama . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                            return $date;
                        }
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
                    ->rawColumns([
                        'no',
                        'nama',
                        'email',
                        'cabang',
                        'bagian',
                        'status',
                        'history',
                        'action'
                    ])
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

    public function userTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('users as u')
                ->whereNotNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_delete', 'hapus-data-imprint');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    return $data->status == 1 ? 'Aktif' : 'Non Aktif';
                })
                ->addColumn('dihapus_pada', function ($data) {
                    if ($data->deleted_at == null) {
                        return '-';
                    } else {
                        $date = date('d M Y, H:i', strtotime($data->deleted_at));
                        return $date;
                    }
                })
                ->addColumn('dihapus_oleh', function ($data) {
                    if ($data->deleted_by == null) {
                        return '-';
                    } else {
                        $dataUser = User::where('id', $data->deleted_by)->first();
                        return $dataUser->nama;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="#"
                        class="d-block btn btn-sm btn_ResUser btn-dark btn-icon""
                        data-toggle="tooltip" title="Restore Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-restore-alt"></i> Restore</div></a>';
                    } else {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'no',
                    'nama',
                    'email',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'status',
                    'action'
                ])
                ->make(true);
        }

        return view('manweb.users.telah_dihapus', [
            'title' => 'User Telah Dihapus'
        ]);
    }

    public function selectUser(Request $request, $id)
    {
        if ($request->ajax()) {
            switch ($request->type) {
                case 'log':
                    $data = DB::table('user_log')
                        ->where('users_id', $id)
                        ->orderBy('last_login', 'desc')
                        ->get();
                    $start = 1;
                    return DataTables::of($data)
                        ->addColumn('no', function ($no) use (&$start) {
                            return $start++;
                        })
                        ->addColumn('id', function ($data) {
                            return $data->users_id;
                        })
                        ->addColumn('ip', function ($data) {
                            return $data->ip_address;
                        })
                        ->addColumn('browser', function ($data) {
                            return $data->user_agent;
                        })
                        ->addColumn('terakhir_login', function ($data) {
                            $diff = Carbon::createFromFormat('Y-m-d H:i:s', $data->last_login, 'Asia/Jakarta')->diffForHumans();
                            return $data->last_login . '<br>(' . $diff . ')';
                        })
                        ->rawColumns([
                            'no',
                            'ip',
                            'browser',
                            'terakhir_login'
                        ])
                        ->make(true);
                    break;
                case 'access':
                    if (Gate::allows('do_update', 'ubah-data-user')) {
                        $userAccess = $this->getDataPermissions($id);
                        $accBagian = collect($userAccess['accbag']);
                        $access = collect(['ls' => $userAccess['ls'], 'ld' => $userAccess['ld']]);
                        $permissions = $userAccess['perm'];
                        $html = '';
                        $html .= '<ul id="treeview" class="hummingbird-base">';
                        foreach ($accBagian as $ab) {
                            $check = $ab->checked ? 'checked' : '';
                            $html .= '<li>
                                <i class="fa fa-minus"></i>
                                <label>
                                    <input id="xnode-' . $ab->id . '" data-id="custom-' . $ab->id . '" type="checkbox" ' . $check . ' /> ' . $ab->name . '
                                </label>
                                <ul style="display:block">';
                            foreach ($access['ls'] as $ls) {
                                if ($ab->id === $ls->bagian_id) {
                                    $check = $ls->checked ? 'checked' : '';
                                    $html .= '<li>
                                            <i class="fa fa-plus"></i>
                                            <label>
                                                <input id="xnode-' . $ab->id . '-' . $ls->id . '" data-id="custom-' . $ab->id . '-' . $ls->id . '" type="checkbox" ' . $check . '/> ' . $ls->name . '
                                            </label>';

                                    if ($ls->url == '#') {
                                        $html .= '<ul>';
                                        foreach ($access['ld'] as $ld) {
                                            if ($ls->id === $ld->parent_id) {
                                                $check = $ld->checked ? 'checked' : '';
                                                $html .= '<li>
                                                <i class="fa fa-plus"></i>
                                                <label>
                                                    <input id="xnode-' . $ab->id . '-' . $ls->id . '-' . $ld->id . '" data-id="custom-' . $ab->id . '-' . $ls->id . '-' . $ld->id . '" type="checkbox" ' . $check . '/> ' . $ld->name . '
                                                </label>

                                                <ul>';
                                                foreach ($permissions as $p) {
                                                    switch ($p->type) {
                                                        case 'Read':
                                                            $iconld = 'fas fa-envelope-open-text';
                                                            break;
                                                        case 'Create':
                                                            $iconld = 'fas fa-plus-square';
                                                            break;
                                                        case 'Update':
                                                            $iconld = 'fas fa-edit';
                                                            break;
                                                        case 'Delete':
                                                            $iconld = 'fas fa-trash-alt';
                                                            break;
                                                        case 'Approval':
                                                            $iconld = 'fas fa-check-circle';
                                                            break;
                                                        default:
                                                            $iconld = 'fas fa-question-circle';
                                                            break;
                                                    }
                                                    if ($ld->id == $p->access_id) {
                                                        $check = $p->checked ? 'checked' : '';
                                                        $html .= '<li>
                                                        <i class="' . $iconld . '"></i>
                                                        <label>
                                                            <input id="xnode-' . $ab->id . '-' . $ls->id . '-' . $ld->id . '-' . $p->id . '" data-id="custom-' . $ab->id . '-' . $ls->id . '-' . $ld->id . '-' . $p->id . '" value="' . $p->id . '" name="access[]" type="checkbox" ' . $check . '/> ' . $p->name . '
                                                        </label>
                                                    </li>';
                                                    }
                                                }

                                                $html .= '</ul>
                                            </li>';
                                            }
                                        }
                                        $html .= '</ul>';
                                    } else {
                                        $html .= '<ul>';
                                        foreach ($permissions as $p) {
                                            switch ($p->type) {
                                                case 'Read':
                                                    $iconls = 'fas fa-envelope-open-text';
                                                    break;
                                                case 'Create':
                                                    $iconls = 'fas fa-plus-square';
                                                    break;
                                                case 'Update':
                                                    $iconls = 'fas fa-edit';
                                                    break;
                                                case 'Delete':
                                                    $iconls = 'fas fa-trash-alt';
                                                    break;
                                                case 'Approval':
                                                    $iconld = 'fas fa-check-circle';
                                                    break;
                                                default:
                                                    $iconls = 'fas fa-question-circle';
                                                    break;
                                            }
                                            if ($ls->id == $p->access_id) {
                                                $check = $p->checked ? 'checked' : '';
                                                $html .= '<li>
                                                <i class="' . $iconls . '"></i>
                                                <label>
                                                    <input id="xnode-' . $ab->id . '-' . $ls->id . '-' . $p->id . '" data-id="custom-' . $ab->id . '-' . $ls->id . '-' . $p->id . '" value="' . $p->id . '" name="access[]" type="checkbox" ' . $check . '/> ' . $p->name . '
                                                </label>
                                            </li>';
                                            }
                                        }


                                        $html .= '</ul>';
                                    }
                                    $html .= '</li>';
                                }
                            }
                            $html .= '</ul>
                            </li>';
                        }
                        $html .= '</ul>';
                        return $html;
                    }
                    return;
                    break;
                default:
                    return abort(400);
                    break;
            }
        }
        $urlPrev = url()->previous();
        $urlPrev = explode('/', $urlPrev);
        $btnPrev = end($urlPrev) == 'users' ? true : false;
        if ($id == 'be8d42fa88a14406ac201974963d9c1b' and auth()->id() != 'be8d42fa88a14406ac201974963d9c1b') {
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
        // dd($user = collect($user));
        $user = collect($user)->map(function ($item, $key) {
            switch ($key) {
                case 'tanggal_lahir':
                    return $item != '' ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : NULL;
                    break;
                case 'created_at':
                    return $item != '' ? Carbon::parse($item)->translatedFormat('d F Y, H:i') : '-';
                    break;
                case 'created_by':
                    return $item != '' ? DB::table('users')->where('id', $item)->first()->nama : '-';
                    break;
                case 'updated_at':
                    return $item != '' ? Carbon::parse($item)->translatedFormat('d F Y, H:i') : '-';
                    break;
                case 'updated_by':
                    return $item != '' ? DB::table('users')->where('id', $item)->first()->nama : '-';
                    break;
                default:
                    return $item;
                    break;
            }
        })->all();

        if (Gate::allows('do_update', 'ubah-data-user')) {

            return view('manweb.users.user-detail', [
                'btnPrev' => $btnPrev,
                'user' => (object)$user, 'lcab' => $lcab, 'ldiv' => $ldiv, 'ljab' => $ljab,
                'userStatus' => $userStatus,
                'queryStatus' => $q->status,
                'title' => 'User Detail',
            ]);
        } else {
            return view('manweb.users.user-detail', [
                'btnPrev' => $btnPrev,
                'user' => (object)$user,
                'userStatus' => $userStatus,
                'queryStatus' => $q->status,
                'title' => 'Detail User'
            ]);
        }
    }

    public function ajaxUser(Request $request)
    {
        switch ($request->act) {
            case 'create':
                return $this->createUser($request);
                break;
            case 'update':
                return $this->updateUser($request);
                break;
            case 'update-status-user':
                return $this->updateUserStatus($request);
                break;
            case 'delete':
                return $this->deleteUser($request);
                break;
            case 'update-password':
                return $this->updatePassword($request);
                break;
            case 'update-access':
                return $this->updateAccess($request);
                break;
            case 'save-image':
                return $this->storeImage($request);
                break;
            default:
                return abort(404);
                break;
        }
    }

    protected function createUser($request)
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

        try {
            DB::beginTransaction();
            $create = [
                'params' => 'Create User',
                'id' => $id,
                'nama' => $request->input('adduser_nama'),
                'email' => $request->input('adduser_email'),
                'password' => Hash::make($password),
                'cabang_id' => $request->input('adduser_cabang'),
                'divisi_id' => $request->input('adduser_divisi'),
                'jabatan_id' => $request->input('adduser_jabatan'),
                'created_by' => auth()->id()
            ];
            event(new UserEvent($create));
            $history = [
                'params' => 'History Create User',
                'type_history' => 'Create',
                'user_id' => $id,
                'nama' => $request->input('adduser_nama'),
                'email' => $request->input('adduser_email'),
                'cabang_id' => $request->input('adduser_cabang'),
                'divisi_id' => $request->input('adduser_divisi'),
                'jabatan_id' => $request->input('adduser_jabatan'),
                'author_id' => auth()->id(),
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            event(new UserEvent($history));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data pengguna berhasil ditambahkan!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return;
    }
    protected function storeImage($request)
    {
        try {
            $user = DB::table('users')->where('id', $request->id)->first();

            $image_parts = explode(";base64,", $request->cropped_image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1]; //png
            $image_base64 = base64_decode($image_parts[1]);
            $avatar = uniqid() . '.' . $image_type;
            $imageFullPath = Storage::path('users/' . $user->id . '/' . $avatar);

            file_put_contents($imageFullPath, $image_base64);
            DB::table('users')->where('id', $user->id)
                ->update([
                    'avatar' => $avatar,
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            Storage::delete('users/' . $user->id . '/' . $user->avatar);
            return response()->json([
                'status' => 'success',
                'message' => 'Foto profil berhasil diperbarui!',
                'path' => url('storage/users/' . $user->id . '/' . $avatar)
            ]);
        } catch (\Exception $e) {
            if ($avatar !== $user->avatar) {
                Storage::delete('users/' . $user->id . '/' . $avatar);
            }
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function updateUser($request)
    {
        $tgl_lahir = is_null($request->input('uedit_tanggal_lahir')) ? $request->input('uedit_tanggal_lahir')
            : Carbon::createFromFormat('d F Y', $request->input('uedit_tanggal_lahir'))->format('Y-m-d');

        try {
            // $user = DB::table('users')->where('id', $request->input('uedit_id'))->first();
            $history = DB::table('users')
                ->where('id', $request->input('uedit_id'))
                ->first();
            if (Gate::allows('do_update', 'ubah-data-user')) {
                $request->validate([
                    'uedit_nama' => 'required',
                    'uedit_email' => 'required|unique:users,email,' . $request->input('uedit_id'),
                    'uedit_cabang' => 'required',
                    'uedit_divisi' => 'required',
                    'uedit_jabatan' => 'required',
                ]);

                $update = [
                    'params' => 'Update User',
                    'id' => $request->input('uedit_id'),
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
                ];
                $nama = event(new UserEvent($update));
                if (array_sum($nama) > 0) {
                    $insert = [
                        'params' => 'History Update User',
                        'type_history' => 'Update',
                        'id' => $request->input('uedit_id'),
                        'nama_his' => $history->nama == $request->input('uedit_nama') ? null : $history->nama,
                        'nama_new' => $history->nama == $request->input('uedit_nama') ? null : $request->input('uedit_nama'),
                        'tanggal_lahir_his' => $history->tanggal_lahir == $tgl_lahir ? null : $history->tanggal_lahir,
                        'tanggal_lahir_new' => $history->tanggal_lahir == $tgl_lahir ? null : $tgl_lahir,
                        'tempat_lahir_his' => $history->tempat_lahir == $request->input('uedit_tempat_lahir') ? null : $history->tempat_lahir,
                        'tempat_lahir_new' => $history->tempat_lahir == $request->input('uedit_tempat_lahir') ? null : $request->input('uedit_tempat_lahir'),
                        'alamat_his' => $history->alamat == $request->input('uedit_alamat') ? null : $history->alamat,
                        'alamat_new' => $history->alamat == $request->input('uedit_alamat') ? null : $request->input('uedit_alamat'),
                        'email_his' => $history->email == $request->input('uedit_email') ? null : $history->email,
                        'email_new' => $history->email == $request->input('uedit_email') ? null : $request->input('uedit_email'),
                        'cabang_id_his' => $history->cabang_id == $request->uedit_cabang ? null : $history->cabang_id,
                        'cabang_id_new' => $history->cabang_id == $request->input('uedit_cabang') ? null : $request->input('uedit_cabang'),
                        'divisi_id_his' => $history->divisi_id == $request->input('uedit_divisi') ? null : $history->divisi_id,
                        'divisi_id_new' => $history->divisi_id == $request->input('uedit_divisi') ? null : $request->input('uedit_divisi'),
                        'jabatan_id_his' => $history->jabatan_id == $request->input('uedit_jabatan') ? null : $history->jabatan_id,
                        'jabatan_id_new' => $history->jabatan_id == $request->input('uedit_jabatan') ? null : $request->input('uedit_jabatan'),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    event(new UserEvent($insert));
                }
            } else {
                $request->validate([
                    'uedit_nama' => 'required',
                    'uedit_email' => 'required|unique:users,email,' . $request->input('uedit_id'),
                ]);

                $update = [
                    'params' => 'Update User',
                    'id' => $request->input('uedit_id'),
                    'nama' => $request->input('uedit_nama'),
                    'tanggal_lahir' => $tgl_lahir,
                    'tempat_lahir' => $request->input('uedit_tempat_lahir'),
                    'alamat' => $request->input('uedit_alamat'),
                    'email' => $request->input('uedit_email'),
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                event(new UserEvent($update));
            }
        } catch (\Exception $e) {
            return abort(500, $e->getMessage());
        }
        return;
    }
    protected function updateUserStatus($request)
    {
        try {
            $proses = $request->proses;
            $id = $request->id;
            if ($proses == 1) {
                $class = 'badge badge-success';
                $label  = 'Aktif';
            } else {
                $class = 'badge badge-danger';
                $label  = 'Nonaktif';
            }
            $history = DB::table('users')
                ->where('id', $id)
                ->first();
            DB::table('users')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update(['status' => $proses]);
            // return response()->json($proses);
            // dd();
            $insert = [
                'params' => 'History Status User',
                'user_id' => $id,
                'type_history' => 'Status',
                'status_his' => $history->status,
                'status_new' => $proses,
                'author_id' => auth()->id(),
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            event(new UserEvent($insert));

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah status pengguna',
                'data' => '<span class="' . $class . '">' . $label . '</span>'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }
    protected function updatePassword($request)
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

    protected function deleteUser($request)
    {
        try {
            $id = $request->id;
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $insert = [
                'params' => 'History Delete User',
                'user_id' => $id,
                'type_history' => 'Delete',
                'deleted_at' => $tgl,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            event(new UserEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data user!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    protected function updateAccess($request)
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

    public function lihatHistoryUser(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('user_history as uh')
                ->join('users as u', 'u.id', '=', 'uh.author_id')
                ->where('uh.user_id', $id)
                ->select(
                    'uh.*',
                    'u.nama'
                )
                ->orderBy('uh.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Create':
                        $html .= '<span class="ticket-item" id="newAppend">';

                        if (!is_null($d->nama_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> User dengan nama <b class="text-dark">' .
                                $d->nama_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->email_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Email <b class="text-dark">' .
                                $d->email_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        $html .=
                            '<div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' .
                            url('/manajemen-web/user/' . $d->author_id) .
                            '">' .
                            $d->nama .
                            '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' .
                            Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() .
                            ' (' .
                            Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') .
                            ')</div>
                    </div>
                    </span>';
                        break;
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">';

                        if (!is_null($d->nama_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> User <b class="text-dark">' .
                                $d->nama_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->nama_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->nama_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> User <b class="text-dark">' .
                                $d->nama_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->email_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Email <b class="text-dark">' .
                                $d->email_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->email_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->email_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Email <b class="text-dark">' .
                                $d->email_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->tanggal_lahir_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Tanggal lahir <b class="text-dark">' .
                                $d->tanggal_lahir_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->tanggal_lahir_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->tanggal_lahir_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Tanggal lahir <b class="text-dark">' .
                                $d->tanggal_lahir_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->tempat_lahir_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Tempat lahir <b class="text-dark">' .
                                $d->tempat_lahir_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->tempat_lahir_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->tempat_lahir_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Tempat lahir <b class="text-dark">' .
                                $d->tempat_lahir_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->telepon_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Telepon <b class="text-dark">' .
                                $d->telepon_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->telepon_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->telepon_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Telepon <b class="text-dark">' .
                                $d->telepon_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->alamat_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Alamat <b class="text-dark">' .
                                $d->alamat_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->alamat_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->alamat_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Alamat <b class="text-dark">' .
                                $d->alamat_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->cabang_id_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Cabang <b class="text-dark">' .
                                DB::table('cabang')->where('id', $d->cabang_id_his)->first()->nama .
                                '</b> diubah menjadi <b class="text-dark">' .
                                DB::table('cabang')->where('id', $d->cabang_id_new)->first()->nama .
                                '</b> </span></div>';
                        } elseif (!is_null($d->cabang_id_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Cabang <b class="text-dark">' .
                                DB::table('cabang')->where('id', $d->cabang_id_new)->first()->nama .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->divisi_id_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Divisi <b class="text-dark">' .
                                DB::table('divisi')->where('id', $d->divisi_id_his)->first()->nama .
                                '</b> diubah menjadi <b class="text-dark">' .
                                DB::table('divisi')->where('id', $d->divisi_id_new)->first()->nama .
                                '</b> </span></div>';
                        } elseif (!is_null($d->divisi_id_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Divisi <b class="text-dark">' .
                                DB::table('divisi')->where('id', $d->divisi_id_new)->first()->nama .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->jabatan_id_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Jabatan <b class="text-dark">' .
                                DB::table('jabatan')->where('id', $d->jabatan_id_his)->first()->nama .
                                '</b> diubah menjadi <b class="text-dark">' .
                                DB::table('jabatan')->where('id', $d->jabatan_id_new)->first()->nama .
                                '</b> </span></div>';
                        } elseif (!is_null($d->jabatan_id_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Jabatan <b class="text-dark">' .
                                DB::table('jabatan')->where('id', $d->jabatan_id_new)->first()->nama .
                                '</b> ditambahkan. </span></div>';
                        }
                        $html .=
                            '<div class="ticket-info">
                    <div class="text-muted pt-2">Modified by <a href="' .
                            url('/manajemen-web/user/' . $d->author_id) .
                            '">' .
                            $d->nama .
                            '</a></div>
                    <div class="bullet pt-2"></div>
                    <div class="pt-2">' .
                            Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() .
                            ' (' .
                            Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') .
                            ')</div>
                </div>
                </span>';
                        break;
                    case 'Delete':
                        $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Data user dihapus pada <b class="text-dark">' . Carbon::parse($d->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
                            </div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . ')</div>
                            </div>
                            </span>';
                        break;
                    case 'Restore':
                        $html .= '<span class="ticket-item" id="newAppend">
                                <div class="ticket-title">
                                    <span><span class="bullet"></span> Data user direstore pada <b class="text-dark">' . Carbon::parse($d->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
                                </div>
                                <div class="ticket-info">
                                    <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                    <div class="bullet pt-2"></div>
                                    <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                                </div>
                                </span>';
                        break;
                    case 'Status':
                        $html .= '<span class="ticket-item" id="newAppend">
                                    <div class="ticket-title">
                                        <span><span class="bullet"></span> Data status user diubah dari <b class="text-dark">' . ($d->status_his == 1 ? 'Aktif' : 'Non Aktif') . '</b> menjadi <b class="text-dark">' . ($d->status_new == 1 ? 'Aktif' : 'Non Aktif') . '</b>.</span>
                                    </div>
                                    <div class="ticket-info">
                                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                        <div class="bullet pt-2"></div>
                                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                                    </div>
                                    </span>';
                        break;
                }
            }
            return $html;
        }
    }

    public function restoreUser(Request $request)
    {
        $id = $request->id;
        $restored = DB::table('users')
            ->where('id', $id)
            ->update(['deleted_at' => null, 'deleted_by' => null]);
        $insert = [
            'params' => 'History Restored User',
            'user_id' => $id,
            'type_history' => 'Restore',
            'restored_at' => now(),
            'author_id' => auth()->user()->id,
            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
        ];
        event(new UserEvent($insert));
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengembalikan user!'
        ]);
    }
}
