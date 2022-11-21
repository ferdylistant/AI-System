<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class PracetakSetterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->whereNull('dp.deleted_at')
                ->select(
                    'ps.*',
                    'pn.kode',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'pn.kelompok_buku_id',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'df.bullet'
                )
                ->orderBy('ps.tgl_masuk_pracetak', 'ASC')
                ->get();

            return DataTables::of($data)
                // ->addIndexColumn()
                ->addColumn('kode', function ($data) {
                    $tandaProses = '';
                    $dataKode = $data->kode;
                    if ($data->status == 'Proses') {
                        $tandaProses = $data->proses == '1' ? '<span class="beep-success"></span>' : '<span class="beep-danger"></span>';
                        $dataKode = $data->proses == '1'  ? '<span class="text-success">'.$data->kode.'</span>':'<span class="text-danger">'.$data->kode.'</span>';
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
                ->addColumn('jalur_buku', function ($data) {
                    if (!is_null($data->jalur_buku)) {
                        $res = $data->jalur_buku;
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
                ->addColumn('tgl_masuk_pracetak', function ($data) {
                    return Carbon::parse($data->tgl_masuk_pracetak)->translatedFormat('l d M Y H:i');
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
                ->addColumn('setter', function ($data) {
                    if (!is_null($data->setter)) {
                        $res = '';
                        $tgl = '';
                        $setterVar = DB::table('pracetak_setter_selesai')
                        ->where('type','Setter')
                        ->where('pracetak_setter_id',$data->id)
                        ->get();
                        if (!$setterVar->isEmpty()) {
                            foreach ($setterVar as $v) {
                                if (in_array($v->users_id,json_decode($data->setter, true))) {
                                    $tgl = '('.Carbon::parse($v->tgl_proses_selesai)->translatedFormat('l d M Y, H:i').')';
                                }
                            }
                        }
                        foreach (json_decode($data->setter, true) as $q) {
                            $res .= '<span class="d-block">-&nbsp;' . DB::table('users')->where('id', $q)->whereNull('deleted_at')->first()->nama . ' <span class="text-success">'.$tgl.'</span></span>';
                        }
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
                ->addColumn('korektor', function ($data) {
                    if (!is_null($data->korektor)) {
                        $res = '';
                        $tgl = '';
                        $korVar = DB::table('pracetak_setter_selesai')
                        ->where('type','Korektor')
                        ->where('pracetak_setter_id',$data->id)
                        ->get();
                        if (!$korVar->isEmpty()) {
                            foreach ($korVar as $v) {
                                if (in_array($v->users_id,json_decode($data->korektor, true))) {
                                    $tgl = '('.Carbon::parse($v->tgl_proses_selesai)->translatedFormat('l d M Y, H:i').')';
                                }
                            }
                        }
                        foreach (json_decode($data->korektor, true) as $q) {
                            $res .= '<span class="d-block">-&nbsp;' . DB::table('users')->where('id', $q)->whereNull('deleted_at')->first()->nama . ' <span class="text-success">'.$tgl.'</span></span>';
                        }
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
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
                    $btn = '<a href="' . url('penerbitan/pracetak/setter/detail?setter=' . $data->id . '&kode=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';

                    if ($data->jalur_buku == 'Reguler') { //REGULER
                        if (Gate::allows('do_create', 'ubah-atau-buat-setter-reguler')) {
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

                        if (Gate::allows('do_create', 'ubah-atau-buat-setter-reguler')) {
                            $btn = $this->panelStatusKabag($data->status, $data->id, $data->kode, $data->judul_final, $btn);
                        } else {
                            $btn = $this->panelStatusGuest($data->status, $btn);
                        }
                    } elseif ($data->jalur_buku == 'MOU') { //MOU
                        if (Gate::allows('do_create', 'ubah-atau-buat-setter-mou')) {
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

                        if (Gate::allows('do_create', 'ubah-atau-buat-setter-mou')) {
                            $btn = $this->panelStatusKabag($data->status, $data->id, $data->kode, $data->judul_final, $btn);
                        } else {
                            $btn = $this->panelStatusGuest($data->status, $btn);
                        }
                    } elseif ($data->jalur_buku == 'SMK/NonSMK') { //SMK
                        if (Gate::allows('do_create', 'ubah-atau-buat-setter-smk')) {
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

                        if (Gate::allows('do_create', 'ubah-atau-buat-setter-smk')) {
                            $btn = $this->panelStatusKabag($data->status, $data->id, $data->kode, $data->judul_final, $btn);
                        } else {
                            $btn = $this->panelStatusGuest($data->status, $btn);
                        }
                    } else {
                        if (Gate::allows('do_create', 'ubah-atau-buat-setter-reguler') || Gate::allows('do_create', 'ubah-atau-buat-setter-mou')) {
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

                        if (Gate::allows('do_create', 'ubah-atau-buat-setter-reguler') || Gate::allows('do_create', 'ubah-atau-buat-setter-mou')) {
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
                    'tgl_masuk_pracetak',
                    'pic_prodev',
                    'setter',
                    'korektor',
                    'history',
                    'action'
                ])
                ->make(true);
        }
        $data = DB::table('pracetak_setter as ps')
            ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->whereNull('dp.deleted_at')
            ->select(
                'ps.*',
                'pn.kode',
                'pn.pic_prodev',
                'pn.jalur_buku',
                'pn.kelompok_buku_id',
                'dp.naskah_id',
                'dp.judul_final',
                'df.bullet'
            )
            ->orderBy('ps.tgl_masuk_pracetak', 'ASC')
            ->get();
        //Isi Warna Enum
        $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_setter WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        $statusAction = Arr::except($statusProgress, ['4']);
        $statusProgress = Arr::sort($statusProgress);
        return view('penerbitan.pracetak_setter.index', [
            'title' => 'Pracetak Setter',
            'status_action' => $statusAction,
            'status_progress' => $statusProgress,
            'count' => count($data)
        ]);
    }
    public function editSetter(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $history = DB::table('pracetak_setter as ps')
                    ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->where('ps.id', $request->id)
                    ->select('ps.*', 'dp.judul_final', 'df.bullet', 'dp.jml_hal_perkiraan')
                    ->first();
                if ($request->has('copy_editor')) {
                    foreach ($request->copy_editor as $ce) {
                        $req_copyeditor[] = $ce;
                    }
                    $copyeditor = json_encode($req_copyeditor);
                } else {
                    $copyeditor = null;
                }
                if ($history->proses == '1') {
                    if (is_null($history->tgl_selesai_edit)) {
                        if ((json_decode($history->editor, true) != $request->editor) && (!is_null($history->tgl_mulai_edit))) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Hentikan proses untuk mengubah editor'
                            ]);
                        }
                    } else {
                        if ((json_decode($history->copy_editor, true) != $request->copy_editor) && (!is_null($history->tgl_mulai_edit))) {
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
                    'copy_editor' => $request->has('copy_editor') ? json_encode($request->copy_editor) : null,
                    'bulan' => Carbon::createFromDate($request->bulan)
                ];
                // event(new EditingEvent($update));

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
                    'copy_editor_his' => $history->copy_editor == $copyeditor ? null : $history->copy_editor,
                    'copy_editor_new' => $history->copy_editor == $copyeditor ? null : $copyeditor,
                    'catatan_his' => $history->catatan == $request->catatan ? null : $history->catatan,
                    'catatan_new' => $history->catatan == $request->catatan ? null : $request->catatan,
                    'bulan_his' => $history->bulan == Carbon::createFromDate($request->bulan) ? null : Carbon::createFromFormat('Y-m-d', $history->bulan)->format('Y-m-d'),
                    'bulan_new' => $history->bulan == Carbon::createFromDate($request->bulan) ? null : Carbon::createFromDate($request->bulan),
                    'author_id' => auth()->id(),
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                // event(new EditingEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data editing proses berhasil ditambahkan',
                    'route' => route('editing.view')
                ]);
            }
        }
        $id = $request->get('setter');
        $kodenaskah = $request->get('kode');
        $data = DB::table('pracetak_setter as ps')
            ->join('deskripsi_final as df', 'ps.deskripsi_final_id', '=', 'df.id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('ps.id', $id)
            ->where('pn.kode', $kodenaskah)
            ->select(
                'ps.*',
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
            $setter = DB::table('users as u')
            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
            ->where('j.nama', 'LIKE', '%Setter%')
            ->where('d.nama', 'LIKE', '%Penerbitan%')
            ->select('u.nama', 'u.id')
            ->orderBy('u.nama', 'Asc')
            ->get();
        if (!is_null($data->setter)) {
            foreach (json_decode($data->setter) as $e) {
                $namaSetter[] = DB::table('users')->where('id', $e)->first()->nama;
            }
            $korektor = DB::table('users as u')
                ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
                ->where('j.nama', 'LIKE', '%Setter%')
                ->where('d.nama', 'LIKE', '%Penerbitan%')
                ->whereNotIn('u.id', json_decode($data->setter))
                ->orWhere('j.nama', 'LIKE', '%Korektor%')
                ->select('u.nama', 'u.id')
                ->orderBy('u.nama', 'Asc')
                ->get();
        } else {
            $namaEditor = null;
            $korektor = null;
        }
        if (!is_null($data->korektor)) {
            foreach (json_decode($data->korektor) as $ce) {
                $namakorektor[] = DB::table('users')->where('id', $ce)->first()->nama;
            }
        } else {
            $namakorektor = null;
        }
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.nama')
            ->get();
        return view('penerbitan.pracetak_setter.edit', [
            'title' => 'Pracetak Setter Proses',
            'data' => $data,
            'setter' => $setter,
            'nama_setter' => $namaSetter,
            'korektor' => $korektor,
            'nama_korektor' => $namakorektor,
            'penulis' => $penulis,
        ]);
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
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-setter" style="background:#34395E;color:white" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusSetter" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Pending':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-setter" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusSetter" title="Update Status">
                    <div>' . $status . '</div></a>';
                break;
            case 'Proses':
                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-setter" data-id="' . $id . '" data-kode="' . $kode . '" data-judul="' . $judul_final . '" data-toggle="modal" data-target="#md_UpdateStatusSetter" title="Update Status">
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
        $btn .= '<a href="' . url('penerbitan/pracetak/setter/edit?setter=' . $id . '&kode=' . $kode) . '"
                class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                <div><i class="fas fa-edit"></i></div></a>';
        return $btn;
    }
}
