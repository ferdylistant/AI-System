<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Events\EditingEvent;
use Illuminate\Http\Request;
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
                    'df.bullet'
                )
                ->orderBy('ep.tgl_mulai_edit', 'ASC')
                ->get();

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
                        foreach (json_decode($data->editor, true) as $q) {
                            $res .= '<span class="d-block">-&nbsp;' . DB::table('users')->where('id', $q)->whereNull('deleted_at')->first()->nama . '</span>';
                        }
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
                ->addColumn('copy_editor', function ($data) {
                    if (!is_null($data->copy_editor)) {
                        $res = '';
                        foreach (json_decode($data->copy_editor, true) as $q) {
                            $res .= '<span class="d-block">-&nbsp;' . DB::table('users')->where('id', $q)->whereNull('deleted_at')->first()->nama . '</span>';
                        }
                    } else {
                        $res = '-';
                    }
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
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('penerbitan/editing/detail?editing=' . $data->id . '&kode=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';

                    if ($data->jalur_buku == 'Reguler') { //REGULER
                        if (Gate::allows('do_create', 'ubah-atau-buat-editing-reguler')) {
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

                        if (Gate::allows('do_create', 'ubah-atau-buat-editing-reguler')) {
                            $btn = $this->panelStatusKabag($data->status, $data->id, $data->kode, $data->judul_final, $btn);
                        } else {
                            $btn = $this->panelStatusGuest($data->status, $btn);
                        }
                    } elseif ($data->jalur_buku == 'MOU') { //MOU
                        if (Gate::allows('do_create', 'ubah-atau-buat-editing-mou')) {
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

                        if (Gate::allows('do_create', 'ubah-atau-buat-editing-mou')) {
                            $btn = $this->panelStatusKabag($data->status, $data->id, $data->kode, $data->judul_final, $btn);
                        } else {
                            $btn = $this->panelStatusGuest($data->status, $btn);
                        }
                    } elseif ($data->jalur_buku == 'SMK/NonSMK') { //SMK
                        if (Gate::allows('do_create', 'ubah-atau-buat-editing-smk')) {
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

                        if (Gate::allows('do_create', 'ubah-atau-buat-editing-smk')) {
                            $btn = $this->panelStatusKabag($data->status, $data->id, $data->kode, $data->judul_final, $btn);
                        } else {
                            $btn = $this->panelStatusGuest($data->status, $btn);
                        }
                    } else {
                        if (Gate::allows('do_create', 'ubah-atau-buat-editing-reguler') || Gate::allows('do_create', 'ubah-atau-buat-editing-mou')) {
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

                        if (Gate::allows('do_create', 'ubah-atau-buat-editing-reguler') || Gate::allows('do_create', 'ubah-atau-buat-editing-mou')) {
                            $btn = $this->panelStatusKabag($data->status, $data->id, $data->kode, $data->judul_final, $btn);
                        } else {
                            $btn = $this->panelStatusGuest($data->status, $btn);
                        }
                    }
                    return $btn;
                })
                ->rawColumns([
                    'kode',
                    'judul_final',
                    'penulis',
                    'jalur_buku',
                    'tgl_masuk_editing',
                    'pic_prodev',
                    'editor',
                    'copy_editor',
                    'history',
                    'action'
                ])
                ->make(true);
        }
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
                'df.bullet'
            )
            ->orderBy('ep.tgl_mulai_edit', 'ASC')
            ->get();
        //Isi Warna Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM editing_proses WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        return view('penerbitan.editing.index', [
            'title' => 'Editing Proses',
            'status_progress' => $statusProgress,
            'count' => count($data)
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
                if ($request->has('copy_editor')) {
                    foreach ($request->copy_editor as $ce) {
                        $req_copyeditor[] = $ce;
                    }
                    $copyeditor = json_encode($req_copyeditor);
                } else {
                    $copyeditor = NULL;
                }
                if ($request->has('proses')) {
                    if ((json_decode($history->editor,true) != $request->editor) && (!is_null($history->tgl_mulai_edit))) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Hentikan proses untuk mengubah editor'
                        ]);
                    }
                    if ((json_decode($history->copy_editor,true) != $request->copy_editor) && (!is_null($history->tgl_mulai_edit))) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Hentikan proses untuk mengubah copy editor'
                        ]);
                    }
                    if ($request->has('editor')) {
                        if (json_decode($history->editor, true) == $request->editor) {
                            $tglEditor = $history->tgl_mulai_edit;
                        } else {
                            $tglEditor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        }
                    }  else {
                        $tglEditor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    }
                    if (is_null($history->tgl_selesai_edit)) {
                        $tglCopyEditor = $history->tgl_mulai_copyeditor;
                    } else {
                        if ($request->has('copy_editor')) {
                            if (json_decode($history->copy_editor, true) == $request->copy_editor) {
                                $tglCopyEditor = $history->tgl_mulai_copyeditor;
                            } else {
                                $tglCopyEditor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            }
                        } else {
                            $tglCopyEditor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        }
                    }
                    $proses = '1';
                } else {
                    $proses = '0';
                    $tglEditor = NULL;
                    $tglCopyEditor = NULL;
                    if ($request->has('editor')) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Harap switch tombol mulai proses editor di samping tombol update.'
                        ]);
                    } elseif ($request->has('copy_editor')) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Harap switch tombol mulai proses copy editor di samping tombol update.'
                        ]);
                    }
                }
                $update = [
                    'params' => 'Edit Editing',
                    'id' => $request->id,
                    'jml_hal_perkiraan' => $request->jml_hal_perkiraan, //Deskripsi Produk
                    'catatan' => $request->catatan,
                    'bullet' =>  json_encode(array_filter($request->bullet)), //Deskripsi Final
                    'editor' => json_encode($request->editor),
                    'tgl_mulai_edit' => $tglEditor,
                    'copy_editor' => $request->has('copy_editor') ? json_encode($request->copy_editor) : NULL,
                    'tgl_mulai_copyeditor' => $tglCopyEditor,
                    'proses' => $proses,
                    'bulan' => Carbon::createFromDate($request->bulan)
                ];
                event(new EditingEvent($update));

                $insert = [
                    'params' => 'Insert History Edit Editing',
                    'deskripsi_final_id' => $request->id,
                    'type_history' => 'Update',
                    'editor_his' => $history->editor == json_encode(array_filter($request->editor)) ? NULL : $history->editor,
                    'editor_new' => $history->editor == json_encode(array_filter($request->editor)) ? NULL : json_encode(array_filter($request->editor)),
                    'jml_hal_perkiraan_his' => $history->jml_hal_perkiraan == $request->jml_hal_perkiraan ? NULL : $history->jml_hal_perkiraan,
                    'jml_hal_perkiraan_new' => $history->jml_hal_perkiraan == $request->jml_hal_perkiraan ? NULL : $request->jml_hal_perkiraan,
                    'bullet_his' => $history->bullet == json_encode(array_filter($request->bullet)) ? NULL : $history->bullet,
                    'bullet_new' => $history->bullet == json_encode(array_filter($request->bullet)) ? NULL : json_encode(array_filter($request->bullet)),
                    'copy_editor_his' => $history->copy_editor == $copyeditor ? NULL : $history->copy_editor,
                    'copy_editor_new' => $history->copy_editor == $copyeditor ? NULL : $copyeditor,
                    'catatan_his' => $history->catatan == $request->catatan ? NULL : $history->catatan,
                    'catatan_new' => $history->catatan == $request->catatan ? NULL : $request->catatan,
                    'bulan_his' => $history->bulan == Carbon::createFromDate($request->bulan) ? NULL : Carbon::createFromFormat('Y-m-d', $history->bulan)->format('Y-m-d'),
                    'bulan_new' => $history->bulan == Carbon::createFromDate($request->bulan) ? NULL : Carbon::createFromDate($request->bulan),
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
            $copyEditor = DB::table('users as u')
                ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
                ->where('j.nama', 'LIKE', '%Editor%')
                ->where('d.nama', 'LIKE', '%Penerbitan%')
                ->whereNotIn('u.id', json_decode($data->editor))
                ->orWhere('j.nama', 'LIKE', '%Korektor%')
                ->select('u.nama', 'u.id')
                ->orderBy('u.nama', 'Asc')
                ->get();
        } else {
            $namaEditor = NULL;
            $copyEditor = NULL;
        }
        if (!is_null($data->copy_editor)) {
            foreach (json_decode($data->copy_editor) as $ce) {
                $namaCopyEditor[] = DB::table('users')->where('id', $ce)->first()->nama;
            }
        } else {
            $namaCopyEditor = NULL;
        }
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
            'copy_editor' => $copyEditor,
            'nama_copyeditor' => $namaCopyEditor,
            'penulis' => $penulis,
        ]);
    }
    public function detailEditing(Request $request)
    {
        $id = $request->get('editing');
        $kode = $request->get('kode');
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
                'dp.jml_hal_perkiraan',
                'pn.kode',
                'pn.jalur_buku',
                'pn.pic_prodev',
                'kb.nama'
            )
            ->first();
        if (is_null($data)) {
            return abort(404);
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();
        $pic = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')->select('nama')->first();
        if (!is_null($data->editor)) {
            foreach (json_decode($data->editor, true) as $ed) {
                $namaEditor[] = DB::table('users')->where('id', $ed)->first()->nama;
            }
        } else {
            $namaEditor = NULL;
        }
        if (!is_null($data->copy_editor)) {
            foreach (json_decode($data->copy_editor, true) as $cpe) {
                $namaCopyEditor[] = DB::table('users')->where('id', $cpe)->first()->nama;
            }
        } else {
            $namaCopyEditor = NULL;
        }
        return view('penerbitan.editing.detail', [
            'title' => 'Detail Editing Proses',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'nama_editor' => $namaEditor,
            'nama_copyeditor' => $namaCopyEditor
        ]);
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
                'params' => 'Update Status Editing',
                'id' => $data->id,
                'status' => $request->status,
            ];
            $insert = [
                'params' => 'Insert History Status Editing',
                'deskripsi_final_id' => $data->id,
                'type_history' => 'Status',
                'status_his' => $data->status,
                'status_new'  => $request->status,
                'author_id' => auth()->user()->id,
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            if ($request->status == 'Selesai') {
                // event(new DescovEvent($update));
                // event(new DescovEvent($insert));
                // $insertEditingProses = [
                //     'params' => 'Insert Editing',
                //     'id' => Uuid::uuid4()->toString(),
                //     'deskripsi_final_id' => $data->deskripsi_final_id,
                //     'editor' => json_encode([$data->editor]),
                //     'tgl_masuk_editing' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                // ];
                // event(new EditingEvent($insertEditingProses));
                // $insertPracetakSetter = [
                //     'params' => 'Insert Pracetak Setter',
                //     'id' => Uuid::uuid4()->toString(),
                //     'deskripsi_final_id' => $data->deskripsi_final_id,
                //     'setter' => json_encode([$data->setter]),
                //     'korektor_komp' => json_encode([$data->korektor]),
                //     'korektor_manual' => json_encode([$data->korektor]),
                //     'jml_hal_final' => $data->jml_hal_perkiraan,
                //     'tgl_masuk_pracetak' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                // ];
                // event(new PracetakSetterEvent($insertPracetakSetter));
                // $insertPracetakCover = [
                //     'params' => 'Insert Pracetak Cover',
                //     'id' => Uuid::uuid4()->toString(),
                //     'deskripsi_cover_id' => $data->id,
                //     'desainer' => json_encode([$data->desainer]),
                //     'editor' => json_encode([$data->editor]),
                //     'tgl_masuk_cover' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                // ];
                // event(new PracetakCoverEvent($insertPracetakCover));
                // $msg = 'Deskripsi cover selesai, silahkan lanjut ke proses Pracetak Cover dan Editing..';
            } else {
                event(new EditingEvent($update));
                event(new EditingEvent($insert));
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
                // if ($value == '0') {
                //     if (condition) {
                //         # code...
                //     }
                //     $tglEditor = NULL;
                //     $tglCopyEditor = NULL;
                // } else {

                // }
                return response()->json([
                    'status' => 'error',
                    'message' => $request->editor
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],500);
            }
        }
    }
}
