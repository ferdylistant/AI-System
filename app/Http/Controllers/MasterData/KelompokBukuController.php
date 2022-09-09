<?php

namespace App\Http\Controllers\MasterData;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\{DB, Gate};

class KelompokBukuController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-kbuku') {
                $data = DB::table('penerbitan_m_kelompok_buku')
                    ->whereNull('deleted_at')
                    ->orderBy('kode', 'asc')
                    ->get();
                $update = Gate::allows('do_update', 'ubah-kelompok-buku');
                // foreach ($data as $key => $value) {
                //     $no = $key + 1;
                // }
                // $start=1;
                return DataTables::of($data)
                        ->addColumn('kode', function($data) {
                            return $data->kode;
                        })
                        ->addColumn('nama', function($data) {
                            return $data->nama;
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
                            if(Gate::allows('do_delete', 'hapus-kelompok-buku')) {
                                $btn = '<a href="'.url('master/kelompok-buku/hapus?kb='.$data->id).'"
                                    class="btn btn-sm btn-danger btn-icon mr-1" id="hapus-kbuku" data-toggle="tooltip" title="Hapus Data">
                                    <div><i class="fas fa-trash"></i></div></a>';
                            }
                            if($update) {
                                $btn .= '<a href="'.url('master/kelompok-buku/ubah?kb='.$data->id).'"
                                    class="btn btn-sm btn-warning btn-icon mr-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'kode',
                            'nama',
                            'tgl_dibuat',
                            'dibuat_oleh',
                            'diubah_terakhir',
                            'diubah_oleh',
                            'action'
                            ])
                        ->make(true);
            }
        }

        return view('master_data.kelompok_buku.index', [
            'title' => 'Kelompok Buku Penerbitan',
        ]);
    }
    public function createKbuku(Request $request) {
        if($request->ajax()) {
            if($request->isMethod('POST')) {
                $request->validate([
                    'add_nama' => 'required|unique:penerbitan_m_kelompok_buku,nama',
                ], [
                    'required' => 'This field is requried'
                ]);
                $last = DB::table('penerbitan_m_kelompok_buku')->whereNull('deleted_at')->get('kode')->max();
                $lastId_ = (int)substr($last->kode, 2);
                DB::table('penerbitan_m_kelompok_buku')->insert([
                    'id' => Uuid::uuid4()->toString(),
                    'kode' => 'KB'.$lastId_+=1,
                    'nama' => $request->add_nama,
                    'created_by' => auth()->user()->id
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kelompok buku berhasil ditambahkan!',
                    // 'redirect' => route('produksi.view')
                ]);
            }
        }
        return view('master_data.kelompok_buku.create', [
            'title' => 'Tambah Kelompok Buku'
        ]);
    }
    public function updateKbuku(Request $request) {
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
    public function deleteKbuku(Request $request) {
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
