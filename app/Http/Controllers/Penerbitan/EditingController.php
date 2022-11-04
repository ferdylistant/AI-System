<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Illuminate\Support\Arr;
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
        if($request->ajax()) {
            if ($request->isMethod('POST')) {
                $history = DB::table('editing_proses as ep')
                ->join('deskripsi_final as df','df.id','=','ep.deskripsi_final_id')
                ->join('deskripsi_produk as dp','dp.id','=','df.deskripsi_produk_id')
                ->where('ep.id', $request->id)
                ->select('dc.*','dp.judul_final','dp.format_buku','df.sub_judul_final','df.bullet')
                ->first();
                foreach ($request->bullet as $value) {
                    $bullet[] = $value;
                }
                if (!$request->has('finishing_cover')) {
                    if ((!is_null($history->finishing_cover)) || ($history->finishing_cover != [])) {
                        $finishing_cover = json_decode($history->finishing_cover);
                    }
                } else {
                    foreach ($request->finishing_cover as $value) {
                        $finishing_cover[] = $value;
                    }
                }
                $update = [
                    'params' => 'Edit Descov',
                    'id' => $request->id,
                    'sub_judul_final' => $request->sub_judul_final, //Deskripsi Final
                    'des_front_cover' => $request->des_front_cover,
                    'des_back_cover' => $request->des_back_cover,
                    'finishing_cover' => json_encode(array_filter($finishing_cover)), //Array
                    'format_buku' => $request->format_buku, //Deskripsi Produk
                    'jilid' => $request->jilid,
                    'tipografi' => $request->tipografi,
                    'warna' => $request->warna,
                    'kelengkapan' => $request->kelengkapan,
                    'catatan' => $request->catatan,
                    'bullet' =>  json_encode(array_filter($bullet)), //Deskripsi Final
                    'desainer' => $request->desainer,
                    'contoh_cover' => $request->contoh_cover,
                    'bulan' => Carbon::createFromDate($request->bulan),
                    'updated_by'=> auth()->id()
                ];
                // event(new DescovEvent($update));

                $insert = [
                    'params' => 'Insert History Edit Descov',
                    'deskripsi_cover_id' => $request->id,
                    'type_history' => 'Update',
                    'format_buku_his' => $history->format_buku==$request->format_buku?NULL:$history->format_buku,
                    'format_buku_new' => $history->format_buku==$request->format_buku?NULL:$request->format_buku,
                    'sub_judul_final_his' => $history->sub_judul_final==$request->sub_judul_final?NULL:$history->sub_judul_final,
                    'sub_judul_final_new' => $history->sub_judul_final==$request->sub_judul_final?NULL:$request->sub_judul_final,
                    'des_front_cover_his' => $history->des_front_cover==$request->des_front_cover?NULL:$history->des_front_cover,
                    'des_front_cover_new' => $history->des_front_cover==$request->des_front_cover?NULL:$request->des_front_cover,
                    'des_back_cover_his' => $history->des_back_cover==$request->des_back_cover?NULL:$history->des_back_cover,
                    'des_back_cover_new' => $history->des_back_cover==$request->des_back_cover?NULL:$request->des_back_cover,
                    'finishing_cover_his' => $history->finishing_cover==json_encode(array_filter($finishing_cover))?NULL:$history->finishing_cover,
                    'finishing_cover_new' => $history->finishing_cover==json_encode(array_filter($finishing_cover))?NULL:json_encode(array_filter($finishing_cover)),
                    'jilid_his' => $history->jilid==$request->jilid?NULL:$history->jilid,
                    'jilid_new' => $history->jilid==$request->jilid?NULL:$request->jilid,
                    'tipografi_his' => $history->tipografi==$request->tipografi?NULL:$history->tipografi,
                    'tipografi_new' => $history->tipografi==$request->tipografi?NULL:$request->tipografi,
                    'warna_his' => $history->warna==$request->warna?NULL:$history->warna,
                    'warna_new' => $history->warna==$request->warna?NULL:$request->warna,
                    'bullet_his' => $history->bullet==json_encode(array_filter($bullet))?NULL:$history->bullet,
                    'bullet_new' => $history->bullet==json_encode(array_filter($bullet))?NULL:json_encode(array_filter($bullet)),
                    'desainer_his' => $history->desainer==$request->desainer?NULL:$history->desainer,
                    'desainer_new' => $history->desainer==$request->desainer?NULL:$request->desainer,
                    'contoh_cover_his' => $history->contoh_cover==$request->contoh_cover?NULL:$history->contoh_cover,
                    'contoh_cover_new' => $history->contoh_cover==$request->contoh_cover?NULL:$request->contoh_cover,
                    'kelengkapan_his' => $history->kelengkapan==$request->kelengkapan?NULL:$history->kelengkapan,
                    'kelengkapan_new' => $history->kelengkapan==$request->kelengkapan?NULL:$request->kelengkapan,
                    'catatan_his' => $history->catatan==$request->catatan?NULL:$history->catatan,
                    'catatan_new' => $history->catatan==$request->catatan?NULL:$request->catatan,
                    'bulan_his' => $history->bulan==Carbon::createFromDate($request->bulan)?NULL:Carbon::createFromFormat('Y-m-d', $history->bulan)->format('Y-m-d'),
                    'bulan_new' => $history->bulan==Carbon::createFromDate($request->bulan)?NULL:Carbon::createFromDate($request->bulan),
                    'author_id' => auth()->id(),
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                // event(new DescovEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data deskripsi cover berhasil ditambahkan',
                    'route' => route('descov.view')
                ]);
            }
        }
        $id = $request->get('editing');
        $kodenaskah = $request->get('kode');
        $data = DB::table('editing_proses as ep')
            ->join('deskripsi_final as df','ep.deskripsi_final_id','=','df.id')
            ->join('deskripsi_produk as dp','dp.id','=','df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb',function($q){
                $q->on('pn.kelompok_buku_id','=','kb.id')
                ->whereNull('kb.deleted_at');
            })
            ->where('ep.id',$id)
            ->where('pn.kode',$kodenaskah)
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
        is_null($data)?abort(404):
        $editor = DB::table('users as u')
            ->join('jabatan as j','u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id','=','d.id')
            ->where('j.nama','LIKE','%Editor%')
            ->where('d.nama','LIKE','%Penerbitan%')
            ->select('u.nama','u.id')
            ->get();
        $copyEditor = DB::table('users as u')
            ->join('jabatan as j','u.jabatan_id', '=', 'j.id')
            ->join('divisi as d', 'u.divisi_id','=','d.id')
            ->where('j.nama','LIKE','%Editor%')
            ->orWhere('j.nama','LIKE','%Korektor%')
            ->where('d.nama','LIKE','%Penerbitan%')
            ->select('u.nama','u.id')
            ->get();
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
        ->join('penerbitan_penulis as pp',function($q) {
            $q->on('pnp.penulis_id','=','pp.id')
            ->whereNull('pp.deleted_at');
        })
        ->where('pnp.naskah_id','=',$data->naskah_id)
        ->select('pp.nama')
        ->get();
        return view('penerbitan.editing.edit',[
            'title' => 'Editing Proses',
            'data' => $data,
            'editor' => $editor,
            'copy_editor' => $copyEditor,
            'penulis' => $penulis,
            // 'imprint' => $imprint
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
}
