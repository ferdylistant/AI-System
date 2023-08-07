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

class OperatorGudangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pj_st_op_master')
                ->whereNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-operator-gudang');
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
                    $historyData = DB::table('pj_st_op_master_history')->where('operator_id', $data->id)->get();
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
                                    data-toggle="modal" data-target="#md_EditOperatorGudang" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-operator-gudang')) {
                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn_DelOperatorGudang btn-danger btn-icon mr-1 mt-1"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (Auth::user()->cannot('do_update', 'ubah-operator-gudang') && Auth::user()->cannot('do_delete', 'hapus-operator-gudang')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'no',
                    'nama',
                    'created_at',
                    'created_by',
                    'updated_at',
                    'updated_by',
                    'history',
                    'action'
                ])
                ->make(true);
        }
        return view('master_data.operator_gudang.index', [
            'title' => 'Operator Gudang'
        ]);
    }
    public function operatorGudangTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pj_st_op_master')
                ->whereNotNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_delete', 'hapus-operator-gudang');
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
                        class="d-block btn btn-sm btn_ResOperatorGudang btn-dark btn-icon""
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

        return view('master_data.operator_gudang.telah_dihapus', [
            'title' => 'Data Operator Gudang Telah Dihapus',
        ]);
    }
    public function createOperatorGudang(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $id = Uuid::uuid4()->toString();
                    $input = [
                        'params' => 'Create Master Operator Gudang',
                        'id' => $id,
                        'nama' => $request->nama,
                        'created_by' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($input));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data operator gudang berhasil ditambahkan!',
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
    public function updateOperatorGudang(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('pj_st_op_master')->where('id', $request->edit_id)->first();
                    $update = [
                        'params' => 'Update Master Operator Gudang',
                        'id' => $request->edit_id,
                        'nama' => $request->edit_nama,
                        'updated_by' => auth()->user()->id,
                    ];
                    $updated = event(new MasterDataEvent($update));
                    if ($updated) {
                        $insert = [
                            'params' => 'Insert History Update Operator Gudang',
                            'type_history' => 'Update',
                            'operator_id' => $request->edit_id,
                            'nama_his' => $request->edit_nama == $history->nama ? NULL : $history->nama,
                            'nama_new' => $request->edit_nama == $history->nama ? NULL : $request->edit_nama,
                            'author_id' => auth()->user()->id,
                        ];
                        event(new MasterDataEvent($insert));
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data operator gudang berhasil diubah!',
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
                $data = DB::table('pj_st_op_master')
                    ->where('id', $id)
                    ->first();
                return response()->json($data);
            }
        }
    }
    public function deleteOperatorGudang(Request $request)
    {
        try {
            $id = $request->id;
            //Check Relasi
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $insert = [
                'params' => 'Insert History Delete Operator Gudang',
                'operator_id' => $id,
                'type_history' => 'Delete',
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data operator gudang!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryOperatorGudang(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pj_st_op_master_history as ppmh')
                ->join('pj_st_op_master as ppm', 'ppm.id', '=', 'ppmh.operator_id')
                ->join('users as u', 'u.id', '=', 'ppmh.author_id')
                ->where('ppmh.operator_id', $id)
                ->select('ppmh.*', 'u.nama')
                ->orderBy('ppmh.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Operator gudang <b class="text-dark">' . $d->nama_his . '</b> diubah menjadi <b class="text-dark">' . $d->nama_new . '</b>.</span>
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
                                <span><span class="bullet"></span> Data operator gudang dihapus pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data operator gudang direstore pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
    public function restoreOperatorGudang(Request $request)
    {
        try {
            $id = $request->id;
            $insert = [
                'params' => 'Restore Operator Gudang',
                'operator_id' => $id,
                'deleted_at' => null,
                'deleted_by' => null,
                'type_history' => 'Restore',
                'author_id' => auth()->user()->id,
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengembalikan data operator gudang!'
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
            case 'check-duplicate-operator-gudang':
                return $this->checkDuplicateOperator($request);
                break;
            default:
                abort(500);
            break;
        }
    }
    protected function checkDuplicateOperator($request)
    {
        $nama = $request->nama;
        $check = DB::table('pj_st_op_master')->where('nama',$nama)->exists();
        $res = TRUE;
        if ($check) {
            $res = FALSE;
        }
        return response()->json($res,200);
    }
}
