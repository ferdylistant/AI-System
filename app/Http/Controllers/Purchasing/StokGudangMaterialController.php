<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class StokGudangMaterialController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('purchase_gudang_stok')->orderBy('nama_stok', 'asc')->get();
            $start = 1;
            return DataTables::of($data)
                ->addColumn('no', function () use (&$start) {
                    return $start++;
                })
                ->addColumn('kode_stok', function ($data) {
                    return $data->kode;
                })
                ->addColumn('nama_stok', function ($data) {
                    return $data->nama_stok;
                })
                ->addColumn('satuan', function ($data) {
                    $satuan = DB::table('satuan')->where('id', $data->satuan_id)->first();
                    return $satuan->nama;
                })
                ->addColumn('jumlah_stok', function ($data) {
                    return $data->jumlah_stok;
                })
                ->addColumn('tanggal', function ($data) {
                    return Carbon::parse($data->tgl_stok)->translatedFormat('d M Y, H:i');
                })
                ->rawColumns([
                    'kode_stok',
                    'nama_stok',
                    'satuan',
                    'jumlah_stok',
                    'tanggal'
                ])
                ->make(true);
        }

        return view('purchasing.stok_gudang_material.index', [
            'title' => 'Stok Gudang Material',
        ]);
    }

    public function callAjax(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->cat) {
                case 'add':
                    return $this->showCreatePermintaan();
                    break;
                case 'select':
                    return $this->ajaxSelect($request);
                    break;
                default:
                    abort(500);
                    break;
            }
        }
    }

    public function ajaxSelect($request)
    {
        $data = DB::table($request->item_)
            ->whereNull('deleted_at')
            ->where('nama', 'like', '%' . $request->input('term') . '%')
            ->get();
        return response()->json($data);
    }

    public function createPermintaan()
    {
        return view('purchasing.stok_gudang_material.create-permintaan', [
            'title' => 'Tambah Permintaan',
        ]);
    }
}
