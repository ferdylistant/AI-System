<?php

namespace App\Http\Controllers\MasterData;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class FormatBukuController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-format-buku') {
                $data = DB::table('format_buku')
                    ->whereNull('deleted_at')
                    ->orderBy('jenis_format', 'asc')
                    ->get();
                $update = Gate::allows('do_update', 'ubah-format-buku');
                // foreach ($data as $key => $value) {
                //     $no = $key + 1;
                // }
                $start=1;
                return DataTables::of($data)
                        ->addColumn('no', function($no) use (&$start) {
                            return $start++;
                        })
                        ->addColumn('jenis_format', function($data) {
                            return $data->jenis_format;
                        })
                        ->addColumn('tgl_dibuat', function($data) {
                            $date = Carbon::parse($data->created_at)->translatedFormat('d M Y, H:i');
                            // $date = date('d F Y, H:i', strtotime($data->created_at));
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
                                $date = Carbon::parse($data->updated_at)->translatedFormat('d M Y, H:i');
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
                            if(Gate::allows('do_delete', 'hapus-format-buku')) {
                                $btn = '<a href="'.url('master/format-buku/hapus?kb='.$data->id).'"
                                    class="btn btn-sm btn-danger btn-icon mr-1" id="hapus-fbuku" data-toggle="tooltip" title="Hapus Data">
                                    <div><i class="fas fa-trash"></i></div></a>';
                            }
                            if($update) {
                                $btn .= '<a href="'.url('master/format-buku/ubah?kb='.$data->id).'"
                                    class="btn btn-sm btn-warning btn-icon mr-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'no',
                            'jenis_format',
                            'tgl_dibuat',
                            'dibuat_oleh',
                            'diubah_terakhir',
                            'diubah_oleh',
                            'action'
                            ])
                        ->make(true);
            }
        }

        return view('master_data.format_buku.index', [
            'title' => 'Format Buku Penerbitan',
        ]);
    }
    public function createFbuku(Request $request) {
        if($request->ajax()) {
            if($request->isMethod('POST')) {
                $request->validate([
                    'add_nama' => 'required|unique:format_buku,jenis_format',
                ], [
                    'required' => 'This field is requried'
                ]);
                DB::table('format_buku')->insert([
                    'id' => Uuid::uuid4()->toString(),
                    'jenis_format' => $request->add_nama,
                    'created_by' => auth()->user()->id
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kelompok buku berhasil ditambahkan!',
                    // 'redirect' => route('produksi.view')
                ]);
            }
        }
        return view('master_data.format_buku.create', [
            'title' => 'Tambah Format Buku'
        ]);
    }
    public function updateFbuku(Request $request) {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'up_nama' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);

                DB::table('penerbitan_m_kelompok_buku')
                    ->where('id', $request->id)
                    ->update([
                    'nama' => $request->up_nama,
                    'updated_by' => auth()->user()->id
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kelompok buku berhasil diubah!',
                    'route' => route('kb.view')
                ]);
            }
        }
        $id = $request->get('kb');
        $data = DB::table('penerbitan_m_kelompok_buku')
                    ->where('id', $id)
                    ->first();
        return view('master_data.kelompok_buku.update', [
            'title' => 'Update Kelompok Buku',
            'data' => $data
        ]);
    }
    public function deleteFbuku(Request $request) {
        $id = $request->get('kb');
        $deleted = DB::table('penerbitan_m_kelompok_buku')->where('id',$id)->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        if ($deleted) {
            echo '<script>

            window.location = "'.route('kb.view').'";
            </script>';
        }
    }
}
