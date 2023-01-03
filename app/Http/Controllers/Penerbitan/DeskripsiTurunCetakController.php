<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use App\Events\EditingEvent;
use Illuminate\Http\Request;
use App\Events\DesturcetEvent;
use Yajra\DataTables\DataTables;
use App\Events\PracetakCoverEvent;
use App\Events\PracetakSetterEvent;
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
                    'dp.format_buku',
                )
                ->orderBy('dtc.tgl_masuk', 'ASC')
                ->get();
            $update = Gate::allows('do_create', 'ubah-atau-buat-des-cover');

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
                ->addColumn('format_buku', function ($data) {
                    if (!is_null($data->format_buku)) {
                        $res = $data->format_buku . ' cm';
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
                ->addColumn('format_buku', function ($data) {
                    if (!is_null($data->format_buku)) {
                        $res = $data->format_buku . ' cm';
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
                    $historyData = DB::table('deskripsi_cover_history')->where('deskripsi_cover_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-history"></i>&nbsp;History</button>';
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
                    'format_buku',
                    'pic_prodev',
                    'tgl_masuk',
                    'history',
                    'action'
                ])
                ->make(true);
        }
        $data = DB::table('deskripsi_final as df')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->whereNull('df.deleted_at')
            ->select(
                'df.*',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev',
                'dp.naskah_id',
                'dp.imprint',
                'dp.judul_final'
            )
            ->orderBy('df.tgl_deskripsi', 'ASC')
            ->get();
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
            'count' => count($data)
        ]);
    }
    public function detailDeskripsiTurunCetak(Request $request)
    {
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
                'df.sub_judul_final',
                'df.bullet',
                'dp.naskah_id',
                'dp.format_buku',
                'dp.judul_final',
                'dp.imprint',
                'dp.kelengkapan',
                'dp.catatan',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev',
                'pn.jalur_buku',
                'kb.nama'
            )
            ->first();
        if (is_null($data)) {
            return redirect()->route('desturcet.view');
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();
        $pic = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')->first()->nama;
        // $desainer = DB::table('users')->where('id', $data->desainer)->whereNull('deleted_at')->first()->nama;
        return view('penerbitan.des_turun_cetak.detail', [
            'title' => 'Detail Deskripsi Turun Cetak',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            // 'desainer' => $desainer,
        ]);
    }
    public function editDeskripsiTurunCetak(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                try {
                    $history = DB::table('deskripsi_turun_cetak as dtc')
                    ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                    ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                    // ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->where('dtc.id', $request->id)
                    ->select('dtc.*')
                    ->first();
                    return response()->json([
                        'status' => 'error',
                        'message' => Carbon::createFromFormat('F Y', $request->bulan,'Asia/Jakarta')->format('Y-m-d')
                    ]);
                    $update = [
                        'params' => 'Edit Desturcet',
                        'id' => $request->id,
                        'edisi_cetak' => $request->edisi_cetak, //Deskripsi Final
                        'format_buku' => $request->format_buku, //Deskripsi Produk
                        'bulan' => Carbon::createFromDate($request->bulan)
                        // 'updated_by' => auth()->id()
                    ];

                    event(new DesturcetEvent($update));

                    // $insert = [
                    //     'params' => 'Insert History Edit Desturcet',
                    //     'deskripsi_cover_id' => $request->id,
                    //     'type_history' => 'Update',
                    //     'format_buku_his' => $history->format_buku == $request->format_buku ? NULL : $history->format_buku,
                    //     'format_buku_new' => $history->format_buku == $request->format_buku ? NULL : $request->format_buku,
                    //     'bulan_his' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $history->bulan,
                    //     'bulan_new' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : Carbon::createFromDate($request->bulan),
                    //     'author_id' => auth()->id(),
                    //     'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    // ];
                    // event(new DesturcetEvent($insert));
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
            ->where('pnp.naskah_id', '=', $data->id)
            ->select('pp.nama')
            ->get();
        $format_buku = DB::table('format_buku')->whereNull('deleted_at')->get();

        //Jilid Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_cover WHERE Field = 'jilid'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $jilid = explode("','", $matches[1]);
        //Finishing Cover
        $finishingCover = ['Embosh', 'Foil', 'Glossy', 'Laminasi Dof', 'UV', 'UV Spot'];
        //Kelengkapan Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'kelengkapan'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $kelengkapan = explode("','", $matches[1]);
        // $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        return view('penerbitan.des_turun_cetak.edit', [
            'title' => 'Edit Deskripsi Turun Cetak',
            'data' => $data,
            'penulis' => $penulis,
            'format_buku' => $format_buku,
            'jilid' => $jilid,
            'finishing_cover' => $finishingCover,
            'kelengkapan' => $kelengkapan,
            'sasaran_pasar' => $sasaran_pasar
            // 'imprint' => $imprint
        ]);
    }
    public function updateStatusProgress(Request $request)
    {
        try {
            $id = $request->id;
            $data = DB::table('deskripsi_cover as dc')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                    $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                        ->whereNull('kb.deleted_at');
                })
                ->where('dc.id', $id)
                ->whereNull('dp.deleted_at')
                ->whereNull('pn.deleted_at')
                ->select(
                    'dc.*',
                    'df.id as deskripsi_final_id',
                    'df.setter',
                    'df.korektor',
                    'dp.naskah_id',
                    'dp.format_buku',
                    'dp.judul_final',
                    'dp.editor',
                    'dp.imprint',
                    'dp.jml_hal_perkiraan',
                    'dp.kelengkapan',
                    'dp.catatan',
                    'pn.kode',
                    'pn.judul_asli',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'kb.nama'
                )
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
            $update = [
                'params' => 'Update Status Descov',
                'deskripsi_produk_id' => $data->deskripsi_produk_id,
                'status' => $request->status,
                'updated_by' => auth()->id()
            ];
            $insert = [
                'params' => 'Insert History Status Descov',
                'deskripsi_cover_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            if ($request->status == 'Selesai') {
                event(new DesturcetEvent($update));
                event(new DesturcetEvent($insert));
                $insertEditingProses = [
                    'params' => 'Insert Editing',
                    'id' => Uuid::uuid4()->toString(),
                    'deskripsi_final_id' => $data->deskripsi_final_id,
                    'editor' => json_encode([$data->editor]),
                    'tgl_masuk_editing' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new EditingEvent($insertEditingProses));
                $insertPracetakSetter = [
                    'params' => 'Insert Pracetak Setter',
                    'id' => Uuid::uuid4()->toString(),
                    'deskripsi_final_id' => $data->deskripsi_final_id,
                    'setter' => json_encode([$data->setter]),
                    'korektor' => json_encode([$data->korektor]),
                    'jml_hal_final' => $data->jml_hal_perkiraan,
                    'tgl_masuk_pracetak' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                ];
                event(new PracetakSetterEvent($insertPracetakSetter));
                $insertPracetakCover = [
                    'params' => 'Insert Pracetak Cover',
                    'id' => Uuid::uuid4()->toString(),
                    'deskripsi_cover_id' => $data->id,
                    'desainer' => json_encode([$data->desainer]),
                    'editor' => json_encode([$data->editor]),
                    'tgl_masuk_cover' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                ];
                event(new PracetakCoverEvent($insertPracetakCover));
                $msg = 'Deskripsi cover selesai, silahkan lanjut ke proses Pracetak Cover dan Editing..';
            } else {
                event(new DesturcetEvent($update));
                event(new DesturcetEvent($insert));
                $msg = 'Status progress deskripsi cover berhasil diupdate';
            }
            return response()->json([
                'status' => 'success',
                'message' => $msg
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryDesTurunCetak(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('deskripsi_turun_cetak_history as dtch')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'dtch.deskripsi_turun_cetak_id')
                ->join('users as u', 'dtch.author_id', '=', 'u.id')
                ->where('dtch.deskripsi_turun_cetak_id', $id)
                ->select('dtch.*', 'u.nama',)
                ->orderBy('dtch.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title">
                        <span><span class="bullet"></span> Status deskripsi cover <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
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
                        if (!is_null($d->sub_judul_final_his)) {
                            $html .= ' Sub Judul final <b class="text-dark">' . $d->sub_judul_final_his . '</b> diubah menjadi <b class="text-dark">' . $d->sub_judul_final_new . '</b>.<br>';
                        } elseif (!is_null($d->sub_judul_final_new)) {
                            $html .= ' Sub Judul final <b class="text-dark">' . $d->sub_judul_final_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->des_front_cover_his)) {
                            $html .= ' Deskripsi cover depan <b class="text-dark">' . $d->des_front_cover_his . '</b> diubah menjadi <b class="text-dark">' . $d->des_front_cover_new . '</b>.<br>';
                        } elseif (!is_null($d->des_front_cover_new)) {
                            $html .= ' Deskripsi cover depan <b class="text-dark">' . $d->des_front_cover_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->des_back_cover_his)) {
                            $html .= ' Deskripsi cover belakang <b class="text-dark">' . $d->des_back_cover_his . '</b> diubah menjadi <b class="text-dark">' . $d->des_back_cover_new . '</b>.<br>';
                        } elseif (!is_null($d->des_back_cover_new)) {
                            $html .= ' Deskripsi cover belakang <b class="text-dark">' . $d->des_back_cover_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->finishing_cover_his)) {
                            $loopFC = '';
                            $loopFCN = '';
                            foreach (json_decode($d->finishing_cover_his, true) as $fc) {
                                $loopFC .= '<b class="text-dark">' . $fc . '</b>, ';
                            }
                            foreach (json_decode($d->finishing_cover_new, true) as $fcn) {
                                $loopFCN .= '<span class="bullet"></span>' . $fcn;
                            }
                            $html .= ' Finishing cover <b class="text-dark">' . $loopFC . '</b> diubah menjadi <b class="text-dark">' . $loopFCN . '</b>.<br>';
                        } elseif (!is_null($d->finishing_cover_new)) {
                            $loopFCNew = '';
                            foreach (json_decode($d->finishing_cover_new, true) as $fcn) {
                                $loopFCNew .= '<b class="text-dark">' . $fcn . '</b>, ';
                            }
                            $html .= ' Finishing cover ' . $loopFCNew . 'ditambahkan.<br>';
                        }
                        if (!is_null($d->format_buku_his)) {
                            $html .= ' Format buku <b class="text-dark">' . $d->format_buku_his . ' cm</b> diubah menjadi <b class="text-dark">' . $d->format_buku_new . ' cm</b>.<br>';
                        } elseif (!is_null($d->format_buku_new)) {
                            $html .= ' Format buku <b class="text-dark">' . $d->format_buku_new . ' cm</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->jilid_his)) {
                            $html .= ' Jilid <b class="text-dark">' . $d->jilid_his . '</b> diubah menjadi <b class="text-dark">' . $d->jilid_new . '</b>.<br>';
                        } elseif (!is_null($d->jilid_new)) {
                            $html .= ' Jilid <b class="text-dark">' . $d->jilid_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->tipografi_his)) {
                            $html .= ' Tipografi <b class="text-dark">' . $d->tipografi_his . '</b> diubah menjadi <b class="text-dark">' . $d->tipografi_new . '</b>.<br>';
                        } elseif (!is_null($d->tipografi_new)) {
                            $html .= ' Tipografi <b class="text-dark">' . $d->tipografi_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->warna_his)) {
                            $html .= ' Warna <b class="text-dark">' . $d->warna_his . '</b> diubah menjadi <b class="text-dark">' . $d->warna_new . '</b>.<br>';
                        } elseif (!is_null($d->warna_new)) {
                            $html .= ' Warna <b class="text-dark">' . $d->warna_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->desainer_his)) {
                            $html .= ' Desainer <b class="text-dark">'
                                . DB::table('users')->where('id', $d->desainer_his)->whereNull('deleted_at')->first()->nama .
                                '</b> diubah menjadi <b class="text-dark">' . DB::table('users')->where('id', $d->desainer_new)->whereNull('deleted_at')->first()->nama . '</b>.<br>';
                        } elseif (!is_null($d->desainer_new)) {
                            $html .= ' Desainer <b class="text-dark">'
                                . DB::table('users')->where('id', $d->desainer_new)->whereNull('deleted_at')->first()->nama .
                                '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->kelengkapan_his)) {
                            $html .= ' Kelengkapan <b class="text-dark">' . $d->kelengkapan_his . '</b> diubah menjadi <b class="text-dark">' . $d->kelengkapan_new . '</b>.<br>';
                        } elseif (!is_null($d->kelengkapan_new)) {
                            $html .= ' Kelengkapan <b class="text-dark">' . $d->kelengkapan_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->catatan_his)) {
                            $html .= ' Catatan <b class="text-dark">' . $d->catatan_his . '</b> diubah menjadi <b class="text-dark">' . $d->catatan_new . '</b>.<br>';
                        } elseif (!is_null($d->catatan_new)) {
                            $html .= ' Catatan <b class="text-dark">' . $d->catatan_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->contoh_cover_his)) {
                            $html .= ' Link contoh cover <a href="' . $d->contoh_cover_his . '" target="_blank" class="text-dark"><b>' . $d->contoh_cover_his . '</b></a> diubah menjadi <a href="' . $d->contoh_cover_new . '" target="_blank" class="text-dark"><b>' . $d->contoh_cover_new . '</b></a>.<br>';
                        } elseif (!is_null($d->contoh_cover_new)) {
                            $html .= ' Link contoh cover <a href="' . $d->contoh_cover_new . '" target="_blank" class="text-dark"><b>' . $d->contoh_cover_new . '</b></a> ditambahkan.<br>';
                        }
                        if (!is_null($d->bullet_his)) {
                            $loopFC = '';
                            $loopFCN = '';
                            foreach (json_decode($d->bullet_his, true) as $fc) {
                                $loopFC .= '<span class="bullet"></span>' . $fc;
                            }
                            foreach (json_decode($d->bullet_new, true) as $fcn) {
                                $loopFCN .= '<span class="bullet"></span>' . $fcn;
                            }
                            $html .= ' Bullet <b class="text-dark">' . $loopFC . '</b> diubah menjadi <b class="text-dark">' . $loopFCN . '</b>.<br>';
                        } elseif (!is_null($d->bullet_new)) {
                            $loopFCNew = '';
                            foreach (json_decode($d->bullet_new, true) as $fcn) {
                                $loopFCNew .= '<span class="bullet"></span>' . $fcn;
                            }
                            $html .= ' Bullet ' . $loopFCNew . '</b> ditambahkan.<br>';
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
    protected function panelStatusProdev($status = null, $id, $kode, $judul_final, $btn)
    {
        switch ($status) {
            case 'Terkunci':
                $btn .= '<span class="d-block badge badge-dark mr-1 mt-1"><i class="fas fa-lock"></i>&nbsp;' . $status . '</span>';
                break;
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
