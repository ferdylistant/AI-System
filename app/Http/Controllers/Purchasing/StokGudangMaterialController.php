<?php

namespace App\Http\Controllers\Purchasing;

use App\Events\PurchasingEvent;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Ramsey\Uuid\Uuid;
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
                case 'select-barang':
                    return $this->ajaxSelectBarang($request);
                    break;
                case 'get-barang':
                    return $this->ajaxGetBarang($request);
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

    public function ajaxSelectBarang($request)
    {
        $data = DB::table('purchase_gudang_stok')
            ->where('nama_stok', 'like', '%' . $request->input('term') . '%')
            ->get();
        return response()->json($data);
    }

    public function ajaxGetBarang($request)
    {
        $data = DB::table('purchase_gudang_stok')->where('id', $request->id)->first();
        return $data;
    }

    public function createPermintaan(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $last = DB::table('purchase_permintaan_gudang')->orderBy('created_at', 'desc')->first();
                if (is_null($last)) {
                    $kode = '0001';
                } else {
                    $kode = sprintf('%04d', (int)$last->kode_permintaan + 1);
                }
                $data = [
                    'params' => 'Create Permintaan Pembelian',
                    'id' => Uuid::uuid4()->toString(),
                    'dataKode' => $request->dataKode,
                    'dataPermintaan' => json_decode($request->dataPermintaan[0]),
                    'dataNama' => $request->dataNama,
                    'dataQty' => $request->dataQty,
                    'kodePermintaan' => $kode,
                    'created_by' => auth()->user()->id
                ];

                try {
                    event(new PurchasingEvent($data));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Permintaan Pembelian Berhasil Dibuat!'
                    ]);
                } catch (\Throwable $err) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $err->getMessage()
                    ]);
                }
            }
        }

        $lastKode = DB::table('purchase_gudang_stok')->orderBy('created_at', 'desc')->first();
        $lastKode_ = DB::table('purchase_permintaan_gudang_detail')->orderBy('id', 'desc')->first();
        if (is_null($lastKode) && is_null($lastKode_)) {
            $kode = '00001';
        } else {
            if (is_null($lastKode_)) {
                $kode = substr($lastKode->kode, -5);
            } else {
                $kode1 = substr($lastKode->kode, -5);
                $kode2 = substr($lastKode_->kode_barang, -5);
                if ($kode1 > $kode2) {
                    $kode = $kode1;
                } else {
                    $kode = $kode2;
                }
            }
        }
        return view('purchasing.stok_gudang_material.create-permintaan', [
            'title' => 'Tambah Permintaan',
            'kode' => $kode
        ]);
    }

    public function test(Request $request)
    {
        $last = DB::table('purchase_permintaan_gudang')->orderBy('created_at', 'desc')->first();
        if (is_null($last)) {
            $kode = '0001';
        } else {
            $kode = sprintf('%04d', (int)$last->kode_permintaan + 1);
        }
        $data = [
            'params' => 'Create Permintaan Pembelian',
            'id' => Uuid::uuid4()->toString(),
            'dataKode' => $request->input('dataKode'),
            'dataPermintaan' => json_decode($request->dataPermintaan[0]),
            'dataNama' => $request->input('dataNama'),
            'dataQty' => $request->input('dataQty'),
            'kodePermintaan' => $kode,
            'created_by' => auth()->user()->id
        ];

        try {
            event(new PurchasingEvent($data));
            return redirect()->route('sgm.create');
        } catch (\Throwable $err) {
            DB::rollBack();
            return $err->getMessage();
        }
    }
}
