<?php

namespace App\Http\Controllers\MasterData;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Events\MasterDataEvent;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\{DB, Gate};

class GolonganController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('golongan')
                ->whereNull('deleted_at')
                ->orderBy('kode', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-golongan');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('kode', function ($data) {
                    return $data->kode;
                })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                })
                ->addColumn('tgl_dibuat', function ($data) {
                    $date = Carbon::parse($data->created_at)->translatedFormat('d M Y, H:i');
                    // $date = date('d F Y, H:i', strtotime($data->created_at));
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
                        $date = Carbon::parse($data->updated_at)->translatedFormat('d M Y, H:i');
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
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('golongan_history')->where('golongan_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 tooltip-gol" data-type="history"
                        data-toggle="modal" data-target="#md_Golongan" data-backdrop="static" data-id="' . $data->id . '" data-nama="' . $data->nama . '" title="'.ucfirst($data->nama).'"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1 tooltip-gol" data-type="edit"
                                    data-toggle="modal" data-target="#md_Golongan" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-golongan')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelGolongan btn-danger btn-icon mr-1 mt-1 tooltip-gol"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (!Gate::allows('do_update', 'ubah-golongan') && !Gate::allows('do_delete', 'hapus-golongan')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
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
                    'history',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.golongan.index', [
            'title' => 'Master Golongan',
        ]);
    }
    public function callAjax(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->cat) {
                case 'add':
                    return $this->showCreateGolongan();
                    break;
                case 'edit':
                    return $this->showEditGolongan($request);
                    break;
                case 'history':
                    return $this->showHistoryGolongan($request);
                    break;
                default:
                    return abort(500);
                    break;
            }
        }
    }
    public function golonganTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('golongan')
                ->whereNotNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_delete', 'hapus-golongan');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('kode', function ($data) {
                    return $data->kode;
                })
                ->addColumn('golongan', function ($data) {
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
                        class="d-block btn btn-sm btn_ResGolongan btn-dark btn-icon""
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
                    'kode',
                    'golongan',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.golongan.telah_dihapus', [
            'title' => 'Golongan Telah Dihapus',
        ]);
    }
    public function createGolongan(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $last = DB::table('golongan')->orderBy('created_at', 'desc')->first();
                    if (is_null($last)) {
                        $kode = '01';
                    } else {
                        $kode = sprintf('%02d', (int)$last->kode + 1);
                    }
                    $content = [
                        'kode' => $kode,
                        'nama' => $request->nama_golongan
                    ];
                    $input = [
                        'params' => 'Create Golongan',
                        'content' => $content,
                        'created_by' => auth()->user()->id
                    ];

                    DB::beginTransaction();
                    event(new MasterDataEvent($input));
                    $insert = [
                        'params' => 'Insert History Create or Update Golongan',
                        'golongan_id' => DB::getPdo()->lastInsertId(),
                        'type_history' => 'Create',
                        'content' => json_encode($content),
                        'author_id' => auth()->user()->id,
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new MasterDataEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data golongan berhasil ditambahkan!'
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
        abort(404);
    }
    public function updateGolongan(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('golongan')->where('id', $request->id)->first();
                    $content = [
                        'kode' => $history->kode,
                        'nama_his' => $history->nama,
                        'nama_new' => $request->nama_golongan
                    ];
                    $update = [
                        'params' => 'Update Golongan',
                        'id' => $request->id,
                        'content' => $content,
                        'updated_by' => auth()->user()->id
                    ];
                    DB::beginTransaction();
                    event(new MasterDataEvent($update));
                    $insert = [
                        'params' => 'Insert History Create or Update Golongan',
                        'golongan_id' => $request->id,
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
    }
    public function deleteGolongan(Request $request)
    {
        try {
            $id = $request->id;
            $insert = [
                'params' => 'Insert History Delete Golongan',
                'golongan_id' => $id,
                'type_history' => 'Delete',
                'deleted_at' => date('Y-m-d H:i:s'),
                'author_id' => auth()->user()->id,
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data golongan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function restoreGolongan(Request $request)
    {
        $id = $request->id;
        $restored = DB::table('golongan')
            ->where('id', $id)
            ->update(['deleted_at' => null, 'deleted_by' => null]);
        $insert = [
            'params' => 'Insert History Restored Golongan',
            'golongan_id' => $id,
            'type_history' => 'Restore',
            'restored_at' => now(),
            'author_id' => auth()->user()->id,
        ];
        event(new MasterDataEvent($insert));
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengembalikan golongan!'
        ]);
    }
    protected function showCreateGolongan()
    {
        $html = '';
        $html .= '<form id="fm_addGolongan">';
        $html .= csrf_field();
        $html .= '<div class="form-group">
            <label class="col-form-label">Nama Golongan <span class="text-danger">*</span></label>
            <input type="text" name="nama_golongan" class="form-control"
                placeholder="Nama Golongan">
            <div id="err_nama_golongan"></div>
        </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-success" form="fm_addGolongan" data-el="#fm_addGolongan">Simpan</button>';
        $title = '<i class="fas fa-plus"></i> Tambah Data Golongan';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }
    protected function showEditGolongan($request)
    {
        $data = DB::table('golongan')->where('id', $request->id)->first();
        $html = '';
        $html .= '<form id="fm_editGolongan">';
        $html .= csrf_field();
        $html .= '<div class="form-group">
        <input type="hidden" name="id" value="' . $data->id . '">
            <label class="col-form-label">Kode Golongan <span class="text-danger">*</span></label>
            <input type="text" name="kode_golongan" class="form-control"
                value="' . $data->kode . '" placeholder="Kode Golongan" readonly>
            <div id="err_kode_golongan"></div>
        </div>
        <div class="form-group">
            <label class="col-form-label">Nama Golongan <span class="text-danger">*</span></label>
            <input type="text" name="nama_golongan" class="form-control"
                value="' . $data->nama . '" placeholder="Nama Golongan">
            <div id="err_nama_golongan"></div>
        </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-warning" form="fm_editGolongan" data-el="#fm_editGolongan">Update</button>';
        $title = '<i class="fas fa-edit"></i> Edit Data Golongan (' . $request->nama . ')';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }
    protected function showHistoryGolongan($request)
    {
        $html = '';
        $id = $request->id;
        $data = DB::table('golongan_history as gh')
            ->join('golongan as t', 't.id', '=', 'gh.golongan_id')
            ->join('users as u', 'u.id', '=', 'gh.author_id')
            ->where('gh.golongan_id', $id)
            ->select('gh.*', 'u.nama')
            ->orderBy('gh.id', 'desc')
            ->paginate(2);
        if (!$data->isEmpty()) {
            $html .= '<div class="tickets-list" id="dataHistory">';
            foreach ($data as $d) {
                $content = json_decode($d->content);
                switch ($d->type_history) {
                    case 'Create':
                        $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Golongan <b class="text-dark">' . $content->nama  . '</b> dengan kode <b class="text-dark">' . $content->kode . '</b> ditambahkan.</span>
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
                                    <span><span class="bullet"></span> Golongan <b class="text-dark">' . $content->nama_his . '</b> diubah menjadi <b class="text-dark">' . $content->nama_new . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data Golongan dihapus pada <b class="text-dark">' . Carbon::parse($d->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                        <span><span class="bullet"></span> Data Golongan direstore pada <b class="text-dark">' . Carbon::parse($d->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
        $title = '<i class="fas fa-history"></i> History Golongan (' . $request->nama . ')';
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
