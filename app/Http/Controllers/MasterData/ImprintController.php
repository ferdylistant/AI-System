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
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-toggle="modal" data-target="#md_ImprintHistory"><i class="fas fa-history"></i>&nbsp;History</button>';
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
                    if (Gate::allows('do_delete', 'hapus-data-imprint')) {
                        $btn = '<a href="' . url('master/imprint/hapus?im=' . $data->id) . '"
                                    class="d-block btn btn-sm btn-danger btn-icon mr-1" id="hapus-imprint" data-toggle="tooltip" title="Hapus Data">
                                    <div><i class="fas fa-trash"></i></div></a>';
                    }
                    if ($update) {
                        $btn .= '<a href="' . url('master/imprint/ubah-imprint?im=' . $data->id) . '"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
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

    public function createImprint(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'nama_imprint' => 'required|unique:nama_imprint',
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
                return response()->json(['data' => $input]);
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
                    'up_nama' => 'required',
                ], [
                    'required' => 'This field is requried'
                ]);
                $history = DB::table('imprint')->where('id', $request->id)->first();
                $update = [
                    'params' => 'Update Imprint',
                    'id' => $request->id,
                    'nama' => $request->up_nama,
                    'updated_by' => auth()->user()->id
                ];
                event(new MasterDataEvent($update));
                $insert = [
                    'params' => 'Insert History Imprint',
                    'imprint_id' => $request->id,
                    'imprint_history' => $history->nama,
                    'imprint_new' => $request->up_nama,
                    'author_id' => auth()->user()->id,
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new MasterDataEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data imprint berhasil diubah!',
                    'route' => route('imprint.view')
                ]);
            }
        }
        $id = $request->get('im');
        $data = DB::table('imprint')
            ->where('id', $id)
            ->first();
        return view('master_data.imprint.update', [
            'title' => 'Update Imprint',
            'data' => $data
        ]);
    }
    public function deleteImprint(Request $request)
    {
        $id = $request->get('im');
        $deleted = DB::table('imprint')->where('id', $id)->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        if ($deleted) {
            echo '<script>

            window.location = "' . route('imprint.view') . '";
            </script>';
        }
    }
    public function lihatHistory(Request $request)
    {
        $id = $request->input('id');
        $data = DB::table('imprint_history as ih')->join('users as u', 'ih.author_id', '=', 'u.id')
            ->where('ih.imprint_id', $id)
            ->select('ih.*', 'u.nama')
            ->get();
        foreach ($data as $d) {
            $result[] = [
                'imprint_history' => $d->imprint_history,
                'imprint_new' => $d->imprint_new,
                'author_id' => $d->author_id,
                'nama' => $d->nama,
                'modified_at' => Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans(),
                'format_tanggal' => Carbon::parse($d->modified_at)->translatedFormat('d M Y, H:i')
            ];
        }
        return response()->json($result);
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
                        $btn = '<a href="#" class="d-block btn btn-sm btn-warning btn_EditCabang btn-icon mr-1 mt-1"
                                    data-toggle="modal" data-target="#md_EditPlatformEbook" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-data-imprint')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelPlatform btn-danger btn-icon mr-1 mt-1"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
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
            if ($request->input('request_') === 'table-platform') {
                $data = DB::table('platform_digital_ebook')
                    ->whereNotNull('deleted_at')
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
                            $btn = '<a href="' . url('master/platform-digital/restore?p=' . $data->id) . '"
                                    class="d-block btn btn-sm btn-dark btn-icon" id="restore-platform" data-toggle="tooltip" title="Restore Data">
                                    <div><i class="fas fa-trash-restore-alt"></i> Restore</div></a>';
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
        $id = $request->id;
        $deleted = DB::table('platform_digital_ebook')->where('id', $id)->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        $insert = [
            'params' => 'Insert History Delete Platform',
            'platform_id' => $id,
            'type_history' => 'Delete',
            'deleted_at' => date('Y-m-d H:i:s'),
            'author_id' => auth()->user()->id,
            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
        ];
        event(new MasterDataEvent($insert));
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil hapus data platform!'
        ]);
        if ($deleted) {
            echo '<script>

            window.location = "' . route('platform.view') . '";
            </script>';
        }
    }

    public function restorePlatform(Request $request)
    {
        $id = $request->get('p');
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
        if ($restored) {
            echo '<script>

            window.location = "' . route('platform.view') . '";
            </script>';
        }
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
                            <span><span class="bullet"></span> Platform e-book <b class="text-dark">' . $d->platform_name  . '</b> ditambahkan.</span>
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
                                <span><span class="bullet"></span> Platform e-book <b class="text-dark">' . $d->platform_history . '</b> diubah menjadi <b class="text-dark">' . $d->platform_new . '</b>.</span>
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
                                <span><span class="bullet"></span> Data platform e-book dihapus pada <b class="text-dark">' . Carbon::parse($d->deleted_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data platform e-book direstore pada <b class="text-dark">' . Carbon::parse($d->restored_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
