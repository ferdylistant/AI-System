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

class CategoryGudangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('purchase_category_master')->whereNull('deleted_at')->orderBy('nama', 'asc')->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_category', function ($data) {
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
                    $historyData = DB::table('purchase_category_master_history')->where('category_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 tooltip-category" data-type="history" data-toggle="modal" data-target="#md_Category" data-backdrop="static" data-id="' . $data->id . '" data-nama="' . $data->nama . '" title="' . ucfirst($data->nama) . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) {
                    if (Gate::allows('do_update', 'ubah-category')) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1 tooltip-category"
                                    data-type="edit" data-toggle="modal" data-target="#md_Category" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-category')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelCategory btn-danger btn-icon mr-1 mt-1 tooltip-category"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (!Gate::allows('do_update', 'ubah-category') && !Gate::allows('do_delete', 'hapus-category')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'nama_category',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'diubah_terakhir',
                    'diubah_oleh',
                    'history',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.category_gudang_material.index', [
            'title' => 'Master Data Category Gudang Material',
        ]);
    }

    public function callAjax(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->cat) {
                case 'add':
                    return $this->showCreateCategory();
                    break;
                case 'edit':
                    return $this->showEditCategory($request);
                    break;
                case 'history':
                    return $this->showHistoryCategory($request);
                    break;
                case 'check-duplicate-category':
                    return $this->checkDuplicateCategory($request);
                    break;
                default:
                    abort(500);
                    break;
            }
        }
    }

    public function createCategory(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $validator = Validator::make($request->all(), [
                    'nama_category' => 'required|string|unique:purchase_category_master,nama'
                ], [
                    'unique' => 'Category sudah terdaftar!'
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
                        'nama_category' => $request->nama_category,
                    ];
                    $input = [
                        'params' => 'Create Category Gudang Material',
                        'id' => $id,
                        'content' => $content,
                        'created_by' => auth()->user()->id
                    ];

                    DB::beginTransaction();
                    event(new MasterDataEvent($input));
                    $insert = [
                        'params' => 'Insert History Create or Update Category Gudang Material',
                        'category_id' => $id,
                        'type_history' => 'Create',
                        'content' => json_encode($content),
                        'author_id' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data category berhasil ditambahkan!'
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

    public function updateCategory(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $validator = Validator::make($request->all(), [
                    'nama_category' => 'required|string|unique:purchase_category_master,nama,' . $request->id
                ], [
                    'unique' => 'Category sudah terdaftar!'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors()
                    ]);
                }

                try {
                    $history = DB::table('purchase_category_master')->where('id', $request->id)->first();
                    $content = [
                        'nama_category_history' => $history->nama,
                        'nama_category_new' => $request->nama_category
                    ];
                    $update = [
                        'params' => 'Update Category Gudang Material',
                        'id' => $request->id,
                        'content' => $content,
                        'updated_by' => auth()->user()->id
                    ];
                    DB::beginTransaction();
                    event(new MasterDataEvent($update));
                    $insert = [
                        'params' => 'Insert History Create or Update Category Gudang Material',
                        'category_id' => $request->id,
                        'type_history' => 'Update',
                        'content' => json_encode($content),
                        'author_id' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data category berhasil diubah!'
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
        $data = DB::table('purchase_category_master')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function deleteCategory(Request $request)
    {
        try {
            $history = DB::table('purchase_category_master')->where('id', $request->id)->first();
            $content = [
                'nama_category' => $history->nama,
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
                'params' => 'Insert History Delete Category Gudang Material',
                'category_id' => $request->id,
                'type_history' => 'Delete',
                'content' => json_encode($content),
                'deleted_at' => $content['deleted_at'],
                'author_id' => auth()->user()->id
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data category!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function categoryTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('purchase_category_master')->whereNotNull('deleted_at')->orderBy('nama', 'asc')->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_category', function ($data) {
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
                    if (Gate::allows('do_delete', 'hapus-category')) {
                        $btn = '<a href="#"
                        class="d-block btn btn-sm btn_ResCategory btn-dark btn-icon"
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
                    'nama_category',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.category_gudang_material.telah_dihapus', [
            'title' => 'Category Gudang Material Telah Dihapus',
        ]);
    }

    public function restoreCategory(Request $request)
    {
        try {
            $restored = DB::table('purchase_category_master')->where('id', $request->id)->first();
            $content = [
                'nama_category' => $restored->nama,
                'restored_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            ];
            $insert = [
                'params' => 'Insert History Restored Category Gudang Material',
                'category_id' => $request->id,
                'type_history' => 'Restore',
                'content' => json_encode($content),
                'author_id' => auth()->user()->id
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengembalikan category!'
            ]);
        } catch (\Throwable $err) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $err->getMessage()
            ]);
        }
    }

    protected function checkDuplicateCategory($request)
    {
        $check = DB::table('purchase_category_master')->where('nama', $request->nama_category)->exists();
        $res = TRUE;
        if ($check) {
            $res = FALSE;
        }
        return response()->json($res, 200);
    }

    protected function showCreateCategory()
    {
        $html = '';
        $html .= '<form id="fm_addCategory">';
        $html .= csrf_field();
        $html .= '<div class="form-group">
                <label class="col-form-label">Nama Category <span class="text-danger">*</span></label>
                <input type="text" name="nama_category" class="form-control"
                    placeholder="Nama Category">
                <div id="err_nama_category"></div>
            </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-success" form="fm_addCategory" data-el="#fm_addCategory">Simpan</button>';
        $title = '<i class="fas fa-plus"></i> Tambah Data Category';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }

    protected function showEditCategory($request)
    {
        $data = DB::table('purchase_category_master')->where('id', $request->id)->first();
        $html = '';
        $html .= '<form id="fm_editCategory">';
        $html .= csrf_field();
        $html .= '<input type="hidden" name="id" value="' . $data->id . '">
            <div class="form-group">
                <label class="col-form-label">Nama Category <span class="text-danger">*</span></label>
                <input type="text" name="nama_category" class="form-control"
                    value="' . $data->nama . '" placeholder="Nama Category">
                <div id="err_nama_category"></div>
            </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-warning" form="fm_editCategory" data-el="#fm_editCategory">Update</button>';
        $title = '<i class="fas fa-edit"></i> Edit Data Category (' . $request->nama . ')';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }

    public function showHistoryCategory(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $data = DB::table('purchase_category_master_history as pcmh')
                ->join('purchase_category_master as pcm', 'pcm.id', '=', 'pcmh.category_id')
                ->join('users as u', 'u.id', '=', 'pcmh.author_id')
                ->where('pcmh.category_id', $request->id)
                ->select('pcmh.*', 'u.nama')
                ->orderBy('pcmh.id', 'desc')
                ->paginate(2);

            if (!$data->isEmpty()) {
                $html .= '<div class="tickets-list" id="dataHistory">';
                foreach ($data as $d) {
                    $content = json_decode($d->content);
                    switch ($d->type_history) {
                        case 'Create':
                            $html .= '<span class="ticket-item" id="newAppend">
                                <div class="ticket-title">
                                    <span><span class="bullet"></span> Category <b class="text-dark">' . $content->nama_category  . '</b> ditambahkan .</span>
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
                                    <span><span class="bullet"></span> Nama Category <b class="text-dark">' . $content->nama_category_history . '</b> diubah menjadi <b class="text-dark">' . $content->nama_category_new . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data category dihapus pada <b class="text-dark">' . Carbon::parse($content->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data category direstore pada <b class="text-dark">' . Carbon::parse($content->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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

            $title = '<i class="fas fa-history"></i> History Category (' . $request->nama . ')';
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
