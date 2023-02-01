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

class KelompokBukuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('penerbitan_m_kelompok_buku')
                ->whereNull('deleted_at')
                ->orderBy('kode', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-kelompok-buku');
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
                ->addColumn('nama_kelompok_buku', function ($data) {
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
                    $historyData = DB::table('penerbitan_m_kelompok_buku_history')->where('kelompok_buku_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-nama="' . $data->nama . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1"
                                    data-toggle="modal" data-target="#md_EditKelompokBuku" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-kelompok-buku')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelKelompokBuku btn-danger btn-icon mr-1 mt-1"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (Auth::user()->cannot('do_update','ubah-kelompok-buku') && Auth::user()->cannot('do_delete','hapus-kelompok-buku')) {
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

        return view('master_data.kelompok_buku.index', [
            'title' => 'Kelompok Buku Penerbitan',
        ]);
    }

    public function kBukuTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('penerbitan_m_kelompok_buku')
                ->whereNotNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_delete', 'hapus-kelompok-buku');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('kelompok_buku', function ($data) {
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
                        class="d-block btn btn-sm btn_ResKBuku btn-dark btn-icon""
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
                    'kelompok_buku',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.kelompok_buku.telah_dihapus', [
            'title' => 'Kelompok Buku Telah Dihapus',
        ]);
    }

    public function createKbuku(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'nama_kelompok_buku' => 'required|unique:penerbitan_m_kelompok_buku,nama',
                ], [
                    'required' => 'This field is requried'
                ]);
                $last = DB::table('penerbitan_m_kelompok_buku')->whereNull('deleted_at')->orderBy('created_at', 'desc')->first();
                if (is_null($last)) {
                    $kode = 'KB1';
                } else {
                    // $last = DB::table('penerbitan_m_kelompok_buku')->whereNull('deleted_at')->max('kode')->first();
                    $lastId_ = (int)substr($last->kode, 2);
                    // return $lastId_;
                    $kode = 'KB' . $lastId_ += 1;
                }
                $id = Uuid::uuid4()->toString();
                $input = [
                    'params' => 'Create Kelompok Buku',
                    'id' => $id,
                    'kode' => $kode,
                    'nama_kelompok_buku' => $request->nama_kelompok_buku,
                    'created_by' => auth()->user()->id
                ];
                event(new MasterDataEvent($input));
                $insert = [
                    'params' => 'Insert History Create Kelompok Buku',
                    'kelompok_buku_id' => $id,
                    'type_history' => 'Create',
                    'kode' => $kode,
                    'nama_kelompok_buku' => $request->nama_kelompok_buku,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kelompok buku berhasil ditambahkan!',
                ]);
            }
        }
        return view('master_data.kelompok_buku.create', [
            'title' => 'Tambah Kelompok Buku'
        ]);
    }
    public function updateKbuku(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'edit_nama' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);
                $history = DB::table('penerbitan_m_kelompok_buku')->where('id', $request->edit_id)->first();
                $update = [
                    'params' => 'Update Kelompok Buku',
                    'id' => $request->edit_id,
                    'nama' => $request->edit_nama,
                    'updated_by' => auth()->user()->id
                ];
                event(new MasterDataEvent($update));
                $insert = [
                    'params' => 'Insert History Update Kelompok Buku',
                    'type_history' => 'Update',
                    'kelompok_buku_id' => $request->edit_id,
                    'kelompok_buku_history' => $history->nama,
                    'kelompok_buku_new' => $request->edit_nama,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kelompok buku berhasil diubah!',
                    'route' => route('kb.view')
                ]);
            }
        }
        $id = $request->id;
        $data = DB::table('penerbitan_m_kelompok_buku')
            ->where('id', $id)
            ->first();
        return response()->json($data);
    }
    public function deleteKbuku(Request $request)
    {
        try {
            $id = $request->id;
            //Check Relasi
            $relation = DB::table('penerbitan_naskah')->where('kelompok_buku_id', $id)->first();
            if (!is_null($relation)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kelompok buku tidak bisa dihapus karena telah terpakai di naskah yang sedang diproses!'
                ]);
            }
            $insert = [
                'params' => 'Insert History Delete Kelompok Buku',
                'kelompok_buku_id' => $id,
                'type_history' => 'Delete',
                'deleted_at' => date('Y-m-d H:i:s'),
                'author_id' => auth()->user()->id,
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data kelompok buku!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function restoreKBuku(Request $request)
    {
        $id = $request->id;
        $restored = DB::table('penerbitan_m_kelompok_buku')
            ->where('id', $id)
            ->update(['deleted_at' => null, 'deleted_by' => null]);
        $insert = [
            'params' => 'Insert History Restored Kelompok Buku',
            'kelompok_buku_id' => $id,
            'type_history' => 'Restore',
            'restored_at' => now(),
            'author_id' => auth()->user()->id,
            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
        ];
        event(new MasterDataEvent($insert));
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengembalikan kelompok buku!'
        ]);
    }
    public function lihatHistoryKBuku(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('penerbitan_m_kelompok_buku_history as kbh')
                ->join('penerbitan_m_kelompok_buku as kb', 'kb.id', '=', 'kbh.kelompok_buku_id')
                ->join('users as u', 'u.id', '=', 'kbh.author_id')
                ->where('kbh.kelompok_buku_id', $id)
                ->select('kbh.*', 'u.nama')
                ->orderBy('kbh.id', 'desc')
                ->paginate(2);

            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Create':
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Kelompok Buku <b class="text-dark">' . $d->kelompok_buku_name  . '</b> dengan kode <b class="text-dark">' . $d->kode . '</b> ditambahkan .</span>
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
                                <span><span class="bullet"></span> Kelompok Buku <b class="text-dark">' . $d->kelompok_buku_history . '</b> diubah menjadi <b class="text-dark">' . $d->kelompok_buku_new . '</b>.</span>
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
                                <span><span class="bullet"></span> Data Kelompok Buku dihapus pada <b class="text-dark">' . Carbon::parse($d->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data Kelompok Buku direstore pada <b class="text-dark">' . Carbon::parse($d->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
