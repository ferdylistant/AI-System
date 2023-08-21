<?php

namespace App\Http\Controllers\Produksi;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class RekondisiController extends Controller
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
                ->join('proses_produksi_cetak as ppc','ppc.id','=','ppr.produksi_id')
                ->join('order_cetak as oc', 'oc.id', '=', 'ppc.order_cetak_id')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->orderBy('oc.kode_order', 'asc')
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
                ->addColumn('kirim_gudang', function ($data) {
                    if (Gate::allows('do_approval', 'kirim-data-rekondisi')) {
                        $statusColor = $data->status == 'sedang dalam proses' ? 'warning' : 'success';
                        $res = '<button class="btn btn-sm btn-light btn-icon position-relative" data-track_id="' . $data->id . '"
                            data-judulfinal="' . $data->judul_final . '" data-status="' . $data->status . '" data-naskah_id="' . $data->naskah_id . '"
                            data-produksi_id="' . $data->produksi_id . '" data-proses_tahap="' . $data->proses_tahap . '"
                            data-statuscolor="' . $statusColor . '" data-jml_cetak="' . $data->jumlah_cetak . '" data-toggle="modal" data-target="#modalPenerimaanBuku" data-backdrop="static">
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
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" data-placement="top" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                    if (Gate::allows('do_update','ubah-data-rekondisi')) {
                        $btn .= '<a href="' . url('produksi/proses/cetak/edit?no=') . '"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                    }
                    return $btn;
                })
                ->rawColumns([
                    'kode_rekondisi',
                    'kode_naskah',
                    'judul_final',
                    'penulis',
                    'buku_jadi',
                    'kirim_gudang',
                    'action'
                ])
                ->make(true);
        }

        return view('produksi.rekondisi.index', [
            'title' => 'Proses Produksi Cetak'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->ajax()) {
            $data = DB::table('proses_produksi_track')
            ->where('proses_tahap','Kirim Gudang')
            ->whereNotNull('tgl_selesai')->get();
            $html = '';
            $btn = '';
            if ($data->isEmpty()) {
                $html .= '<div class="col-12 offset-3 mt-5">
                <div class="row">
                    <div class="col-4 offset-1">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" width="100%">
                        <p class="pt-2 font-weight-bold">Naskah order cetak belum ada</p>
                    </div>
                </div>
            </div>';
            $btn .= '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>';
            } else {
                foreach ($data as $track) {
                    $list_select[] = $data = DB::table('proses_produksi_cetak as ppc')
                    ->join('order_cetak as oc', 'oc.id', '=', 'ppc.order_cetak_id')
                    ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
                    ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                    ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                    ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                    ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->orderBy('oc.kode_order', 'asc')
                    ->where('ppc.id',$track->produksi_id)
                    ->select(
                        'ppc.id',
                        'dp.judul_final',
                        'dp.naskah_id',
                        'pn.kode',
                    )
                    ->first();
                }
                $html .= '<div class="form-group">
                  <label for="naskahRekondisi" class="col-form-label">Naskah <span class="text-danger">*</span></label>
                  <select class="form-control select-rekondisi" name="naskah_rekondisi" id="naskahRekondisi">
                  <option label="Pilih naskah"></option> ';
                foreach($list_select as $d) {
                    $html .= '<option value="naskah='.$d->kode.'&produksi_id='.$d->id.'">'.$d->kode.' &mdash; '.$d->judul_final.'</option>';
                }
                $html .= '</select>
                <div id="err_naskah_rekondisi"></div>
                </div>
                <div class="form-row" style="display:none" id="formOther">
                    <div class="form-group col-lg-6">
                    <label for="jmlRekondisi" class="col-form-label">Jumlah Naskah Rekondisi <span class="text-danger">*</span></label>
                    <input type="text" name="jml_rekondisi" class="form-control" id="jmlRekondisi">
                    <div id="err_jml_rekondisi"></div>
                    </div>
                    <div class="form-group col-lg-6">
                    <label for="catatanRekondisi" class="col-form-label">Catatan (<span class="text-muted">Opsional</span>)</label>
                    <textarea class="swal-content__textarea" name="catatan" id="catatanRekondisi" cols="3"></textarea>
                    </div>
                </div>
                ';
            $btn .= '<button type="submit" class="d-block btn btn-sm btn-outline-primary btn-block btn-cetak-ulang" id="btnRedirectCetul">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>';
            }
            return [
                'content' => $html,
                'btn' => $btn
            ];
        }
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
