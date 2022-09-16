<?php

namespace App\Http\Controllers\Penerbitan;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class DeskripsiFinalController extends Controller
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

        return view('penerbitan.imprint.index', [
            'title' => 'Imprint Penerbitan',
        ]);
    }
}
