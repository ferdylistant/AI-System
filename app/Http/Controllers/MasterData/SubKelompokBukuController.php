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

class SubKelompokBukuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('penerbitan_m_s_kelompok_buku as skb')
                ->join('penerbitan_m_kelompok_buku as kb','kb.id','=','skb.kelompok_id')
                ->whereNull('skb.deleted_at')
                ->select('skb.*','kb.nama as nama_kb')
                ->orderBy('skb.kode_sub', 'asc')
                ->get();
            $update = Gate::allows('do_update', 'ubah-sub-kelompok-buku');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function ($no) use (&$start) {
                    return $start++;
                })
                ->addColumn('kode_sub', function ($data) {
                    return $data->kode_sub;
                })
                ->addColumn('nama', function ($data) {
                    return $data->nama;
                })
                ->addColumn('kelompok_id', function ($data) {
                    return $data->nama_kb;
                })
                ->addColumn('created_at', function ($data) {
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
                    $historyData = DB::table('penerbitan_m_s_kelompok_buku_history')->where('sub_kelompok_id', $data->id)->get();
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
                                    data-toggle="modal" data-target="#md_EditSubKelompokBuku" data-backdrop="static"
                                    data-id="' . $data->id . '" data-nama="' . $data->nama . '"
                                    data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    if (Gate::allows('do_delete', 'hapus-sub-kelompok-buku')) {
                        $btn .= '<a href="#" class="d-block btn btn-sm btn_DelSubKelompokBuku btn-danger btn-icon mr-1 mt-1"
                        data-toggle="tooltip" title="Hapus Data"
                        data-id="' . $data->id . '" data-nama="' . $data->nama . '">
                        <div><i class="fas fa-trash-alt"></i></div></a>';
                    }
                    if (Auth::user()->cannot('do_update','ubah-sub-kelompok-buku') && Auth::user()->cannot('do_delete','hapus-sub-kelompok-buku')) {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'kode_sub',
                    'nama',
                    'kelompok_id',
                    'created_at',
                    'dibuat_oleh',
                    'diubah_terakhir',
                    'diubah_oleh',
                    'history',
                    'action'
                ])
                ->make(true);
        }

        return view('master_data.sub_kelompok_buku.index', [
            'title' => 'Sub-Kelompok Buku Penerbitan',
        ]);
    }
    public function skBukuTelahDihapus(Request $request)
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
    public function createSKbuku(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $last = DB::table('penerbitan_m_s_kelompok_buku')->orderBy('created_at', 'desc')->first();
                    if (is_null($last)) {
                        $kode = '0001';
                    } else {
                        $kode = sprintf('%04u',$last->kode_sub+1);
                    }
                    $id = Uuid::uuid4()->toString();
                    $input = [
                        'params' => 'Create Sub Kelompok Buku',
                        'id' => $id,
                        'kelompok_id' => $request->nama_kelompok_buku,
                        'kode_sub' => $kode,
                        'nama' => $request->nama_sub_kelompok_buku,
                        'created_by' => auth()->user()->id,
                        //History
                        'type_history' => 'Create',
                    ];
                    event(new MasterDataEvent($input));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data sub kelompok buku berhasil ditambahkan!',
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
    public function updateSKbuku(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $history = DB::table('penerbitan_m_s_kelompok_buku')->where('id', $request->edit_id)->first();
                $update = [
                    'params' => 'Update Sub Kelompok Buku',
                    'id' => $request->edit_id,
                    'nama' => $request->edit_nama,
                    'kelompok_id' => $request->edit_kelompok_id,
                    'updated_by' => auth()->user()->id
                ];
                $updated = event(new MasterDataEvent($update));
                if ($updated) {
                    $insert = [
                        'params' => 'Insert History Update Sub Kelompok Buku',
                        'type_history' => 'Update',
                        'sub_kelompok_id' => $request->edit_id,
                        'sub_his' => $request->edit_nama == $history->nama ? NULL : $history->nama,
                        'sub_new' => $request->edit_nama == $history->nama ? NULL : $request->edit_nama,
                        'kb_his' => $request->edit_kelompok_id == $history->kelompok_id ? NULL : $history->kelompok_id,
                        'kb_new' => $request->edit_kelompok_id == $history->kelompok_id ? NULL : $request->edit_kelompok_id,
                        'author_id' => auth()->user()->id,
                    ];
                    event(new MasterDataEvent($insert));
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kelompok buku berhasil diubah!',
                    'route' => route('kb.view')
                ]);
            }
        }
        $id = $request->id;
        $data = DB::table('penerbitan_m_s_kelompok_buku as s')
            ->join('penerbitan_m_kelompok_buku as k','k.id','=','s.kelompok_id')
            ->where('s.id', $id)
            ->select('s.*','k.nama as kelompok_nama')
            ->first();
        return response()->json($data);
    }
    public function deleteSKbuku(Request $request)
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
    public function restoreSKBuku(Request $request)
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
    public function lihatHistorySKBuku(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('penerbitan_m_s_kelompok_buku_history as kbh')
                ->join('penerbitan_m_s_kelompok_buku as kb', 'kb.id', '=', 'kbh.sub_kelompok_id')
                ->join('users as u', 'u.id', '=', 'kbh.author_id')
                ->where('kbh.sub_kelompok_id', $id)
                ->select('kbh.*', 'u.nama')
                ->orderBy('kbh.id', 'desc')
                ->paginate(2);

            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Create':
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Sub-Kelompok Buku <b class="text-dark">' . $d->sub_create  . '</b> ditambahkan .</span>
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
    public function ajaxSelect(Request $request)
    {
        $data = DB::table('penerbitan_m_kelompok_buku')
            ->whereNull('deleted_at')
            ->where('nama', 'like', '%' . $request->input('term') . '%')
            ->get();
        return response()->json($data);
    }
}
