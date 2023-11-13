<?php

namespace App\Http\Controllers\PenjualanStok;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\ProduksiEvent;
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
                    case 'table-riwayat-aktivitas-rak':
                        return self::datatableRiwayatAktivitasRak($request);
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
                    case 'show-modal-rack-move':
                        return self::showModalRackMove($request);
                        break;
                    case 'show-modal-rack-history':
                        return self::showModalRackHistory($request);
                        break;
                    case 'select-rack':
                        return self::selectRack($request);
                        break;
                    case 'select-rack-for-move':
                        return self::selectRackMove($request);
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
                    case 'pindah-rack':
                        return self::pindahDataRack($request);
                        break;
                    case 'permohonan-rekondisi':
                        return self::actionPermohonanRekondisi($request);
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
            ->addColumn('judul_final', function ($data) {
                return ucfirst($data->judul_final);
            })
            ->addColumn('sub_judul_final', function ($data) {
                return $data->sub_judul_final ?? '-';
            })
            // ->addColumn('kelompok_buku', function ($data) {
            //     return $data->nama_kb . ' <i class="fas fa-chevron-right"></i> ' . $data->nama_skb;
            // })
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
            ->addColumn('zona1', function ($data) {
                return is_null($data->pengajuan_harga) ? '-' : $data->pengajuan_harga;
            })
            ->addColumn('zona2', function ($data) {
                $avail = DB::table('pj_st_harga_jual as hj')
                ->join('pj_st_master_harga_jual as mhj','hj.master_harga_jual_id','=','mhj.id')
                ->where('hj.stok_id',$data->id)
                ->where('mhj.nama','Zona 2')
                ->select('hj.*','mhj.nama')
                ->first();
                return is_null($avail) ? '0' : $avail->harga;
            })
            ->addColumn('zona3', function ($data) {
                $avail = DB::table('pj_st_harga_jual as hj')
                ->join('pj_st_master_harga_jual as mhj','hj.master_harga_jual_id','=','mhj.id')
                ->where('hj.stok_id',$data->id)
                ->where('mhj.nama','Zona 3')
                ->select('hj.*','mhj.nama')
                ->first();
                return is_null($avail) ? '0' : $avail->harga;
            })
            ->addColumn('action', function ($data) use ($update) {
                $btn = '<a href="' . url('produksi/proses/cetak/detail?no=' . $data->kode_sku . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1 tooltip-class" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                if ($update) {
                    $btn .= '<a href="' . url('produksi/proses/cetak/edit?no=' . $data->kode_sku . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1 tooltip-class" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                }
                $btn .='<a href="javascript:void(0)" class="d-block btn btn-light btn-icon mr-1 mt-1 tooltip-class" data-toggle="modal" data-judul="' . $data->judul_final . '" data-total_stok="' . $data->total_stok . '" data-stok_id="' . $data->id . '" data-target="#modalRack" title="Rak Buku" data-backdrop="static">
                <i class="fas fa-border-all"></i> Rak Buku
                </a>';
                return $btn;
            })
            ->rawColumns([
                'kode_sku',
                // 'kode',
                'judul_final',
                'sub_judul_final',
                // 'kelompok_buku',
                'penulis',
                'imprint',
                'total_stok',
                'zona1',
                'zona2',
                'zona3',
                'action'
            ])
            ->make(true);
    }
    protected function datatableRiwayatAktivitasRak($request)
    {
        $data = DB::table('pj_st_rack_data_activity')
            ->where('stok_id', $request->stok_id)
            ->orderBy('created_at', 'ASC')
            ->get();
        $start = 1;
        return DataTables::of($data)
            ->addColumn('no', function ($no) use (&$start) {
                return $start++;
            })
            ->addColumn('type_history', function ($data) {
                return $data->type_history;
            })
            ->addColumn('type_activity', function ($data) {
                return $data->type_activity;
            })
            ->addColumn('rack_id', function ($data) {
                return DB::table('pj_st_rack_master')->where('id', $data->rack_id)->first()->nama;
            })
            ->addColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->translatedFormat('d/m/Y');
            })
            ->addColumn('created_by', function ($data) {
                return DB::table('users')->where('id', $data->created_by)->first()->nama;
            })
            ->addColumn('qty', function ($data) {
                return $data->type_activity === 'Stok Masuk' ? '<b class="text-success">+'.$data->qty.'</b>' : '<b class="text-danger">-'.$data->qty.'</b>';
            })
            ->addColumn('sisa', function ($data) {
                return $data->sisa;
            })
            ->addColumn('keterangan', function ($data) {
                return $data->catatan ?? '-';
            })
            ->rawColumns([
                'no',
                'type_history',
                'type_activity',
                'rack_id',
                'created_at',
                'created_by',
                'qty',
                'sisa',
                'keterangan'
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
                DB::raw('IFNULL(SUM(rd.total_masuk),0) as total_diletakkan'),
                DB::raw(
                    'IFNULL(count(rd.id),0) as count_proses'
                )
            )
            ->orderBy('rd.id', 'ASC')->groupBy('rm.nama')->get();
        $modal = '';
        if (!$dataRack->isEmpty()) {
            $modal .= '<div class="form-group">
            <label class="form-label"><span class="beep"></span>Pilih dari rak mana buku akan direkondisi</label>
            <br>
            <input type="hidden" name="stok_id" value="'.$stok_id.'">
            <small class="text-danger" id="errTextRack"></small>
                            <div class="selectgroup selectgroup-pills">';
            $exist = TRUE;
            $dataLoop = $dataRack;
            foreach ($dataLoop as $loop) {
                $modal .= '<label class="selectgroup-item">
                                  <input type="checkbox" name="rack_id[]" value="' . $loop->rack_id . '" data-name="' . $loop->nama . '" class="selectgroup-input" id="check' . $loop->rack_id . '">
                                  <span class="selectgroup-button">' . $loop->nama . ' <span class="badge badge-pill badge-light">Total: ' . $loop->total_diletakkan . '</span></span>
                                </label>';
            }
            $modal .= '</div>
                          </div>
                            <div class="form-group" id="showInputJumlahRak">
                            <label class="form-label"><span class="beep"></span>Form jumlah yang direkondisi (<span class="text-danger">*Pilih rak terlebih dahulu</span>)</label>
                            <div id="inputRakRekondisi"></div>
                            </div>
                            <div class="form-group">
                            <label class="form-label"><span class="beep"></span>Catatan</label>
                            <textarea class="form-control" name="catatan"></textarea>
                            </div>
                          ';
        } else {
            $exist = FALSE;
            $dataLoop = [];
            $modal .= '<div class="col-12 offset-3 mt-5">
                    <div class="row">
                        <div class="col-4 offset-1">
                        <h6 class="text-danger">#Tidak ada stok buku di rak!</h6>
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png"
                                width="100%">
                        </div>
                    </div>
                </div>';
        };
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
                    'st.total_stok as stok_keseluruhan',
                    // 'rdd.jml_stok'
                )
                ->orderBy('rd.id', 'ASC');
            $contentRack = '<p class="text-danger">Belum ada stok yang diiput dalam rak.</p>';
            if ($dataRack->exists()) {
                $contentRack = self::contentRack($dataRack->get());
            }
            $contentForm = self::contentForm();

            $totalMasuk = DB::table('pj_st_rack_data')
                ->where('stok_id', $stok_id)
                ->select(DB::raw('IFNULL(SUM(total_masuk),0) as total_diterima'))
                ->get();
            $totalKeluar = DB::table('pj_st_rack_data_activity')
                ->where('stok_id', $stok_id)
                ->where('type_activity', 'Stok Keluar')
                ->select(DB::raw('IFNULL(SUM(qty),0) as total_keluar'))
                ->get();
            $totalPenjualan = DB::table('pj_st_rack_data_activity')
                ->where('stok_id', $stok_id)
                ->where('type_history', 'Penjualan')
                ->select(DB::raw('IFNULL(SUM(qty),0) as total_penjualan'))
                ->get();
            $totalLain = DB::table('pj_st_rack_data_activity')
                ->where('stok_id', $stok_id)
                ->where('type_history','<>', 'Penjualan')
                ->where('type_activity', 'Stok Keluar')
                ->select(DB::raw('IFNULL(SUM(qty),0) as total_lain'))
                ->get();
            return [
                'stok_id' => $stok_id,
                'total_stok' => $total_stok,
                'total_belum' => $total_stok - $totalMasuk[0]->total_diterima,
                'total_masuk' => $totalMasuk[0]->total_diterima,
                'total_keluar' => $totalKeluar[0]->total_keluar,
                'total_penjualan' => $totalPenjualan[0]->total_penjualan,
                'total_lain' => $totalLain[0]->total_lain,
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
        $rack_data_id = $request->rack_data_id;
        $rack_id = $request->rack_id;
        $stok_id = $request->stok_id;
        $nama_rak = $request->nama_rak;
        $data = DB::table('pj_st_rack_data_detail')
            ->where('rack_data_id', $rack_data_id)
            ->orderBy('created_at', 'DESC')->get();
        $totalStok = DB::table('pj_st_rack_data_detail')
            ->where('rack_data_id', $rack_data_id)
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
                        <td id="row_num' . $i . '">' . $i . '<input type="hidden" name="task_number[]" value=' . $i . '></td>
                        <td>' . $d->operators_id . '</td>
                        <td>' . $d->jml_stok . '</td>
                        <td>' . $d->tgl_masuk_stok . '</td>
                        <td>' . $d->created_at . '</td>
                        <td><a href="' . url('/manajemen-web/user/' . $created_by) . '">' . $d->created_by . '</a></td>
                        <td><a href="javascript:void(0)"
                        class="d-flex btn btn-sm btn-outline-warning btn-icon mr-1 mt-1" id="btnEditRack" data-rack_id="' . $rack_id . '" data-stok_id="' . $stok_id . '" data-id="' . $d->id . '">
                        <i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)"
                        class="d-flex btn btn-sm btn-outline-danger btn-icon mr-1 mt-1" id="btnDeleteRack" data-nama_rak="' . $nama_rak . '" data-rack_id="' . $rack_id . '" data-stok_id="' . $stok_id . '" data-id="' . $d->id . '">
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
        $data = DB::table('pj_st_rack_data_detail')
            ->where('id', $id)->first();
        $master = DB::table('pj_st_op_master')
            ->whereNull('deleted_at')
            ->get();
        $content .= '<div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputJmlStok">Jumlah</label>
            <input type="hidden" name="edit_id" value="' . $data->id . '">
            <input type="hidden" name="edit_rack_id" value="' . $rack_id . '">
            <input type="hidden" name="edit_stok_id" value="' . $stok_id . '">
            <input id="inputJmlStok" type="text" class="form-control" name="edit_jml_stok" value="' . $data->jml_stok . '" placeholder="Jumlah yang dimasukkan">
            <div id="err_edit_jml_stok"></div>
        </div>
        <div class="form-group col-md-6">
            <label for="datepickerEditRack">Masuk Gudang</label>
            <input type="text" class="form-control" value="' . Carbon::parse($data->tgl_masuk_stok)->format('d F Y') . '" name="edit_tgl_masuk_stok"
                placeholder="DD/MM/YYYY" id="datepickerEditRack">
            <div id="err_edit_tgl_masuk_stok"></div>
        </div>
        </div>
        <div class="form-group">
            <label for="selectOptGudangEdit">Oleh</label>
            <select id="selectOptGudangEdit" class="form-control selectOptGudangEdit" name="edit_operators_id[]" multiple="multiple">';
        foreach ($master as $o) {
            $sel = '';
            if (in_array($o->id, json_decode($data->operators_id))) {
                $sel = ' selected="selected" ';
            }
            $content .= '<option value="' . $o->id . '" ' . $sel . '>' . $o->nama . '</option>';
        }
        $content .= '</select>
            <div id="err_edit_operators_id"></div>
        </div>';
        return response()->json($content);
    }
    protected function showModalRackMove($request)
    {
        try {
            $rack_data_id = $request->rack_data_id;
            $data = DB::table('pj_st_rack_data as rd')
                ->leftJoin('pj_st_rack_master as rm', function ($q) {
                    $q->on('rd.rack_id', '=', 'rm.id')
                        ->whereNull('rm.deleted_at');
                })
                ->where('rd.id', $rack_data_id)
                ->select(
                    'rd.*',
                    'rm.kode',
                    'rm.nama',
                )->first();
            if (is_null($data)) {
                return abort(404);
            }
            return response()->json($data);
        } catch (\Exception $e) {
            return abort($e->getCode(), $e->getMessage());
        }
    }
    protected function showModalRackHistory($request)
    {
        try {
            $html = '';
            $rack_data_id = $request->rack_data_id;
            $data = DB::table('pj_st_rack_data_history as rdh')
                ->leftJoin('pj_st_rack_master as rm', function ($q) {
                    $q->on('rdh.pindah_dari_rack', '=', 'rm.id')
                        ->whereNull('rm.deleted_at');
                })
                ->where('rdh.rack_data_id', $rack_data_id)
                ->select(
                    'rdh.*',
                    'rm.kode',
                    'rm.nama',
                )
                ->orderBy('rdh.id', 'desc')
                ->paginate(2);
            $rack = DB::table('pj_st_rack_data as rd')
                ->leftJoin('pj_st_rack_master as rm', function ($q) {
                    $q->on('rd.rack_id', '=', 'rm.id')
                        ->whereNull('rm.deleted_at');
                })
                ->where('rd.id', $rack_data_id)
                ->select(
                    'rd.*',
                    'rm.kode',
                    'rm.nama',
                )
                ->first();
            if ($data->total() < 1) {
                $html .= '<div class="col-12 offset-3 mt-5">
                    <div class="row">
                        <div class="col-4 offset-1">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png"
                                width="100%">
                        </div>
                    </div>
                </div>';
                return response()->json([
                    'status' => 'error',
                    'html' => $html,
                    'title' => $rack->nama
                ]);
            }
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Move':
                        $nameOpr = (array)collect(json_decode($d->operator_pindah))->map(function ($item) {
                            return DB::table('pj_st_op_master')
                                ->whereNull('deleted_at')->where('id', $item)->first()->nama;
                        })->all();
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Item pindahan dari rak <b class="text-dark">' . $d->nama . '</b> dengan detail:<br>
                            -Jumlah stok item dipindah: <b class="text-dark">' . $d->jml_stok_pindah . '</b>.<br>
                            -Operator gudang: <b class="text-dark">' . implode(', ', $nameOpr) . '</b>.<br>
                            -Tanggal stok item dipindah: <b class="text-dark">' . Carbon::parse($d->tgl_masuk_stok_pindah)->translatedFormat('d F Y') . '</b>.<br>
                            </span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . DB::table('users')->where('id', $d->author_id)->whereNull('deleted_at')->first()->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Edit':
                        $html .= '<span class="ticket-item">';
                        if (!is_null($d->jml_stok_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah stok yang masuk <b class="text-dark">' . $d->jml_stok_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_stok_new . '</b>.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->operator_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $operatorHIS = '';
                            $operatorNEW = '';
                            foreach (json_decode($d->operator_his, true) as $sethis) {
                                $operatorHIS .= '<b class="text-dark">' . DB::table('pj_st_op_master')
                                    ->whereNull('deleted_at')->where('id', $sethis)->first()->nama . '</b>, ';
                            }
                            foreach (json_decode($d->operator_new, true) as $setnew) {
                                $operatorNEW .= '<span class="bullet"></span>' . DB::table('pj_st_op_master')
                                    ->whereNull('deleted_at')->where('id', $setnew)->first()->nama;
                            }
                            $html .= ' Operator <b class="text-dark">' . $operatorHIS . '</b> diubah menjadi <b class="text-dark">' . $operatorNEW . '</b>.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->tgl_masuk_stok_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Tanggal masuk rak <b class="text-dark">' . Carbon::parse($d->tgl_masuk_stok_his)->translatedFormat('d F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->tgl_masuk_stok_new)->translatedFormat('d F Y') . '</b>.';
                            $html .= '</span></div>';
                        }
                        $html .= '<div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . DB::table('users')->where('id', $d->author_id)->whereNull('deleted_at')->first()->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                        break;
                    case 'Delete':
                        $nameOpr = (array)collect(json_decode($d->operator_pindah))->map(function ($item) {
                            return DB::table('pj_st_op_master')
                                ->whereNull('deleted_at')->where('id', $item)->first()->nama;
                        })->all();
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Item dihapus sejumlah <b class="text-dark">' . $d->jml_stok_his . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . DB::table('users')->where('id', $d->author_id)->whereNull('deleted_at')->first()->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                }
            }
            return response()->json([
                'status' => 'success',
                'html' => $html,
                'rack_data_id' => $rack_data_id,
                'title' => $rack->nama
            ]);
        } catch (\Exception $e) {
            return abort($e->getCode(), $e->getMessage());
        }
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
                                  <td id="jumlahDalamRak' . $kg->rack_id . '">' . $kg->total_masuk . ' pcs</td>
                                  <td><a href="javascript:void(0)" data-rack_data_id="' . $kg->id . '" data-rack_id="' . $kg->rack_id . '" data-stok_id="' . $kg->stok_id . '" data-nama_rak="' . $kg->nama . '" id="btnDetailRak" data-toggle="modal" data-target="#modalDetailRack">
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
            $users_id = array_values($request->users_id);
            $totalInputJmlStok = array_sum($jml_stok);
            $author = auth()->id();
            $check = DB::table('pj_st_rack_data')
                ->where('stok_id', $stok_id)
                ->select(DB::raw('IFNULL(SUM(total_masuk),0) as total_diterima'))
                ->get();
            $totalMasuk = $check[0]->total_diterima + $totalInputJmlStok;
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
                $checkRack = DB::table('pj_st_rack_data')
                    ->where('stok_id', $stok_id)
                    ->where('rack_id', $rak[$count])
                    ->first();
                if (is_null($checkRack)) {
                    $rackData = [
                        'rack_id' => $rak[$count],
                        'stok_id' => $stok_id,
                        'total_masuk'  => $jml_stok[$count],
                    ];
                    DB::table('pj_st_rack_data')->insert($rackData);
                    $rack_data_id =  DB::getPdo()->lastInsertId();
                } else {
                    $rack_data_id = $checkRack->id;
                    DB::table('pj_st_rack_data')
                        ->where('stok_id', $stok_id)
                        ->where('rack_id', $rak[$count])
                        ->update([
                            'total_masuk' => $checkRack->total_masuk + $jml_stok[$count]
                        ]);
                }
                $rackDataDetail[] = [
                    'rack_data_id' => $rack_data_id,
                    'jml_stok'  => $jml_stok[$count],
                    'tgl_masuk_stok'  => Carbon::createFromFormat('d F Y', $tgl_masuk_stok[$count])->format('Y-m-d'),
                    'operators_id'  => json_encode($users_id[$count]),
                    'created_by' => $author
                ];
                $totItem = DB::table('pj_st_rack_master')->where('id', $rak[$count])->first()->total_item + $jml_stok[$count];
                DB::table('pj_st_rack_master')->where('id', $rak[$count])->update([
                    'total_item' => $totItem
                ]);
                $checkRiwayat = DB::table('pj_st_rack_data_activity')->where('stok_id', $stok_id)->orderBy('id', 'DESC')->first();
                $sisa = is_null($checkRiwayat) ? 0 + $jml_stok[$count] : $checkRiwayat->sisa + $jml_stok[$count];
                $insertRiwayat = [
                    'params' => 'Insert Riwayat Aktivitas Rak',
                    'rack_id' => $rak[$count],
                    'stok_id' => $stok_id,
                    'type_history' => 'Buku Baru',
                    'type_activity' => 'Stok Masuk',
                    'qty' => $jml_stok[$count],
                    'sisa' => $sisa,
                    'catatan' => NULL,
                    'created_by' => auth()->user()->id
                ];
                event(new PenjualanStokEvent($insertRiwayat));
            }
            $insert = [
                'params' => 'Insert Stock Detail In Rack',
                'data' => $rackDataDetail
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
            $total_stok = DB::table('pj_st_andi')->where('id', $stok_id)->select('total_stok')->first()->total_stok;
            $check = DB::table('pj_st_rack_data_detail as rdd')
                ->join('pj_st_rack_data as rd', 'rd.id', '=', 'rdd.rack_data_id')
                ->where('rd.stok_id', $stok_id)
                ->where('rdd.id', '<>', $id)
                ->select(DB::raw('IFNULL(SUM(rdd.jml_stok),0) as total_diterima'))
                ->get();

            //Check Jika Total Yang masuk rak melebihi stok
            $totalMasuk = $check[0]->total_diterima + $jml_stok;
            if ($totalMasuk > $total_stok) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jumlah input melebihi total stok!'
                ]);
            }
            //Check untuk update total kesuluruhan di rack
            $totalPerRak = DB::table('pj_st_rack_data_detail')
                ->where('id', $id)
                ->first();
            $update = [
                'params' => 'Update Stock In Rack',
                'id' => $id,
                'jml_stok'  => $jml_stok,
                'tgl_masuk_stok'  => Carbon::createFromFormat('d F Y', $tgl_masuk_stok)->format('Y-m-d'),
                'operators_id'  => json_encode($operators_id)
            ];
            event(new PenjualanStokEvent($update));
            if (($totalPerRak->jml_stok != $jml_stok) || (json_encode($operators_id) != $totalPerRak->operators_id) || (date('Y-m-d', strtotime($tgl_masuk_stok)) != date('Y-m-d', strtotime($totalPerRak->tgl_masuk_stok)))) {
                //INSERT HISTORY EDIT
                DB::table('pj_st_rack_data_history')->insert([
                    'rack_data_id' => $totalPerRak->rack_data_id,
                    'type_history' => 'Edit',
                    'jml_stok_his' => $totalPerRak->jml_stok == $jml_stok ? NULL : $totalPerRak->jml_stok,
                    'jml_stok_new' => $totalPerRak->jml_stok == $jml_stok ? NULL : $jml_stok,
                    'operator_his' => json_encode($operators_id) == $totalPerRak->operators_id ? NULL : $totalPerRak->operators_id,
                    'operator_new' => json_encode($operators_id) == $totalPerRak->operators_id ? NULL : json_encode($operators_id),
                    'tgl_masuk_stok_his' => date('Y-m-d', strtotime($tgl_masuk_stok)) == date('Y-m-d', strtotime($totalPerRak->tgl_masuk_stok)) ? NULL : date('Y-m-d', strtotime($totalPerRak->tgl_masuk_stok)),
                    'tgl_masuk_stok_new' => date('Y-m-d', strtotime($tgl_masuk_stok)) == date('Y-m-d', strtotime($totalPerRak->tgl_masuk_stok)) ? NULL : Carbon::createFromFormat('d F Y', $tgl_masuk_stok)->format('Y-m-d'),
                    'author_id' => auth()->id()
                ]);
            }
            $totalDalamRak = DB::table('pj_st_rack_data_detail as rdd')
                ->join('pj_st_rack_data as rd', 'rd.id', '=', 'rdd.rack_data_id')
                ->where('rd.rack_id', $rack_id)
                ->where('rd.stok_id', $stok_id)
                ->select(DB::raw('IFNULL(SUM(rdd.jml_stok),0) as total_dalam_rak'))
                ->get();
            $totalItem = DB::table('pj_st_rack_master')->where('id', $rack_id)->first()->total_item;
            $updateTotalItem = $totalItem - $totalPerRak->jml_stok;
            $hasilTotalStokEdit = $updateTotalItem + $jml_stok;
            if ($totalItem != $hasilTotalStokEdit) {
                //Update Rack Master Total Item
                DB::beginTransaction();
                DB::table('pj_st_rack_master')->where('id', $rack_id)->update([
                    'total_item' => $hasilTotalStokEdit
                ]);
                DB::commit();
            }
            $totalStokPerRak = DB::table('pj_st_rack_data')
                ->where('rack_id', $rack_id)
                ->where('stok_id', $stok_id)
                ->first()->total_masuk;
            $updateTotalStok = $totalStokPerRak - $totalPerRak->jml_stok;
            $hasilTotalStokEdit = $updateTotalStok + $jml_stok;
            if ($totalStokPerRak != $hasilTotalStokEdit) {
                //Update Rack Master Total Item
                DB::beginTransaction();
                DB::table('pj_st_rack_data')
                    ->where('rack_id', $rack_id)
                    ->where('stok_id', $stok_id)->update([
                        'total_masuk' => $hasilTotalStokEdit
                    ]);
                DB::commit();
            }
            $checkRiwayat = DB::table('pj_st_rack_data_activity')->where('stok_id', $stok_id)->orderBy('id', 'DESC')->first();

            if ($totalPerRak->jml_stok != $jml_stok) {
                $type_activity = $jml_stok > $totalPerRak->jml_stok ? 'Stok Masuk':'Stok Keluar';
                $qty = $jml_stok > $totalPerRak->jml_stok ? $jml_stok - $totalPerRak->jml_stok : abs($jml_stok - $totalPerRak->jml_stok);
                $sisa = $type_activity == 'Stok Masuk' ? $checkRiwayat->sisa + $qty : $checkRiwayat->sisa - $qty;
                $insertRiwayat = [
                    'params' => 'Insert Riwayat Aktivitas Rak',
                    'rack_id' => $rack_id,
                    'stok_id' => $stok_id,
                    'type_history' => 'Adjustment',
                    'type_activity' => $type_activity,
                    'qty' => $qty,
                    'sisa' => $sisa,
                    'catatan' => NULL,
                    'created_by' => auth()->user()->id
                ];
                event(new PenjualanStokEvent($insertRiwayat));
            }
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
            $totalRiwayat = DB::table('pj_st_rack_data_detail as rdd')
                ->join('pj_st_rack_data as rd', 'rd.id', '=', 'rdd.rack_data_id')
                ->where('rd.stok_id', $stok_id)
                ->where('rd.rack_id', $rack_id)
                ->select('rdd.*')
                ->get()->count();
            //Check untuk update total kesuluruhan di rack
            $totalItem = DB::table('pj_st_rack_master')->where('id', $rack_id)->first()->total_item;
            $up_JUM = DB::table('pj_st_rack_data_detail')->where('id', $id)->first();
            $totItemDiRak = $totalItem - $up_JUM->jml_stok;
            $checkRiwayat = DB::table('pj_st_rack_data_activity')->where('stok_id', $stok_id)->orderBy('id', 'DESC')->first();

            $type_activity = 'Stok Keluar';
            $qty = $up_JUM->jml_stok;
            $sisa = $checkRiwayat->sisa - $qty;
            $insertRiwayat = [
                'params' => 'Insert Riwayat Aktivitas Rak',
                'rack_id' => $rack_id,
                'stok_id' => $stok_id,
                'type_history' => 'Adjustment',
                'type_activity' => $type_activity,
                'qty' => $qty,
                'sisa' => $sisa,
                'catatan' => NULL,
                'created_by' => auth()->user()->id
            ];
            event(new PenjualanStokEvent($insertRiwayat));
            if ($totalRiwayat == 1) {
                DB::beginTransaction();
                //CASCADE MYSQL DELETE
                DB::table('pj_st_rack_master')
                    ->where('id', $rack_id)
                    ->decrement('total_item',$up_JUM->jml_stok);
                DB::table('pj_st_rack_data')
                    ->where('stok_id', $stok_id)
                    ->where('rack_id', $rack_id)
                    ->delete();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil dihapus!',
                    'load' => TRUE
                ]);
            }
            DB::beginTransaction();
            $up_TOT = DB::table('pj_st_rack_data')->where('stok_id', $stok_id)
                ->where('rack_id', $rack_id)->first();
            DB::table('pj_st_rack_data_detail')->delete($id);
            $totStokDiRak = $up_TOT->total_masuk - $up_JUM->jml_stok;
            DB::table('pj_st_rack_data as rd')
                ->join('pj_st_rack_master as rm', 'rm.id', '=', 'rd.rack_id')
                ->where('rd.stok_id', $stok_id)
                ->where('rd.rack_id', $rack_id)
                ->update([
                    'rd.total_masuk' => $totStokDiRak,
                    'rm.total_item' => $totItemDiRak
                ]);
            //INSERT HISTORY DELETE
            DB::table('pj_st_rack_data_history')->insert([
                'rack_data_id' => $up_TOT->id,
                'type_history' => 'Delete',
                'jml_stok_his' => $up_JUM->jml_stok,
                'author_id' => auth()->id()
            ]);
            DB::commit();

            $totalStok = DB::table('pj_st_rack_data_detail as rdd')
                ->join('pj_st_rack_data as rd', 'rd.id', '=', 'rdd.rack_data_id')
                ->where('rd.rack_id', $rack_id)
                ->where('rd.stok_id', $stok_id)
                ->select(DB::raw('IFNULL(SUM(rdd.jml_stok),0) as total_stok'))
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
                'popover' => $popover,
                'load' => FALSE
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function pindahDataRack($request)
    {
        try {
            $rack_data_id = $request->rack_data_id;
            $current_rack_id = $request->current_rack_id;
            $stok_id = $request->stok_id;
            $rack_id = $request->rack_id;
            $jml_stok_rak = $request->jml_stok_rak;
            $tgl_masuk_stok = Carbon::createFromFormat('d F Y', $request->tgl_pindah)->format('Y-m-d');
            $users_id = array_values($request->users_id);
            $totalStok = DB::table('pj_st_rack_data_detail')
                ->where('rack_data_id', $rack_data_id)
                ->orderBy('created_at', 'DESC')
                ->select(DB::raw('IFNULL(SUM(jml_stok),0) as total_stok'))
                ->get();
            if ($jml_stok_rak > $totalStok[0]->total_stok) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jumlah item yang dipindah melebihi stok di dalam rak!'
                ]);
            } elseif ($jml_stok_rak == $totalStok[0]->total_stok) {
                //DELETE CASCADE
                DB::table('pj_st_rack_data')->delete($rack_data_id);
            } elseif ($jml_stok_rak < $totalStok[0]->total_stok) {
                $dataRackCurrent = DB::table('pj_st_rack_data')
                    ->where('stok_id', $stok_id)
                    ->where('rack_id', $current_rack_id) //RAK SAAT INI
                    ->first();
                DB::table('pj_st_rack_data')
                    ->where('stok_id', $stok_id)
                    ->where('rack_id', $current_rack_id)->update([
                        'total_masuk' => $totalStok[0]->total_stok - $jml_stok_rak
                    ]);
                //Update Detail
                $detailRack = DB::table('pj_st_rack_data_detail')->where('rack_data_id', $dataRackCurrent->id)
                    ->orderBy('id', 'DESC')
                    ->get();
                $res = 0;
                foreach ($detailRack as $dr) {
                    if ($jml_stok_rak >= $dr->jml_stok) {
                        $res = $jml_stok_rak - $dr->jml_stok;
                        DB::table('pj_st_rack_data_detail')->delete($dr->id);
                    } else {
                        if ($res == 0) {
                            $res =  (int)$dr->jml_stok - (int)$jml_stok_rak;
                        } else {
                            $res =  (int)$dr->jml_stok - $res;
                        }
                        DB::table('pj_st_rack_data_detail')->where('id', $dr->id)->update([
                            'jml_stok' => $res
                        ]);
                        break;
                    }
                }
            }

            $dataRackNew = DB::table('pj_st_rack_data')
                ->where('stok_id', $stok_id)
                ->where('rack_id', $rack_id) //RAK TUJUAN PEMINDAHAN
                ->first();
            if (is_null($dataRackNew)) {
                $rackData = [
                    'rack_id' => $rack_id,
                    'stok_id' => $stok_id,
                    'total_masuk'  => $jml_stok_rak,
                ];
                DB::table('pj_st_rack_data')->insert($rackData);
                $rack_data_id =  DB::getPdo()->lastInsertId();
            } else {
                $rack_data_id = $dataRackNew->id;
                DB::table('pj_st_rack_data')
                    ->where('stok_id', $stok_id)
                    ->where('rack_id', $rack_id)
                    ->increment('total_masuk',$jml_stok_rak);
            }
            $rackDataDetail = [
                'rack_data_id' => $rack_data_id,
                'jml_stok'  => $jml_stok_rak,
                'tgl_masuk_stok'  => $tgl_masuk_stok,
                'operators_id'  => json_encode($users_id),
                'created_by' => auth()->id()
            ];
            $insert = [
                'params' => 'Insert Stock Detail In Rack',
                'data' => $rackDataDetail
            ];
            event(new PenjualanStokEvent($insert));
            //UPDATE MASTER RACK
            $dataCurrentRackMaster = DB::table('pj_st_rack_master')->where('id', $current_rack_id)->first();
            $dataNewRackMaster = DB::table('pj_st_rack_master')->where('id', $rack_id)->first();
            $array = [
                [
                    'id' => $current_rack_id,
                    'total_item' => $dataCurrentRackMaster->total_item - $jml_stok_rak,
                ],
                [
                    'id' => $rack_id,
                    'total_item' => $dataNewRackMaster->total_item + $jml_stok_rak,
                ],
            ];
            foreach ($array as $arr) {
                DB::table('pj_st_rack_master')->where('id', $arr['id'])->update([
                    'total_item' => $arr['total_item']
                ]);
            }
            //INSERT HISTORY PINDAH
            DB::table('pj_st_rack_data_history')->insert([
                'rack_data_id' => $rack_data_id,
                'type_history' => 'Move',
                'jml_stok_pindah' => $jml_stok_rak,
                'operator_pindah' => json_encode($users_id),
                'tgl_masuk_stok_pindah' => $tgl_masuk_stok,
                'pindah_dari_rack' => $current_rack_id,
                'author_id' => auth()->id()
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Buku berhasil dipindahkan!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
            // return response()->json([
            //     'status' => 'error',
            //     'message' => $e->getMessage()
            // ]);
        }
    }
    protected function actionPermohonanRekondisi($request)
    {
        try {
            $stok_id = $request->stok_id;
            $rack_id = array_values($request->rekondisi_rak_id);
            $jml_rekondisi = array_values($request->jml_rekondisi);
            $sum_jml_rekondisi = array_sum($request->jml_rekondisi);
            $nama_rak = array_values($request->nama_rak);
            $total_dalam_rak = array_values($request->total_dalam_rak);
            $catatan = $request->catatan;
            $data_stok = DB::table('pj_st_andi')->where('id',$stok_id)->first();

            for ($count = 0; $count < count($rack_id); $count++) {
                if ($jml_rekondisi[$count] > $total_dalam_rak[$count]) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jumlah input rekondisi tidak boleh melebihi total di dalam rak '.$nama_rak[$count].'!'
                    ]);
                }
                $rack_data = DB::table('pj_st_rack_data')
                ->where('rack_id',$rack_id[$count])
                ->where('stok_id',$stok_id)
                ->first();
                if (is_null($rack_data)) {
                    return abort(404);
                }
                if($jml_rekondisi[$count] == $rack_data->total_masuk) {
                    DB::beginTransaction();
                    DB::table('pj_st_rack_data')
                    ->where('rack_id',$rack_id[$count])
                    ->where('stok_id',$stok_id)->delete();
                    DB::commit();
                } else {
                    $detailRack = DB::table('pj_st_rack_data_detail')->where('rack_data_id', $rack_data->id)
                    ->orderBy('id', 'DESC')
                    ->get();
                    $res = 0;
                    foreach ($detailRack as $dr) {
                        if ($jml_rekondisi[$count] >= $dr->jml_stok) {
                            $res = $jml_rekondisi[$count] - $dr->jml_stok;
                            DB::table('pj_st_rack_data_detail')->delete($dr->id);
                        } else {
                            if ($res == 0) {
                                $res =  (int)$dr->jml_stok - (int)$jml_rekondisi[$count];
                            } else {
                                $res =  (int)$dr->jml_stok - $res;
                            }
                            DB::table('pj_st_rack_data_detail')->where('id', $dr->id)->update([
                                'jml_stok' => $res
                            ]);
                            break;
                        }
                    }
                    DB::table('pj_st_rack_data')
                    ->where('stok_id', $stok_id)
                    ->where('rack_id', $rack_id[$count])
                    ->decrement('total_masuk',$jml_rekondisi[$count]);
                }
                DB::table('pj_st_rack_master')->where('id',$rack_id[$count])->decrement('total_item',$jml_rekondisi[$count]);
                $checkRiwayat = DB::table('pj_st_rack_data_activity')->where('stok_id', $stok_id)->orderBy('id', 'DESC')->first();
                $type_activity = 'Stok Keluar';
                $qty = $jml_rekondisi[$count];
                $sisa = $checkRiwayat->sisa - $qty;
                $insertRiwayat = [
                    'params' => 'Insert Riwayat Aktivitas Rak',
                    'rack_id' => $rack_id[$count],
                    'stok_id' => $stok_id,
                    'type_history' => 'Rekondisi',
                    'type_activity' => $type_activity,
                    'qty' => $qty,
                    'sisa' => $sisa,
                    'catatan' => NULL,
                    'created_by' => auth()->user()->id
                ];
                event(new PenjualanStokEvent($insertRiwayat));

            }
            //Update Stok Global
            DB::beginTransaction();
            DB::table('pj_st_andi')->where('id',$stok_id)->update([
                'total_stok' => $data_stok->total_stok - $sum_jml_rekondisi
            ]);
            DB::commit();
            //END
            $insertRekondisi = [
                'params' => 'Create Data Rekondisi Dari Gudang',
                'id' => Uuid::uuid4()->toString(),
                'kode' => NULL, //Perlu Approve baru keluar kode
                'rekondisi_dari' => 'Gudang',
                'naskah_id' => $data_stok->naskah_id,
                'jml_rekondisi' => $sum_jml_rekondisi,
                'catatan' => $catatan,
                'status' => 'approval',
                'created_by' => auth()->user()->id
            ];
            event(new ProduksiEvent($insertRekondisi));
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengirim permohonan rekondisi!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return abort($e->getCode(),$e->getMessage());
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
    protected function selectRackMove($request)
    {
        $data = DB::table('pj_st_rack_master')
            ->whereNull('deleted_at')
            ->where('id', '<>', $request->rack_id)
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
