<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Events\DescovEvent;
use Illuminate\Support\Arr;
use App\Events\EditingEvent;
use App\Events\TrackerEvent;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use Yajra\DataTables\DataTables;
use App\Events\PracetakCoverEvent;
use App\Events\PracetakSetterEvent;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class DeskripsiCoverController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('deskripsi_cover as dc')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->whereNull('dc.deleted_at')
                ->select(
                    'dc.*',
                    'pn.kode',
                    'pn.judul_asli',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'pn.kelompok_buku_id',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'dp.nama_pena',
                    'df.sub_judul_final',
                    'df.bullet'
                )
                ->orderBy('dc.tgl_deskripsi', 'ASC');
            if (in_array(auth()->id(),$data->pluck('pic_prodev')->toArray())) {
                $data->where('pn.pic_prodev', auth()->id());
            }
            $data->get();
            $update = Gate::allows('do_create', 'ubah-atau-buat-des-cover');
            if ($request->has('count_data')) {
                return $data->count();
            } else {
                return DataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('kode', function ($data) {
                    return $data->kode;
                })
                ->addColumn('judul_asli', function ($data) {
                    return $data->judul_asli;
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
                ->addColumn('jalur_buku', function ($data) {
                    if (!is_null($data->jalur_buku)) {
                        $res = $data->jalur_buku;
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
                ->addColumn('kelompok_buku', function ($data) {
                    if (!is_null($data->kelompok_buku_id)) {
                        $res = DB::table('penerbitan_m_kelompok_buku')->where('id', $data->kelompok_buku_id)->first()->nama;
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
                ->addColumn('judul_final', function ($data) {
                    if (is_null($data->judul_final)) {
                        $res = '-';
                    } else {
                        $res = $data->judul_final;
                    }
                    return $res;
                })
                ->addColumn('tgl_deskripsi', function ($data) {
                    return Carbon::parse($data->tgl_deskripsi)->translatedFormat('l d M Y H:i');
                })
                ->addColumn('pic_prodev', function ($data) {
                    $res = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')
                        ->first()->nama;
                    return $res;
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
                    $btn = '<a href="' . url('penerbitan/deskripsi/cover/detail?desc=' . $data->id . '&kode=' . $data->kode) . '"
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
                        $btn = $this->panelStatusGuest($data->status,$btn);
                    }
                    return $btn;
                })
                ->rawColumns([
                    'kode',
                    'judul_asli',
                    'penulis',
                    'nama_pena',
                    'jalur_buku',
                    'kelompok_buku',
                    'judul_final',
                    'tgl_deskripsi',
                    'pic_prodev',
                    'history',
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
        return view('penerbitan.des_cover.index', [
            'title' => 'Deskripsi Cover',
            'status_progress' => $statusFilter,
            'status_action' => $statusAction,
        ]);
    }
    public function detailDeskripsiCover(Request $request)
    {
        $id = $request->get('desc');
        $kode = $request->get('kode');
        $data = DB::table('deskripsi_cover as dc')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('dc.id', $id)
            ->where('pn.kode', $kode)
            ->whereNull('dp.deleted_at')
            ->whereNull('pn.deleted_at')
            ->select(
                'dc.*',
                'df.sub_judul_final',
                'df.bullet',
                'dp.naskah_id',
                'dp.format_buku',
                'dp.judul_final',
                'dp.nama_pena',
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
            return redirect()->route('descov.view');
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.id','pp.nama')
            ->get();
        $pic = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')->select('id','nama')->first();
        if (!is_null($data->desainer)) {
            $desainer = DB::table('users')->where('id', $data->desainer)->whereNull('deleted_at')->select('id','nama')->first();
        } else {
            $desainer = NULL;
        }
        $imprint = NULL;
        if (!is_null($data->imprint)) {
            $imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id',$data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        // $desainer = DB::table('users')->where('id', $data->desainer)->whereNull('deleted_at')->first()->nama;
        return view('penerbitan.des_cover.detail', [
            'title' => 'Detail Deskripsi Cover',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'desainer' => $desainer,
            'imprint' => $imprint,
            'format_buku' => $format_buku,
        ]);
    }
    public function editDeskripsiCover(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $history = DB::table('deskripsi_cover as dc')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->join('deskripsi_final as df', 'df.deskripsi_produk_id', '=', 'dp.id')
                    ->where('dc.id', $request->id)
                    ->whereNull('dc.deleted_at')
                    ->select('dc.*', 'dp.judul_final','dp.nama_pena', 'dp.format_buku','dp.kelengkapan', 'df.sub_judul_final', 'df.bullet')
                    ->first();
                foreach ($request->bullet as $value) {
                    $bullet[] = $value;
                }
                $np = explode(",",$request->nama_pena);
                $n_pena = is_null($request->nama_pena)?NULL:json_encode($np);
                $fc = is_null($request->finishing_cover) ? NULL : json_encode($request->finishing_cover);
                $bulletVariabel = is_null($request->bullet) ? NULL : json_encode(array_filter($bullet));
                $bulan = is_null($request->bulan) ? NULL : Carbon::createFromDate($request->bulan);
                $update = [
                    'params' => 'Edit Descov',
                    'id' => $request->id,
                    'sub_judul_final' => $request->sub_judul_final, //Deskripsi Final
                    'nama_pena' => $n_pena, //Deskripsi Produk
                    'des_front_cover' => $request->des_front_cover,
                    'des_back_cover' => $request->des_back_cover,
                    'finishing_cover' => $fc, //Array
                    'format_buku' => $request->format_buku, //Deskripsi Produk
                    'jilid' => $request->jilid,
                    'tipografi' => $request->tipografi,
                    'warna' => $request->warna,
                    'kelengkapan' => $request->kelengkapan, //Deskripsi Produk
                    'catatan' => $request->catatan,
                    'bullet' =>  $bulletVariabel, //Deskripsi Final
                    'desainer' => $request->desainer,
                    'contoh_cover' => $request->contoh_cover,
                    'bulan' => $bulan,
                    'updated_by' => auth()->id()
                ];
                event(new DescovEvent($update));

                $insert = [
                    'params' => 'Insert History Edit Descov',
                    'deskripsi_cover_id' => $request->id,
                    'type_history' => 'Update',
                    'nama_pena_his' => $history->nama_pena == $n_pena ? NULL : $history->nama_pena,
                    'nama_pena_new' => $history->nama_pena == $n_pena ? NULL : $n_pena,
                    'format_buku_his' => $history->format_buku == $request->format_buku ? NULL : $history->format_buku,
                    'format_buku_new' => $history->format_buku == $request->format_buku ? NULL : $request->format_buku,
                    'sub_judul_final_his' => $history->sub_judul_final == $request->sub_judul_final ? NULL : $history->sub_judul_final,
                    'sub_judul_final_new' => $history->sub_judul_final == $request->sub_judul_final ? NULL : $request->sub_judul_final,
                    'des_front_cover_his' => $history->des_front_cover == $request->des_front_cover ? NULL : $history->des_front_cover,
                    'des_front_cover_new' => $history->des_front_cover == $request->des_front_cover ? NULL : $request->des_front_cover,
                    'des_back_cover_his' => $history->des_back_cover == $request->des_back_cover ? NULL : $history->des_back_cover,
                    'des_back_cover_new' => $history->des_back_cover == $request->des_back_cover ? NULL : $request->des_back_cover,
                    'finishing_cover_his' => $history->finishing_cover == $fc ? NULL : $history->finishing_cover,
                    'finishing_cover_new' => $history->finishing_cover == $fc ? NULL : $fc,
                    'jilid_his' => $history->jilid == $request->jilid ? NULL : $history->jilid,
                    'jilid_new' => $history->jilid == $request->jilid ? NULL : $request->jilid,
                    'tipografi_his' => $history->tipografi == $request->tipografi ? NULL : $history->tipografi,
                    'tipografi_new' => $history->tipografi == $request->tipografi ? NULL : $request->tipografi,
                    'warna_his' => $history->warna == $request->warna ? NULL : $history->warna,
                    'warna_new' => $history->warna == $request->warna ? NULL : $request->warna,
                    'bullet_his' => $history->bullet == $bulletVariabel ? NULL : $history->bullet,
                    'bullet_new' => $history->bullet == $bulletVariabel ? NULL : $bulletVariabel,
                    'desainer_his' => $history->desainer == $request->desainer ? NULL : $history->desainer,
                    'desainer_new' => $history->desainer == $request->desainer ? NULL : $request->desainer,
                    'contoh_cover_his' => $history->contoh_cover == $request->contoh_cover ? NULL : $history->contoh_cover,
                    'contoh_cover_new' => $history->contoh_cover == $request->contoh_cover ? NULL : $request->contoh_cover,
                    'kelengkapan_his' => $history->kelengkapan == $request->kelengkapan ? NULL : $history->kelengkapan,
                    'kelengkapan_new' => $history->kelengkapan == $request->kelengkapan ? NULL : $request->kelengkapan,
                    'catatan_his' => $history->catatan == $request->catatan ? NULL : $history->catatan,
                    'catatan_new' => $history->catatan == $request->catatan ? NULL : $request->catatan,
                    'bulan_his' => date('Y-m',strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $history->bulan,
                    'bulan_new' => date('Y-m',strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $bulan,
                    'author_id' => auth()->id(),
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new DescovEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data deskripsi cover berhasil ditambahkan',
                    'route' => route('descov.view')
                ]);
            }
        }
        $id = $request->get('desc');
        $kodenaskah = $request->get('kode');
        $data = DB::table('deskripsi_cover as dc')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
            ->join('deskripsi_final as df', 'df.deskripsi_produk_id', '=', 'dp.id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('dc.id', $id)
            ->where('pn.kode', $kodenaskah)
            ->whereNull('dc.deleted_at')
            ->whereNull('pn.deleted_at')
            ->select(
                'dc.*',
                'df.sub_judul_final',
                'df.bullet',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.nama_pena',
                'dp.format_buku',
                'dp.imprint',
                'dp.kelengkapan',
                'pn.kode',
                'pn.judul_asli',
                'pn.pic_prodev',
                'kb.nama',
            )
            ->first();
        is_null($data) ? abort(404) :
            $desainer = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Design%')
            ->where('d.nama', 'LIKE', '%Penerbitan%')
            ->select('u.nama', 'u.id')
            ->get();
        if (!is_null($data->desainer)) {
            $namaDesainer = DB::table('users')
                ->where('id', $data->desainer)
                ->first();
        } else {
            $namaDesainer = NULL;
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();
        $format_buku_list = DB::table('format_buku')->whereNull('deleted_at')->get();
        $nama_pena = json_decode($data->nama_pena);
        //Jilid Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_cover WHERE Field = 'jilid'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $jilid = explode("','", $matches[1]);
        //Finishing Cover
        $finishingCover = ['Embosh', 'Foil', 'Glossy', 'Laminasi Dof', 'UV', 'UV Spot'];
        //Kelengkapan Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_produk WHERE Field = 'kelengkapan'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $kelengkapan = explode("','", $matches[1]);
        $nama_imprint = '-';
        if (!is_null($data->imprint)) {
            $nama_imprint = DB::table('imprint')->where('id',$data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id',$data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        // $imprint = DB::table('imprint')->whereNull('deleted_at')->get();
        return view('penerbitan.des_cover.edit', [
            'title' => 'Edit Deskripsi Cover',
            'data' => $data,
            'desainer' => $desainer,
            'penulis' => $penulis,
            'format_buku_list' => $format_buku_list,
            'format_buku' => $format_buku,
            'jilid' => $jilid,
            'nama_desainer' => $namaDesainer,
            'finishing_cover' => $finishingCover,
            'kelengkapan' => $kelengkapan,
            'nama_imprint' => $nama_imprint,
            'nama_pena' => is_null($data->nama_pena)?NULL:implode(",",$nama_pena)
            // 'imprint' => $imprint
        ]);
    }
    public function updateStatusProgress(Request $request)
    {
        try {
            DB::beginTransaction();
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
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
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
                'modified_at' => $tgl
            ];
            if ($request->status == 'Selesai') {
                event(new DescovEvent($update));
                event(new DescovEvent($insert));
                //? Todo list Prodev
                $cekTodo = DB::table('todo_list')
                ->where('form_id',$data->id)
                ->where('users_id',$data->pic_prodev)
                ->where('title','Proses deskripsi cover naskah berjudul "'.$data->judul_final.'" perlu dilengkapi kelengkapan data nya.');
                if (!is_null($cekTodo->first())) {
                    $cekTodo->update([
                        'status' => '1'
                    ]);
                } else {
                    DB::table('todo_list')->insert([
                        'form_id' => $data->id,
                        'users_id' => $data->pic_prodev,
                        'title' => 'Proses deskripsi cover naskah berjudul "'.$data->judul_final.'" perlu dilengkapi kelengkapan data nya.',
                        'status' => '1'
                    ]);
                }
                $updateTimelineDescov = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Deskripsi Cover',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineDescov));

                $cekEditing = DB::table('editing_proses')->where('deskripsi_final_id',$data->deskripsi_final_id)->first();
                if (is_null($cekEditing)) {
                    //INSERT EDITING
                    $idEditing = Uuid::uuid4()->toString();
                    $insertEditingProses = [
                        'params' => 'Insert Editing',
                        'id' => $idEditing,
                        'deskripsi_final_id' => $data->deskripsi_final_id,
                        'editor' => json_encode([$data->editor]),
                        'tgl_masuk_editing' => $tgl
                    ];
                    event(new EditingEvent($insertEditingProses));

                    //? Insert Todo List Kabag Editing
                    switch ($data->jalur_buku) {
                        case 'Reguler':
                            $editorPermission = '88f281e83aff47d08f555a2961420bf5';
                            break;
                        case 'MoU':
                            $editorPermission = 'ce3589b822a14011ba581c803ef50f5b';
                            break;
                        case 'SMK/NonSmk':
                            $editorPermission = 'a9354dd060524bce8278e2cd75ce349a';
                            break;
                        default:
                            //permission jalur buku lainnya belum ditentukan di database
                            $editorPermission = '';
                            break;
                    }
                    $kabagEditing = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                        ->where('p.id',$editorPermission)
                        ->select('up.user_id')
                        ->get();
                    $dataEditing = [
                        'id' => $idEditing,
                        'kode' => $data->kode,
                        'judul' => $data->judul_final
                    ];
                    $kabagEditing = (object)collect($kabagEditing)->map(function($item) use ($dataEditing) {
                        return DB::table('todo_list')->insert([
                            'form_id' => $dataEditing['id'],
                            'users_id' => $item->user_id,
                            'title' => 'Proses delegasi tahap editing naskah "'.$dataEditing['judul'].'".',
                            'link' => '/penerbitan/editing/edit?editing='.$dataEditing['id'].'&kode='.$dataEditing['kode'],
                            'status' => '0',
                        ]);
                    })->all();
                    $insertTimelineEditing = [
                        'params' => 'Insert Timeline',
                        'id' => Uuid::uuid4()->toString(),
                        'progress' => 'Editing',
                        'naskah_id' => $data->naskah_id,
                        'tgl_mulai' => $tgl,
                        'url_action' => urlencode(URL::to('/penerbitan/editing/detail?editing='.$idEditing.'&kode='.$data->kode)),
                        'status' => 'Antrian'
                    ];
                    event(new TimelineEvent($insertTimelineEditing));
                    //* TRACKER EDITING
                    $descEditing = 'Naskah berjudul <a href="'.url('penerbitan/editing/detail?editing='.$idEditing.'&kode='.$data->kode).'">'.$data->judul_final.'</a> telah memasuki tahap antrian Editing.';
                    $trackerEditing = [
                        'id' => Uuid::uuid4()->toString(),
                        'section_id' => $idEditing,
                        'section_name' => 'Editing',
                        'description' => $descEditing,
                        'icon' => 'fas fa-folder-plus',
                        'created_by' => auth()->id()
                    ];
                    event(new TrackerEvent($trackerEditing));
                }
                $cekPraset = DB::table('pracetak_setter')->where('deskripsi_final_id',$data->deskripsi_final_id)->first();
                if (is_null($cekPraset)) {
                    //INSERT PRACETAK SETTER
                    $idPraset = Uuid::uuid4()->toString();
                    $insertPracetakSetter = [
                        'params' => 'Insert Pracetak Setter',
                        'id' => $idPraset,
                        'deskripsi_final_id' => $data->deskripsi_final_id,
                        'setter' => json_encode([$data->setter]),
                        'korektor' => json_encode([$data->korektor]),
                        'jml_hal_final' => $data->jml_hal_perkiraan,
                        'tgl_masuk_pracetak' => $tgl,
                    ];
                    event(new PracetakSetterEvent($insertPracetakSetter));
                    //? Insert Todo List Kabag Pracetak Setter
                    switch ($data->jalur_buku) {
                        case 'Reguler':
                            $prasetPermission = '2c2753d3-6951-11ed-9234-4cedfb61fb39';
                            break;
                        case 'MoU':
                            $prasetPermission = '25b1853c-6952-11ed-9234-4cedfb61fb39';
                            break;
                        case 'SMK/NonSmk':
                            $prasetPermission = '457aca55-6952-11ed-9234-4cedfb61fb39';
                            break;
                        default:
                            //permission jalur buku lainnya belum ditentukan di database
                            $prasetPermission = '';
                            break;
                    }
                    $kabagPraset = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                        ->where('p.id',$prasetPermission)
                        ->select('up.user_id')
                        ->get();
                    $dataPraset = [
                        'id' => $idPraset,
                        'kode' => $data->kode,
                        'judul' => $data->judul_final
                    ];
                    $kabagPraset = (object)collect($kabagPraset)->map(function($item) use ($dataPraset) {
                        return DB::table('todo_list')->insert([
                            'form_id' => $dataPraset['id'],
                            'users_id' => $item->user_id,
                            'title' => 'Proses delegasi tahap pracetak setter naskah "'.$dataPraset['judul'].'".',
                            'link' => '/penerbitan/pracetak/setter/edit?setter='.$dataPraset['id'].'&kode='.$dataPraset['kode'],
                            'status' => '0',
                        ]);
                    })->all();
                    $insertTimelinePraset = [
                        'params' => 'Insert Timeline',
                        'id' => Uuid::uuid4()->toString(),
                        'progress' => 'Pracetak Setter',
                        'naskah_id' => $data->naskah_id,
                        'tgl_mulai' => $tgl,
                        'url_action' => urlencode(URL::to('/penerbitan/pracetak/setter/detail?pra='.$idPraset.'&kode='.$data->kode)),
                        'status' => 'Antrian'
                    ];
                    event(new TimelineEvent($insertTimelinePraset));
                    //* TRACKER PRACETAK SETTER
                    $descPraset = 'Naskah berjudul <a href="'.url('penerbitan/pracetak/setter/detail?pra='.$idPraset.'&kode='.$data->kode).'">'.$data->judul_final.'</a> telah memasuki tahap antrian Pracetak Setter.';
                    $trackerPraset = [
                        'id' => Uuid::uuid4()->toString(),
                        'section_id' => $idPraset,
                        'section_name' => 'Pracetak Setter',
                        'description' => $descPraset,
                        'icon' => 'fas fa-folder-plus',
                        'created_by' => auth()->id()
                    ];
                    event(new TrackerEvent($trackerPraset));
                }
                $cekPracov = DB::table('pracetak_cover')->where('deskripsi_cover_id',$data->id)->first();
                if (is_null($cekPracov)) {
                    //INSERT PRACETAK COVER
                    $idPracov = Uuid::uuid4()->toString();
                    $insertPracetakCover = [
                        'params' => 'Insert Pracetak Cover',
                        'id' => $idPracov,
                        'deskripsi_cover_id' => $data->id,
                        'desainer' => json_encode([$data->desainer]),
                        'tgl_masuk_cover' => $tgl,
                    ];
                    event(new PracetakCoverEvent($insertPracetakCover));
                    //? Insert Todo List Kabag Pracetak Cover
                    switch ($data->jalur_buku) {
                        case 'Reguler':
                            $pracovPermission = '2c2753d3-6951-11ed-9234-4cedfb61fb39';
                            break;
                        case 'MoU':
                            $pracovPermission = '25b1853c-6952-11ed-9234-4cedfb61fb39';
                            break;
                        case 'SMK/NonSmk':
                            $pracovPermission = '457aca55-6952-11ed-9234-4cedfb61fb39';
                            break;
                        default:
                            //permission jalur buku lainnya belum ditentukan di database
                            $pracovPermission = '';
                            break;
                    }
                    $kabagPracov = DB::table('permissions as p')->join('user_permission as up','up.permission_id','=','p.id')
                        ->where('p.id',$pracovPermission)
                        ->select('up.user_id')
                        ->get();
                    $dataPracov = [
                        'id' => $idPracov,
                        'kode' => $data->kode,
                        'judul' => $data->judul_final
                    ];
                    $kabagPracov = (object)collect($kabagPracov)->map(function($item) use ($dataPracov) {
                        return DB::table('todo_list')->insert([
                            'form_id' => $dataPracov['id'],
                            'users_id' => $item->user_id,
                            'title' => 'Proses delegasi tahap pracetak cover naskah "'.$dataPracov['judul'].'".',
                            'link' => '/penerbitan/pracetak/designer/edit?cover='.$dataPracov['id'].'&kode='.$dataPracov['kode'],
                            'status' => '0',
                        ]);
                    })->all();
                    $insertTimelinePracov = [
                        'params' => 'Insert Timeline',
                        'id' => Uuid::uuid4()->toString(),
                        'progress' => 'Pracetak Cover',
                        'naskah_id' => $data->naskah_id,
                        'tgl_mulai' => $tgl,
                        'url_action' => urlencode(URL::to('/penerbitan/pracetak/designer/detail?pra='.$idPracov.'&kode='.$data->kode)),
                        'status' => 'Antrian'
                    ];
                    event(new TimelineEvent($insertTimelinePracov));
                    //* TRACKER PRACETAK COVER
                    $descPracov = 'Naskah berjudul <a href="'.url('penerbitan/pracetak/designer/detail?pra='.$idPracov.'&kode='.$data->kode).'">'.$data->judul_final.'</a> telah memasuki tahap antrian Pracetak Cover.';
                    $trackerPracov = [
                        'id' => Uuid::uuid4()->toString(),
                        'section_id' => $idPracov,
                        'section_name' => 'Pracetak Cover',
                        'description' => $descPracov,
                        'icon' => 'fas fa-folder-plus',
                        'created_by' => auth()->id()
                    ];
                    event(new TrackerEvent($trackerPracov));
                }
                $desc = 'Deskripsi cover selesai, proses berlanjut ke Editing dan Pracetak.';
                $icon = 'fas fa-clipboard-check';
                $msg = 'Deskripsi cover selesai, silahkan lanjut ke proses Pracetak Cover dan Editing..';
            } else {
                event(new DescovEvent($update));
                event(new DescovEvent($insert));
                $updateTimelineDescov = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Deskripsi Cover',
                    'tgl_selesai' => NULL,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineDescov));
                $namaUser = auth()->user()->nama;
                $desc = '<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a> mengubah status pengerjaan Deskripsi Cover menjadi <b>'.$request->status.'</b>.';
                $icon = 'fas fa-info-circle';
                $msg = 'Status progress deskripsi cover berhasil diupdate';
            }
            $addTracker = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $data->id,
                'section_name' => 'Deskripsi Cover',
                'description' => $desc,
                'icon' => $icon,
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($addTracker));
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
    public function lihatHistoryDescov(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('deskripsi_cover_history as dch')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'dch.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('users as u', 'dch.author_id', '=', 'u.id')
                ->where('dch.deskripsi_cover_id', $id)
                ->select('dch.*', 'dp.judul_final', 'u.nama',)
                ->orderBy('dch.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item">
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
                        $html .= '<span class="ticket-item">';
                    if (!is_null($d->sub_judul_final_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Sub Judul final <b class="text-dark">' . $d->sub_judul_final_his . '</b> diubah menjadi <b class="text-dark">' . $d->sub_judul_final_new . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->sub_judul_final_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Sub Judul final <b class="text-dark">' . $d->sub_judul_final_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if ((!is_null($d->nama_pena_his)) && ($d->nama_pena_his != '[""]')) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $loopNamaPenaHIS = '';
                        $loopNamaPenaNEW = '';
                        foreach (json_decode($d->nama_pena_his, true) as $namaPenahis) {
                            $loopNamaPenaHIS .= '<b class="text-dark">' . $namaPenahis . '</b>, ';
                        }
                        foreach (json_decode($d->nama_pena_new, true) as $namaPenanew) {
                            $loopNamaPenaNEW .= '<span class="bullet"></span>' . $namaPenanew;
                        }
                        $html .= ' Nama pena <b class="text-dark">' . $loopNamaPenaHIS . '</b> diubah menjadi <b class="text-dark">' . $loopNamaPenaNEW . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif ((!is_null($d->nama_pena_new)) && ($d->nama_pena_new != '[""]')) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $loopNamaPenaNEW = '';
                        foreach (json_decode($d->nama_pena_new, true) as $namaPenanew) {
                            $loopNamaPenaNEW .= '<b class="text-dark">' . $namaPenanew . '</b>, ';
                        }
                        $html .= ' Nama pena <b class="text-dark">' . $loopNamaPenaNEW . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->des_front_cover_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Deskripsi cover depan <b class="text-dark">' . $d->des_front_cover_his . '</b> diubah menjadi <b class="text-dark">' . $d->des_front_cover_new . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->des_front_cover_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Deskripsi cover depan <b class="text-dark">' . $d->des_front_cover_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->des_back_cover_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Deskripsi cover belakang <b class="text-dark">' . $d->des_back_cover_his . '</b> diubah menjadi <b class="text-dark">' . $d->des_back_cover_new . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->des_back_cover_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Deskripsi cover belakang <b class="text-dark">' . $d->des_back_cover_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->finishing_cover_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $loopFC = '';
                        $loopFCN = '';
                        foreach (json_decode($d->finishing_cover_his, true) as $fc) {
                            $loopFC .= '<b class="text-dark">' . $fc . '</b>, ';
                        }
                        foreach (json_decode($d->finishing_cover_new, true) as $fcn) {
                            $loopFCN .= '<span class="bullet"></span>' . $fcn;
                        }
                        $html .= ' Finishing cover <b class="text-dark">' . $loopFC . '</b> diubah menjadi <b class="text-dark">' . $loopFCN . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->finishing_cover_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $loopFCNew = '';
                        foreach (json_decode($d->finishing_cover_new, true) as $fcn) {
                            $loopFCNew .= '<b class="text-dark">' . $fcn . '</b>, ';
                        }
                        $html .= ' Finishing cover ' . $loopFCNew . 'ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->format_buku_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Format buku <b class="text-dark">' . DB::table('format_buku')->where('id',$d->format_buku_his)->first()->jenis_format . ' cm</b> diubah menjadi <b class="text-dark">' . DB::table('format_buku')->where('id',$d->format_buku_new)->first()->jenis_format . ' cm</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->format_buku_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Format buku <b class="text-dark">' . DB::table('format_buku')->where('id',$d->format_buku_new)->first()->jenis_format . ' cm</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->jilid_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Jilid <b class="text-dark">' . $d->jilid_his . '</b> diubah menjadi <b class="text-dark">' . $d->jilid_new . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->jilid_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Jilid <b class="text-dark">' . $d->jilid_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->tipografi_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Tipografi <b class="text-dark">' . $d->tipografi_his . '</b> diubah menjadi <b class="text-dark">' . $d->tipografi_new . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->tipografi_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Tipografi <b class="text-dark">' . $d->tipografi_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->warna_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Warna <b class="text-dark">' . $d->warna_his . '</b> diubah menjadi <b class="text-dark">' . $d->warna_new . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->warna_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Warna <b class="text-dark">' . $d->warna_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->desainer_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Desainer <b class="text-dark">'
                            . DB::table('users')->where('id', $d->desainer_his)->whereNull('deleted_at')->first()->nama .
                            '</b> diubah menjadi <b class="text-dark">' . DB::table('users')->where('id', $d->desainer_new)->whereNull('deleted_at')->first()->nama . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->desainer_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Desainer <b class="text-dark">'
                            . DB::table('users')->where('id', $d->desainer_new)->whereNull('deleted_at')->first()->nama .
                            '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                    if (!is_null($d->kelengkapan_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Kelengkapan <b class="text-dark">' . $d->kelengkapan_his . '</b> diubah menjadi <b class="text-dark">' . $d->kelengkapan_new . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->kelengkapan_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Kelengkapan <b class="text-dark">' . $d->kelengkapan_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->catatan_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Catatan <b class="text-dark">' . $d->catatan_his . '</b> diubah menjadi <b class="text-dark">' . $d->catatan_new . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->catatan_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Catatan <b class="text-dark">' . $d->catatan_new . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->contoh_cover_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Link contoh cover <a href="' . $d->contoh_cover_his . '" target="_blank" class="text-dark"><b>' . $d->contoh_cover_his . '</b></a> diubah menjadi <a href="' . $d->contoh_cover_new . '" target="_blank" class="text-dark"><b>' . $d->contoh_cover_new . '</b></a>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->contoh_cover_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Link contoh cover <a href="' . $d->contoh_cover_new . '" target="_blank" class="text-dark"><b>' . $d->contoh_cover_new . '</b></a> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->bullet_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $loopFC = '';
                        $loopFCN = '';
                        foreach (json_decode($d->bullet_his, true) as $fc) {
                            $loopFC .= '<span class="bullet"></span>' . $fc;
                        }
                        foreach (json_decode($d->bullet_new, true) as $fcn) {
                            $loopFCN .= '<span class="bullet"></span>' . $fcn;
                        }
                        $html .= ' Bullet <b class="text-dark">' . $loopFC . '</b> diubah menjadi <b class="text-dark">' . $loopFCN . '</b>.<br>';
                        $html .= '</span></div>';
                    } elseif (!is_null($d->bullet_new)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $loopFCNew = '';
                        foreach (json_decode($d->bullet_new, true) as $fcn) {
                            $loopFCNew .= '<span class="bullet"></span>' . $fcn;
                        }
                        $html .= ' Bullet ' . $loopFCNew . '</b> ditambahkan.<br>';
                        $html .= '</span></div>';
                    }
                    if (!is_null($d->bulan_his)) {
                        $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                        $html .= ' Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->bulan_new)->translatedFormat('F Y') . '</b>.';
                        $html .= '</span></div>';
                    }
                    $html .= '<div class="ticket-info">
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
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-descov" style="background:#34395E;color:white" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusDesCover" title="Update Status">
                        <div>' . $status . '</div></a>';
                break;
            case 'Pending':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-descov" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusDesCover" title="Update Status">
                        <div>' . $status . '</div></a>';
                break;
            case 'Proses':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-descov" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusDesCover" title="Update Status">
                        <div>' . $status . '</div></a>';
                break;
            case 'Selesai':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-light btn-icon mr-1 mt-1 btn-status-descov" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusDesCover" title="Update Status">
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
        $btn .= '<a href="' . url('penerbitan/deskripsi/cover/edit?desc=' . $id . '&kode=' . $kode) . '"
            class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
            <div><i class="fas fa-edit"></i></div></a>';
        return $btn;
    }
}
