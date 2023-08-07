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

class RackController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pj_st_rack_master')
                ->whereNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-rack-list');
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
                    $historyData = DB::table('pj_st_rack_master_history')->where('rack_id', $data->id)->get();
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
                                    data-toggle="modal" data-target="#md_EditRackList" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-rack-list')) {
                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn_DelRackList btn-danger btn-icon mr-1 mt-1"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (Auth::user()->cannot('do_update', 'ubah-rack-list') && Auth::user()->cannot('do_delete', 'hapus-rack-list')) {
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
        return view('master_data.rack_list.index', [
            'title' => 'Rack List Gudang'
        ]);
    }
    public function rackListTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pj_st_rack_master')
                ->whereNotNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_delete', 'hapus-rack-list');
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
                        class="d-block btn btn-sm btn_ResRackList btn-dark btn-icon""
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
                    'location',
                    'created_at',
                    'created_by',
                    'deleted_at',
                    'deleted_by',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.rack_list.telah_dihapus', [
            'title' => 'Data Rak Telah Dihapus',
        ]);
    }
    public function createRackList(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $id = Uuid::uuid4()->toString();
                    $input = [
                        'params' => 'Create Master Rack',
                        'id' => $id,
                        'nama' => $request->nama,
                        'location' => $request->location,
                        'created_by' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($input));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data rak berhasil ditambahkan!',
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
    public function updateRackList(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('pj_st_rack_master')->where('id', $request->edit_id)->first();
                    $update = [
                        'params' => 'Update Master Rack',
                        'id' => $request->edit_id,
                        'nama' => $request->edit_nama,
                        'location' => $request->edit_location,
                        'updated_by' => auth()->user()->id,
                    ];
                    $updated = event(new MasterDataEvent($update));
                    if ($updated) {
                        $insert = [
                            'params' => 'Insert History Update Rack',
                            'type_history' => 'Update',
                            'rack_id' => $request->edit_id,
                            'nama_his' => $request->edit_nama == $history->nama ? NULL : $history->nama,
                            'nama_new' => $request->edit_nama == $history->nama ? NULL : $request->edit_nama,
                            'location_his' => $request->edit_location == $history->location ? NULL : $history->location,
                            'location_new' => $request->edit_location == $history->location ? NULL : $request->edit_location,
                            'author_id' => auth()->user()->id,
                        ];
                        event(new MasterDataEvent($insert));
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data rak berhasil diubah!',
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
                $data = DB::table('pj_st_rack_master')
                    ->where('id', $id)
                    ->first();
                return response()->json($data);
            }
        }
    }
    public function deleteRackList(Request $request)
    {
        try {
            $id = $request->id;
            //Check Relasi
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $insert = [
                'params' => 'Insert History Delete Rack',
                'rack_id' => $id,
                'type_history' => 'Delete',
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data rak!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryRackList(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pj_st_rack_master_history as ppmh')
                ->join('pj_st_rack_master as ppm', 'ppm.id', '=', 'ppmh.rack_id')
                ->join('users as u', 'u.id', '=', 'ppmh.author_id')
                ->where('ppmh.rack_id', $id)
                ->select('ppmh.*', 'u.nama')
                ->orderBy('ppmh.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Rak <b class="text-dark">' . $d->nama_his . '</b> diubah menjadi <b class="text-dark">' . $d->nama_new . '</b>.</span>
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
                                <span><span class="bullet"></span> Data rak dihapus pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data rak direstore pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
    public function restoreRackList(Request $request)
    {
        try {
            $id = $request->id;
            $insert = [
                'params' => 'Restore Rack',
                'rack_id' => $id,
                'deleted_at' => null,
                'deleted_by' => null,
                'type_history' => 'Restore',
                'author_id' => auth()->user()->id,
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengembalikan data rak!'
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
            case 'check-duplicate-rack':
                return $this->checkDuplicateRack($request);
                break;
            default:
                abort(500);
            break;
        }
    }
    protected function checkDuplicateRack($request)
    {
        $nama = $request->nama;
        $check = DB::table('pj_st_rack_master')->where('nama',$nama)->exists();
        $res = TRUE;
        if ($check) {
            $res = FALSE;
        }
        return response()->json($res,200);
    }
}
