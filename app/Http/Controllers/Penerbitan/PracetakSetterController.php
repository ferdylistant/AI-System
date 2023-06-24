<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use App\Events\TrackerEvent;
use Illuminate\Http\Request;
use App\Events\TimelineEvent;
use App\Events\DesturcetEvent;
use Yajra\DataTables\DataTables;
use App\Events\PracetakSetterEvent;
use Illuminate\Support\Facades\URL;
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
                    'dp.nama_pena',
                    'df.bullet'
                )
                ->orderBy('ps.tgl_masuk_pracetak', 'ASC');
            $reguler = Gate::allows('do_create', 'otorisasi-setter-praset-reguler');
            $mou = Gate::allows('do_create', 'otorisasi-setter-praset-mou');
            $smk = Gate::allows('do_create', 'otorisasi-setter-praset-smk');
            if (in_array(auth()->id(), $data->pluck('pic_prodev')->toArray())) {
                $data->where('pn.pic_prodev', auth()->id());
            }
            if ($reguler || $mou || $smk) {
                $data->where('ps.setter', 'like', '%"' . auth()->id() . '"%');
            }
            if (Gate::allows('do_create', 'otorisasi-korektor-praset-reguler') || Gate::allows('do_create', 'otorisasi-korektor-praset-mou') || Gate::allows('do_create', 'otorisasi-korektor-praset-smk')) {
                $data->where('ps.korektor', 'like', '%"' . auth()->id() . '"%');
            }
            $data->get();
            if ($request->has('count_data')) {
                return $data->count();
            } elseif ($request->has('show_status')) {
                $showStatus = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->where('ps.id',$request->id)
                ->select('ps.*','dp.judul_final')
                ->first();
                return response()->json($showStatus);
            } else {
                return DataTables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('kode', function ($data) {
                        $tandaProses = '';
                        $dataKode = $data->kode;
                        if ($data->status == 'Proses') {
                            if ($data->proses_saat_ini == 'Siap Turcet') {
                                $dataKode = '<span class="text-primary" data-toggle="tooltip" data-placement="top" title="Proses ' . $data->proses_saat_ini . ', menunggu kabag melengkapi data-data yang dibutuhkan untuk Turun Cetak">' . $data->kode . '</span>';
                                $tandaProses = '<span class="beep-primary d-table"></span>';
                            } else {
                                $tooltip = $data->proses == '1' ? 'data-toggle="tooltip" data-placement="top" title="Sedang dikerjakan oleh ' . $data->proses_saat_ini . '"' : 'data-toggle="tooltip" data-placement="top" title="Proses ' . $data->proses_saat_ini . ' belum dimulai oleh kabag"';
                                $dataKode = $data->proses == '1'  ? '<span class="text-success" ' . $tooltip . '>' . $data->kode . '</span>' : '<span class="text-danger" ' . $tooltip . '>' . $data->kode . '</span>';
                                $tandaProses = $data->proses == '1' ? '<span class="beep-success d-table"></span>' : '<span class="beep-danger d-table"></span>';
                            }
                        }
                        return $dataKode . $tandaProses;
                    })
                    ->addColumn('judul_final', function ($data) {
                        if (is_null($data->judul_final)) {
                            $res = '-';
                        } else {
                            $res = $data->judul_final;
                        }
                        return $res;
                    })
                    ->addColumn('setter', function ($data) {
                        $result = '';
                        if (is_null($data->setter) || $data->setter == '[]' || $data->setter == '[null]') {
                            $result .= "<span class='text-danger'>Belum ditambahkan</span>";
                        } else {
                            $sett = collect(json_decode($data->setter))->map(function ($item) {
                                $name = DB::table('users')->where('id', $item)->first()->nama;
                                return $name;
                            })->all();
                            foreach ($sett as $q) {
                                $result .= '<span class="bullet"></span>' . $q . '<br>';
                            }
                        }
                        return $result;
                    })
                    ->addColumn('korektor', function ($data) {
                        $result = '';
                        if (is_null($data->korektor) || $data->korektor == '[]' || $data->korektor == '[null]') {
                            $result .= "<span class='text-danger'>Belum ditambahkan</span>";
                        } else {
                            $kor = collect(json_decode($data->korektor))->map(function ($item) {
                                $name = DB::table('users')->where('id', $item)->first()->nama;
                                return $name;
                            })->all();
                            foreach ($kor as $q) {
                                $result .= '<span class="bullet"></span>' . $q . '<br>';
                            }
                        }
                        return $result;
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
                    ->addColumn('proses_saat_ini', function ($data) {
                        if (!is_null($data->proses_saat_ini)) {
                            $res = '<span class="badge badge-light">' . $data->proses_saat_ini . '</span>';
                        } else {
                            $res = '<span class="text-danger"><i class="fas fa-exclamation-circle"></i>&nbsp;Belum ada proses</span>';
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
                    ->addColumn('tracker', function ($data) {
                        $trackerData = DB::table('tracker')->where('section_id', $data->id)->get();
                        if ($trackerData->isEmpty()) {
                            return '-';
                        } else {
                            $date = '<button type="button" class="btn btn-sm btn-info btn-icon mr-1 btn-tracker" data-id="' . $data->id . '" data-judulfinal="' . $data->judul_final . '"><i class="fas fa-file-signature"></i>&nbsp;Lihat Tracking</button>';
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
                        'setter',
                        'korektor',
                        'penulis',
                        'jalur_buku',
                        'tgl_masuk_pracetak',
                        'pic_prodev',
                        'proses_saat_ini',
                        'history',
                        'tracker',
                        'action'
                    ])
                    ->make(true);
            }
        }

        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_setter WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        $statusAction = Arr::except($statusProgress, ['4']);
        $statusProgress = Arr::sort($statusProgress);
        $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_setter WHERE Field = 'proses_saat_ini'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProsesSaatIni = explode("','", $matches[1]);
        $statusProsesSaatIni = Arr::sort($statusProsesSaatIni);
        $statusProsesSaatIni = Arr::prepend($statusProsesSaatIni, 'Belum ada proses');
        return view('penerbitan.pracetak_setter.index', [
            'title' => 'Pracetak Setter',
            'status_action' => $statusAction,
            'status_progress' => $statusProgress,
            'status_proses_saat_ini' => $statusProsesSaatIni,
        ]);
    }
    public function editSetter(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('GET')) {
                $id = $request->get('setter');
                $kodenaskah = $request->get('kode');
                $data = DB::table('pracetak_setter as ps')
                    ->join('deskripsi_final as df', 'ps.deskripsi_final_id', '=', 'df.id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->join('deskripsi_cover as dc', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->join('pracetak_cover as pc', 'dc.id', '=', 'pc.deskripsi_cover_id')
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
                        'df.kertas_isi',
                        'df.isi_warna',
                        'df.isi_huruf',
                        'df.ukuran_asli',
                        'dp.naskah_id',
                        'dp.judul_final',
                        'dp.nama_pena',
                        'dp.imprint',
                        'dp.format_buku',
                        'dp.jml_hal_perkiraan',
                        'pn.kode',
                        'pn.jalur_buku',
                        'pn.pic_prodev',
                        'kb.nama',
                        'pc.id as id_pracov'
                    )
                    ->first();
                if (is_null($data)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
                $useData = $data;

                $data = collect($data)->put('penulis', DB::table('penerbitan_naskah_penulis as pnp')
                    ->join('penerbitan_penulis as pp', function ($q) {
                        $q->on('pnp.penulis_id', '=', 'pp.id')
                            ->whereNull('pp.deleted_at');
                    })
                    ->where('pnp.naskah_id', '=', $data->naskah_id)
                    ->select('pp.nama')
                    ->get());
                // return response()->json($useData);
                $data = (object)collect($data)->map(function ($item, $key) use ($useData) {
                    switch ($key) {
                        case 'status':
                            $html = '';
                            switch ($item) {
                                case 'Antrian':
                                    $html .= '<i class="far fa-circle" style="color:#34395E;"></i>
                                    Status Progress:
                                    <span class="badge" style="background:#34395E;color:white" id="badgeStatProg">' . $item . '</span>';
                                    break;

                                case 'Pending':
                                    $html .= '<i class="far fa-circle text-danger"></i>
                                    Status Progress:
                                    <span class="badge badge-danger">' . $item . '</span>';
                                    break;

                                case 'Proses':
                                    $html .= '<i class="far fa-circle text-success"></i>
                                    Status Progress:
                                    <span class="badge badge-success">' . $item . '</span>';
                                    break;

                                case 'Selesai':
                                    $html .= '<i class="far fa-circle text-dark"></i>
                                    Status Progress:
                                    <span class="badge badge-light">' . $item . '</span>';
                                    break;

                                case 'Revisi':
                                    $html .= '<i class="far fa-circle text-info"></i>
                                    Status Progress:
                                    <span class="badge badge-info">' . $item . '</span>';
                                    break;
                            }
                            return $html;
                            break;
                        case 'proses_saat_ini':
                            $htmlHeader = '';
                            $htmlHeader .= '<i class="fas fa-exclamation-circle"></i>&nbsp;Proses Saat Ini:';
                            switch ($item) {
                                case 'Antrian Koreksi':
                                    $htmlHeader .= '<span class="text-dark"> Antri Koreksi</span>';
                                    break;

                                case 'Antrian Setting':
                                    $htmlHeader .= '<span class="text-dark"> Antrian Setting</span>';
                                    break;

                                case 'Setting':
                                    $htmlHeader .= '<span class="text-dark" class="text-dark"> Setting</span>';
                                    break;

                                case 'Antrian Proof':
                                    $htmlHeader .= '<span class="text-dark"> Antrian Proof</span>';
                                    break;

                                case 'Proof Prodev':
                                    $htmlHeader .= '<span class="text-dark"> Proof Prodev</span>';
                                    break;

                                case 'Koreksi':
                                    $htmlHeader .= '<span class="text-dark"> Koreksi</span>';
                                    break;

                                case 'Siap Turcet':
                                    $htmlHeader .= '<span class="text-dark"> Siap Turcet</span>';
                                    break;

                                case 'Turun Cetak':
                                    $htmlHeader .= '<span class="text-dark"> Turun Cetak</span>';
                                    break;

                                case 'Setting Revisi':
                                    $htmlHeader .= '<span class="text-dark"> Setting Revisi</span>';
                                    break;

                                default:
                                    $htmlHeader .= '<span class="text-danger"> Belum ada proses</span>';
                                    break;
                            }
                            if ($useData->status == 'Revisi') {
                                $htmlHeader .= '<br>
                                <button type="button" class="btn btn-warning" id="done-revision"
                                    data-id="' . $useData->id . '" data-judul="' . $useData->judul_final . '"
                                    data-kode="' . $useData->kode . '">
                                    <i class="fas fa-check"></i>&nbsp;Selesai Revisi
                                </button>';
                            }
                            //!FORM
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_setter WHERE Field = 'proses_saat_ini'"))[0]->Type;
                            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
                            $prosesSaatIni = explode("','", $matches[1]);
                            $prosesFilter = Arr::except($prosesSaatIni, ['1', '3', '5', '8']);
                            if ($item == 'Siap Turcet') {
                                $prosesFilter = Arr::where($prosesFilter, function ($value, $key) {
                                    return $key >= 6;
                                });
                            } elseif (!is_null($useData->selesai_proof)) {
                                $prosesFilter = Arr::except($prosesSaatIni, ['1', '2', '3', '5', '8']);
                                $prosesFilter = Arr::where($prosesFilter, function ($value, $key) {
                                    return $key >= 0;
                                });
                            } elseif (!is_null($useData->selesai_setting)) {
                                $prosesFilter = Arr::where($prosesFilter, function ($value, $key) {
                                    return $key >= 2;
                                });
                            } elseif ($item == 'Setting Revisi') {
                                $prosesFilter = Arr::where($prosesSaatIni, function ($value, $key) {
                                    return $key >= 6;
                                });
                                $prosesFilter = Arr::sort($prosesFilter, function ($value) {
                                    return $value;
                                });
                            }
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if (is_null($item)) {
                                    $textAlign = 'text-left';
                                    $html .= '<div class="input-group">
                                    <select name="proses_saat_ini" class="form-control select-proses" required>
                                        <option label="Pilih proses saat ini"></option>';
                                    foreach ($prosesFilter as $k) {
                                        $html .= '<option value="' . $k . '">' . $k . '&nbsp;&nbsp;</option>';
                                    }
                                    $html .= '</select>
                                </div>';
                                } else {
                                    $idCol = "prosCol";
                                    $textAlign = 'text-right';
                                    $html .= $item . '
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="prosButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>';
                                    $htmlHidden .= '<div class="input-group">
                                <select name="proses_saat_ini" class="form-control select-proses" required>
                                    <option label="Pilih proses saat ini"></option>';
                                    foreach ($prosesFilter as $k) {
                                        $sel = $item == $k ? 'Selected' : '';
                                        $htmlHidden .= '<option value="' . $k . '" ' . $sel . '>' . $k . '&nbsp;&nbsp;</option>';
                                    }
                                    $htmlHidden .= '</select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger batal_edit_proses text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                </div>
                            </div>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $html .= $item;
                                }
                            }
                            return ['htmlHeader' => $htmlHeader, 'data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign];
                            break;
                        case 'penulis':
                            $html = '';
                            if (is_null($item)) {
                                $html .= '-';
                            } else {
                                foreach ($item as $value) {
                                    $html .= '<span class="bullet"></span>' . $value->nama . '<br>';
                                }
                            }
                            return $html;
                            break;
                        case 'nama_pena':
                            $html = '';
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if (is_null($item)) {
                                    $html = '-';
                                } else {
                                    foreach (json_decode($item) as $value) {
                                        $html .= '<span class="bullet"></span>' . $value . '<br>';
                                    }
                                }
                            } else {
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html = 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    foreach (json_decode($item) as $value) {
                                        $html .= '<span class="bullet"></span>' . $value . '<br>';
                                    }
                                }
                            }
                            return ['data' => $html, 'textColor' => $text];
                            break;
                        case 'imprint':
                            $res = DB::table('imprint')->whereNull('deleted_at')->first()->nama;
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                $return = is_null($item) ? '-' : $res;
                            } else {
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $return = 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $return = $res;
                                }
                            }
                            return ['data' => $return, 'textColor' => $text];
                            break;
                        case 'format_buku':
                            $res = DB::table('format_buku')->whereNull('deleted_at')->first()->jenis_format;
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                $return = is_null($item) ? '-' : $res . ' cm';
                            } else {
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $return = 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $return = $res . ' cm';
                                }
                            }
                            return ['data' => $return, 'textColor' => $text];
                            break;
                        case 'bullet':
                            $html = '';
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if ((is_null($item)) || ($item == '[]')) {
                                    $html .= '-';
                                } else {
                                    foreach (json_decode($item) as $value) {
                                        $html .= '<span class="bullet"></span>' . $value . '<br>';
                                    }
                                }
                            } else {
                                $text = 'text-danger';
                                if ((is_null($item)) || ($item == '[]')) {
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    foreach (json_decode($item) as $value) {
                                        $html .= '<span class="bullet"></span>' . $value . '<br>';
                                    }
                                }
                            }
                            return ['data' => $html, 'textColor' => $text];
                            break;
                        case 'jml_hal_final':
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if (is_null($item)) {
                                    $textAlign = 'text-left';
                                    $html .= '<input type="number" name="jml_hal_final" class="form-control" min="1" required>';
                                } else {
                                    $idCol = "jmlHalCol";
                                    $textAlign = 'text-right';
                                    $html .= $item . '
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="jmlHalButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>';
                                    $htmlHidden .= '<div class="input-group">
                                        <input type="number" name="jml_hal_final" value="' . $item . '" class="form-control" min="1" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger batal_edit_jml text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $html .= $item;
                                }
                            }
                            return ['data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign];
                            break;
                        case 'catatan':
                            $html = '';
                            $htmlHidden = '';
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if (is_null($item)) {
                                    $idCol = "catCol";
                                    $textAlign = 'text-left';
                                    $html .= '<a href="javascript:void(0)" id="catButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>';
                                    $htmlHidden .= '<div class="input-group">
                                    <textarea name="catatan" class="form-control" cols="30" rows="10"></textarea>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-danger batal_edit_cat text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>';
                                } else {
                                    $idCol = "catCol";
                                    $textAlign = 'text-right';
                                    $html .= $item . '
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="catButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>';
                                    $htmlHidden .= '<div class="input-group">
                                <textarea name="catatan" class="form-control" cols="30" rows="10">' . $item . '</textarea>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger batal_edit_cat text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                </div>
                                </div>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                $idCol = "";
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $html .= $item;
                                }
                            }
                            return ['data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign];
                            break;
                        case 'setter':
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            $setter = DB::table('users as u')
                                ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                                ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
                                ->where('j.nama', 'LIKE', '%Setter%')
                                ->where('d.nama', 'LIKE', '%Penerbitan%')
                                ->select('u.nama', 'u.id')
                                ->orderBy('u.nama', 'Asc')
                                ->get();
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if ((is_null($item)) || ($item == '[null]')) {

                                    $textAlign = 'text-left';
                                    $html .= '<select name="setter[]" class="form-control select-setter" multiple="multiple" required>
                                    <option label="Pilih setter"></option>';
                                    foreach ($setter as $i => $edList) {
                                        $html .= '<option value="' . $edList->id . '">
                                        ' . $edList->nama . '&nbsp;&nbsp;
                                    </option>';
                                    }
                                    $html .= '</select>';
                                } else {
                                    $idCol = "setterCol";
                                    $textAlign = 'text-right';
                                    if ((!is_null($item)) && ($item != '[null]')) {
                                        foreach (json_decode($item) as $e) {
                                            $namaSetter[] = DB::table('users')->where('id', $e)->first()->nama;
                                        }
                                        $korektor = DB::table('users as u')
                                            ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                                            ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
                                            ->where('j.nama', 'LIKE', '%Setter%')
                                            ->where('d.nama', 'LIKE', '%Penerbitan%')
                                            ->whereNotIn('u.id', json_decode($item))
                                            ->orWhere('j.nama', 'LIKE', '%Korektor%')
                                            ->select('u.nama', 'u.id')
                                            ->orderBy('u.nama', 'Asc')
                                            ->get();
                                    } else {
                                        $namaSetter = null;
                                        $korektor = null;
                                    }
                                    foreach ($namaSetter as $key => $aj) {
                                        $html .= '<span class="bullet"></span>' . $aj . '<br>';
                                    }
                                    if ($useData->proses_saat_ini != 'Siap Turcet') {
                                        $html .= '<p class="text-small">
                                            <a href="javascript:void(0)" id="setterButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                        </p>';
                                    }
                                    $htmlHidden .= '<div class="input-group">
                                <select name="setter[]" class="form-control select-setter" multiple="multiple">
                                    <option label="Pilih setter"></option>';
                                    foreach ($setter as $i => $edList) {
                                        $sel = '';
                                        if (in_array($edList->nama, $namaSetter)) {
                                            $sel = ' selected="selected" ';
                                        }
                                        $htmlHidden .= '<option value="' . $edList->id . '" ' . $sel . '>
                                        ' . $edList->nama . '&nbsp;&nbsp;
                                    </option>';
                                    }
                                    $htmlHidden .= '</select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger batal_edit_setter text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                </div>
                            </div>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                if ((is_null($item)) || ($item == '[null]')) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    foreach (json_decode($item) as $e) {
                                        $namaSetter[] = DB::table('users')->where('id', $e)->first()->nama;
                                    }
                                    foreach ($namaSetter as $key => $aj) {
                                        $html .= '<span class="bullet"></span>' . $aj . '<br>';
                                    }
                                }
                            }
                            return ['data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign];
                            break;
                        case 'korektor':
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            $setter = DB::table('users as u')
                                ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                                ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
                                ->where('j.nama', 'LIKE', '%Setter%')
                                ->where('d.nama', 'LIKE', '%Penerbitan%')
                                ->select('u.nama', 'u.id')
                                ->orderBy('u.nama', 'Asc')
                                ->get();
                            if ((!is_null($useData->setter)) && ($useData->setter != '[null]')) {
                                $korektor = DB::table('users as u')
                                    ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
                                    ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
                                    ->where('j.nama', 'LIKE', '%Setter%')
                                    ->where('d.nama', 'LIKE', '%Penerbitan%')
                                    ->whereNotIn('u.id', json_decode($useData->setter))
                                    ->orWhere('j.nama', 'LIKE', '%Korektor%')
                                    ->select('u.nama', 'u.id')
                                    ->orderBy('u.nama', 'Asc')
                                    ->get();
                            } else {
                                $korektor = null;
                            }
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if ((!is_null($item)) && ($item != '[null]')) {
                                    $idCol = "korektorCol";
                                    $textAlign = 'text-right';
                                    if ((!is_null($item)) && ($item != '[null]')) {
                                        foreach (json_decode($item) as $ce) {
                                            $namakorektor[] = DB::table('users')->where('id', $ce)->first()->nama;
                                        }
                                    } else {
                                        $namakorektor = null;
                                    }
                                    foreach ($namakorektor as $key => $aj) {
                                        $html .= '<span class="bullet"></span>' . $aj . '<br>';
                                    }
                                    if ($useData->proses_saat_ini != 'Siap Turcet') {
                                        $html .= '<p class="text-small">
                                        <a href="javascript:void(0)" id="korektorButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                        </p>';
                                    }
                                    $htmlHidden .= '<div class="input-group">
                                    <select name="korektor[]" class="form-control select-korektor" multiple="multiple">
                                    <option label="Pilih korektor"></option>';
                                    foreach ($korektor as $i => $edList) {
                                        $sel = '';
                                        if (in_array($edList->nama, $namakorektor)) {
                                            $sel = ' selected="selected" ';
                                        }
                                        $htmlHidden .= '<option value="' . $edList->id . '" ' . $sel . '>
                                        ' . $edList->nama . '&nbsp;&nbsp;
                                    </option>';
                                    }
                                    $htmlHidden .= '</select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger batal_edit_korektor text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                </div>
                                </div>';
                                } else {
                                    $textAlign = 'text-left';
                                    $dis = '';
                                    if (is_null($useData->selesai_proof)) {
                                        $html .= '<span class="text-danger"><i class="fas fa-exclamation-circle"></i>
                                            Belum bisa melanjutkan proses koreksi,
                                            proses ' . $useData->proses_saat_ini . ' belum selesai.</span>';
                                        $dis = 'disabled';
                                    }
                                    $html .= '<select name="korektor[]" class="form-control select-korektor" multiple="multiple" ' . $dis . ' required>
                                        <option label="Pilih korektor"></option>';
                                    if (!is_null($korektor)) {
                                        foreach ($korektor as $cpeList) {
                                            $html .= '<option value="' . $cpeList->id . '">
                                                ' . $cpeList->nama . '&nbsp;&nbsp;
                                                </option>';
                                        }
                                    }
                                    $html .= '</select>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                if ((is_null($item)) || ($item == '[null]')) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    foreach (json_decode($item) as $e) {
                                        $namakorektor[] = DB::table('users')->where('id', $e)->first()->nama;
                                    }
                                    foreach ($namakorektor as $key => $aj) {
                                        $html .= '<span class="bullet"></span>' . $aj . '<br>';
                                    }
                                }
                            }
                            $required = '';
                            $requiredColor = '';
                            if (!is_null($useData->selesai_proof)) {
                                $required = '*';
                                $requiredColor = 'text-danger';
                            }
                            return [
                                'data' => $html,
                                'textColor' => $text,
                                'htmlHidden' => $htmlHidden,
                                'idCol' => $idCol,
                                'textAlign' => $textAlign,
                                'required' => $required,
                                'requiredColor' => $requiredColor
                            ];
                            break;
                        case 'edisi_cetak':
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if (is_null($item)) {
                                    $textAlign = 'text-left';
                                    $html .= '<input type="text" name="edisi_cetak" class="form-control">';
                                } else {
                                    $idCol = "edCetakCol";
                                    $textAlign = 'text-right';
                                    $html .= $item . '
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="edCetakButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>';
                                    $htmlHidden .= '<div class="input-group">
                                    <input type="text" name="edisi_cetak" value="' . $item . '" class="form-control">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-danger batal_edit_edisicetak text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $html .= $item;
                                }
                            }
                            return ['data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign];
                            break;
                        case 'mulai_p_copyright':
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if (is_null($item)) {
                                    $textAlign = 'text-left';
                                    $html .= '<input name="mulai_p_copyright" class="form-control datepicker-copyright" placeholder="Tanggal mulai proses copyright" readonly>';
                                } else {
                                    $idCol = "copyrightCol";
                                    $textAlign = 'text-right';
                                    $html .= Carbon::parse($item)->translatedFormat('l d F Y, H:i') . '
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="copyrightButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>';
                                    $htmlHidden .= '<div class="input-group">
                                <input name="mulai_p_copyright" class="form-control datepicker-copyright" placeholder="Tanggal mulai proses copyright" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger batal_edit_copyright text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                </div>
                            </div>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $html .= Carbon::parse($item)->translatedFormat('l d F Y, H:i');
                                }
                            }
                            return ['data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign];
                            break;
                        case 'selesai_p_copyright':
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if (is_null($item)) {
                                    $textAlign = 'text-left';
                                    $html .= '<input name="selesai_p_copyright" class="form-control datepicker-copyright" placeholder="Tanggal selesai proses copyright" readonly>';
                                } else {
                                    $idCol = "copyrightSelCol";
                                    $textAlign = 'text-right';
                                    $html .= Carbon::parse($item)->translatedFormat('l d F Y, H:i') . '
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="copyrightSelButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>';
                                    $htmlHidden .= '<div class="input-group">
                                <input name="selesai_p_copyright" class="form-control datepicker-copyright" placeholder="Tanggal selesai proses copyright" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger batal_edit_selesaicopyright text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                </div>
                            </div>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $html .= Carbon::parse($item)->translatedFormat('l d F Y, H:i');
                                }
                            }
                            return ['data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign];
                            break;
                        case 'isbn':
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                $hiddenForm = false;
                                if (is_null($item)) {
                                    $textAlign = 'text-left';
                                    $html .= '<div class="input-group">
                                    <input type="text" name="isbn" class="form-control"  placeholder="000000000000" id="ISBN">
                                </div>';
                                } else {
                                    $idCol = "isbnCol";
                                    $textAlign = 'text-right';
                                    $html .= $item . '
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="isbnButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>';
                                    $htmlHidden .= '<div class="input-group">
                                <input type="text" name="isbn" class="form-control" value="' . $item . '"  placeholder="000000000000" id="ISBN">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger batal_edit_isbn text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                </div>
                            </div>';
                                }
                            } else {
                                $hiddenForm = true;
                                $textAlign = 'text-right';
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $html .= $item;
                                }
                            }
                            return ['data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign, 'hiddenForm' => $hiddenForm];
                            break;
                        case 'pengajuan_harga':
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                $hiddenForm = false;
                                if (is_null($item)) {

                                    $textAlign = 'text-left';
                                    $html .= '<div class="input-group">
                                    <input type="text" name="pengajuan_harga" class="form-control" placeholder="000000000000" id="HARGA">
                                </div>';
                                } else {
                                    $idCol = "hargaCol";
                                    $textAlign = 'text-right';
                                    $html .= $item . '
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="hargaButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>';
                                    $htmlHidden .= '<div class="input-group">
                                <input type="text" name="pengajuan_harga" class="form-control" value="' . $item . '"  placeholder="000000000000" id="HARGA">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger batal_edit_pengajuan_harga text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                </div>
                            </div>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                $hiddenForm = true;
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $html .= $item;
                                }
                            }
                            return ['data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign, 'hiddenForm' => $hiddenForm];
                            break;
                        case 'bulan':
                            $html = '';
                            $htmlHidden = '';
                            $idCol = "";
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                if (is_null($item)) {
                                    $idCol = "";
                                    $textAlign = 'text-left';
                                    $html .= '<input name="bulan" class="form-control datepicker" placeholder="Bulan proses" readonly required>';
                                } else {
                                    $idCol = "bulanCol";
                                    $textAlign = 'text-right';
                                    $html .= Carbon::parse($item)->translatedFormat('F Y') . '
                                    <p class="text-small">
                                        <a href="javascript:void(0)" id="bulanButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                    </p>';
                                    $htmlHidden .= '<div class="input-group">
                                <input name="bulan" class="form-control datepicker" value="' . Carbon::createFromFormat('Y-m-d', $item, 'Asia/Jakarta')->format('F Y') . '" placeholder="Bulan proses" readonly required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger batal_edit_bulan text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                </div>
                            </div>';
                                }
                            } else {
                                $textAlign = 'text-right';
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $html .= 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $html .= Carbon::parse($item)->translatedFormat('F Y');
                                }
                            }
                            return ['data' => $html, 'textColor' => $text, 'htmlHidden' => $htmlHidden, 'idCol' => $idCol, 'textAlign' => $textAlign];
                            break;
                        case 'proses':
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $label = $item == 1 ? 'Stop' : 'Mulai';
                                $checked = $item == 1 ? true : false;
                                $disable = false;
                                $disableBtn = false;
                                $cursor = 'pointer';
                                $cursorBtn = 'pointer';
                                if (!is_null($useData->mulai_proof) && is_null($useData->selesai_proof)) {
                                    $disable = true;
                                    $disableBtn = true;
                                    $cursor = 'not-allowed';
                                    $cursorBtn = 'not-allowed';
                                    $lbl = 'Sedang proses proof prodev';
                                } elseif (is_null($useData->selesai_setting) && is_null($useData->selesai_koreksi)) {
                                    $lbl = $label . ' proses setting';
                                } elseif (!is_null($useData->selesai_setting) && is_null($useData->mulai_proof)) {
                                    $lbl = $label . ' proses proof prodev';
                                } elseif (is_null($useData->selesai_koreksi) && !is_null($useData->selesai_setting)) {
                                    $lbl = $label . ' proses koreksi';
                                } elseif ((!is_null($useData->selesai_koreksi)) && ($useData->proses_saat_ini == 'Setting Revisi') && ($useData->status == 'Proses')) {
                                    $lbl = $label . ' proses revisi setting';
                                } elseif (!is_null($useData->selesai_koreksi) && is_null($useData->selesai_setting)) {
                                    $lbl = $label . ' proses revisi setting';
                                } else {
                                    $disable = true;
                                    $disableBtn = true;
                                    $cursor = 'not-allowed';
                                    $cursorBtn = 'not-allowed';
                                    if ($useData->proses_saat_ini == 'Siap Turcet') {
                                        $disableBtn = false;
                                        $cursorBtn = 'pointer';
                                    }
                                    $lbl = '-';
                                }
                            } else {
                                $checked = $item == 1 ? true : false;
                                $disable = true;
                                $disableBtn = true;
                                $cursor = 'not-allowed';
                                $cursorBtn = 'not-allowed';
                                $lbl = '-';
                            }
                            return [
                                'id' => $useData->id,
                                'data' => $item,
                                'checked' => $checked,
                                'disabled' => $disable,
                                'disabledBtn' => $disableBtn,
                                'cursor' => $cursor,
                                'cursorBtn' => $cursorBtn,
                                'label' => $lbl
                            ];
                            break;
                        default:
                            if (
                                $useData->status == 'Proses' || $useData->status == 'Revisi' ||
                                ($useData->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk'))
                            ) {
                                $text = 'text-dark';
                                $return = is_null($item) ? '-' : $item;
                            } else {
                                if (is_null($item)) {
                                    $text = 'text-danger';
                                    $return = 'Belum diinput';
                                } else {
                                    $text = 'text-dark';
                                    $return = $item;
                                }
                            }
                            return ['data' => $return, 'textColor' => $text];
                            break;
                    }
                })->all();
                return $data;
            } else {
                try {
                    DB::beginTransaction();
                    $history = DB::table('pracetak_setter as ps')
                        ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                        ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                        ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                        ->where('ps.id', $request->id)
                        ->select('ps.*', 'dp.naskah_id', 'dp.judul_final', 'pn.kode', 'pn.pic_prodev', 'pn.jalur_buku')
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
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Hentikan proses untuk update data'
                        ]);
                    }
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    if ($request->proses_saat_ini == 'Turun Cetak') {
                        $cekTotalKoreksi = DB::table('pracetak_setter_selesai')
                        ->where('pracetak_setter_id',$history->id)
                        ->where('section','Koreksi')->get()->count();
                        $cekTotalSettingRevisi = DB::table('pracetak_setter_selesai')
                        ->where('pracetak_setter_id',$history->id)
                        ->where('section','Setting Revision')->get()->count();
                        if ($cekTotalSettingRevisi < $cekTotalKoreksi) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Proses setting belum selesai'
                            ]);
                        }
                        if (is_null($history->selesai_setting) || is_null($history->selesai_koreksi)) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Belum melakukan proses setting/koreksi!'
                            ]);
                        }
                        if ($history->status == 'Proses') {
                            $cekTidakKosong = DB::table('pracetak_setter')
                                ->where('id', $request->id)
                                ->whereNotNull('isbn')
                                ->whereNotNull('pengajuan_harga')
                                ->first();
                            if (is_null($cekTidakKosong)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'ISBN, atau Pengajuan Harga belum diinput!'
                                ]);
                            }
                            $cekSelesaiCover = DB::table('pracetak_cover')
                                ->where('id', $request->id_pracov)
                                ->where('status', 'Selesai')
                                ->first();
                            if (!is_null($cekSelesaiCover)) {
                                $id_turcet = Uuid::uuid4()->toString();
                                $turcet = [
                                    'params' => 'Insert Turun Cetak',
                                    'id' => $id_turcet,
                                    'pracetak_cover_id' => $request->id_pracov,
                                    'pracetak_setter_id' => $history->id,
                                    'tgl_masuk' => $tgl
                                ];
                                event(new DesturcetEvent($turcet));
                                $descDesturcetTracker = 'Naskah berjudul <a href="'.url('penerbitan/deskripsi/turun-cetak/detail?desc='.$id_turcet.'&kode='.$history->kode).'">'.$history->judul_final.'</a> telah memasuki tahap antrian Desktipsi Turun Cetak.';
                                $trackerDesturcet = [
                                    'id' => Uuid::uuid4()->toString(),
                                    'section_id' => $id_turcet,
                                    'section_name' => 'Deskripsi Turun Cetak',
                                    'description' => $descDesturcetTracker,
                                    'icon' => 'fas fa-folder-plus',
                                    'created_by' => auth()->id()
                                ];
                                event(new TrackerEvent($trackerDesturcet));
                                //Update Todo List Kabag
                                switch ($history->jalur_buku) {
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
                                $kabagPraset = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                                    ->where('p.id', $prasetPermission)
                                    ->select('up.user_id')
                                    ->get();
                                $kabagPraset = (object)collect($kabagPraset)->map(function ($item) use ($history) {
                                    return DB::table('todo_list')
                                        ->where('form_id', $history->id)
                                        ->where('users_id', $item->user_id)
                                        ->where('title', 'Proses delegasi tahap pracetak setter naskah "' . $history->judul_final . '".')
                                        ->update([
                                            'status' => '1'
                                        ]);
                                })->all();
                                //? Insert Todo List Deskripsi Turun Cetak
                                DB::table('todo_list')->insert([
                                    'form_id' => $id_turcet,
                                    'users_id' => $history->pic_prodev,
                                    'title' => 'Proses deskripsi turun cetak naskah berjudul "' . $history->judul_final . '" perlu dilengkapi kelengkapan data nya.',
                                    'link' => '/penerbitan/deskripsi/turun-cetak?desc=' . $id_turcet . '&kode=' . $history->kode,
                                    'status' => '0',
                                ]);
                                //INSERT TIMELINE TURUN CETAK
                                $insertTimelinePraset = [
                                    'params' => 'Insert Timeline',
                                    'id' => Uuid::uuid4()->toString(),
                                    'progress' => 'Deskripsi Turun Cetak',
                                    'naskah_id' => $history->naskah_id,
                                    'tgl_mulai' => $tgl,
                                    'url_action' => urlencode(URL::to('/penerbitan/deskripsi/turun-cetak/detail?desc=' . $id_turcet . '&kode=' . $history->kode)),
                                    'status' => 'Antrian'
                                ];
                                event(new TimelineEvent($insertTimelinePraset));
                            }
                            DB::table('pracetak_setter')->where('id', $request->id)->update([
                                'turun_cetak' => $tgl,
                                'status' => 'Selesai'
                            ]);
                        }
                    }
                    $mulaiCopyright = $request->mulai_p_copyright == '' ? NULL : Carbon::createFromFormat('d F Y', $request->mulai_p_copyright)->format('Y-m-d H:i:s');
                    $selesaiCopyright = $request->selesai_p_copyright == '' ? NULL : Carbon::createFromFormat('d F Y', $request->selesai_p_copyright)->format('Y-m-d H:i:s');
                    $setter = $request->has('setter') ? json_encode($request->setter) : null;
                    $update = [
                        'params' => 'Edit Pracetak Setter',
                        'id' => $request->id,
                        'jml_hal_final' => $request->jml_hal_final,
                        'catatan' => $request->catatan,
                        'setter' => $setter,
                        'korektor' => $korektor,
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
                        'setter_his' => $history->setter == $setter ? null : $history->setter,
                        'setter_new' => $history->setter == $setter ? null : $setter,
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
                        'proses_ini_his' => $history->proses_saat_ini == $request->proses_saat_ini ? null : $history->proses_saat_ini,
                        'proses_ini_new' => $history->proses_saat_ini == $request->proses_saat_ini ? null : $request->proses_saat_ini,
                        'author_id' => auth()->id(),
                        'modified_at' => $tgl
                    ];
                    event(new PracetakSetterEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data setting proses berhasil ditambahkan',
                        'route' => route('setter.view')
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
        //     $setter = DB::table('users as u')
        //     ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
        //     ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
        //     ->where('j.nama', 'LIKE', '%Setter%')
        //     ->where('d.nama', 'LIKE', '%Penerbitan%')
        //     ->select('u.nama', 'u.id')
        //     ->orderBy('u.nama', 'Asc')
        //     ->get();
        // if ((!is_null($data->setter)) && ($data->setter != '[null]')) {
        //     foreach (json_decode($data->setter) as $e) {
        //         $namaSetter[] = DB::table('users')->where('id', $e)->first()->nama;
        //     }
        //     $korektor = DB::table('users as u')
        //         ->join('jabatan as j', 'u.jabatan_id', '=', 'j.id')
        //         ->join('divisi as d', 'u.divisi_id', '=', 'd.id')
        //         ->where('j.nama', 'LIKE', '%Setter%')
        //         ->where('d.nama', 'LIKE', '%Penerbitan%')
        //         ->whereNotIn('u.id', json_decode($data->setter))
        //         ->orWhere('j.nama', 'LIKE', '%Korektor%')
        //         ->select('u.nama', 'u.id')
        //         ->orderBy('u.nama', 'Asc')
        //         ->get();
        // } else {
        //     $namaSetter = null;
        //     $korektor = null;
        // }
        // if ((!is_null($data->korektor)) && ($data->korektor != '[null]')) {
        //     foreach (json_decode($data->korektor) as $ce) {
        //         $namakorektor[] = DB::table('users')->where('id', $ce)->first()->nama;
        //     }
        // } else {
        //     $namakorektor = null;
        // }
        // $nama_imprint = '-';
        // if (!is_null($data->imprint)) {
        //     $nama_imprint = DB::table('imprint')->where('id', $data->imprint)->whereNull('deleted_at')->first()->nama;
        // }
        // $format_buku = NULL;
        // if (!is_null($data->format_buku)) {
        //     $format_buku = DB::table('format_buku')->where('id', $data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        // }
        //Status
        // $type = DB::select(DB::raw("SHOW COLUMNS FROM pracetak_setter WHERE Field = 'proses_saat_ini'"))[0]->Type;
        // preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        // $prosesSaatIni = explode("','", $matches[1]);
        // $prosesFilter = Arr::except($prosesSaatIni, ['1', '4', '7']);
        return view('penerbitan.pracetak_setter.edit', [
            'title' => 'Pracetak Setter Proses',
            // 'setter' => $setter,
            // 'nama_setter' => $namaSetter,
            // 'korektor' => $korektor,
            // 'nama_korektor' => $namakorektor,
            // 'proses_saat_ini' => $prosesFilter,
            // 'nama_imprint' => $nama_imprint,
            // 'format_buku' => $format_buku,
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
                'df.kertas_isi',
                'df.isi_warna',
                'df.isi_huruf',
                'df.ukuran_asli',
                'dp.naskah_id',
                'dp.judul_final',
                'dp.imprint',
                'dp.format_buku',
                'dp.nama_pena',
                'dp.jml_hal_perkiraan',
                'pn.kode',
                'pn.jalur_buku',
                'pn.pic_prodev',
                'kb.nama',
            )
            ->first();
        if (is_null($data)) {
            return abort(404);
        }
        $proofRevisi = DB::table('pracetak_setter_proof')->where('pracetak_setter_id', $id)->where('type_action', 'Revisi')->get();
        $penulis = DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $data->naskah_id)
            ->select('pp.id', 'pp.nama')
            ->get();
        $pic = DB::table('users')->where('id', $data->pic_prodev)->whereNull('deleted_at')->first();
        if ((!is_null($data->setter)) && ($data->setter != '[null]')) {
            foreach (json_decode($data->setter, true) as $set) {
                $namaSetter[] = DB::table('users')->where('id', $set)->first();
            }
            // dd($data->setter);
        } else {
            $namaSetter = null;
        }
        if ((!is_null($data->korektor)) && ($data->korektor != '[null]')) {
            foreach (json_decode($data->korektor, true) as $kor) {
                $namaKorektor[] = DB::table('users')->where('id', $kor)->first();
            }
        } else {
            $namaKorektor = null;
        }
        $label = is_null($data->selesai_setting) ? "setter" : "korektor";
        $dataRole  = is_null($data->selesai_setting) ? $data->setter : $data->korektor;
        $doneProses = DB::table('pracetak_setter_selesai')
            ->where('type', ucfirst($label))
            ->where('pracetak_setter_id', $data->id)
            ->where('users_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->first();
        switch ($label) {
            case 'setter':
                if (is_null($doneProses)) {
                    $result = FALSE;
                } else {
                    if (!is_null($data->selesai_koreksi) && is_null($data->selesai_setting)) {
                        switch ($doneProses->section) {
                            case 'Proof Setting':
                                $result = $data->proses_saat_ini == 'Setting Revisi' ? FALSE : TRUE;
                                break;
                            case 'Setting Revision':
                                $lastKoreksi = DB::table('pracetak_setter_selesai')
                                    ->where('type', 'Korektor')
                                    ->where('section', 'Koreksi')
                                    ->where('pracetak_setter_id', $data->id)
                                    ->orderBy('id', 'desc')
                                    ->first();
                                if ($lastKoreksi->tahap > 1) {
                                    // $tahapKoreksi = $lastKoreksi->tahap + 1;
                                    $doneSelf = DB::table('pracetak_setter_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Setting Revision')
                                        ->where('tahap', $lastKoreksi->tahap)
                                        ->where('pracetak_setter_id', $data->id)
                                        ->get();
                                    if (!$doneSelf->isEmpty()) {
                                        foreach ($doneSelf as $d) {
                                            $userId[] = $d->users_id;
                                        }
                                        if (in_array(auth()->user()->id, $userId)) {
                                            $result = TRUE;
                                        } else {
                                            $tahap = $doneProses->tahap + 1;
                                            $doneNext = DB::table('pracetak_setter_selesai')
                                                ->where('type', ucfirst($label))
                                                ->where('section', 'Setting Revision')
                                                ->where('tahap', $tahap)
                                                ->where('pracetak_setter_id', $data->id)
                                                ->where('users_id', auth()->user()->id)
                                                ->first();
                                            if (!is_null($doneNext)) {
                                                $result = TRUE;
                                            } else {
                                                $result = FALSE;
                                            }
                                        }
                                    } else {
                                        $result = FALSE;
                                    }
                                } else {
                                    $doneSelf = DB::table('pracetak_setter_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Setting Revision')
                                        ->where('tahap', $doneProses->tahap)
                                        ->where('pracetak_setter_id', $data->id)
                                        ->get();
                                    if (!$doneSelf->isEmpty()) {
                                        foreach ($doneSelf as $d) {
                                            $userId[] = $d->users_id;
                                        }
                                        if (in_array(auth()->user()->id, $userId)) {
                                            $result = TRUE;
                                        } else {
                                            $tahap = $doneProses->tahap + 1;
                                            $doneNext = DB::table('pracetak_setter_selesai')
                                                ->where('type', ucfirst($label))
                                                ->where('section', 'Setting Revision')
                                                ->where('tahap', $tahap)
                                                ->where('pracetak_setter_id', $data->id)
                                                ->where('users_id', auth()->user()->id)
                                                ->first();
                                            if (!is_null($doneNext)) {
                                                $result = TRUE;
                                            } else {
                                                $result = FALSE;
                                            }
                                        }
                                    } else {
                                        $result = FALSE;
                                    }
                                }
                                break;
                            default:
                                $result = FALSE;
                                break;
                        }
                    } else {
                        $result = TRUE;
                    }
                }
                break;
            default:
                if (is_null($doneProses)) {
                    $result = FALSE;
                } else {
                    if (is_null($data->selesai_koreksi) && !is_null($data->selesai_setting)) {
                        $lastSetting = DB::table('pracetak_setter_selesai')
                            ->where('type', 'Setter')
                            ->where('pracetak_setter_id', $data->id)
                            ->orderBy('id', 'desc')
                            ->first();
                        if ($lastSetting->tahap > 1) {
                            $doneSelf = DB::table('pracetak_setter_selesai')
                                ->where('type', ucfirst($label))
                                ->where('section', 'Koreksi')
                                ->where('tahap', $lastSetting->tahap + 1)
                                ->where('pracetak_setter_id', $data->id)
                                ->get();
                            if (!$doneSelf->isEmpty()) {
                                foreach ($doneSelf as $d) {
                                    $userId[] = $d->users_id;
                                }
                                if (in_array(auth()->user()->id, $userId)) {
                                    $result = TRUE;
                                } else {
                                    $tahap = $doneProses->tahap + 1;
                                    $doneNext = DB::table('pracetak_setter_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Koreksi')
                                        ->where('tahap', $tahap)
                                        ->where('pracetak_setter_id', $data->id)
                                        ->where('users_id', auth()->user()->id)
                                        ->first();
                                    if (!is_null($doneNext)) {
                                        $result = TRUE;
                                    } else {
                                        $result = FALSE;
                                    }
                                }
                            } else {
                                $result = FALSE;
                            }
                        } else {
                            $doneSelf = DB::table('pracetak_setter_selesai')
                                ->where('type', ucfirst($label))
                                ->where('section', 'Koreksi')
                                ->where('tahap', $doneProses->tahap)
                                ->where('pracetak_setter_id', $data->id)
                                ->get();
                            if (!$doneSelf->isEmpty()) {
                                foreach ($doneSelf as $d) {
                                    $userId[] = $d->users_id;
                                }
                                if (in_array(auth()->user()->id, $userId)) {
                                    $result = FALSE;
                                } else {
                                    $tahap = $doneProses->tahap + 1;
                                    $doneNext = DB::table('pracetak_setter_selesai')
                                        ->where('type', ucfirst($label))
                                        ->where('section', 'Koreksi')
                                        ->where('tahap', $tahap)
                                        ->where('pracetak_setter_id', $data->id)
                                        ->where('users_id', auth()->user()->id)
                                        ->first();
                                    if (!is_null($doneNext)) {
                                        $result = TRUE;
                                    } else {
                                        $result = FALSE;
                                    }
                                }
                            } else {
                                $result = FALSE;
                            }
                        }
                    } else {
                        $result = TRUE;
                    }
                }
                break;
        }
        $imprint = NULL;
        if (!is_null($data->imprint)) {
            $imprint = DB::table('imprint')->where('id', $data->imprint)->whereNull('deleted_at')->first()->nama;
        }
        $format_buku = NULL;
        if (!is_null($data->format_buku)) {
            $format_buku = DB::table('format_buku')->where('id', $data->format_buku)->whereNull('deleted_at')->first()->jenis_format;
        }
        return view('penerbitan.pracetak_setter.detail', [
            'title' => 'Detail Pracetak Setter',
            'data' => $data,
            'penulis' => $penulis,
            'pic' => $pic,
            'nama_setter' => $namaSetter,
            'nama_korektor' => $namaKorektor,
            'label' => $label,
            'dataRole' => $dataRole,
            'done_proses' => $result,
            'proof_revisi' => $proofRevisi,
            'imprint' => $imprint,
            'format_buku' => $format_buku,
        ]);
    }
    public function actionAjax(Request $request)
    {
        try {
            switch ($request->act) {
                case 'proses-kerja':
                    return $this->prosesKerjaSetter($request);
                    break;
                case 'lihat-tracking':
                    return $this->lihatTrackingSetter($request);
                    break;
                case 'update-status-progress':
                    return $this->updateStatusProgress($request);
                    break;
                case 'revision':
                    return $this->revisionActProdev($request);
                    break;
                case 'approve':
                    return $this->approveActProdev($request);
                    break;
                case 'revision-done':
                    return $this->donerevisionActKabag($request);
                    break;
                case 'lihat-history':
                    return $this->lihatHistorySetter($request);
                    break;
                case 'lihat-informasi-proof':
                    return $this->lihatInformasiProof($request);
                    break;
                case 'lihat-proses-setkor':
                    return $this->lihatProgressSetterKorektor($request);
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
    protected function prosesKerjaSetter($request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->id;
                $value = $request->proses;
                $data = DB::table('pracetak_setter as ps')
                    ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->where('ps.id', $id)
                    ->select(
                        'ps.*',
                        'dp.naskah_id',
                        'dp.judul_final',
                        'pn.kode',
                        'pn.jalur_buku',
                        'pn.pic_prodev',
                    )
                    ->first();
                if (is_null($data)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'naskah sudah tidak ada'
                    ]);
                }
                if ($value == '0') {
                    if (is_null($data->selesai_setting)) {
                        $dataProgress = [
                            'params' => 'Progress Setter-Korektor',
                            'label' => 'setting',
                            'id' => $id,
                            'mulai_setting' => NULL,
                            'proses' => $value,
                            'proses_saat_ini' => 'Antrian Setting',
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        if ((!is_null($data->selesai_koreksi)) && ($data->proses_saat_ini == 'Setting Revisi')) {
                            //? Delete Todo List Setter
                            (object)collect(json_decode($data->setter))->map(function ($item) use ($data) {
                                return DB::table('todo_list')
                                    ->where('form_id', $data->id)
                                    ->where('users_id', $item)
                                    ->where('title', 'Selesaikan proses setting revisi naskah yang berjudul "' . $data->judul_final . '".')
                                    ->delete();
                            })->all();
                        } else {
                            //? Delete Todo List Setter
                            (object)collect(json_decode($data->setter))->map(function ($item) use ($data) {
                                return DB::table('todo_list')
                                    ->where('form_id', $data->id)
                                    ->where('users_id', $item)
                                    ->where('title', 'Selesaikan proses setting pengajuan naskah yang berjudul "' . $data->judul_final . '".')
                                    ->delete();
                            })->all();
                        }
                    } else {
                        $dataProgress = [
                            'params' => 'Progress Setter-Korektor',
                            'label' => 'koreksi',
                            'id' => $id,
                            'mulai_koreksi' => NULL,
                            'proses' => $value,
                            'proses_saat_ini' => 'Antrian Koreksi',
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        //? Delete Todo List Setter
                        (object)collect(json_decode($data->korektor))->map(function ($item) use ($data) {
                            return DB::table('todo_list')
                                ->where('form_id', $data->id)
                                ->where('users_id', $item)
                                ->where('title', 'Selesaikan proses koreksi naskah yang berjudul "' . $data->judul_final . '".')
                                ->delete();
                        })->all();
                    }
                    event(new PracetakSetterEvent($dataProgress));
                    $msg = 'Proses diberhentikan!';
                } else {
                    if (is_null($data->proses_saat_ini)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Pilih proses saat ini!'
                        ]);
                    }
                    switch ($data->proses_saat_ini) {
                        case 'Antrian Setting':
                            if (!is_null($data->selesai_setting)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses setting selesai, silahkan ubah proses saat ini menjadi "Proof Prodev".'
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
                            if (is_null($data->mulai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses proof belum dilakukan, silahkan ubah proses saat ini menjadi "Proof Prodev".'
                                ]);
                            }
                            if (!is_null($data->selesai_koreksi)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses koreksi selesai, silahkan ubah proses saat ini!.'
                                ]);
                            }
                            break;

                        case 'Antrian Proof':
                            if (is_null($data->selesai_setting)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses setting belum selesai. Belum bisa melakukan proses "Proof Prodev".'
                                ]);
                            }
                            if (!is_null($data->selesai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses proof prodev selesai, silahkan ubah proses saat ini menjadi proses selanjutnya atau mengubah status proses menjadi selesai.'
                                ]);
                            }
                            if (!is_null($data->mulai_proof)) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Proses proof prodev sudah dimulai.'
                                ]);
                            }
                            break;
                    }
                    if ((!is_null($data->selesai_koreksi)) && ($data->proses_saat_ini == 'Setting Revisi')) {
                        DB::table('pracetak_setter')->where('id', $id)->update([
                            'selesai_setting' => NULL
                        ]);
                        if (!$request->has('setter')) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pilih setter terlebih dahulu!'
                            ]);
                        } else {
                            $tglSetter = Carbon::now('Asia/Jakarta')->toDateTimeString();
                            $dataSetter = [
                                'params' => 'Progress Setter-Korektor',
                                'label' => 'setting',
                                'id' => $id,
                                'mulai_setting' => $tglSetter,
                                'proses' => $value,
                                'proses_saat_ini' => 'Setting Revisi',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakSetterEvent($dataSetter));
                            $dataTodo = DB::table('todo_list')->where('form_id', $data->id)->where('title', 'Selesaikan proses setting revisi naskah yang berjudul "' . $data->judul_final . '".')->select('users_id')->get();
                            if (!$dataTodo->isEmpty()) {
                                foreach ($dataTodo as $dt) {
                                    $dtUser[] = $dt->users_id;
                                }
                                $data = collect($data)->put('users_id', $dtUser);
                                //? Insert Todo List Setter
                                (object)collect(json_decode($data['setter']))->map(function ($item) use ($data) {
                                    if (in_array($item, $data['users_id'])) {
                                        return DB::table('todo_list')
                                            ->where('form_id', $data['id'])
                                            ->where('users_id', $item)
                                            ->where('title', 'Selesaikan proses setting revisi naskah yang berjudul "' . $data['judul_final'] . '".')
                                            ->update([
                                                'status' => '0'
                                            ]);
                                    } else {
                                        return DB::table('todo_list')->insert([
                                            'form_id' => $data['id'],
                                            'users_id' => $item,
                                            'title' => 'Selesaikan proses setting revisi naskah yang berjudul "' . $data['judul_final'] . '".',
                                            'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data['id'] . '&kode=' . $data['kode'],
                                            'status' => '0'
                                        ]);
                                    }
                                })->all();
                            } else {
                                (object)collect(json_decode($data->setter))->map(function ($item) use ($data) {
                                    return DB::table('todo_list')->insert([
                                        'form_id' => $data->id,
                                        'users_id' => $item,
                                        'title' => 'Selesaikan proses setting revisi naskah yang berjudul "' . $data->judul_final . '".',
                                        'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode,
                                        'status' => '0'
                                    ]);
                                })->all();
                            }
                        }
                    } elseif (is_null($data->selesai_setting)) {
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
                                'params' => 'Progress Setter-Korektor',
                                'label' => 'setting',
                                'id' => $id,
                                'mulai_setting' => $tglSetter,
                                'proses' => $value,
                                'proses_saat_ini' => 'Setting',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakSetterEvent($dataSetter));
                            //? Insert Todo List Setter
                            (object)collect(json_decode($data->setter))->map(function ($item) use ($data) {
                                return DB::table('todo_list')->insert([
                                    'form_id' => $data->id,
                                    'users_id' => $item,
                                    'title' => 'Selesaikan proses setting pengajuan naskah yang berjudul "' . $data->judul_final . '".',
                                    'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode,
                                    'status' => '0'
                                ]);
                            })->all();
                        }
                    } elseif (is_null($data->selesai_proof)) {
                        if (is_null($data->mulai_proof)) {
                            $tglProof = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        } else {
                            $tglProof = $data->mulai_proof;
                        }
                        $dataProof = [
                            'params' => 'Progress Proof Prodev',
                            'id' => $id,
                            'mulai_proof' => $tglProof,
                            'proses' => $value,
                            'proses_saat_ini' => 'Proof Prodev',
                            'type_history' => 'Progress',
                            'author_id' => auth()->id(),
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        event(new PracetakSetterEvent($dataProof));
                        //? Insert Todo Prodev
                        DB::table('todo_list')->insert([
                            'form_id' => $data->id,
                            'users_id' => $data->pic_prodev,
                            'title' => 'Proof setting pengajuan naskah yang berjudul "' . $data->judul_final . '".',
                            'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode,
                            'status' => '0'
                        ]);
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
                                'params' => 'Progress Setter-Korektor',
                                'label' => 'koreksi',
                                'id' => $id,
                                'mulai_koreksi' => $tglKorektor,
                                'proses' => $value,
                                'proses_saat_ini' => 'Koreksi',
                                'type_history' => 'Progress',
                                'author_id' => auth()->id(),
                                'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                            ];
                            event(new PracetakSetterEvent($dataKorektor));
                            $dataTodo = DB::table('todo_list')->where('form_id', $data->id)->where('title', 'Selesaikan proses koreksi naskah yang berjudul "' . $data->judul_final . '".')->select('users_id')->get();
                            if (!$dataTodo->isEmpty()) {
                                foreach ($dataTodo as $dt) {
                                    $dtUser[] = $dt->users_id;
                                }
                                $data = collect($data)->put('users_id', $dtUser);
                                //? Insert Todo List Korektor
                                (object)collect(json_decode($data['korektor']))->map(function ($item) use ($data) {
                                    if (in_array($item, $data['users_id'])) {
                                        return DB::table('todo_list')
                                            ->where('form_id', $data['id'])
                                            ->where('users_id', $item)
                                            ->where('title', 'Selesaikan proses koreksi naskah yang berjudul "' . $data['judul_final'] . '".')
                                            ->update([
                                                'status' => '0'
                                            ]);
                                    } else {
                                        return DB::table('todo_list')->insert([
                                            'form_id' => $data['id'],
                                            'users_id' => $item,
                                            'title' => 'Selesaikan proses koreksi naskah yang berjudul "' . $data['judul_final'] . '".',
                                            'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data['id'] . '&kode=' . $data['kode'],
                                            'status' => '0'
                                        ]);
                                    }
                                })->all();
                            } else {
                                (object)collect(json_decode($data->korektor))->map(function ($item) use ($data) {
                                    return DB::table('todo_list')->insert([
                                        'form_id' => $data->id,
                                        'users_id' => $item,
                                        'title' => 'Selesaikan proses koreksi naskah yang berjudul "' . $data->judul_final . '".',
                                        'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode,
                                        'status' => '0'
                                    ]);
                                })->all();
                            }
                        }
                    }
                    $msg = 'Proses dimulai!';
                }
                return response()->json([
                    'status' => 'success',
                    'message' => $msg
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }
    protected function lihatTrackingSetter($request)
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
    protected function updateStatusProgress($request)
    {
        try {
            $id = $request->id;
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('deskripsi_cover as dc', 'dc.deskripsi_produk_id', '=', 'dp.id')
                ->join('pracetak_cover as pc', 'pc.deskripsi_cover_id', '=', 'dc.id')
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
                    'dp.imprint',
                    'dp.kelengkapan',
                    'dp.catatan',
                    'pn.kode',
                    'pn.judul_asli',
                    'pn.pic_prodev',
                    'pn.jalur_buku',
                    'kb.nama',
                    'pc.id as pracov_id'
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
                'params' => 'Update Status Pracetak Setter',
                'id' => $data->id,
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
                } else {
                    $cekTotalKoreksi = DB::table('pracetak_setter_selesai')
                    ->where('pracetak_setter_id',$data->id)
                    ->where('section','Koreksi')->get()->count();
                    $cekTotalSettingRevisi = DB::table('pracetak_setter_selesai')
                    ->where('pracetak_setter_id',$data->id)
                    ->where('section','Setting Revision')->get()->count();
                    if ($cekTotalSettingRevisi < $cekTotalKoreksi) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Proses setting belum selesai'
                        ]);
                    }
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

                // if (is_null($data->pengajuan_harga)) {
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'Proses pengajuan harga belum selesai'
                //     ]);
                // }
                // if (is_null($data->isbn)) {
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'ISBN belum ditambahkan.'
                //     ]);
                // }
                event(new PracetakSetterEvent($update));
                event(new PracetakSetterEvent($insert));
                //UPDATE TIMELINE PRACETAK SETTER
                $updateTimelinePracetakSetter = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Pracetak Setter',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelinePracetakSetter));
                //Update Todo List Kabag
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
                $kabagPraset = DB::table('permissions as p')->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                    ->where('p.id', $prasetPermission)
                    ->select('up.user_id')
                    ->get();
                $kabagPraset = (object)collect($kabagPraset)->map(function ($item) use ($data) {
                    return DB::table('todo_list')
                        ->where('form_id', $data->id)
                        ->where('users_id', $item->user_id)
                        ->where('title', 'Proses delegasi tahap pracetak setter naskah "' . $data->judul_final . '".')
                        ->update([
                            'status' => '1'
                        ]);
                })->all();
                //Cek Pracetak Cover
                $dataPracov = DB::table('pracetak_cover')
                    ->where('id', $data->pracov_id)
                    ->where('status', 'Selesai')
                    ->first();
                if (!is_null($dataPracov)) {
                    DB::table('pracetak_setter')->where('id', $data->id)->update([
                        'proses_saat_ini' => 'Turun Cetak'
                    ]);
                    $cekDesturcet = DB::table('deskripsi_turun_cetak')->where('pracetak_cover_id',$data->id)->first();
                    //Insert Deskripsi Turun Cetak
                    $id_turcet = Uuid::uuid4()->toString();
                    if (is_null($cekDesturcet)) {
                        $in = [
                            'params' => 'Insert Turun Cetak',
                            'id' => $id_turcet,
                            'pracetak_cover_id' => $data->pracov_id,
                            'pracetak_setter_id' => $data->id,
                            'tgl_masuk' => $tgl,
                        ];
                        event(new DesturcetEvent($in));
                        $descDesturcetTracker = 'Naskah berjudul <a href="'.url('penerbitan/deskripsi/turun-cetak/detail?desc='.$id_turcet.'&kode='.$data->kode).'">'.$data->judul_final.'</a> telah memasuki tahap antrian Desktipsi Turun Cetak.';
                        $trackerDesturcet = [
                            'id' => Uuid::uuid4()->toString(),
                            'section_id' => $id_turcet,
                            'section_name' => 'Deskripsi Turun Cetak',
                            'description' => $descDesturcetTracker,
                            'icon' => 'fas fa-folder-plus',
                            'created_by' => auth()->id()
                        ];
                        event(new TrackerEvent($trackerDesturcet));
                        //? Insert Todo List Deskripsi Turun Cetak
                        DB::table('todo_list')->insert([
                            'form_id' => $id_turcet,
                            'users_id' => $data->pic_prodev,
                            'title' => 'Proses deskripsi turun cetak naskah berjudul "' . $data->judul_final . '" perlu dilengkapi kelengkapan data nya.',
                            'link' => '/penerbitan/deskripsi/turun-cetak?desc=' . $id_turcet . '&kode=' . $data->kode,
                            'status' => '0',
                        ]);
                        //INSERT TIMELINE TURUN CETAK
                        $insertTimelinePraset = [
                            'params' => 'Insert Timeline',
                            'id' => Uuid::uuid4()->toString(),
                            'progress' => 'Deskripsi Turun Cetak',
                            'naskah_id' => $data->naskah_id,
                            'tgl_mulai' => $tgl,
                            'url_action' => urlencode(URL::to('/penerbitan/deskripsi/turun-cetak/detail?desc=' . $id_turcet . '&kode=' . $data->kode)),
                            'status' => 'Antrian'
                        ];
                        event(new TimelineEvent($insertTimelinePraset));
                    }
                    $namaUser = auth()->user()->nama;
                    $desc = 'Pracetak Setter selesai, <a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a> mengubah status pengerjaan Pracetak Setter menjadi <b>'.$request->status.'</b>. Proses berlanjut ke Deskripsi Turun Cetak.';
                    $icon = 'fas fa-clipboard-check';
                    $msg = 'Pracetak Setter selesai, silahkan lanjut ke proses deskripsi turun cetak..';
                } else {
                    $namaUser = auth()->user()->nama;
                    $desc = 'Pracetak Setter selesai, <a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a> mengubah status pengerjaan Pracetak Setter menjadi <b>'.$request->status.'</b>. Proses berlanjut ke pracetak cover.';
                    $icon = 'fas fa-clipboard-check';
                    $msg = 'Pracetak Setter selesai, silahkan selesaikan proses pracetak cover..';
                }
            } else {
                event(new PracetakSetterEvent($update));
                event(new PracetakSetterEvent($insert));
                $updateTimelinePracetakSetter = [
                    'params' => 'Update Timeline',
                    'naskah_id' => $data->naskah_id,
                    'progress' => 'Pracetak Setter',
                    'tgl_selesai' => $tgl,
                    'status' => $request->status
                ];
                event(new TimelineEvent($updateTimelinePracetakSetter));
                $namaUser = auth()->user()->nama;
                $desc = '<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a> mengubah status pengerjaan Pracetak Setter menjadi <b>'.$request->status.'</b>.';
                $icon = 'fas fa-info-circle';
                $msg = 'Status progress pracetak setter berhasil diupdate';
            }
            $addTracker = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $data->id,
                'section_name' => 'Pracetak Setter',
                'description' => $desc,
                'icon' => $icon,
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($addTracker));
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
    protected function lihatHistorySetter($request)
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
                        $html .= '<span class="ticket-item">
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
                        $html .= '<span class="ticket-item">';
                        if (!is_null($d->status_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Status pracetak setter <b class="text-dark">' . $d->status_his . '</b> diubah menjadi <b class="text-dark">' . $d->status_new . '</b>.';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->ket_revisi)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Status pracetak setter <b class="text-dark">' . $d->status_new . '</b> dengan keterangan revisi: <b class="text-dark">' . $d->ket_revisi . '</b>.';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->setter_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $loopSetHIS = '';
                            $loopSetNEW = '';
                            foreach (json_decode($d->setter_his, true) as $sethis) {
                                $loopSetHIS .= '<b class="text-dark">' . DB::table('users')->where('id', $sethis)->first()->nama . '</b>, ';
                            }
                            foreach (json_decode($d->setter_new, true) as $setnew) {
                                $loopSetNEW .= '<span class="bullet"></span>' . DB::table('users')->where('id', $setnew)->first()->nama;
                            }
                            $html .= ' Setter <b class="text-dark">' . $loopSetHIS . '</b> diubah menjadi <b class="text-dark">' . $loopSetNEW . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->setter_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $loopSetNEW = '';
                            foreach (json_decode($d->setter_new, true) as $setnew) {
                                $loopSetNEW .= '<b class="text-dark">' . DB::table('users')->where('id', $setnew)->first()->nama . '</b>, ';
                            }
                            $html .= ' Setter <b class="text-dark">' . $loopSetNEW . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->korektor_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $loopKorHIS = '';
                            $loopKorNEW = '';
                            foreach (json_decode($d->korektor_his, true) as $korhis) {
                                $loopKorHIS .= '<b class="text-dark">' . DB::table('users')->where('id', $korhis)->first()->nama . '</b>, ';
                            }
                            foreach (json_decode($d->korektor_new, true) as $kornew) {
                                $loopKorNEW .= '<span class="bullet"></span>' . DB::table('users')->where('id', $kornew)->first()->nama;
                            }
                            $html .= ' Korektot <b class="text-dark">' . $loopKorHIS . '</b> diubah menjadi <b class="text-dark">' . $loopKorNEW . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->korektor_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $loopKorNEW = '';
                            foreach (json_decode($d->korektor_new, true) as $kornew) {
                                $loopKorNEW .= '<b class="text-dark">' . DB::table('users')->where('id', $kornew)->first()->nama . '</b>, ';
                            }
                            $html .= ' Korektor <b class="text-dark">' . $loopKorNEW . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->jml_hal_final_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_final_his . '</b> diubah menjadi <b class="text-dark">' . $d->jml_hal_final_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->jml_hal_final_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Jumlah halaman final <b class="text-dark">' . $d->jml_hal_final_new . '</b> ditambahkan.';
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
                        if (!is_null($d->edisi_cetak_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Edisi cetak <b class="text-dark">' . $d->edisi_cetak_his . '</b> diubah menjadi <b class="text-dark">' . $d->edisi_cetak_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->edisi_cetak_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Edisi cetak <b class="text-dark">' . $d->edisi_cetak_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->proses_ini_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses saat ini <b class="text-dark">' . $d->proses_ini_his . '</b> diubah menjadi <b class="text-dark">' . $d->proses_ini_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->proses_ini_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses saat ini <b class="text-dark">' . $d->proses_ini_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->isbn_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' ISBN <b class="text-dark">' . $d->isbn_his . '</b> diubah menjadi <b class="text-dark">' . $d->isbn_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->isbn_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' ISBN <b class="text-dark">' . $d->isbn_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->pengajuan_harga_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Pengajuan harga <b class="text-dark">' . $d->pengajuan_harga_his . '</b> diubah menjadi <b class="text-dark">' . $d->pengajuan_harga_new . '</b>.<br>';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->pengajuan_harga_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Pengajuan harga <b class="text-dark">' . $d->pengajuan_harga_new . '</b> ditambahkan.<br>';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->mulai_setting)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Setting dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_setting)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->selesai_setting)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Setting selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_setting)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->mulai_proof)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proof prodev dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_proof)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->selesai_proof)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proof prodev selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_proof)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->mulai_koreksi)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Koreksi dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_koreksi)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->selesai_koreksi)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Koreksi selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_koreksi)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->mulai_p_copyright)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses copyright dimulai pada <b class="text-dark">' . Carbon::parse($d->mulai_p_copyright)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->selesai_p_copyright)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Proses copyright selesai pada <b class="text-dark">' . Carbon::parse($d->selesai_p_copyright)->translatedFormat('l d F Y, H:i') . '</b>.';
                            $html .= '</span></div>';
                        }
                        if (!is_null($d->bulan_his)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> diubah menjadi <b class="text-dark">' . Carbon::parse($d->bulan_new)->translatedFormat('F Y') . '</b>.';
                            $html .= '</span></div>';
                        } elseif (!is_null($d->bulan_new)) {
                            $html .= '<div class="ticket-title"><span><span class="bullet"></span>';
                            $html .= ' Bulan <b class="text-dark">' . Carbon::parse($d->bulan_his)->translatedFormat('F Y') . '</b> ditambahkan.';
                            $html .= '</span></div>';
                        }
                        $html .= '
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
    protected function lihatInformasiProof($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pracetak_setter_proof as psp')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'psp.pracetak_setter_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('users as u', 'psp.users_id', '=', 'u.id')
                ->where('psp.pracetak_setter_id', $id)
                ->select('psp.*', 'ps.mulai_proof', 'dp.judul_final', 'u.nama')
                ->orderBy('psp.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                switch ($d->type_action) {
                    case 'Proof':
                        $diff = Carbon::parse($d->mulai_proof)->diffInDays($d->tgl_action);
                        if ($diff == 0) {
                            $diff = Carbon::parse($d->mulai_proof)->diffInHours($d->tgl_action);
                            if ($diff == 0) {
                                $diff = Carbon::parse($d->mulai_proof)->diffInMinutes($d->tgl_action);
                                $diff = $diff . ' menit';
                            } else {
                                $diff = $diff . ' jam';
                            }
                        } else {
                            $diff = $diff . ' hari';
                        }
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Prodev menyetujui hasil setting dalam waktu ' . $diff . '.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_action, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_action)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Revisi':
                        $diff = Carbon::parse($d->mulai_proof)->diffInDays($d->tgl_action);
                        if ($diff == 0) {
                            $diff = Carbon::parse($d->mulai_proof)->diffInHours($d->tgl_action);
                            if ($diff == 0) {
                                $diff = Carbon::parse($d->mulai_proof)->diffInMinutes($d->tgl_action);
                                $diff = $diff . ' menit';
                            } else {
                                $diff = $diff . ' jam';
                            }
                        } else {
                            $diff = $diff . ' hari';
                        }
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Status pracetak setter <b>Revisi</b> dengan keterangan revisi: <b>' . $d->ket_revisi . '</b>, dalam waktu ' . $diff . '.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_action, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_action)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Selesai Revisi':
                        $dataSebelumnya = DB::table('pracetak_setter_proof')
                            ->where('pracetak_setter_id', $d->pracetak_setter_id)
                            ->where('id', '<', $d->id)
                            ->first();
                        $diff = Carbon::parse($dataSebelumnya->tgl_action)->diffInDays($d->tgl_action);
                        if ($diff == 0) {
                            $diff = Carbon::parse($dataSebelumnya->tgl_action)->diffInHours($d->tgl_action);
                            if ($diff == 0) {
                                $diff = Carbon::parse($dataSebelumnya->tgl_action)->diffInMinutes($d->tgl_action);
                                $diff = $diff . ' menit';
                            } else {
                                $diff = $diff . ' jam';
                            }
                        } else {
                            $diff = $diff . ' hari';
                        }
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> Setter telah menyelesaikan revisi dengan persetujuan kabag dalam waktu ' . $diff . '.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_action, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_action)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                }
            }
            return $html;
        }
    }
    protected function lihatProgressSetterKorektor($request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('pracetak_setter_selesai as pss')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'pss.pracetak_setter_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('users as u', 'pss.users_id', '=', 'u.id')
                ->where('pss.pracetak_setter_id', $id)
                ->select('pss.*', 'dp.judul_final', 'u.nama')
                ->orderBy('pss.id', 'desc')
                ->paginate(2);
            foreach ($data as $d) {
                $labelSetkor = $d->type == 'Setter' ? 'Setting' : 'Koreksi';
                $diff = Carbon::parse($d->tgl_proses_mulai)->diffInDays($d->tgl_proses_selesai);
                if ($diff == 0) {
                    $diff = Carbon::parse($d->tgl_proses_mulai)->diffInHours($d->tgl_proses_selesai);
                    if ($diff == 0) {
                        $diff = Carbon::parse($d->tgl_proses_mulai)->diffInMinutes($d->tgl_proses_selesai);
                        $diff = $diff . ' menit';
                    } else {
                        $diff = $diff . ' jam';
                    }
                } else {
                    $diff = $diff . ' hari';
                }
                switch ($d->section) {
                    case 'Proof Setting':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelSetkor . ' untuk proof oleh prodev telah diselesaikan dalam waktu ' . $diff . '.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Proof Setting Revision':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelSetkor . ' hasil revisi untuk proof oleh prodev telah diselesaikan.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Setting Revision':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelSetkor . ' hasil revisi tahap ' . $d->tahap . ' dari korektor telah diselesaikan setter dalam waktu ' . $diff . '.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Koreksi':
                        $html .= '<span class="ticket-item">
                        <div class="ticket-title">
                            <span><span class="bullet"></span> ' . $labelSetkor . ' setting naskah tahap ' . $d->tahap . ' oleh korektor telah diselesaikan dalam waktu ' . $diff . '.</span>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' . url('/manajemen-web/user/' . $d->users_id) . '">' . $d->nama . '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' . Carbon::createFromFormat('Y-m-d H:i:s', $d->tgl_proses_selesai, 'Asia/Jakarta')->diffForHumans() . ' (' . Carbon::parse($d->tgl_proses_selesai)->translatedFormat('l d M Y, H:i') . ')</div>
                        </div>
                        </span>';
                        break;
                }
            }
            return $html;
        }
    }
    protected function revisionActProdev($request)
    {
        try {
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->where('ps.id', $request->id)
                ->select(
                    'ps.*',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'pn.kode',
                    'pn.jalur_buku',
                    'pn.pic_prodev',
                )
                ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
            $dataTodo = DB::table('todo_list')
                ->where('form_id', $request->id)
                ->where('users_id', $data->pic_prodev)
                ->where('title', 'Proof setting pengajuan naskah yang berjudul "' . $data->judul_final . '".');
            if (is_null($dataTodo->first())) {
                //? Insert Todo Prodev
                DB::table('todo_list')->insert([
                    'form_id' => $data->id,
                    'users_id' => $data->pic_prodev,
                    'title' => 'Proof setting pengajuan naskah yang berjudul "' . $data->judul_final . '".',
                    'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode,
                    'status' => '1'
                ]);
            } else {
                $dataTodo->update([
                    'status' => '1'
                ]);
            }
            $rev = [
                'params' => 'Act Prodev',
                'type_user' => 'Setter',
                'type_action' => 'Revisi',
                'pracetak_setter_id' => $request->id, //?Pracetak Setter, Pracetak Setter Proof, & Pracetak Setter History
                'users_id' => auth()->id(), //?Pracetak Setter Proof & Pracetak Setter History
                'ket_revisi' => $request->ket_revisi, //?Pracetak Setter Proof, & Pracetak Setter History
                'tgl_action' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'selesai_proof' => NULL, //?Pracetak Setter & Pracetak Setter History
                'proses_saat_ini' => 'Setting Revisi', //?Pracetak Setter
                'proses' => '1', //?Pracetak Setter
                'type_history' => 'Update', //? Pracetak Setter History
                'status_his' => $data->status,
                'status' => 'Revisi' //?Pracetak Setter & Pracetak Setter History
            ];
            event(new PracetakSetterEvent($rev));
            $namaUser = auth()->user()->nama;
            $desc = 'Prodev (<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a>) meminta untuk hasil proof ke penulis direvisi.';
            $addTracker = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $data->id,
                'section_name' => 'Pracetak Setter',
                'description' => $desc,
                'icon' => 'fas fa-user-clock',
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($addTracker));
            return response()->json([
                'status' => 'success',
                'message' => 'Setting naskah direvisi!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function approveActProdev($request)
    {
        try {
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->where('ps.id', $request->id)
                ->select(
                    'ps.*',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'pn.kode',
                    'pn.jalur_buku',
                    'pn.pic_prodev',
                )
                ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
            //? Update Todo list prodev
            DB::table('todo_list')
                ->where('form_id', $request->id)
                ->where('users_id', $data->pic_prodev)
                ->where('title', 'Proof setting pengajuan naskah yang berjudul "' . $data->judul_final . '".')->update([
                    'status' => '1'
                ]);
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $done = [
                'params' => 'Act Prodev',
                'type_user' => 'Setter',
                'type_action' => 'Proof',
                'pracetak_setter_id' => $request->id, //?Pracetak Setter, Pracetak Setter Proof, & Pracetak Setter History
                'users_id' => auth()->id(), //?Pracetak Setter Proof & Pracetak Setter History
                'ket_revisi' => NULL, //?Pracetak Setter Proof, & Pracetak Setter History
                'tgl_action' => $tgl,
                'selesai_proof' => $tgl, //?Pracetak Setter & Pracetak Setter History
                'proses_saat_ini' => 'Antrian Koreksi', //?Pracetak Setter
                'proses' => '0', //?Pracetak Setter
                'type_history' => 'Update', //? Pracetak Setter History
                'status_his' => $data->status, //? Pracetak Setter History
                'status' => 'Proses' //?Pracetak Setter & Pracetak Setter History
            ];
            event(new PracetakSetterEvent($done));
            $namaUser = auth()->user()->nama;
            $desc = 'Prodev (<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a>) menyelesaikan proof ke penulis.';
            $addTracker = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $data->id,
                'section_name' => 'Pracetak Setter',
                'description' => $desc,
                'icon' => 'fas fa-people-arrows',
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($addTracker));
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function donerevisionActKabag($request)
    {
        try {
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->where('ps.id', $request->id)
                ->select(
                    'ps.*',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'pn.kode',
                    'pn.jalur_buku',
                    'pn.pic_prodev',
                )
                ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan!'
                ]);
            }
            $dataTodo = DB::table('todo_list')
                ->where('form_id', $request->id)
                ->where('users_id', $data->pic_prodev)
                ->where('title', 'Proof setting pengajuan naskah yang berjudul "' . $data->judul_final . '".');
            if (is_null($dataTodo->first())) {
                //? Insert Todo Prodev
                DB::table('todo_list')->insert([
                    'form_id' => $data->id,
                    'users_id' => $data->pic_prodev,
                    'title' => 'Proof setting pengajuan naskah yang berjudul "' . $data->judul_final . '".',
                    'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode,
                    'status' => '0'
                ]);
            } else {
                $dataTodo->update([
                    'status' => '0'
                ]);
            }
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $done = [
                'params' => 'Act Prodev',
                'type_user' => 'Setter',
                'type_action' => 'Selesai Revisi',
                'pracetak_setter_id' => $request->id, //?Pracetak Setter, Pracetak Setter Proof, & Pracetak Setter History
                'users_id' => auth()->id(), //?Pracetak Setter Proof & Pracetak Setter History
                'ket_revisi' => NULL, //?Pracetak Setter Proof, & Pracetak Setter History
                'tgl_action' => $tgl,
                'selesai_proof' => NULL, //?Pracetak Setter & Pracetak Setter History
                'proses_saat_ini' => 'Proof Prodev', //?Pracetak Setter
                'proses' => '1', //?Pracetak Setter
                'type_history' => 'Update', //? Pracetak Setter History
                'status_his' => $data->status, //? Pracetak Setter History
                'status' => 'Proses' //?Pracetak Setter & Pracetak Setter History
            ];
            event(new PracetakSetterEvent($done));
            $namaUser = auth()->user()->nama;
            $desc = 'Kabag (<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a>) menyelesaikan setting yang direvisi.';
            $addTracker = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $data->id,
                'section_name' => 'Pracetak Setter',
                'description' => $desc,
                'icon' => 'fas fa-user-check',
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($addTracker));
            return response()->json([
                'status' => 'success',
                'message' => 'Setting naskah telah selesai direvisi!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function logicPermissionAction($status = null, $jb = null, $pic_prodev, $id, $kode, $judul_final, $btn)
    {
        switch ($jb) {
            case 'MoU-Reguler':
                $gate = Gate::allows('do_create', 'ubah-atau-buat-setter-reguler') || Gate::allows('do_create', 'ubah-atau-buat-setter-mou');
                break;
            default:
                $gate = Gate::allows('do_create', 'ubah-atau-buat-setter-' . $jb);
                break;
        }
        if ($gate) {
            $btn = $this->buttonEdit($id, $kode, $btn);
        } else {
            if ($status == 'Selesai') {
                if (Gate::allows('do_approval', 'approval-deskripsi-produk') || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
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
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi Kesalahan!'
                ]);
                break;
        }
    }
    protected function selesaiSetter($id)
    {
        try {
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->where('ps.id', $id)
                ->select(
                    'ps.*',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'pn.kode',
                    'pn.jalur_buku',
                    'pn.pic_prodev',
                )
                ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ada'
                ]);
            }
            $namaUser = auth()->user()->nama;
            $dataProsesSelf = DB::table('pracetak_setter_selesai')
                ->where('type', 'Setter')
                ->where('section', 'Proof Setting')
                ->where('pracetak_setter_id', $data->id)
                ->where('users_id', auth()->id())
                ->first();
            if (!is_null($dataProsesSelf)) {
                $dataProsesSettRevision = DB::table('pracetak_setter_selesai')
                    ->where('type', 'Setter')
                    ->where('section', 'Setting Revision')
                    ->where('pracetak_setter_id', $data->id)
                    ->where('users_id', auth()->id())
                    ->orderBy('id', 'desc')
                    ->first();
                if (!is_null($dataProsesSettRevision)) {
                    $tahapSelanjutnya = $dataProsesSettRevision->tahap + 1;
                    $dataNew = DB::table('pracetak_setter_selesai')
                        ->where('type', 'Setter')
                        ->where('section', 'Setting Revision')
                        ->where('tahap', $tahapSelanjutnya)
                        ->where('pracetak_setter_id', $data->id)
                        ->get();
                    $setPros = count($dataNew) + 1;
                    if ($setPros == count(json_decode($data->setter, true))) {
                        $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        $pros = [
                            'params' => 'Proses Setting-Koreksi Selesai',
                            'type' => 'Setter',
                            'section' => 'Setting Revision',
                            'tahap' => $tahapSelanjutnya,
                            'pracetak_setter_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_mulai' => $data->mulai_setting,
                            'tgl_proses_selesai' => $tgl
                        ];
                        event(new PracetakSetterEvent($pros));
                        $done = [
                            'params' => 'Setting-Koreksi Selesai',
                            'label' => 'setting',
                            'id' => $data->id,
                            'selesai_setting' => $tgl,
                            'proses' => '0',
                            'proses_saat_ini' => 'Antrian Koreksi',
                            //Pracetak Setter History
                            'type_history' => 'Update',
                            'author_id' => auth()->id()
                        ];
                        event(new PracetakSetterEvent($done));
                        // DB::table('pracetak_setter')->where('id', $data->id)->update([
                        //     'selesai_koreksi' => NULL
                        // ]);
                    } else {
                        $pros = [
                            'params' => 'Proses Setting-Koreksi Selesai',
                            'type' => 'Setter',
                            'section' => 'Setting Revision',
                            'tahap' => $tahapSelanjutnya,
                            'pracetak_setter_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_mulai' => $data->mulai_setting,
                            'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        event(new PracetakSetterEvent($pros));
                    }
                    $desc = 'Setter (<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a>) menyelesaikan setting revisi tahap '.$tahapSelanjutnya.'.';
                } else {
                    $dataProses = DB::table('pracetak_setter_selesai')
                        ->where('type', 'Setter')
                        ->where('section', 'Setting Revision')
                        ->where('tahap', 1)
                        ->where('pracetak_setter_id', $data->id)
                        ->get();
                    $setPros = count($dataProses) + 1;
                    if ($setPros == count(json_decode($data->setter, true))) {
                        $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                        $pros = [
                            'params' => 'Proses Setting-Koreksi Selesai',
                            'type' => 'Setter',
                            'section' => 'Setting Revision',
                            'tahap' => 1,
                            'pracetak_setter_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_mulai' => $data->mulai_setting,
                            'tgl_proses_selesai' => $tgl
                        ];
                        event(new PracetakSetterEvent($pros));
                        $done = [
                            'params' => 'Setting-Koreksi Selesai',
                            'label' => 'setting',
                            'id' => $data->id,
                            'selesai_setting' => $tgl,
                            'proses' => '0',
                            'proses_saat_ini' => 'Antrian Koreksi',
                            //Pracetak Setter History
                            'type_history' => 'Update',
                            'author_id' => auth()->id()
                        ];
                        event(new PracetakSetterEvent($done));
                        DB::table('pracetak_setter')->where('id', $data->id)->update([
                            'selesai_koreksi' => NULL
                        ]);
                    } else {
                        $pros = [
                            'params' => 'Proses Setting-Koreksi Selesai',
                            'type' => 'Setter',
                            'section' => 'Setting Revision',
                            'tahap' => 1,
                            'pracetak_setter_id' => $data->id,
                            'users_id' => auth()->id(),
                            'tgl_proses_mulai' => $data->mulai_setting,
                            'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                        ];
                        event(new PracetakSetterEvent($pros));
                    }
                    $dataTodo = DB::table('todo_list')->where('form_id', $data->id)
                    ->where('users_id', auth()->id())
                    ->where('title', 'Selesaikan proses setting revisi naskah yang berjudul "' . $data->judul_final . '".');
                    if (is_null($dataTodo->first())) {
                        //? Insert Todo List Setter
                        DB::table('todo_list')->insert([
                            'form_id' => $data->id,
                            'users_id' => auth()->id(),
                            'title' => 'Selesaikan proses setting revisi naskah yang berjudul "' . $data->judul_final . '".',
                            'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode,
                            'status' => '1'
                        ]);
                    } else {
                        $dataTodo->update([
                            'status' => '1'
                        ]);
                    }
                    $desc = 'Setter (<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a>) menyelesaikan setting revisi tahap 1.';
                }

            } else {
                $dataProses = DB::table('pracetak_setter_selesai')
                    ->where('type', 'Setter')
                    ->where('section', 'Proof Setting')
                    ->where('tahap', 1)
                    ->where('pracetak_setter_id', $data->id)
                    ->get();
                $setPros = count($dataProses) + 1;
                if ($setPros == count(json_decode($data->setter, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Setter',
                        'section' =>  'Proof Setting',
                        'tahap' => 1,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_mulai' => $data->mulai_setting,
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new PracetakSetterEvent($pros));
                    $currentProccess = is_null($data->selesai_proof) ? 'Antrian Proof' : 'Antrian Koreksi';
                    $done = [
                        'params' => 'Setting-Koreksi Selesai',
                        'label' => 'setting',
                        'id' => $data->id,
                        'selesai_setting' => $tgl,
                        'proses' => '0',
                        'proses_saat_ini' => $currentProccess,
                        //Pracetak Setter History
                        'type_history' => 'Update',
                        'author_id' => auth()->id()
                    ];
                    event(new PracetakSetterEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Setter',
                        'section' =>  'Proof Setting',
                        'tahap' => 1,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_mulai' => $data->mulai_setting,
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new PracetakSetterEvent($pros));
                }
                $dataTodo = DB::table('todo_list')->where('form_id', $data->id)->where('users_id', auth()->id())->where('title', 'Selesaikan proses setting pengajuan naskah yang berjudul "' . $data->judul_final . '".');
                if (is_null($dataTodo->first())) {
                    DB::table('todo_list')->insert([
                        'form_id' => $data->id,
                        'users_id' => auth()->id(),
                        'title' => 'Selesaikan proses setting pengajuan naskah yang berjudul "' . $data->judul_final . '".',
                        'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode,
                        'status' => '1'
                    ]);
                } else {
                    $dataTodo->update([
                        'status' => '1'
                    ]);
                }
                $desc = 'Setter (<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a>) menyelesaikan sample setting.';

            }
            $addTracker = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $data->id,
                'section_name' => 'Pracetak Setter',
                'description' => $desc,
                'icon' => 'fas fa-user-check',
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($addTracker));
            return response()->json([
                'status' => 'success',
                'message' => 'Pengerjaan selesai'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function selesaiKorektor($id)
    {
        try {
            $data = DB::table('pracetak_setter as ps')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->where('ps.id', $id)
                ->select(
                    'ps.*',
                    'dp.naskah_id',
                    'dp.judul_final',
                    'pn.kode',
                    'pn.jalur_buku',
                    'pn.pic_prodev',
                )
                ->first();
            if (is_null($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ada'
                ]);
            }
            $namaUser = auth()->user()->nama;
            $dataProsesSelf = DB::table('pracetak_setter_selesai')
                ->where('type', 'Korektor')
                ->where('section', 'Koreksi')
                ->where('pracetak_setter_id', $data->id)
                ->where('users_id', auth()->id())
                ->orderBy('id', 'desc')
                ->first();

            if (!is_null($dataProsesSelf)) {
                $tahapSelanjutnya = $dataProsesSelf->tahap + 1;
                $dataNew = DB::table('pracetak_setter_selesai')
                    ->where('type', 'Korektor')
                    ->where('section', 'Koreksi')
                    ->where('tahap', $tahapSelanjutnya)
                    ->where('pracetak_setter_id', $data->id)
                    ->get();
                $korPros = count($dataNew) + 1;
                if ($korPros == count(json_decode($data->korektor, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => $tahapSelanjutnya,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_mulai' => $data->mulai_koreksi,
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new PracetakSetterEvent($pros));
                    $done = [
                        'params' => 'Setting-Koreksi Selesai',
                        'label' => 'koreksi',
                        'id' => $data->id,
                        'selesai_koreksi' => $tgl,
                        'proses' => '0',
                        'proses_saat_ini' => 'Setting Revisi',
                        //Pracetak Setter History
                        'type_history' => 'Update',
                        'author_id' => auth()->id()
                    ];
                    event(new PracetakSetterEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => $tahapSelanjutnya,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_mulai' => $data->mulai_koreksi,
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new PracetakSetterEvent($pros));
                }
                $desc = 'Korektor (<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a>) menyelesaikan koreksi tahap '.$tahapSelanjutnya.'.';
            } else {
                $dataProses = DB::table('pracetak_setter_selesai')
                    ->where('type', 'Korektor')
                    ->where('section', 'Koreksi')
                    ->where('tahap', 1)
                    ->where('pracetak_setter_id', $data->id)
                    ->get();
                $korPros = count($dataProses) + 1;
                if ($korPros == count(json_decode($data->korektor, true))) {
                    $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => 1,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_mulai' => $data->mulai_koreksi,
                        'tgl_proses_selesai' => $tgl
                    ];
                    event(new PracetakSetterEvent($pros));
                    $done = [
                        'params' => 'Setting-Koreksi Selesai',
                        'label' => 'koreksi',
                        'id' => $data->id,
                        'selesai_koreksi' => $tgl,
                        'proses' => '0',
                        'proses_saat_ini' => 'Setting Revisi',
                        //Pracetak Setter History
                        'type_history' => 'Update',
                        'author_id' => auth()->id()
                    ];
                    event(new PracetakSetterEvent($done));
                } else {
                    $pros = [
                        'params' => 'Proses Setting-Koreksi Selesai',
                        'type' => 'Korektor',
                        'section' => 'Koreksi',
                        'tahap' => 1,
                        'pracetak_setter_id' => $data->id,
                        'users_id' => auth()->id(),
                        'tgl_proses_mulai' => $data->mulai_koreksi,
                        'tgl_proses_selesai' => Carbon::now('Asia/Jakarta')->toDateTimeString()
                    ];
                    event(new PracetakSetterEvent($pros));
                }
                $desc = 'Korektor (<a href="'.url('/manajemen-web/user/' . auth()->id()).'">'.ucfirst($namaUser).'</a>) menyelesaikan koreksi tahap 1.';
            }
            $dataTodo = DB::table('todo_list')->where('form_id', $data->id)
                ->where('users_id', auth()->id())
                ->where('title', 'Selesaikan proses koreksi naskah yang berjudul "' . $data->judul_final . '".');
            if (is_null($dataTodo->first())) {
                //? Insert Todo List Korektor
                DB::table('todo_list')->insert([
                    'form_id' => $data->id,
                    'users_id' => auth()->id(),
                    'title' => 'Selesaikan proses koreksi naskah yang berjudul "' . $data->judul_final . '".',
                    'link' => '/penerbitan/pracetak/setter/detail?pra=' . $data->id . '&kode=' . $data->kode,
                    'status' => '1'
                ]);
            } else {
                $dataTodo->update([
                    'status' => '1'
                ]);
            }
            $addTracker = [
                'id' => Uuid::uuid4()->toString(),
                'section_id' => $data->id,
                'section_name' => 'Pracetak Setter',
                'description' => $desc,
                'icon' => 'fas fa-user-check',
                'created_by' => auth()->id()
            ];
            event(new TrackerEvent($addTracker));
            return response()->json([
                'status' => 'success',
                'message' => 'Pengerjaan selesai'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    protected function notifikasi()
    {
        try {
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
