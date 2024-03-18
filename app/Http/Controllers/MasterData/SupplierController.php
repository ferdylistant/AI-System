<?php

namespace App\Http\Controllers\MasterData;

use App\Events\MasterDataEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Gate, Http};
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('supplier')
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
                ->addColumn('nama_supplier', function ($data) {
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
                    if (Gate::allows('do_update', 'ubah-supplier')) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1 tooltip-supplier"
                                    data-type="edit" data-toggle="modal" data-target="#md_Supplier" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-supplier')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelSupplier btn-danger btn-icon mr-1 mt-1 tooltip-supplier"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (!Gate::allows('do_update', 'ubah-supplier') && !Gate::allows('do_delete', 'hapus-supplier')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'kode',
                    'nama_supplier',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'diubah_terakhir',
                    'diubah_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.supplier.index', [
            'title' => 'Master Data Supplier',
        ]);
    }

    public function callAjax(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->cat) {
                case 'add':
                    return $this->showCreateSupplier();
                    break;
                case 'edit':
                    return $this->showEditSupplier($request);
                    break;
                case 'select-wilayah':
                    return $this->selectWilayah($request);
                    break;
                case 'select-sub-wilayah':
                    return $this->selectSubWilayah($request);
                    break;
                default:
                    abort(500);
                    break;
            }
        }
    }

    public function createSupplier(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $last = DB::table('supplier')->orderBy('created_at', 'desc')->first();
                if (is_null($last)) {
                    $kode = '0001';
                } else {
                    $kode = sprintf('%04d', (int)$last->kode + 1);
                }

                try {
                    $input = [
                        'params' => 'Create Supplier',
                        'id' => Uuid::uuid4()->toString(),
                        'kode_supplier' => $kode,
                        'nama_supplier' => $request->nama_supplier,
                        'alamat_supplier' => $request->alamat_supplier,
                        'wilayah_supplier' => $request->wilayah_supplier,
                        'sub_wilayah_supplier' => $request->sub_wilayah_supplier,
                        'telepon_supplier' => $request->telepon_supplier,
                        'fax_supplier' => $request->fax_supplier,
                        'kontak_supplier' => $request->kontak_supplier,
                        'npwp_supplier' => $request->npwp_supplier,
                        'created_by' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($input));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data supplier berhasil ditambahkan!'
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

    public function updateSupplier(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('supplier')->where('id', $request->id)->first();
                    $update = [
                        'params' => 'Update Supplier',
                        'id' => $request->id,
                        'nama_supplier' => $request->edit_nama_supplier,
                        'alamat_supplier' => $request->edit_alamat_supplier,
                        'wilayah_supplier' => $request->edit_wilayah_supplier ?? $history->wilayah,
                        'sub_wilayah_supplier' => $request->edit_sub_wilayah_supplier ?? $history->sub_wilayah,
                        'telepon_supplier' => $request->edit_telepon_supplier,
                        'fax_supplier' => $request->edit_fax_supplier,
                        'kontak_supplier' => $request->edit_kontak_supplier,
                        'npwp_supplier' => $request->edit_npwp_supplier,
                        'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                        'updated_by' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($update));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data supplier berhasil diubah!'
                    ]);
                } catch (\Throwable $err) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $err->getMessage(),
                    ]);
                }
            }
        }
        $data = DB::table('supplier')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function deleteSupplier(Request $request)
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
                'params' => 'Delete Supplier',
                'supplier_id' => $request->id,
                'deleted_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'deleted_by' => auth()->user()->id
            ];
            event(new MasterDataEvent($delete));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data supplier!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function supplierTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('supplier')->whereNotNull('deleted_at')->orderBy('nama', 'asc')->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('nama_supplier', function ($data) {
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
                    if (Gate::allows('do_delete', 'hapus-supplier')) {
                        $btn = '<a href="#"
                        class="d-block btn btn-sm btn_ResSupplier btn-dark btn-icon"
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
                    'nama_supplier',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.supplier.telah_dihapus', [
            'title' => 'Supplier Telah Dihapus',
        ]);
    }

    public function restoreSupplier(Request $request)
    {
        try {
            $restore = [
                'params' => 'Restore Supplier',
                'supplier_id' => $request->id
            ];
            event(new MasterDataEvent($restore));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengembalikan supplier!'
            ]);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => 'error',
                'message' => $err->getMessage()
            ]);
        }
    }

    public function selectWilayah($request)
    {
        $data = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')->json();
        if ($request->has('term')) {
            $term = strtolower($request->input('term'));
            $data = collect($data)->filter(function ($item) use ($term) {
                return strpos(strtolower($item['name']), $term) !== false;
            })->values();
        }
        return response()->json($data);
    }

    public function selectSubWilayah($request)
    {
        $data = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/$request->id.json")->json();
        if ($request->has('term')) {
            $term = strtolower($request->input('term'));
            $data = collect($data)->filter(function ($item) use ($term) {
                return strpos(strtolower($item['name']), $term) !== false;
            })->values();
        }
        return response()->json($data);
    }

    protected function showCreateSupplier()
    {
        $html = '';
        $html .= '<form id="fm_addSupplier">';
        $html .= csrf_field();
        $html .= '<div class="form-group">
                <label class="col-form-label">Nama Supplier <span class="text-danger">*</span></label>
                <input type="text" name="nama_supplier" class="form-control"
                    placeholder="Nama Supplier">
                <div id="err_nama_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Alamat Supplier <span class="text-danger">*</span></label>
                <input type="text" name="alamat_supplier" class="form-control"
                    placeholder="Alamat Supplier">
                <div id="err_alamat_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Wilayah <span class="text-danger">*</span></label>
                <select id="add_select_wilayah" name="wilayah_supplier" class="form-control">
                    <option label="Pilih wilayah"></option>
                </select>
                <div id="err_wilayah_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Sub Wilayah <span class="text-danger">*</span></label>
                <select id="add_select_sub_wilayah" name="sub_wilayah_supplier" class="form-control select-sub-wilayah">
                    <option label="Pilih sub wilayah"></option>
                </select>
                <div id="err_sub_wilayah_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Telepon <span class="text-danger">*</span></label>
                <input type="text" name="telepon_supplier" class="form-control"
                    placeholder="Telepon">
                <div id="err_telepon_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Fax <span class="text-danger">*</span></label>
                <input type="text" name="fax_supplier" class="form-control"
                    placeholder="Fax">
                <div id="err_fax_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Kontak <span class="text-danger">*</span></label>
                <input type="text" name="kontak_supplier" class="form-control"
                    placeholder="Kontak">
                <div id="err_kontak_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">NPWP <span class="text-danger">*</span></label>
                <input type="text" name="npwp_supplier" id="npwp" class="form-control"
                    placeholder="NPWP">
                <div id="err_npwp_supplier"></div>
            </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-success" form="fm_addSupplier" data-el="#fm_addSupplier">Simpan</button>';
        $title = '<i class="fas fa-plus"></i> Tambah Data Supplier';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }

    protected function showEditSupplier($request)
    {
        $data = DB::table('supplier')->where('id', $request->id)->first();
        $html = '';
        $html .= '<form id="fm_editSupplier">';
        $html .= csrf_field();
        $html .= '<input type="hidden" name="id" value="' . $data->id . '">
            <div class="form-group">
                <label class="col-form-label">Kode Supplier <span class="text-danger">*</span></label>
                <input type="number" name="kode_supplier" class="form-control"
                    value="' . $data->kode . '" placeholder="Kode Supplier" readonly>
                <div id="err_kode_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Nama Supplier <span class="text-danger">*</span></label>
                <input type="text" name="edit_nama_supplier" class="form-control"
                    value="' . $data->nama . '" placeholder="Nama Supplier">
                <div id="err_edit_nama_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Alamat Supplier <span class="text-danger">*</span></label>
                <input type="text" name="edit_alamat_supplier" class="form-control"
                    value="' . $data->alamat . '" placeholder="Alamat Supplier">
                <div id="err_edit_alamat_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Wilayah <span class="text-danger">*</span></label>
                <select id="edit_select_wilayah" name="edit_wilayah_supplier" class="form-control">
                    <option label="Pilih wilayah"></option>
                </select>
                <div id="err_edit_wilayah_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Sub Wilayah <span class="text-danger">*</span></label>
                <select id="edit_select_sub_wilayah" name="edit_sub_wilayah_supplier" class="form-control select-sub-wilayah">
                    <option label="Pilih sub wilayah"></option>
                </select>
                <div id="err_edit_sub_wilayah_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Telepon <span class="text-danger">*</span></label>
                <input type="text" name="edit_telepon_supplier" class="form-control"
                    value="' . $data->telepon . '" placeholder="Telepon">
                <div id="err_edit_telepon_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Fax <span class="text-danger">*</span></label>
                <input type="text" name="edit_fax_supplier" class="form-control"
                    value="' . $data->fax . '" placeholder="Fax">
                <div id="err_edit_fax_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">Kontak <span class="text-danger">*</span></label>
                <input type="text" name="edit_kontak_supplier" class="form-control"
                    value="' . $data->kontak . '" placeholder="Kontak">
                <div id="err_edit_kontak_supplier"></div>
            </div>
            <div class="form-group">
                <label class="col-form-label">NPWP <span class="text-danger">*</span></label>
                <input type="text" name="edit_npwp_supplier" id="npwp" class="form-control"
                    value="' . $data->npwp . '" placeholder="NPWP">
                <div id="err_edit_npwp_supplier"></div>
            </div>
        </form>';
        $footer = '<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-sm btn-warning" form="fm_editSupplier" data-el="#fm_editSupplier">Update</button>';
        $title = '<i class="fas fa-edit"></i> Edit Data Supplier (' . $request->nama . ')';
        return [
            'content' => $html,
            'footer' => $footer,
            'title' => $title
        ];
    }
}
