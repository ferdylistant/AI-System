<?php

namespace App\Http\Controllers\MasterData;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Events\MasterDataEvent;
use Yajra\DataTables\DataTables;
use App\Events\UpdateImprintEvent;
use App\Events\UpdatePlatformEvent;
use App\Events\InsertImprintHistory;
use App\Http\Controllers\Controller;
use App\Events\InsertPlatformHistory;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class ImprintController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('imprint')
                ->whereNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-data-imprint');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_imprint', function ($data) {
                    return $data->nama;
                })
                ->addColumn('tgl_dibuat', function ($data) {
                    $date = date('d F Y, H:i', strtotime($data->created_at));
                    return $date;
                })
                ->addColumn('dibuat_oleh', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('diubah_terakhir', function ($data) {
                    if ($data->updated_at == null) {
                        return '-';
                    } else {
                        $date = date('d F Y, H:i', strtotime($data->updated_at));
                        return $date;
                    }
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('imprint_history')->where('imprint_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-nama="' . $data->nama . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
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
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1"
                                    data-toggle="modal" data-target="#md_EditImprint" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-data-imprint')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelImprint btn-danger btn-icon mr-1 mt-1"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (Auth::user()->cannot('do_update', 'ubah-data-imprint') && Auth::user()->cannot('do_delete', 'hapus-data-imprint')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
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
                    'history',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.imprint.index', [
            'title' => 'Imprint Penerbitan',
        ]);
    }
    public function imprintTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('imprint')
                ->whereNotNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_delete', 'hapus-data-imprint');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_imprint', function ($data) {
                    return $data->nama;
                })
                ->addColumn('tgl_dibuat', function ($data) {
                    $date = date('d M Y, H:i', strtotime($data->created_at));
                    return $date;
                })
                ->addColumn('dibuat_oleh', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('dihapus_pada', function ($data) {
                    if ($data->deleted_at == null) {
                        return '-';
                    } else {
                        $date = date('d M Y, H:i', strtotime($data->deleted_at));
                        return $date;
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
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="#"
                        class="d-block btn btn-sm btn_ResImprint btn-dark btn-icon""
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
                    'nama_imprint',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.imprint.telah_dihapus', [
            'title' => 'Imprint Telah Dihapus',
        ]);
    }
    public function createImprint(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'nama_imprint' => 'required|unique:imprint,nama',
                ], [
                    'required' => 'This field is requried'
                ]);
                $id = Uuid::uuid4()->toString();
                $input = [
                    'params' => 'Create Imprint',
                    'id' => $id,
                    'nama_imprint' => $request->nama_imprint,
                    'created_by' => auth()->user()->id
                ];
                // return response()->json(['data' => $input]);
                event(new MasterDataEvent($input));
                $insert = [
                    'params' => 'Insert History Create Imprint',
                    'imprint_id' => $id,
                    'type_history' => 'Create',
                    'nama_imprint' => $request->nama_imprint,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data imprint berhasil ditambahkan!',
                    // 'redirect' => route('produksi.view')
                ]);
            }
        }
        return view('master_data.imprint.create', [
            'title' => 'Tambah Imprint Penerbitan'
        ]);
    }
    public function updateImprint(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'edit_nama' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);
                $history = DB::table('imprint')->where('id', $request->edit_id)->first();
                $update = [
                    'params' => 'Update Imprint',
                    'id' => $request->edit_id,
                    'edit_nama' => $request->edit_nama,
                    'updated_by' => auth()->user()->id
                ];
                event(new MasterDataEvent($update));
                $insert = [
                    'params' => 'Insert History Update Imprint',
                    'imprint_id' => $request->edit_id,
                    'type_history' => 'Update',
                    'imprint_history' => $history->nama,
                    'imprint_new' => $request->edit_nama,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data imprint berhasil diubah!',
                ]);
            }
        }
        $id = $request->id;
        $data = DB::table('imprint')
            ->where('id', $id)
            ->first();
        return response()->json($data);
    }
    public function deleteImprint(Request $request)
    {
        try {
            $id = $request->id;
            // CHECK RELASI
            $relation = DB::table('deskripsi_produk')->where('imprint', $id)->first();
            if (!is_null($relation)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Imprint tidak bisa dihapus karena telah terpakai di naskah yang sedang diproses!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $insert = [
                'params' => 'Insert History Delete Imprint',
                'imprint_id' => $id,
                'type_history' => 'Delete',
                'deleted_at' => $tgl,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data imprint!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function restoreImprint(Request $request)
    {
        $id = $request->id;
        $restored = DB::table('imprint')
            ->where('id', $id)
            ->update(['deleted_at' => null, 'deleted_by' => null]);
        $insert = [
            'params' => 'Insert History Restored Imprint',
            'imprint_id' => $id,
            'type_history' => 'Restore',
            'restored_at' => now(),
            'author_id' => auth()->user()->id,
            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
        ];
        event(new MasterDataEvent($insert));
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengembalikan data imprint!'
        ]);
    }
    public function lihatHistoryImprint(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('imprint_history as ih')
                ->join('imprint as i', 'i.id', '=', 'ih.imprint_id')
                ->join('users as u', 'u.id', '=', 'ih.author_id')
                ->where('ih.imprint_id', $id)
                ->select('ih.*', 'u.nama')
                ->orderBy('ih.id', 'desc')
                ->paginate(2);

            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Create':
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Imprint <b class="text-dark">' . $d->imprint_name  . '</b> ditambahkan.</span>
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
                                <span><span class="bullet"></span> Imprint <b class="text-dark">' . $d->imprint_history . '</b> diubah menjadi <b class="text-dark">' . $d->imprint_new . '</b>.</span>
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
                                <span><span class="bullet"></span> Data imprint dihapus pada <b class="text-dark">' . Carbon::parse($d->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data imprint direstore pada <b class="text-dark">' . Carbon::parse($d->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
    public function indexPlatform(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('platform_digital_ebook')
                ->whereNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-platform-digital');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_platform', function ($data) {
                    return $data->nama;
                })
                ->addColumn('tgl_dibuat', function ($data) {
                    $date = date('d M Y, H:i', strtotime($data->created_at));
                    return $date;
                })
                ->addColumn('dibuat_oleh', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('diubah_terakhir', function ($data) {
                    if ($data->updated_at == null) {
                        return '-';
                    } else {
                        $date = date('d M Y, H:i', strtotime($data->updated_at));
                        return $date;
                    }
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('platform_digital_ebook_history')->where('platform_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-nama="' . $data->nama . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
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
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1"
                                    data-toggle="modal" data-target="#md_EditPlatformEbook" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-platform-digital')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelPlatform btn-danger btn-icon mr-1 mt-1"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (Auth::user()->cannot('do_update', 'ubah-platform-digital') && Auth::user()->cannot('do_delete', 'hapus-platform-digital')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'no',
                    'nama_platform',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'diubah_terakhir',
                    'history',
                    'diubah_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.platform_digital.index', [
            'title' => 'Platform Digital',
        ]);
    }
    public function platformTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('platform_digital_ebook')
                ->whereNotNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_delete', 'hapus-platform-digital');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_platform', function ($data) {
                    return $data->nama;
                })
                ->addColumn('tgl_dibuat', function ($data) {
                    $date = date('d M Y, H:i', strtotime($data->created_at));
                    return $date;
                })
                ->addColumn('dibuat_oleh', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('dihapus_pada', function ($data) {
                    if ($data->deleted_at == null) {
                        return '-';
                    } else {
                        $date = date('d M Y, H:i', strtotime($data->deleted_at));
                        return $date;
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
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="#"
                        class="d-block btn btn-sm btn_ResPlatform btn-dark btn-icon""
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
                    'nama_platform',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.platform_digital.telah_dihapus', [
            'title' => 'Platform Digital Telah Dihapus',
        ]);
    }
    public function createPlatform(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'nama_platform' => 'required|unique:platform_digital_ebook,nama',
                ], [
                    'required' => 'This field is requried'
                ]);
                $id = Uuid::uuid4()->toString();
                $input = [
                    'params' => 'Create Platform',
                    'id' => $id,
                    'nama_platform' => $request->nama_platform,
                    'created_by' => auth()->user()->id
                ];
                event(new MasterDataEvent($input));
                $insert = [
                    'params' => 'Insert History Create Platform',
                    'type_history' => 'Create',
                    'platform_id' => $id,
                    'platform_name' => $request->nama_platform,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data platform berhasil ditambahkan!',
                    // 'redirect' => route('produksi.view')
                ]);
            }
        }
    }
    public function updatePlatform(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'edit_nama' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);
                $history = DB::table('platform_digital_ebook')->where('id', $request->edit_id)->first();
                // return response()->json($history);
                $update = [
                    'params' => 'Update Platform',
                    'id' => $request->edit_id,
                    'nama' => $request->edit_nama,
                    'updated_by' => auth()->user()->id
                ];
                event(new MasterDataEvent($update));
                $insert = [
                    'params' => 'Insert History Update Platform',
                    'type_history' => 'Update',
                    'platform_id' => $request->edit_id,
                    'platform_history' => $history->nama,
                    'platform_new' => $request->edit_nama,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data platform berhasil diubah!',
                ]);
            }
            $id = $request->id;
            $data = DB::table('platform_digital_ebook')
                ->where('id', $id)
                ->first();
            return response()->json($data);
        }
    }
    public function deletePlatform(Request $request)
    {
        try {
            $id = $request->id;
            $pil_terbit = DB::table('pilihan_penerbitan')->get();
            foreach ($pil_terbit as $pt) {
                if (in_array($id, json_decode($pt->platform_digital_ebook_id))) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Platform digital tidak bisa dihapus karena telah terpakai di naskah yang sedang diproses!'
                    ]);
                }
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $insert = [
                'params' => 'Insert History Delete Platform',
                'platform_id' => $id,
                'type_history' => 'Delete',
                'deleted_at' => $tgl,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data platform!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function restorePlatform(Request $request)
    {
        $id = $request->id;
        $restored = DB::table('platform_digital_ebook')
            ->where('id', $id)
            ->update(['deleted_at' => null, 'deleted_by' => null]);
        $insert = [
            'params' => 'Insert History Restored Platform',
            'platform_id' => $id,
            'type_history' => 'Restore',
            'restored_at' => now(),
            'author_id' => auth()->user()->id,
            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
        ];
        event(new MasterDataEvent($insert));
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengembalikan data platform!'
        ]);
    }

    public function lihatHistoryPlatform(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('platform_digital_ebook_history as pdeh')
                ->join('platform_digital_ebook as pde', 'pde.id', '=', 'pdeh.platform_id')
                ->join('users as u', 'u.id', '=', 'pdeh.author_id')
                ->where('pdeh.platform_id', $id)
                ->select('pdeh.*', 'u.nama')
                ->orderBy('pdeh.id', 'desc')
                ->paginate(2);

            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Create':
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Platform digital <b class="text-dark">' . $d->platform_name  . '</b> ditambahkan.</span>
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
                                <span><span class="bullet"></span> Platform digital <b class="text-dark">' . $d->platform_history . '</b> diubah menjadi <b class="text-dark">' . $d->platform_new . '</b>.</span>
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
                                <span><span class="bullet"></span> Data platform digital dihapus pada <b class="text-dark">' . Carbon::parse($d->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data platform digital direstore pada <b class="text-dark">' . Carbon::parse($d->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
}
