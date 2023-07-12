<?php

namespace App\Http\Controllers\MasterData;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Events\MasterDataEvent;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB,Auth,Gate};

class JenisMesinController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('proses_produksi_master')
                ->where('type','M')
                ->whereNull('deleted_at')
                ->orderBy('nama', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-jenis-mesin');
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                })
                ->addColumn('created_at', function ($data) {
                    $date = date('d F Y, H:i', strtotime($data->created_at));
                    return $date;
                })
                ->addColumn('created_by', function ($data) {
                    $dataUser = User::where('id', $data->created_by)->first();
                    return $dataUser->nama;
                })
                ->addColumn('updated_at', function ($data) {
                    if (is_null($data->updated_at)) {
                        $res = '-';
                    } else {
                        $res = date('d F Y, H:i', strtotime($data->updated_at));
                    }
                    return $res;
                })
                ->addColumn('updated_by', function ($data) {
                    if (is_null($data->updated_by)) {
                        $res = '-';
                    } else {
                        $res = User::where('id', $data->updated_by)->first()->nama;
                    }
                    return $res;
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('proses_produksi_master_history')->where('master_id', $data->id)->get();
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
                                    data-toggle="modal" data-target="#md_EditJenisMesin" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-data-imprint')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelJenisMesin btn-danger btn-icon mr-1 mt-1"
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
                    'nama',
                    'created_at',
                    'created_by',
                    'updated_at',
                    'updated_by',
                    'history',
                    'action'
                ])
                ->make(true);
        }
        return view('master_data.jenis_mesin.index',[
            'title' => 'Jenis Mesin Produksi'
        ]);
    }
    public function createJmesin(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $id = Uuid::uuid4()->toString();
                    $input = [
                        'params' => 'Create Jenis Mesin',
                        'id' => $id,
                        'type' => 'M',
                        'nama' => $request->nama,
                        'created_by' => auth()->user()->id
                    ];
                    event(new MasterDataEvent($input));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data jenis mesin berhasil ditambahkan!',
                    ]);
                } catch(\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
            }
        }
    }
    public function updateJmesin(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('proses_produksi_master')->where('id', $request->edit_id)->first();
                    $update = [
                        'params' => 'Update Jenis Mesin',
                        'id' => $request->edit_id,
                        'nama' => $request->edit_nama,
                        'updated_by' => auth()->user()->id,
                        // history
                        'type_history' => 'Update',
                        'nama_his' => $history->nama,
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new MasterDataEvent($update));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data jenis mesin berhasil diubah!',
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
            } else {
                $id = $request->id;
                $data = DB::table('proses_produksi_master')
                    ->where('id', $id)
                    ->first();
                return response()->json($data);
            }
        }
    }
    public function deleteFbuku(Request $request)
    {
        try {
            $id = $request->id;
            //Check Relasi
            $relation = DB::table('deskripsi_produk')->where('format_buku',$id)->first();
            if (!is_null($relation)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Format buku tidak bisa dihapus karena telah terpakai di naskah yang sedang diproses!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $insert = [
                'params' => 'Insert History Delete Format Buku',
                'format_buku_id' => $id,
                'type_history' => 'Delete',
                'deleted_at' => $tgl,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            event(new MasterDataEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data format buku!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryJmesin(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('proses_produksi_master_history as ppmh')
                ->join('proses_produksi_master as ppm', 'ppm.id', '=', 'ppmh.master_id')
                ->join('users as u', 'u.id', '=', 'ppmh.author_id')
                ->where('ppmh.master_id', $id)
                ->select('ppmh.*', 'u.nama')
                ->orderBy('ppmh.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Jenis mesin <b class="text-dark">' . $d->nama_his . '</b> diubah menjadi <b class="text-dark">' . $d->nama_new . '</b>.</span>
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
                                <span><span class="bullet"></span> Data jenis mesin dihapus pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
                                    <span><span class="bullet"></span> Data jenis mesin direstore pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
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
