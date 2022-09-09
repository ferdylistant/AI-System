<?php

namespace App\Http\Controllers\MasterData;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class ImprintController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-imprint') {
                $data = DB::table('imprint')
                    ->whereNull('deleted_at')
                    ->orderBy('nama', 'asc')
                    ->get();
                $update = Gate::allows('do_update', 'ubah-data-imprint');
                // foreach ($data as $key => $value) {
                //     $no = $key + 1;
                // }
                $start=1;
                return DataTables::of($data)
                        ->addColumn('no', function($no) use (&$start) {
                            return $start++;
                        })
                        ->addColumn('nama_imprint', function($data) {
                            return $data->nama;
                        })
                        ->addColumn('tgl_dibuat', function($data) {
                            $date = date('d F Y, H:i', strtotime($data->created_at));
                            return $date;
                        })
                        ->addColumn('dibuat_oleh', function($data) {
                            $dataUser = User::where('id',$data->created_by)->first();
                            return $dataUser->nama;
                        })
                        ->addColumn('diubah_terakhir', function($data) {
                            if($data->updated_at == null) {
                                return '-';
                            } else {
                                $date = date('d F Y, H:i', strtotime($data->updated_at));
                                return $date;
                            }
                        })
                        ->addColumn('diubah_oleh', function($data) {
                            if($data->updated_by == null) {
                                return '-';
                            } else {
                                $dataUser = User::where('id',$data->updated_by)->first();
                                return $dataUser->nama;
                            }
                        })
                        ->addColumn('action', function($data) use ($update) {
                            if(Gate::allows('do_delete', 'hapus-data-imprint')) {
                                $btn = '<a href="'.url('master/imprint/hapus?im='.$data->id).'"
                                    class="btn btn-sm btn-danger btn-icon mr-1" data-toggle="tooltip" title="Hapus Data">
                                    <div><i class="fas fa-trash"></i></div></a>';
                            }
                            if($update) {
                                $btn .= '<a href="'.url('master/imprint/ubah-imprint?im='.$data->id).'"
                                    class="btn btn-sm btn-warning btn-icon mr-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'no',
                            'nama_imprint',
                            'tgl_dibuat',
                            'dibuat_oleh',
                            'diubah_terakhir',
                            'diubah_oleh',
                            'action'
                            ])
                        ->make(true);
            }
        }

        return view('master_data.imprint.index', [
            'title' => 'Imprint Penerbitan',
        ]);
    }

    public function createImprint(Request $request) {
        if($request->ajax()) {
            if($request->isMethod('POST')) {
                $request->validate([
                    'add_nama' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);
                DB::table('imprint')->insert([
                    'id' => Uuid::uuid4()->toString(),
                    'nama' => $request->add_nama,
                    'created_by' => auth()->user()->id
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data imprint berhasil ditambahkan!',
                    // 'redirect' => route('produksi.view')
                ]);
            }
        }
        return view('master_data.imprint.create', [
            'title' => 'Tambah Imprint Penerbitan'
        ]);
    }

    public function updateImprint(Request $request) {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'up_nama' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);

                DB::table('imprint')
                    ->where('id', $request->id)
                    ->update([
                    'nama' => $request->up_nama,
                    'updated_by' => auth()->user()->id
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data imprint berhasil diubah!',
                    'route' => route('imprint.view')
                ]);
            }
        }
        $id = $request->get('im');
        $data = DB::table('imprint')
                    ->where('id', $id)
                    ->first();
        return view('master_data.imprint.update', [
            'title' => 'Update Imprint',
            'data' => $data
        ]);
    }
    public function indexPlatform(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-platform') {
                $data = DB::table('platform_digital_ebook')
                    ->whereNull('deleted_at')
                    ->orderBy('nama', 'asc')
                    ->get();
                $update = Gate::allows('do_update', 'ubah-platform-digital');
                // foreach ($data as $key => $value) {
                //     $no = $key + 1;
                // }
                $start=1;
                return DataTables::of($data)
                        ->addColumn('no', function($no) use (&$start) {
                            return $start++;
                        })
                        ->addColumn('nama_platform', function($data) {
                            return $data->nama;
                        })
                        ->addColumn('tgl_dibuat', function($data) {
                            $date = date('d F Y, H:i', strtotime($data->created_at));
                            return $date;
                        })
                        ->addColumn('dibuat_oleh', function($data) {
                            $dataUser = User::where('id',$data->created_by)->first();
                            return $dataUser->nama;
                        })
                        ->addColumn('diubah_terakhir', function($data) {
                            if($data->updated_at == null) {
                                return '-';
                            } else {
                                $date = date('d F Y, H:i', strtotime($data->updated_at));
                                return $date;
                            }
                        })
                        ->addColumn('diubah_oleh', function($data) {
                            if($data->updated_by == null) {
                                return '-';
                            } else {
                                $dataUser = User::where('id',$data->updated_by)->first();
                                return $dataUser->nama;
                            }
                        })
                        ->addColumn('action', function($data) use ($update) {
                            if(Gate::allows('do_delete', 'hapus-data-imprint')) {
                                $btn = '<a href="'.url('master/platform-digital/hapus?p='.$data->id).'"
                                    class="btn btn-sm btn-danger btn-icon mr-1" data-toggle="tooltip" title="Hapus Data">
                                    <div><i class="fas fa-trash"></i></div></a>';
                            }
                            if($update) {
                                $btn .= '<a href="'.url('master/platform-digital/ubah?p='.$data->id).'"
                                    class="btn btn-sm btn-warning btn-icon mr-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'no',
                            'nama_platform',
                            'tgl_dibuat',
                            'dibuat_oleh',
                            'diubah_terakhir',
                            'diubah_oleh',
                            'action'
                            ])
                        ->make(true);
            }
        }

        return view('master_data.platform_digital.index', [
            'title' => 'Platform Digital',
        ]);
    }

    public function createPlatform(Request $request) {
        if($request->ajax()) {
            if($request->isMethod('POST')) {
                $request->validate([
                    'add_nama' => 'required|unique:platform_digital_ebook,nama',
                ], [
                    'required' => 'This field is requried'
                ]);
                DB::table('platform_digital_ebook')->insert([
                    'id' => Uuid::uuid4()->toString(),
                    'nama' => $request->add_nama,
                    'created_by' => auth()->user()->id
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data platform berhasil ditambahkan!',
                    // 'redirect' => route('produksi.view')
                ]);
            }
        }
        return view('master_data.platform_digital.create', [
            'title' => 'Tambah Platform Digital'
        ]);
    }

    public function updatePlatform(Request $request) {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'up_nama' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);

                DB::table('platform_digital_ebook')
                    ->where('id', $request->id)
                    ->update([
                    'nama' => $request->up_nama,
                    'updated_by' => auth()->user()->id
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data platform berhasil diubah!',
                    'route' => route('platform.view')
                ]);
            }
        }
        $id = $request->get('p');
        $data = DB::table('platform_digital_ebook')
                    ->where('id', $id)
                    ->first();
        return view('master_data.platform_digital.update', [
            'title' => 'Update Platform Digital',
            'data' => $data
        ]);
    }
}
