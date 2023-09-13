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
                        $statusColor = $data->status == 'selesai' ? 'success' : 'warning';
                        $res = '<button class="btn btn-sm btn-light btn-icon position-relative tooltip-class" data-id="' . $data->id . '" data-produksi_id="' . $data->produksi_id . '"
                            data-judul="' . $data->judul_final . '" data-status="' . $data->status . '"
                            data-statuscolor="' . $statusColor . '" data-toggle="modal" data-target="#modalPenerimaanRekondisi" data-backdrop="static" title="' . $data->status . '">
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
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $dataParent = DB::table('proses_produksi_rekondisi')->where('id', $id)->first();
                if (is_null($dataParent)) {
                    return abort(404);
                }
                $content = '';
                $jml_cetak = $request->jml_cetak;
                $data = DB::table('proses_produksi_rekondisi_kirim')->where('rekondisi_id', $id)->get();
                $totalDiterima = DB::table('proses_produksi_rekondisi_kirim')
                    ->whereNotNull('tgl_diterima')
                    ->where('rekondisi_id', $id)
                    ->select(DB::raw('IFNULL(SUM(jml_kirim),0) as total_diterima'))
                    ->get();
                $totalKekurangan = $jml_cetak - $totalDiterima[0]->total_diterima;
                $totalKekurangan = $totalDiterima[0]->total_diterima > $jml_cetak ? 0 : $totalKekurangan;
                $content .= '
                    <div class="form-group">
                        <label for="historyKirim">Riwayat Kirim</label>
                            <div class="scroll-riwayat">
                            <table class="table table-striped" style="width:100%" id="tableRiwayatKirim">
                            <thead style="position: sticky;top:0">
                              <tr>
                              <th scope="col" style="background: #eee;">No</th>
                              <th scope="col" style="background: #eee;">Tanggal Kirim</th>
                              <th scope="col" style="background: #eee;">Otorisasi Oleh</th>
                              <th scope="col" style="background: #eee;">Tanggal Diterima</th>
                              <th scope="col" style="background: #eee;">Penerima</th>
                              <th scope="col" style="background: #eee;">Jumlah Kirim</th>
                              <th scope="col" style="background: #eee;">Status</th>
                              <th scope="col" style="background: #eee;">Action</th>
                              </tr>
                            </thead>
                            <tbody>';
                $totalKirim = NULL;
                foreach ($data as $i => $kg) {
                    $i++;
                    if (!is_null($kg->tgl_diterima)) {
                        $btnAction = '<span class="badge badge-light">No action</span>';
                    } else {
                        $btnAction = '<button type="button" class="btn-block btn btn-sm btn-outline-warning btn-icon mr-1 mt-1"
                    id="btnTerimaBuku" data-id="' . $kg->id . '" data-jml_cetak="' . $jml_cetak . '" data-jml_diterima="' . $kg->jml_kirim . '">
                                    Terima</button>';
                    }
                    $kg = (object)collect($kg)->map(function ($item, $key) {
                        switch ($key) {
                            case 'otorisasi_oleh':
                                return DB::table('users')->where('id', $item)->first()->nama;
                                break;
                            case 'diterima_oleh':
                                return is_null($item) ? '-' : DB::table('users')->where('id', $item)->first()->nama;
                                break;
                            case 'tgl_diterima':
                                return is_null($item) ? '-' : Carbon::parse($item)->format('d-m-Y H:i:s');
                                break;
                            case 'tgl_kirim':
                                return Carbon::parse($item)->format('d-m-Y H:i:s');
                                break;
                            case 'status':
                                $color = $item == 'dalam pengiriman' ? 'warning' : 'success';
                                $item = '<span class="badge bg-' . $color . '">' . $item . '</span>';
                                return $item;
                                break;
                            default:
                                return is_null($item) ? '-' : $item;
                                break;
                        }
                    })->all();
                    $content .= '<tr id="index_' . $kg->id . '">
                                  <td id="row_num' . $i . '">' . $i . '<input type="hidden" name="task_number[]" value=' . $i . '></td>
                                  <td>' . $kg->tgl_kirim . '</td>
                                  <td>' . $kg->otorisasi_oleh . '</td>
                                  <td id="indexTglTerima' . $kg->id . '">' . $kg->tgl_diterima . '</td>
                                  <td id="indexDiterimaOleh' . $kg->id . '">' . $kg->diterima_oleh . '</td>
                                  <td id="indexJmlKirim' . $kg->id . '">' . $kg->jml_kirim . ' eks</td>
                                  <td id="indexStatus' . $kg->id . '">' . $kg->status . '</td>
                                  <td id="indexAction' . $kg->id . '">' . $btnAction . '</td>
                                </tr>';
                    $totalKirim += $kg->jml_kirim;
                }
                $content .= '</tbody>
                </table>
                </div>
                </div>
                <div class="alert d-flex justify-content-between" style="background: #141517;
                background: -webkit-linear-gradient(to right, #6777ef, #141517);
                background: linear-gradient(to right, #6777ef, #141517);
                " role="alert">
                <div class="col-auto">
                    <span class="bullet"></span><span>Total Cetak</span><br>
                    <span class="bullet"></span><span>Total Kirim</span><br>
                    <span class="bullet"></span><span>Total Diterima  <a href="javascript:void(0)" class="text-warning" tabindex="0" role="button"
                    data-toggle="popover" data-trigger="focus" title="Informasi"
                    data-content="Jumlah cetak rekondisi hanya dapat diinput oleh admin pengiriman.">
                    <abbr title="">
                    <i class="fas fa-info-circle me-3"></i>
                    </abbr>
                    </a></span><br>
                    <span class="bullet"></span><span>Total Kekurangan</span><br>
                </div>
                <div class="col-auto">
                    <span class="text-center">' . $dataParent->jml_rekondisi . ' eks</span><br>
                    <span class="text-center" id="totDikirim">' . $totalKirim . ' eks</span><br>
                    <span class="text-center" id="totDiterima">' . $totalDiterima[0]->total_diterima . ' eks</span><br>
                    <span class="text-center" id="totKekurangan">' . $totalKekurangan . ' eks</span>
                </div>
                </div>';
                return [
                    'content' => $content
                ];
            } catch (\Exception $e) {
                return abort(500, $e->getMessage());
            }
        }
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
