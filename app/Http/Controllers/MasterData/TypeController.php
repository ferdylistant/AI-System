<?php

namespace App\Http\Controllers\MasterData;

use App\Events\MasterDataEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Gate, Validator};
use Yajra\DataTables\DataTables;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('type')
                ->whereNull('deleted_at')
                ->orderBy('kode', 'asc')
                ->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('kode', function ($data) {
                    return $data->kode;
                })
                ->addColumn('nama_type', function ($data) {
                    return $data->nama;
                })
                ->addColumn('tgl_dibuat', function ($data) {
                    return Carbon::parse($data->created_at)->translatedFormat('d M Y, H:i');
                })
                ->addColumn('dibuat_oleh', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('diubah_terakhir', function ($data) {
                    if ($data->updated_at == null) {
                        return '-';
                    } else {
                        return Carbon::parse($data->updated_at)->translatedFormat('d M Y, H:i');
                    }
                })
                ->addColumn('diubah_oleh', function ($data) {
                    if ($data->updated_by == null) {
                        return '-';
                    } else {
                        $dataUser = User::where('id', $data->updated_by)->first();
                        return $dataUser->nama;
                    }
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('type_history')->where('type_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 tooltip-type" data-type="history" data-toggle="modal" data-target="#md_Type" data-backdrop="static" data-id="' . $data->id . '" data-nama="' . $data->nama . '" title="' . ucfirst($data->nama) . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) {
                    if (Gate::allows('do_update', 'ubah-type')) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1 tooltip-type"
                                    data-type="edit" data-toggle="modal" data-target="#md_Type" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-type')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelType btn-danger btn-icon mr-1 mt-1 tooltip-type"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (!Gate::allows('do_update', 'ubah-type') && !Gate::allows('do_delete', 'hapus-type')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'kode',
                    'nama_type',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'diubah_terakhir',
                    'diubah_oleh',
                    'history',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.type.index', [
            'title' => 'Master Data Type',
        ]);
    }

    public function callAjax(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->cat) {
                case 'add':
                    return $this->showCreateType();
                    break;
                case 'edit':
                    return $this->showEditType($request);
                    break;
                case 'history':
                    return $this->showHistoryType($request);
                    break;
                case 'check-duplicate-type':
                    return $this->checkDuplicateType($request);
                    break;
                default:
                    abort(500);
                    break;
            }
        }
    }

    public function createType(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $validator = Validator::make($request->all(), [
                    'nama_type' => 'required|string|unique:type,nama'
                ], [
                    'unique' => 'Type sudah terdaftar!'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors()
                    ]);
                }

                try {
                    $last = DB::table('type')->orderBy('created_at', 'desc')->first();
                    if (is_null($last)) {
                        $kode = '01';
                    } else {
                        $kode = sprintf('%02d', (int)$last->kode + 1);
                    }
                    $content = [
                        'kode_type' => $kode,
                        'nama_type' => $request->nama_type
                    ];
                    $input = [
                        'params' => 'Create Type',
                        'content' => $content,
                        'created_by' => auth()->user()->id
                    ];

                    DB::beginTransaction();
                    event(new MasterDataEvent($input));
                    $insert = [
                        'params' => 'Insert History Create or Update Type',
                        'type_id' => DB::getPdo()->lastInsertId(),
                        'type_history' => 'Create',
                        'content' => json_encode($content),
                        'author_id' => auth()->user()->id,
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new MasterDataEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data type berhasil ditambahkan!'
                    ]);
                } catch (\Throwable $err) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $err->getMessage(),
                    ]);
                }
            }
        }
    }

    public function updateType(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $validator = Validator::make($request->all(), [
                    'nama_type' => 'required|string|unique:type,nama,' . $request->id
                ], [
                    'unique' => 'Type sudah terdaftar!'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors()
                    ]);
                }

                try {
                    $history = DB::table('type')->where('id', $request->id)->first();
                    $content = [
                        'kode_type' => $history->kode,
                        'nama_type_history' => $history->nama,
                        'nama_type_new' => $request->nama_type
                    ];
                    $update = [
                        'params' => 'Update Type',
                        'id' => $request->id,
                        'content' => $content,
                        'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                        'updated_by' => auth()->user()->id
                    ];
                    DB::beginTransaction();
                    event(new MasterDataEvent($update));
                    $insert = [
                        'params' => 'Insert History Create or Update Type',
                        'type_id' => $request->id,
                        'type_history' => 'Update',
                        'content' => json_encode($content),
                        'author_id' => auth()->user()->id,
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new MasterDataEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data Type berhasil diubah!'
                    ]);
                } catch (\Throwable $err) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $err->getMessage(),
                    ]);
                }
            }
        }
        $id = $request->id;
        $data = DB::table('type')->where('id', $id)->first();
        return response()->json($data);
    }

    public function deleteType(Request $request)
    {
        try {
            $id = $request->id;
            $history = DB::table('type')->where('id', $id)->first();
            $content = [
                'kode_type' => $history->kode,
                'nama_type' => $history->nama
            ];
            // Check Relasi
            // $relation = DB::table('penerbitan_naskah')->where('kelompok_buku_id', $id)->first();
            // if (!is_null($relation)) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Type tidak bisa dihapus karena telah terpakai di naskah yang sedang diproses!'
            //     ]);
            // }
            $insert = [
                'params' => 'Insert History Delete Type',
                'type_id' => $id,
                'type_history' => 'Delete',
                'deleted_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'content' => json_encode($content),
                'author_id' => auth()->user()->id
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data type!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function typeTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('type')->whereNotNull('deleted_at')->orderBy('nama', 'asc')->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_type', function ($data) {
                    return $data->nama;
                })
                ->addColumn('tgl_dibuat', function ($data) {
                    return Carbon::parse($data->created_at)->translatedFormat('d M Y, H:i');
                })
                ->addColumn('dibuat_oleh', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('dihapus_pada', function ($data) {
                    if ($data->deleted_at == null) {
                        return '-';
                    } else {
                        return Carbon::parse($data->deleted_at)->translatedFormat('d M Y, H:i');
                    }
                })
                ->addColumn('dihapus_oleh', function ($data) {
                    if ($data->deleted_by == null) {
                        return '-';
                    } else {
                        $dataUser = User::where('id', $data->deleted_by)->first();
                        return $dataUser->nama;
                    }
                })
                ->addColumn('action', function ($data) {
                    if (Gate::allows('do_delete', 'hapus-type')) {
                        $btn = '<a href="#"
                        class="d-block btn btn-sm btn_ResType btn-dark btn-icon"
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
                    'nama_type',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.type.telah_dihapus', [
            'title' => 'Type Telah Dihapus',
        ]);
    }

    public function restoreType(Request $request)
    {
        try {
            $id = $request->id;
            $restored = DB::table('type')->where('id', $id)->first();
            $content = [
                'kode_type' => $restored->kode,
                'nama_type' => $restored->nama
            ];
            $insert = [
                'params' => 'Insert History Restored Type',
                'type_id' => $id,
                'type_history' => 'Restore',
                'restored_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'content' => json_encode($content),
                'author_id' => auth()->user()->id
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengembalikan type!'
            ]);
        } catch (\Throwable $err) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $err->getMessage()
            ]);
        }
    }

    protected function checkDuplicateType($request)
    {
        $nama = $request->nama_type;
        $check = DB::table('type')->where('nama', $nama)->exists();
        $res = TRUE;
        if ($check) {
            $res = FALSE;
        }
        return response()->json($res, 200);
    }

    protected function showCreateType()
    {
        $html = '';
        $html .= '<form id="fm_addType">';
        $html .= csrf_field();
        $html .= '<div class="form-group">
            <label class="col-form-label">Nama Type <span class="text-danger">*</span></label>
            <input type="text" name="nama_type" class="form-control"
                placeholder="Nama Type">
            <div id="err_nama_type"></div>
        </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-success" form="fm_addType" data-el="#fm_addType">Simpan</button>';
        $title = '<i class="fas fa-plus"></i> Tambah Data Type';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }

    protected function showEditType($request)
    {
        $data = DB::table('type')->where('id', $request->id)->first();
        $html = '';
        $html .= '<form id="fm_editType">';
        $html .= csrf_field();
        $html .= '<div class="form-group">
        <input type="hidden" name="id" value="' . $data->id . '">
            <label class="col-form-label">Kode Type <span class="text-danger">*</span></label>
            <input type="text" name="kode_type" class="form-control"
                value="' . $data->kode . '" placeholder="Kode Type" readonly>
            <div id="err_kode_type"></div>
        </div>
        <div class="form-group">
            <label class="col-form-label">Nama Type <span class="text-danger">*</span></label>
            <input type="text" name="nama_type" class="form-control"
                value="' . $data->nama . '" placeholder="Nama Type">
            <div id="err_nama_type"></div>
        </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-warning" form="fm_editType" data-el="#fm_editType">Update</button>';
        $title = '<i class="fas fa-edit"></i> Edit Data Type (' . $request->nama . ')';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }

    public function showHistoryType(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('type_history as th')
                ->join('type as t', 't.id', '=', 'th.type_id')
                ->join('users as u', 'u.id', '=', 'th.author_id')
                ->where('th.type_id', $id)
                ->select('th.*', 'u.nama')
                ->orderBy('th.id', 'desc')
                ->paginate(2);

            if (!$data->isEmpty()) {
                $html .= '<div class="tickets-list" id="dataHistory">';
                foreach ($data as $d) {
                    $content = json_decode($d->content);
                    switch ($d->type_history) {
                        case 'Create':
                            $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Type <b class="text-dark">' . $content->nama_type  . '</b> dengan kode <b class="text-dark">' . $content->kode_type . '</b> ditambahkan .</span>
                            </div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                            </div>
                            </span>';
                            break;
                        case 'Update':
                            $html .= '<span class="ticket-item" id="newAppend">
                                <div class="ticket-title">
                                    <span><span class="bullet"></span> Type <b class="text-dark">' . $content->nama_type_history . '</b> diubah menjadi <b class="text-dark">' . $content->nama_type_new . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data Type dihapus pada <b class="text-dark">' . Carbon::parse($d->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                        <span><span class="bullet"></span> Data Type direstore pada <b class="text-dark">' . Carbon::parse($d->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                $html .= '</div>';
            }

            $title = '<i class="fas fa-history"></i> History Type (' . $request->nama . ')';
            $footer = '<button type="button" class="d-block btn btn-sm btn-outline-primary btn-block load-more" id="load_more"
        data-paginate="2" data-id="">Load more</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>';
            return [
                'content' => $html,
                'footer' => $footer,
                'title' => $title
            ];
        }
    }
}
