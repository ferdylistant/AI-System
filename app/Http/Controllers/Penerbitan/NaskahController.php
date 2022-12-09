<?php

namespace App\Http\Controllers\Penerbitan;

use App\Events\DesproEvent;
use App\Events\NaskahEvent;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class NaskahController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->input('request_')) {
                case 'selectPenulis':
                    $data = DB::table('penerbitan_penulis')
                        ->whereNull('deleted_at')
                        ->where('nama', 'like', '%' . $request->input('term') . '%')
                        ->get();

                    return $data;
                    break;
                case 'selectedPenulis':
                    $data = DB::table('penerbitan_penulis')
                        ->where('id', $request->input('id'))
                        ->first();
                    return $data;
                    break;
                case 'getKodeNaskah':
                    return self::generateId();
                    break;
                case 'getPICTimeline':
                    $data = DB::table('users')->whereNull('deleted_at')->where('status', '1')
                        ->where('nama', 'like', '%' . $request->input('term') . '%')->select('id', 'nama')
                        ->get();
                    return $data;
                    break;
                default:
                    $data = DB::table('penerbitan_naskah as pn')
                        ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                        ->whereNull('pn.deleted_at')
                        ->select(
                            'pn.id',
                            'pn.kode',
                            'pn.judul_asli',
                            'pn.pic_prodev',
                            'pn.created_by',
                            'pn.jalur_buku',
                            'pn.tanggal_masuk_naskah',
                            'pn.selesai_penilaian',
                            'pn.bukti_email_penulis',
                            'pns.tgl_pn_prodev',
                            'pns.tgl_pn_m_penerbitan',
                            'pns.tgl_pn_m_pemasaran',
                            'pns.tgl_pn_d_pemasaran',
                            'pns.tgl_pn_direksi',
                            'pns.tgl_pn_editor',
                            'pns.tgl_pn_setter',
                            'pns.tgl_pn_selesai'
                        )
                        ->orderBy('pn.tanggal_masuk_naskah', 'asc')
                        ->get();
                    $update = Gate::allows('do_update', 'ubah-data-naskah');

                    return Datatables::of($data)
                        ->addColumn('kode', function ($data) {
                            return $data->kode;
                        })
                        ->addColumn('judul_asli', function ($data) {
                            return $data->judul_asli;
                        })
                        ->addColumn('jalur_buku', function ($data) {
                            return $data->jalur_buku;
                        })
                        ->addColumn('masuk_naskah', function ($data) {
                            return Carbon::parse($data->tanggal_masuk_naskah)->translatedFormat('l, d F Y');
                        })
                        ->addColumn('stts_penilaian', function ($data) {
                            $badge = '';
                            if (in_array($data->jalur_buku, ['Reguler', 'MoU-Reguler'])) {
                                if (!is_null($data->tgl_pn_selesai)) {
                                    $badge .= '<span class="badge badge-primary">Selesai Dinilai</span>';
                                    if ($data->pic_prodev == auth()->user()->id) {
                                        if (is_null($data->bukti_email_penulis)) {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning">Data belum lengkap</span>';
                                            $badge .= '&nbsp;|&nbsp;<a href="javascript:void(0)" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" class="text-primary mark-sent-email">Tandai data lengkap</a>';
                                        } else {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success">Data sudah lengkap</span>';
                                        }
                                    } else {
                                        if (is_null($data->bukti_email_penulis)) {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning">Data belum lengkap</span>';
                                        } else {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success">Data sudah lengkap</span>';
                                        }
                                    }
                                } else {
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_prodev) ? 'danger' : 'success') . ' mr-1">Prodev' . (is_null($data->tgl_pn_prodev) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_prodev)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_m_penerbitan) ? 'danger' : 'success') . ' mr-1 mt-1">M.Penerbitan' . (is_null($data->tgl_pn_m_penerbitan) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_m_penerbitan)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_m_pemasaran) ? 'danger' : 'success') . ' mr-1 mt-1">M.Pemasaran' . (is_null($data->tgl_pn_m_pemasaran) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_m_pemasaran)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_d_pemasaran) ? 'danger' : 'success') . ' mr-1 mt-1">D.Pemasaran' . (is_null($data->tgl_pn_d_pemasaran) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_d_pemasaran)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_direksi) ? 'danger' : 'success') . ' mr-1 mt-1">Direksi' . (is_null($data->tgl_pn_direksi) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_direksi)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                }
                            } elseif ($data->jalur_buku == 'Pro Literasi') {
                                if (!is_null($data->tgl_pn_selesai)) {
                                    $badge .= '<span class="badge badge-primary">Selesai Dinilai</span>';
                                    if ($data->pic_prodev == auth()->user()->id) {
                                        if (is_null($data->bukti_email_penulis)) {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning">Data belum lengkap</span>';
                                            $badge .= '&nbsp;|&nbsp;<a href="javascript:void(0)" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" class="text-primary mark-sent-email">Tandai data lengkap</a>';
                                        } else {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success">Data sudah lengkap</span>';
                                        }
                                    } else {
                                        if (is_null($data->bukti_email_penulis)) {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning">Data belum lengkap</span>';
                                        } else {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success">Data sudah lengkap</span>';
                                        }
                                    }
                                } else {
                                    $badge .= '<span class="badge badge-' . (is_null($data->tgl_pn_editor) ? 'danger' : 'success') . '">Editor</span>';
                                    $badge .= '<span class="badge badge-' . (is_null($data->tgl_pn_setter) ? 'danger' : 'success') . '">Setter</span>';
                                }
                            } else {
                                $badge .= '<span class="badge badge-primary">Tidak Dinilai</span>';
                                if ($data->pic_prodev == auth()->user()->id) {
                                    if (is_null($data->bukti_email_penulis)) {
                                        $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning">Data belum lengkap</span>';
                                        $badge .= '&nbsp;|&nbsp;<a href="javascript:void(0)" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" class="text-primary mark-sent-email">Tandai sudah lengkap</a>';
                                    } else {
                                        $badge .= '&nbsp;|&nbsp;<span class="badge badge-success">Data sudah lengkap</span>';
                                    }
                                } else {
                                    if (is_null($data->bukti_email_penulis)) {
                                        $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning">Data belum lengkap</span>';
                                    } else {
                                        $badge .= '&nbsp;|&nbsp;<span class="badge badge-success">Data sudah lengkap</span>';
                                    }
                                }
                            }

                            return $badge;
                        })
                        ->addColumn('history', function ($data) {
                            $historyData = DB::table('penerbitan_naskah_history')->where('naskah_id', $data->id)->get();
                            if ($historyData->isEmpty()) {
                                return '-';
                            } else {
                                $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-judulasli="' . $data->judul_asli . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                                return $date;
                            }
                        })
                        ->addColumn('action', function ($data) use ($update) {
                            $btn = '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $data->id) . '"
                                class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Data">
                                <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if ($update) {
                                if ((auth()->id() == $data->pic_prodev) || (auth()->id() == $data->created_by) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') || (Gate::allows('do_approval', 'approval-deskripsi-produk'))) {
                                    $btn .= '<a href="' . url('penerbitan/naskah/mengubah-naskah/' . $data->id) . '"
                                        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                        <div><i class="fas fa-edit"></i></div></a>';
                                }
                            }
                            return $btn;
                        })
                        ->rawColumns(['kode', 'judul_asli', 'jalur_buku', 'masuk_naskah', 'stts_penilaian', 'history', 'action'])
                        ->make(true);
                    break;
            }
        }
        $data = DB::table('penerbitan_naskah as pn')
            ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
            ->whereNull('pn.deleted_at')
            ->select(
                'pn.id',
                'pn.kode',
                'pn.judul_asli',
                'pn.jalur_buku',
                'pn.pic_prodev',
                'pn.tanggal_masuk_naskah',
                'pn.selesai_penilaian',
                'pn.bukti_email_penulis',
                'pns.tgl_pn_prodev',
                'pns.tgl_pn_m_penerbitan',
                'pns.tgl_pn_m_pemasaran',
                'pns.tgl_pn_d_pemasaran',
                'pns.tgl_pn_direksi',
                'pns.tgl_pn_editor',
                'pns.tgl_pn_setter',
                'pns.tgl_pn_selesai'
            )
            ->orderBy('pn.tanggal_masuk_naskah', 'asc')
            ->get();
        $dataLengkap = [
            [
                'value' => 'Data Belum Lengkap'
            ],
            [
                'value' => 'Data Sudah Lengkap',
            ]
        ];
        $type = DB::select(DB::raw("SHOW COLUMNS FROM penerbitan_naskah WHERE Field = 'jalur_buku'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $jalurB = explode("','", $matches[1]);
        return view('penerbitan.naskah.index', [
            'title' => 'Naskah Penerbitan',
            'data_naskah_penulis' => $dataLengkap,
            'count' => count($data),
            'jalur_b' => $jalurB
        ]);
    }

    public function createNaskah(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'add_judul_asli' => 'required',
                    'add_kode' => 'required|unique:penerbitan_naskah,kode',
                    'add_kelompok_buku' => 'required',
                    'add_jalur_buku' => 'required',
                    'add_tanggal_masuk_naskah' => 'required',
                    'add_sumber_naskah' => 'required',
                    'add_cdqr_code' => 'required',
                    'add_pic_prodev' => 'required',
                    'add_penulis' => 'required'
                ], [
                    'required' => 'This field is requried'
                ]);
                foreach ($request->input('add_sumber_naskah') as $sn) {
                    if ($sn == 'SC') {
                        $request->validate([
                            'add_url_file' => 'required|url',
                        ], [
                            'required' => 'This field is requried'
                        ]);
                    }
                }
                DB::beginTransaction();

                try {
                    $idN = Str::uuid()->getHex();

                    foreach ($request->input('add_penulis') as $p) {
                        $daftarPenulis[] = [
                            'naskah_id' => $idN,
                            'penulis_id' => $p
                        ];
                    }

                    DB::table('penerbitan_naskah_penulis')->insert($daftarPenulis);
                    // DB::table('penerbitan_naskah_files')->insert($fileNaskah);
                    $penilaian = $this->alurPenilaian($request->input('add_jalur_buku'), 'create-notif-from-naskah', [
                        'id_prodev' => $request->input('add_pic_prodev'),
                        'form_id' => $idN
                    ]);
                    $addNaskah = [
                        'params' => 'Add Naskah',
                        'id' => $idN,
                        'kode' => $request->input('add_kode'),
                        'judul_asli' => $request->input('add_judul_asli'),
                        'tanggal_masuk_naskah' => Carbon::createFromFormat('d F Y', $request->input('add_tanggal_masuk_naskah'))
                            ->format('Y-m-d'),
                        'kelompok_buku_id' => $request->input('add_kelompok_buku'),
                        'jalur_buku' => $request->input('add_jalur_buku'),
                        'sumber_naskah' => json_encode($request->input('add_sumber_naskah')),
                        'cdqr_code' => $request->input('add_cdqr_code'),
                        'keterangan' => $request->input('add_keterangan'),
                        'pic_prodev' => $request->input('add_pic_prodev'),
                        'url_file' => $request->input('add_url_file'),
                        'penilaian_naskah' => ($penilaian['penilaian_naskah'] ? '1' : '0'),
                        'created_by' => auth()->id()
                    ];
                    event(new NaskahEvent($addNaskah));
                    $addPnStatus = [
                        'params' => 'Add Penilaian Status',
                        'id' => Str::uuid()->getHex(),
                        'naskah_id' => $idN,
                        'tgl_naskah_masuk' => Carbon::createFromFormat('d F Y', $request->input('add_tanggal_masuk_naskah'))
                            ->format('Y-m-d'),
                        'tgl_pn_selesai' => ($penilaian['selesai_penilaian'] > 0) ? date('Y-m-d H:i:s') : null
                    ];
                    event(new NaskahEvent($addPnStatus));

                    DB::commit();
                    return;
                } catch (\Exception $e) {
                    Storage::deleteDirectory('penerbitan/naskah/' . $idN);
                    DB::rollback();
                    return abort(500, $e->getMessage());
                }
            } else {
                return abort('404');
            }
        }

        $kbuku = DB::table('penerbitan_m_kelompok_buku')
            ->get();
        $user = DB::table('users as u')->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->where('j.nama', 'LIKE', '%Prodev%')
            ->select('u.nama', 'u.id')
            ->get();
        return view('penerbitan.naskah.create-naskah', [
            'kode' => self::generateId(),
            'kbuku' => $kbuku,
            'user' => $user,
            'title' => 'Tambah Naskah Penerbitan'
        ]);
    }

    public function updateNaskah(Request $request)
    {
        $naskah = DB::table('penerbitan_naskah as pn')
            ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
            ->whereNull('pn.deleted_at')
            ->where('pn.id', $request->id)
            ->select(DB::raw('pn.*, pns.tgl_pn_prodev'))
            ->first();

        if (is_null($naskah)) {
            return abort(404);
        }
        $naskah = (object)collect($naskah)->map(function ($item, $key) {
            if ($key == 'tanggal_masuk_naskah' and $item != '') {
                return Carbon::createFromFormat('Y-m-d', $item)->format('d F Y');
            }
            return $item;
        })->all();

        if ($request->ajax()) {
            if ($request->isMethod('GET')) {
                $penulis = DB::table('penerbitan_naskah_penulis as pnp')
                    ->join('penerbitan_penulis as pp', 'pnp.penulis_id', '=', 'pp.id')
                    ->where('naskah_id', $naskah->id)
                    ->select('pp.id', 'pp.nama')
                    ->get();

                return ['naskah' => $naskah, 'penulis' => $penulis];
            } elseif ($request->isMethod('POST')) {
                $request->validate([
                    'edit_judul_asli' => 'required',
                    'edit_kode' => 'required|unique:penerbitan_naskah,kode,' . $request->id,
                    'edit_kelompok_buku' => 'required',
                    'edit_jalur_buku' => 'required',
                    'edit_tanggal_masuk_naskah' => 'required',
                    'edit_sumber_naskah' => 'required',
                    'edit_cdqr_code' => 'required',
                    'edit_pic_prodev' => 'required',
                    'edit_penulis' => 'required'
                ], [
                    'required' => 'This field is requried'
                ]);
                foreach ($request->input('edit_sumber_naskah') as $sn) {
                    if ($sn == 'SC') {
                        $request->validate([
                            'edit_url_file' => 'required|url',
                        ], [
                            'required' => 'This field is requried'
                        ]);
                    }
                }
                if ((count($request->input('edit_sumber_naskah')) == 1) && ($request->input('edit_sumber_naskah')[0] != 'SC')) {
                    $url_file = NULL;
                } elseif ((count($request->input('edit_sumber_naskah')) == 2) && ($request->input('edit_sumber_naskah')[1] != 'SC')) {
                    $url_file = NULL;
                } else {
                    $url_file = $request->input('edit_url_file');
                }
                DB::beginTransaction();
                try {

                    foreach ($request->input('edit_penulis') as $p) {
                        $daftarPenulis[] = [
                            'naskah_id' => $naskah->id,
                            'penulis_id' => $p
                        ];
                    }

                    DB::table('penerbitan_naskah_penulis')
                        ->where('naskah_id', $naskah->id)->delete();
                    DB::table('penerbitan_naskah_penulis')
                        ->insert($daftarPenulis);

                    if ($request->input('edit_pic_prodev') != $naskah->pic_prodev) { // Update Notifikasi jika naskah diubah. (Hanya prodev)
                        $this->alurPenilaian($request->input('edit_jalur_buku'), 'update-notif-from-naskah', [
                            'id_prodev' => $request->input('edit_pic_prodev'),
                            'form_id' => $naskah->id
                        ]);
                    }
                    $editNaskah = [
                        'params' => 'Edit Naskah',
                        'id' => $naskah->id,
                        'judul_asli' => $request->input('edit_judul_asli'),
                        'tanggal_masuk_naskah' => Carbon::createFromFormat('d F Y', $request->input('edit_tanggal_masuk_naskah'))
                            ->format('Y-m-d'),
                        'kelompok_buku_id' => $request->input('edit_kelompok_buku'),
                        'jalur_buku' => $request->input('edit_jalur_buku'),
                        'sumber_naskah' => $request->input('edit_sumber_naskah'),
                        'cdqr_code' => $request->input('edit_cdqr_code'),
                        'keterangan' => $request->input('edit_keterangan'),
                        'pic_prodev' => $request->input('edit_pic_prodev'),
                        'url_file' => $url_file,
                        'updated_by' => auth()->id(),
                        'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new NaskahEvent($editNaskah));
                    $insertHistoryEdit = [
                        'params' => 'Insert Edit Naskah History',
                        'type_history' => 'Update',
                        'naskah_id' => $naskah->id,
                        'judul_asli_his' => $naskah->judul_asli == $request->input('edit_judul_asli') ? NULL : $naskah->judul_asli,
                        'judul_asli_new' => $naskah->judul_asli == $request->input('edit_judul_asli') ? NULL : $request->input('edit_judul_asli'),
                        'kelompok_buku_his' => $naskah->kelompok_buku_id == $request->input('edit_kelompok_buku') ? NULL : $naskah->kelompok_buku_id,
                        'kelompok_buku_new' => $naskah->kelompok_buku_id == $request->input('edit_kelompok_buku') ? NULL : $request->input('edit_kelompok_buku'),
                        'tgl_masuk_nas_his' => Carbon::createFromFormat('d F Y', $naskah->tanggal_masuk_naskah)
                            ->format('Y-m-d') == Carbon::createFromFormat('d F Y', $request->input('edit_tanggal_masuk_naskah'))
                            ->format('Y-m-d') ? NULL : Carbon::createFromFormat('d F Y', $naskah->tanggal_masuk_naskah)
                            ->format('Y-m-d'),
                        'tgl_masuk_nas_new' => Carbon::createFromFormat('d F Y', $naskah->tanggal_masuk_naskah)
                            ->format('Y-m-d') == Carbon::createFromFormat('d F Y', $request->input('edit_tanggal_masuk_naskah'))
                            ->format('Y-m-d') ? NULL : Carbon::createFromFormat('d F Y', $request->input('edit_tanggal_masuk_naskah'))
                            ->format('Y-m-d'),
                        'sumber_naskah_his' => $naskah->sumber_naskah == json_encode($request->input('edit_sumber_naskah')) ? NULL : $naskah->sumber_naskah,
                        'sumber_naskah_new' => $naskah->sumber_naskah == json_encode($request->input('edit_sumber_naskah')) ? NULL : json_encode($request->input('edit_sumber_naskah')),
                        'cdqr_code_his' => $naskah->cdqr_code == $request->input('edit_cdqr_code') ? NULL : $naskah->cdqr_code,
                        'cdqr_code_new' => $naskah->cdqr_code == $request->input('edit_cdqr_code') ? NULL : $request->input('edit_cdqr_code'),
                        'pic_prodev_his' => $naskah->pic_prodev == $request->input('edit_pic_prodev') ? NULL : $naskah->pic_prodev,
                        'pic_prodev_new' => $naskah->pic_prodev == $request->input('edit_pic_prodev') ? NULL : $request->input('edit_pic_prodev'),
                        'penulis_new' => json_encode($daftarPenulis),
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                        'author_id' => auth()->id()
                    ];
                    event(new NaskahEvent($insertHistoryEdit));
                    DB::commit();
                    return;
                } catch (\Exception $e) {
                    DB::rollback();
                    return abort(500, $e->getMessage());
                }
            } else {
                return abort(404);
            }
        }
        $kbuku = DB::table('penerbitan_m_kelompok_buku')->get();
        $user = DB::table('users as u')->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->where('j.nama', 'LIKE', '%Prodev%')
            ->select('u.nama', 'u.id')
            ->get();
        $hcsc = ['HC', 'SC'];
        return view('penerbitan.naskah.update-naskah', [
            'kbuku' => $kbuku,
            'user' => $user,
            'hcsc' => $hcsc,
            'title' => 'Update Naskah',
        ]);
    }

    public function viewNaskah(Request $request)
    {
        $naskah = DB::table('penerbitan_naskah as pn')
            ->leftJoin('penerbitan_naskah_files as pnf', 'pn.id', '=', 'pnf.naskah_id')
            ->leftJoin('penerbitan_m_kelompok_buku as pmkb', 'pn.kelompok_buku_id', '=', 'pmkb.id')
            ->leftJoin('users as u', 'pn.pic_prodev', '=', 'u.id')
            ->whereNull('pn.deleted_at')
            ->where('pn.id', $request->id)
            ->select(DB::raw('pn.*, pmkb.nama as kelompok_buku, u.nama as npic_prodev'))
            ->first();
        if (is_null($naskah)) {
            abort(404);
        }

        $naskah = (object)collect($naskah)->map(function ($item, $key) {
            switch ($key) {
                case 'tanggal_masuk_naskah':
                    return $item != '' ? Carbon::parse($item)->translatedFormat('l, d F Y') : '-';
                case 'cdqr_code':
                    return $item ? 'Ya' : 'Tidak';
                default:
                    return $item;
            }
        })->all();


        // Penilaian Naskah
        if (Gate::allows('do_update', 'naskah-pn-prodev')) {
            $startPn = 'prodev';
        } elseif (Gate::allows('do_update', 'naskah-pn-editor')) {
            $startPn = 'editor';
        } elseif (Gate::allows('do_update', 'naskah-pn-setter')) {
            $startPn = 'setter';
        } elseif (Gate::allows('do_update', 'naskah-pn-mpemasaran')) {
            $startPn = 'mpemasaran';
        } elseif (Gate::allows('do_update', 'naskah-pn-dpemasaran')) {
            $startPn = 'dpemasaran';
        } elseif (Gate::allows('do_update', 'naskah-pn-mpenerbitan')) {
            $startPn = 'mpenerbitan';
        } elseif (Gate::allows('do_update', 'naskah-pn-direksi')) {
            $startPn = 'direksi';
        } else {
            $startPn = 'guest';
        }

        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', 'pnp.penulis_id', '=', 'pp.id')
            ->where('pnp.naskah_id', $naskah->id)->select('pp.id', 'pp.nama')->get();

        return view('penerbitan.naskah.detail-naskah', [
            'naskah' => $naskah, 'penulis' => $penulis, 'startPn' => $startPn,
            'title' => 'Detail Naskah'
        ]);
    }

    public function tandaDataLengkap(Request $request)
    {
        try {
            $id = $request->id;
            // return response()->json($id);
            $data = DB::table('penerbitan_naskah as pn')->where('id', $id)->first();
            if (is_null($data)) {
                abort(404);
            }
            $penulis = DB::table('penerbitan_naskah_penulis as pnp')
                ->join('penerbitan_penulis as pp', 'pnp.penulis_id', '=', 'pp.id')
                ->where('pnp.naskah_id', $id)
                ->get();
            foreach ($penulis as $pen) {
                $resPen = DB::table('penerbitan_penulis')
                    ->where('id', $pen->penulis_id)
                    ->whereNotNull('tanggal_lahir')
                    ->whereNotNull('tempat_lahir')
                    ->whereNotNull('kewarganegaraan')
                    ->whereNotNull('alamat_domisili')
                    ->whereNotNull('ponsel_domisili')
                    ->whereNotNull('telepon_domisili')
                    ->whereNotNull('email')
                    ->whereNotNull('bank')
                    ->whereNotNull('bank_atasnama')
                    ->whereNotNull('no_rekening')
                    ->whereNotNull('ktp')
                    ->whereNotNull('scan_ktp')
                    ->whereNotNull('url_tentang_penulis')
                    ->first();
                if (is_null($resPen)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data Penulis belum dilengkapi!',
                    ]);
                }
            }
            $createDespro = [
                'params' => 'Create Despro',
                'id' => Uuid::uuid4()->toString(),
                'naskah_id' => $id,
                'status' => 'Antrian'
            ];
            event(new DesproEvent($createDespro));
            $buktiEmail = [
                'params' => 'Bukti Email',
                'id' => $id,
                'bukti_email_penulis' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ];
            event(new NaskahEvent($buktiEmail));
            $buktiEmailHistory = [
                'params' => 'Bukti Email History',
                'type_history' => 'Sent Email',
                'naskah_id' => $id,
                'bukti_email_penulis' => $buktiEmail['bukti_email_penulis'],
                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'author_id' => auth()->id()
            ];
            event(new NaskahEvent($buktiEmailHistory));
            return redirect()->json([
                'status' => 'success',
                'message' => 'Naskah selesai, silahkan lanjut pada proses Deskripsi Produk'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function lihatHistoryNaskah(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('penerbitan_naskah_history as pnh')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'pnh.naskah_id')
                ->join('users as u', 'pnh.author_id', '=', 'u.id')
                ->where('pnh.naskah_id', $id)
                ->select('pnh.*', 'u.nama')
                ->orderBy('pnh.id', 'desc')
                ->paginate(2);
            foreach ($data as $key => $d) {
                if ($d->type_history == 'Sent Email') {
                    $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title">
                        <span><span class="bullet"></span> Penulis telah selesai melengkapi naskah. Selanjutnya, naskah akan diproses pada tahap deskripsi produk.</span>
                    </div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>
                    </div>
                    </span>';
                } elseif ($d->type_history == 'Update') {
                    $html .= '<span class="ticket-item" id="newAppend">
                    <div class="ticket-title"><span><span class="bullet"></span>';
                    if (!is_null($d->judul_asli_his)) {
                        $html .= ' Judul asli <b class="text-dark">' . $d->judul_asli_his . '</b> diubah menjadi <b class="text-dark">' . $d->judul_asli_new . '</b>.<br>';
                    }
                    if (!is_null($d->kelompok_buku_his)) {
                        $html .= ' Kelompok buku <b class="text-dark">' . DB::table('penerbitan_m_kelompok_buku')->where('id', $d->kelompok_buku_his)->whereNull('deleted_at')->first()->nama . '</b> diubah menjadi <b class="text-dark">' . DB::table('penerbitan_m_kelompok_buku')->where('id', $d->kelompok_buku_new)->whereNull('deleted_at')->first()->nama . '</b>.<br>';
                    }
                    if (!is_null($d->tgl_masuk_nas_his)) {
                        $html .= ' Tanggal masuk naskah <b class="text-dark">' . Carbon::parse($d->tgl_masuk_nas_his)->translatedFormat('l d F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->tgl_masuk_nas_new)->translatedFormat('l d F Y') . '</b>.<br>';
                    }
                    if (!is_null($d->sumber_naskah_his)) {
                        $loopSN = '';
                        $loopSNN = '';
                        foreach (json_decode($d->sumber_naskah_his, true) as $fc) {
                            $loopSN .= '<b class="text-dark">' . ($fc == 'SC' ? 'Soft Copy' : 'Hard Copy') . '</b>, ';
                        }
                        foreach (json_decode($d->sumber_naskah_new, true) as $fcn) {
                            $loopSNN .= '<span class="bullet"></span>' . ($fcn == 'SC' ? 'Soft Copy' : 'Hard Copy');
                        }
                        $html .= ' Sumber Naskah <b class="text-dark">' . $loopSN . '</b>diubah menjadi <b class="text-dark">' . $loopSNN . '</b>.<br>';
                    }
                    if (!is_null($d->cdqr_code_his)) {
                        $html .= ' Cdqr code <b class="text-dark">' . $d->cdqr_code_his . '</b> diubah menjadi <b class="text-dark">' . $d->cdqr_code_new . '</b>.<br>';
                    }
                    if (!is_null($d->pic_prodev_his)) {
                        $html .= ' PIC prodev <b class="text-dark">' . DB::table('users')->where('id', $d->pic_prodev_his)->whereNull('deleted_at')->first()->nama . '</b> diubah menjadi <b class="text-dark">' . DB::table('users')->where('id', $d->pic_prodev_new)->whereNull('deleted_at')->first()->nama . '</b>.<br>';
                    }
                    $html .= '</span></div>
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                    </div>
                    </span>';
                }
            }
            return $html;
        }
    }
    public static function generateId()
    {
        $last = DB::table('penerbitan_naskah')
            ->select('kode')
            ->where('created_at', '>', strtotime(date('Y-m-d') . '00:00:01'))
            ->orderBy('created_at', 'desc')
            ->first();

        if (is_null($last)) {
            $id = 'NA' . date('Ymd') . '001';
        } else {
            $id = (int)substr($last->kode, -3);
            $id = 'NA' . date('Ymd') . sprintf('%03s', $id + 1);
        }
        return $id;
    }

    protected function alurPenilaian($jalbuk, $action = null, $data = null)
    {
        /*
            Reguler :: Prodev -> Pemasaran -> Penerbitan -> Direksi
            MoU :: Tidak Ada Penilaian
            ProLiterasi :: Editor/Setter
            MoU-Reguler :: Prodev -> Pemasaran -> Penerbitan -> Direksi
            SMK/NonSMK :: Tidak ada Penilaian
            ====================================================================
            Prodev :: ebca07da8aad42c4aee304e3a6b81001(naskah-pn-prodev)
            Pemasaran :: a213b689b8274f4dbe19b3fb24d66840(naskah-pn-pemasaran)
            M.Pemasaran :: 9beba245308543ce821efe8a3ba965e3(naskah-pn-mpemasaran)
            Editor:: 5d793b19c75046b9a4d75d067e8e33b2(naskah-pn-editor)
            Setter :: 33c3711d787d416082c0519356547b0c(naskah-pn-setter)
            Operasional(M.Penerbitan) :: 12b852d92d284ab5a654c26e8856fffd(naskah-pn-mpenerbitan)
            Direksi :: 8791f143a90e42e2a4d1d0d6b1254bad(naskah-pn-direksi)

        */

        if ($jalbuk == 'Reguler' or $jalbuk == 'MoU-Reguler') {
            if ($action == 'create-notif-from-naskah') {
                $id_notif = Str::uuid()->getHex();
                DB::table('notif')->insert([
                    [
                        'id' => $id_notif,
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => 'ebca07da8aad42c4aee304e3a6b81001', // Prodev
                        'form_id' => $data['form_id'],
                    ],
                    [
                        'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => 'a213b689b8274f4dbe19b3fb24d66840', // M.Pemasaran
                        'form_id' => $data['form_id'],
                    ],
                    [
                        'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => '9beba245308543ce821efe8a3ba965e3', // D.Pemasaran
                        'form_id' => $data['form_id'],
                    ],
                    [
                        'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => '12b852d92d284ab5a654c26e8856fffd', // M.Penerbitan
                        'form_id' => $data['form_id'],
                    ],
                ]);
                DB::table('notif_detail')->insert([
                    'notif_id' => $id_notif,
                    'user_id' => $data['id_prodev']
                ]);

                return ['selesai_penilaian' => 0, 'penilaian_naskah' => 1];
            } elseif ($action == 'update-notif-from-naskah') {
                $notif = DB::table('notif')->whereNull('expired')->where('permission_id', 'ebca07da8aad42c4aee304e3a6b81001') // Hanya untuk prodev
                    ->where('form_id', $data['form_id'])->first();
                DB::table('notif_detail')->where('notif_id', $notif->id)
                    ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                DB::table('notif_detail')->insert([
                    'notif_id' => $notif->id,
                    'user_id' => $data['id_prodev']
                ]);
            }
        } elseif ($jalbuk == 'Pro Literasi') {
            if ($action == 'create-notif-from-naskah') {
                DB::table('notif')->insert([
                    [
                        'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => '5d793b19c75046b9a4d75d067e8e33b2', // Editor
                        'form_id' => $data['form_id'],
                    ],
                    [
                        'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => '33c3711d787d416082c0519356547b0c', // Setter
                        'form_id' => $data['form_id'],
                    ],
                ]);

                return ['selesai_penilaian' => 0, 'penilaian_naskah' => 1];
            } elseif ($action == 'update-notif-from-naskah') {
            }
        } else { // Jalur Buku MoU & SMK/NonSMK
            if ($action == 'create-notif-from-naskah') {
                return ['selesai_penilaian' => 1, 'penilaian_naskah' => 0];
            } elseif ($action == 'update-notif-from-naskah') {
            }
        }
    }

    public function timelineNaskah(Request $request)
    {
        switch ($request->cat) {
            case 'timeline':
                return $this->timeline($request);
            case 'date-timeline':
                return $this->dateTimeline($request);
            case 'view-timeline':
                return $this->viewTimeline($request);
            case 'subtimeline':
                return $this->subTimeline($request);
            default:
                abort(404);
        }
    }

    protected function timeline($request)
    {
        if ($request->input('request_') == 'form') { // Load modal
            $naskah = DB::table('penerbitan_naskah as pn')->whereNull('deleted_at')
                ->where('id', $request->input('id'))->first();
            $timeline = [];

            if ($request->input('method_') == 'add') {
                $arrPic = [];
            } elseif ($request->input('method_') == 'edit') {
                $timeline = DB::table('timeline as t')->join('timeline_detail as td', 't.id', '=', 'td.timeline_id')
                    ->where('t.naskah_id', $request->input('id'))->get();

                foreach ($timeline as $t) {
                    if ($t->proses == 'Proses Produksi') {
                        $produksi = Carbon::createFromFormat('Y-m-d', $t->tanggal_mulai)->format('d M Y') . ' - ' .
                            Carbon::createFromFormat('Y-m-d', $t->tanggal_selesai)->format('d M Y');
                    } else {
                        $penerbitan = Carbon::createFromFormat('Y-m-d', $t->tanggal_mulai)->format('d M Y') . ' - ' .
                            Carbon::createFromFormat('Y-m-d', $t->tanggal_selesai)->format('d M Y');
                    }
                    $tgl_buku_jadi = $t->tgl_buku_jadi;
                }

                $timeline->produksi = $produksi;
                $timeline->penerbitan = $penerbitan;
                $timeline->tgl_buku_jadi = Carbon::createFromFormat('Y-m-d', $tgl_buku_jadi)->format('d M Y');
            } else {
                return abort(404);
            }

            $naskah = (object)collect($naskah)->map(function ($v, $i) {
                if ($i == 'tanggal_masuk_naskah') {
                    return Carbon::createFromFormat('Y-m-d', $v)->format('d M Y');
                } else {
                    return $v;
                }
            })->all();

            return view('penerbitan.naskah.page.modal-timeline', [
                'naskah' => $naskah,
                'method' => $request->input('method_'),
                'timeline' => $timeline,
                'title' => 'Timeline Naskah',
            ]);
        } elseif ($request->input('request_') == 'submit') {
            $tlId = Str::uuid()->getHex();
            $tlDet = [];
            $subtl = [];

            try {
                $master = DB::table('timeline_m_tb')->whereNull('deleted_at')->orderBy('order_tb')->get();
                foreach ($master->where('kategori', 'PROSES')->all() as $v) {
                    $masterSubTl = DB::table('timeline_m_subtl as ms')
                        ->join('timeline_m_tb as mt', 'ms.bagian', '=', 'mt.detail')
                        ->where('ms.proses', $v->detail)
                        ->whereNull('ms.deleted_at')->get();

                    if ($v->detail == 'Proses Produksi') {
                        $date = explode(' - ', $request->input('add_proses_produksi'));
                    } else {
                        $date = explode(' - ', $request->input('add_proses_penerbitan'));
                    }

                    $tlDetId = Str::uuid()->getHex();
                    $tlDet[] = [
                        'id' => $tlDetId,
                        'timeline_id' => $tlId,
                        'proses' => $v->detail,
                        'tanggal_mulai' => Carbon::createFromFormat('d M Y', $date[0])->format('Y-m-d'),
                        'tanggal_selesai' => Carbon::createFromFormat('d M Y', $date[1])->format('Y-m-d'),
                    ];
                    foreach ($masterSubTl as $vv) {
                        $subtl[] = [
                            'id' => Str::uuid()->getHex(),
                            'timeline_det_id' => $tlDetId,
                            'deskripsi' => $vv->deskripsi,
                            'pic' => $vv->user_id,
                            'created_by' => auth()->id()
                        ];
                    }
                }

                DB::beginTransaction();

                DB::table('timeline')->insert([
                    'id' => $tlId,
                    'naskah_id' => $request->input('add_tl_naskah_id'),
                    'tgl_masuk_naskah' => Carbon::createFromFormat('d M Y', $request->input('add_naskah_masuk'))->format('Y-m-d'),
                    'tgl_buku_jadi' => Carbon::createFromFormat('d M Y', $request->input('add_buku_jadi'))->format('Y-m-d'),
                    'created_by' => auth()->id()
                ]);
                DB::table('timeline_detail')->insert($tlDet);
                DB::table('timeline_sub')->insert($subtl);

                DB::commit();
                return;
            } catch (\Exception $e) {
                return $e->getMessage();
            }


            die();
        } else {
            abort(404);
        }
    }

    protected function subTimeline($request)
    {
        if ($request->input('category') == 'update-subtl') {
            $id = explode('_', $request->input('id'));
            return DB::table('timeline_sub')->where('id', $id[1])
                ->update([
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->id()
                ]);
        } elseif ($request->input('category') == 'add-subtl') {
            return DB::table('timeline_sub')->insert([
                'id' => Str::uuid()->getHex(),
                'timeline_det_id' => $request->input('id'),
                'deskripsi' => $request->input('desc'),
                'pic' => auth()->id(),
                'created_by' => auth()->id()
            ]);
        } else {
            abort(404);
        }
    }

    public function ajaxFromCalendar(Request $request, $cat)
    {
        switch ($request->cat) {
            case 'events':
                return $this->fCalendarEvents($request);
                break;
            case 'details':
                return $this->fCalendarDetails($request);
                break;
            default:
                abort(404);
        }
    }

    protected function fCalendarEvents($request)
    {
        $data = DB::table('penerbitan_naskah as pn')
            ->leftJoin('timeline as t', 'pn.id', '=', 't.naskah_id')
            ->select(DB::raw(' pn.id, pn.judul_asli, pn.tanggal_masuk_naskah AS tgl_naskah_masuk,
                    DATE_FORMAT(t.tgl_buku_jadi, "%Y-%m-%d") AS tgl_buku_jadi'))
            ->where(function ($q) use ($request) {
                return $q->where('pn.tanggal_masuk_naskah', '>', $request->input('start'))
                    ->where('pn.tanggal_masuk_naskah', '<', $request->input('end'));
            })
            ->orWhere(function ($q) use ($request) {
                return $q->where('t.tgl_buku_jadi', '>', $request->input('start'))
                    ->where('t.tgl_buku_jadi', '<', $request->input('end'));
            })->get();
        $result = [];

        foreach ($data as $d) {
            $result[] = [
                'id' => $d->id,
                'title' => $d->judul_asli,
                'start' => is_null($d->tgl_buku_jadi) ? $d->tgl_naskah_masuk : $d->tgl_buku_jadi,
                'classNames' => [is_null($d->tgl_buku_jadi) ? 'nMasuk' : 'nJadi']
            ];
        }

        return $result;
    }

    protected function fCalendarDetails($request)
    {
        $naskah = DB::table('penerbitan_naskah')
            ->where('id', $request->input('id'))
            ->select(DB::raw('
                        id, kode, judul_asli, DATE_FORMAT(tanggal_masuk_naskah, "%e %M %Y") AS
                        tanggal_masuk_naskah, jalur_buku
                    '))
            ->first();
        $timeline = DB::table('timeline')
            ->where('naskah_id', $naskah->id)
            ->select(DB::raw('
                            DATE_FORMAT(tgl_naskah_masuk, "%e %M %Y") AS tgl_naskah_masuk,
                            DATE_FORMAT(tgl_mulai_penerbitan, "%e %M %Y") AS tgl_mulai_penerbitan,
                            DATE_FORMAT(tgl_mulai_produksi, "%e %M %Y") AS tgl_mulai_produksi,
                            DATE_FORMAT(tgl_buku_jadi, "%e %M %Y") AS tgl_buku_jadi,
                            ttl_hari_penerbitan, ttl_hari_produksi
                        '))
            ->first();

        return [
            'naskah' => $naskah, 'timeline' => $timeline
        ];
    }
}
