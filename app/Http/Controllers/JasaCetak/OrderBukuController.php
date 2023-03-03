<?php

namespace App\Http\Controllers\JasaCetak;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\JasaCetakEvent;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class OrderBukuController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = DB::table('jasa_cetak_order_buku')
            ->orderBy('tgl_order', 'ASC')
            ->get();

            return DataTables::of($data)
                ->addColumn('jalur_proses', function ($data) {
                    $label = 'Belum ditentukan kabag';
                    if (Gate::allows('do_update','otorisasi-kabag-order-buku-jasa-cetak')) {
                        $label = 'Belum anda tentukan';
                    }
                    return is_null($data->jalur_proses) ? '<span class="text-danger">'.$label.'</span>':$data->jalur_proses;
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('jasa_cetak_order_buku_history')->where('order_buku_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judul="' . $data->judul_buku . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('status', function ($data) {

                    $btn = $this->panelStatus($data->id,$data->no_order,$data->judul_buku,$data->status);

                    return $btn;
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('jasa-cetak/order-buku/detail?order=' . $data->id . '&kode=' . $data->no_order) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                    $btn = $this->buttonAction($data->id,$data->no_order,$btn);

                    return $btn;
                })
                ->rawColumns([
                    'jalur_proses',
                    'history',
                    'status',
                    'action'
                ])
                ->make(true);
        }
        $data = DB::table('jasa_cetak_order_buku')
            ->orderBy('tgl_order', 'ASC')
            ->get();
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM jasa_cetak_order_buku WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        $statusAction = Arr::except($statusProgress, ['4']);
        return view('jasa_cetak.order_buku.index', [
            'title' => 'Jasa Cetak Order Buku',
            'status_progress' => $statusProgress,
            'status_action' => $statusAction,
            'count' => count($data)
        ]);
    }
    public function createOrder(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->request_) {
                case 'getNomorOrder':
                    return self::generateId();
                    break;
                case 'createOrder':
                    $request->validate([
                        'add_no_order' => 'required|unique:jasa_cetak_order_buku,no_order',
                    ], [
                        'required' => 'This field is requried'
                    ]);
                    $noOrder = $request->input('add_no_order');
                    $jmlOrder = explode(",",$request->add_jml_order);
                    $last = DB::table('jasa_cetak_order_buku')
                        ->select('no_order')
                        ->where('no_order',$noOrder)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    if (!is_null($last)) {
                        $noOrder = self::generateId();
                    }
                    DB::beginTransaction();

                    try {
                        $idN = Uuid::uuid4()->toString();
                        // DB::table('penerbitan_naskah_files')->insert($fileNaskah);
                        // $penilaian = $this->alurPenilaian($request->input('add_jalur_buku'), 'create-notif-from-naskah', [
                        //     'id_prodev' => $request->input('add_pic_prodev'),
                        //     'form_id' => $idN
                        // ]);
                        $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        $addOrderBuku = [
                            'params' => 'Add Order Buku',
                            'id' => $idN,
                            'no_order' => $noOrder,
                            'judul_buku' => $request->input('add_judul_buku'),
                            'tgl_order' => Carbon::createFromFormat('d F Y', $request->input('add_tgl_order'))
                                ->format('Y-m-d'),
                            'tgl_permintaan_selesai' => Carbon::createFromFormat('d F Y', $request->input('add_tgl_permintaan_selesai'))
                                ->format('Y-m-d'),
                            'nama_pemesan' => $request->input('add_nama_pemesan'),
                            'pengarang' => $request->input('add_pengarang'),
                            'format' => $request->input('add_format'),
                            'jml_halaman' => $request->input('add_jml_halaman'),
                            'kertas_isi' => $request->input('add_kertas_isi'),
                            'kertas_isi_tinta' => $request->input('add_kertas_isi_tinta'),
                            'kertas_cover' => $request->input('add_kertas_cover'),
                            'kertas_cover_tinta' => $request->input('add_kertas_cover_tinta'),
                            'jml_order' => is_null($request->input('add_jml_order')) ? NULL : json_encode($jmlOrder),
                            'status_cetak' => $request->input('add_status_cetak'),
                            'keterangan' => $request->input('add_keterangan'),
                            'created_by' => auth()->id()
                        ];
                        event(new JasaCetakEvent($addOrderBuku));

                        // $addTimeline = [
                        //     'params' => 'Insert Timeline',
                        //     'id' => Uuid::uuid4()->toString(),
                        //     'progress' => ($penilaian['selesai_penilaian'] > 0) ? 'Pelengkapan Data Penulis' : 'Penilaian Naskah',
                        //     'naskah_id' => $idN,
                        //     'tgl_mulai' => $tgl,
                        //     'url_action' => urlencode(URL::to('/penerbitan/naskah/melihat-naskah',$idN)),
                        //     'status' => 'Proses'
                        // ];
                        // event(new TimelineEvent($addTimeline));
                        DB::commit();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Berhasil menambah order buku!'
                        ]);
                    } catch (\Exception $e) {
                        DB::rollback();
                        return response()->json([
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ]);
                    }
                    break;
                default:
                return abort(500);
                    break;
            }
        }
        $type = DB::select(DB::raw("SHOW COLUMNS FROM jasa_cetak_order_buku WHERE Field = 'status_cetak'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $status_cetak = explode("','", $matches[1]);
        return view('jasa_cetak.order_buku.create', [
            'kode' => self::generateId(),
            'status_cetak' => $status_cetak,
            'title' => 'Tambah Order Buku Jasa Cetak'
        ]);
    }
    public function updateOrder(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('GET')) {
                $id = $request->order;
                $no_order = $request->kode;
                $data = DB::table('jasa_cetak_order_buku')
                ->where('id',$id)
                ->where('no_order',$no_order)
                ->first();
                if (is_null($data)) {
                    return abort(404);
                }
            } else {
                $noOrder = $request->input('edit_no_order');
                $data = DB::table('jasa_cetak_order_buku')
                ->where('id',$request->id)
                ->where('no_order',$noOrder)
                ->first();
                if (is_null($data)) {
                    return abort(404);
                }
            }
            switch ($request->request_) {
                case 'getValue':
                    $data = (object)collect($data)->map(function ($item, $key) {
                        switch ($key) {
                            case 'jml_order':
                                $json = json_decode($item);
                                $implode = implode(",",$json);
                                return !is_null($item) ? $implode : NULL;
                                break;
                            case 'kalkulasi_harga':
                                if (!is_null($item)) {
                                    $json = json_decode($item);
                                    $implode = implode(",",$json);
                                } else {
                                    $implode = NULL;
                                }
                                return $implode;
                                break;
                            case 'tgl_order':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : NULL;
                                break;
                            case 'tgl_permintaan_selesai':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : NULL;
                                break;
                            case 'created_at':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item)->format('d/m/Y H:i:s') : '-';
                                break;
                            case 'created_by':
                                return !is_null($item) ? DB::table('users')->where('id',$item)->first()->nama : NULL;
                                break;
                            default:
                                $item;
                                break;
                        }
                        return $item;
                    })->all();
                    if (Gate::allows('do_update','otorisasi-kalkulasi-order-buku-jasa-cetak')) {
                        $gate = TRUE;
                    } else {
                        $gate = FALSE;
                    }
                    return ['data' => $data,'gate'=> $gate];
                    break;
                case 'updateOrder':
                    DB::beginTransaction();

                    try {
                        // DB::table('penerbitan_naskah_files')->insert($fileNaskah);
                        // $penilaian = $this->alurPenilaian($request->input('add_jalur_buku'), 'create-notif-from-naskah', [
                        //     'id_prodev' => $request->input('add_pic_prodev'),
                        //     'form_id' => $idN
                        // ]);
                        $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        if (Gate::allows('do_update','otorisasi-kalkulasi-order-buku-jasa-cetak')) {
                            $ex = explode(",",$request->input('edit_kalkulasi_harga'));
                            foreach ($ex as $x) {
                                $r[] = preg_replace( '/[^0-9]/', '', $x);
                            }
                            $kalkulasi_harga = json_encode(array_filter($r));
                            if (count($r) != count(json_decode($data->jml_order))) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Total rancangan kalkulasi harga tidak sama dengan jumlah order!'
                                ]);
                            }
                            $editOrAddKalkulasi = [
                                'params' => 'Edit Or Add Kalkulasi Harga',
                                'id' => $request->id,
                                'no_order' => $noOrder,
                                'kalkulasi_harga' => is_null($request->input('edit_kalkulasi_harga')) ? NULL : $kalkulasi_harga,
                            ];
                            event(new JasaCetakEvent($editOrAddKalkulasi));
                            $HistoryOrderBuku = [
                                'params' => 'History Edit Or Add Kalkulasi Harga',
                                'type_history' => 'Update',
                                'order_buku_id' => $request->id,
                                'kalkulasi_harga_his' => $kalkulasi_harga == $data->kalkulasi_harga ? NULL : $data->kalkulasi_harga,
                                'kalkulasi_harga_new' => $kalkulasi_harga == $data->kalkulasi_harga ? NULL : $kalkulasi_harga,
                                'author_id' => auth()->id(),
                                'modified_at' => $tgl
                            ];
                            event(new JasaCetakEvent($HistoryOrderBuku));
                        } else {
                            $exJ = explode(",",$request->input('edit_jml_order'));
                            foreach ($exJ as $xJ) {
                                $res[] = preg_replace( '/[^0-9]/', '', $xJ);
                            }
                            $jml_order = json_encode(array_filter($res));
                            $editOrderBuku = [
                                'params' => 'Edit Order Buku',
                                'id' => $request->id,
                                'no_order' => $noOrder,
                                'judul_buku' => $request->input('edit_judul_buku'),
                                'tgl_order' => Carbon::createFromFormat('d F Y', $request->input('edit_tgl_order'))
                                    ->format('Y-m-d'),
                                'tgl_permintaan_selesai' => Carbon::createFromFormat('d F Y', $request->input('edit_tgl_permintaan_selesai'))
                                    ->format('Y-m-d'),
                                'nama_pemesan' => $request->input('edit_nama_pemesan'),
                                'pengarang' => $request->input('edit_pengarang'),
                                'format' => $request->input('edit_format'),
                                'jml_halaman' => $request->input('edit_jml_halaman'),
                                'kertas_isi' => $request->input('edit_kertas_isi'),
                                'kertas_isi_tinta' => $request->input('edit_kertas_isi_tinta'),
                                'kertas_cover' => $request->input('edit_kertas_cover'),
                                'kertas_cover_tinta' => $request->input('edit_kertas_cover_tinta'),
                                'jml_order' => $jml_order,
                                'status_cetak' => $request->input('edit_status_cetak'),
                                'keterangan' => $request->input('edit_keterangan'),
                            ];
                            event(new JasaCetakEvent($editOrderBuku));
                            // if (array_sum($update) > 0) {

                            // }
                            $HistoryOrderBuku = [
                                'params' => 'History Edit Order Buku',
                                'type_history' => 'Update',
                                'order_buku_id' => $request->id,
                                'judul_buku_his' => $request->input('edit_judul_buku') == $data->judul_buku ? NULL : $data->judul_buku,
                                'judul_buku_new' => $request->input('edit_judul_buku') == $data->judul_buku ? NULL : $request->input('edit_judul_buku'),
                                'tgl_order_his' =>date('Y-m-d', strtotime($request->edit_tgl_order)) == date('Y-m-d', strtotime($data->tgl_order)) ? NULL : date('Y-m-d', strtotime($data->tgl_order)),
                                'tgl_order_new' =>date('Y-m-d', strtotime($request->edit_tgl_order)) == date('Y-m-d', strtotime($data->tgl_order)) ? NULL : Carbon::createFromFormat('d F Y', $request->edit_tgl_order)->format('Y-m-d'),
                                'tgl_permintaan_selesai_his' => date('Y-m-d', strtotime($request->edit_tgl_permintaan_selesai)) == date('Y-m-d', strtotime($data->tgl_permintaan_selesai)) ? NULL : date('Y-m-d', strtotime($data->tgl_permintaan_selesai)),
                                'tgl_permintaan_selesai_new' => date('Y-m-d', strtotime($request->edit_tgl_permintaan_selesai)) == date('Y-m-d', strtotime($data->tgl_permintaan_selesai)) ? NULL : Carbon::createFromFormat('d F Y', $request->edit_tgl_permintaan_selesai)->format('Y-m-d'),
                                'nama_pemesan_his' => $request->input('edit_nama_pemesan') == $data->nama_pemesan ? NULL : $data->nama_pemesan,
                                'nama_pemesan_new' => $request->input('edit_nama_pemesan') == $data->nama_pemesan ? NULL : $request->input('edit_nama_pemesan'),
                                'pengarang_his' => $request->input('edit_pengarang') == $data->pengarang ? NULL : $data->pengarang,
                                'pengarang_new' => $request->input('edit_pengarang') == $data->pengarang ? NULL : $request->input('edit_pengarang'),
                                'format_his' => $request->input('edit_format') == $data->format ? NULL : $data->format,
                                'format_new' => $request->input('edit_format') == $data->format ? NULL : $request->input('edit_format'),
                                'jml_halaman_his' => $request->input('edit_jml_halaman') == $data->jml_halaman ? NULL : $data->jml_halaman,
                                'jml_halaman_new' => $request->input('edit_jml_halaman') == $data->jml_halaman ? NULL : $request->input('edit_jml_halaman'),
                                'kertas_isi_his' => $request->input('edit_kertas_isi') == $data->kertas_isi ? NULL : $data->kertas_isi,
                                'kertas_isi_new' => $request->input('edit_kertas_isi') == $data->kertas_isi ? NULL : $request->input('edit_kertas_isi'),
                                'kertas_isi_tinta_his' => $request->input('edit_kertas_isi_tinta') == $data->kertas_isi_tinta ? NULL : $data->kertas_isi_tinta,
                                'kertas_isi_tinta_new' => $request->input('edit_kertas_isi_tinta') == $data->kertas_isi_tinta ? NULL : $request->input('edit_kertas_isi_tinta'),
                                'kertas_cover_his' => $request->input('edit_kertas_cover') == $data->kertas_cover ? NULL : $data->kertas_cover,
                                'kertas_cover_new' => $request->input('edit_kertas_cover') == $data->kertas_cover ? NULL : $request->input('edit_kertas_cover'),
                                'kertas_cover_tinta_his' => $request->input('edit_kertas_cover_tinta') == $data->kertas_cover_tinta ? NULL : $data->kertas_cover_tinta,
                                'kertas_cover_tinta_new' => $request->input('edit_kertas_cover_tinta') == $data->kertas_cover_tinta ? NULL : $request->input('edit_kertas_cover_tinta'),
                                'jml_order_his' => $jml_order == $data->jml_order ? NULL : $data->jml_order,
                                'jml_order_new' => $jml_order == $data->jml_order ? NULL : $jml_order,
                                'status_cetak_his' => $request->input('edit_status_cetak') == $data->status_cetak ? NULL : $data->status_cetak,
                                'status_cetak_new' => $request->input('edit_status_cetak') == $data->status_cetak ? NULL : $request->input('edit_status_cetak'),
                                'keterangan_his' => $request->input('edit_keterangan') == $data->keterangan ? NULL : $data->keterangan,
                                'keterangan_new' => $request->input('edit_keterangan') == $data->keterangan ? NULL : $request->input('edit_keterangan'),
                                'author_id' => auth()->id(),
                                'modified_at' => $tgl
                            ];
                            event(new JasaCetakEvent($HistoryOrderBuku));
                        }
                        // $addTimeline = [
                        //     'params' => 'Insert Timeline',
                        //     'id' => Uuid::uuid4()->toString(),
                        //     'progress' => ($penilaian['selesai_penilaian'] > 0) ? 'Pelengkapan Data Penulis' : 'Penilaian Naskah',
                        //     'naskah_id' => $idN,
                        //     'tgl_mulai' => $tgl,
                        //     'url_action' => urlencode(URL::to('/penerbitan/naskah/melihat-naskah',$idN)),
                        //     'status' => 'Proses'
                        // ];
                        // event(new TimelineEvent($addTimeline));
                        DB::commit();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Berhasil mengubah order buku!'
                        ]);
                    } catch (\Exception $e) {
                        DB::rollback();
                        return response()->json([
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ]);
                    }
                    break;
                default:
                return abort(500);
                    break;
            }
        }
        $type = DB::select(DB::raw("SHOW COLUMNS FROM jasa_cetak_order_buku WHERE Field = 'status_cetak'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $status_cetak = explode("','", $matches[1]);
        return view('jasa_cetak.order_buku.edit', [
            'status_cetak' => $status_cetak,
            'title' => 'Ubah Order Buku Jasa Cetak'
        ]);
    }
    public function detailOrder(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('GET')) {
                $id = $request->order;
                $no_order = $request->kode;
                $data = DB::table('jasa_cetak_order_buku')
                ->where('id',$id)
                ->where('no_order',$no_order)
                ->first();
                if (is_null($data)) {
                    return abort(404);
                }
            } else {

            }
            switch ($request->request_) {
                case 'getValue':
                    $useData = $data;
                    $data = (object)collect($data)->map(function ($item, $key) use ($useData) {
                        switch ($key) {
                            case 'jml_order':
                                $res = '';
                                $json = json_decode($item);
                                if (is_null($item)) {
                                    $res .= '-';
                                }
                                foreach ($json as $i => $val) {
                                    if (is_null($useData->kalkulasi_harga)) {
                                        $res .= '<span class="bullet"></span>'.$val.' - <span class="text-danger">Kalkulasi harga belum diinput</span><br>';
                                    } else {
                                        foreach (json_decode($useData->kalkulasi_harga) as $k => $val2) {
                                            if ($i != $k) {
                                                continue;
                                            }
                                            $res .= '<span class="bullet"></span>'.$val.' - Rp.'.number_format($val2,0,',','.').'<br>';
                                        }
                                    }
                                }
                                return $res;
                                break;
                            case 'status':
                                $res = '';
                                switch($item){
                                    case 'Antrian':
                                        $res .='<span class="badge" style="background:#34395E;color:white">Antrian</span>';
                                    break;
                                    case 'Pending':
                                        $res .='<span class="badge badge-danger">Pending</span>';
                                    break;

                                    case 'Proses':
                                        $res .='<span class="badge badge-success">Proses</span>';
                                    break;

                                    case 'Selesai':
                                        $res .='<span class="badge badge-light">Selesai</span>';
                                    break;
                                    case 'Revisi':
                                        $res .='<span class="badge badge-info">Revisi</span>';
                                    break;
                                    default:
                                        $res .='<span class="badge badge-primary">Kalkulasi</span>';
                                    break;
                                }
                                return $res;
                                break;
                            case 'tgl_order':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : '-';
                                break;
                            case 'tgl_permintaan_selesai':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : '-';
                                break;
                            case 'created_at':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item)->format('d F Y, H:i:s') : '-';
                                break;
                            case 'created_by':
                                return !is_null($item) ? DB::table('users')->where('id',$item)->first()->nama : '-';
                                break;
                            default:
                                $item;
                                break;
                        }
                        return $item;
                    })->all();
                    if (Gate::allows('do_update','otorisasi-kalkulasi-order-buku-jasa-cetak')) {
                        $gate = TRUE;
                    } else {
                        $gate = FALSE;
                    }
                    return ['data' => $data,'gate'=> $gate];
                    break;
            }
        }
        return view('jasa_cetak.order_buku.detail',[
            'title' => 'Detail Order Buku Jasa Cetak'
        ]);
    }
    protected function panelStatus($id,$no_order,$judul,$status = null)
    {
        $btn = '';
        if (!Gate::allows('do_update','otorisasi-kabag-order-buku-jasa-cetak')) {
            switch ($status) {
                case 'Kalkulasi':
                    $btn .= '<span class="d-block badge badge-primary mr-1 mt-1">' . $status . '</span>';
                    break;
                case 'Antrian':
                    $btn .= '<span class="d-block badge badge-secondary mr-1 mt-1">' . $status . '</span>';
                    break;
                case 'Pending':
                    $btn .= '<span class="d-block badge badge-danger mr-1 mt-1">' . $status . '</span>';
                    break;
                case 'Proses':
                    $btn .= '<span class="d-block badge badge-success mr-1 mt-1">' . $status . '</span>';
                    break;
                case 'Selesai':
                    $btn .= '<span class="d-block badge badge-light mr-1 mt-1">' . $status . '</span>';
                    break;
                case 'Revisi':
                    $btn .= '<span class="d-block badge badge-info mr-1 mt-1">' . $status . '</span>';
                    break;
                default:
                    return abort(410);
                    break;
            }
        } else {
            switch ($status) {
                case 'Kalkulasi':
                    $btn .= '<span class="d-block badge badge-primary mr-1 mt-1">' . $status . '</span>';
                    break;
                case 'Antrian':
                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-order-buku" style="background:#34395E;color:white" data-id="' . $id . '" data-no_order="' . $no_order . '" data-judul="' . $judul . '" data-toggle="modal" data-target="#md_UpdateStatusJasaCetakOrderBuku" title="Update Status">
                        <div>' . $status . '</div></a>';
                    break;
                case 'Pending':
                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-order-buku" data-id="' . $id . '" data-no_order="' . $no_order . '" data-judul="' . $judul . '" data-toggle="modal" data-target="#md_UpdateStatusJasaCetakOrderBuku" title="Update Status">
                        <div>' . $status . '</div></a>';
                    break;
                case 'Proses':
                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-order-buku" data-id="' . $id . '" data-no_order="' . $no_order . '" data-judul="' . $judul . '" data-toggle="modal" data-target="#md_UpdateStatusJasaCetakOrderBuku" title="Update Status">
                        <div>' . $status . '</div></a>';
                    break;
                case 'Selesai':
                    $btn .= '<span class="d-block badge badge-light mr-1 mt-1">' . $status . '</span>';
                    break;
                case 'Revisi':
                    $btn .= '<span class="d-block badge badge-info mr-1 mt-1">' . $status . '</span>';
                    break;
                default:
                    return abort(410);
                    break;
            }
        }
        return $btn;
    }
    protected function buttonAction($id,$no_order,$btn)
    {
        //Button Edit
        if (Gate::allows('do_update','update-order-buku-jasa-cetak')) {
            $btn .= '<a href="' . url('jasa-cetak/order-buku/edit?order=' . $id . '&kode=' . $no_order) . '"
            class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
            <div><i class="fas fa-edit"></i></div></a>';
        }
        //Button Otorisasi Kabag
        if (Gate::allows('do_update','otorisasi-kabag-order-buku-jasa-cetak')) {
            $btn .= '<a href="' . url('jasa-cetak/order-buku/edit?order=' . $id . '&kode=' . $no_order) . '"
            class="d-block btn btn-sm btn-info btn-icon mr-1 mt-1" data-toggle="tooltip" title="Otorisasi Kabag">
            <div><i class="fas fa-tasks"></i></div></a>';
        }
        return $btn;
    }
    public static function generateId()
    {
        $last = DB::table('jasa_cetak_order_buku')
            ->select('no_order')
            ->where('created_at', '>', strtotime(date('Y-m-d') . '00:00:01'))
            ->orderBy('created_at', 'desc')
            ->first();

        if (is_null($last)) {
            $id = substr(date('Y'),2) . '.001';
        } else {
            $id = (int)substr($last->no_order, -3);
            $id = substr(date('Y'),2) .'.'. sprintf('%03s', $id + 1);
        }
        return $id;
    }
    public function ajaxRequest(Request $request)
    {
        try {
            switch ($request->cat) {
                case 'lihat-history':
                    return $this->lihatHistory($request);
                    break;
                case 'catch-value-status':
                    return $this->getValueStatus($request);
                    break;
                case 'update-status-progress':
                    return $this->updateStatusProgress($request);
                    break;
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan!'
                    ]);
                    break;
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function lihatHistory($request)
    {
        $html = '';
        $id = $request->id;
        $data = DB::table('jasa_cetak_order_buku_history as h')
            ->join('users as u', 'h.author_id', '=', 'u.id')
            ->where('h.order_buku_id', $id)
            ->select('h.*', 'u.nama')
            ->orderBy('h.id', 'desc')
            ->paginate(2);
        foreach ($data as $d) {
            $d = (object)collect($d)->map(function ($item, $key) {
                switch ($key) {
                    case 'tgl_order_his':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : NULL;
                        break;
                    case 'tgl_order_new':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : NULL;
                        break;
                    case 'tgl_permintaan_selesai_his':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : NULL;
                        break;
                    case 'tgl_permintaan_selesai_new':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : NULL;
                        break;
                    case 'jml_order_his':
                        if (!is_null($item)) {
                            $jmlOrder = '';
                            foreach (json_decode($item, true) as $his) {
                                $jmlOrder .= '<b class="text-dark">' . $his . '</b>, ';
                            }
                        } else {
                            $jmlOrder = NULL;
                        }
                        return $jmlOrder;
                        break;
                    case 'jml_order_new':
                        if (!is_null($item)) {
                            $jmlOrder = '';
                            foreach (json_decode($item, true) as $new) {
                                $jmlOrder .= '<span class="bullet"></span>' .$new;
                            }
                        } else {
                            $jmlOrder = NULL;
                        }
                        return $jmlOrder;
                        break;
                    case 'kalkulasi_harga_his':
                        if (!is_null($item)) {
                            $kalkulasiHarga = '';
                            foreach (json_decode($item, true) as $his) {
                                $kalkulasiHarga .= '<b class="text-dark">Rp.' . number_format($his,0,',','.') . '</b>, ';
                            }
                        } else {
                            $kalkulasiHarga = NULL;
                        }
                        return $kalkulasiHarga;
                        break;
                    case 'kalkulasi_harga_new':
                        if (!is_null($item)) {
                            $kalkulasiHarga = '';
                            foreach (json_decode($item, true) as $new) {
                                $kalkulasiHarga .= '<span class="bullet"></span>Rp.' .number_format($new,0,',','.');
                            }
                        } else {
                            $kalkulasiHarga = NULL;
                        }
                        return $kalkulasiHarga;
                        break;
                    case 'modified_at':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($item)->translatedFormat('l d M Y, H:i').')' : NULL;
                        break;
                    default:
                        $item;
                        break;
                }
                return $item;
            })->all();
            switch ($d->type_history) {
                case 'Update':
                    $html .= '<span class="ticket-item" id="newAppend">';

                    if (!is_null($d->jalur_proses_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Jalur proses <b class="text-dark">' .
                            $d->jalur_proses_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->jalur_proses_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->jalur_proses_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Jalur proses <b class="text-dark">' .
                            $d->jalur_proses_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->nama_pemesan_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Nama pemesan <b class="text-dark">' .
                            $d->nama_pemesan_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->nama_pemesan_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->nama_pemesan_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Nama pemesan <b class="text-dark">' .
                            $d->nama_pemesan_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->judul_buku_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Judul buku <b class="text-dark">' .
                            $d->judul_buku_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->judul_buku_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->judul_buku_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Judul buku <b class="text-dark">' .
                            $d->judul_buku_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->pengarang_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Pengarang <b class="text-dark">' .
                            $d->pengarang_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->pengarang_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->pengarang_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Pengarang <b class="text-dark">' .
                            $d->pengarang_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->format_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Format <b class="text-dark">' .
                            $d->format_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->format_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->format_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Format <b class="text-dark">' .
                            $d->format_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->jml_halaman_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Jumlah halaman <b class="text-dark">' .
                            $d->jml_halaman_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->jml_halaman_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->jml_halaman_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Jumlah halaman <b class="text-dark">' .
                            $d->jml_halaman_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->kertas_isi_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kertas isi <b class="text-dark">' .
                            $d->kertas_isi_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->kertas_isi_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->kertas_isi_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kertas isi <b class="text-dark">' .
                            $d->kertas_isi_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->kertas_isi_tinta_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kertas isi tinta <b class="text-dark">' .
                            $d->kertas_isi_tinta_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->kertas_isi_tinta_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->kertas_isi_tinta_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kertas isi tinta <b class="text-dark">' .
                            $d->kertas_isi_tinta_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->kertas_cover_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kertas cover <b class="text-dark">' .
                            $d->kertas_cover_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->kertas_cover_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->kertas_cover_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kertas cover <b class="text-dark">' .
                            $d->kertas_cover_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->kertas_cover_tinta_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kertas cover tinta <b class="text-dark">' .
                            $d->kertas_cover_tinta_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->kertas_cover_tinta_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->kertas_cover_tinta_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kertas cover tinta <b class="text-dark">' .
                            $d->kertas_cover_tinta_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->status_cetak_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Status cetak <b class="text-dark">' .
                            $d->status_cetak_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->status_cetak_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->status_cetak_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Status cetak <b class="text-dark">' .
                            $d->status_cetak_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->jml_order_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Jumlah order ' .
                            $d->jml_order_his .
                            'diubah menjadi <b class="text-dark">' .
                            $d->jml_order_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->jml_order_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Jumlah order <b class="text-dark">' .
                            $d->jml_order_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->kalkulasi_harga_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kalkulasi harga ' .
                            $d->kalkulasi_harga_his .
                            'diubah menjadi <b class="text-dark">' .
                            $d->kalkulasi_harga_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->kalkulasi_harga_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kalkulasi harga <b class="text-dark">' .
                            $d->kalkulasi_harga_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->tgl_order_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Tanggal order <b class="text-dark">' .
                            $d->tgl_order_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->tgl_order_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->tgl_order_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Tanggal order <b class="text-dark">' .
                            $d->tgl_order_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->tgl_permintaan_selesai_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Tanggal permintaan selesai <b class="text-dark">' .
                            $d->tgl_permintaan_selesai_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->tgl_permintaan_selesai_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->tgl_permintaan_selesai_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Tanggal permintaan selesai <b class="text-dark">' .
                            $d->tgl_permintaan_selesai_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    if (!is_null($d->keterangan_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Keterangan <b class="text-dark">' .
                            $d->keterangan_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->keterangan_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->keterangan_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Keterangan <b class="text-dark">' .
                            $d->keterangan_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    $html .=
                        '<div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' .url('/manajemen-web/user/' . $d->author_id) .'">' .$d->nama .'</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' .$d->modified_at.'</div>
                        </div>
                        </span>';
                    break;
                case 'Status':
                    $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status jasa cetak order buku <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' .$d->modified_at.'</div>
                        </div>
                        </span>';
                    break;
            }
        }
        return $html;
    }
    protected function getValueStatus($request)
    {
        try {
            $id = $request->id;
            $no_order = $request->no_order;
            $data = DB::table('jasa_cetak_order_buku')
            ->where('id',$id)
            ->where('no_order',$no_order)
            ->first();
            if (is_null($data)) {
                return abort(404);
            }
            return ['data' => $data];
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function updateStatusProgress($request)
    {
        try {
            $id = $request->id;
            DB::beginTransaction();
            $data = DB::table('jasa_cetak_order_buku')
                ->where('id', $id)
                ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ], 404);
            }
            if ($data->status == $request->status) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pilih status yang berbeda dengan status saat ini!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $update = [
                'params' => 'Update Status Order Buku',
                'id' => $data->id,
                'tgl_selesai_order' => $request->status == 'Selesai' ? $tgl : NULL,
                'status' => $request->status,
            ];
            $insert = [
                'params' => 'Insert History Status Order Buku',
                'order_buku_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            if ($data->proses == '1') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak bisa mengubah status, karena sedang proses kerja.'
                ]);
            }
            if ($request->status == 'Selesai') {
                if (is_null($data->jalur_proses)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jalur proses belum ditentukan!'
                    ]);
                } else {
                    switch ($data->jalur_proses) {
                        case 'Jalur Reguler':
                            if (is_null($data->selesai_desain)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses desain belum selesai dikerjakan'
                                ]);
                            }
                            if (is_null($data->selesai_setter)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses setter belum selesai dikerjakan'
                                ]);
                            }
                            if (is_null($data->selesai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses proof belum selesai dikerjakan'
                                ]);
                            }
                            if (is_null($data->selesai_pracetak)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses pracetak belum selesai dikerjakan'
                                ]);
                            }
                            break;
                        case 'Jalur Pendek':
                            if (is_null($data->selesai_pracetak)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses pracetak belum selesai dikerjakan'
                                ]);
                            }
                            break;
                    }
                }
                event(new JasaCetakEvent($update));
                event(new JasaCetakEvent($insert));
                $msg = 'Order Buku selesai, silahkan selesaikan proses produksi..';
            } else {
                event(new JasaCetakEvent($update));
                event(new JasaCetakEvent($insert));
                $msg = 'Status progress order buku berhasil diupdate';
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $msg
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
