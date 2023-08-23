<?php

namespace App\Http\Controllers\Produksi;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Events\ProduksiEvent;
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
                ->join('proses_produksi_cetak as ppc', 'ppc.id', '=', 'ppr.produksi_id')
                ->join('order_cetak as oc', 'oc.id', '=', 'ppc.order_cetak_id')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
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
                ->addColumn('kirim_gudang', function ($data) {
                    if (Gate::allows('do_approval', 'kirim-data-rekondisi')) {
                        switch ($data->status) {
                            case 'belum selesai':
                                $statusColor = 'dark';
                                break;
                            case 'sedang dalam proses':
                                $statusColor = 'warning';
                                break;
                            case 'selesai':
                                $statusColor = 'success';
                                break;
                        }
                        $res = '<button class="btn btn-sm btn-light btn-icon position-relative tooltip-class" data-id="' . $data->id . '" data-produksi_id="' . $data->produksi_id . '"
                            data-judul="'.$data->judul_final.'" data-status="'.$data->status.'"
                            data-statuscolor="' . $statusColor . '" data-toggle="modal" data-target="#modalPengirimanRekondisi" data-backdrop="static" title="'.$data->status.'">
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
                    'kirim_gudang',
                    'action'
                ])
                ->make(true);
        }

        return view('produksi.rekondisi.index', [
            'title' => 'Proses Produksi Cetak'
        ]);
    }
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('proses_produksi_track')
                ->where('proses_tahap', 'Kirim Gudang')
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
                        ->where('ppc.id', $track->produksi_id)
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
                  <select class="form-control select-rekondisi" name="produksi_id" id="naskahRekondisi">
                  <option label="Pilih naskah"></option> ';
                foreach ($list_select as $d) {
                    $html .= '<option value="' . $d->id . '">' . $d->kode . ' &mdash; ' . $d->judul_final . '</option>';
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
    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $produksi_id = $request->produksi_id;
                $jml_rekondisi = $request->jml_rekondisi;
                $catatan = $request->catatan;
                $data = DB::table('proses_produksi_rekondisi')->orderBy('created_at', 'DESC')->first();
                if (is_null($data)) {
                    $kode = 'RE' . date('ymd') . '-0001';
                } else {
                    $sortNumber = (int)substr($data->kode, -4);
                    $kode = 'RE' . date('ymd') . '-' . sprintf("%04d", $sortNumber + 1);
                }
                $arr = [
                    'params' => 'Create Data Rekondisi',
                    'id' => Uuid::uuid4()->toString(),
                    'kode' => $kode,
                    'produksi_id' => $produksi_id,
                    'jml_rekondisi' => $jml_rekondisi,
                    'catatan' => $catatan,
                    'created_by' => auth()->user()->id
                ];
                event(new ProduksiEvent($arr));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data rekondisi berhasil ditambahkan!'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = DB::table('proses_produksi_rekondisi')->where('id',$id)->first();
            if (is_null($data)) {
                return abort(404);
            }
            $status = $request->status;
            $kabag = Gate::allows('do_update', 'pic-data-produksi');
            $classTooltip = '';
            $content ='';
            $addContent = '';
            $footer ='';
            $disable ='';
        switch ($status) {
            case 'belum selesai':
                $badge = 'badge badge-light';
                if ($kabag) {
                    $classTooltip ="tooltip-class";
                    $disable = 'title="Hanya admin pengiriman" style="cursor:not-allowed" disabled';
                    $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>';
                } else {
                    $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                            <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                }
                break;
            case 'sedang dalam proses':
                $kirimGudang = DB::table('proses_produksi_rekondisi_kirim')->where('rekondisi_id',$id)->get();
                $totalDiterima = DB::table('proses_produksi_rekondisi_kirim')
                ->whereNotNull('tgl_diterima')
                ->where('rekondisi_id',$id)
                ->orderBy('created_at','asc')
                ->select(DB::raw('IFNULL(SUM(jml_kirim),0) as total_diterima'))
                ->get();
                $totalKekurangan = $data->jml_rekondisi - $totalDiterima[0]->total_diterima;
                $totalKekurangan = $totalDiterima[0]->total_diterima > $data->jml_rekondisi ? 0:$totalKekurangan;
                $addContent .='<div class="form-group">
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
                                <th scope="col" style="background: #eee;">Action</th>
                              </tr>
                            </thead>
                            <tbody>';
                            $totalKirim = NULL;
                            foreach ($kirimGudang as $i => $kg) {
                                $i++;
                                if ($kabag) {
                                    $btnAction = '<span class="badge badge-secondary tooltip-class" title="Hanya admin pengiriman">No Action</span>';
                                } else {
                                    if(!is_null($kg->tgl_diterima)) {
                                        $btnAction = '<button type="button" class="btn-block btn btn-sm btn-outline-warning btn-icon mr-1 mt-1 tooltip-class"
                                        id="btnEditRiwayatKirim" title="Data sudah diterima" style="cursor:not-allowed" disabled>
                                        <i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn-block btn btn-sm btn-outline-danger btn-icon mr-1 mt-1 tooltip-class" id="btnDeleteRiwayatKirim" title="Data sudah diterima" style="cursor:not-allowed" disabled>
                                        <i class="fas fa-trash"></i></button>
                                        <button type="button" class="tooltip-class btn-block btn btn-sm btn-outline-info btn-icon mr-1 mt-1" title="Catatan" data-id="'.$kg->id.'" data-toggle="modal" data-target="#modalCatatan">
                                        <i class="fas fa-comment-alt"></i></button>';
                                    } else {
                                        $btnAction = '<button type="button" class="tooltip-class btn-block btn btn-sm btn-outline-warning btn-icon mr-1 mt-1"
                                        id="btnEditRiwayatKirim" data-id="'.$kg->id.'" data-dibuat="'.Carbon::parse($kg->created_at)->translatedFormat('l, d M Y - H:i:s').'"
                                        title="Edit Data" data-toggle="modal" data-target="#modalEditRiwayatKirim">
                                        <i class="fas fa-edit"></i></button>
                                        <button type="button" class="tooltip-class btn-block btn btn-sm btn-outline-danger btn-icon mr-1 mt-1" id="btnDeleteRiwayatKirim" data-id="'.$kg->id.'" data-track_id="'.$kg->track_id.'" title="Hapus Data">
                                        <i class="fas fa-trash"></i></button>
                                        <button type="button" class="tooltip-class btn-block btn btn-sm btn-outline-info btn-icon mr-1 mt-1" title="Catatan" data-id="'.$kg->id.'" data-toggle="modal" data-target="#modalCatatan">
                                        <i class="fas fa-comment-alt"></i></button>';
                                    }
                                }
                                $kg = (object)collect($kg)->map(function($item,$key){
                                    switch ($key) {
                                        case 'users_id':
                                            return DB::table('users')->where('id',$item)->first()->nama;
                                            break;
                                        case 'diterima_oleh':
                                            return is_null($item) ? '-':DB::table('users')->where('id',$item)->first()->nama;
                                            break;
                                        case 'tgl_diterima':
                                            return is_null($item) ? '<small class="badge badge-danger">menunggu</small>':Carbon::parse($item)->format('d-m-Y H:i:s');
                                            break;
                                        case 'created_at':
                                            return Carbon::parse($item)->format('d-m-Y H:i:s');
                                            break;
                                        default:
                                            return is_null($item) ? '-':$item;
                                            break;
                                    }
                                })->all();
                                $addContent .='<tr id="index_'.$kg->id.'">
                                  <td id="row_num'. $i . '">'.$i.'<input type="hidden" name="task_number[]" value=' . $i . '></td>
                                  <td>'.$kg->created_at.'</td>
                                  <td>'.$kg->users_id.'</td>
                                  <td>'.$kg->tgl_diterima.'</td>
                                  <td>'.$kg->diterima_oleh.'</td>
                                  <td id="indexJmlKirim'.$kg->id.'">'.$kg->jml_dikirim.' eks</td>
                                  <td>'.$btnAction.'</td>
                                </tr>';
                                $totalKirim +=$kg->jml_dikirim;
                            }
                            $addContent .='</tbody>
                          </table>
                            </div>
                            </div>
                            <div class="alert d-flex justify-content-between" style="background: #141517;
                            background: -webkit-linear-gradient(to right, #6777ef, #141517);
                            background: linear-gradient(to right, #6777ef, #141517);
                            " role="alert">
                                <div class="col-auto">
                                    <span class="bullet"></span><span>Total Oplah</span><br>
                                    <span class="bullet"></span><span>Total Kirim</span><br>
                                    <span class="bullet"></span><span>Total Diterima  <a href="javascript:void(0)" class="text-warning" tabindex="0" role="button"
                                    data-toggle="popover" data-trigger="focus" title="Informasi"
                                    data-content="Total diterima adalah total yang diterima dan diotorisasi oleh departemen Penjualan & Stok.">
                                    <abbr title="">
                                    <i class="fas fa-info-circle me-3"></i>
                                    </abbr>
                                    </a></span><br>
                                    <span class="bullet"></span><span>Total Kekurangan</span><br>
                                </div>
                                <div class="col-auto">
                                <span class="text-center">' . $data->jumlah_cetak . ' eks</span><br>
                                    <span class="text-center" id="totDikirim">'.$totalKirim.' eks</span><br>
                                    <span class="text-center">'.$totalDiterima[0]->total_diterima.' eks</span><br>
                                    <span class="text-center" id="totKekurangan">' . $totalKekurangan. ' eks</span>
                                </div>
                            </div>';
                            if ($kabag) {
                                $classTooltip ="tooltip-class";
                                $disable = 'title="Hanya admin pengiriman" style="cursor:not-allowed" disabled';
                                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>';
                            } else {
                                $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>
                        <button type="submit" class="btn btn-primary" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Konfirmasi</button>';
                            }

                $badge = 'badge badge-warning';
                break;

            // case 'selesai':
            //     foreach ($masterStatus as $ms) {
            //         if ($status == $ms) {
            //             $sel = ' selected="selected" ';
            //         }
            //     }
            //     $kirimGudang = DB::table('proses_produksi_track_riwayat')->where('track_id',$trackData->id)->where('track',$trackData->proses_tahap)
            //     ->orderBy('created_at','asc')->get();
            //     $totalDiterima = DB::table('proses_produksi_track_riwayat')
            //     ->whereNotNull('tgl_diterima')
            //     ->where('track_id',$trackData->id)
            //     ->where('track',$trackData->proses_tahap)
            //     ->orderBy('created_at','asc')
            //     ->select(DB::raw('IFNULL(SUM(jml_dikirim),0) as total_diterima'))
            //     ->get();
            //     $totalKekurangan = $data->jumlah_cetak - $totalDiterima[0]->total_diterima;
            //     $totalKekurangan = $totalDiterima[0]->total_diterima > $data->jumlah_cetak ? 0:$totalKekurangan;
            //     $addContent .='<div class="form-group">
            //             <label for="historyKirim">Riwayat Kirim</label>
            //                 <div class="scroll-riwayat">
            //                 <table class="table table-striped" style="width:100%" id="tableRiwayatKirim">
            //                 <thead style="position: sticky;top:0">
            //                   <tr>
            //                     <th scope="col" style="background: #eee;">No</th>
            //                     <th scope="col" style="background: #eee;">Tanggal Kirim</th>
            //                     <th scope="col" style="background: #eee;">Otorisasi Oleh</th>
            //                     <th scope="col" style="background: #eee;">Tanggal Diterima</th>
            //                     <th scope="col" style="background: #eee;">Penerima</th>
            //                     <th scope="col" style="background: #eee;">Jumlah Kirim</th>
            //                     <th scope="col" style="background: #eee;">Action</th>
            //                   </tr>
            //                 </thead>
            //                 <tbody>';
            //                 $totalKirim = NULL;
            //                 foreach ($kirimGudang as $i => $kg) {
            //                     $i++;
            //                     $kg = (object)collect($kg)->map(function($item,$key){
            //                         switch ($key) {
            //                             case 'users_id':
            //                                 return DB::table('users')->where('id',$item)->first()->nama;
            //                                 break;
            //                             case 'diterima_oleh':
            //                                 return is_null($item) ? '-':DB::table('users')->where('id',$item)->first()->nama;
            //                                 break;
            //                             case 'tgl_diterima':
            //                                 return is_null($item) ? '<small class="badge badge-danger">menunggu</small>':Carbon::parse($item)->format('d-m-Y H:i:s');
            //                                 break;
            //                             case 'created_at':
            //                                 return Carbon::parse($item)->format('d-m-Y H:i:s');
            //                                 break;
            //                             default:
            //                                 return is_null($item) ? '-':$item;
            //                                 break;
            //                         }
            //                     })->all();
            //                     $addContent .='<tr id="index_'.$kg->id.'">
            //                       <td id="row_num'. $i . '">'.$i.'<input type="hidden" name="task_number[]" value=' . $i . '></td>
            //                       <td>'.$kg->created_at.'</td>
            //                       <td>'.$kg->users_id.'</td>
            //                       <td>'.$kg->tgl_diterima.'</td>
            //                       <td>'.$kg->diterima_oleh.'</td>
            //                       <td>'.$kg->jml_dikirim.' eks</td>
            //                       <td><button type="button" class="btn-block btn btn-sm btn-outline-warning btn-icon mr-1 mt-1 tooltip-class"
            //                       title="Data sudah diterima" style="cursor:not-allowed" disabled>
            //                       <i class="fas fa-edit"></i></button>
            //                       <button type="button" class="btn-block btn btn-sm btn-outline-danger btn-icon mr-1 mt-1 tooltip-class" id="btnDeleteRiwayatKirim" title="Data sudah diterima" style="cursor:not-allowed" disabled>
            //                       <i class="fas fa-trash"></i></button>
            //                       <button type="button" class="tooltip-class btn-block btn btn-sm btn-outline-info btn-icon mr-1 mt-1" data-id="'.$kg->id.'" title="Catatan" data-toggle="modal" data-target="#modalCatatan">
            //                       <i class="fas fa-comment-alt"></i></button></td>
            //                     </tr>';
            //                     $totalKirim +=$kg->jml_dikirim;
            //                 }
            //                 $addContent .='</tbody>
            //               </table>
            //                 </div>
            //                 </div>
            //                 <div class="alert d-flex justify-content-between" style="background: #141517;
            //                 background: -webkit-linear-gradient(to right, #6777ef, #141517);
            //                 background: linear-gradient(to right, #6777ef, #141517);
            //                 " role="alert">
            //                     <div class="col-auto">
            //                         <span class="bullet"></span><span>Total Oplah</span><br>
            //                         <span class="bullet"></span><span>Total Kirim</span><br>
            //                         <span class="bullet"></span><span>Total Diterima  <a href="javascript:void(0)" class="text-warning" tabindex="0" role="button"
            //                         data-toggle="popover" data-trigger="focus" title="Informasi"
            //                         data-content="Jumlah oplah hanya dapat diinput oleh departemen penerbitan.">
            //                         <abbr title="">
            //                         <i class="fas fa-info-circle me-3"></i>
            //                         </abbr>
            //                         </a></span><br>
            //                         <span class="bullet"></span><span>Total Kekurangan</span><br>
            //                     </div>
            //                     <div class="col-auto">
            //                     <span class="text-center">' . $data->jumlah_cetak . ' eks</span><br>
            //                         <span class="text-center">'.$totalKirim.' eks</span><br>
            //                         <span class="text-center">'.$totalDiterima[0]->total_diterima.' eks</span><br>
            //                         <span class="text-center" id="totKekurangan">' . $totalKekurangan. ' eks</span>
            //                     </div>
            //                 </div>';
            //     $footer .= '<button type="button" class="btn btn-secondary" data-dismiss="modal" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 1px, rgba(0, 0, 0, 0.07) 0px 2px 2px, rgba(0, 0, 0, 0.07) 0px 4px 4px, rgba(0, 0, 0, 0.07) 0px 8px 8px, rgba(0, 0, 0, 0.07) 0px 16px 16px;">Close</button>';
            //     $badge = 'badge badge-success';
            //     if($kabag) {
            //         $classTooltip ="tooltip-class";
            //         $disable = 'title="Hanya admin pengiriman" style="cursor:not-allowed" disabled';
            //     } else {
            //         $disable = 'style="cursor:not-allowed" disabled';
            //     }
            //     break;
        }
        $content .= '
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="jmlCetak">Jumlah buku yang rekondisi <a href="javascript:void(0)" class="text-primary" tabindex="0" role="button"
                            data-toggle="popover" data-trigger="focus" title="Informasi"
                            data-content="Jumlah rekondisi hanya dapat diinput oleh departemen penerbitan.">
                            <abbr title="">
                            <i class="fas fa-info-circle me-3"></i>
                            </abbr>
                            </a></label>
                            <input name="jml_cetak" class="form-control tooltip-class" id="jmlCetak" value="' . $data->jml_rekondisi . '" title="Tidak dapat diubah" style="cursor:not-allowed" readonly>
                            </div>
                            <div class="form-group col-md-6">
                            <label for="jmlDikirim">Jumlah Dikirim (<span class="text-danger">*</span>)</label>
                            <input name="jml_dikirim" class="form-control '.$classTooltip.'" id="jmlDikirim" ' . $disable . '>
                            <span id="err_jml_dikirim"></span>
                            </div>
                        </div>';
        $content .= $addContent;
        return [
            'badge' => $badge,
            'status' => $status,
            'content' => $content,
            'footer' => $footer,
            'data' => $data,
        ];
        }
    }
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
