<?php

namespace App\Http\Controllers\MasterData;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Events\MasterDataEvent;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Auth, Gate};

class HargaJualController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pj_st_master_harga_jual')
                ->whereNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-harga-jual');
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                })
                ->addColumn('created_at', function ($data) {
                    $date = date('d F Y, H:i', strtotime($data->created_at));
                    return $date;
                })
                ->addColumn('created_by', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('updated_at', function ($data) {
                    if (is_null($data->updated_at)) {
                        $res = '-';
                    } else {
                        $res = date('d F Y, H:i', strtotime($data->updated_at));
                    }
                    return $res;
                })
                ->addColumn('updated_by', function ($data) {
                    if (is_null($data->updated_by)) {
                        $res = '-';
                    } else {
                        $res = User::where('id', $data->updated_by)->first()->nama;
                    }
                    return $res;
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('pj_st_master_harga_jual_history')->where('master_harga_jual_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-nama="' . $data->nama . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="javascript:void(0)" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1"
                                    data-toggle="modal" data-target="#md_EditHargaJual" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-harga-jual')) {
                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn_DelHargaJual btn-danger btn-icon mr-1 mt-1"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (Auth::user()->cannot('do_update', 'ubah-harga-jual') && Auth::user()->cannot('do_delete', 'hapus-harga-jual')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'no',
                    'nama',
                    'location',
                    'created_at',
                    'created_by',
                    'updated_at',
                    'updated_by',
                    'history',
                    'action'
                ])
                ->make(true);
        }
        return view('master_data.harga_jual.index', [
            'title' => 'Master Harga Jual'
        ]);
    }
    public function hargaJualTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pj_st_master_harga_jual')
                ->whereNotNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_delete', 'hapus-harga-jual');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                })
                ->addColumn('location', function ($data) {
                    return $data->location ?? '-';
                })
                ->addColumn('created_at', function ($data) {
                    $date = date('d M Y, H:i', strtotime($data->created_at));
                    return $date;
                })
                ->addColumn('created_by', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('deleted_at', function ($data) {
                    if ($data->deleted_at == null) {
                        return '-';
                    } else {
                        $date = date('d M Y, H:i', strtotime($data->deleted_at));
                        return $date;
                    }
                })
                ->addColumn('deleted_by', function ($data) {
                    if ($data->deleted_by == null) {
                        return '-';
                    } else {
                        $dataUser = User::where('id', $data->deleted_by)->first();
                        return $dataUser->nama;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="javascript:void(0)"
                        class="d-block btn btn-sm btn_ResHargaJual btn-dark btn-icon""
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
                    'created_at',
                    'created_by',
                    'deleted_at',
                    'deleted_by',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.harga_jual.telah_dihapus', [
            'title' => 'Data Harga Jual Telah Dihapus',
        ]);
    }
    public function createHargaJual(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $id = Uuid::uuid4()->toString();
                    $input = [
                        'params' => 'Create Master Harga Jual',
                        'id' => $id,
                        'nama' => $request->nama,
                        'created_by' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($input));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data Harga Jual berhasil ditambahkan!',
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
            }
        }
    }
    public function updateHargaJual(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('pj_st_master_harga_jual')->where('id', $request->edit_id)->first();
                    $update = [
                        'params' => 'Update Master Harga Jual',
                        'id' => $request->edit_id,
                        'nama' => $request->edit_nama,
                        'updated_by' => auth()->user()->id,
                    ];
                    $updated = event(new MasterDataEvent($update));
                    if ($updated) {
                        $insert = [
                            'params' => 'Insert History Update Harga Jual',
                            'type_history' => 'Update',
                            'master_harga_jual_id' => $request->edit_id,
                            'nama_his' => $request->edit_nama == $history->nama ? NULL : $history->nama,
                            'nama_new' => $request->edit_nama == $history->nama ? NULL : $request->edit_nama,
                            'author_id' => auth()->user()->id,
                        ];
                        event(new MasterDataEvent($insert));
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data Harga Jual berhasil diubah!',
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
            } else {
                $id = $request->id;
                $data = DB::table('pj_st_master_harga_jual')
                    ->where('id', $id)
                    ->first();
                return response()->json($data);
            }
        }
    }
    public function deleteHargaJual(Request $request)
    {
        try {
            $id = $request->id;
            //Check Relasi
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $insert = [
                'params' => 'Insert History Delete Harga Jual',
                'master_harga_jual_id' => $id,
                'type_history' => 'Delete',
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data Harga Jual!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryHargaJual(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pj_st_master_harga_jual_history as ppmh')
                ->join('pj_st_master_harga_jual as ppm', 'ppm.id', '=', 'ppmh.master_harga_jual_id')
                ->join('users as u', 'u.id', '=', 'ppmh.author_id')
                ->where('ppmh.master_harga_jual_id', $id)
                ->select('ppmh.*', 'u.nama')
                ->orderBy('ppmh.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Harga Jual <b class="text-dark">' . $d->nama_his . '</b> diubah menjadi <b class="text-dark">' . $d->nama_new . '</b>.</span>
                            </div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                            </div>
                            </span>';
                        break;
                    case 'Delete':
                        $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Data Harga Jual dihapus pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data Harga Jual direstore pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
    public function restoreHargaJual(Request $request)
    {
        try {
            $id = $request->id;
            $insert = [
                'params' => 'Restore Harga Jual',
                'master_harga_jual_id' => $id,
                'deleted_at' => null,
                'deleted_by' => null,
                'type_history' => 'Restore',
                'author_id' => auth()->user()->id,
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengembalikan data Harga Jual!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function callAjax(Request $request)
    {
        switch($request->cat) {
            case 'check-duplicate-harga-jual':
                return $this->checkDuplicateHargaJual($request);
                break;
            default:
                abort(500);
            break;
        }
    }
    protected function checkDuplicateHargaJual($request)
    {
        $nama = $request->nama;
        $check = DB::table('pj_st_master_harga_jual')->where('nama',$nama)->exists();
        $res = TRUE;
        if ($check) {
            $res = FALSE;
        }
        return response()->json($res,200);
    }
}
