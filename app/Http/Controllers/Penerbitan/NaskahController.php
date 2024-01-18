<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Events\DesproEvent;
use App\Events\NaskahEvent;
use Illuminate\Support\Str;
use App\Events\TrackerEvent;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use PhpParser\Node\Stmt\Catch_;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class NaskahController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            switch ($request->input('request_')) {
                case 'getModalDetailJumlahPenilaian':
                    return $this->showModalJumlahPenilaian($request);
                    break;
                case 'getCountNaskahReguler':
                    $html = '';
                    $newData = DB::table('penerbitan_naskah as pn')
                        ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                        ->whereNull('pn.deleted_at')
                        ->where('pn.jalur_buku', 'LIKE', '%Reguler%');
                    if ((Gate::allows('do_update', 'naskah-pn-prodev')) && (auth()->user()->id != 'be8d42fa88a14406ac201974963d9c1b')) {
                        $newData->where('pn.pic_prodev', auth()->user()->id);
                    }
                    $data = DB::table('penerbitan_naskah as pn')
                        ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                        ->whereNull('pn.deleted_at')
                        ->where('pn.jalur_buku', 'LIKE', '%Reguler%');
                    if ((Gate::allows('do_update', 'naskah-pn-prodev')) && (auth()->user()->id != 'be8d42fa88a14406ac201974963d9c1b')) {
                        $data->where('pn.pic_prodev', auth()->user()->id);
                    }
                    $html .= '<h4 class="section-title">Total naskah reguler yang belum dinilai: <b id="naskahBelumDinilai">0</b></h4>';
                    $html .= '<hr style="border: 0;
                    height: 1px;
                    background: #333;
                    background-image: linear-gradient(to right, #ccc, #333, #ccc);">';
                    if (auth()->user()->id == 'be8d42fa88a14406ac201974963d9c1b') {
                        //Prodev
                        $html .= '<a href="javascript:void(0)" class="action-modal text-decoration-none" data-role="prodev">
                        <span class="badge badge-danger" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian Prodev: <b id="animate-count-prodev">0</b></span>
                        </a>&nbsp;';
                        //M Penerbitan
                        $html .= '<a href="javascript:void(0)" class="action-modal text-decoration-none" data-role="mpenerbitan">
                        <span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian M Penerbitan: <b id="animate-count-mpenerbitan">0</b></span>
                        </a>&nbsp;';
                        //M Pemasaran
                        $html .= '<a href="javascript:void(0)" class="action-modal text-decoration-none" data-role="mpemasaran">
                        <span class="badge badge-info" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian M Pemasaran: <b id="animate-count-mpemasaran">0</b></span>
                        </a>&nbsp;';
                        //D Pemasaran
                        $html .= '<a href="javascript:void(0)" class="action-modal text-decoration-none" data-role="dpemasaran">
                        <span class="badge badge-light" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian D Pemasaran: <b id="animate-count-dpemasaran">0</b></span>
                        </a>&nbsp;';
                        //Direksi
                        $html .= '<a href="javascript:void(0)" class="action-modal text-decoration-none" data-role="direksi">
                        <span class="badge badge-secondary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian Direksi: <b id="animate-count-direksi">0</b></span>
                        </a>';
                    } else {
                        //Prodev
                        $html .= '<span class="badge badge-danger" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian Prodev: <b id="animate-count-prodev">0</b></span>&nbsp;';
                        //M Penerbitan
                        $html .= '<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian M Penerbitan: <b id="animate-count-mpenerbitan">0</b></span>&nbsp;';
                        //M Pemasaran
                        $html .= '<span class="badge badge-info" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian M Pemasaran: <b id="animate-count-mpemasaran">0</b></span>&nbsp;';
                        //D Pemasaran
                        $html .= '<span class="badge badge-light" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian D Pemasaran: <b id="animate-count-dpemasaran">0</b></span>&nbsp;';
                        //Direksi
                        $html .= '<span class="badge badge-secondary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i class="fas fa-star-half-alt"></i> Penilaian Direksi: <b id="animate-count-direksi">0</b></span>';
                    }
                    return response()->json([
                        'html' => $html,
                        'belumDinilai' => $newData->whereNull('pns.tgl_pn_prodev')->get()->count(),
                        'countProdev' => $data->whereNotNull('pns.tgl_pn_prodev')->get()->count(),
                        'countMPenerbitan' => $data->whereNotNull('pns.tgl_pn_m_penerbitan')->get()->count(),
                        'countMPemasaran' => $data->whereNotNull('pns.tgl_pn_m_pemasaran')->get()->count(),
                        'countDPemasaran' => $data->whereNotNull('pns.tgl_pn_d_pemasaran')->get()->count(),
                        'countDireksi' => $data->whereNotNull('pns.tgl_pn_direksi')->get()->count(),
                    ]);

                    break;
                case 'getCountNaskah':
                    $data = DB::table('penerbitan_naskah as pn')
                        ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                        ->whereNull('pn.deleted_at');
                    if ((Gate::allows('do_update', 'naskah-pn-prodev')) && (auth()->user()->id != 'be8d42fa88a14406ac201974963d9c1b')) {
                        $data->where('pic_prodev', auth()->user()->id);
                    }
                    $data->select(
                        'pn.id',
                        'pn.kode',
                        'pn.judul_asli',
                        'pn.pic_prodev',
                        'pn.created_by',
                        'pn.jalur_buku',
                        'pn.tanggal_masuk_naskah',
                        'pn.selesai_penilaian',
                        'pn.bukti_email_penulis',
                        'pn.urgent',
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
                    return $data->count();
                    break;
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
                    if ((Gate::allows('do_update', 'naskah-pn-prodev')) && (auth()->user()->id != 'be8d42fa88a14406ac201974963d9c1b')) {
                        $data = DB::table('penerbitan_naskah as pn')
                            ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                            ->whereNull('pn.deleted_at')->where('pic_prodev', auth()->user()->id)->select(
                                'pn.id',
                                'pn.kode',
                                'pn.judul_asli',
                                'pn.pic_prodev',
                                'pn.created_by',
                                'pn.jalur_buku',
                                'pn.tanggal_masuk_naskah',
                                'pn.selesai_penilaian',
                                'pn.bukti_email_penulis',
                                'pn.urgent',
                                'pns.tgl_pn_prodev',
                                'pns.tgl_pn_m_penerbitan',
                                'pns.tgl_pn_m_pemasaran',
                                'pns.tgl_pn_d_pemasaran',
                                'pns.tgl_pn_direksi',
                                'pns.tgl_pn_editor',
                                'pns.tgl_pn_setter',
                                'pns.tgl_pn_selesai'
                            )
                            ->orderBy('pn.tanggal_masuk_naskah', 'desc')
                            ->get();
                    } else {
                        $data = DB::table('penerbitan_naskah as pn')
                            ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                            ->whereNull('pn.deleted_at')->select(
                                'pn.id',
                                'pn.kode',
                                'pn.judul_asli',
                                'pn.pic_prodev',
                                'pn.created_by',
                                'pn.jalur_buku',
                                'pn.tanggal_masuk_naskah',
                                'pn.selesai_penilaian',
                                'pn.bukti_email_penulis',
                                'pn.urgent',
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
                    }
                    $update = Gate::allows('do_update', 'ubah-data-naskah');

                    return Datatables::of($data)
                        ->addColumn('kode', function ($data) {
                            $html = '';
                            $danger = $data->urgent == '0' ? '' : 'class="text-danger"';
                            $tooltip =  $data->urgent == '0' ? '' : 'data-toggle="tooltip" data-placement="top" title="Naskah Urgent" style="cursor:context-menu"';
                            $html .= '<span ' . $danger . ' ' . $tooltip . '>' . $data->kode . '</span>';
                            return $html;
                        })
                        ->addColumn('judul_asli', function ($data) {
                            return $data->judul_asli;
                        })
                        ->addColumn('pic_prodev', function ($data) {
                            return DB::table('users')->where('id', $data->pic_prodev)->first()->nama;
                        })
                        ->addColumn('jalur_buku', function ($data) {
                            return $data->jalur_buku;
                        })
                        ->addColumn('masuk_naskah', function ($data) {
                            return Carbon::parse($data->tanggal_masuk_naskah)->translatedFormat('l, d F Y');
                        })
                        ->addColumn('created_by', function ($data) {
                            return DB::table('users')->where('id', $data->created_by)->first()->nama;
                        })
                        ->addColumn('stts_penilaian', function ($data) {
                            $badge = '';
                            if (in_array($data->jalur_buku, ['Reguler', 'MoU-Reguler'])) {
                                if (!is_null($data->tgl_pn_selesai)) {
                                    $statusPenilaianDB = DB::table('penerbitan_pn_direksi')->where('naskah_id', $data->id)->pluck('keputusan_final')->first();
                                    $badge .= '<span class="badge badge-primary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Diputuskan: ' . $statusPenilaianDB . '</span>';
                                    if ($data->pic_prodev == auth()->user()->id) {
                                        if (is_null($data->bukti_email_penulis)) {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                                            $badge .= '&nbsp;|&nbsp;<a href="javascript:void(0)" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" class="text-primary mark-sent-email">Tandai data lengkap</a>';
                                        } else {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                                        }
                                    } else {
                                        if (is_null($data->bukti_email_penulis)) {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                                        } else {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                                        }
                                    }
                                } else {
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_prodev) ? 'danger' : 'success') . ' mr-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Prodev' . (is_null($data->tgl_pn_prodev) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_prodev)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_m_penerbitan) ? 'danger' : 'success') . ' mr-1 mt-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">M.Penerbitan' . (is_null($data->tgl_pn_m_penerbitan) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_m_penerbitan)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_m_pemasaran) ? 'danger' : 'success') . ' mr-1 mt-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">M.Pemasaran' . (is_null($data->tgl_pn_m_pemasaran) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_m_pemasaran)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_d_pemasaran) ? 'danger' : 'success') . ' mr-1 mt-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">D.Pemasaran' . (is_null($data->tgl_pn_d_pemasaran) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_d_pemasaran)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                    $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_direksi) ? 'danger' : 'success') . ' mr-1 mt-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Direksi' . (is_null($data->tgl_pn_direksi) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_direksi)->translatedFormat('d M Y, H:i:s')) . '</span>';
                                }
                            } elseif ($data->jalur_buku == 'Pro Literasi') {
                                if (!is_null($data->tgl_pn_selesai)) {
                                    $badge .= '<span class="badge badge-primary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Selesai Dinilai</span>';
                                    if ($data->pic_prodev == auth()->user()->id) {
                                        if (is_null($data->bukti_email_penulis)) {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                                            $badge .= '&nbsp;|&nbsp;<a href="javascript:void(0)" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" class="text-primary mark-sent-email">Tandai data lengkap</a>';
                                        } else {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                                        }
                                    } else {
                                        if (is_null($data->bukti_email_penulis)) {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                                        } else {
                                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                                        }
                                    }
                                } else {
                                    $badge .= '<span class="badge badge-' . (is_null($data->tgl_pn_editor) ? 'danger' : 'success') . '" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Editor</span>';
                                    $badge .= '<span class="badge badge-' . (is_null($data->tgl_pn_setter) ? 'danger' : 'success') . '" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Setter</span>';
                                }
                            } else {
                                $badge .= '<span class="badge badge-primary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Tidak Dinilai</span>';
                                if ($data->pic_prodev == auth()->user()->id) {
                                    if (is_null($data->bukti_email_penulis)) {
                                        $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                                        $badge .= '&nbsp;|&nbsp;<a href="javascript:void(0)" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" class="text-primary mark-sent-email">Tandai sudah lengkap</a>';
                                    } else {
                                        $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                                    }
                                } else {
                                    if (is_null($data->bukti_email_penulis)) {
                                        $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                                    } else {
                                        $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                                    }
                                }
                            }

                            return $badge;
                        })
                        ->addColumn('action', function ($data) use ($update) {
                            $btn = '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $data->id) . '"
                                class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" data-placement="top" title="Lihat Data">
                                <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if ($update) {
                                if ((auth()->id() == $data->pic_prodev) || (auth()->id() == $data->created_by) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') || (Gate::allows('do_approval', 'approval-deskripsi-produk'))) {
                                    $btn .= '<a href="' . url('penerbitan/naskah/mengubah-naskah/' . $data->id) . '"
                                        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                        <div><i class="fas fa-edit"></i></div></a>';
                                }
                            }
                            if ((Gate::allows('do_delete', 'delete-data-naskah'))) {
                                $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-delete-naskah btn-danger btn-icon mr-1 mt-1"
                                data-toggle="tooltip" title="Hapus Data"
                                data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '">
                                <i class="fas fa-trash-alt"></i></a>';
                            }
                            $historyData = DB::table('penerbitan_naskah_history')->where('naskah_id', $data->id)->get();
                            if (!$historyData->isEmpty()) {
                                $btn .= '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 mt-1 btn-history" data-id="' . $data->id . '" data-judulasli="' . $data->judul_asli . '" data-toggle="tooltip" title="History">
                                <i class="fas fa-history"></i>&nbsp;History</button>';
                            }
                            $trackerData = DB::table('tracker')->where('section_id', $data->id)->get();
                            if (!$trackerData->isEmpty()) {
                                $btn .= '<button type="button" class="btn btn-sm btn-info btn-icon mr-1 mt-1 btn-tracker" data-id="' . $data->id . '" data-judulasli="' . $data->judul_asli . '" data-toggle="tooltip" title="Tracker">
                                <i class="fas fa-file-signature"></i>&nbsp;Tracking</button>';
                            }
                            return $btn;
                        })
                        ->rawColumns(['kode', 'judul_asli', 'pic_prodev', 'jalur_buku', 'masuk_naskah', 'created_by', 'stts_penilaian', 'action'])
                        ->make(true);
                    break;
            }
        }
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
            'jalur_b' => $jalurB
        ]);
    }
    protected function showModalJumlahPenilaian($request)
    {
        try {
            $title = '';
            $data = DB::table('penerbitan_naskah as pn')
                ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id');
            switch ($request->role) {
                case 'prodev':
                    $data->join('penerbitan_pn_prodev as ppp', 'pn.id', '=', 'ppp.naskah_id')
                        ->join('users as u', 'ppp.created_by', '=', 'u.id')
                        ->whereNull('pn.deleted_at')
                        ->where('pn.jalur_buku', 'LIKE', '%Reguler%')
                        ->whereNotNull('pns.tgl_pn_prodev')
                        ->orderBy('pns.tgl_pn_prodev', 'desc')
                        ->select('pn.*', 'pns.tgl_pn_prodev as tgl', 'u.id as users_id', 'u.avatar', 'u.nama');
                    $title = 'Prodev';
                    $class = 'badge-danger';
                    $styleBox = 'rgb(252, 84, 75, 0.4) 5px 5px, rgb(252, 84, 75, 0.3) 10px 10px, rgba(252, 84, 75, 0.2) 15px 15px, rgba(252, 84, 75, 0.1) 20px 20px, rgba(252, 84, 75, 0.05) 25px 25px;';
                    $scroll = 'scrollbar-deep-red bordered-deep-red';
                    break;
                case 'mpenerbitan':
                    $data->join('penerbitan_pn_penerbitan as ppp', 'pn.id', '=', 'ppp.naskah_id')
                        ->join('users as u', 'ppp.created_by', '=', 'u.id')
                        ->whereNull('pn.deleted_at')
                        ->where('pn.jalur_buku', 'LIKE', '%Reguler%')
                        ->whereNotNull('pns.tgl_pn_m_penerbitan')
                        ->orderBy('pns.tgl_pn_m_penerbitan', 'desc')
                        ->select('pn.*', 'pns.tgl_pn_m_penerbitan as tgl', 'u.id as users_id', 'u.avatar', 'u.nama');
                    $title = 'Manajer Penerbitan';
                    $class = 'badge-success';
                    $styleBox = 'rgb(99, 237, 122, 0.4) 5px 5px, rgb(99, 237, 122, 0.3) 10px 10px, rgba(99, 237, 122, 0.2) 15px 15px, rgba(99, 237, 122, 0.1) 20px 20px, rgba(99, 237, 122, 0.05) 25px 25px;';
                    $scroll = 'scrollbar-deep-success bordered-deep-success';
                    break;
                case 'mpemasaran':
                    $data->join('penerbitan_pn_pemasaran as ppp', 'pn.id', '=', 'ppp.naskah_id')
                        ->join('users as u', 'ppp.created_by', '=', 'u.id')
                        ->where('ppp.pic', 'M')
                        ->whereNull('pn.deleted_at')
                        ->where('pn.jalur_buku', 'LIKE', '%Reguler%')
                        ->whereNotNull('pns.tgl_pn_m_pemasaran')
                        ->orderBy('ppp.created_at', 'desc')
                        ->select('pn.*', 'ppp.created_at as tgl', 'u.id as users_id', 'u.avatar', 'u.nama');
                    $title = 'Manajer Pemasaran';
                    $class = 'badge-info';
                    $styleBox = 'rgb(58, 186, 244, 0.4) 5px 5px, rgb(58, 186, 244, 0.3) 10px 10px, rgba(58, 186, 244, 0.2) 15px 15px, rgba(58, 186, 244, 0.1) 20px 20px, rgba(58, 186, 244, 0.05) 25px 25px;';
                    $scroll = 'scrollbar-deep-info bordered-deep-info';
                    break;
                case 'dpemasaran':
                    $data->join('penerbitan_pn_pemasaran as ppp', 'pn.id', '=', 'ppp.naskah_id')
                        ->join('users as u', 'ppp.created_by', '=', 'u.id')
                        ->where('ppp.pic', 'D')
                        ->whereNull('pn.deleted_at')
                        ->where('pn.jalur_buku', 'LIKE', '%Reguler%')
                        ->whereNotNull('pns.tgl_pn_d_pemasaran')
                        ->orderBy('pns.tgl_pn_d_pemasaran', 'desc')
                        ->select('pn.*', 'pns.tgl_pn_d_pemasaran as tgl', 'u.id as users_id', 'u.avatar', 'u.nama');
                    $title = 'Direktur Pemasaran';
                    $class = 'badge-light';
                    $styleBox = 'rgb(227, 234, 239, 0.4) 5px 5px, rgb(227, 234, 239, 0.3) 10px 10px, rgba(227, 234, 239, 0.2) 15px 15px, rgba(227, 234, 239, 0.1) 20px 20px, rgba(227, 234, 239, 0.05) 25px 25px;';
                    $scroll = 'scrollbar-deep-light bordered-deep-light';
                    break;
                default:
                    $data->join('penerbitan_pn_direksi as ppp', 'pn.id', '=', 'ppp.naskah_id')
                        ->join('users as u', 'ppp.created_by', '=', 'u.id')
                        ->whereNull('pn.deleted_at')
                        ->where('pn.jalur_buku', 'LIKE', '%Reguler%')
                        ->whereNotNull('pns.tgl_pn_direksi')
                        ->orderBy('pns.tgl_pn_direksi', 'desc')
                        ->select('pn.*', 'pns.tgl_pn_direksi as tgl', 'u.id as users_id', 'u.avatar', 'u.nama');
                    $title = 'Direktur Utama';
                    $class = 'badge-secondary';
                    $styleBox = 'rgb(52, 57, 94, 0.4) 5px 5px, rgb(52, 57, 94, 0.3) 10px 10px, rgba(52, 57, 94, 0.2) 15px 15px, rgba(52, 57, 94, 0.1) 20px 20px, rgba(52, 57, 94, 0.05) 25px 25px;';
                    $scroll = 'scrollbar-deep-secondary bordered-deep-secondary';
                    break;
            }

            $html = "";
            foreach ($data->get() as $d) {
                $words = explode(' ', $d->nama);
                $acronym = '';

                foreach ($words as $w) {
                    $acronym .= mb_substr($w, 0, 1);
                }
                $html .= '<li class="media">
                    <a href="' . url('storage/users/' . $d->users_id . '/' . $d->avatar) . '" data-magnify="gallery">
                        <img class="mr-3 rounded-circle" src="' . url('storage/users/' . $d->users_id . '/' . $d->avatar) . '" alt="' . Str::upper($acronym) . '"
                            width="50">
                    </a>
                    <div class="media-body">
                        <div class="media-title"><a href="' . url('/penerbitan/naskah/melihat-naskah/' . $d->id) . '" class="text-dark">' . $d->judul_asli . '</a></div>
                        <div class="text-muted text-small">by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a>
                            <div class="bullet"></div> ' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl, 'Asia/Jakarta')->diffForHumans() . '
                        </div>
                    </div>
                </li>';
            }
            return [
                'content' => $html,
                'titleModal' => 'Total Penilaian ' . $title,
                'totalNaskah' => $data->get()->count(),
                'class' => $class,
                'style' => $styleBox,
                'scroll' => $scroll
            ];
        } catch (\Exception $e) {
            return abort(500, $e);
        }
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
                    'add_pic_prodev' => 'required',
                    'add_penulis' => 'required'
                ], [
                    'required' => 'This field is requried'
                ]);
                $kodeNas = $request->input('add_kode');
                $last = DB::table('penerbitan_naskah')
                    ->select('kode')
                    ->where('kode', $kodeNas)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if (!is_null($last)) {
                    $kodeNas = self::generateId();
                }
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
                    ], $request->input('add_judul_asli'));

                    $addNaskah = [
                        'params' => 'Add Naskah',
                        'id' => $idN,
                        'kode' => $kodeNas,
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
                        'urgent' => $request->add_urgent == 'on' ? '1' : '0',
                        'penilaian_naskah' => ($penilaian['penilaian_naskah'] ? '1' : '0'),
                        'created_by' => auth()->id()
                    ];
                    event(new NaskahEvent($addNaskah));
                    $tgl = date('Y-m-d H:i:s');
                    $addTimeline = [
                        'params' => 'Insert Timeline',
                        'id' => Uuid::uuid4()->toString(),
                        'progress' => ($penilaian['selesai_penilaian'] > 0) ? 'Pelengkapan Data Penulis' : 'Penilaian Naskah',
                        'naskah_id' => $idN,
                        'tgl_mulai' => $tgl,
                        'url_action' => urlencode(URL::to('/penerbitan/naskah/melihat-naskah', $idN)),
                        'status' => 'Proses'
                    ];
                    event(new TimelineEvent($addTimeline));
                    $addPnStatus = [
                        'params' => 'Add Penilaian Status',
                        'id' => Str::uuid()->getHex(),
                        'naskah_id' => $idN,
                        'tgl_naskah_masuk' => Carbon::createFromFormat('d F Y', $request->input('add_tanggal_masuk_naskah'))
                            ->format('Y-m-d'),
                        'tgl_pn_selesai' => ($penilaian['selesai_penilaian'] > 0) ? $tgl : null
                    ];
                    event(new NaskahEvent($addPnStatus));
                    $namaUser = auth()->user()->nama;
                    $desc = 'Naskah berjudul asli <a href="' . url('penerbitan/naskah/melihat-naskah/' . $idN) . '">' . $request->input('add_judul_asli') . '</a> telah dibuat oleh <a href="' . url('/manajemen-web/user/' . auth()->id()) . '">' . ucfirst($namaUser) . '</a>.';
                    $addTracker = [
                        'id' => Uuid::uuid4()->toString(),
                        'section_id' => $idN,
                        'section_name' => 'Naskah',
                        'description' => $desc,
                        'icon' => 'fas fa-folder-plus',
                        'created_by' => auth()->id()
                    ];
                    event(new TrackerEvent($addTracker));
                    DB::commit();
                    return;
                } catch (\Exception $e) {
                    Storage::deleteDirectory('penerbitan/naskah/' . $idN);
                    DB::rollback();
                    return abort(500, $e->getMessage());
                }
            } else {
                if ($request->request_select == 'selectKategoriAll') {
                    $kbuku = DB::table('penerbitan_m_kelompok_buku')
                        ->whereNull('deleted_at')
                        ->where('nama', 'like', '%' . $request->input('term') . '%')
                        ->get();
                    return response()->json($kbuku);
                } elseif ($request->request_select == 'selectSub') {
                    $dataSelect = DB::table('penerbitan_m_s_kelompok_buku')
                        ->where('kelompok_id', $request->kelompok_id)
                        ->whereNull('deleted_at')
                        ->where('nama', 'like', '%' . $request->input('term') . '%')->get();
                    return response()->json($dataSelect);
                }
            }
        }
        $user = DB::table('users as u')->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
            ->where('j.nama', 'LIKE', '%Prodev%')
            ->select('u.nama', 'u.id')
            ->get();
        return view('penerbitan.naskah.create-naskah', [
            'kode' => self::generateId(),
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
            ->select(DB::raw('pn.*, pns.tgl_pn_prodev, pns.tgl_pn_m_penerbitan'))
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
                $disabled = is_null($naskah->tgl_pn_m_penerbitan) ? false : true;
                return ['naskah' => $naskah, 'penulis' => $penulis, 'disabled' => $disabled];
            } elseif ($request->isMethod('POST')) {
                // return response()->json($request->edit_urgent);
                $disabled = is_null($naskah->tgl_pn_m_penerbitan) ? false : true;
                if ($disabled == true) {
                    $request->validate([
                        'edit_kelompok_buku' => 'required',
                        'edit_penulis' => 'required'
                    ], [
                        'required' => 'This field is requried'
                    ]);
                } else {
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
                    if ($request->input('edit_jalur_buku') != $naskah->jalur_buku) {
                        switch ($naskah->jalur_buku) {
                            case 'MoU':
                                $prog = 'Pelengkapan Data Penulis';
                                break;
                            case 'SMK/NonSMK':
                                $prog = 'Pelengkapan Data Penulis';
                                break;
                            default:
                                $prog = 'Penilaian Naskah';
                                break;
                        }
                        switch ($request->input('edit_jalur_buku')) {
                            case 'MoU':
                                $progNew = 'Pelengkapan Data Penulis';
                                break;
                            case 'SMK/NonSMK':
                                $progNew = 'Pelengkapan Data Penulis';
                                break;
                            default:
                                $progNew = 'Penilaian Naskah';
                                break;
                        }
                        DB::table('timeline')->where('naskah_id', $naskah->id)->where('progress', $prog)->update([
                            'progress' => $progNew
                        ]);
                    }
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
                    if ($disabled == true) {
                        DB::table('penerbitan_naskah')->where('id', $naskah->id)->update([
                            'kelompok_buku_id' => $request->input('edit_kelompok_buku'),
                        ]);
                        DB::table('penerbitan_naskah_history')->insert([
                            'kelompok_buku_his' => $naskah->kelompok_buku_id == $request->input('edit_kelompok_buku') ? NULL : $naskah->kelompok_buku_id,
                            'kelompok_buku_new' => $naskah->kelompok_buku_id == $request->input('edit_kelompok_buku') ? NULL : $request->input('edit_kelompok_buku'),
                            'penulis_new' => json_encode($daftarPenulis),
                        ]);
                    } else {
                        if ($request->input('edit_pic_prodev') != $naskah->pic_prodev) { // Update Notifikasi jika naskah diubah. (Hanya prodev)
                            $this->alurPenilaian($request->input('edit_jalur_buku'), 'update-notif-from-naskah', [
                                'id_prodev' => $request->input('edit_pic_prodev'),
                                'form_id' => $naskah->id
                            ], $request->input('edit_judul_asli'));
                        }
                        if ($request->input('edit_judul_asli') != $naskah->judul_asli) {
                            DB::table('todo_list')
                                ->where('form_id', $naskah->id)
                                ->where('users_id', $naskah->pic_prodev)
                                ->update([
                                    'title' => 'Penilaian naskah berjudul "' . $request->input('edit_judul_asli') . '".'
                                ]);
                            $data = [
                                'id' => $naskah->id,
                                'judul' => $request->input('edit_judul_asli')
                            ];
                            $penerbitan = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                                ->where('p.id', '12b852d92d284ab5a654c26e8856fffd')
                                ->select('up.user_id')
                                ->get();

                            $penerbitan = (object)collect($penerbitan)->map(function ($item) use ($data) {
                                return DB::table('todo_list')
                                    ->where('form_id', $data['id'])
                                    ->where('users_id', $item->user_id)
                                    ->update([
                                        'title' => 'Penilaian naskah berjudul "' . $data['judul'] . '".'
                                    ]);
                            })->all();
                            $mpemasaran = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                                ->where('p.id', 'a213b689b8274f4dbe19b3fb24d66840')
                                ->select('up.user_id')
                                ->get();

                            $mpemasaran = (object)collect($mpemasaran)->map(function ($item) use ($data) {
                                return DB::table('todo_list')
                                    ->where('form_id', $data['id'])
                                    ->where('users_id', $item->user_id)
                                    ->update([
                                        'title' => 'Penilaian naskah berjudul "' . $data['judul'] . '".',
                                    ]);
                            })->all();
                            $dpemasaran = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                                ->where('p.id', '9beba245308543ce821efe8a3ba965e3')
                                ->select('up.user_id')
                                ->get();

                            $dpemasaran = (object)collect($dpemasaran)->map(function ($item) use ($data) {
                                return DB::table('todo_list')
                                    ->where('form_id', $data['id'])
                                    ->where('users_id', $item->user_id)
                                    ->update([
                                        'title' => 'Penilaian naskah berjudul "' . $data['judul'] . '".'
                                    ]);
                            })->all();
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
                            'urgent' => $request->edit_urgent == 'on' ? '1' : '0',
                            'updated_by' => auth()->id(),
                            'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        event(new NaskahEvent($editNaskah));
                        $urgent = $request->edit_urgent == 'on' ? '1' : '0';
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
                            'urgent' => $naskah->urgent == $urgent ? NULL : $urgent,
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                            'author_id' => auth()->id()
                        ];
                        event(new NaskahEvent($insertHistoryEdit));
                    }

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
                    return is_null($item) ? 'Tidak diinput' : ($item ? 'Ya' : 'Tidak');
                case 'urgent':
                    return $item == '1' ? 'Ya' : 'Tidak';
                    break;
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
        $drafBadge = (is_null($naskah->selesai_penilaian) ? '' : ($naskah->selesai_penilaian == '0' ? 'badge badge-info' : '')); //Draf
        $drafBadgeText = (is_null($naskah->selesai_penilaian) ? '' : ($naskah->selesai_penilaian == '0' ? 'Draf' : '')); //Draf Text
        return view('penerbitan.naskah.detail-naskah', [
            'naskah' => $naskah, 'penulis' => $penulis, 'startPn' => $startPn,
            'badgeDraf' => $drafBadge,
            'badgeDrafText' => $drafBadgeText,
            'title' => 'Detail Naskah'
        ]);
    }
    public function tandaDataLengkap(Request $request)
    {
        try {
            $id = $request->id;
            // return response()->json($id);
            DB::beginTransaction();
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
            $idProduk = Uuid::uuid4()->toString();
            $createDespro = [
                'params' => 'Create Despro',
                'id' => $idProduk,
                'naskah_id' => $id,
                'status' => 'Antrian'
            ];
            event(new DesproEvent($createDespro));
            $desc = 'Naskah berjudul asli <a href="' . url('penerbitan/naskah/melihat-naskah/' . $id) . '">' . $data->judul_asli . '</a> telah selesai dan menuju Deskripsi Produk.';
            $addTracker = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $id,
                'section_name' => 'Naskah',
                'description' => $desc,
                'icon' => 'fas fa-clipboard-check',
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($addTracker));
            $desc = 'Naskah berjudul asli <a href="' . url('penerbitan/deskripsi/produk/detail?desc=' . $idProduk . '&kode=' . $data->kode) . '">' . $data->judul_asli . '</a> telah memasuki tahap antrian Deskripsi Produk.';
            $addTracker = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $idProduk,
                'section_name' => 'Deskripsi Produk',
                'description' => $desc,
                'icon' => 'fas fa-folder-plus',
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($addTracker));
            DB::table('todo_list')
                ->where('form_id', $id)
                ->where('users_id', auth()->id())
                ->where('title', 'Tandai data naskah berjudul (' . $data->judul_asli . ') telah dilengkapi.')
                ->update([
                    'status' => '1'
                ]);
            DB::table('todo_list')->insert([
                'form_id' => $idProduk,
                'users_id' => auth()->id(),
                'title' => 'Proses deskripsi produk naskah berjudul "' . $data->judul_asli . '" perlu dilengkapi data kelengkapan penentuan judul.',
                'link' => '/penerbitan/deskripsi/produk/edit?desc=' . $idProduk . '&kode=' . $data->kode,
                'status' => '0'
            ]);
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
            switch ($data->jalur_buku) {
                case 'MoU':
                    $prog = 'Pelengkapan Data Penulis';
                    break;
                case 'SMK/NonSMK':
                    $prog = 'Pelengkapan Data Penulis';
                    break;
                default:
                    $prog = 'Penilaian Naskah';
                    break;
            }
            $updateTimeline = [
                'params' => 'Update Timeline',
                'naskah_id' => $id,
                'progress' => $prog,
                'tgl_selesai' => $buktiEmail['bukti_email_penulis'],
                'status' => 'selesai'
            ];
            event(new TimelineEvent($updateTimeline));
            $insertTimeline = [
                'params' => 'Insert Timeline',
                'id' => Uuid::uuid4()->toString(),
                'progress' => 'Deskripsi Produk',
                'naskah_id' => $id,
                'tgl_mulai' => $buktiEmail['bukti_email_penulis'],
                'url_action' => urlencode(URL::to('/penerbitan/deskripsi/produk/detail?desc=' . $idProduk . '&kode=' . $request->kode)),
                'status' => 'Antrian'
            ];
            event(new TimelineEvent($insertTimeline));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Naskah selesai, silahkan lanjut pada proses Deskripsi Produk'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function ajaxCallModal(Request $request)
    {
        switch ($request->cat) {
            case 'lihat-tracker':
                return $this->lihatTrackingNaskah($request);
                break;
            case 'lihat-history':
                return $this->lihatHistoryNaskah($request);
                break;
            case 'delete-naskah':
                return $this->deleteNaskah($request);
                break;
            case 'filter-penilaian':
                return $this->filterPenilaian($request);
                break;
            default:
                return abort(400);
                break;
        }
    }
    protected function lihatTrackingNaskah($request)
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
    protected function lihatHistoryNaskah($request)
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
                switch ($d->type_history) {
                    case 'Sent Email':
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
                        break;
                    case 'Update':
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
                        if (!is_null($d->penulis_his)) {
                            $loopPH = '';
                            $loopPN = '';
                            foreach (json_decode($d->penulis_his, true) as $ph) {
                                $ph = (object)collect($ph)->map(function ($item, $key) {
                                    switch ($key) {
                                        case 'penulis_id':
                                            return DB::table('penerbitan_m_penulis')->where('id', $item)->whereNull('deleted_at')->first()->nama;
                                            break;
                                        default:
                                            return $item;
                                            break;
                                    }
                                })->all();
                                $loopPH .= '<b class="text-dark">' . $ph->penulis_id . '</b>, ';
                            }
                            foreach (json_decode($d->penulis_new, true) as $pn) {
                                $pn = (object)collect($pn)->map(function ($item, $key) {
                                    switch ($key) {
                                        case 'penulis_id':
                                            return DB::table('penerbitan_penulis')->where('id', $item)->whereNull('deleted_at')->first()->nama;
                                            break;
                                        default:
                                            return $item;
                                            break;
                                    }
                                })->all();
                                $loopPN .= '<span class="bullet"></span>' . $pn->penulis_id;
                            }
                            $html .= ' Penulis <b class="text-dark">' . $loopPH . '</b>diubah menjadi <b class="text-dark">' . $loopPN . '</b>.<br>';
                        } elseif (!is_null($d->penulis_new)) {
                            $loopPN = '';
                            foreach (json_decode($d->penulis_new, true) as $pn) {
                                $pn = (object)collect($pn)->map(function ($item, $key) {
                                    switch ($key) {
                                        case 'penulis_id':
                                            return DB::table('penerbitan_penulis')->where('id', $item)->whereNull('deleted_at')->first()->nama;
                                            break;
                                        default:
                                            return $item;
                                            break;
                                    }
                                })->all();
                                $loopPN .= '<span class="bullet"></span>' . $pn->penulis_id;
                            }
                            $html .= ' Penulis <b class="text-dark">' . $loopPN . '</b> ditambahkan.<br>';
                        }
                        if (!is_null($d->cdqr_code_his)) {
                            $html .= ' Cdqr code <b class="text-dark">' . $d->cdqr_code_his . '</b> diubah menjadi <b class="text-dark">' . $d->cdqr_code_new . '</b>.<br>';
                        }
                        if (!is_null($d->pic_prodev_his)) {
                            $html .= ' PIC prodev <b class="text-dark">' . DB::table('users')->where('id', $d->pic_prodev_his)->whereNull('deleted_at')->first()->nama . '</b> diubah menjadi <b class="text-dark">' . DB::table('users')->where('id', $d->pic_prodev_new)->whereNull('deleted_at')->first()->nama . '</b>.<br>';
                        }
                        if (!is_null($d->urgent)) {
                            $urgent = $d->urgent == '0' ? 'Tidak Urgent' : 'Urgent';
                            $textColor = $d->urgent == '0' ? 'dark' : 'danger';
                            $html .= ' Naskah diubah menjadi <b class="text-' . $textColor . '">"' .  $urgent . '"</b>.<br>';
                        }
                        $html .= '</span></div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') . ')</div>

                        </div>
                        </span>';
                        break;
                    case 'Delete':
                        $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Data naskah dihapus pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
                            </div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . ')</div>
                            </div>
                            </span>';
                        break;
                    case 'Restore':
                        $html .= '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Data naskah dikembalikan pada <b class="text-dark">' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . '</b>.</span>
                            </div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->author_id) . '">' . $d->nama . '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') . ')</div>
                            </div>
                            </span>';
                        break;
                }
            }
            return $html;
        }
    }
    protected function deleteNaskah($request)
    {
        try {
            if ($request->ajax()) {
                $id = $request->id;
                $relation = DB::table('deskripsi_produk')->where('naskah_id', $id)->first();
                if (!is_null($relation)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Naskah tidak bisa dihapus karena telah selesai proses penilaian!'
                    ]);
                }
                $deleted = DB::table('penerbitan_naskah')->where('id', $id)->whereNotNull('deleted_at')->first();
                if ($deleted) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Naskah sudah dihapus!'
                    ]);
                }
                $data = [
                    'params' => 'Delete Naskah',
                    'type_history' => 'Delete',
                    'id' => $id,
                    'deleted_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                    'deleted_by' => auth()->id()
                ];
                event(new NaskahEvent($data));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Naskah berhasil dihapus!'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }
    protected function filterPenilaian($request)
    {
        $belum = $request->belum_dinilai;
        $sudah = $request->sudah_dinilai;
        if ((Gate::allows('do_update', 'naskah-pn-prodev')) && (auth()->user()->id != 'be8d42fa88a14406ac201974963d9c1b')) {
            $data = DB::table('penerbitan_naskah as pn')
                ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                ->where('pn.jalur_buku','Reguler')
                ->whereNull('pn.deleted_at')
                ->when(!is_null($belum), function ($query) use ($belum) {
                    return $query->whereNull('pns.'.$belum);
                })
                ->when(!is_null($sudah), function ($query) use ($sudah) {
                    if ($sudah == 'mpenerbitanpemasaran') {
                        return $query->whereNotNull('pns.tgl_pn_m_pemasaran')
                        ->whereNotNull('pns.tgl_pn_m_penerbitan');
                    }
                    return $query->whereNotNull('pns.'.$sudah);
                })
                ->whereNull('pns.tgl_pn_selesai')
                ->where('pn.pic_prodev', auth()->user()->id)->select(
                    'pn.id',
                    'pn.kode',
                    'pn.judul_asli',
                    'pn.pic_prodev',
                    'pn.created_by',
                    'pn.jalur_buku',
                    'pn.tanggal_masuk_naskah',
                    'pn.selesai_penilaian',
                    'pn.bukti_email_penulis',
                    'pn.urgent',
                    'pns.tgl_pn_prodev',
                    'pns.tgl_pn_m_penerbitan',
                    'pns.tgl_pn_m_pemasaran',
                    'pns.tgl_pn_d_pemasaran',
                    'pns.tgl_pn_direksi',
                    'pns.tgl_pn_editor',
                    'pns.tgl_pn_setter',
                    'pns.tgl_pn_selesai'
                )
                ->orderBy('pn.tanggal_masuk_naskah', 'desc')
                ->get();
        } else {
            $data = DB::table('penerbitan_naskah as pn')
                ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                ->where('pn.jalur_buku','Reguler')
                ->whereNull('pn.deleted_at')
                ->when(!is_null($belum), function ($query) use ($belum) {
                    return $query->whereNull('pns.'.$belum);
                })
                ->when(!is_null($sudah), function ($query) use ($sudah) {
                    if ($sudah == 'mpenerbitanpemasaran') {
                        return $query->whereNotNull('pns.tgl_pn_m_pemasaran')
                        ->whereNotNull('pns.tgl_pn_m_penerbitan');
                    }
                    return $query->whereNotNull('pns.'.$sudah);
                })
                ->whereNull('pns.tgl_pn_selesai')
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
                    'pn.urgent',
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
        }
        $update = Gate::allows('do_update', 'ubah-data-naskah');

        return Datatables::of($data)
            ->addColumn('kode', function ($data) {
                $html = '';
                $danger = $data->urgent == '0' ? '' : 'class="text-danger"';
                $tooltip =  $data->urgent == '0' ? '' : 'data-toggle="tooltip" data-placement="top" title="Naskah Urgent" style="cursor:context-menu"';
                $html .= '<span ' . $danger . ' ' . $tooltip . '>' . $data->kode . '</span>';
                return $html;
            })
            ->addColumn('judul_asli', function ($data) {
                return $data->judul_asli;
            })
            ->addColumn('pic_prodev', function ($data) {
                return DB::table('users')->where('id', $data->pic_prodev)->first()->nama;
            })
            ->addColumn('jalur_buku', function ($data) {
                return $data->jalur_buku;
            })
            ->addColumn('masuk_naskah', function ($data) {
                return Carbon::parse($data->tanggal_masuk_naskah)->translatedFormat('l, d F Y');
            })
            ->addColumn('created_by', function ($data) {
                return DB::table('users')->where('id', $data->created_by)->first()->nama;
            })
            ->addColumn('stts_penilaian', function ($data) {
                $badge = '';
                if (in_array($data->jalur_buku, ['Reguler', 'MoU-Reguler'])) {
                    if (!is_null($data->tgl_pn_selesai)) {
                        $statusPenilaianDB = DB::table('penerbitan_pn_direksi')->where('naskah_id', $data->id)->pluck('keputusan_final')->first();
                        $badge .= '<span class="badge badge-primary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Diputuskan: ' . $statusPenilaianDB . '</span>';
                        if ($data->pic_prodev == auth()->user()->id) {
                            if (is_null($data->bukti_email_penulis)) {
                                $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                                $badge .= '&nbsp;|&nbsp;<a href="javascript:void(0)" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" class="text-primary mark-sent-email">Tandai data lengkap</a>';
                            } else {
                                $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                            }
                        } else {
                            if (is_null($data->bukti_email_penulis)) {
                                $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                            } else {
                                $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                            }
                        }
                    } else {
                        $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_prodev) ? 'danger' : 'success') . ' mr-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Prodev' . (is_null($data->tgl_pn_prodev) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_prodev)->translatedFormat('d M Y, H:i:s')) . '</span>';
                        $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_m_penerbitan) ? 'danger' : 'success') . ' mr-1 mt-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">M.Penerbitan' . (is_null($data->tgl_pn_m_penerbitan) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_m_penerbitan)->translatedFormat('d M Y, H:i:s')) . '</span>';
                        $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_m_pemasaran) ? 'danger' : 'success') . ' mr-1 mt-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">M.Pemasaran' . (is_null($data->tgl_pn_m_pemasaran) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_m_pemasaran)->translatedFormat('d M Y, H:i:s')) . '</span>';
                        $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_d_pemasaran) ? 'danger' : 'success') . ' mr-1 mt-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">D.Pemasaran' . (is_null($data->tgl_pn_d_pemasaran) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_d_pemasaran)->translatedFormat('d M Y, H:i:s')) . '</span>';
                        $badge .= '<span class="d-block badge badge-' . (is_null($data->tgl_pn_direksi) ? 'danger' : 'success') . ' mr-1 mt-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Direksi' . (is_null($data->tgl_pn_direksi) ? '' : '&nbsp;' . Carbon::parse($data->tgl_pn_direksi)->translatedFormat('d M Y, H:i:s')) . '</span>';
                    }
                } elseif ($data->jalur_buku == 'Pro Literasi') {
                    if (!is_null($data->tgl_pn_selesai)) {
                        $badge .= '<span class="badge badge-primary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Selesai Dinilai</span>';
                        if ($data->pic_prodev == auth()->user()->id) {
                            if (is_null($data->bukti_email_penulis)) {
                                $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                                $badge .= '&nbsp;|&nbsp;<a href="javascript:void(0)" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" class="text-primary mark-sent-email">Tandai data lengkap</a>';
                            } else {
                                $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                            }
                        } else {
                            if (is_null($data->bukti_email_penulis)) {
                                $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                            } else {
                                $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                            }
                        }
                    } else {
                        $badge .= '<span class="badge badge-' . (is_null($data->tgl_pn_editor) ? 'danger' : 'success') . '" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Editor</span>';
                        $badge .= '<span class="badge badge-' . (is_null($data->tgl_pn_setter) ? 'danger' : 'success') . '" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Setter</span>';
                    }
                } else {
                    $badge .= '<span class="badge badge-primary" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Tidak Dinilai</span>';
                    if ($data->pic_prodev == auth()->user()->id) {
                        if (is_null($data->bukti_email_penulis)) {
                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                            $badge .= '&nbsp;|&nbsp;<a href="javascript:void(0)" data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '" class="text-primary mark-sent-email">Tandai sudah lengkap</a>';
                        } else {
                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                        }
                    } else {
                        if (is_null($data->bukti_email_penulis)) {
                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-warning" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data belum lengkap</span>';
                        } else {
                            $badge .= '&nbsp;|&nbsp;<span class="badge badge-success" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Data sudah lengkap</span>';
                        }
                    }
                }

                return $badge;
            })
            ->addColumn('action', function ($data) use ($update) {
                $btn = '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $data->id) . '"
                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" data-placement="top" title="Lihat Data">
                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                if ($update) {
                    if ((auth()->id() == $data->pic_prodev) || (auth()->id() == $data->created_by) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') || (Gate::allows('do_approval', 'approval-deskripsi-produk'))) {
                        $btn .= '<a href="' . url('penerbitan/naskah/mengubah-naskah/' . $data->id) . '"
                            class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                            <div><i class="fas fa-edit"></i></div></a>';
                    }
                }
                if ((Gate::allows('do_delete', 'delete-data-naskah'))) {
                    $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-delete-naskah btn-danger btn-icon mr-1 mt-1"
                    data-toggle="tooltip" title="Hapus Data"
                    data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '">
                    <i class="fas fa-trash-alt"></i></a>';
                }
                $historyData = DB::table('penerbitan_naskah_history')->where('naskah_id', $data->id)->get();
                if (!$historyData->isEmpty()) {
                    $btn .= '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 mt-1 btn-history" data-id="' . $data->id . '" data-judulasli="' . $data->judul_asli . '" data-toggle="tooltip" title="History">
                    <i class="fas fa-history"></i>&nbsp;History</button>';
                }
                $trackerData = DB::table('tracker')->where('section_id', $data->id)->get();
                if (!$trackerData->isEmpty()) {
                    $btn .= '<button type="button" class="btn btn-sm btn-info btn-icon mr-1 mt-1 btn-tracker" data-id="' . $data->id . '" data-judulasli="' . $data->judul_asli . '" data-toggle="tooltip" title="Tracker">
                    <i class="fas fa-file-signature"></i>&nbsp;Tracking</button>';
                }
                return $btn;
            })
            ->rawColumns(['kode', 'judul_asli', 'pic_prodev', 'jalur_buku', 'masuk_naskah', 'created_by', 'stts_penilaian', 'action'])
            ->make(true);
    }
    public function restorePage(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $id = $request->id;
                try {
                    $insert = [
                        'params' => 'Restore Naskah',
                        'id' => $id,
                        'type_history' => 'Restore',
                        'author_id' => auth()->user()->id,
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new NaskahEvent($insert));
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Berhasil mengembalikan data naskah!'
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ], 500);
                }
            } else {
                $data = DB::table('penerbitan_naskah')
                    ->whereNotNull('deleted_at')
                    ->orderBy('deleted_at', 'desc')
                    ->get();
                return Datatables::of($data)
                    ->addColumn('kode', function ($data) {
                        return $data->kode;
                    })
                    ->addColumn('judul_asli', function ($data) {
                        return $data->judul_asli;
                    })
                    ->addColumn('pic_prodev', function ($data) {
                        return DB::table('users')->where('id', $data->pic_prodev)->first()->nama;
                    })
                    ->addColumn('tanggal_masuk_naskah', function ($data) {
                        return Carbon::parse($data->tanggal_masuk_naskah)->translatedFormat('l, d F Y');
                    })
                    ->addColumn('deleted_at', function ($data) {
                        return Carbon::parse($data->deleted_at)->translatedFormat('l, d F Y H:i');
                    })
                    ->addColumn('deleted_by', function ($data) {
                        return DB::table('users')->where('id', $data->deleted_by)->first()->nama;
                    })
                    ->addColumn('action', function ($data) {
                        $btn = '<a href="javascript:void(0)"
                            class="d-block btn btn-sm btn-restore-naskah btn-dark btn-icon""
                            data-toggle="tooltip" title="Restore Data"
                            data-id="' . $data->id . '" data-kode="' . $data->kode . '" data-judul="' . $data->judul_asli . '">
                            <div><i class="fas fa-trash-restore-alt"></i></div></a>';
                        return $btn;
                    })
                    ->rawColumns([
                        'kode',
                        'judul_asli',
                        'pic_prodev',
                        'jalur_buku',
                        'tanggal_masuk_naskah',
                        'deleted_at',
                        'deleted_by',
                        'action'
                    ])
                    ->make(true);
            }
        }

        return view('penerbitan.naskah.restore-naskah', [
            'title' => 'Naskah Telah Dihapus',
        ]);
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
    protected function alurPenilaian($jalbuk, $action = null, $data = null, $judul)
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
                DB::table('todo_list')->insert([
                    'form_id' => $data['form_id'],
                    'users_id' => $data['id_prodev'],
                    'title' => 'Penilaian naskah berjudul "' . $judul . '".',
                    'link' => '/penerbitan/naskah/melihat-naskah/' . $data['form_id'],
                    'status' => '0',
                ]);
                $data = collect($data)->put('judul', $judul);
                $penerbitan = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                    ->where('p.id', '12b852d92d284ab5a654c26e8856fffd')
                    ->select('up.user_id')
                    ->get();

                $penerbitan = (object)collect($penerbitan)->map(function ($item) use ($data) {
                    return DB::table('todo_list')->insert([
                        'form_id' => $data['form_id'],
                        'users_id' => $item->user_id,
                        'title' => 'Penilaian naskah berjudul "' . $data['judul'] . '".',
                        'link' => '/penerbitan/naskah/melihat-naskah/' . $data['form_id'],
                        'status' => '0',
                    ]);
                })->all();
                $mpemasaran = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                    ->where('p.id', 'a213b689b8274f4dbe19b3fb24d66840')
                    ->select('up.user_id')
                    ->get();

                $mpemasaran = (object)collect($mpemasaran)->map(function ($item) use ($data) {
                    return DB::table('todo_list')->insert([
                        'form_id' => $data['form_id'],
                        'users_id' => $item->user_id,
                        'title' => 'Penilaian naskah berjudul "' . $data['judul'] . '".',
                        'link' => '/penerbitan/naskah/melihat-naskah/' . $data['form_id'],
                        'status' => '0',
                    ]);
                })->all();
                $dpemasaran = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                    ->where('p.id', '9beba245308543ce821efe8a3ba965e3')
                    ->select('up.user_id')
                    ->get();

                $dpemasaran = (object)collect($dpemasaran)->map(function ($item) use ($data) {
                    return DB::table('todo_list')->insert([
                        'form_id' => $data['form_id'],
                        'users_id' => $item->user_id,
                        'title' => 'Penilaian naskah berjudul "' . $data['judul'] . '".',
                        'link' => '/penerbitan/naskah/melihat-naskah/' . $data['form_id'],
                        'status' => '0',
                    ]);
                })->all();
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
                DB::table('todo_list')->insert([
                    'form_id' => $data['form_id'],
                    'users_id' => $data['id_prodev'],
                    'title' => 'Penilaian naskah berjudul "' . $judul . '".',
                    'link' => '/penerbitan/naskah/melihat-naskah/' . $data['form_id'],
                    'status' => '0',
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
                $editSet = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                    ->where('p.id', '5d793b19c75046b9a4d75d067e8e33b2')
                    ->where('p.id', '33c3711d787d416082c0519356547b0c')
                    ->select('up.user_id')
                    ->get();

                $editSet = (object)collect($editSet)->map(function ($item) use ($data) {
                    return DB::table('todo_list')->insert([
                        'form_id' => $data['form_id'],
                        'users_id' => $item->user_id,
                        'title' => 'Penilaian naskah berjudul "' . $data['judul'] . '".',
                        'link' => '/penerbitan/naskah/melihat-naskah/' . $data['form_id'],
                        'status' => '0',
                    ]);
                })->all();
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
}
