<?php

namespace App\Http\Controllers\MasterData;

use App\Events\MasterDataEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Gate, Validator};
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class RackPembelianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('purchase_rack_master')->whereNull('deleted_at')->orderBy('nama', 'asc')->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_rack', function ($data) {
                    return $data->nama;
                })
                ->addColumn('location', function ($data) {
                    return $data->location ?? '-';
                })
                ->addColumn('tgl_dibuat', function ($data) {
                    return Carbon::parse($data->created_at)->translatedFormat('d M Y, H:i');
                })
                ->addColumn('dibuat_oleh', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('diubah_terakhir', function ($data) {
                    return $data->updated_at ? Carbon::parse($data->updated_at)->translatedFormat('d M Y, H:i') : '-';
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
                    $historyData = DB::table('purchase_rack_master_history')->where('rack_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 tooltip-rack" data-type="history" data-toggle="modal" data-target="#md_Rack" data-backdrop="static" data-id="' . $data->id . '" data-nama="' . $data->nama . '" title="' . ucfirst($data->nama) . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) {
                    if (Gate::allows('do_update', 'ubah-rack-pembelian')) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1 tooltip-rack"
                                    data-type="edit" data-toggle="modal" data-target="#md_Rack" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-rack-pembelian')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelRack btn-danger btn-icon mr-1 mt-1 tooltip-rack"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (!Gate::allows('do_update', 'ubah-rack-pembelian') && !Gate::allows('do_delete', 'hapus-rack-pembelian')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'nama_rack',
                    'location',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'diubah_terakhir',
                    'diubah_oleh',
                    'history',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.rack_pembelian.index', [
            'title' => 'Master Data Rack Pembelian',
        ]);
    }

    public function callAjax(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->cat) {
                case 'add':
                    return $this->showCreateRack();
                    break;
                case 'edit':
                    return $this->showEditRack($request);
                    break;
                case 'history':
                    return $this->showHistoryRack($request);
                    break;
                case 'check-duplicate-rack':
                    return $this->checkDuplicateRack($request);
                    break;
                default:
                    abort(500);
                    break;
            }
        }
    }

    public function createRack(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $validator = Validator::make($request->all(), [
                    'nama_rack' => 'required|string|unique:purchase_rack_master,nama'
                ], [
                    'unique' => 'Rack sudah terdaftar!'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors()
                    ]);
                }

                try {
                    $id = Uuid::uuid4()->toString();
                    $content = [
                        'nama_rack' => $request->nama_rack,
                        'location_rack' => $request->location_rack,
                    ];
                    $input = [
                        'params' => 'Create Rack Pembelian',
                        'id' => $id,
                        'content' => $content,
                        'created_by' => auth()->user()->id
                    ];

                    DB::beginTransaction();
                    event(new MasterDataEvent($input));
                    $insert = [
                        'params' => 'Insert History Create or Update Rack Pembelian',
                        'rack_id' => $id,
                        'type_history' => 'Create',
                        'content' => json_encode($content),
                        'author_id' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data rack pembelian berhasil ditambahkan!'
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

    public function updateRack(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $validator = Validator::make($request->all(), [
                    'nama_rack' => 'required|string|unique:purchase_rack_master,nama,' . $request->id
                ], [
                    'unique' => 'Rack sudah terdaftar!'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors()
                    ]);
                }

                try {
                    $history = DB::table('purchase_rack_master')->where('id', $request->id)->first();
                    $content = [
                        'nama_rack_history' => $history->nama,
                        'nama_rack_new' => $request->nama_rack,
                        'location_rack_history' => $history->location,
                        'location_rack_new' => $request->location_rack
                    ];
                    $update = [
                        'params' => 'Update Rack Pembelian',
                        'id' => $request->id,
                        'content' => $content,
                        'updated_by' => auth()->user()->id
                    ];
                    DB::beginTransaction();
                    event(new MasterDataEvent($update));
                    $insert = [
                        'params' => 'Insert History Create or Update Rack Pembelian',
                        'rack_id' => $request->id,
                        'type_history' => 'Update',
                        'content' => json_encode($content),
                        'author_id' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data rack pembelian berhasil diubah!'
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
        $data = DB::table('purchase_rack_master')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function deleteRack(Request $request)
    {
        try {
            $history = DB::table('purchase_rack_master')->where('id', $request->id)->first();
            $content = [
                'nama_rack' => $history->nama,
                'location_rack' => $history->location,
                'deleted_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
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
                'params' => 'Insert History Delete Rack Pembelian',
                'rack_id' => $request->id,
                'type_history' => 'Delete',
                'content' => json_encode($content),
                'deleted_at' => $content['deleted_at'],
                'author_id' => auth()->user()->id
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data rack pembelian!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function rackTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('purchase_rack_master')->whereNotNull('deleted_at')->orderBy('nama', 'asc')->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_rack', function ($data) {
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
                    return $data->deleted_at ? Carbon::parse($data->deleted_at)->translatedFormat('d M Y, H:i') : '-';
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
                    if (Gate::allows('do_delete', 'hapus-rack-pembelian')) {
                        $btn = '<a href="#"
                        class="d-block btn btn-sm btn_ResRack btn-dark btn-icon"
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
                    'nama_rack',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.rack_pembelian.telah_dihapus', [
            'title' => 'Rack Pembelian Telah Dihapus',
        ]);
    }

    public function restoreRack(Request $request)
    {
        try {
            $restored = DB::table('purchase_rack_master')->where('id', $request->id)->first();
            $content = [
                'nama_rack' => $restored->nama,
                'location_rack' => $restored->location,
                'restored_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            ];
            $insert = [
                'params' => 'Insert History Restored Rack Pembelian',
                'rack_id' => $request->id,
                'type_history' => 'Restore',
                'content' => json_encode($content),
                'author_id' => auth()->user()->id
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengembalikan rack pembelian!'
            ]);
        } catch (\Throwable $err) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $err->getMessage()
            ]);
        }
    }

    protected function checkDuplicateRack($request)
    {
        $check = DB::table('purchase_rack_master')->where('nama', $request->nama_rack)->exists();
        $res = TRUE;
        if ($check) {
            $res = FALSE;
        }
        return response()->json($res, 200);
    }

    protected function showCreateRack()
    {
        $html = '';
        $html .= '<form id="fm_addRack">';
        $html .= csrf_field();
        $html .= '<div class="form-group">
                <label class="col-form-label">Nama Rak <span class="text-danger">*</span></label>
                <input type="text" name="nama_rack" class="form-control"
                    placeholder="Nama Rak">
                <div id="err_nama_rack"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Lokasi Rak <span class="text-danger">*</span></label>
                <input type="text" name="location_rack" class="form-control"
                    placeholder="Lokasi Rak">
                <div id="err_location_rack"></div>
            </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-success" form="fm_addRack" data-el="#fm_addRack">Simpan</button>';
        $title = '<i class="fas fa-plus"></i> Tambah Data Rak';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }

    protected function showEditRack($request)
    {
        $data = DB::table('purchase_rack_master')->where('id', $request->id)->first();
        $html = '';
        $html .= '<form id="fm_editRack">';
        $html .= csrf_field();
        $html .= '<input type="hidden" name="id" value="' . $data->id . '">
            <div class="form-group">
                <label class="col-form-label">Nama Rak <span class="text-danger">*</span></label>
                <input type="text" name="nama_rack" class="form-control"
                    value="' . $data->nama . '" placeholder="Nama Rak">
                <div id="err_nama_rack"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Lokasi Rak <span class="text-danger">*</span></label>
                <input type="text" name="location_rack" class="form-control"
                    value="' . $data->location . '" placeholder="Lokasi Rak">
                <div id="err_location_rack"></div>
            </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-warning" form="fm_editRack" data-el="#fm_editRack">Update</button>';
        $title = '<i class="fas fa-edit"></i> Edit Data Rak (' . $request->nama . ')';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }

    public function showHistoryRack(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $data = DB::table('purchase_rack_master_history as prmh')
                ->join('purchase_rack_master as prm', 'prm.id', '=', 'prmh.rack_id')
                ->join('users as u', 'u.id', '=', 'prmh.author_id')
                ->where('prmh.rack_id', $request->id)
                ->select('prmh.*', 'u.nama')
                ->orderBy('prmh.id', 'desc')
                ->paginate(2);

            if (!$data->isEmpty()) {
                $html .= '<div class="tickets-list" id="dataHistory">';
                foreach ($data as $d) {
                    $content = json_decode($d->content);
                    switch ($d->type_history) {
                        case 'Create':
                            $html .= '<span class="ticket-item" id="newAppend">
                                <div class="ticket-title">
                                    <span><span class="bullet"></span> Rak <b class="text-dark">' . $content->nama_rack  . '</b> ditambahkan .</span>
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
                                    <span class="d-block"><span class="bullet"></span> Nama Rak <b class="text-dark">' . $content->nama_rack_history . '</b> diubah menjadi <b class="text-dark">' . $content->nama_rack_new . '</b>.</span>
                                    <span><span class="bullet"></span> Lokasi Rak <b class="text-dark">' . $content->location_rack_history . '</b> diubah menjadi <b class="text-dark">' . $content->location_rack_new . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data rak dihapus pada <b class="text-dark">' . Carbon::parse($content->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data rak direstore pada <b class="text-dark">' . Carbon::parse($content->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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

            $title = '<i class="fas fa-history"></i> History Rack (' . $request->nama . ')';
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
