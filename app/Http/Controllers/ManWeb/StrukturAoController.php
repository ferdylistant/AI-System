<?php

namespace App\Http\Controllers\ManWeb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class StrukturAoController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-cabang') {  // Load Table Cabang
                $table = DB::table('cabang')
                            ->whereNull('deleted_at')
                            ->select('id', 'kode', 'nama', 'telp', 'alamat')
                            ->get();

                return Datatables::of($table)
                        ->addColumn('action', function($data){
                            $btn = '<a href="#" class="btn btn-sm btn-warning btn_EditCabang btn-icon mr-1"
                                    data-toggle="modal" data-target="#md_EditCabang" data-backdrop="static"
                                    data-id="'.$data->id.'" data-nama="'.$data->nama.'">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            $btn .= '<a href="#" class="btn btn-sm btn_DelCabang btn-danger btn-icon"
                                    data-id="'.$data->id.'" data-nama="'.$data->nama.'">
                                    <div><i class="fas fa-trash-alt"></i></div></a>';
                            return $btn;
                        })
                        ->make(true);

            } elseif ($request->input('request_') === 'table-divisi') { // Load Table Divisi
                $table = DB::table('divisi')
                                ->whereNull('deleted_at')
                                ->orderBy('nama','ASC')
                                ->select('id', 'nama')->get();

                    return Datatables::of($table)
                            ->addIndexColumn()
                            ->addColumn('action', function($data){
                                $btn = '<a href="#" class="btn btn-sm btn-warning btn-icon mr-1" data-tipe="Divisi"
                                        data-toggle="modal" data-target="#md_EditDivJab" data-backdrop="static"
                                        data-id="'.$data->id.'" data-nama="'.$data->nama.'"">
                                        <div><i class="fas fa-edit"></i></div></a>';
                                $btn .= '<a href="#" class="btn btn-sm btn-danger btn_DelDivJab btn-icon"
                                        data-id="'.$data->id.'" data-nama="'.$data->nama.'"" data-tipe="Divisi">
                                        <div><i class="fas fa-trash-alt"></i></div></a>';
                                return $btn;
                            })
                            ->make(true);

            } elseif ($request->input('request_') === 'table-jabatan') { // Load Table Jabatan
                $table = DB::table('jabatan')
                                ->whereNull('deleted_at')
                                ->orderBy('nama','ASC')
                                ->select('id', 'nama')->get();

                    return Datatables::of($table)
                            ->addIndexColumn()
                            ->addColumn('action', function($data){
                                $btn = '<a href="#" class="btn btn-sm btn-warning btn-icon mr-1" data-tipe="Jabatan"
                                        data-toggle="modal" data-target="#md_EditDivJab" data-backdrop="static"
                                        data-id="'.$data->id.'" data-nama="'.$data->nama.'"">
                                        <div><i class="fas fa-edit"></i></div></a>';
                                $btn .= '<a href="#" class="btn btn-sm btn-danger btn_DelDivJab btn-icon"
                                        data-id="'.$data->id.'" data-nama="'.$data->nama.'"" data-tipe="Jabatan">
                                        <div><i class="fas fa-trash-alt"></i></div></a>';
                                return $btn;
                            })
                            ->make(true);

            } elseif ($request->input('request_') === 'cabang') {
                $result = DB::table('cabang')->whereNull('deleted_at')
                            ->where('id', $request->input('id'))
                            ->select('id', 'kode', 'nama', 'telp', 'alamat', 'nama as oldnama')
                            ->first();
                return $result;
            }
        }

        return view('manweb.struktur-ao.index',[
            'title' => 'Struktur AO'
        ]);
    }

    public function crudSO(Request $request) {
        if($request->act == 'create') {
            if($request->type == 'cabang') {
                return $this->addCabang($request);
            } elseif ($request->type == 'divisi' OR $request->type == 'jabatan') {
                return $this->addDivJab($request);
            } else { return abort(404); }

        } elseif($request->act == 'update') {
            if($request->type == 'cabang') {
                return $this->editCabang($request);
            } elseif ($request->type == 'divisi' OR $request->type == 'jabatan') {
                return $this->editDivJab($request);
            } else { return abort(404); }

        } elseif($request->act == 'delete') {
            if($request->type == 'cabang') {
                $users = DB::table('users')->whereNull('deleted_at')
                            ->where('cabang_id', $request->input('id'))
                            ->get();

                if($users->isEmpty()) {
                    DB::table('cabang')->where('id', $request->input('id'))
                        ->update([
                            'deleted_at' => date('Y-m-d H:i:s'),
                            'deleted_by' => auth()->id()
                        ]);
                    return;
                } else {
                    return response()->json([
                        'error' => 'Users Exists'
                    ], 422);
                }

            } elseif ($request->type == 'divisi') {
                $users = DB::table('users')->whereNull('deleted_at')
                            ->where('divisi_id', $request->input('id'))
                            ->get();

                if($users->isEmpty()) {
                    DB::table('divisi')->where('id', $request->input('id'))
                        ->update([
                            'deleted_at' => date('Y-m-d H:i:s'),
                            'deleted_by' => auth()->id()
                        ]);
                    return;
                } else {
                    return response()->json([
                        'error' => 'Users Exists'
                    ], 422);
                }

            } elseif ($request->type == 'jabatan') {
                $users = DB::table('users')->whereNull('deleted_at')
                            ->where('jabatan_id', $request->input('id'))
                            ->get();

                if($users->isEmpty()) {
                    DB::table('jabatan')->where('id', $request->input('id'))
                        ->update([
                            'deleted_at' => date('Y-m-d H:i:s'),
                            'deleted_by' => auth()->id()
                        ]);
                    return;
                } else {
                    return response()->json([
                        'error' => 'Users Exists'
                    ], 422);
                }

            } else { abort(404); }
        } else {
            return abort(404);
        }
    }

    protected function addCabang($request) {
        $request->validate([
            'add_kode' => 'required||min:4|max:4|unique:cabang,kode',
            'add_nama' => 'required',
            'add_telp' => 'nullable|numeric'
        ]);

        DB::table('cabang')->insert([
            'id' => Str::uuid()->getHex(),
            'kode' => $request->input('add_kode'),
            'nama' => $request->input('add_nama'),
            'telp' => $request->input('add_telp'),
            'alamat' => $request->input('add_alamat'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->id()
        ]);

        return;
    }

    protected function editCabang($request) {
        $request->validate([
            'edit_id' => 'required',
            'edit_nama' => 'required',
            'edit_telp' => 'nullable|numeric'
        ]);

        DB::table('cabang')->whereNull('deleted_at')
            ->where('id', $request->input('edit_id'))->update([
                'nama' => $request->input('edit_nama'),
                'telp' => $request->input('edit_telp'),
                'alamat' => $request->input('edit_alamat'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->id()
            ]);

        return;
    }

    protected function addDivJab($request) {
        $request->validate([
            'add_djnama' => 'required'
        ]);

        if($request->type == 'divisi') {
            DB::table('divisi')->insert([
                'id' => Str::uuid()->getHex(),
                'nama' => $request->input('add_djnama'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->id()
            ]);
            return;
        } else {
            DB::table('jabatan')->insert([
                'id' => Str::uuid()->getHex(),
                'nama' => $request->input('add_djnama'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->id()
            ]);
            return;
        }
    }

    protected function editDivJab($request) {
        $request->validate([
            'edit_djid' => 'required',
            'edit_djnama' => 'required'
        ]);

        if($request->type == 'divisi') {
            DB::table('divisi')->where('id', $request->input('edit_djid'))
                ->update([
                    'nama' => $request->input('edit_djnama'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->id()
                ]);
            return;
        } else {
            DB::table('jabatan')->where('id', $request->input('edit_djid'))
                ->update([
                    'nama' => $request->input('edit_djnama'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->id()
                ]);
            return;
        }
    }




}
