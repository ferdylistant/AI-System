<?php

namespace App\Http\Controllers\PenjualanStok;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class PenerimaanRekondisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('proses_produksi_rekondisi as ppr')
                ->join('proses_produksi_cetak as ppc', 'ppc.id', '=', 'ppr.produksi_id')
                ->join('order_cetak as oc', 'oc.id', '=', 'ppc.order_cetak_id')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->whereNotIn('ppr.status', ['belum selesai'])
                ->orderBy('pn.kode', 'asc')
                ->select(
                    'ppr.*',
                    'dp.judul_final',
                    'dp.naskah_id',
                    'pn.kode as kode_naskah',
                )
                ->get();
            if ($request->has('modal')) {
                $response = $this->modalTrackProduksi($request);
                return $response;
            }
            return DataTables::of($data)
                ->addColumn('kode_rekondisi', function ($data) {
                    return $data->kode;
                })
                ->addColumn('kode_naskah', function ($data) {
                    return $data->kode_naskah;
                })
                ->addColumn('judul_final', function ($data) {
                    return $data->judul_final;
                })
                ->addColumn('penulis', function ($data) {
                    $result = '';
                    $res = DB::table('penerbitan_naskah_penulis as pnp')
                        ->join('penerbitan_penulis as pp', function ($q) {
                            $q->on('pnp.penulis_id', '=', 'pp.id')
                                ->whereNull('pp.deleted_at');
                        })
                        ->where('pnp.naskah_id', '=', $data->naskah_id)
                        ->select('pp.nama')
                        // ->pluck('pp.nama');
                        ->get();
                    foreach ($res as $q) {
                        $result .= '<span class="d-block">-&nbsp;' . $q->nama . '</span>';
                    }
                    return $result;
                })
                ->addColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->translatedFormat('l, d-m-Y H:i');
                })
                ->addColumn('cek_pengiriman', function ($data) {
                    if (Gate::allows('do_approval', 'otorisasi-penerimaan-rekondisi')) {
                        $statusColor = $data->status == 'selesai' ? 'success':'warning';
                        $res = '<button class="btn btn-sm btn-light btn-icon position-relative tooltip-class" data-id="' . $data->id . '" data-produksi_id="' . $data->produksi_id . '"
                            data-judul="' . $data->judul_final . '" data-status="' . $data->status . '"
                            data-statuscolor="' . $statusColor . '" data-toggle="modal" data-target="#modalPengirimanRekondisi" data-backdrop="static" title="' . $data->status . '">
                            <i class="fas fa-truck-loading"></i> Pengiriman
                            <span class="position-absolute translate-middle p-1 bg-' . $statusColor . ' border border-light rounded-circle" style="top:0;right:0">
                            </span>
                            </button>';
                    } else {
                        $res = '<span class="text-danger">Anda tidak memiliki hak akses untuk melihat data ini</span>';
                    }
                    return $res;
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('produksi/proses/cetak/detail?no=') . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1 tooltip-class" data-toggle="tooltip" data-placement="top" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                    if (Gate::allows('do_update', 'ubah-data-rekondisi')) {
                        $btn .= '<a href="' . url('produksi/proses/cetak/edit?no=') . '"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1 tooltip-class" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'kode_rekondisi',
                    'kode_naskah',
                    'judul_final',
                    'penulis',
                    'created_at',
                    'cek_pengiriman',
                    'action'
                ])
                ->make(true);
        }

        return view('penjualan_stok.gudang.penerimaan_rekondisi.index', [
            'title' => 'Proses Penerimaan Rekondisi'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
