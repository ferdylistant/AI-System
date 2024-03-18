<?php

namespace App\Http\Controllers\MasterData;

use App\Events\MasterDataEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Gate, Validator};
use Yajra\DataTables\DataTables;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('satuan')->whereNull('deleted_at')->orderBy('nama', 'asc')->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_satuan', function ($data) {
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
                ->addColumn('action', function ($data) {
                    if (Gate::allows('do_update', 'ubah-satuan')) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1 tooltip-satuan"
                                    data-type="edit" data-toggle="modal" data-target="#md_Satuan" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-satuan')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelSatuan btn-danger btn-icon mr-1 mt-1 tooltip-satuan"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (!Gate::allows('do_update', 'ubah-satuan') && !Gate::allows('do_delete', 'hapus-satuan')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'nama_satuan',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'diubah_terakhir',
                    'diubah_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.satuan.index', [
            'title' => 'Master Data Satuan',
        ]);
    }

    public function callAjax(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->cat) {
                case 'add':
                    return $this->showCreateSatuan();
                    break;
                case 'edit':
                    return $this->showEditSatuan($request);
                    break;
                case 'check-duplicate-name':
                    return $this->checkDuplicateSatuan($request);
                    break;
                default:
                    abort(500);
                    break;
            }
        }
    }

    public function createSatuan(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $validator = Validator::make($request->all(), [
                    'nama_satuan' => 'required|string|unique:satuan,nama'
                ], [
                    'unique' => 'Satuan sudah terdaftar!'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors()
                    ]);
                }

                try {
                    $input = [
                        'params' => 'Create Satuan',
                        'nama_satuan' => $request->nama_satuan,
                        'created_by' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($input));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data satuan berhasil ditambahkan!'
                    ]);
                } catch (\Throwable $err) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $err->getMessage(),
                    ]);
                }
            }
        }
    }

    public function updateSatuan(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $validator = Validator::make($request->all(), [
                    'nama_satuan' => 'required|string|unique:satuan,nama,' . $request->id
                ], [
                    'unique' => 'Satuan sudah terdaftar!'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors()
                    ]);
                }

                try {
                    $update = [
                        'params' => 'Update Satuan',
                        'id' => $request->id,
                        'nama_satuan' => $request->nama_satuan,
                        'updated_by' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($update));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data satuan berhasil diubah!'
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
        $data = DB::table('satuan')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function deleteSatuan(Request $request)
    {
        try {
            // Check Relasi
            // $relation = DB::table('penerbitan_naskah')->where('kelompok_buku_id', $request->id)->first();
            // if (!is_null($relation)) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Type tidak bisa dihapus karena telah terpakai di naskah yang sedang diproses!'
            //     ]);
            // }
            $delete = [
                'params' => 'Delete Satuan',
                'id' => $request->id,
                'deleted_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'deleted_by' => auth()->user()->id
            ];
            event(new MasterDataEvent($delete));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data satuan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function satuanTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('satuan')->whereNotNull('deleted_at')->orderBy('nama', 'asc')->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_satuan', function ($data) {
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
                    if (Gate::allows('do_delete', 'hapus-satuan')) {
                        $btn = '<a href="#"
                        class="d-block btn btn-sm btn_ResSatuan btn-dark btn-icon"
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
                    'nama_satuan',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.satuan.telah_dihapus', [
            'title' => 'Satuan Telah Dihapus',
        ]);
    }

    public function restoreSatuan(Request $request)
    {
        try {
            $restore = [
                'params' => 'Restore Satuan',
                'id' => $request->id
            ];
            event(new MasterDataEvent($restore));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengembalikan satuan!'
            ]);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => $err->getMessage()
            ]);
        }
    }

    protected function checkDuplicateSatuan($request)
    {
        $nama = $request->nama_satuan;
        $check = DB::table('satuan')->where('nama', $nama)->exists();
        $res = TRUE;
        if ($check) {
            $res = FALSE;
        }
        return response()->json($res, 200);
    }

    protected function showCreateSatuan()
    {
        $html = '';
        $html .= '<form id="fm_addSatuan">';
        $html .= csrf_field();
        $html .= '<div class="form-group">
            <label class="col-form-label">Nama Satuan <span class="text-danger">*</span></label>
            <input type="text" name="nama_satuan" class="form-control"
                placeholder="Nama Satuan">
            <div id="err_nama_satuan"></div>
        </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-success" form="fm_addSatuan" data-el="#fm_addSatuan">Simpan</button>';
        $title = '<i class="fas fa-plus"></i> Tambah Data Satuan';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }

    protected function showEditSatuan($request)
    {
        $data = DB::table('satuan')->where('id', $request->id)->first();
        $html = '';
        $html .= '<form id="fm_editSatuan">';
        $html .= csrf_field();
        $html .= '<input type="hidden" name="id" value="' . $data->id . '">
        <div class="form-group">
            <label class="col-form-label">Nama Satuan <span class="text-danger">*</span></label>
            <input type="text" name="nama_satuan" class="form-control"
                value="' . $data->nama . '" placeholder="Nama Satuan">
            <div id="err_nama_satuan"></div>
        </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-warning" form="fm_editSatuan" data-el="#fm_editSatuan">Update</button>';
        $title = '<i class="fas fa-edit"></i> Edit Data Satuan (' . $request->nama . ')';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }
}
