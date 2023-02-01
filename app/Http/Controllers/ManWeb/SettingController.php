<?php

namespace App\Http\Controllers\ManWeb;

use Illuminate\Support\Str;
use App\Events\SettingEvent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->id() != 'be8d42fa88a14406ac201974963d9c1b') {
            abort(403);
        }
        if ($request->ajax()) {
            switch ($request->input('request_')) {
                case 'table-section':
                    $table = DB::table('access_bagian')
                        ->orderBy('order_ab', 'asc')
                        ->get();

                    return Datatables::of($table)
                        ->addIndexColumn()
                        ->addColumn('action', function ($data) {
                            $btn = '<a href="javascript:void(0)" class="d-block btn btn-sm btn-warning btn_EditSectionMenu btn-icon  mr-1 mt-1"
                                    data-toggle="modal" data-target="#md_EditSectionMenu" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->name . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            $btn .= '<a href="#" class="d-block btn btn-sm btn_DelCabang btn-danger btn-icon mr-1 mt-1"
                                    data-id="' . $data->id . '" data-nama="' . $data->name . '"
                                    data-toggle="tooltip" title="Hapus Data">
                                    <div><i class="fas fa-trash-alt"></i></div></a>';
                            return $btn;
                        })
                        ->make(true);
                    break;
                case 'table-menu':
                    $table = DB::table('access as a')->join('access_bagian as ab', 'ab.id', '=', 'a.bagian_id')
                        ->select([
                            'a.*',
                            'ab.name as nama_bagian'
                        ])
                        ->get();

                    return Datatables::of($table)
                        ->addIndexColumn()
                        ->addColumn('nama_bagian', function ($data) {
                            $res = '';
                            switch ($data->nama_bagian) {
                                case 'Dashboard':
                                    $res .= '<span class="badge badge-danger">' . $data->nama_bagian . '</span>';
                                    break;
                                case 'Penerbitan':
                                    $res .= '<span class="badge badge-warning">' . $data->nama_bagian . '</span>';
                                    break;
                                case 'Master Data':
                                    $res .= '<span class="badge badge-info">' . $data->nama_bagian . '</span>';
                                    break;
                                case 'Produksi':
                                    $res .= '<span class="badge badge-success">' . $data->nama_bagian . '</span>';
                                    break;
                                case 'Manajemen Web':
                                    $res .= '<span class="badge badge-primary">' . $data->nama_bagian . '</span>';
                                    break;
                                default:
                                    $res .= '<span class="badge badge-dark">' . $data->nama_bagian . '</span>';
                                    break;
                            }
                            return $res;
                        })
                        ->addColumn('action', function ($data) {
                            $btn = '<a href="javascript:void(0)" class="d-block btn btn-sm btn-warning btn_EditMenu btn-icon mr-1 mt-1"
                                    data-toggle="modal" data-target="#md_EditMenu" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->name . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            $btn .= '<a href="#" class="d-block btn btn-sm btn_DelMenu btn-danger btn-icon mr-1 mt-1"
                                    data-toggle="tooltip" title="Hapus Data"
                                    data-id="' . $data->id . '" data-nama="' . $data->name . '">
                                    <div><i class="fas fa-trash-alt"></i></div></a>';
                            return $btn;
                        })
                        ->rawColumns([
                            'nama_bagian',
                            'action'
                        ])
                        ->make(true);
                    break;
                case 'table-permission':
                    $table = DB::table('permissions as p')
                        ->join('access as a', 'a.id', '=', 'p.access_id')
                        ->join('access_bagian as ab', 'ab.id', '=', 'a.bagian_id')
                        ->select([
                            'p.*',
                            'a.name as nama_access',
                            'ab.name as nama_bagian'
                        ])
                        ->orderBy('a.name', 'ASC')
                        ->get();

                    return Datatables::of($table)
                        ->addIndexColumn()
                        ->addColumn('nama_bagian', function ($data) {
                            $res = '';
                            switch ($data->nama_bagian) {
                                case 'Dashboard':
                                    $res .= '<span class="badge badge-danger">' . $data->nama_bagian . '</span>';
                                    break;
                                case 'Penerbitan':
                                    $res .= '<span class="badge badge-warning">' . $data->nama_bagian . '</span>';
                                    break;
                                case 'Master Data':
                                    $res .= '<span class="badge badge-info">' . $data->nama_bagian . '</span>';
                                    break;
                                case 'Produksi':
                                    $res .= '<span class="badge badge-success">' . $data->nama_bagian . '</span>';
                                    break;
                                case 'Manajemen Web':
                                    $res .= '<span class="badge badge-primary">' . $data->nama_bagian . '</span>';
                                    break;
                                default:
                                    $res .= '<span class="badge badge-dark">' . $data->nama_bagian . '</span>';
                                    break;
                            }
                            return $res;
                        })
                        ->addColumn('action', function ($data) {
                            $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn_EditCabang btn-icon mr-1 mt-1"
                                    data-toggle="modal" data-target="#md_EditCabang" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->name . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            $btn .= '<a href="#" class="d-block btn btn-sm btn_DelCabang btn-danger btn-icon mr-1 mt-1"
                                    data-toggle="tooltip" title="Hapus Data"
                                    data-id="' . $data->id . '" data-nama="' . $data->name . '">
                                    <div><i class="fas fa-trash-alt"></i></div></a>';
                            return $btn;
                        })
                        ->rawColumns([
                            'nama_bagian',
                            'action'
                        ])
                        ->make(true);
                    break;
                case 'selectBagian':
                    $res = DB::table('access_bagian')->get();
                    return $res;
                    break;
                case 'selectParent':
                    $res = DB::table('access')
                        ->where('bagian_id', $request->input('id'))
                        ->where('url', '#')
                        ->whereNull('parent_id')
                        ->get();
                    return $res;
                    break;
                case 'data-menu':
                    $result = DB::table('access')
                        ->where('id', $request->input('id'))
                        ->first();
                    // $res = (object)collect($result)->map(function ($item, $key) {
                    //     switch ($key) {
                    //         case 'bagian':
                    //             return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : '-';
                    //             break;
                    //         case 'nama_pena':
                    //             return !is_null($item) ? implode(",", json_decode($item)) : '-';
                    //             break;
                    //         default:
                    //             $item;
                    //             break;
                        // }
                    return $result;
                    break;
                case 'data-section-menu':
                    $result = DB::table('access_bagian')
                        ->where('id', $request->input('id'))
                        ->select('id', 'name', 'order_ab', 'name as oldnama')
                        ->first();
                    return $result;
                    break;
                default:
                    return abort(500);
                    break;
            }
        }
        return view('manweb.setting.index', [
            'title' => 'Setting',
        ]);
    }
    public function crudSetting(Request $request)
    {
        switch ($request->act) {
            case 'create':
                switch ($request->type) {
                    case 'menu':
                        return $this->addMenu($request);
                        break;
                    case 'section-menu':
                        return $this->addSectionMenu($request);
                        break;
                    case 'permission':
                        return $this->addPermission($request);
                        break;
                    default:
                        return abort(404);
                        break;
                }
                break;
            case 'update':
                switch ($request->type) {
                    case 'menu':
                        return $this->editMenu($request);
                        break;
                    case 'section-menu':
                        return $this->editSectionMenu($request);
                        break;
                    case 'permission':
                        return $this->editPermission($request);
                        break;
                    default:
                        return abort(404);
                        break;
                }
                break;
            case 'delete':
                switch ($request->type) {
                    case 'menu':
                        return $this->deleteMenu($request);
                        break;
                    case 'section-menu':
                        return $this->deleteSectionMenu($request);
                        break;
                    case 'permission':
                        return $this->deletePermission($request);
                        break;
                    default:
                        return abort(404);
                        break;
                }
                break;
            default:
                return abort(404);
                break;
        }
    }
    //Menu
    protected function addMenu($request)
    {
        try {
            $request->validate([
                'add_name' => 'required|min:1|max:20',
                'add_bagian' => 'required',
                'add_level' => 'required',
                'add_order_menu' => 'required|numeric',
                'add_url' => 'required',
                'add_icon' => 'required'
            ]);
            $data = [
                'params' => 'Add Menu',
                'id' => Str::uuid()->getHex(),
                'name' => $request->input('add_name'),
                'bagian_id' => $request->input('add_bagian'),
                'level' => $request->input('add_level'),
                'parent_id' => $request->input('add_level') == '1' ? NULL : $request->input('add_parent'),
                'order_menu' => $request->input('add_order_menu'),
                'url' => $request->input('add_url'),
                'icon' => $request->input('add_icon')
            ];
            event(new SettingEvent($data));
            return response()->json([
                'status' => 'success',
                'message' => 'Data menu berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function deleteMenu($request)
    {
        try {
            $access = DB::table('access')
                ->where('id', $request->id)
                ->first();
            if (!is_null($access)) {
                DB::table('access')->where('id', $request->id)
                    ->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil hapus data!'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    //Section Menu
    protected function addSectionMenu($request)
    {
        try {
            $request->validate([
                'add_nama' => 'required|min:1|max:20|unique:access_bagian,name',
                'add_order_ab' => 'required|numeric|min:1|max:20|unique:access_bagian,order_ab'
            ]);
            $data = [
                'params' => 'Add Section Menu',
                'id' => Str::uuid()->getHex(),
                'name' => $request->input('add_nama'),
                'order_ab' => $request->input('add_order_ab'),
            ];
            event(new SettingEvent($data));

            return response()->json([
                'status' => 'success',
                'message' => 'Data section menu berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function editSectionMenu($request)
    {
        try {
            $request->validate([
                'edit_name' => 'required|min:1|max:20',
                'edit_order_ab' => 'required|numeric|min:1|max:20'
            ]);
            $data = [
                'params' => 'Edit Section Menu',
                'id' => $request->input('edit_id'),
                'name' => $request->input('edit_name'),
                'order_ab' => $request->input('edit_order_ab')
            ];
            event(new SettingEvent($data));
            return response()->json([
                'status' => 'success',
                'message' => 'Data section menu berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
