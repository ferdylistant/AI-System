<?php

namespace App\Http\Controllers\MasterData;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Events\MasterDataEvent;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class FormatBukuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('format_buku')
                ->whereNull('deleted_at')
                ->orderBy('jenis_format', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-format-buku');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('jenis_format', function ($data) {
                    return $data->jenis_format;
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
                    $historyData = DB::table('format_buku_history')->where('format_buku_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-nama="' . $data->jenis_format . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1"
                                    data-toggle="modal" data-target="#md_EditFormatBuku" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->jenis_format . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-format-buku')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelFBuku btn-danger btn-icon mr-1 mt-1"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->jenis_format . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'no',
                    'jenis_format',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'diubah_terakhir',
                    'diubah_oleh',
                    'history',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.format_buku.index', [
            'title' => 'Format Buku Penerbitan',
        ]);
    }
    public function fBukuTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('format_buku')
                ->whereNotNull('deleted_at')
                ->orderBy('jenis_format', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-format-buku');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('format_buku', function ($data) {
                    return $data->jenis_format;
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
                        class="d-block btn btn-sm btn_ResFBuku btn-dark btn-icon""
                        data-toggle="tooltip" title="Restore Data"
                        data-id="' . $data->id . '" data-nama="' . $data->jenis_format . '">
                        <div><i class="fas fa-trash-restore-alt"></i> Restore</div></a>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'no',
                    'format_buku',
                    'tgl_dibuat',
                    'dibuat_oleh',
                    'dihapus_pada',
                    'dihapus_oleh',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.format_buku.telah_dihapus', [
            'title' => 'Platform Digital Telah Dihapus',
        ]);
    }
    public function createFbuku(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'nama_format_buku' => 'required|unique:format_buku,jenis_format',
                ], [
                    'required' => 'This field is requried'
                ]);
                $id = Uuid::uuid4()->toString();
                $input = [
                    'params' => 'Create Format Buku',
                    'id' => $id,
                    'nama_format_buku' => $request->nama_format_buku,
                    'created_by' => auth()->user()->id
                ];
                event(new MasterDataEvent($input));
                $insert = [
                    'params' => 'Insert History Create Format Buku',
                    'format_buku_id' => $id,
                    'type_history' => 'Create',
                    'nama_format_buku' => $request->nama_format_buku,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data format buku berhasil ditambahkan!',
                ]);
            }
        }
        return view('master_data.format_buku.create', [
            'title' => 'Tambah Format Buku'
        ]);
    }
    public function updateFbuku(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'edit_jenis_format' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);

                $history = DB::table('format_buku')->where('id', $request->edit_id)->first();
                $update = [
                    'params' => 'Update Format Buku',
                    'id' => $request->edit_id,
                    'jenis_format' => $request->edit_jenis_format,
                    'updated_by' => auth()->user()->id
                ];
                event(new MasterDataEvent($update));
                $insert = [
                    'params' => 'Insert History Update Format Buku',
                    'format_buku_id' => $request->edit_id,
                    'type_history' => 'Update',
                    'jenis_format_history' => $history->jenis_format,
                    'jenis_format_new' => $request->edit_jenis_format,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data format buku berhasil diubah!',
                    'route' => route('fb.view')
                ]);
            }
        }
        $id = $request->id;
        $data = DB::table('format_buku')
            ->where('id', $id)
            ->first();
        return response()->json($data);
    }
    public function deleteFbuku(Request $request)
    {
        $id = $request->id;
        $deleted = DB::table('format_buku')->where('id', $id)->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        $insert = [
            'params' => 'Insert History Delete Format Buku',
            'format_buku_id' => $id,
            'type_history' => 'Delete',
            'deleted_at' => date('Y-m-d H:i:s'),
            'author_id' => auth()->user()->id,
            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
        ];
        event(new MasterDataEvent($insert));
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil hapus data format buku!'
        ]);
        if ($deleted) {
            echo '<script>

            window.location = "' . route('fb.view') . '";
            </script>';
        }
    }
    public function lihatHistoryFBuku(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('format_buku_history as fbh')
                ->join('format_buku as fb', 'fb.id', '=', 'fbh.format_buku_id')
                ->join('users as u', 'u.id', '=', 'fbh.author_id')
                ->where('fbh.format_buku_id', $id)
                ->select('fbh.*', 'u.nama')
                ->orderBy('fbh.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Create':
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Format Buku <b class="text-dark">' . $d->jenis_format_name  . '</b> ditambahkan.</span>
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
                                <span><span class="bullet"></span> Format Buku <b class="text-dark">' . $d->jenis_format_history . '</b> diubah menjadi <b class="text-dark">' . $d->jenis_format_new . '</b>.</span>
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
                                <span><span class="bullet"></span> Data format buku dihapus pada <b class="text-dark">' . Carbon::parse($d->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data format buku direstore pada <b class="text-dark">' . Carbon::parse($d->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
    public function restoreFBuku(Request $request)
    {
        $id = $request->id;
        $restored = DB::table('format_buku')
            ->where('id', $id)
            ->update(['deleted_at' => null, 'deleted_by' => null]);
        $insert = [
            'params' => 'Insert History Restored Format Buku',
            'format_buku_id' => $id,
            'type_history' => 'Restore',
            'restored_at' => now(),
            'author_id' => auth()->user()->id,
            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
        ];
        event(new MasterDataEvent($insert));
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengembalikan format buku!'
        ]);
    }
}
