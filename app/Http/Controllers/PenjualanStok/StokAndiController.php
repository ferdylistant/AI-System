<?php

namespace App\Http\Controllers\PenjualanStok;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Events\PenjualanStokEvent;
use App\Events\convertNumberToRoman;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\{DB, Gate};
use Symfony\Component\Console\Input\Input;

class StokAndiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pj_st_andi as st')
                ->leftJoin('penerbitan_naskah as pn', 'pn.id', '=', 'st.naskah_id')
                ->leftJoin('deskripsi_produk as dp', 'pn.id', '=', 'dp.naskah_id')
                ->leftJoin('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->leftJoin('pracetak_setter as ps', 'df.id', '=', 'ps.deskripsi_final_id')
                ->leftJoin('deskripsi_turun_cetak as dtc', 'df.id', '=', 'dtc.pracetak_setter_id')
                ->leftJoin('penerbitan_m_kelompok_buku as kb', function ($q) {
                    $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                        ->whereNull('kb.deleted_at');
                })
                ->leftJoin('penerbitan_m_s_kelompok_buku as skb', function ($q) {
                    $q->on('pn.sub_kelompok_buku_id', '=', 'skb.id')
                        ->whereNull('skb.deleted_at');
                })
                ->orderBy('pn.kode', 'asc')
                ->select(
                    'st.*',
                    'ps.edisi_cetak',
                    'ps.pengajuan_harga',
                    'df.sub_judul_final',
                    'dp.judul_final',
                    'dp.naskah_id',
                    'dp.format_buku',
                    'dp.imprint',
                    'pn.kode',
                    'kb.nama as nama_kb',
                    'skb.nama as nama_skb',
                )
                ->get();
            if ($request->isMethod('GET')) {
                switch ($request->request_type) {
                    case 'table-index':
                        return self::datatableIndex($data);
                        break;
                    case 'show-track-timeline':
                        return self::trackTimeline($request);
                        break;
                    case 'show-modal-rack':
                        return self::showModalRack($request);
                        break;
                    case 'show-modal-rack-detail':
                        return self::showModalRackDetail($request);
                        break;
                    case 'show-modal-rack-edit':
                        return self::showModalRackEdit($request);
                        break;
                    case 'select-rack':
                        return self::selectRack($request);
                        break;
                    case 'select-operator-gudang':
                        return self::selectOperator($request);
                        break;
                    case 'modal-permohonan-rekondisi':
                        return self::showModalPermohonanRekondisi($request);
                        break;
                }
            } else {
                switch ($request->request_type) {
                    case 'add-data-rack':
                        return self::addDataRack($request);
                        break;
                    case 'edit-data-rack':
                        return self::editDataRack($request);
                        break;
                    case 'delete-data-rack':
                        return self::deleteDataRack($request);
                        break;
                }
            }
        }
        return view('penjualan_stok.gudang.stok_andi.index', [
            'title' => 'Stok Gudang Andi'
        ]);
    }
    protected function datatableIndex($data)
    {
        $update = Gate::allows('do_update', 'pic-data-produksi');
        return DataTables::of($data)
            ->addColumn('kode_sku', function ($data) {
                return $data->kode_sku;
            })
            ->addColumn('kode', function ($data) {
                return $data->kode;
            })
            ->addColumn('judul_final', function ($data) {
                return ucfirst($data->judul_final);
            })
            ->addColumn('sub_judul_final', function ($data) {
                return $data->sub_judul_final ?? '-';
            })
            ->addColumn('kelompok_buku', function ($data) {
                return $data->nama_kb . ' <i class="fas fa-chevron-right"></i> ' . $data->nama_skb;
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
            ->addColumn('imprint', function ($data) {
                $res = DB::table('imprint')->where('id', $data->imprint)->whereNull('deleted_at')->first()->nama ?? '-';
                return $res;
            })
            ->addColumn('total_stok', function ($data) {
                return $data->total_stok;
            })
            ->addColumn('rack', function ($data) {
                return '<button class="btn btn-light" data-toggle="modal" data-judul="' . $data->judul_final . '" data-total_stok="' . $data->total_stok . '" data-stok_id="' . $data->id . '" data-target="#modalRack" data-backdrop="static">
                <i class="fas fa-border-all"></i> Rak Buku
                </button>';
            })
            ->addColumn('action', function ($data) use ($update) {
                $btn = '<a href="' . url('produksi/proses/cetak/detail?no=' . $data->kode_sku . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                if ($update) {
                    $btn .= '<a href="' . url('produksi/proses/cetak/edit?no=' . $data->kode_sku . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                }
                return $btn;
            })
            ->rawColumns([
                'kode_sku',
                'kode',
                'judul_final',
                'sub_judul_final',
                'kelompok_buku',
                'penulis',
                'imprint',
                'total_stok',
                'rack',
                'action'
            ])
            ->make(true);
    }
    protected function trackTimeline($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('tracker')->where('section_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
            foreach ($data as $d) {
                $html .= '<div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">
                    <i class="' . $d->icon . '"></i>
                </div>
                <div class="activity-detail col">
                    <div class="mb-2">
                        <span class="text-job">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Jakarta')->diffForHumans() . '</span>
                        <span class="bullet"></span>
                        <span class="text-job">' . Carbon::parse($d->created_at)->translatedFormat('l d M Y, H:i') . '</span>
                    </div>
                    <p>' . $d->description . '</p>
                </div>
            </div>';
            }
            return $html;
        }
    }
    protected function showModalPermohonanRekondisi($request)
    {
        $stok_id = $request->stok_id;
        $total_stok = $request->total_stok;
        $dataRack = DB::table('pj_st_rack_data as rd')
                ->leftJoin('pj_st_rack_master as rm', function ($q) {
                    $q->on('rd.rack_id', '=', 'rm.id')
                        ->whereNull('rm.deleted_at');
                })
                ->leftJoin('pj_st_andi as st', 'rd.stok_id', '=', 'st.id')
                ->where('rd.stok_id', $stok_id)
                ->select(
                    'rd.*',
                    'rm.kode',
                    'rm.nama',
                    'st.total_stok',
                    DB::raw('IFNULL(SUM(rd.jml_stok),0) as total_diletakkan'),
                    DB::raw('IFNULL(count(rd.id),0) as count_proses'
                ))
                ->orderBy('rd.created_at', 'ASC');
        $modal ='';
        $modal .='<div id="modalPermohonanRekondisi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalPermohonanRekondisiTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPermohonanRekondisiTitle"><i class="fas fa-recycle"></i>&nbsp;Permohonan Rekondisi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body scrollbar-deep-purple" style="max-height: 500px; overflow-y: auto;">
                    <div class="section">
                        <div class="section-body">';
                        if ($dataRack->exists()) {
                            $modal .='<form id="fm_PermohonanRekondisi">
                            <div class="form-group">
                            <label class="form-label"><span class="beep"></span>Pilih dari rak mana buku akan direkondisi</label>
                            <div class="selectgroup selectgroup-pills">';
                            $exist = TRUE;
                            $dataLoop = $dataRack->groupBy('rm.nama')->get();
                            $footer = '<button class="d-block btn btn-sm btn-outline-primary btn-block" form="fm_PermohonanRekondisi">Submit</button>';
                            foreach ($dataLoop as $loop) {
                                $modal .='<label class="selectgroup-item">
                                  <input type="checkbox" name="rack_id" value="'.$loop->rack_id.'" data-name="'.$loop->nama.'" class="selectgroup-input" id="check'.$loop->rack_id.'">
                                  <span class="selectgroup-button">'.$loop->nama.' <span class="badge badge-pill badge-light">Total: '.$loop->total_diletakkan.'</span></span>
                                </label>';
                            }
                            $modal .='</div>
                          </div>
                            <div class="form-group" id="showInputJumlahRak">
                            <label class="form-label"><span class="beep"></span>Form jumlah yang direkondisi (<span class="text-danger">*Pilih rak terlebih dahulu</span>)</label>
                            <div id="inputRakRekondisi"></div>
                            </div>
                            <div class="form-group">
                            <label class="form-label"><span class="beep"></span>Catatan</label>
                            <textarea class="form-control"></textarea>
                            </div>
                            </form>
                          ';
                        } else {
                            $exist = FALSE;
                            $dataLoop = [];
                            $footer ='';
                            $modal .= '<div class="col-12 offset-3 mt-5">
                    <div class="row">
                        <div class="col-4 offset-1">
                        <h6 class="text-danger">#Tidak ada stok buku di rak!</h6>
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png"
                                width="100%">
                        </div>
                    </div>
                </div>';
                        }
                        $modal .='</div>
                    </div>
                </div>
                <div class="modal-footer">';
                $modal .= $footer;
                $modal .='<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
            </div>
        </div>
        </div>';
        return response()->json([
            'modal' => $modal,
            'exist' => $exist,
            'data' => $dataLoop
        ]);
    }
    protected function showModalRack($request)
    {
        try {
            $stok_id = $request->stok_id;
            $total_stok = $request->total_stok;
            $dataRack = DB::table('pj_st_rack_data as rd')
                ->leftJoin('pj_st_rack_master as rm', function ($q) {
                    $q->on('rd.rack_id', '=', 'rm.id')
                        ->whereNull('rm.deleted_at');
                })
                ->leftJoin('pj_st_andi as st', 'rd.stok_id', '=', 'st.id')
                ->where('rd.stok_id', $stok_id)
                ->select(
                    'rd.*',
                    'rm.kode',
                    'rm.nama',
                    'st.total_stok',
                    DB::raw('IFNULL(SUM(rd.jml_stok),0) as total_diletakkan'),
                    DB::raw('IFNULL(count(rd.id),0) as count_proses'
                ))
                ->orderBy('rd.created_at', 'ASC');
            $contentRack = '<p class="text-danger">Belum ada stok yang diiput dalam rak.</p>';
            if ($dataRack->exists()) {
                $contentRack = self::contentRack($dataRack->groupBy('rm.nama')->get());
            }
            $contentForm = self::contentForm();

            $totalMasuk = DB::table('pj_st_rack_data')
                ->where('stok_id', $stok_id)
                ->select(DB::raw('IFNULL(SUM(jml_stok),0) as total_diterima'))
                ->get();
            return [
                'stok_id' => $stok_id,
                'total_stok' => $total_stok,
                'total_masuk' => $totalMasuk[0]->total_diterima,
                'contentForm' => $contentForm,
                'contentRack' => $contentRack
            ];
        } catch (\Exception $e) {
            return abort(500, $e->getMessage());
        }
    }
    protected function showModalRackDetail($request)
    {
        $content = '';
        $rack_id = $request->rack_id;
        $stok_id = $request->stok_id;
        $nama_rak = $request->nama_rak;
        $data = DB::table('pj_st_rack_data')
            ->where('rack_id', $rack_id)
            ->where('stok_id', $stok_id)
            ->orderBy('created_at', 'DESC')->get();
        $totalStok = DB::table('pj_st_rack_data')
            ->where('rack_id', $rack_id)
            ->where('stok_id', $stok_id)
            ->orderBy('created_at', 'DESC')
            ->select(DB::raw('IFNULL(SUM(jml_stok),0) as total_stok'))
            ->get();
        $content .= '<div class="scroll-riwayat">
                            <table class="table table-striped" style="width:100%" id="tableRackDetail">
                            <thead style="position: sticky;top:0">
                              <tr>
                                <th scope="col" style="background: #eee;">No</th>
                                <th scope="col" style="background: #eee;">Operator Gudang</th>
                                <th scope="col" style="background: #eee;">Jumlah</th>
                                <th scope="col" style="background: #eee;">Tgl Masuk Gudang</th>
                                <th scope="col" style="background: #eee;">Tgl Diinput</th>
                                <th scope="col" style="background: #eee;">Otorisasi</th>
                                <th scope="col" style="background: #eee;">Action</th>
                              </tr>
                            </thead>
                            <tbody>';
        foreach ($data as $i => $d) {
            $i++;
            $created_by = $d->created_by;
            $d = (object)collect($d)->map(function ($item, $key) {
                switch ($key) {
                    case 'operators_id':
                        foreach (json_decode($item) as $data) {
                            $convert[] = DB::table('pj_st_op_master')->where('id', $data)->first()->nama;
                        }
                        $result = implode(", ", $convert);
                        return $result;
                        break;
                    case 'tgl_masuk_stok':
                        return Carbon::parse($item)->format('d-m-Y');
                        break;
                    case 'created_at':
                        return Carbon::parse($item)->format('d-m-Y H:i');
                        break;
                    case 'created_by':
                        return DB::table('users')->where('id', $item)->first()->nama;
                        break;
                    default:
                        return is_null($item) ? '-' : $item;
                        break;
                }
            })->all();
            $content .= '<tr>
                        <td id="row_num'. $i . '">'.$i.'<input type="hidden" name="task_number[]" value=' . $i . '></td>
                        <td>' . $d->operators_id . '</td>
                        <td>' . $d->jml_stok . '</td>
                        <td>' . $d->tgl_masuk_stok . '</td>
                        <td>' . $d->created_at . '</td>
                        <td><a href="' . url('/manajemen-web/user/' . $created_by) . '">' . $d->created_by . '</a></td>
                        <td><a href="javascript:void(0)"
                        class="d-flex btn btn-sm btn-outline-warning btn-icon mr-1 mt-1" id="btnEditRack" data-rack_id="' . $d->rack_id . '" data-stok_id="' . $d->stok_id . '" data-id="' . $d->id . '">
                        <i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)"
                        class="d-flex btn btn-sm btn-outline-danger btn-icon mr-1 mt-1" id="btnDeleteRack" data-nama_rak="'.$nama_rak.'" data-rack_id="' . $d->rack_id . '" data-stok_id="' . $d->stok_id . '" data-id="' . $d->id . '">
                        <i class="fas fa-trash"></i></a></td>
                        </tr>';
        }
        $content .= '</tbody>
            </table>
            </div>';
        $popover = '<a href="javascript:void(0)" class="text-primary" tabindex="0" role="button"
                data-toggle="popover" data-trigger="focus" title="Informasi"
                data-content="Total stok yang ada di rak ' . $nama_rak . ' sejumlah ' . $totalStok[0]->total_stok . ' buku.">
                <abbr title="">
                <i class="fas fa-info-circle me-3"></i>
                </abbr>
                </a>';
        return ['content' => $content, 'popover' => $popover];
    }
    protected function showModalRackEdit($request)
    {
        $id = $request->id;
        $rack_id = $request->rack_id;
        $stok_id = $request->stok_id;
        $content = '';
        $data = DB::table('pj_st_rack_data')
        ->where('id',$id)
        ->where('rack_id',$rack_id)
        ->where('stok_id',$stok_id)->first();
        $master = DB::table('pj_st_op_master')
            ->whereNull('deleted_at')
            ->get();
        $content .= '<div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputJmlStok">Jumlah</label>
            <input type="hidden" name="edit_id" value="'.$data->id.'">
            <input type="hidden" name="edit_rack_id" value="'.$data->rack_id.'">
            <input type="hidden" name="edit_stok_id" value="'.$data->stok_id.'">
            <input id="inputJmlStok" type="text" class="form-control" name="edit_jml_stok" value="'.$data->jml_stok.'" placeholder="Jumlah yang dimasukkan">
            <div id="err_edit_jml_stok"></div>
        </div>
        <div class="form-group col-md-6">
            <label for="datepickerEditRack">Masuk Gudang</label>
            <input type="text" class="form-control" value="'.Carbon::parse($data->tgl_masuk_stok)->format('d F Y').'" name="edit_tgl_masuk_stok"
                placeholder="DD/MM/YYYY" id="datepickerEditRack">
            <div id="err_edit_tgl_masuk_stok"></div>
        </div>
        </div>
        <div class="form-group">
            <label for="selectOptGudangEdit">Oleh</label>
            <select id="selectOptGudangEdit" class="form-control selectOptGudangEdit" name="edit_operators_id[]" multiple="multiple">';
            foreach($master as $o) {
                $sel = '';
                if (in_array($o->id, json_decode($data->operators_id))) {
                    $sel = ' selected="selected" ';
                }
                $content .='<option value="'.$o->id.'" '.$sel.'>'.$o->nama.'</option>';
            }
            $content .='</select>
            <div id="err_edit_operators_id"></div>
        </div>';
        return response()->json($content);
    }
    private function contentRack($data)
    {
        $contentRack = '';
        $contentRack .= '<div class="scroll-riwayat">
                            <table class="table table-striped" style="width:100%">
                            <thead style="position: sticky;top:0">
                              <tr>
                                <th scope="col" style="background: #eee;">No</th>
                                <th scope="col" style="background: #eee;">Rak</th>
                                <th scope="col" style="background: #eee;">Peletakan Rak</th>
                                <th scope="col" style="background: #eee;">Jumlah Buku</th>
                                <th scope="col" style="background: #eee;">Detail</th>
                              </tr>
                            </thead>
                            <tbody>';
        foreach ($data as $i => $kg) {
            $i++;
            $contentRack .= '<tr>
                                  <td id="row_num' . $i . '">' . $i . '<input type="hidden" name="task_number[]" value=' . $i . '></td>
                                  <td>' . $kg->nama . '</td>
                                  <td>' . $kg->count_proses . ' kali</td>
                                  <td id="jumlahDalamRak' . $kg->rack_id . '">' . $kg->total_diletakkan . ' pcs</td>
                                  <td><a href="javascript:void(0)" data-rack_id="' . $kg->rack_id . '" data-stok_id="' . $kg->stok_id . '" data-nama_rak="' . $kg->nama . '" id="btnDetailRak" data-toggle="modal" data-target="#modalDetailRack">
                                  <span class="badge badge-primary"><i class="fas fa-info-circle"></i> Detail</span>
                                  </a></td>
                                </tr>';
        }
        $contentRack .= '</tbody>
            </table>
            </div>';
        return $contentRack;
    }
    private function contentForm()
    {
        $contentForm = '<div class="input-group mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text bg-light text-dark" id="">Rak</span>
        </div>
        <select class="form-control select-rack" name="rak[]" required></select>
        <div class="input-group-prepend">
            <span class="input-group-text bg-light text-dark" id="">Jumlah</span>
        </div>
        <input type="text" class="form-control" name="jml_stok[]" placeholder="Jumlah yang dimasukkan" required>
        <div class="input-group-prepend">
            <span class="input-group-text bg-light text-dark" id="">Masuk Gudang</span>
        </div>
        <input type="text" class="form-control datepicker" name="tgl_masuk_stok[]"
            placeholder="DD/MM/YYYY" required>
        <div class="input-group-prepend">
            <span class="input-group-text bg-light text-dark" id="">Oleh</span>
        </div>
        <select class="form-control select-optgudang" name="users_id[0][]" multiple="multiple" required></select>
        </div>';
        return $contentForm;
    }
    protected function addDataRack($request)
    {
        try {
            $total_stok = $request->total_stok;
            $stok_id = $request->stok_id;
            $rak = $request->rak;
            $jml_stok = $request->jml_stok;
            $tgl_masuk_stok = $request->tgl_masuk_stok;
            // $users_id = $request->users_id;
            $users_id = array_values($request->users_id);
            $author = auth()->id();
            $check = DB::table('pj_st_rack_data')
                ->where('stok_id', $stok_id)
                ->select(DB::raw('IFNULL(SUM(jml_stok),0) as total_diterima'))
                ->get();
            $totalMasuk = $check[0]->total_diterima + array_sum($jml_stok);
            if ($totalMasuk > $total_stok) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jumlah input melebihi total stok!'
                ]);
            }
            $unique = array_unique($rak);
            if (count($unique) < count($rak)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rak tidak boleh duplikat!'
                ]);
            }
            for ($count = 0; $count < count($rak); $count++) {

                $data[] = [
                    'rack_id' => $rak[$count],
                    'stok_id' => $stok_id,
                    'jml_stok'  => $jml_stok[$count],
                    'tgl_masuk_stok'  => Carbon::createFromFormat('d F Y', $tgl_masuk_stok[$count])->format('Y-m-d'),
                    'operators_id'  => json_encode($users_id[$count]),
                    'created_by' => $author
                ];
                $totItem = DB::table('pj_st_rack_master')->where('id',$rak[$count])->first()->total_item + $jml_stok[$count];
                DB::table('pj_st_rack_master')->where('id',$rak[$count])->update([
                    'total_item' => $totItem
                ]);
            }
            $insert = [
                'params' => 'Insert Stock In Rack',
                'data' => $data
            ];
            event(new PenjualanStokEvent($insert));
            return response()->json([
                'status' => 'success',
                'message' => 'Data stok berhasil ditempatkan pada rak!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function editDataRack($request)
    {
        try {
            $id = $request->edit_id;
            $stok_id = $request->edit_stok_id;
            $rack_id = $request->edit_rack_id;
            $jml_stok = $request->edit_jml_stok;
            $tgl_masuk_stok = $request->edit_tgl_masuk_stok;
            $operators_id = $request->edit_operators_id;
            $total_stok = DB::table('pj_st_andi')->where('id',$stok_id)->select('total_stok')->first()->total_stok;
            $check = DB::table('pj_st_rack_data')
            ->where('stok_id', $stok_id)
            ->where('id','<>',$id)
            ->select(DB::raw('IFNULL(SUM(jml_stok),0) as total_diterima'))
            ->get();

            $totalMasuk = $check[0]->total_diterima + $jml_stok;
            if ($totalMasuk > $total_stok) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jumlah input melebihi total stok!'
                ]);
            }
            $update = [
                'params' => 'Update Stock In Rack',
                'id' => $id,
                'stok_id' => $stok_id,
                'jml_stok'  => $jml_stok,
                'tgl_masuk_stok'  => Carbon::createFromFormat('d F Y', $tgl_masuk_stok)->format('Y-m-d'),
                'operators_id'  => json_encode($operators_id)
            ];
            event(new PenjualanStokEvent($update));
            $totalDalamRak = DB::table('pj_st_rack_data')
            ->where('rack_id',$rack_id)
            ->where('stok_id', $stok_id)
            ->select(DB::raw('IFNULL(SUM(jml_stok),0) as total_dalam_rak'))
            ->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Data stok berhasil diperbarui!',
                'total_stok' => $total_stok,
                'total_masuk' => $totalMasuk,
                'total_dalam_rak' => $totalDalamRak[0]->total_dalam_rak,
                'rack_id' => $rack_id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function deleteDataRack($request)
    {
        try {
            $rack_id = $request->rack_id;
            $stok_id = $request->stok_id;
            $id = $request->id;
            $nama_rak = $request->nama_rak;
            DB::beginTransaction();
            DB::table('pj_st_rack_data')->delete($id);
            DB::commit();
            $totalStok = DB::table('pj_st_rack_data')
            ->where('rack_id', $rack_id)
            ->where('stok_id', $stok_id)
            ->orderBy('created_at', 'DESC')
            ->select(DB::raw('IFNULL(SUM(jml_stok),0) as total_stok'))
            ->get();
            $popover = '<a href="javascript:void(0)" class="text-primary" tabindex="0" role="button"
                data-toggle="popover" data-trigger="focus" title="Informasi"
                data-content="Total stok yang ada di rak ' . $nama_rak . ' sejumlah ' . $totalStok[0]->total_stok . ' buku.">
                <abbr title="">
                <i class="fas fa-info-circle me-3"></i>
                </abbr>
                </a>';
            return response()->json([
                'status' => 'success',
                'message' => 'Data stok berhasil diperbarui!',
                'popover' => $popover
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function selectRack($request)
    {
        $data = DB::table('pj_st_rack_master')
            ->whereNull('deleted_at')
            ->where('nama', 'like', '%' . $request->input('term') . '%')
            ->get();
        return response()->json($data);
    }
    protected function selectOperator($request)
    {
        $data = DB::table('pj_st_op_master')
            ->whereNull('deleted_at')
            ->where('nama', 'like', '%' . $request->input('term') . '%')
            ->get();
        return response()->json($data);
    }
}
