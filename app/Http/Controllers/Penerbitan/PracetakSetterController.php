<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Events\PracetakSetterEvent;
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
                            ->where('type', 'Setter')
                            ->where('pracetak_setter_id', $data->id)
                            ->get();
                        if (!$setterVar->isEmpty()) {
                            foreach ($setterVar as $v) {
                                if (in_array($v->users_id, json_decode($data->setter, true))) {
                                    $tgl = '(' . Carbon::parse($v->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')';
                                }
                            }
                        }
                        foreach (json_decode($data->setter, true) as $q) {
                            $res .= '<span class="d-block">-&nbsp;' . DB::table('users')->where('id', $q)->whereNull('deleted_at')->first()->nama . ' <span class="text-success">' . $tgl . '</span></span>';
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
                            ->where('type', 'Korektor')
                            ->where('pracetak_setter_id', $data->id)
                            ->get();
                        if (!$korVar->isEmpty()) {
                            foreach ($korVar as $v) {
                                if (in_array($v->users_id, json_decode($data->korektor, true))) {
                                    $tgl = '(' . Carbon::parse($v->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')';
                                }
                            }
                        }
                        foreach (json_decode($data->korektor, true) as $q) {
                            $res .= '<span class="d-block">-&nbsp;' . DB::table('users')->where('id', $q)->whereNull('deleted_at')->first()->nama . ' <span class="text-success">' . $tgl . '</span></span>';
                        }
                    } else {
                        $res = '-';
                    }
                    return $res;
                })
                ->addColumn('history', function ($data) {
                    $historyData = DB::table('pracetak_setter_history')->where('pracetak_setter_id', $data->id)->get();
                    if ($historyData->isEmpty()) {
                        return '-';
                    } else {
                        $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                        return $date;
                    }
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode) . '"
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
        //Status
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
                    ->select('ps.*', 'dp.judul_final')
                    ->first();
                if ($request->has('korektor')) {
                    foreach ($request->korektor as $ce) {
                        $req_korektor[] = $ce;
                    }
                    $korektor = json_encode($req_korektor);
                } else {
                    $korektor = null;
                }
                if ($history->proses == '1') {
                    if (is_null($history->selesai_setting)) {
                        if ((json_decode($history->setter, true) != $request->setter) && (!is_null($history->mulai_setting))) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Hentikan proses untuk mengubah setter'
                            ]);
                        }
                    } else {
                        if ((json_decode($history->korektor, true) != $request->korektor) && (!is_null($history->mulai_koreksi))) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Hentikan proses untuk mengubah korektor'
                            ]);
                        }
                    }
                }
                $mulaiCopyright = $request->mulai_p_copyright == '' ? NULL : Carbon::createFromFormat('d F Y', $request->mulai_p_copyright)->format('Y-m-d H:i:s');
                $selesaiCopyright = $request->selesai_p_copyright == '' ? NULL : Carbon::createFromFormat('d F Y', $request->selesai_p_copyright)->format('Y-m-d H:i:s');
                $update = [
                    'params' => 'Edit Pracetak Setter',
                    'id' => $request->id,
                    'jml_hal_final' => $request->jml_hal_final,
                    'catatan' => $request->catatan,
                    'setter' => json_encode($request->setter),
                    'korektor' => $request->has('korektor') ? json_encode($request->korektor) : null,
                    'edisi_cetak' => $request->edisi_cetak,
                    'isbn' => $request->isbn,
                    'pengajuan_harga' => $request->pengajuan_harga,
                    'mulai_p_copyright' => $mulaiCopyright,
                    'selesai_p_copyright' => $selesaiCopyright,
                    'proses_saat_ini' => $request->proses_saat_ini,
                    'bulan' => Carbon::createFromDate($request->bulan)
                ];
                event(new PracetakSetterEvent($update));
                $insert = [
                    'params' => 'Insert History Edit Pracetak Setter',
                    'pracetak_setter_id' => $request->id,
                    'type_history' => 'Update',
                    'setter_his' => $history->setter == json_encode(array_filter($request->setter)) ? null : $history->setter,
                    'setter_new' => $history->setter == json_encode(array_filter($request->setter)) ? null : json_encode(array_filter($request->setter)),
                    'jml_hal_final_his' => $history->jml_hal_final == $request->jml_hal_final ? null : $history->jml_hal_final,
                    'jml_hal_final_new' => $history->jml_hal_final == $request->jml_hal_final ? null : $request->jml_hal_final,
                    'edisi_cetak_his' => $history->edisi_cetak == $request->edisi_cetak ? null : $history->edisi_cetak,
                    'edisi_cetak_new' => $history->edisi_cetak == $request->edisi_cetak ? null : $request->edisi_cetak,
                    'isbn_his' => $history->isbn == $request->isbn ? null : $history->isbn,
                    'isbn_new' => $history->isbn == $request->isbn ? null : $request->isbn,
                    'pengajuan_harga_his' => $history->pengajuan_harga == $request->pengajuan_harga ? null : $history->pengajuan_harga,
                    'pengajuan_harga_new' => $history->pengajuan_harga == $request->pengajuan_harga ? null : $request->pengajuan_harga,
                    'korektor_his' => $history->korektor == $korektor ? null : $history->korektor,
                    'korektor_new' => $history->korektor == $korektor ? null : $korektor,
                    'catatan_his' => $history->catatan == $request->catatan ? null : $history->catatan,
                    'catatan_new' => $history->catatan == $request->catatan ? null : $request->catatan,
                    'mulai_p_copyright' => $history->mulai_p_copyright == $mulaiCopyright ? null : $history->mulai_p_copyright,
                    'selesai_p_copyright' => $history->selesai_p_copyright == $selesaiCopyright ? null : $history->selesai_p_copyright,
                    'bulan_his' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : $history->bulan,
                    'bulan_new' => date('Y-m', strtotime($history->bulan)) == date('Y-m', strtotime($request->bulan)) ? null : Carbon::createFromDate($request->bulan),
                    'author_id' => auth()->id(),
                    'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                event(new PracetakSetterEvent($insert));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data setting proses berhasil ditambahkan',
                    'route' => route('setter.view')
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
            $namaSetter = null;
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
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_setter WHERE Field = 'proses_saat_ini'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $prosesSaatIni = explode("','", $matches[1]);
        $prosesFilter = Arr::except($prosesSaatIni, ['1', '4']);
        return view('penerbitan.pracetak_setter.edit', [
            'title' => 'Pracetak Setter Proses',
            'data' => $data,
            'setter' => $setter,
            'nama_setter' => $namaSetter,
            'korektor' => $korektor,
            'nama_korektor' => $namakorektor,
            'proses_saat_ini' => $prosesFilter,
            'penulis' => $penulis,
        ]);
    }
    public function detailSetter(Request $request)
    {
        $id = $request->get('pra');
        $kode = $request->get('kode');
        $data = DB::table('pracetak_setter as ps')
            ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
            ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
            ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
            ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                    ->whereNull('kb.deleted_at');
            })
            ->where('ps.id', $id)
            ->where('pn.kode', $kode)
            ->select(
                'ps.*',
                'df.sub_judul_final',
                'df.bullet',
                'df.sinopsis',
                'df.isi_warna',
                'df.isi_huruf',
                'dp.naskah_id',
                'dp.judul_final',
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
        $pic = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')->first()->nama;
        if (!is_null($data->setter)) {
            foreach (json_decode($data->setter, true) as $set) {
                $namaSetter[] = DB::table('users')->where('id', $set)->first();
            }
        } else {
            $namaSetter = null;
        }
        if (!is_null($data->korektor)) {
            foreach (json_decode($data->korektor, true) as $kor) {
                $namaKorektor[] = DB::table('users')->where('id', $kor)->first();
            }
        } else {
            $namaKorektor = null;
        }
        return view('penerbitan.pracetak_setter.detail', [
            'title' => 'Detail Pracetak Setter',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'nama_setter' => $namaSetter,
            'nama_korektor' => $namaKorektor
        ]);
    }
    public function lihatHistorySetter(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pracetak_setter_history as psh')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'psh.pracetak_setter_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('users as u', 'psh.author_id', '=', 'u.id')
                ->where('psh.pracetak_setter_id', $id)
                ->select('psh.*', 'dp.judul_final', 'u.nama')
                ->orderBy('psh.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Status':
                        $html .= '<span class="ticket-item" id="newAppend">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status pracetak setter <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.</span>
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
                        if (!is_null($d->setter_his)) {
                            $loopSetHIS = '';
                            $loopSetNEW = '';
                            foreach (json_decode($d->setter_his, true) as $sethis) {
                                $loopSetHIS .= '<b class="text-dark">' . DB::table('users')->where('id', $sethis)->first()->nama . '</b>, ';
                            }
                            foreach (json_decode($d->setter_new, true) as $setnew) {
                                $loopSetNEW .= '<span class="bullet"></span>' . DB::table('users')->where('id', $setnew)->first()->nama;
                            }
                            $html .= ' Setter <b class="text-dark">' . $loopSetHIS . '</b> diubah menjadi <b class="text-dark">' . $loopSetNEW . '</b>.<br>';
                        } elseif (!is_null($d->setter_new)) {
                            $loopSetNEW = '';
                            foreach (json_decode($d->setter_new, true) as $setnew) {
                                $loopSetNEW .= '<b class="text-dark">' . DB::table('users')->where('id', $setnew)->first()->nama . '</b>, ';
                            }
                            $html .= ' Setter <b class="text-dark">' . $loopSetNEW . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->korektor_his)) {
                            $loopKorHIS = '';
                            $loopKorNEW = '';
                            foreach (json_decode($d->korektor_his, true) as $korhis) {
                                $loopKorHIS .= '<b class="text-dark">' . DB::table('users')->where('id', $korhis)->first()->nama . '</b>, ';
                            }
                            foreach (json_decode($d->korektor_new, true) as $kornew) {
                                $loopKorNEW .= '<span class="bullet"></span>' . DB::table('users')->where('id', $kornew)->first()->nama;
                            }
                            $html .= ' Copy Editor <b class="text-dark">' . $loopKorHIS . '</b> diubah menjadi <b class="text-dark">' . $loopKorNEW . '</b>.<br>';
                        } elseif (!is_null($d->korektor_new)) {
                            $loopKorNEW = '';
                            foreach (json_decode($d->korektor_new, true) as $kornew) {
                                $loopKorNEW .= '<b class="text-dark">' . DB::table('users')->where('id', $kornew)->first()->nama . '</b>, ';
                            }
                            $html .= ' Editor <b class="text-dark">' . $loopKorNEW . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->jml_hal_final_his)) {
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_final_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_hal_final_new . '</b>.<br>';
                        } elseif (!is_null($d->jml_hal_final_new)) {
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_final_new . '</b> ditambahkan.';
                        }
                        if (!is_null($d->catatan_his)) {
                            $html .= ' Catatan <b class="text-dark">' . $d->catatan_his . '</b> diubah menjadi <b class="text-dark">' . $d->catatan_new . '</b>.<br>';
                        } elseif (!is_null($d->catatan_new)) {
                            $html .= ' Catatan <b class="text-dark">' . $d->catatan_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->edisi_cetak_his)) {
                            $html .= ' Edisi cetak <b class="text-dark">' . $d->edisi_cetak_his . '</b> diubah menjadi <b class="text-dark">' . $d->edisi_cetak_new . '</b>.<br>';
                        } elseif (!is_null($d->edisi_cetak_new)) {
                            $html .= ' Edisi cetak <b class="text-dark">' . $d->edisi_cetak_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->isbn_his)) {
                            $html .= ' ISBN <b class="text-dark">' . $d->isbn_his . '</b> diubah menjadi <b class="text-dark">' . $d->isbn_new . '</b>.<br>';
                        } elseif (!is_null($d->isbn_new)) {
                            $html .= ' ISBN <b class="text-dark">' . $d->isbn_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->pengajuan_harga_his)) {
                            $html .= ' Pengajuan harga <b class="text-dark">' . $d->pengajuan_harga_his . '</b> diubah menjadi <b class="text-dark">' . $d->pengajuan_harga_new . '</b>.<br>';
                        } elseif (!is_null($d->pengajuan_harga_new)) {
                            $html .= ' Pengajuan harga <b class="text-dark">' . $d->pengajuan_harga_new . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->mulai_setting)) {
                            $html .= ' Setting dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_setting)->translatedFormat('l d F Y, H:i') . '</b>.';
                        } elseif (!is_null($d->selesai_setting)) {
                            $html .= ' Setting selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_setting)->translatedFormat('l d F Y, H:i') . '</b>.';
                        }
                        if (!is_null($d->mulai_proof)) {
                            $html .= ' Proof prodev dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_proof)->translatedFormat('l d F Y, H:i') . '</b>.';
                        } elseif (!is_null($d->selesai_proof)) {
                            $html .= ' Proof prodev selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_proof)->translatedFormat('l d F Y, H:i') . '</b>.';
                        }
                        if (!is_null($d->mulai_koreksi)) {
                            $html .= ' Koreksi dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_koreksi)->translatedFormat('l d F Y, H:i') . '</b>.';
                        } elseif (!is_null($d->selesai_koreksi)) {
                            $html .= ' Koreksi selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_koreksi)->translatedFormat('l d F Y, H:i') . '</b>.';
                        }
                        if (!is_null($d->mulai_p_copyright)) {
                            $html .= ' Proses copyright dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_p_copyright)->translatedFormat('l d F Y, H:i') . '</b>.';
                        } elseif (!is_null($d->selesai_p_copyright)) {
                            $html .= ' Proses copyright selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_p_copyright)->translatedFormat('l d F Y, H:i') . '</b>.';
                        }
                        if (!is_null($d->bulan_his)) {
                            $html .= ' Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->bulan_new)->translatedFormat('F Y') . '</b>.';
                        } elseif (!is_null($d->bulan_new)) {
                            $html .= ' Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> ditambahkan.';
                        }
                        $html .= '</span></div>
                        <div class="ticket-info">
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
                            <span><span class="bullet"></span> Progress pracetak  setter <b class="text-dark">' . $ket . '</b>.</span>
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
    public function updateStatusProgress(Request $request)
    {
        try {
            $id = $request->id;
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                    $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                        ->whereNull('kb.deleted_at');
                })
                ->where('ps.id', $id)
                ->select(
                    'ps.*',
                    'df.id as deskripsi_final_id',
                    'dp.naskah_id',
                    'dp.format_buku',
                    'dp.judul_final',
                    'dp.editor',
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
            // switch ($request->status) {
            //     case 'Selesai':
            //         $proses_saat_ini = 'Turun Cetak';
            //         break;
            //     case 'Pending':
            //         if (is_null($data->selesai_setting)) {
            //             if (is_null($data->mulai_setting)) {
            //                 $proses_saat_ini = NULL;
            //             } else {
            //                 $proses_saat_ini = 'Setting';
            //             }
            //         } else {
            //             if (is_null($data->mulai_koreksi)) {
            //                 $proses_saat_ini = NULL;
            //             } else {
            //                 $proses_saat_ini = 'Koreksi';
            //             }
            //         }
            //         break;
            //     case 'Proses':
            //         if (is_null($data->selesai_setting)) {
            //             if (is_null($data->mulai_setting)) {
            //                 $proses_saat_ini = NULL;
            //             } else {
            //                 $proses_saat_ini = 'Setting';
            //             }
            //         } else {
            //             if (is_null($data->mulai_koreksi)) {
            //                 $proses_saat_ini = NULL;
            //             } else {
            //                 $proses_saat_ini = 'Koreksi';
            //             }
            //         }
            //         break;
            //     case 'Antrian':
            //         if (is_null($data->selesai_setting)) {
            //             if (is_null($data->mulai_setting)) {
            //                 $proses_saat_ini = NULL;
            //             } else {
            //                 $proses_saat_ini = 'Antrian Setting';
            //             }
            //         } else {
            //             if (is_null($data->mulai_koreksi)) {
            //                 $proses_saat_ini = NULL;
            //             } else {
            //                 $proses_saat_ini = 'Antrian Koreksi';
            //             }
            //         }
            //         break;
            // }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $update = [
                'params' => 'Update Status Pracetak Setter',
                'id' => $data->id,
                // 'proses_saat_ini' => $proses_saat_ini?$proses_saat_ini:$data->proses_saat_ini,
                'turun_cetak' => $request->status == 'Selesai' ? $tgl : NULL,
                'status' => $request->status,
            ];
            $insert = [
                'params' => 'Insert History Status Pracetak Setter',
                'pracetak_setter_id' => $data->id,
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
                if (is_null($data->selesai_setting)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses setting belum selesai'
                    ]);
                }
                if (is_null($data->selesai_proof)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses proof prodev belum selesai'
                    ]);
                }
                if (is_null($data->selesai_koreksi)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses koreksi belum selesai'
                    ]);
                }
                if (is_null($data->selesai_p_copyright)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Proses copyright belum selesai'
                    ]);
                }
                event(new PracetakSetterEvent($update));
                event(new PracetakSetterEvent($insert));
                // DB::table('pracetak_setter')->where('deskripsi_final_id',$data->deskripsi_final_id)->update([
                //     'jml_hal_final' => $data->jml_hal_perkiraan
                // ]);
                $msg = 'Pracetak Setter selesai, silahkan lanjut ke proses deskripsi turun cetak..';
            } else {
                event(new PracetakSetterEvent($update));
                event(new PracetakSetterEvent($insert));
                $msg = 'Status progress pracetak setter berhasil diupdate';
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
    public function prosesKerjaSetter(Request $request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->id;
                $value = $request->proses;
                $data = DB::table('pracetak_setter')->where('id', $id)->first();
                if (is_null($data)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'naskah sudah tidak ada'
                    ]);
                }
                if ($value == '0') {
                    if (is_null($data->selesai_setting)) {
                        $dataProgress = [
                            'params' => 'Progress Setter',
                            'id' => $id,
                            'mulai_setting' => null,
                            'proses' => $value,
                            'proses_saat_ini' => NULL,
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                    } else {
                        $dataProgress = [
                            'params' => 'Progress Korektor',
                            'id' => $id,
                            'mulai_korektor' => null,
                            'proses' => $value,
                            'proses_saat_ini' => NULL,
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                    }
                    event(new PracetakSetterEvent($dataProgress));
                    $msg = 'Proses diberhentikan!';
                } else {
                    switch ($data->proses_saat_ini) {
                        case 'Antrian Setting':
                            if (!is_null($data->selesai_setting)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses setting selesai, silahkan ubah proses saat ini menjadi "Proof Reading".'
                                ]);
                            }
                            break;
                        case 'Antrian Koreksi':
                            if (is_null($data->selesai_setting)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses setting belum selesai. Belum bisa melakukan proses "Antrian Koreksi".'
                                ]);
                            }
                            if (!is_null($data->selesai_koreksi)) {
                                return response()->json([
                                    'status' => 'error',
                                    'Proses koreksi selesai, silahkan ubah proses saat ini menjadi proses selanjutnya atau mengubah status proses menjadi selesai.'
                                ]);
                            }
                            break;
                        case 'Proof Reading':
                            if (is_null($data->selesai_setting)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses setting belum selesai. Belum bisa melakukan proses "Proof Reading".'
                                ]);
                            }
                            if (!is_null($data->selesai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses proof reading selesai, silahkan ubah proses saat ini menjadi proses selanjutnya atau mengubah status proses menjadi selesai.'
                                ]);
                            }
                            break;
                    }
                    if (is_null($data->selesai_setting)) {
                        if (!$request->has('setter')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih setter terlebih dahulu!'
                            ]);
                        } else {
                            if (json_decode($data->setter, true) == $request->setter) {
                                if (is_null($data->mulai_setting)) {
                                    $tglSetter = Carbon::now('Asia/Jakarta')->toDateTimeString();
                                } else {
                                    $tglSetter = $data->mulai_setting;
                                }
                            } else {
                                $tglSetter = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            }
                            $dataSetter = [
                                'params' => 'Progress Setter',
                                'id' => $id,
                                'mulai_setting' => $tglSetter,
                                'proses' => $value,
                                'proses_saat_ini' => 'Setting',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakSetterEvent($dataSetter));
                        }
                    } else {
                        if (!$request->has('korektor')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih korektor terlebih dahulu!'
                            ]);
                        } else {
                            if (json_decode($data->korektor, true) == $request->korektor) {
                                if (is_null($data->mulai_koreksi)) {
                                    $tglKorektor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                                } else {
                                    $tglKorektor = $data->mulai_koreksi;
                                }
                            } else {
                                $tglKorektor = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            }
                            $dataKorektor = [
                                'params' => 'Progress Korektor',
                                'id' => $id,
                                'mulai_koreksi' => $tglKorektor,
                                'proses' => $value,
                                'proses_saat_ini' => 'Koreksi',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakSetterEvent($dataKorektor));
                        }
                    }
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
    protected function logicPermissionAction($status = null, $jb = null, $pic_prodev,$id, $kode, $judul_final, $btn)
    {
        switch ($jb) {
            case 'MoU-Reguler':
                $gate = Gate::allows('do_create', 'ubah-atau-buat-setter-reguler') || Gate::allows('do_create', 'ubah-atau-buat-setter-mou');
                break;
            default:
                $gate = Gate::allows('do_create', 'ubah-atau-buat-setter-'.$jb);
                break;
        }
        if ($gate) {
            if ($status == 'Selesai') {
                if (Gate::allows('do_approval', 'approval-deskripsi-produk')) {
                    $btn = $this->buttonEdit($id, $kode, $btn);
                }
            } else {
                if ((auth()->id() == $pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') || (Gate::allows('do_approval', 'approval-deskripsi-produk'))) {
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
    public function prosesSelesaiSetter($autor, $id)
    {
        switch ($autor) {
            case 'setter':
                return $this->selesaiSetter($id);
                break;
            case 'korektor':
                return $this->selesaiKorektor($id);
                break;
            default:
                return abort(500);
                break;
        }
    }
    protected function selesaiSetter($id)
    {
        // ? BELOM SELESAI
        try {
            $data = DB::table('pracetak_setter')->where('id', $id)->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data setting tidak ada'
                ]);
            }
            $dataProsesSelf = DB::table('pracetak_setter_selesai')
                ->where('type', 'Setter')
                ->where('type_selesai', 'Sample')
                ->where('pracetak_setter_id', $data->id)
                ->where('users_id', auth()->id())
                ->first();

            // ? BELOM SELESAI

            if (!is_null($dataProsesSelf)) {
                // foreach ($dataProsesSelf as $value) {
                //     if ($value->keterangan == '0') {

                //     }
                // }
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
                //* event(new EditingEvent($pros));
                $done = [
                    'params' => 'Editing Selesai',
                    'id' => $data->id,
                    'tgl_selesai_edit' => $tgl,
                    'proses' => '0'
                ];
                //* event(new EditingEvent($done));
            } else {
                $pros = [
                    'params' => 'Proses Selesai',
                    'type' => 'Editor',
                    'editing_proses_id' => $data->id,
                    'users_id' => auth()->id(),
                    'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                ];
                //* event(new EditingEvent($pros));
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
}
