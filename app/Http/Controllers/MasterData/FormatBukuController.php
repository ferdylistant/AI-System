<?php

namespace App\Http\Controllers\MasterData;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Events\MasterDataEvent;
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
                        ->addColumn('history', function ($data) {
                            $historyData = DB::table('format_buku_history')->where('format_buku_id',$data->id)->get();
                            if($historyData->isEmpty()) {
                                return '-';
                            } else {
                                $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="'.$data->id.'" data-toggle="modal" data-target="#md_FormatBukuHistory"><i class="fas fa-history"></i>&nbsp;History</button>';
                                return $date;
                            }
                        })
                        ->addColumn('action', function($data) use ($update) {
                            if(Gate::allows('do_delete', 'hapus-format-buku')) {
                                $btn = '<a href="'.url('master/format-buku/hapus?fb='.$data->id).'"
                                    class="d-block btn btn-sm btn-danger btn-icon mr-1" id="hapus-fbuku" data-toggle="tooltip" title="Hapus Data">
                                    <div><i class="fas fa-trash"></i></div></a>';
                            }
                            if($update) {
                                $btn .= '<a href="'.url('master/format-buku/ubah?fb='.$data->id).'"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
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
                            'history',
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
                    'jenis_format' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);

                $history = DB::table('format_buku')->where('id',$request->id)->first();
                $update = [
                    'params' => 'Update Format Buku',
                    'id' => $request->id,
                    'jenis_format' => $request->jenis_format,
                    'updated_by' => auth()->user()->id
                ];
                event(new MasterDataEvent($update));
                $insert = [
                    'params' => 'Insert History Format Buku',
                    'format_buku_id' => $request->id,
                    'jenis_format_history' => $history->jenis_format,
                    'jenis_format_new' => $request->jenis_format,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data format buku berhasil diubah!',
                    'route' => route('fb.view')
                ]);
            }
        }
        $id = $request->get('fb');
        $data = DB::table('format_buku')
                    ->where('id', $id)
                    ->first();
        return view('master_data.format_buku.update', [
            'title' => 'Update Format Buku',
            'data' => $data
        ]);
    }
    public function deleteFbuku(Request $request) {
        $id = $request->get('fb');
        $deleted = DB::table('format_buku')->where('id',$id)->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        if ($deleted) {
            echo '<script>

            window.location = "'.route('fb.view').'";
            </script>';
        }
    }
    public function lihatHistory(Request $request) {
        $id = $request->input('id');
        $data = DB::table('format_buku_history as fb')->join('users as u','fb.author_id','=','u.id')
        ->where('fb.format_buku_id',$id)
        ->select('fb.*','u.nama')
        ->get();
        foreach ($data as $d){
            $result[] = [
                'jenis_format_history' => $d->jenis_format_history,
                'jenis_format_new' => $d->jenis_format_new,
                'author_id' => $d->author_id,
                'nama' => $d->nama,
                'modified_at' => Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans(),
                'format_tanggal' => Carbon::parse($d->modified_at)->translatedFormat('d M Y, H:i')
            ];
        }
        return response()->json($result);
    }
}
