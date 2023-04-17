<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Events\EditingEvent;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class EditingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('editing_proses as ep')
                ->join('deskripsi_final as df', 'df.id', '=', 'ep.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->whereNull('dp.deleted_at')
                ->select(
                    'ep.*',
                    'pn.kode',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'pn.kelompok_buku_id',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'dp.nama_pena',
                    'df.bullet'
                )
                ->orderBy('ep.tgl_mulai_edit', 'ASC');
            if (in_array(auth()->id(), $data->pluck('pic_prodev')->toArray())) {
                $data->where('pn.pic_prodev', auth()->id());
            }
            if (Gate::allows('do_create', 'otorisasi-editor-editing-reguler') || Gate::allows('do_create', 'otorisasi-editor-editing-mou') || Gate::allows('do_create', 'otorisasi-editor-editing-smk')) {
                $data->where('ep.editor', 'like', '%"' . auth()->id() . '"%');
            }
            $data->get();
            if ($request->has('count_data')) {
                return $data->count();
            } else {
                return DataTables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('kode', function ($data) {
                        $tandaProses = '';
                        $dataKode = $data->kode;
                        if ($data->status == 'Proses') {
                            $tandaProses = $data->proses == '1' ? '<span class="beep-success"></span>' : '<span class="beep-danger"></span>';
                            $dataKode = $data->proses == '1'  ? '<span class="text-success">' . $data->kode . '</span>' : '<span class="text-danger">' . $data->kode . '</span>';
                        }
                        return $tandaProses . $dataKode;
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
                    ->addColumn('jalur_buku', function ($data) {
                        if (!is_null($data->jalur_buku)) {
                            $res = $data->jalur_buku;
                        } else {
                            $res = '-';
                        }
                        return $res;
                    })
                    ->addColumn('tgl_masuk_editing', function ($data) {
                        return Carbon::parse($data->tgl_masuk_editing)->translatedFormat('l d M Y H:i');
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
                    ->addColumn('editor', function ($data) {
                        if (!is_null($data->editor)) {
                            $res = '';
                            $tgl = '';
                            $edpros = DB::table('editing_proses_selesai')
                                ->where('type', 'Editor')
                                ->where('editing_proses_id', $data->id)
                                ->get();
                            if (!$edpros->isEmpty()) {
                                foreach ($edpros as $v) {
                                    if (in_array($v->users_id, json_decode($data->editor, true))) {
                                        $tgl = '(' . Carbon::parse($v->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')';
                                    }
                                }
                            }
                            foreach (json_decode($data->editor, true) as $q) {
                                $res .= '<span class="d-block">-&nbsp;' . DB::table('users')->where('id', $q)->whereNull('deleted_at')->first()->nama . ' <span class="text-success">' . $tgl . '</span></span>';
                            }
                        } else {
                            $res = '-';
                        }
                        return $res;
                    })
                    // ->addColumn('copy_editor', function ($data) {
                    //     if (!is_null($data->copy_editor)) {
                    //         $res = '';
                    //         $tgl = '';
                    //         $edpros = DB::table('editing_proses_selesai')
                    //         ->where('type','Copy Editor')
                    //         ->where('editing_proses_id',$data->id)
                    //         ->get();
                    //         if (!$edpros->isEmpty()) {
                    //             foreach ($edpros as $v) {
                    //                 if (in_array($v->users_id,json_decode($data->copy_editor, true))) {
                    //                     $tgl = '('.Carbon::parse($v->tgl_proses_selesai)->translatedFormat('l d M Y, H:i').')';
                    //                 }
                    //             }
                    //         }
                    //         foreach (json_decode($data->copy_editor, true) as $q) {
                    //             $res .= '<span class="d-block">-&nbsp;' . DB::table('users')->where('id', $q)->whereNull('deleted_at')->first()->nama . ' <span class="text-success">'.$tgl.'</span></span>';
                    //         }
                    //     } else {
                    //         $res = '-';
                    //     }
                    //     return $res;
                    // })
                    ->addColumn('history', function ($data) {
                        $historyData = DB::table('editing_proses_history')->where('editing_proses_id', $data->id)->get();
                        if ($historyData->isEmpty()) {
                            return '-';
                        } else {
                            $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                            return $date;
                        }
                    })
                    ->addColumn('action', function ($data) {
                        $btn = '<a href="' . url('penerbitan/editing/detail?editing=' . $data->id . '&kode=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                        switch ($data->jalur_buku) {
                            case 'Reguler':
                                $jb = 'reguler';
                                $btn = $this->logicPermissionAction($data->status, $jb, $data->pic_prodev, $data->id, $data->kode, $data->judul_final, $btn);
                                break;
                            case 'MoU':
                                $jb = 'mou';
                                $btn = $this->logicPermissionAction($data->status, $jb, $data->pic_prodev, $data->id, $data->kode, $data->judul_final, $btn);
                                break;
                            case 'SMK/NonSMK':
                                $jb = 'smk';
                                $btn = $this->logicPermissionAction($data->status, $jb, $data->pic_prodev, $data->id, $data->kode, $data->judul_final, $btn);
                                break;
                            default:
                                $jb = 'MoU-Reguler';
                                $btn = $this->logicPermissionAction($data->status, $jb, $data->pic_prodev, $data->id, $data->kode, $data->judul_final, $btn);
                                break;
                        }
                        return $btn;
                    })
                    ->rawColumns([
                        'kode',
                        'judul_final',
                        'penulis',
                        'nama_pena',
                        'jalur_buku',
                        'tgl_masuk_editing',
                        'pic_prodev',
                        'editor',
                        // 'copy_editor',
                        'history',
                        'action'
                    ])
                    ->make(true);
            }
        }
        //Isi Warna Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM editing_proses WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        $statusAction = Arr::except($statusProgress, ['4']);
        $statusProgress = Arr::sort($statusProgress);
        return view('penerbitan.editing.index', [
            'title' => 'Editing Proses',
            'status_progress' => $statusProgress,
            'status_action' => $statusAction,
        ]);
    }
    public function editEditing(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $history = DB::table('editing_proses as ep')
                    ->join('deskripsi_final as df', 'df.id', '=', 'ep.deskripsi_final_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->where('ep.id', $request->id)
                    ->select('ep.*', 'dp.judul_final', 'df.bullet', 'dp.jml_hal_perkiraan')
                    ->first();
                if ($request->has('request_revision')) {
                    return $this->donerevisionActKabag($history);
                } else {
                    // if ($request->has('copy_editor')) {
                    //     foreach ($request->copy_editor as $ce) {
                    //         $req_copyeditor[] = $ce;
                    //     }
                    //     $copyeditor = json_encode($req_copyeditor);
                    // } else {
                    //     $copyeditor = null;
                    // }
                    if ($history->proses == '1') {
                        if (is_null($history->tgl_selesai_edit)) {
                            if ((json_decode($history->editor, true) != $request->editor) && (!is_null($history->tgl_mulai_edit))) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Hentikan proses untuk mengubah editor'
                                ]);
                            }
                        } else {
                            if ((json_decode($history->copy_editor, true) != $request->copy_editor) && (!is_null($history->tgl_mulai_copyeditor))) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Hentikan proses untuk mengubah copy editor'
                                ]);
                            }
                        }
                    }
                    $update = [
                        'params' => 'Edit Editing',
                        'id' => $request->id,
                        'jml_hal_perkiraan' => $request->jml_hal_perkiraan, //Deskripsi Produk
                        'catatan' => $request->catatan,
                        'bullet' =>  json_encode(array_filter($request->bullet)), //Deskripsi Final
                        'editor' => json_encode($request->editor),
                        // 'copy_editor' => $request->has('copy_editor') ? json_encode($request->copy_editor) : null,
                        'bulan' => Carbon::createFromDate($request->bulan)
                    ];
                    event(new EditingEvent($update));

                    $insert = [
                        'params' => 'Insert History Edit Editing',
                        'editing_proses_id' => $request->id,
                        'type_history' => 'Update',
                        'editor_his' => $history->editor == json_encode(array_filter($request->editor)) ? null : $history->editor,
                        'editor_new' => $history->editor == json_encode(array_filter($request->editor)) ? null : json_encode(array_filter($request->editor)),
                        'jml_hal_perkiraan_his' => $history->jml_hal_perkiraan == $request->jml_hal_perkiraan ? null : $history->jml_hal_perkiraan,
                        'jml_hal_perkiraan_new' => $history->jml_hal_perkiraan == $request->jml_hal_perkiraan ? null : $request->jml_hal_perkiraan,
                        'bullet_his' => $history->bullet == json_encode(array_filter($request->bullet)) ? null : $history->bullet,
                        'bullet_new' => $history->bullet == json_encode(array_filter($request->bullet)) ? null : json_encode(array_filter($request->bullet)),
                        // 'copy_editor_his' => $history->copy_editor == $copyeditor ? null : $history->copy_editor,
                        // 'copy_editor_new' => $history->copy_editor == $copyeditor ? null : $copyeditor,
                        'catatan_his' => $history->catatan == $request->catatan ? null : $history->catatan,
                        'catatan_new' => $history->catatan == $request->catatan ? null : $request->catatan,
                        'bulan_his' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $history->bulan,
                        'bulan_new' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : Carbon::createFromDate($request->bulan),
                        'author_id' => auth()->id(),
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new EditingEvent($insert));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data editing proses berhasil ditambahkan',
                        'route' => route('editing.view')
                    ]);
                }
            }
        }
        $id = $request->get('editing');
        $kodenaskah = $request->get('kode');
        $data = DB::table('editing_proses as ep')
            ->join('deskripsi_final as df', 'ep.deskripsi_final_id', '=', 'df.id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('ep.id', $id)
            ->where('pn.kode', $kodenaskah)
            ->select(
                'ep.*',
                'df.sub_judul_final',
                'df.bullet',
                'df.sinopsis',
                'df.isi_warna',
                'df.isi_huruf',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.nama_pena',
                'dp.format_buku',
                'dp.jml_hal_perkiraan',
                'pn.kode',
                'pn.jalur_buku',
                'pn.pic_prodev',
                'kb.nama',
            )
            ->first();
        is_null($data) ? abort(404) :
            $editor = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Editor%')
            ->where('d.nama', 'LIKE', '%Penerbitan%')
            ->select('u.nama', 'u.id')
            ->orderBy('u.nama', 'Asc')
            ->get();
        if (!is_null($data->editor)) {
            foreach (json_decode($data->editor) as $e) {
                $namaEditor[] = DB::table('users')->where('id', $e)->first()->nama;
            }
            // $copyEditor = DB::table('users as u')
            //     ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            //     ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            //     ->where('j.nama', 'LIKE', '%Editor%')
            //     ->where('d.nama', 'LIKE', '%Penerbitan%')
            //     ->whereNotIn('u.id', json_decode($data->editor))
            //     ->orWhere('j.nama', 'LIKE', '%Korektor%')
            //     ->select('u.nama', 'u.id')
            //     ->orderBy('u.nama', 'Asc')
            //     ->get();
        } else {
            $namaEditor = null;
            // $copyEditor = null;
        }
        // if (!is_null($data->copy_editor)) {
        //     foreach (json_decode($data->copy_editor) as $ce) {
        //         $namaCopyEditor[] = DB::table('users')->where('id', $ce)->first()->nama;
        //     }
        // } else {
        //     $namaCopyEditor = null;
        // }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();
        return view('penerbitan.editing.edit', [
            'title' => 'Editing Proses',
            'data' => $data,
            'editor' => $editor,
            'nama_editor' => $namaEditor,
            // 'copy_editor' => $copyEditor,
            // 'nama_copyeditor' => $namaCopyEditor,
            'penulis' => $penulis,
        ]);
    }
    public function detailEditing(Request $request)
    {
        $id = $request->editing;
        $kode = $request->kode;
        $data = DB::table('editing_proses as ep')
            ->join('deskripsi_final as df', 'df.id', '=', 'ep.deskripsi_final_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('ep.id', $id)
            ->where('pn.kode', $kode)
            ->select(
                'ep.*',
                'df.sub_judul_final',
                'df.bullet',
                'df.sinopsis',
                'df.isi_warna',
                'df.isi_huruf',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.nama_pena',
                'dp.jml_hal_perkiraan',
                'pn.kode',
                'pn.jalur_buku',
                'pn.pic_prodev',
                'kb.nama'
            )
            ->first();

        if ($request->ajax()) {
            if (is_null($data)) {
                return abort(404);
            }
            if ($request->isMethod('GET')) {
                switch ($request->request_) {
                    case 'load-data':
                        //load data
                        return $this->loadData($data);
                        break;
                    case 'show-modal-revisi':
                        //show modal revisi
                        return $this->showModal($data);
                        break;
                    default:
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Request tidak ditemukan',
                        ]);
                        break;
                }
            } else {
                //submit revisi
                return $this->submitRevisi($request, $data);
            }
        }
        return view('penerbitan.editing.detail', [
            'title' => 'Detail Editing Proses',
        ]);
    }
    protected function loadData($data)
    {
        try {
            $useData = $data;
            $data = collect($data)->put('penulis', DB::table('penerbitan_naskah_penulis as pnp')
                ->join('penerbitan_penulis as pp', function ($q) {
                    $q->on('pnp.penulis_id', '=', 'pp.id')
                        ->whereNull('pp.deleted_at');
                })
                ->where('pnp.naskah_id', '=', $data->naskah_id)
                ->select('pp.id', 'pp.nama')
                ->get());
            $data = (object)collect($data)->map(function ($item, $key) use ($useData) {
                switch ($key) {
                    case 'proses':
                        $html = '';
                        if ($useData->status == 'Revisi') {
                            $html .= '<div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon" id="btn-revisi-editing" data-ajax="false" data-id="' . $useData->id . '" title="Keterangan Revisi">
                                    <i class="fas fa-eye"></i>&nbsp;Keterangan Revisi</a>
                                </div>
                            </div>';
                        } else {
                            if ($item == '1') {
                                $label = "editor";
                                // $label = is_null($data->tgl_selesai_edit)?"editor":"copyeditor";
                                $dataRole  = $useData->editor;
                                // $dataRole  = is_null($data->tgl_selesai_edit)?$data->editor:$data->copy_editor;
                                switch ($useData->jalur_buku) {
                                    case 'Reguler':
                                        foreach (json_decode($dataRole, true) as $edt) {
                                            if (auth()->id() == $edt) {
                                                if (Gate::allows('do_create', 'otorisasi-' . $label . '-editing-reguler')) {
                                                    $html .= '<div class="col-auto">
                                                        <div class="mb-4">
                                                            <button type="submit" class="btn btn-success"
                                                                id="btn-done-editing" data-ajax="false" data-id="' . $useData->id . '"
                                                                data-kode="' . $useData->kode . '" data-autor="' . $label . '">
                                                                <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                                            <button type="button" class="btn btn-danger" id="btn-revisi-editing" data-id="' . $useData->id . '" data-ajax="false">
                                                                    <i class="fas fa-tools"></i>&nbsp;Revisi</button>
                                                        </div>
                                                    </div>';
                                                }
                                            }
                                        }
                                        break;

                                    case 'MoU':
                                        foreach (json_decode($dataRole) as $edt) {
                                            if (auth()->id() == $edt) {
                                                if (Gate::allows('do_create', 'otorisasi-' . $label . '-editing-mou')) {
                                                    $html .= '<div class="col-auto">
                                                            <div class="mb-4">
                                                            <button type="submit" class="btn btn-success"
                                                                id="btn-done-editing" data-id="' . $useData->id . '"
                                                                data-kode="' . $useData->kode . '" data-autor="' . $label . '">
                                                                <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                                        </div>
                                                    </div>';
                                                }
                                            }
                                        }
                                        break;

                                    case 'MoU-Reguler':
                                        foreach (json_decode($dataRole) as $edt) {
                                            if (auth()->id() == $edt) {
                                                if (
                                                    Gate::allows('do_create', 'otorisasi-' . $label . '-editing-reguler') ||
                                                    Gate::allows('do_create', 'otorisasi-' . $label . '-editing-mou')
                                                ) {
                                                    $html .= '<div class="col-auto">
                                                        <div class="mb-4">
                                                            <button type="submit" class="btn btn-success"
                                                                id="btn-done-editing" data-id="' . $useData->id . '"
                                                                data-kode="' . $useData->kode . '" data-autor="' . $label . '">
                                                                <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                                        </div>
                                                    </div>';
                                                }
                                            }
                                        }
                                        break;

                                    case 'SMK/NonSMK':
                                        foreach (json_decode($dataRole) as $edt) {
                                            if (auth()->id() == $edt) {
                                                if (Gate::allows('do_create', 'otorisasi-' . $label . '-editing-smk')) {
                                                    $html .= '<div class="col-auto">
                                                        <div class="mb-4">
                                                            <button type="submit" class="btn btn-success"
                                                                id="btn-done-editing" data-id="' . $useData->id . '"
                                                                data-kode="' . $useData->kode . '" data-autor="' . $label . '">
                                                                <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                                        </div>
                                                    </div>';
                                                }
                                            }
                                        }
                                        break;

                                    case 'Pro Literasi':
                                        foreach (json_decode($dataRole) as $edt) {
                                            if (auth()->id() == $edt) {
                                                if (
                                                    Gate::allows('do_create', 'otorisasi-' . $label . '-editing-reguler') ||
                                                    Gate::allows('do_create', 'otorisasi-editor-editing-mou')
                                                ) {
                                                }
                                            }
                                        }
                                        break;
                                }
                            }
                        }
                        return $html;
                        break;
                    case 'status':
                        $html = '';
                        switch ($item) {
                            case 'Antrian':
                                $html .= '<span class="badge" style="background:#34395E;color:white">' . $item . '</span>';
                                break;

                            case 'Pending':
                                $html .= '<span class="badge badge-danger">' . $item . '</span>';
                                break;

                            case 'Proses':
                                $html .= '<span class="badge badge-success">' . $item . '</span>';
                                break;

                            case 'Selesai':
                                $html .= '<span class="badge badge-light">' . $item . '</span>';
                                break;
                            case 'Revisi':
                                $html .= '<span class="badge badge-info">' . $item . '</span>';
                                break;
                        }
                        $proses = '';
                        if ($useData->proses == '1') {
                            $label = is_null($useData->tgl_selesai_edit) ? 'editor' : 'copy editor';
                            $proses .= '<span class="text-danger"><i class="fas fa-exclamation-circle"></i>&nbsp;Sedang proses
                                    pengerjaan ' . $label . '</span>';
                        }
                        return ['status' => $html, 'proses' => $proses];
                        break;
                    case 'bullet':
                        $html = '';
                        if (!is_null($item) && $item != '[]') {
                            foreach (json_decode($item, true) as $key => $value) {
                                $html .= '<span class="bullet">' . $value . '</span>';
                            }
                        } else {
                            $html .= '-';
                        }
                        return $html;
                        break;
                    case 'penulis':
                        $html = '';
                        if (!is_null($item)) {
                            foreach ($item as $key => $value) {
                                $html .= '<span class="bullet"></span>
                                <a href="' . url('/penerbitan/penulis/detail-penulis/' . $value->id) . '">' . $value->nama . '</a>';
                            }
                        } else {
                            $html .= '-';
                        }
                        return $html;
                        break;
                    case 'nama_pena':
                        $html = '';
                        if (!is_null($item)) {
                            foreach (json_decode($item, true) as $key => $value) {
                                $html .= '<span class="bullet"></span>' . $value ;
                            }
                        } else {
                            $html .= '-';
                        }
                        return $html;
                        break;
                    case 'pic_prodev':
                        $html = '';
                        $pic = DB::table('users')->where('id', $item)->whereNull('deleted_at')->select('id', 'nama')->first();
                        if (!is_null($item)) {
                            $html .= '<a href="' . url('/manajemen-web/user/' . $pic->id) . '">' . $pic->nama . '</a>';
                        } else {
                            $html .= '-';
                        }
                        return $html;
                        break;
                    case 'tgl_masuk_editing':
                        return $item ? Carbon::parse($item)->translatedFormat('l d F Y, H:i') : '-';
                        break;
                    case 'tgl_mulai_edit':
                        return $item ? Carbon::parse($item)->translatedFormat('l d F Y, H:i') : '-';
                        break;
                    case 'tgl_selesai_edit':
                        return $item ? Carbon::parse($item)->translatedFormat('l d F Y, H:i') : '-';
                        break;
                    case 'tgl_selesai_edit':
                        return $item ? Carbon::parse($item)->translatedFormat('F Y') : '-';
                        break;
                    case 'editor':
                        $html = '';
                        if (!is_null($item)) {
                            $item = collect($item)->map(function ($item) {
                                $return = DB::table('users')->where('id', json_decode($item))->whereNull('deleted_at')->select('id', 'nama')->first();
                                return $return;
                            })->all();
                            foreach ($item as $key => $value) {
                                $html .= '<span class="bullet"></span>
                            <a href="' . url('/manajemen-web/user/' . $value->id) . '">' . $value->nama . '</a><br>';
                            }
                        } else {
                            $html .= '-';
                        }
                        return $html;
                        break;
                    default:
                        return $item ? $item : '-';
                        break;
                }
            })->all();
            return $data;
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
            $data = DB::table('editing_proses as ep')
                ->join('deskripsi_final as df', 'df.id', '=', 'ep.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                    $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                        ->whereNull('kb.deleted_at');
                })
                ->where('ep.id', $id)
                ->select(
                    'ep.*',
                    'df.id as deskripsi_final_id',
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
                'params' => 'Update Status Editing',
                'id' => $data->id,
                'tgl_selesai_proses' => $request->status == 'Selesai' ? $tgl : NULL,
                'turun_pracetak' => $request->status == 'Selesai' ? $tgl : NULL,
                'status' => $request->status,
            ];
            $insert = [
                'params' => 'Insert History Status Editing',
                'editing_proses_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl
            ];
            if ($data->proses == '1') {
                $label = is_null($data->tgl_selesai_edit) ? 'editor' : 'copy editor';
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak bisa mengubah status, karena sedang proses kerja ' . $label
                ]);
            }
            if ($request->status == 'Selesai') {
                if (is_null($data->tgl_selesai_edit)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Editor belum selesai'
                    ]);
                }
                // if (is_null($data->tgl_selesai_copyeditor)) {
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'Copy Editor belum selesai'
                //     ]);
                // }
                $desfin = DB::table('deskripsi_final')->where('id', $data->deskripsi_final_id)->whereNull('sinopsis')->first();
                if ($desfin) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sinopsis (bagian: Deskripsi Final) belum ditambahkan!'
                    ]);
                }
                event(new EditingEvent($update));
                event(new EditingEvent($insert));
                DB::table('pracetak_setter')->where('deskripsi_final_id', $data->deskripsi_final_id)->update([
                    'jml_hal_final' => $data->jml_hal_perkiraan
                ]);
                $updateTimelineEditing = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Editing',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineEditing));
                $msg = 'Proses editing selesai, proses akan dilanjukan ke tahap pracetak..';
            } else {
                event(new EditingEvent($update));
                event(new EditingEvent($insert));
                $updateTimelineEditing = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Editing',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelineEditing));
                $msg = 'Status progress editing proses berhasil diupdate';
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
    public function lihatHistoryEditing(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('editing_proses_history as eph')
                ->join('editing_proses as ep', 'ep.id', '=', 'eph.editing_proses_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ep.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('users as u', 'eph.author_id', '=', 'u.id')
                ->where('eph.editing_proses_id', $id)
                ->select('eph.*', 'dp.judul_final', 'u.nama')
                ->orderBy('eph.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status editing proses <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">';
                        if (!is_null($d->editor_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $loopEDHIS = '';
                            $loopEDNEW = '';
                            foreach (json_decode($d->editor_his, true) as $edhis) {
                                $loopEDHIS .= '<b class="text-dark">' . DB::table('users')->where('id', $edhis)->first()->nama . '</b>, ';
                            }
                            foreach (json_decode($d->editor_new, true) as $ednew) {
                                $loopEDNEW .= '<span class="bullet"></span>' . DB::table('users')->where('id', $ednew)->first()->nama;
                            }
                            $html .= ' Editor <b class="text-dark">' . $loopEDHIS . '</b> diubah menjadi <b class="text-dark">' . $loopEDNEW . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->editor_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $loopEDNEW = '';
                            foreach (json_decode($d->editor_new, true) as $ednew) {
                                $loopEDNEW .= '<b class="text-dark">' . DB::table('users')->where('id', $ednew)->first()->nama . '</b>, ';
                            }
                            $html .= ' Editor <b class="text-dark">' . $loopEDNEW . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        // if (!is_null($d->copy_editor_his)) {
                        //     $loopCEDHIS = '';
                        //     $loopCEDNEW = '';
                        //     foreach (json_decode($d->copy_editor_his, true) as $cedhis) {
                        //         $loopCEDHIS .= '<b class="text-dark">' . DB::table('users')->where('id', $cedhis)->first()->nama . '</b>, ';
                        //     }
                        //     foreach (json_decode($d->copy_editor_new, true) as $cednew) {
                        //         $loopCEDNEW .= '<span class="bullet"></span>' . DB::table('users')->where('id', $cednew)->first()->nama;
                        //     }
                        //     $html .= ' Copy Editor <b class="text-dark">' . $loopCEDHIS . '</b> diubah menjadi <b class="text-dark">' . $loopCEDNEW . '</b>.<br>';
                        // } elseif (!is_null($d->copy_editor_new)) {
                        //     $loopCEDNEW = '';
                        //     foreach (json_decode($d->copy_editor_new, true) as $cednew) {
                        //         $loopCEDNEW .= '<b class="text-dark">' . DB::table('users')->where('id', $cednew)->first()->nama . '</b>, ';
                        //     }
                        //     $html .= ' Editor <b class="text-dark">' . $loopCEDNEW . '</b> ditambahkan.<br>';
                        // }
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
                        if (!is_null($d->jml_hal_perkiraan_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_perkiraan_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_hal_perkiraan_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->jml_hal_perkiraan_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_perkiraan_new . '</b> ditambahkan.';
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
                    case 'Progress':
                        $ket = $d->progress == 1 ? 'Dimulai' : 'Dihentikan';
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Progress editing <b class="text-dark">' . $ket . '</b>.</span>
                        </div>
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
    protected function logicPermissionAction($status = null, $jb = null, $pic_prodev, $id, $kode, $judul_final, $btn)
    {
        switch ($jb) {
            case 'MoU-Reguler':
                $gate = Gate::allows('do_create', 'ubah-atau-buat-editing-reguler') || Gate::allows('do_create', 'ubah-atau-buat-editing-mou');
                break;
            default:
                $gate = Gate::allows('do_create', 'ubah-atau-buat-editing-' . $jb);
                break;
        }
        if ($gate) {
            if ($status == 'Selesai') {
                if (Gate::allows('do_approval', 'approval-deskripsi-produk')) {
                    $btn = $this->buttonEdit($id, $kode, $btn);
                }
            } else {
                if ((auth()->id() == $pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') || (Gate::allows('do_approval', 'approval-deskripsi-produk')) || ($gate)) {
                    $btn = $this->buttonEdit($id, $kode, $btn);
                }
            }
        }

        if ($gate) {
            $btn = $this->panelStatusKabag($status, $id, $kode, $judul_final, $btn);
        } else {
            $btn = $this->panelStatusGuest($status, $btn);
        }
        return $btn;
    }
    protected function panelStatusGuest($status = null, $btn)
    {
        switch ($status) {
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
        return $btn;
    }
    protected function panelStatusKabag($status = null, $id, $kode, $judul_final, $btn)
    {
        switch ($status) {
            case 'Antrian':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-editing" style="background:#34395E;color:white" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusEditing" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Pending':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-editing" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusEditing" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Proses':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-editing" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusEditing" title="Update Status">
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
        return $btn;
    }
    protected function buttonEdit($id, $kode, $btn)
    {
        $btn .= '<a href="' . url('penerbitan/editing/edit?editing=' . $id . '&kode=' . $kode) . '"
                class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                <div><i class="fas fa-edit"></i></div></a>';
        return $btn;
    }
    public function prosesKerjaEditing(Request $request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->id;
                $value = $request->proses;
                $data = DB::table('editing_proses')->where('id', $id)->first();
                if (is_null($data)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'naskah sudah tidak ada'
                    ]);
                }
                if ($value == '0') {
                    if (is_null($data->tgl_selesai_edit)) {
                        $dataProgress = [
                            'params' => 'Progress Editor',
                            'id' => $id,
                            'tgl_mulai_edit' => null,
                            'proses' => $value,
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        event(new EditingEvent($dataProgress));
                    }
                    // else {
                    //     $dataProgress = [
                    //         'params' => 'Progress Copy Editor',
                    //         'id' => $id,
                    //         'tgl_mulai_copyeditor' => null,
                    //         'proses' => $value,
                    //         'type_history' => 'Progress',
                    //         'author_id' => auth()->id(),
                    //         'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    //     ];
                    // }

                    $msg = 'Proses diberhentikan!';
                } else {
                    if (is_null($data->tgl_selesai_edit)) {
                        if (!$request->has('editor')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih editor terlebih dahulu!'
                            ]);
                        } else {
                            if (json_decode($data->editor, true) == $request->editor) {
                                if (is_null($data->tgl_mulai_edit)) {
                                    $tglEditor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                                } else {
                                    $tglEditor = $data->tgl_mulai_edit;
                                }
                            } else {
                                $tglEditor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            }
                            $dataEditor = [
                                'params' => 'Progress Editor',
                                'id' => $id,
                                'tgl_mulai_edit' => $tglEditor,
                                'proses' => $value,
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new EditingEvent($dataEditor));
                        }
                    }
                    // else {
                    //     if (!$request->has('copy_editor')) {
                    //         return response()->json([
                    //             'status' => 'error',
                    //             'message' => 'Pilih copy editor terlebih dahulu!'
                    //         ]);
                    //     } else {
                    //         if (json_decode($data->copy_editor, true) == $request->copy_editor) {
                    //             if (is_null($data->tgl_mulai_copyeditor)) {
                    //                 $tglCopyEditor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    //             } else {
                    //                 $tglCopyEditor = $data->tgl_mulai_copyeditor;
                    //             }
                    //         } else {
                    //             $tglCopyEditor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    //         }
                    //         $dataCopyEditor = [
                    //             'params' => 'Progress Copy Editor',
                    //             'id' => $id,
                    //             'tgl_mulai_copyeditor' => $tglCopyEditor,
                    //             'proses' => $value,
                    //             'type_history' => 'Progress',
                    //             'author_id' => auth()->id(),
                    //             'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    //         ];
                    //         event(new EditingEvent($dataCopyEditor));
                    //     }
                    // }
                    $msg = 'Proses dimulai!';
                }
                return response()->json([
                    'status' => 'success',
                    'message' => $msg
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }
    public function prosesSelesaiEditing($autor, $id)
    {
        switch ($autor) {
            case 'editor':
                return $this->selesaiEditor($id);
                break;
                // case 'copyeditor':
                //     return $this->selesaiCopyEditor($id);
                //     break;
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
                break;
        }
    }
    protected function showModal($data)
    {
        try {
            $html = '';
            switch ($data->status) {
                case 'Proses':
                    $html .= "<div class='form-group'>
                        <label for='ket_revisi' class='col-form-label'>Alasan: <span class='text-danger'>*</span></label>
                        <textarea class='form-control' name='ket_revisi' id='ket_revisi' rows='4'></textarea>
                        <div id='err_ket_revisi'></div>
                        </div>";
                    $title = '<i class="fas fa-times"></i>&nbsp;REVISI DATA EDITING ' . $data->kode . "-" . $data->judul_final;
                    $footer = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi</button>';
                    break;
                case 'Revisi':
                    $ketRevisi = DB::table('editing_proses_selesai')
                        ->where('type', 'Editor')
                        ->where('editing_proses_id', $data->id)
                        ->whereNotNull('ket_revisi')
                        ->first();
                    $html .= "<div class='form-group mb-2'>
                        <label for='decline_by'>Oleh:</label>
                        <p id='decline_by'>" . DB::table('users')->where('id', $ketRevisi->users_id)->first()->nama . "</p></div>
                        <hr>
                        <div class='form-group mb-2'>
                        <label for='decline'>Dilakukan pada:</label>
                        <p id='decline'>" . Carbon::parse($ketRevisi->tgl_proses_selesai)->translatedFormat('l d F Y, H:i') . "</p></div>
                        <hr>
                        <div class='form-group mb-2'>
                        <label for='keterangan_decline'>Alasan:</label>
                        <p id='keterangan_decline'>" . $ketRevisi->ket_revisi . "</p></div>";
                    $title = '<i class="fas fa-times"></i>&nbsp;ORDER BUKU ' . $data->kode . "-" . $data->judul_final . ', REVISI';
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
    protected function submitRevisi($request, $data)
    {
        try {
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $ket_revisi = $request->ket_revisi;
            DB::beginTransaction();
            $pros = [
                'params' => 'Revisi',
                'type' => 'Editor',
                'editing_proses_id' => $data->id,
                'users_id' => auth()->id(),
                'ket_revisi' => $ket_revisi,
                'tgl_proses_selesai' => $tgl
            ];
            event(new EditingEvent($pros));
            DB::table('editing_proses')->where('id', $data->id)
                ->update([
                    'status' => 'Revisi',
                    'tgl_mulai_edit' => NULL,
                    'tgl_selesai_edit' => NULL
                ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil direvisi!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
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
            $pros = [
                'params' => 'Revisi',
                'type' => 'Kabag',
                'editing_proses_id' => $data->id,
                'users_id' => auth()->id(),
                'ket_revisi' => NULL,
                'tgl_proses_selesai' => $tgl
            ];
            event(new EditingEvent($pros));
            DB::table('editing_proses')->where('id', $data->id)->update([
                'tgl_mulai_edit' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'status' => 'Proses',
                'proses' => '1'
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data editing telah selesai direvisi! Selanjutnya akan dikembalikan ke Editor.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function selesaiEditor($id)
    {
        try {
            $data = DB::table('editing_proses')->where('id', $id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data editing tidak ada'
                ], 404);
            }
            $dataProsesSelf = DB::table('editing_proses_selesai')
                ->where('editing_proses_id', $data->id)
                ->orderBy('id', 'desc');
            if ($dataProsesSelf->first()->type != 'Kabag') {
                if ($dataProsesSelf->first()->users_id == auth()->id()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda sudah selesai melakukan editing'
                    ]);
                }
                $dataProses = DB::table('editing_proses_selesai')
                    ->where('type', 'Editor')
                    ->where('editing_proses_id', $data->id)
                    ->get();
                $edPros = count($dataProses) + 1;
                if ($edPros == count(json_decode($data->editor, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Selesai',
                        'type' => 'Editor',
                        'editing_proses_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new EditingEvent($pros));
                    $done = [
                        'params' => 'Editing Selesai',
                        'id' => $data->id,
                        'tgl_selesai_edit' => $tgl,
                        'proses' => '0'
                    ];
                    event(new EditingEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Selesai',
                        'type' => 'Editor',
                        'editing_proses_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new EditingEvent($pros));
                }
            } else {
                $dataProses = DB::table('editing_proses_selesai')
                    ->where('id', '>', $dataProsesSelf->first()->id)
                    ->where('type', 'Editor')
                    ->where('editing_proses_id', $data->id)
                    ->get();
                $edPros = count($dataProses) + 1;
                if ($edPros == count(json_decode($data->editor, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Selesai',
                        'type' => 'Editor',
                        'editing_proses_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new EditingEvent($pros));
                    $done = [
                        'params' => 'Editing Selesai',
                        'id' => $data->id,
                        'tgl_selesai_edit' => $tgl,
                        'proses' => '0'
                    ];
                    event(new EditingEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Selesai',
                        'type' => 'Editor',
                        'editing_proses_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new EditingEvent($pros));
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pengerjaan selesai'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    // protected function selesaiCopyEditor($id)
    // {
    //     try {
    //         $data = DB::table('editing_proses')->where('id',$id)->first();
    //         if (is_null($data)) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Data editing tidak ada'
    //             ],404);
    //         }
    //         $dataProsesSelf = DB::table('editing_proses_selesai')
    //         ->where('type','Copy Editor')
    //         ->where('editing_proses_id',$data->id)
    //         ->where('users_id',auth()->id())
    //         ->get();
    //         if (!$dataProsesSelf->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Anda sudah selesai melakukan koreksi'
    //             ]);
    //         }
    //         $dataProses = DB::table('editing_proses_selesai')
    //         ->where('type','Copy Editor')
    //         ->where('editing_proses_id',$data->id)
    //         ->get();
    //         $edPros = count($dataProses) + 1;
    //         if ($edPros == count(json_decode($data->copy_editor,true))) {
    //             $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
    //             $pros = [
    //                 'params' => 'Proses Selesai',
    //                 'type' => 'Copy Editor',
    //                 'editing_proses_id' => $data->id,
    //                 'users_id' => auth()->id(),
    //                 'tgl_proses_selesai' => $tgl
    //             ];
    //             event(new EditingEvent($pros));
    //             $done = [
    //                 'params' => 'Copy Editing Selesai',
    //                 'id' => $data->id,
    //                 'tgl_selesai_copyeditor' => $tgl,
    //                 'proses' => '0'
    //             ];
    //             event(new EditingEvent($done));
    //         } else {
    //             $pros = [
    //                 'params' => 'Proses Selesai',
    //                 'type' => 'Copy Editor',
    //                 'editing_proses_id' => $data->id,
    //                 'users_id' => auth()->id(),
    //                 'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
    //             ];
    //             event(new EditingEvent($pros));
    //         }
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Pengerjaan selesai'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }
}
