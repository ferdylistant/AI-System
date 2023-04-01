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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('jasa_cetak_order_buku')
                ->orderBy('tgl_order', 'ASC')
                ->get();
            if ($request->has('count_data')) {
                return $data->count();
            } else {
                return DataTables::of($data)
                ->addColumn('jalur_proses', function ($data) {
                    $label = 'Belum ditentukan kabag';
                    if (Gate::allows('do_update', 'otorisasi-kabag-order-buku-jasa-cetak')) {
                        $label = 'Belum anda tentukan';
                    }
                    return is_null($data->jalur_proses) ? '<span class="text-danger">' . $label . '</span>' : $data->jalur_proses;
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

                    $btn = $this->panelStatus($data->id, $data->no_order, $data->judul_buku, $data->status);

                    return $btn;
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('jasa-cetak/order-buku/detail?order=' . $data->id . '&kode=' . $data->no_order) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-role="page" data-ajax="false" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                    $btn = $this->buttonAction($data->id, $data->no_order, $btn);

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
        }
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM jasa_cetak_order_buku WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        $statusAction = Arr::except($statusProgress, ['0', '5']);
        //Jalur Proses
        $type = DB::select(DB::raw("SHOW COLUMNS FROM jasa_cetak_order_buku WHERE Field = 'jalur_proses'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $jalurProses = explode("','", $matches[1]);
        return view('jasa_cetak.order_buku.index', [
            'title' => 'Jasa Cetak Order Buku',
            'status_progress' => $statusProgress,
            'status_action' => $statusAction,
            'jalur_proses' => $jalurProses,
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
                    $jmlOrder = explode(",", $request->add_jml_order);
                    $last = DB::table('jasa_cetak_order_buku')
                        ->select('no_order')
                        ->where('no_order', $noOrder)
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
                    ->where('id', $id)
                    ->where('no_order', $no_order)
                    ->first();
                if (is_null($data)) {
                    return abort(404);
                }
            } else {
                $noOrder = $request->input('edit_no_order');
                $data = DB::table('jasa_cetak_order_buku')
                    ->where('id', $request->id)
                    ->where('no_order', $noOrder)
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
                                $implode = implode(",", $json);
                                return !is_null($item) ? $implode : NULL;
                                break;
                            case 'kalkulasi_harga':
                                if (!is_null($item)) {
                                    $json = json_decode($item);
                                    $implode = implode(",", $json);
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
                                return !is_null($item) ? DB::table('users')->where('id', $item)->first()->nama : NULL;
                                break;
                            default:
                                $item;
                                break;
                        }
                        return $item;
                    })->all();
                    if (Gate::allows('do_update', 'otorisasi-kalkulasi-order-buku-jasa-cetak')) {
                        $gate = TRUE;
                    } else {
                        $gate = FALSE;
                    }
                    if ($data->status == "Tidak Deal") {
                        $noDeal = TRUE;
                    } else {
                        $noDeal = FALSE;
                    }
                    return ['data' => $data, 'gate' => $gate, 'nodeal' => $noDeal];
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
                        if (Gate::allows('do_update', 'otorisasi-kalkulasi-order-buku-jasa-cetak')) {
                            $ex = explode(",", $request->input('edit_kalkulasi_harga'));
                            foreach ($ex as $x) {
                                $r[] = preg_replace('/[^0-9]/', '', $x);
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
                            $exJ = explode(",", $request->input('edit_jml_order'));
                            foreach ($exJ as $xJ) {
                                $res[] = preg_replace('/[^0-9]/', '', $xJ);
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
                                'tgl_order_his' => date('Y-m-d', strtotime($request->edit_tgl_order)) == date('Y-m-d', strtotime($data->tgl_order)) ? NULL : date('Y-m-d', strtotime($data->tgl_order)),
                                'tgl_order_new' => date('Y-m-d', strtotime($request->edit_tgl_order)) == date('Y-m-d', strtotime($data->tgl_order)) ? NULL : Carbon::createFromFormat('d F Y', $request->edit_tgl_order)->format('Y-m-d'),
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
                    ->where('id', $id)
                    ->where('no_order', $no_order)
                    ->first();
                if (is_null($data)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data order buku tidak ditemukan!'
                    ]);
                }
            } else {
                $id = $request->id;
                $no_order = $request->no_order;
                $data = DB::table('jasa_cetak_order_buku')
                    ->where('no_order', $no_order)
                    ->where('id', $id);
                if (is_null($data->first())) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data order buku tidak ditemukan!'
                    ]);
                }
            }
            switch ($request->request_) {
                case 'getValue':
                    $useData = $data;
                    $data = (object)collect($data)->map(function ($item, $key) use ($useData) {
                        switch ($key) {
                            case 'id':
                                $html = '';
                                if ($useData->status == 'Kalkulasi') {
                                    if (Gate::allows('do_create', 'create-order-buku-jasa-cetak') && !Gate::allows('do_update', 'otorisasi-kalkulasi-order-buku-jasa-cetak')) {
                                        if (is_null($useData->approve) && is_null($useData->decline)) {
                                            $html .= '<div class="col-auto mr-auto">
                                            <div class="mb-4">
                                            <button type="submit" class="btn btn-success" id="btn-approve" data-ajax="false" data-decline_revisi="deal_decline" data-id="' . $item . '" data-no_order="' . $useData->no_order . '" data-judul="' . $useData->judul_buku . '">
                                            <i class="fas fa-check"></i>&nbsp;Deal</button>
                                            <button type="button" class="btn btn-danger" id="btn-decline" data-ajax="false" data-id="' . $item . '" data-no_order="' . $useData->no_order . '">
                                            <i class="fas fa-times"></i>&nbsp;Tidak Deal</button>
                                            </div></div>';
                                        } elseif (!is_null($useData->decline)) {
                                            $html .= '<div class="col-auto mr-auto">
                                            <div class="mb-4">
                                            <a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon" id="btn-decline" data-ajax="false" data-id="' . $useData->id . '" data-no_order="' . $useData->no_order . '" title="Keterangan Tidak Deal">
                                            <i class="fas fa-eye"></i>&nbsp;Keterangan Tidak Deal</a></div></div>';
                                        }
                                    }
                                }
                                return $html;
                                break;
                            case 'proses':
                                $html = '';
                                $author_db = NULL;
                                $label_db = NULL;
                                if ($useData->status == 'Proses') {
                                    switch ($useData->jalur_proses) {
                                        case 'Jalur Reguler':
                                            if ((is_null($useData->mulai_desain) || !is_null($useData->mulai_desain)) && is_null($useData->selesai_desain)) {
                                                $author_db = 'desainer';
                                                $label_db = 'desain_setter';
                                            } elseif (!is_null($useData->mulai_proof) && is_null($useData->selesai_proof)) {
                                                $author_db = 'proof';
                                                $label_db = 'proof';
                                            } elseif (!is_null($useData->selesai_proof) && is_null($useData->selesai_koreksi)) {
                                                $author_db = 'korektor';
                                                $label_db = 'korektor';
                                            } elseif ((!is_null($useData->selesai_koreksi)) && (is_null($useData->selesai_pracetak_ctcp))) {
                                                $author_db = 'pracetak';
                                                $label_db = 'pracetak';
                                            } elseif (is_null($useData->selesai_koreksi) && !is_null($useData->selesai_pracetak_ctcp)) {
                                                $author_db = 'korektor';
                                                $label_db = 'korektor';
                                            } elseif ((!is_null($useData->selesai_koreksi)) && (!is_null($useData->selesai_pracetak_ctcp)) && (is_null($useData->selesai_pracetak_prod))) {
                                                $author_db = 'pracetak';
                                                $label_db = 'pracetak';
                                            } else {
                                                $author_db = NULL;
                                                $label_db = NULL;
                                            }
                                            break;
                                        case 'Jalur Pendek':
                                            if (is_null($useData->mulai_proof) && is_null($useData->selesai_koreksi) && (is_null($useData->mulai_pracetak_ctcp))) {
                                                $author_db = 'korektor';
                                                $label_db = 'korektor';
                                            } elseif ((!is_null($useData->selesai_koreksi)) && (is_null($useData->selesai_pracetak_ctcp))) {
                                                $author_db = 'pracetak';
                                                $label_db = 'pracetak';
                                            } elseif (is_null($useData->selesai_koreksi) && !is_null($useData->selesai_pracetak_ctcp)) {
                                                $author_db = 'korektor';
                                                $label_db = 'korektor';
                                            } elseif ((!is_null($useData->selesai_koreksi)) && (!is_null($useData->selesai_pracetak_ctcp)) && (is_null($useData->selesai_pracetak_prod))) {
                                                $author_db = 'pracetak';
                                                $label_db = 'pracetak';
                                            } else {
                                                $author_db = NULL;
                                                $label_db = NULL;
                                            }
                                            break;
                                        default:
                                            $author_db = NULL;
                                            $label_db = NULL;
                                            break;
                                    }
                                    $doneProses = DB::table('jasa_cetak_order_buku_selesai')
                                        ->where('type', ucfirst($label_db))
                                        ->where('order_buku_id', $useData->id)
                                        ->where('users_id', auth()->user()->id)
                                        ->orderBy('id', 'desc')
                                        ->first();
                                    switch ($label_db) {
                                        case 'desain_setter':
                                            if (is_null($doneProses)) {
                                                $done_proses = FALSE;
                                            } else {
                                                $done_proses = is_null($useData->selesai_desain) ? FALSE : TRUE;
                                            }
                                            break;
                                        case 'korektor':
                                            if (is_null($doneProses)) {
                                                $done_proses = FALSE;
                                            } else {
                                                $done_proses = is_null($useData->selesai_koreksi) ? FALSE : TRUE;
                                                // $doneSelf = DB::table('jasa_cetak_order_buku_selesai')
                                                //     ->where('type', ucfirst($label_db))
                                                //     ->where('tahap', $doneProses->tahap + 1)
                                                //     ->where('order_buku_id', $useData->id)
                                                //     ->get();
                                                // if (!$doneSelf->isEmpty()) {
                                                //     foreach ($doneSelf as $d) {
                                                //         $userId[] = $d->users_id;
                                                //     }
                                                //     if (in_array(auth()->user()->id, $userId)) {
                                                //         $done_proses = TRUE;
                                                //     } else {
                                                //         $done_proses = FALSE;
                                                //     }
                                                // } else {
                                                //     $done_proses = FALSE;
                                                // }
                                            }
                                            break;
                                    }
                                } else {
                                    $done_proses = TRUE;
                                }
                                if ($useData->proses == '1') {
                                    if (Gate::allows('do_update', 'otorisasi-' . $author_db . '-order-buku-jasa-cetak')) {
                                        if ($label_db != 'proof' || $label_db != NULL) {
                                            if (!is_null($useData->$label_db)) {
                                                foreach (json_decode($useData->$label_db) as $edt) {
                                                    if (auth()->id() == $edt) {
                                                        if ($done_proses == FALSE) {
                                                            $html .= '<div class="col-auto">
                                                                <div class="mb-4">
                                                                    <button type="submit" class="btn btn-success" id="btn-done-order-buku" data-id="' . $useData->id . '" data-no_order="' . $useData->no_order . '" data-author="' . $label_db . '">
                                                                        <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                                                </div>
                                                            </div>';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } elseif (Gate::allows('do_approval', 'proof-order-buku-jasa-cetak')) {
                                        if (!is_null($useData->mulai_proof) && is_null($useData->selesai_proof)) {
                                            $html .= '<div class="col-auto mr-auto">
                                                <div class="mb-4">
                                                <button type="submit" class="btn btn-success" id="btn-approve" data-ajax="false" data-decline_revisi="approve_revisi" data-id="' . $useData->id . '" data-no_order="' . $useData->no_order . '" data-judul="' . $useData->judul_buku . '">
                                                <i class="fas fa-check"></i>&nbsp;Proof</button>
                                                    <button type="button" class="btn btn-danger" id="btn-decline" data-id="' . $useData->id . '"
                                                        data-no_order="' . $useData->no_order . '" data-ajax="false"><i class="fas fa-tools"></i>&nbsp;Revisi</button>
                                                </div>
                                            </div>';
                                        }
                                    }
                                }
                                return $html;
                                break;
                            case 'jml_order':
                                $res = '';
                                $json = json_decode($item);
                                if (is_null($item)) {
                                    $res .= '-';
                                }
                                foreach ($json as $i => $val) {
                                    if (is_null($useData->kalkulasi_harga)) {
                                        $res .= '<span class="bullet"></span>' . $val . ' - <span class="text-danger">Kalkulasi harga belum diinput</span><br>';
                                    } else {
                                        foreach (json_decode($useData->kalkulasi_harga) as $k => $val2) {
                                            if ($i != $k) {
                                                continue;
                                            }
                                            $hargaFinal = $val . ' Eks - Rp.' . number_format($val2, 0, ',', '.');
                                            if (Gate::allows('do_create', 'create-order-buku-jasa-cetak') && !Gate::allows('do_update', 'otorisasi-kalkulasi-order-buku-jasa-cetak')) {
                                                $labelDeal = $useData->status == "Tidak Deal" ? 'readonly disabled' : '';
                                                $res .= '<div class="form-check">
                                                <input class="form-check-input" type="radio" name="harga_final_radio" data-no_order="' . $useData->no_order . '" data-id="' . $useData->id . '" value="' . $val . ' Eks - Rp.' . number_format($val2, 0, ',', '.') . '" data-ajax="false" id="hargaFinalRadio' . $k . '"
                                                ' . (is_null($useData->harga_final) ? '' : ($useData->harga_final == $hargaFinal ? 'checked' : '')) . ' ' . $labelDeal . '>
                                                <label class="form-check-label" for="hargaFinalRadio' . $k . '">' . $val . ' Eks - Rp.' . number_format($val2, 0, ',', '.') . '
                                                </label></div>';
                                            } else {
                                                $icon = $useData->harga_final == $hargaFinal ? '<i class="fas fa-check text-success"></i>' : '';
                                                $text = $useData->harga_final == $hargaFinal ? 'text-success' : '';
                                                $res .= '<span class="bullet ' . $text . '"></span>' . $val . ' Eks - Rp.' . number_format($val2, 0, ',', '.') . ' ' . $icon . '<br>';
                                            }
                                        }
                                    }
                                }
                                return $res;
                                break;
                            case 'status':
                                $res = '';
                                switch ($item) {
                                    case 'Antrian':
                                        $res .= '<span class="badge" style="background:#34395E;color:white">Antrian</span>';
                                        break;
                                    case 'Pending':
                                        $res .= '<span class="badge badge-warning">Pending</span>';
                                        break;

                                    case 'Proses':
                                        $res .= '<span class="badge badge-success">Proses</span>';
                                        break;

                                    case 'Selesai':
                                        $res .= '<span class="badge badge-light">Selesai</span>';
                                        break;
                                    case 'Revisi':
                                        $res .= '<span class="badge badge-info">Revisi</span>';
                                        break;
                                    case 'Tidak Deal':
                                        $res .= '<span class="badge badge-danger">Tidak Deal</span>';
                                        break;
                                    default:
                                        $res .= '<span class="badge badge-primary">Kalkulasi</span>';
                                        break;
                                }
                                return $res;
                                break;
                            case 'harga_final':
                                return !is_null($item) ? $item : NULL;
                                break;
                            case 'tgl_order':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->translatedFormat('d F Y') : '-';
                                break;
                            case 'tgl_permintaan_selesai':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->translatedFormat('d F Y') : '-';
                                break;
                            case 'created_at':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item)->translatedFormat('d F Y, H:i:s') : '-';
                                break;
                            case 'created_by':
                                return !is_null($item) ? DB::table('users')->where('id', $item)->first()->nama : '-';
                                break;
                            default:
                                return $item;
                                break;
                        }
                        return $item;
                    })->all();
                    if (Gate::allows('do_create', 'create-order-buku-jasa-cetak') && !Gate::allows('do_update', 'otorisasi-kalkulasi-order-buku-jasa-cetak')) {
                        $cs = TRUE;
                    } else {
                        $cs = FALSE;
                    }
                    return ['data' => $data, 'cs' => $cs];
                    break;
                case 'pilih-harga':
                    return $this->pilihHarga($request, $data);
                    break;
                case 'approval-order':
                    return $this->approvalOrder($request, $data);
                    break;
                case 'modal-decline-revisi':
                    return $this->showModal($data);
                    break;
                case 'done-work':
                    switch ($request->author) {
                        case 'desain_setter':
                            return $this->doneWorkDesainSetter($request->author, $data);
                            break;
                        case 'korektor':
                            return $this->doneWorkKorektor($request->author, $data);
                            break;
                        case 'pracetak':
                            return $this->doneWorkPracetak($request->author, $data);
                            break;
                        default:
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Terjadi kesalahan!'
                            ]);
                            break;
                    }
                    break;
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan! Silahkan hubungi tim pengembang AI System.'
                    ]);
                    break;
            }
        }
        return view('jasa_cetak.order_buku.detail', [
            'title' => 'Detail Order Buku Jasa Cetak'
        ]);
    }
    public function otorisasiKabag(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('GET')) {
                $id = $request->order;
                $no_order = $request->kode;
                $data = DB::table('jasa_cetak_order_buku')
                    ->where('id', $id)
                    ->where('no_order', $no_order)
                    ->first();
            } else {
                $noOrder = $request->input('no_order');
                $data = DB::table('jasa_cetak_order_buku')
                    ->where('id', $request->id)
                    ->where('no_order', $noOrder)
                    ->first();
            }
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data order buku tidak ditemukan!'
                ]);
            }
            switch ($request->request_) {
                case 'getValue':
                    $useData = $data;
                    $data = (object)collect($data)->map(function ($item, $key) use ($useData) {
                        switch ($key) {
                            case 'id':
                                $html = '';
                                if ($useData->status == 'Revisi') {
                                    $last =  DB::table('jasa_cetak_order_buku_selesai')
                                        ->where('type', 'Proof')
                                        ->where('section', 'Proof')
                                        ->where('order_buku_id', $item)
                                        ->orderBy('id', 'desc')
                                        ->first();
                                    $html .= '<div class="row card-header">
                                    <div class="col-lg-12"><button type="button" class="btn btn-success form-control" id="done-revision"
                                    data-id="' . $item . '" data-judul="' . $useData->judul_buku . '"
                                    data-no_order="' . $useData->no_order . '">
                                    <i class="fas fa-check"></i>&nbsp;Selesai Revisi</button>
                                    </div>
                                <div class="col-lg-12 mt-3">
                                <h6 class="text-danger"><i class="fas fa-exclamation-circle"></i>&nbsp;Keterangan Revisi:</h6>
                                <p><span class="bullet"></span>' . $last->ket_revisi . '</p>
                                </div>
                                </div>';
                                }
                                return ['html' => $html, 'id' => $item];
                                break;
                            case 'jalur_proses':
                                $html = '';
                                if (is_null($item)) {
                                    $item = '<span class="text-danger">Jalur belum dipilih</span>';
                                    $disabled = true;
                                } elseif ($item == 'Jalur Reguler') {
                                    $kabagFirst = '';
                                    $desainProg = '';
                                    $kabagSecond = '';
                                    $proofProg = '';
                                    $kabagThird = '';
                                    $koreksiFirst = '';
                                    $pracetakCTCP = '';
                                    $koreksiSecond = '';
                                    $kabagFourth = '';
                                    $kabagFifth = '';
                                    $kabagSixth = '';
                                    $pracetakProd = '';
                                    $textKabagSecond = '';
                                    if ($useData->status == 'Revisi') {
                                        $textKabagSecond = 'Otorisasi klik penyelesaian revisi';
                                    } else {
                                        $textKabagSecond = 'Delegasi ke CS untuk Proofing';
                                    }
                                    if (is_null($useData->mulai_desain) && is_null($useData->selesai_desain)) {
                                        $kabagFirst = 'text-danger';
                                    }
                                    if (!is_null($useData->mulai_desain) && is_null($useData->selesai_desain)) {
                                        $desainProg = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_desain) && is_null($useData->mulai_proof)) {
                                        $kabagSecond = 'text-danger';
                                    }
                                    if (!is_null($useData->mulai_proof) && is_null($useData->selesai_proof)) {
                                        $proofProg = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_proof) && is_null($useData->mulai_koreksi)) {
                                        $kabagThird = 'text-danger';
                                    }
                                    if (!is_null($useData->mulai_koreksi) && is_null($useData->selesai_koreksi)) {
                                        $koreksiFirst = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_koreksi) && is_null($useData->mulai_pracetak_ctcp)) {
                                        $kabagFourth = 'text-danger';
                                    }
                                    if ((!is_null($useData->mulai_pracetak_ctcp) && is_null($useData->selesai_pracetak_ctcp)) && !is_null($useData->mulai_koreksi)) {
                                        $pracetakCTCP = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_pracetak_ctcp) && is_null($useData->mulai_koreksi) && is_null($useData->selesai_koreksi)) {
                                        $kabagFifth = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_pracetak_ctcp) && !is_null($useData->mulai_koreksi)) {
                                        $koreksiSecond = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_pracetak_ctcp) && is_null($useData->mulai_pracetak_prod)) {
                                        $kabagSixth = 'text-danger';
                                    }
                                    if (!is_null($useData->mulai_pracetak_prod) && is_null($useData->selesai_pracetak_prod)) {
                                        $pracetakProd = 'text-danger';
                                    }

                                    $html .= '<section class="time-line-box">
                                            <div class="swiper-container text-center">
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide ' . $kabagFirst . '">
                                                    <div class="timestamp"><span class="date">Proses delegasi ke tahap desain</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $desainProg . '">
                                                    <div class="timestamp"><span class="date">Pengerjaan desain/setting</span></div>
                                                    <div class="status"><span>Desain</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $kabagSecond . '">
                                                    <div class="timestamp"><span class="date">' . $textKabagSecond . '</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $proofProg . '">
                                                    <div class="timestamp"><span class="date">Proof Penulis</span></div>
                                                    <div class="status"><span>CS</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $kabagThird . '">
                                                    <div class="timestamp"><span class="date">Delegasi ke korektor</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $koreksiFirst . '">
                                                    <div class="timestamp"><span class="date">Koreksi hasil fix proofing penulis</span></div>
                                                    <div class="status"><span>Korektor</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $kabagFourth . '">
                                                    <div class="timestamp"><span class="date">Delegasi ke Pracetak CTCP</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $pracetakCTCP . '">
                                                    <div class="timestamp"><span class="date">Pracetak CTCP</span></div>
                                                    <div class="status"><span>Admin Pracetak</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $kabagFifth . '">
                                                    <div class="timestamp"><span class="date">Delegasi ke korektor (CTCP)</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $koreksiSecond . '">
                                                    <div class="timestamp"><span class="date">Koreksi hasil CTCP</span></div>
                                                    <div class="status"><span>Korektor</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $kabagSixth . '">
                                                    <div class="timestamp"><span class="date">Delegasi ke admin pracetak</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $pracetakProd . '">
                                                    <div class="timestamp"><span class="date">Mendaftarkan ke produksi</span></div>
                                                    <div class="status"><span>Admin Pracetak</span></div>
                                                    </div>
                                                </div>
                                                <div class="swiper-pagination"></div>
                                            </div>
                                            </section>';
                                    $disabled = false;
                                } elseif ($item == 'Jalur Pendek') {
                                    $kabagFirst = '';
                                    $koreksiFirst = '';
                                    $pracetakCTCP = '';
                                    $kabagSecond = '';
                                    $koreksiSecond = '';
                                    $kabagThird = '';
                                    $pracetakProd = '';
                                    $kabagFourth = '';
                                    if (is_null($useData->mulai_koreksi) && is_null($useData->selesai_koreksi)) {
                                        $kabagFirst = 'text-danger';
                                    }
                                    if (!is_null($useData->mulai_koreksi) && is_null($useData->selesai_koreksi)) {
                                        $koreksiFirst = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_koreksi) && is_null($useData->mulai_pracetak_ctcp)) {
                                        $kabagSecond = 'text-danger';
                                    }
                                    if ((!is_null($useData->mulai_pracetak_ctcp) && is_null($useData->selesai_pracetak_ctcp)) && !is_null($useData->mulai_koreksi)) {
                                        $pracetakCTCP = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_pracetak_ctcp) && is_null($useData->mulai_koreksi) && is_null($useData->selesai_koreksi)) {
                                        $kabagThird = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_pracetak_ctcp) && !is_null($useData->mulai_koreksi)) {
                                        $koreksiSecond = 'text-danger';
                                    }
                                    if (!is_null($useData->selesai_pracetak_ctcp) && is_null($useData->mulai_pracetak_prod)) {
                                        $kabagFourth = 'text-danger';
                                    }
                                    if (!is_null($useData->mulai_pracetak_prod) && is_null($useData->selesai_pracetak_prod)) {
                                        $pracetakProd = 'text-danger';
                                    }
                                    $html .= '<section class="time-line-box">
                                            <div class="swiper-container text-center">
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide ' . $kabagFirst . '">
                                                    <div class="timestamp"><span class="date">Delegasi ke korektor</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $koreksiFirst . '">
                                                    <div class="timestamp"><span class="date">Koreksi bahan jadi</span></div>
                                                    <div class="status"><span>Korektor</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $kabagSecond . '">
                                                    <div class="timestamp"><span class="date">Delegasi ke Pracetak CTCP</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $pracetakCTCP . '">
                                                    <div class="timestamp"><span class="date">Pracetak CTCP</span></div>
                                                    <div class="status"><span>Admin Pracetak</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $kabagThird . '">
                                                    <div class="timestamp"><span class="date">Delegasi ke korektor (CTCP)</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $koreksiSecond . '">
                                                    <div class="timestamp"><span class="date">Koreksi hasil CTCP</span></div>
                                                    <div class="status"><span>Korektor</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $kabagFourth . '">
                                                    <div class="timestamp"><span class="date">Delegasi ke admin pracetak</span></div>
                                                    <div class="status"><span>Kabag</span></div>
                                                    </div>
                                                    <div class="swiper-slide ' . $pracetakProd . '">
                                                    <div class="timestamp"><span class="date">Mendaftarkan ke produksi</span></div>
                                                    <div class="status"><span>Admin Pracetak</span></div>
                                                    </div>
                                                </div>
                                                <div class="swiper-pagination"></div>
                                            </div>
                                            </section>';
                                    $disabled = true;
                                } else {
                                    $disabled = true;
                                    $item = $item;
                                }
                                return ['html' => $html, 'item' => $item, 'disabled' => $disabled];
                                break;
                            case 'proses':
                                $disable = false;
                                $label = $item == 1 ? 'Stop' : 'Mulai';
                                $checked = $useData->proses == '1' ? true : false;
                                if ($useData->status == 'Proses') {
                                    switch ($useData->jalur_proses) {
                                        case 'Jalur Reguler':
                                            if ((is_null($useData->mulai_desain) || !is_null($useData->mulai_desain)) && is_null($useData->selesai_desain)) {
                                                $lbl = $label . ' proses desain/setting';
                                                $lbl_db = 'desain';
                                                $author_db = 'desain_setter';
                                            } elseif (!is_null($useData->selesai_desain) && is_null($useData->mulai_proof)) {
                                                $lbl = $label . ' proses proof CS ke penulis';
                                                $lbl_db = 'proof';
                                                $author_db = 'proof';
                                            } elseif (!is_null($useData->mulai_proof) && is_null($useData->selesai_proof)) {
                                                $disable = true;
                                                $lbl = 'Sedang proses proof CS ke penulis';
                                                $lbl_db = 'proof';
                                                $author_db = 'proof';
                                            } elseif (!is_null($useData->selesai_proof) && is_null($useData->selesai_koreksi)) {
                                                $lbl = $label . ' proses koreksi proof';
                                                $lbl_db = 'koreksi';
                                                $author_db = 'korektor';
                                            } elseif ((!is_null($useData->selesai_koreksi)) && (is_null($useData->selesai_pracetak_ctcp))) {
                                                $lbl = $label . ' proses pracetak CTCP';
                                                $lbl_db = 'pracetak_ctcp';
                                                $author_db = 'pracetak';
                                            } elseif (is_null($useData->selesai_koreksi) && !is_null($useData->selesai_pracetak_ctcp)) {
                                                $lbl = $label . ' proses koreksi CTCP';
                                                $lbl_db = 'koreksi';
                                                $author_db = 'korektor';
                                            } elseif ((!is_null($useData->selesai_koreksi)) && (!is_null($useData->selesai_pracetak_ctcp)) && (is_null($useData->selesai_pracetak_prod))) {
                                                $lbl = $label . ' proses pracetak produksi';
                                                $lbl_db = 'pracetak_prod';
                                                $author_db = 'pracetak';
                                            } else {
                                                $disable = true;
                                                $lbl = '-';
                                                $lbl_db = '-';
                                                $author_db = NULL;
                                            }
                                            break;
                                        case 'Jalur Pendek':
                                            if (is_null($useData->mulai_proof) && is_null($useData->selesai_koreksi) && (is_null($useData->mulai_pracetak_ctcp))) {
                                                $lbl = $label . ' proses koreksi';
                                                $lbl_db = 'koreksi';
                                                $author_db = 'korektor';
                                            } elseif ((!is_null($useData->selesai_koreksi)) && (is_null($useData->selesai_pracetak_ctcp))) {
                                                $lbl = $label . ' proses pracetak CTCP';
                                                $lbl_db = 'pracetak_ctcp';
                                                $author_db = 'pracetak';
                                            } elseif (is_null($useData->selesai_koreksi) && !is_null($useData->selesai_pracetak_ctcp)) {
                                                $lbl = $label . ' proses koreksi CTCP';
                                                $lbl_db = 'koreksi';
                                                $author_db = 'korektor';
                                            } elseif ((!is_null($useData->selesai_koreksi)) && (!is_null($useData->selesai_pracetak_ctcp)) && (is_null($useData->selesai_pracetak_prod))) {
                                                $lbl = $label . ' proses pracetak produksi';
                                                $lbl_db = 'pracetak_prod';
                                                $author_db = 'pracetak';
                                            } else {
                                                $disable = true;
                                                $lbl = '-';
                                                $lbl_db = '-';
                                                $author_db = NULL;
                                            }
                                            break;
                                        default:
                                            $disable = true;
                                            $lbl = '-';
                                            $lbl_db = '-';
                                            $author_db = NULL;
                                            break;
                                    }
                                } elseif ($useData->status == 'Revisi') {
                                    $disable = true;
                                    $lbl = 'Sedang proses penyelesaian revisi proof';
                                    $lbl_db = 'proof';
                                    $author_db = 'proof';
                                } else {
                                    $disable = true;
                                    $lbl = '-';
                                    $lbl_db = '-';
                                    $author_db = NULL;
                                }
                                return [
                                    'id' => $useData->id,
                                    'no_order' => $useData->no_order,
                                    'labelMulai' => $label,
                                    'disabled' => $disable,
                                    'checked' => $checked,
                                    'label' => $lbl,
                                    'prosesValue' => $item,
                                    'labelDB' => $lbl_db,
                                    'authorDB' => $author_db
                                ];
                                break;
                            case 'status':
                                $res = '';
                                switch ($item) {
                                    case 'Antrian':
                                        $res .= '<span class="badge" style="background:#34395E;color:white">Antrian</span>';
                                        break;
                                    case 'Pending':
                                        $res .= '<span class="badge badge-warning">Pending</span>';
                                        break;

                                    case 'Proses':
                                        $res .= '<span class="badge badge-success">Proses</span>';
                                        break;

                                    case 'Selesai':
                                        $res .= '<span class="badge badge-light">Selesai</span>';
                                        break;
                                    case 'Revisi':
                                        $res .= '<span class="badge badge-info">Revisi</span>';
                                        break;
                                    case 'Tidak Deal':
                                        $res .= '<span class="badge badge-danger">Tidak Deal</span>';
                                        break;
                                    default:
                                        $res .= '<span class="badge badge-primary">Kalkulasi</span>';
                                        break;
                                }
                                return $res;
                                break;
                            case 'harga_final':
                                return !is_null($item) ? $item : NULL;
                                break;
                            case 'tgl_order':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->translatedFormat('d F Y') : '-';
                                break;
                            case 'desain_setter':
                                $data = !is_null($item) ? json_decode($item, true) : NULL;
                                $disabled = $useData->jalur_proses == 'Jalur Reguler' ? false : true;
                                $required = $useData->jalur_proses == 'Jalur Reguler' ? true : false;
                                $nonRegulerInfo = $useData->jalur_proses == 'Jalur Reguler' ? '' : (is_null($useData->jalur_proses) ? '' : '<i class="fas fa-exclamation-circle"></i> Jalur Pendek tidak melalui tahap desain & setter');
                                return ['data' => $data, 'disabled' => $disabled, 'required' => $required, 'nonreguler' => $nonRegulerInfo];
                                break;
                            case 'korektor':
                                if (!is_null($item)) {
                                    foreach (json_decode($item, true) as $l) {
                                        $data[] = DB::table('users')->where('id', $l)->select('id', 'nama')->first();
                                    }
                                } else {
                                    $data = NULL;
                                }
                                return $data;
                                break;
                            case 'pracetak':
                                if (!is_null($item)) {
                                    foreach (json_decode($item, true) as $l) {
                                        $data[] = DB::table('users')->where('id', $l)->select('id', 'nama')->first();
                                    }
                                } else {
                                    $data = NULL;
                                }
                                return $data;
                                break;
                            case 'tgl_permintaan_selesai':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->translatedFormat('d F Y') : '-';
                                break;
                            case 'created_at':
                                return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item)->translatedFormat('d F Y, H:i:s') : '-';
                                break;
                            case 'created_by':
                                return !is_null($item) ? DB::table('users')->where('id', $item)->first()->nama : '-';
                                break;
                            default:
                                $item;
                                break;
                        }
                        return $item;
                    })->all();
                    return ['data' => $data];
                    break;
                case 'proses-kerja':
                    return $this->prosesKerja($request, $data);
                    break;
                case 'delegasi':
                    return $this->editDelegasiKorektorDesainerPracetak($request, $data);
                    break;
                case 'done-revision':
                    return $this->donerevisionActKabag($data);
                    break;
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan!'
                    ]);
                    break;
            }
        }
        $desSet = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Design & Setter%')
            ->where('d.nama', 'LIKE', '%Keuangan - Produksi%')
            ->select('u.nama', 'u.id')
            ->orderBy('u.nama', 'Asc')
            ->get();
        $kor = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Korektor%')
            ->where('d.nama', 'LIKE', '%Keuangan - Produksi%')
            ->select('u.nama', 'u.id')
            ->orderBy('u.nama', 'Asc')
            ->get();
        $pra = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Pracetak%')
            ->where('d.nama', 'LIKE', '%Keuangan - Produksi%')
            ->select('u.nama', 'u.id')
            ->orderBy('u.nama', 'Asc')
            ->get();
        return view('jasa_cetak.order_buku.otorisasi_kabag', [
            'title' => 'Otorisasi Kabag Order Buku',
            'data_des_set' => $desSet,
            'data_kor' => $kor,
            'data_pra' => $pra,
        ]);
    }
    protected function prosesKerja($request, $data)
    {
        if ($request->ajax()) {
            try {
                $proses = $request->proses;
                $author = $request->author;
                $label = $request->label;
                $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                if ($proses == 0) {
                    $mulai = 'mulai_' . $label;
                    $dataProgress = [
                        'params' => 'Progress Desain-Proof-Korektor-Pracetak',
                        'id' => $data->id,
                        'label' => $label,
                        $mulai => NULL,
                        'proses' => $proses,
                        'type_history' => 'Progress',
                        'author_id' => auth()->id(),
                        'modified_at' => $tgl
                    ];
                    $message = 'Proses diberhentikan!';
                } else {
                    if ($author != 'proof') {
                        if (is_null($data->$author)) {
                            $msg = $author == 'desain_setter' ? 'Desain & Setter' : ucfirst($author);
                            return response()->json([
                                'status' => 'error',
                                'message' => $msg . ' belum ditentukan!'
                            ]);
                        }
                    }
                    $mulai = 'mulai_' . $label;
                    $dataProgress = [
                        'params' => 'Progress Desain-Proof-Korektor-Pracetak',
                        'id' => $data->id,
                        'label' => $label,
                        $mulai => $tgl,
                        'proses' => $proses,
                        'type_history' => 'Progress',
                        'author_id' => auth()->id(),
                        'modified_at' => $tgl
                    ];
                    $message = 'Proses dimulai!';
                }

                event(new JasaCetakEvent($dataProgress));
                return response()->json([
                    'status' => 'success',
                    'message' => $message
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            return abort(400);
        }
    }
    protected function editDelegasiKorektorDesainerPracetak($request, $data)
    {
        try {
            if ($data->proses == '1') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hentikan proses untuk update data'
                ]);
            }
            // return response()->json();
            if ($request->has('desain')) {
                $desain = json_encode($request->desain);
            } else {
                if (is_null($data->desain_setter)) {
                    $desain = NULL;
                } else {
                    $desain = $data->desain_setter;
                }
            }
            // return response()->json($request->has('desain'));
            if ($request->has('korektor')) {
                $korektor = json_encode($request->korektor);
            } else {
                if (is_null($data->korektor)) {
                    $korektor = NULL;
                } else {
                    $korektor = $data->korektor;
                }
            }
            if ($request->has('pracetak')) {
                $pracetak = json_encode($request->pracetak);
            } else {
                if (is_null($data->pracetak)) {
                    $pracetak = NULL;
                } else {
                    $pracetak = $data->pracetak;
                }
            }

            if ($request->has('desain') || $request->has('korektor') || $request->has('pracetak')) {
                $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                $update = [
                    'params' => 'Update Data Delegasi',
                    'id' => $data->id,
                    'no_order' => $data->no_order,
                    'desain_setter' => $desain,
                    'korektor' => $korektor,
                    'pracetak' => $pracetak,
                    'type_history' => 'Update', //History
                    'desain_his' => $data->desain_setter == $desain ? NULL : $data->desain_setter, //History
                    'desain_new' => $data->desain_setter == $desain ? NULL : $desain, //History
                    'korektor_his' => $data->korektor == $korektor ? NULL : $data->korektor, //History
                    'korektor_new' => $data->korektor == $korektor ? NULL : $korektor, //History
                    'pracetak_his' => $data->pracetak == $pracetak ? NULL : $data->pracetak, //History
                    'pracetak_new' => $data->pracetak == $pracetak ? NULL : $pracetak, //History
                    'author_id' => auth()->user()->id, //History
                    'modified_at' => $tgl //History
                ];
                event(new JasaCetakEvent($update));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil update!'
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Tidak ada perubahan data!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function donerevisionActKabag($data)
    {
        try {
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            DB::beginTransaction();
            $last =  DB::table('jasa_cetak_order_buku_selesai')
                ->where('type', 'Kabag')
                ->where('section', 'Kabag Done Revision')
                ->where('order_buku_id', $data->id)
                ->orderBy('id', 'desc')
                ->first();
            if (is_null($last)) {
                $tahapSelanjutnya = 1;
            } else {
                $tahapSelanjutnya = $last->tahap + 1;
            }
            $pros = [
                'params' => 'Proses Kerja Selesai',
                'type' => 'Kabag',
                'section' => 'Kabag Done Revision',
                'tahap' => $tahapSelanjutnya,
                'order_buku_id' => $data->id,
                'users_id' => auth()->id(),
                'ket_revisi' => NULL,
                'tgl_action' => $tgl
            ];
            event(new JasaCetakEvent($pros));
            DB::table('jasa_cetak_order_buku')->where('id', $data->id)->where('no_order', $data->no_order)->update([
                'mulai_proof' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'status' => 'Proses',
                'proses' => '1'
            ]);
            DB::table('jasa_cetak_order_buku_history')->insert([
                'type_history' => 'Update',
                'order_buku_id' => $data->id,
                'mulai_proof' => $tgl,
                'selesai_revisi_kabag' => $tgl,
                'author_id' => auth()->id(),
                'modified_at' => $tgl
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Order buku yang di-proof telah selesai direvisi! Selanjutnya akan dikembalikan ke CS.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function panelStatus($id, $no_order, $judul, $status = null)
    {
        $btn = '';
        if (!Gate::allows('do_update', 'otorisasi-kabag-order-buku-jasa-cetak')) {
            switch ($status) {
                case 'Kalkulasi':
                    $btn .= '<span class="d-block badge badge-primary mr-1 mt-1">' . $status . '</span>';
                    break;
                case 'Antrian':
                    $btn .= '<span class="d-block badge badge-secondary mr-1 mt-1">' . $status . '</span>';
                    break;
                case 'Pending':
                    $btn .= '<span class="d-block badge badge-warning mr-1 mt-1">' . $status . '</span>';
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
                case 'Tidak Deal':
                    $btn .= '<span class="d-block badge badge-danger mr-1 mt-1">' . $status . '</span>';
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
                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1 btn-status-order-buku" data-id="' . $id . '" data-no_order="' . $no_order . '" data-judul="' . $judul . '" data-toggle="modal" data-target="#md_UpdateStatusJasaCetakOrderBuku" title="Update Status">
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
                case 'Tidak Deal':
                    $btn .= '<span class="d-block badge badge-danger mr-1 mt-1">' . $status . '</span>';
                    break;
                default:
                    return abort(410);
                    break;
            }
        }
        return $btn;
    }
    protected function buttonAction($id, $no_order, $btn)
    {
        //Button Edit
        if (Gate::allows('do_update', 'update-order-buku-jasa-cetak') || Gate::allows('do_update', 'otorisasi-kalkulasi-order-buku-jasa-cetak')) {
            $btn .= '<a href="' . url('jasa-cetak/order-buku/edit?order=' . $id . '&kode=' . $no_order) . '"
            class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
            <div><i class="fas fa-edit"></i></div></a>';
        }
        //Button Otorisasi Kabag
        if (Gate::allows('do_update', 'otorisasi-kabag-order-buku-jasa-cetak')) {
            $btn .= '<a href="' . url('jasa-cetak/order-buku/otorisasi-kabag?order=' . $id . '&kode=' . $no_order) . '"
            class="d-block btn btn-sm btn-info btn-icon mr-1 mt-1" data-toggle="tooltip" title="Otorisasi Kabag">
            <div><i class="fas fa-tasks"></i></div></a>';
        }
        return $btn;
    }
    protected function showModal($data)
    {
        try {
            $html = '';
            switch ($data->status) {
                case 'Kalkulasi':
                    if (is_null($data->decline)) {
                        $html .= "<div class='form-group'>
                        <label for='keterangan_decline' class='col-form-label'>Alasan: <span class='text-danger'>*</span></label>
                        <input type='hidden' name='decline_revisi' value='deal_decline'>
                        <textarea class='form-control' name='keterangan_decline' id='keterangan_decline' rows='4'></textarea>
                        <div id='err_keterangan_decline'></div>
                        </div>";
                        $title = '<i class="fas fa-times"></i>&nbsp;ORDER BUKU ' . $data->no_order . "-" . $data->judul_buku;
                        $footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi</button>';
                    } else {
                        $html .= "<div class='form-group mb-2'>
                        <label for='decline_by'>Oleh:</label>
                        <p id='decline_by'>" . DB::table('users')->where('id', $data->decline_by)->first()->nama . "</p></div>
                        <hr>
                        <div class='form-group mb-2'>
                        <label for='decline'>Dilakukan pada:</label>
                        <p id='decline'>" . Carbon::parse($data->decline)->translatedFormat('l d F Y, H:i') . "</p></div>
                        <hr>
                        <div class='form-group mb-2'>
                        <label for='keterangan_decline'>Alasan:</label>
                        <p id='keterangan_decline'>" . $data->keterangan_decline . "</p></div>";
                        $title = '<i class="fas fa-times"></i>&nbsp;ORDER BUKU ' . $data->no_order . "-" . $data->judul_buku . ', TIDAK DEAL';
                        $footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                    }
                    break;
                case 'Proses':
                    $html .= "<div class='form-group'>
                        <label for='ket_revisi' class='col-form-label'>Alasan: <span class='text-danger'>*</span></label>
                        <input type='hidden' name='decline_revisi' value='approve_revisi'>
                        <textarea class='form-control' name='ket_revisi' id='ket_revisi' rows='4'></textarea>
                        <div id='err_ket_revisi'></div>
                        </div>";
                    $title = '<i class="fas fa-times"></i>&nbsp;REVISI ORDER BUKU ' . $data->no_order . "-" . $data->judul_buku;
                    $footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi</button>';
                    break;
                case 'Revisi':
                    $ketRevisi = DB::table('jasa_cetak_order_buku_selesai')
                        ->where('type', 'Proof')
                        ->where('section', 'Proof')
                        ->where('order_buku_id', $data->id)
                        ->whereNotNull('ket_revisi')
                        ->first();
                    $html .= "<div class='form-group mb-2'>
                        <label for='decline_by'>Oleh:</label>
                        <p id='decline_by'>" . DB::table('users')->where('id', $ketRevisi->users_id)->first()->nama . "</p></div>
                        <hr>
                        <div class='form-group mb-2'>
                        <label for='decline'>Dilakukan pada:</label>
                        <p id='decline'>" . Carbon::parse($ketRevisi->tgl_action)->translatedFormat('l d F Y, H:i') . "</p></div>
                        <hr>
                        <div class='form-group mb-2'>
                        <label for='keterangan_decline'>Alasan:</label>
                        <p id='keterangan_decline'>" . $ketRevisi->ket_revisi . "</p></div>";
                    $title = '<i class="fas fa-times"></i>&nbsp;ORDER BUKU ' . $data->no_order . "-" . $data->judul_buku . ', REVISI';
                    $footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                    break;
            }

            return response()->json([
                'title' => $title,
                'footer' => $footer,
                'data' => $data,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function pilihHarga($request, $data)
    {
        try {
            $harga_final = $request->harga_final;
            DB::beginTransaction();
            $res = [
                'params' => 'Pilih Harga Order Buku',
                'harga_final' => $harga_final,
                'data' => $data,
                'harga_final_his' => $harga_final == $data->first()->harga_final ? NULL : $data->first()->harga_final,
                'harga_final_new' => $harga_final == $data->first()->harga_final ? NULL : $harga_final,
                'author_id' => auth()->user()->id,
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            event(new JasaCetakEvent($res));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Harga berhasil dipilih!',
                'data' => $harga_final
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => NULL
            ]);
        }
    }
    protected function approvalOrder($request, $data)
    {
        try {
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            switch ($request->decline_revisi) {
                case 'approve_revisi':
                    $ket_revisi = $request->ket_revisi;
                    DB::beginTransaction();
                    $last =  DB::table('jasa_cetak_order_buku_selesai')
                        ->where('type', 'Proof')
                        ->where('section', 'Proof')
                        ->where('order_buku_id', $data->first()->id)
                        ->orderBy('id', 'desc');
                    if (is_null($last->first())) {
                        $tahapSelanjutnya = 1;
                    } else {
                        $tahapSelanjutnya = $last->first()->tahap + 1;
                    }
                    if ($request->type == 'decline') {
                        $pros = [
                            'params' => 'Proses Kerja Selesai',
                            'type' => 'Proof',
                            'section' =>  'Proof',
                            'tahap' => $tahapSelanjutnya,
                            'order_buku_id' => $data->first()->id,
                            'users_id' => auth()->id(),
                            'ket_revisi' => $ket_revisi,
                            'tgl_action' => $tgl
                        ];
                        event(new JasaCetakEvent($pros));
                        $data->update([
                            'status' => 'Revisi',
                            'mulai_proof' => NULL,
                            'selesai_proof' => NULL
                        ]);
                    } else {
                        $pros = [
                            'params' => 'Proses Kerja Selesai',
                            'type' => 'Proof',
                            'section' =>  'Proof',
                            'tahap' => $tahapSelanjutnya,
                            'order_buku_id' => $data->first()->id,
                            'users_id' => auth()->id(),
                            'ket_revisi' => NULL,
                            'tgl_action' => $tgl
                        ];
                        event(new JasaCetakEvent($pros));
                        $done = [
                            'params' => 'Selesai Otorisasi',
                            'label' => 'proof',
                            'data' => $data,
                            'selesai_proof' => $tgl,
                            'proses' => '0',
                            //Pracetak Setter History
                            'type_history' => 'Update',
                            'author_id' => auth()->id()
                        ];
                        event(new JasaCetakEvent($done));
                    }
                    $lbl = $request->type == 'approve' ? 'selesai' : 'perlu direvisi';
                    DB::commit();
                    break;
                case 'deal_decline':
                    $keterangan_decline = $request->keterangan_decline;
                    DB::beginTransaction();
                    if (($request->type == 'approve') && (is_null($data->first()->harga_final))) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Anda belum menentukan jumlah order serta harga final!'
                        ]);
                    }
                    $res = [
                        'params' => 'Approval Order Buku',
                        'approve' => $request->type == 'approve' ? $tgl : NULL,
                        'decline' => $request->type == 'approve' ? NULL : $tgl,
                        'keterangan_decline' => $request->type == 'approve' ? NULL : $keterangan_decline,
                        'decline_by' => $request->type == 'approve' ? NULL : auth()->user()->id,
                        'status' => $request->type == 'approve' ? 'Antrian' : 'Tidak Deal',
                        'data' => $data,
                        'author_id' => auth()->user()->id,
                        'modified_at' => $tgl
                    ];
                    event(new JasaCetakEvent($res));
                    $lbl = $request->type == 'approve' ? 'deal' : 'tidak deal';
                    DB::commit();
                    break;
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan!'
                    ]);
                    break;
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Order buku ' . $lbl . '!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function doneWorkDesainSetter($author, $data)
    {
        try {
            $dataProses = DB::table('jasa_cetak_order_buku_selesai')
                ->where('type', ucfirst($author))
                ->where('section', 'Desain/Setter First')
                ->where('tahap', 1)
                ->where('order_buku_id', $data->first()->id)
                ->get();
            $setPros = count($dataProses) + 1;
            if ($setPros == count(json_decode($data->first()->$author, true))) {
                $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                $pros = [
                    'params' => 'Proses Kerja Selesai',
                    'type' => ucfirst($author),
                    'section' =>  'Desain/Setter First',
                    'tahap' => 1,
                    'order_buku_id' => $data->first()->id,
                    'users_id' => auth()->id(),
                    'ket_revisi' => NULL,
                    'tgl_action' => $tgl
                ];
                event(new JasaCetakEvent($pros));
                $done = [
                    'params' => 'Selesai Otorisasi',
                    'label' => 'desain',
                    'data' => $data,
                    'selesai_desain' => $tgl,
                    'proses' => '0',
                    //Pracetak Setter History
                    'type_history' => 'Update',
                    'author_id' => auth()->id()
                ];
                event(new JasaCetakEvent($done));
            } else {
                $pros = [
                    'params' => 'Proses Kerja Selesai',
                    'type' => ucfirst($author),
                    'section' =>  'Desain/Setter First',
                    'tahap' => 1,
                    'order_buku_id' => $data->first()->id,
                    'users_id' => auth()->id(),
                    'ket_revisi' => NULL,
                    'tgl_action' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new JasaCetakEvent($pros));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pengerjaan selesai'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function doneWorkKorektor($author, $data)
    {
        try {
            return response()->json($author);
            $dataProses = DB::table('jasa_cetak_order_buku_selesai')
                ->where('order_buku_id', $data->first()->id)
                ->orderBy('id','desc')
                ->first();
            if (is_null($dataProses)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }

            //! TULIS KODE DISINI

            return response()->json([
                'status' => 'success',
                'message' => 'Pengerjaan selesai'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public static function generateId()
    {
        $last = DB::table('jasa_cetak_order_buku')
            ->select('no_order')
            ->where('created_at', '>', strtotime(date('Y-m-d') . '00:00:01'))
            ->orderBy('created_at', 'desc')
            ->first();

        if (is_null($last)) {
            $id = substr(date('Y'), 2) . '.001';
        } else {
            $id = (int)substr($last->no_order, -3);
            $id = substr(date('Y'), 2) . '.' . sprintf('%03s', $id + 1);
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
                case 'catch-value-jalurproses':
                    return $this->getValueJalurProses($request);
                    break;
                case 'update-status-progress':
                    return $this->updateStatusProgress($request);
                    break;
                case 'update-jalur-proses':
                    return $this->updateJalurProses($request);
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
            $d = (object)collect($d)->map(function ($item, $key) use ($id) {
                switch ($key) {
                    case 'tgl_order_his':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item, 'Asia/Jakarta')->translatedFormat('d F Y') : NULL;
                        break;
                    case 'tgl_order_new':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item, 'Asia/Jakarta')->translatedFormat('d F Y') : NULL;
                        break;
                    case 'tgl_permintaan_selesai_his':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item, 'Asia/Jakarta')->translatedFormat('d F Y') : NULL;
                        break;
                    case 'tgl_permintaan_selesai_new':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item, 'Asia/Jakarta')->translatedFormat('d F Y') : NULL;
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
                                $jmlOrder .= '<b class="text-dark">' . $new . '</b>, ';
                            }
                        } else {
                            $jmlOrder = NULL;
                        }
                        return $jmlOrder;
                        break;
                    case 'kalkulasi_harga_his':
                        if (!is_null($item)) {
                            $kal = DB::table('jasa_cetak_order_buku')->where('id', $id)->first();
                            $kalkulasiHarga = '';
                            foreach (json_decode($kal->jml_order, true) as $i => $v) {
                                foreach (json_decode($item, true) as $j => $his) {
                                    if ($i != $j) {
                                        continue;
                                    }
                                    $kalkulasiHarga .= '<b class="text-dark">' . $v . ' Eks - Rp.' . number_format($his, 0, ',', '.') . '</b>,<br> ';
                                }
                            }
                        } else {
                            $kalkulasiHarga = NULL;
                        }
                        return $kalkulasiHarga;
                        break;
                    case 'kalkulasi_harga_new':
                        if (!is_null($item)) {
                            $kal = DB::table('jasa_cetak_order_buku')->where('id', $id)->first();
                            $kalkulasiHarga = '';
                            foreach (json_decode($kal->jml_order, true) as $i => $v) {
                                foreach (json_decode($item, true) as $j => $new) {
                                    if ($i != $j) {
                                        continue;
                                    }
                                    $kalkulasiHarga .= '<b class="text-dark">' . $v . ' Eks - Rp.' . number_format($new, 0, ',', '.') . '</b>,<br> ';
                                }
                            }
                        } else {
                            $kalkulasiHarga = NULL;
                        }
                        return $kalkulasiHarga;
                        break;
                    case 'desain_his':
                        $loop = '';
                        if (is_null($item)) {
                            $loop = $item;
                        } else {
                            foreach (json_decode($item, true) as $val) {
                                $loop .= '<b class="text-dark">' . DB::table('users')->where('id', $val)->first()->nama . '</b>,<br> ';
                            }
                        }
                        return $loop;
                        break;
                    case 'desain_new':
                        $loop = '';
                        if (is_null($item)) {
                            $loop = $item;
                        } else {
                            foreach (json_decode($item, true) as $val) {
                                $loop .= '<b class="text-dark">' . DB::table('users')->where('id', $val)->first()->nama . '</b>,<br> ';
                            }
                        }
                        return $loop;
                        break;
                    case 'korektor_his':
                        $loop = '';
                        if (is_null($item)) {
                            $loop = $item;
                        } else {
                            foreach (json_decode($item, true) as $val) {
                                $loop .= '<b class="text-dark">' . DB::table('users')->where('id', $val)->first()->nama . '</b>,<br> ';
                            }
                        }
                        return $loop;
                        break;
                    case 'korektor_new':
                        $loop = '';
                        if (is_null($item)) {
                            $loop = $item;
                        } else {
                            foreach (json_decode($item, true) as $val) {
                                $loop .= '<b class="text-dark">' . DB::table('users')->where('id', $val)->first()->nama . '</b>,<br> ';
                            }
                        }
                        return $loop;
                        break;
                    case 'pracetak_his':
                        $loop = '';
                        if (is_null($item)) {
                            $loop = $item;
                        } else {
                            foreach (json_decode($item, true) as $val) {
                                $loop .= '<b class="text-dark">' . DB::table('users')->where('id', $val)->first()->nama . '</b>,<br> ';
                            }
                        }
                        return $loop;
                        break;
                    case 'pracetak_new':
                        $loop = '';
                        if (is_null($item)) {
                            $loop = $item;
                        } else {
                            foreach (json_decode($item, true) as $val) {
                                $loop .= '<b class="text-dark">' . DB::table('users')->where('id', $val)->first()->nama . '</b>,<br> ';
                            }
                        }
                        return $loop;
                        break;
                    case 'mulai_desain':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'selesai_desain':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'mulai_proof':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'selesai_proof':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'selesai_revisi_kabag':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'mulai_koreksi':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'selesai_koreksi':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'mulai_pracetak_ctcp':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'selesai_pracetak_ctcp':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'mulai_pracetak_prod':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'selesai_pracetak_prod':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->translatedFormat('d F Y, H:i:s') : NULL;
                        break;
                    case 'modified_at':
                        return !is_null($item) ? Carbon::createFromFormat('Y-m-d H:i:s', $item, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($item)->translatedFormat('l d M Y, H:i') . ')' : NULL;
                        break;
                    default:
                        return $item;
                        break;
                }
                return $item;
            })->all();
            switch ($d->type_history) {
                case 'Update':
                    $html .= '<span class="ticket-item" id="newAppend">';

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
                            'diubah menjadi ' .
                            $d->jml_order_new .
                            '.</span></div>';
                    } elseif (!is_null($d->jml_order_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Jumlah order ' .
                            $d->jml_order_new .
                            ' ditambahkan. </span></div>';
                    }
                    if (!is_null($d->desain_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Desain & Setter<br>' .
                            $d->desain_his .
                            'diubah menjadi<br>' . $d->desain_new . '</span></div>';
                    } elseif (!is_null($d->desain_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Desain & Setter<br>' .
                            $d->desain_new .
                            ' ditambahkan. </span></div>';
                    }
                    if (!is_null($d->korektor_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Korektor<br>' .
                            $d->korektor_his .
                            'diubah menjadi<br>' . $d->korektor_new . '</span></div>';
                    } elseif (!is_null($d->korektor_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Korektor<br>' .
                            $d->korektor_new .
                            ' ditambahkan. </span></div>';
                    }
                    if (!is_null($d->pracetak_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Pracetak<br>' .
                            $d->pracetak_his .
                            'diubah menjadi<br>' . $d->pracetak_new . '</span></div>';
                    } elseif (!is_null($d->pracetak_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Pracetak<br>' .
                            $d->pracetak_new .
                            ' ditambahkan. </span></div>';
                    }
                    if (!is_null($d->kalkulasi_harga_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kalkulasi harga<br>' .
                            $d->kalkulasi_harga_his .
                            'diubah menjadi<br>' . $d->kalkulasi_harga_new . '</span></div>';
                    } elseif (!is_null($d->kalkulasi_harga_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Kalkulasi harga<br>' .
                            $d->kalkulasi_harga_new .
                            ' ditambahkan. </span></div>';
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
                    if (!is_null($d->mulai_desain)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Desain/Setter dimulai pada <b class="text-dark">' .
                            $d->mulai_desain .
                            '</b>. </span></div>';
                    } elseif (!is_null($d->selesai_desain)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Desain/Setter selesai pada <b class="text-dark">' .
                            $d->selesai_desain .
                            '</b>. </span></div>';
                    }
                    if (!is_null($d->mulai_proof)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Proof penulis oleh CS dimulai pada <b class="text-dark">' .
                            $d->mulai_proof .
                            '</b>. </span></div>';
                    } elseif (!is_null($d->selesai_proof)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Proof penulis oleh CS selesai pada <b class="text-dark">' .
                            $d->selesai_proof .
                            '</b>. </span></div>';
                    }
                    if (!is_null($d->selesai_revisi_kabag)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Revisi telah dinyatakan selesai selesai pada <b class="text-dark">' .
                            $d->selesai_revisi_kabag .
                            '</b>. </span></div>';
                    }
                    if (!is_null($d->mulai_koreksi)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Koreksi dimulai pada <b class="text-dark">' .
                            $d->mulai_koreksi .
                            '</b>. </span></div>';
                    } elseif (!is_null($d->selesai_koreksi)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Koreksi selesai pada <b class="text-dark">' .
                            $d->selesai_koreksi .
                            '</b>. </span></div>';
                    }
                    if (!is_null($d->mulai_pracetak_ctcp)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Pracetak CTCP dimulai pada <b class="text-dark">' .
                            $d->mulai_pracetak_ctcp .
                            '</b>. </span></div>';
                    } elseif (!is_null($d->selesai_pracetak_ctcp)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Pracetak CTCP selesai pada <b class="text-dark">' .
                            $d->selesai_pracetak_ctcp .
                            '</b>. </span></div>';
                    }
                    if (!is_null($d->mulai_pracetak_prod)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Pracetak produksi dimulai pada <b class="text-dark">' .
                            $d->mulai_pracetak_prod .
                            '</b>. </span></div>';
                    } elseif (!is_null($d->selesai_pracetak_prod)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Pracetak produksi selesai pada <b class="text-dark">' .
                            $d->selesai_pracetak_prod .
                            '</b>. </span></div>';
                    }
                    $html .=
                        '<div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . $d->modified_at . '</div>
                        </div>
                        </span>';
                    break;
                case 'Jalur Proses':
                    $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Jalur proses jasa cetak order buku <b class="text-dark">' . $d->jalur_proses . '</b>, telah ditambahkan.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . $d->modified_at . '</div>
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
                            <div class="pt-2">' . $d->modified_at . '</div>
                        </div>
                        </span>';
                    break;
                case 'Pilih Harga':
                    $html .= '<span class="ticket-item" id="newAppend">';

                    if (!is_null($d->harga_final_his)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Harga Final <b class="text-dark">' .
                            $d->harga_final_his .
                            '</b> diubah menjadi <b class="text-dark">' .
                            $d->harga_final_new .
                            '</b> </span></div>';
                    } elseif (!is_null($d->harga_final_new)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Harga Final <b class="text-dark">' .
                            $d->harga_final_new .
                            '</b> ditambahkan. </span></div>';
                    }
                    $html .=
                        '<div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . $d->modified_at . '</div>
                        </div>
                        </span>';
                    break;
                case 'Approval':
                    $html .= '<span class="ticket-item" id="newAppend">';

                    if (!is_null($d->approve)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Order buku deal, status proses "Antrian". <i class="fas fa-check text-success"></i></span></div>';
                    } elseif (!is_null($d->decline)) {
                        $html .=
                            '<div class="ticket-title">
                                <span><span class="bullet"></span> Order buku tidak deal, dengan alasan "<b class="text-dark">' .
                            $d->keterangan_decline .
                            '</b>". <i class="fas fa-times text-danger"></i> </span></div>';
                    }
                    $html .=
                        '<div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . $d->modified_at . '</div>
                        </div>
                        </span>';
                    break;
                case 'Progress':
                    $ket = $d->progress == 1 ? 'Dimulai' : 'Dihentikan';
                    $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title">
                        <span><span class="bullet"></span> Progress order buku jasa cetak <b class="text-dark">' . $ket . '</b>.</span>
                    </div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . $d->modified_at . '</div>
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
                ->where('id', $id)
                ->where('no_order', $no_order)
                ->first();
            if (is_null($data)) {
                return abort(404);
            }
            $data = (object)collect($data)->map(function ($item, $key) {
                return is_null($item) ? NULL : $item;
            });
            return ['data' => $data];
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function getValueJalurStatus($request)
    {
        try {
            $id = $request->id;
            $no_order = $request->no_order;
            $data = DB::table('jasa_cetak_order_buku')
                ->where('id', $id)
                ->where('no_order', $no_order)
                ->first();
            if (is_null($data)) {
                return abort(404);
            }
            $data = (object)collect($data)->map(function ($item, $key) {
                switch ($key) {
                    case 'id':
                        return $item;
                        break;
                    case 'no_order':
                        return $item;
                        break;
                    case 'judul_buku':
                        return $item;
                        break;
                }
            });
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
            $no_order = $request->no_order;
            DB::beginTransaction();
            $data = DB::table('jasa_cetak_order_buku')
                ->where('id', $id)
                ->where('no_order', $no_order)
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
    protected function updateJalurProses($request)
    {
        try {
            $id = $request->id;
            $no_order = $request->no_order;
            DB::beginTransaction();
            $data = DB::table('jasa_cetak_order_buku')
                ->where('id', $id)
                ->where('no_order', $no_order);
            if (is_null($data->first())) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ], 404);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $author = auth()->user()->id;
            $update = [
                'params' => 'Update Status Order Buku',
                'id' => $data->first()->id,
                'tgl_selesai_order' => NULL,
                'status' => $request->status,
            ];
            $insert = [
                'params' => 'Insert History Status Order Buku',
                'order_buku_id' => $data->first()->id,
                'type_history' => 'Status',
                'status_his' => $data->first()->status,
                'status_new'  => $request->status,
                'author_id' => $author,
                'modified_at' => $tgl
            ];
            $jalurProses = [
                'params' => 'Update Jalur Proses Order Buku',
                'data' => $data,
                'jalur_proses' => $request->jalur_proses,
                'type_history' => 'Jalur Proses',
                'author_id' => $author,
                'modified_at' => $tgl
            ];
            event(new JasaCetakEvent($update));
            event(new JasaCetakEvent($insert));
            event(new JasaCetakEvent($jalurProses));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menentukan jalur proses!'
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
