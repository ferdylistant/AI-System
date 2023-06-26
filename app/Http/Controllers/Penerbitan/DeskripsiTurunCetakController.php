<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use App\Events\DesturcetEvent;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class DeskripsiTurunCetakController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('deskripsi_turun_cetak as dtc')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->select(
                    'dtc.*',
                    'pn.kode',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'dp.nama_pena',
                    'dp.format_buku',
                )
                ->orderBy('dtc.tgl_masuk', 'ASC');
            if (in_array(auth()->id(), $data->pluck('pic_prodev')->toArray())) {
                $data->where('pn.pic_prodev', auth()->id());
            }
            $data->get();
            $update = Gate::allows('do_create', 'ubah-atau-buat-des-cover');
            if ($request->has('count_data')) {
                return $data->count();
            } elseif ($request->has('show_status')) {
                $showStatus = DB::table('deskripsi_turun_cetak as dtc')
                    ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                    ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                    ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                    ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->where('dtc.id', $request->id)
                    ->select('dtc.*', 'dp.judul_final')
                    ->first();
                return response()->json($showStatus);
            } else {
                return DataTables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('kode', function ($data) {
                        return $data->kode;
                    })
                    ->addColumn('judul_final', function ($data) {
                        if (is_null($data->judul_final)) {
                            $res = '-';
                        } else {
                            $res = $data->judul_final;
                        }
                        return $res;
                    })
                    ->addColumn('penulis', function ($data) {
                        // return $data->penulis;
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
                        //  $res;
                    })
                    ->addColumn('nama_pena', function ($data) {
                        $result = '';
                        if (is_null($data->nama_pena)) {
                            $result .= "-";
                        } else {
                            foreach (json_decode($data->nama_pena) as $q) {
                                $result .= '<span class="d-block">-&nbsp;' . $q . '</span>';
                            }
                        }
                        return $result;
                    })
                    ->addColumn('format_buku', function ($data) {
                        if (!is_null($data->format_buku)) {
                            $res = DB::table('format_buku')->where('id', $data->format_buku)->first()->jenis_format . ' cm';
                        } else {
                            $res = '-';
                        }
                        return $res;
                    })
                    ->addColumn('tgl_masuk', function ($data) {
                        return Carbon::parse($data->tgl_masuk)->translatedFormat('l d M Y H:i');
                    })
                    ->addColumn('pic_prodev', function ($data) {
                        $result = '';
                        $res = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')
                            ->select('nama')
                            ->get();
                        foreach (json_decode($res) as $q) {
                            $result .= $q->nama;
                        }
                        return $result;
                    })
                    ->addColumn('history', function ($data) {
                        $historyData = DB::table('deskripsi_turun_cetak_history')->where('deskripsi_turun_cetak_id', $data->id)->get();
                        if ($historyData->isEmpty()) {
                            return '-';
                        } else {
                            $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                            return $date;
                        }
                    })
                    ->addColumn('tracker', function ($data) {
                        $trackerData = DB::table('tracker')->where('section_id', $data->id)->get();
                        if ($trackerData->isEmpty()) {
                            return '-';
                        } else {
                            $date = '<button type="button" class="btn btn-sm btn-info btn-icon mr-1 btn-tracker" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-file-signature"></i>&nbsp;Lihat Tracking</button>';
                            return $date;
                        }
                    })
                    ->addColumn('action', function ($data) use ($update) {
                        $btn = '<a href="' . url('penerbitan/deskripsi/turun-cetak/detail?desc=' . $data->id . '&kode=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';

                        if ($data->status != 'Terkunci') {
                            if ($update) {
                                if ($data->status == 'Selesai') {
                                    if (Gate::allows('do_approval', 'approval-deskripsi-produk')) {
                                        $btn = $this->buttonEdit($data->id, $data->kode, $btn);
                                    }
                                } else {
                                    if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') || (Gate::allows('do_approval', 'approval-deskripsi-produk'))) {
                                        $btn = $this->buttonEdit($data->id, $data->kode, $btn);
                                    }
                                }
                            }
                        }

                        if (Gate::allows('do_approval', 'action-progress-des-cover')) {
                            if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                                $btn = $this->panelStatusProdev($data->status, $data->id, $data->kode, $data->judul_final, $btn);
                            }
                        } else {
                            $btn = $this->panelStatusGuest($data->status, $btn);
                        }
                        return $btn;
                    })
                    ->rawColumns([
                        'kode',
                        'judul_final',
                        'penulis',
                        'nama_pena',
                        'format_buku',
                        'pic_prodev',
                        'tgl_masuk',
                        'history',
                        'tracker',
                        'action'
                    ])
                    ->make(true);
            }
        }
        //Isi Warna Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_cover WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        $statusFilter = Arr::sort($statusProgress);
        $statusAction = Arr::except($statusFilter, ['4']);
        return view('penerbitan.des_turun_cetak.index', [
            'title' => 'Deskripsi Turun Cetak',
            'status_progress' => $statusFilter,
            'status_action' => $statusAction,
        ]);
    }
    public function detailDeskripsiTurunCetak(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $insert = [
                        'params' => 'Insert Pilihan Terbit Desturcet',
                        'id' => Uuid::uuid4()->toString(),
                        'deskripsi_turun_cetak_id' => $request->id,
                        'pilihan_terbit' => json_encode($request->add_pilihan_terbit),
                        'platform_ebook' => $request->add_platform_ebook == null ? NULL : json_encode(array_filter($request->add_platform_ebook)),
                    ];
                    DB::table('deskripsi_turun_cetak')->where('id', $request->id)->update([
                        'tgl_pil_terbit_selesai' => $tgl
                    ]);
                    $data = DB::table('deskripsi_turun_cetak as dtc')
                        ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                        ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                        ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                        ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                        ->where('dtc.id', $request->id)
                        ->select(
                            'dtc.*',
                            'dp.naskah_id',
                            'dp.judul_final',
                            'pn.pic_prodev',
                            'pn.kode'
                        )
                        ->first();
                    //Insert Pilih Terbit Todo List
                    $permissionPilTer = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                        ->where('p.id', '6effe04d-8fd9-11ed-a315-4cedfb61fb39')
                        ->select('up.user_id')
                        ->get();
                    $permissionPilTer = (object)collect($permissionPilTer)->map(function ($item) use ($data) {
                        $cekEksis = DB::table('todo_list')
                            ->where('form_id', $data->id)
                            ->where('users_id', $item->user_id)
                            ->where('title', 'Pilih jenis terbit untuk naskah "' . $data->judul_final . '".');
                        if (is_null($cekEksis->first())) {
                            return DB::table('todo_list')->insert([
                                'form_id' => $data->id,
                                'users_id' => $item->user_id,
                                'title' => 'Pilih jenis terbit untuk naskah "' . $data->judul_final . '".',
                                'link' => '/penerbitan/deskripsi/turun-cetak/detail?desc=' . $data->id . '&kode=' . $data->kode,
                                'status' => '1'
                            ]);
                        } else {
                            $cekEksis->update([
                                'status' => '1'
                            ]);
                        }
                    })->all();
                    //UPDATE TIMELINE PILIHAN TERBIT
                    $updateTimelinePilihanTerbit = [
                        'params' => 'Update Timeline',
                        'naskah_id' => $data->naskah_id,
                        'progress' => 'Pilihan Terbit',
                        'tgl_selesai' => $tgl,
                        'status' => 'Selesai'
                    ];
                    event(new TimelineEvent($updateTimelinePilihanTerbit));
                    foreach ($request->add_pilihan_terbit as $pt) {
                        switch ($pt) {
                            case 'ebook':
                                $kodeOrder = self::getOrderEbookId($request->type);
                                $type = 'ebook';
                                break;
                            case 'cetak':
                                $kodeOrder = self::getOrderCetakId($request->type);
                                $type = 'cetak';
                                break;
                        }
                        $idOrder = Uuid::uuid4()->toString();
                        $order = [
                            'params' => 'Insert Order',
                            'type' => $type,
                            'id' => $idOrder,
                            'kode_order' => $kodeOrder,
                            'deskripsi_turun_cetak_id' => $request->id,
                            'tgl_masuk' => $tgl
                        ];
                        event(new DesturcetEvent($order));
                        //INSERT TODO LIST ADMIN
                        $permissionAdmin = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                            ->join('users as u', 'u.id', '=', 'up.user_id')
                            ->join('jabatan as j', 'j.id', '=', 'u.jabatan_id')
                            ->where('p.id', '=', 'e0860766d564483e870b5974a601649c')
                            ->where('j.nama', 'LIKE', '%Administrasi%')
                            ->select('up.user_id')
                            ->get();
                        $data = (object)collect($data)->put('id_order', $idOrder);
                        $permissionAdmin = (object)collect($permissionAdmin)->map(function ($item) use ($data) {
                            return DB::table('todo_list')->insert([
                                'form_id' => $data['id_order'],
                                'users_id' => $item->user_id,
                                'title' => 'Lengkapi data order cetak untuk naskah "' . $data['judul_final'] . '".',
                                'link' => '/penerbitan/order-cetak/edit?order=' . $data['id_order'] . '&naskah=' . $data['kode'],
                                'status' => '0'
                            ]);
                        })->all();
                        //INSERT TIMELINE ORDER CETAK / ORDER EBOOK
                        $insertTimelineOrder = [
                            'params' => 'Insert Timeline',
                            'id' => Uuid::uuid4()->toString(),
                            'progress' => 'Order ' . ucfirst($type),
                            'naskah_id' => $data['naskah_id'],
                            'tgl_mulai' => $tgl,
                            'url_action' => urlencode(URL::to('/penerbitan/order-' . $type . '/detail?order=' . $idOrder . '&naskah=' . $data['kode'])),
                            'status' => 'Antrian'
                        ];
                        event(new TimelineEvent($insertTimelineOrder));
                    }
                    event(new DesturcetEvent($insert));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data pilihan terbit berhasil ditambahkan',
                        'route' => route('desturcet.detail')
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
        $id = $request->get('desc');
        $kode = $request->get('kode');
        $data = DB::table('deskripsi_turun_cetak as dtc')
            ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
            ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
            ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('dtc.id', $id)
            ->where('pn.kode', $kode)
            ->select(
                'dtc.*',
                'ps.edisi_cetak',
                'ps.isbn',
                'ps.jml_hal_final',
                'dp.naskah_id',
                'dp.format_buku',
                'dp.judul_final',
                'dp.nama_pena',
                'pn.kode',
                'pn.url_file',
                'pn.pic_prodev',
                'pn.jalur_buku',
                'kb.nama'
            )
            ->first();
        if (is_null($data)) {
            return redirect()->route('desturcet.view');
        }
        $pilihan_terbit = DB::table('pilihan_penerbitan as pp')
            ->where('pp.deskripsi_turun_cetak_id', $data->id)
            ->first();
        // dd($pilihan_terbit);
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();

        if (is_null($data)) {
            return abort(404);
        }
        $sasaranPasar = DB::table('penerbitan_pn_prodev')
            ->where('naskah_id', $data->naskah_id)
            ->first();
        $sasaran_pasar = is_null($sasaranPasar) ? null : $sasaranPasar->sasaran_pasar;
        $pic = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')->first()->nama;
        $platform_ebook = DB::table('platform_digital_ebook')->whereNull('deleted_at')->get();
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id', $data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        // dd($platform_ebook);
        return view('penerbitan.des_turun_cetak.detail', [
            'title' => 'Detail Deskripsi Turun Cetak',
            'data' => $data,
            'penulis' => $penulis,
            'sasaran_pasar' => $sasaran_pasar,
            'pic' => $pic,
            'platform_ebook' => $platform_ebook,
            'pilihan_terbit' => $pilihan_terbit,
            'format_buku' => $format_buku,
        ]);
    }
    public function editDeskripsiTurunCetak(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('deskripsi_turun_cetak as dtc')
                        ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                        ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                        ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                        ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                        ->where('dtc.id', $request->id)
                        ->select('dtc.*', 'dp.format_buku', 'ps.edisi_cetak')
                        ->first();

                    $update = [
                        'params' => 'Edit Desturcet',
                        'id' => $request->id,
                        'tipe_order' => $request->tipe_order,
                        'edisi_cetak' => $request->edisi_cetak, //Deskripsi Final
                        'format_buku' => $request->format_buku, //Deskripsi Produk
                        'bulan' => Carbon::createFromDate($request->bulan)
                    ];
                    event(new DesturcetEvent($update));

                    $insert = [
                        'params' => 'Insert History Edit Desturcet',
                        'deskripsi_turun_cetak_id' => $request->id,
                        'type_history' => 'Update',
                        'edisi_cetak_his' => $history->edisi_cetak == $request->edisi_cetak ? NULL : $history->edisi_cetak,
                        'edisi_cetak_new' => $history->edisi_cetak == $request->edisi_cetak ? NULL : $request->edisi_cetak,
                        'format_buku_his' => $history->format_buku == $request->format_buku ? NULL : $history->format_buku,
                        'format_buku_new' => $history->format_buku == $request->format_buku ? NULL : $request->format_buku,
                        'tipe_order_his' => $history->tipe_order == $request->tipe_order ? NULL : $history->tipe_order,
                        'tipe_order_new' => $history->tipe_order == $request->tipe_order ? NULL : $request->tipe_order,
                        'bulan_his' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $history->bulan,
                        'bulan_new' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : Carbon::createFromDate($request->bulan),
                        'author_id' => auth()->id(),
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new DesturcetEvent($insert));

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data deskripsi turun cetak berhasil ditambahkan',
                        'route' => route('desturcet.view')
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
        $id = $request->get('desc');
        $kodenaskah = $request->get('kode');
        $data = DB::table('deskripsi_turun_cetak as dtc')
            ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
            ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
            ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('dtc.id', $id)
            ->where('pn.kode', $kodenaskah)
            ->whereNull('pn.deleted_at')
            ->select(
                'dtc.*',
                'ps.edisi_cetak',
                'ps.isbn',
                'ps.jml_hal_final',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.nama_pena',
                'dp.format_buku',
                'pn.kode',
                'pn.jalur_buku',
                'pn.judul_asli',
                'pn.pic_prodev',
                'kb.nama',
            )
            ->first();
        is_null($data) ? abort(404) :
            $sasaranPasar = DB::table('penerbitan_pn_prodev')
            ->where('naskah_id', $data->naskah_id)
            ->first();
        $sasaran_pasar = is_null($sasaranPasar) ? null : $sasaranPasar->sasaran_pasar;
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();
        $nama_pena = json_decode($data->nama_pena);
        $format_buku_list = DB::table('format_buku')->whereNull('deleted_at')->get();
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_turun_cetak WHERE Field = 'tipe_order'"))[0]->Type;
        preg_match("/^set\(\'(.*)\'\)$/", $type, $matches);
        $tipeOrder = explode("','", $matches[1]);
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id', $data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        return view('penerbitan.des_turun_cetak.edit', [
            'title' => 'Edit Deskripsi Turun Cetak',
            'data' => $data,
            'penulis' => $penulis,
            'format_buku_list' => $format_buku_list,
            'format_buku' => $format_buku,
            'sasaran_pasar' => $sasaran_pasar,
            'tipe_order' => $tipeOrder,
            'nama_pena' => is_null($data->nama_pena) ? NULL : $nama_pena
        ]);
    }
    public function actionAjax(Request $request)
    {
        try {
            switch ($request->act) {
                case 'lihat-history':
                    return $this->lihatHistoryDesTurunCetak($request);
                    break;
                case 'lihat-tracking':
                    return $this->lihatTrackingDesTurunCetak($request);
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
    public function updateStatusProgress(Request $request)
    {
        try {
            $id = $request->id;
            // return response()->json($request->id);
            DB::beginTransaction();
            $data = DB::table('deskripsi_turun_cetak as dtc')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->where('dtc.id', $id)
                ->select(
                    'dtc.*',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'pn.kode',
                    'pn.pic_prodev',
                )
                ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data corrupt...'
                ], 404);
            }
            // return response()->json($data);
            if ($data->status == $request->status) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pilih status yang berbeda dengan status saat ini!'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $update = [
                'params' => 'Update Status Desturcet',
                'id' => $data->id,
                'status' => $request->status,
                'tgl_status_selesai' => $request->status == 'Selesai' ? $tgl : NULL
            ];
            $insert = [
                'params' => 'Insert History Status Desturcet',
                'deskripsi_turun_cetak_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            if ($request->status == 'Selesai') {
                $dataValidasi = DB::table('deskripsi_turun_cetak')->where('id', $data->id)->whereNotNull('tipe_order')->first();
                if (is_null($dataValidasi)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tipe order wajib diisi!'
                    ]);
                }
                event(new DesturcetEvent($update));
                event(new DesturcetEvent($insert));
                //Update or Insert Todo Prodev
                $todoProdev = DB::table('todo_list')
                    ->where('form_id', $data->id)
                    ->where('users_id', $data->pic_prodev)
                    ->where('title', 'Proses deskripsi turun cetak naskah berjudul "' . $data->judul_final . '" perlu dilengkapi kelengkapan data nya.');
                if (is_null($todoProdev->first())) {
                    DB::table('todo_list')->insert([
                        'form_id' => $data->id,
                        'users_id' => $data->pic_prodev,
                        'title', 'Proses deskripsi turun cetak naskah berjudul "' . $data->judul_final . '" perlu dilengkapi kelengkapan data nya.',
                        'link' => '/penerbitan/deskripsi/turun-cetak?desc=' . $data->id . '&kode=' . $data->kode,
                        'status' => '1'
                    ]);
                } else {
                    $todoProdev->update([
                        'status' => '1'
                    ]);
                }
                //Insert Pilih Terbit Todo List
                $permissionPilTer = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                    ->where('p.id', '6effe04d-8fd9-11ed-a315-4cedfb61fb39')
                    ->select('up.user_id')
                    ->get();
                $permissionPilTer = (object)collect($permissionPilTer)->map(function ($item) use ($data) {
                    $cekEksis = DB::table('todo_list')
                        ->where('form_id', $data->id)
                        ->where('users_id', $item->user_id)
                        ->where('title', 'Pilih jenis terbit untuk naskah "' . $data->judul_final . '".');
                    if (is_null($cekEksis->first())) {
                        return DB::table('todo_list')->insert([
                            'form_id' => $data->id,
                            'users_id' => $item->user_id,
                            'title' => 'Pilih jenis terbit untuk naskah "' . $data->judul_final . '".',
                            'link' => '/penerbitan/deskripsi/turun-cetak/detail?desc=' . $data->id . '&kode=' . $data->kode,
                            'status' => '0'
                        ]);
                    } else {
                        $cekEksis->update([
                            'status' => '0'
                        ]);
                    }
                })->all();
                //UPDATE TIMELINE DESKRIPSI TURUN CETAK
                $updateTimelineDesturcet = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Deskripsi Turun Cetak',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineDesturcet));
                //INSERT TIMELINE PILIHAN TERBIT
                $insertTimelinePilTerbit = [
                    'params' => 'Insert Timeline',
                    'id' => Uuid::uuid4()->toString(),
                    'progress' => 'Pilihan Terbit',
                    'naskah_id' => $data->naskah_id,
                    'tgl_mulai' => $tgl,
                    'url_action' => urlencode(URL::to('/penerbitan/deskripsi/turun-cetak/detail?desc=' . $data->id . '&kode=' . $data->kode)),
                    'status' => 'Proses'
                ];
                event(new TimelineEvent($insertTimelinePilTerbit));
                $msg = 'Deskripsi turun cetak selesai';
            } else {
                event(new DesturcetEvent($update));
                event(new DesturcetEvent($insert));
                // return response()->json($update);
                $updateTimelineDesturcet = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Deskripsi Turun Cetak',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineDesturcet));
                $msg = 'Status progress deskripsi turun cetak berhasil diupdate';
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
    protected function getOrderCetakId($tipeOrder)
    {
        $year = date('y');
        switch ($tipeOrder) {
            case 1:
                $lastId = DB::table('order_cetak')
                    ->where('kode_order', 'like', $year . '-%')
                    ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 1000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 2999")
                    ->orderBy('kode_order', 'desc')->first();

                $firstId = '1000';
                break;
            case 2:
                $lastId = DB::table('order_cetak')
                    ->where('kode_order', 'like', $year . '-%')
                    ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 3000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 3999")
                    ->orderBy('kode_order', 'desc')->first();
                $firstId = '3000';
                break;
            case 3:
                $lastId = DB::table('order_cetak')
                    ->where('kode_order', 'like', $year . '-%')
                    ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 4000")
                    ->orderBy('kode_order', 'desc')->first();
                $firstId = '4000';
                break;
            default:
                abort(500);
        }

        if (is_null($lastId)) {
            return $year . '-' . $firstId;
        } else {
            $lastId_ = (int)substr($lastId->kode_order, 3);
            if ($lastId_ == 2999) {
                abort(500);
            } elseif ($lastId_ == 3999) {
                abort(500);
            } else {

                return $year . '-' . strval($lastId_ + 1);
            }
        }
    }
    protected function getOrderEbookId($tipeOrder)
    {
        $year = date('y');
        switch ($tipeOrder) {
            case 1:
                $lastId = DB::table('order_ebook')
                    ->where('kode_order', 'like', 'E' . $year . '-%')
                    ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 1000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 2999")
                    ->orderBy('kode_order', 'desc')->first();

                $firstId = '1000';
                break;
            case 2:
                $lastId = DB::table('order_ebook')
                    ->where('kode_order', 'like', 'E' . $year . '-%')
                    ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 3000 and SUBSTRING_INDEX(kode_order, '-', -1) <= 3999")
                    ->orderBy('kode_order', 'desc')->first();
                $firstId = '3000';
                break;
            case 3:
                $lastId = DB::table('order_ebook')
                    ->where('kode_order', 'like', 'E' . $year . '-%')
                    ->whereRaw("SUBSTRING_INDEX(kode_order, '-', -1) >= 4000")
                    ->orderBy('kode_order', 'desc')->first();
                $firstId = '4000';
                break;
            default:
                abort(500);
        }
        //  return $lastId.'1234';
        if (is_null($lastId)) {
            return 'E' . $year . '-' . $firstId;
        } else {
            $lastId_ = (int)substr($lastId->kode_order, 4);
            if ($lastId_ == 2999) {
                abort(500);
            } elseif ($lastId_ == 3999) {
                abort(500);
            } else {

                return 'E' . $year . '-' . strval($lastId_ + 1);
            }
        }
    }
    protected function lihatHistoryDesTurunCetak(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('deskripsi_turun_cetak_history as dtch')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'dtch.deskripsi_turun_cetak_id')
                ->join('users as u', 'u.id', '=', 'dtch.author_id')
                ->where('dtch.deskripsi_turun_cetak_id', $id)
                ->select('dtch.*', 'u.nama')
                ->orderBy('dtch.id', 'desc')
                ->paginate(2);

            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status deskripsi turun cetak <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';

                        if (!is_null($d->edisi_cetak_his)) {
                            $html .= ' Edisi cetak tahun <b class="text-dark">' . $d->edisi_cetak_his . ' </b> diubah menjadi <b class="text-dark">' . $d->edisi_cetak_new . ' </b>.<br>';
                        } elseif (!is_null($d->edisi_cetak_new)) {
                            $html .= ' Edisi cetak tahun <b class="text-dark">' . $d->edisi_cetak_new . ' </b> ditambahkan.<br>';
                        }
                        if (!is_null($d->format_buku_his)) {
                            $html .= ' Format buku <b class="text-dark">' . DB::table('format_buku')->where('id', $d->format_buku_his)->first()->jenis_format . ' cm</b> diubah menjadi <b class="text-dark">' . DB::table('format_buku')->where('id', $d->format_buku_new)->first()->jenis_format . ' cm</b>.<br>';
                        } elseif (!is_null($d->format_buku_new)) {
                            $html .= ' Format buku <b class="text-dark">' . DB::table('format_buku')->where('id', $d->format_buku_new)->first()->jenis_format . ' cm</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->bulan_his)) {
                            $html .= ' Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->bulan_new)->translatedFormat('F Y') . '</b>.';
                        }
                        $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                        break;
                }
            }
            return $html;
        }
    }
    protected function lihatTrackingDesTurunCetak($request)
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
    protected function panelStatusProdev($status = null, $id, $kode, $judul_final, $btn)
    {
        switch ($status) {
            case 'Antrian':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-desturcet" style="background:#34395E;color:white" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusDesTurCet" title="Update Status">
                        <div>' . $status . '</div></a>';
                break;
            case 'Pending':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-desturcet" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusDesTurCet" title="Update Status">
                        <div>' . $status . '</div></a>';
                break;
            case 'Proses':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-desturcet" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusDesTurCet" title="Update Status">
                        <div>' . $status . '</div></a>';
                break;
            case 'Selesai':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-light btn-icon mr-1 mt-1 btn-status-desturcet" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusDesTurCet" title="Update Status">
                        <div>' . $status . '</div></a>';
                break;
            default:
                return abort(410);
                break;
        }
        return $btn;
    }
    protected function panelStatusGuest($status = null, $btn)
    {
        switch ($status) {
            case 'Terkunci':
                $btn .= '<span class="d-block badge badge-dark mr-1 mt-1"><i class="fas fa-lock"></i>&nbsp;' . $status . '</span>';
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
            default:
                return abort(410);
                break;
        }
        return $btn;
    }
    protected function buttonEdit($id, $kode, $btn)
    {
        $btn .= '<a href="' . url('penerbitan/deskripsi/turun-cetak/edit?desc=' . $id . '&kode=' . $kode) . '"
            class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
            <div><i class="fas fa-edit"></i></div></a>';
        return $btn;
    }
}
