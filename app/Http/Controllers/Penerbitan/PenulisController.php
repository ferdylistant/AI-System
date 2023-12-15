<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Penulis;
use Illuminate\Support\Str;
use App\Events\PenulisEvent;
use Illuminate\Http\Request;
use App\Exports\PenulisExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\{DB, Storage, Gate};

class PenulisController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('penerbitan_penulis')
                ->whereNull('deleted_at')
                ->select('id', 'nama', 'email', 'ponsel_domisili', 'ktp')
                ->orderBy('created_at', 'desc')
                ->get();
            switch ($request->input('request_')) {
                case 'table-penulis':
                    $data = collect($data)->map(function ($val) {
                        $val->email = is_null($val->email) ? '-' : $val->email;
                        $val->ponsel_domisili = is_null($val->ponsel_domisili) ? '-' : $val->ponsel_domisili;
                        $val->ktp = is_null($val->ktp) ? '-' : $val->ktp;
                        return $val;
                    });

                    $update = Gate::allows('do_update', 'ubah-data-penulis');
                    $delete = Gate::allows('do_delete', 'hapus-data-penulis');
                    return Datatables::of($data)
                        ->addColumn('history', function ($data) {
                            $historyData = DB::table('penerbitan_penulis_history')
                                ->where('penerbitan_penulis_id', $data->id)
                                ->get();
                            if ($historyData->isEmpty()) {
                                return '-';
                            } else {
                                $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="' . $data->id . '" data-nama="' . $data->nama . '"><i class="fas fa-history"></i>&nbsp;History</button>';
                                return $date;
                            }
                        })
                        ->addColumn('action', function ($data) use ($update, $delete) {
                            $btn =
                                '<a href="' .
                                url('penerbitan/penulis/detail-penulis/' . $data->id) .
                                '"
                                        class="d-block btn btn-sm btn-primary btn-icon mr-1">
                                        <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if ($update) {
                                $btn .=
                                    '<a href="' .
                                    url('penerbitan/penulis/mengubah-penulis/' . $data->id) .
                                    '"
                                        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1">
                                        <div><i class="fas fa-edit"></i></div></a>';
                            }
                            if ($delete) {
                                $btn .=
                                    '<a href="#" class="d-block btn btn-sm btn_DelPenulis btn-danger btn-icon mr-1 mt-1"
                                        data-id ="' .
                                    $data->id .
                                    '" data-nama="' .
                                    $data->nama .
                                    '">
                                        <div><i class="fas fa-trash-alt"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns(['history', 'action'])
                        ->make(true);
                    break;
                case 'count-data':
                    return $data->count();
                    break;
                default:
                    return $data;
                    break;
            }
        }
        return view('penerbitan.penulis.index', [
            'title' => 'Penulis Penerbitan',
        ]);
    }

    public function penulisTelahDihapus(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('penerbitan_penulis')
                ->whereNotNull('deleted_at')
                ->select('id', 'nama', 'email', 'ponsel_domisili', 'ktp', 'deleted_at', 'deleted_by')
                ->get();
            $data = collect($data)->map(function ($val) {
                $val->email = is_null($val->email) ? '-' : $val->email;
                $val->ponsel_domisili = is_null($val->ponsel_domisili) ? '-' : $val->ponsel_domisili;
                $val->ktp = is_null($val->ktp) ? '-' : $val->ktp;
                $val->deleted_at = is_null($val->deleted_at) ? '-' : date('d M Y, H:i', strtotime($val->deleted_at));
                $val->deleted_by = is_null($val->deleted_by) ? '-' : DB::table('users')->where('id', $val->deleted_by)->first()->nama;
                return $val;
            });
            $update = Gate::allows('do_delete', 'hapus-data-penulis');
            // foreach ($data as $key => $value) {
            //     $no = $key + 1;
            // }
            $start = 1;
            return DataTables::of($data)
                ->addColumn('action', function ($data) use ($update) {
                    if ($update) {
                        $btn =
                            '<a href="#"
                    class="d-block btn btn-sm btn_ResPenulis btn-dark btn-icon""
                    data-toggle="tooltip" title="Restore Data"
                    data-id="' .
                            $data->id .
                            '" data-nama="' .
                            $data->nama .
                            '">
                    <div><i class="fas fa-trash-restore-alt"></i> Restore</div></a>';
                    } else {
                        $btn = '<span class="badge badge-dark">No action</span>';
                    }
                    return $btn;
                })
                ->rawColumns(['history', 'action'])
                ->make(true);
        }

        return view('penerbitan.penulis.telah_dihapus', [
            'title' => 'Penulis Telah Dihapus',
        ]);
    }

    public function detailPenulis(Request $request)
    {
        $penulis = DB::table('penerbitan_penulis')
            ->whereNull('deleted_at')
            ->where('id', $request->id)
            ->first();
        if (is_null($penulis)) {
            abort(404);
        }

        $penulis = (object) collect($penulis)
            ->map(function ($val, $key) {
                if (!in_array($key, ['scan_ktp', 'scan_npwp', 'foto_penulis']) and $val == '') {
                    return '-';
                }
                if ($key == 'tanggal_lahir') {
                    $val = Carbon::createFromFormat('Y-m-d', $val)->format('d F Y');
                }
                return $val;
            })
            ->all();

        return view('penerbitan.penulis.detail-penulis', [
            'penulis' => $penulis,
            'title' => 'Detail Penulis Penerbitan',
        ]);
    }

    public function createPenulis(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate(
                    [
                        'add_nama' => 'required',
                        'add_telepon_domisili' => 'required',
                        'add_ponsel_domisili' => 'required',
                        'add_ktp' => 'nullable|unique:penerbitan_penulis,ktp',
                        'add_npwp' => 'nullable|unique:penerbitan_penulis,npwp',
                        'add_scan_ktp' => 'nullable|mimes:pdf',
                        'add_scan_npwp' => 'nullable|mimes:pdf',
                        'add_url_hibah_royalti' => 'nullable|url',
                        'add_url_tentang_penulis' => 'nullable|url',
                    ],
                    [
                        'required' => 'This field is requried',
                    ],
                );

                $idPenulis = Str::uuid()->getHex();
                $scanktp = null;
                $scannpwp = null;
                $fotoPenulis = null;
                // $fHibahRoyalti = null;
                if (!is_null($request->file('add_scan_npwp'))) {
                    $scannpwp = explode('/', $request->file('add_scan_npwp')->store('penerbitan/penulis/' . $idPenulis . '/'));
                    $scannpwp = end($scannpwp);
                }

                if (!is_null($request->file('add_scan_ktp'))) {
                    $scanktp = explode('/', $request->file('add_scan_ktp')->store('penerbitan/penulis/' . $idPenulis . '/'));
                    $scanktp = end($scanktp);
                }

                if (!is_null($request->file('add_foto_penulis'))) {
                    $fotoPenulis = explode('/', $request->file('add_foto_penulis')->store('penerbitan/penulis/' . $idPenulis . '/'));
                    $fotoPenulis = end($fotoPenulis);
                } else {
                    Storage::copy('default/pp-default.jpg', 'penerbitan/penulis/' . $idPenulis . '/default.jpg');
                    $fotoPenulis = 'default.jpg';
                }

                // if (!is_null($request->file('add_file_hibah_royalti'))) {
                //     $fHibahRoyalti = explode('/', $request->file('add_file_hibah_royalti')->store('penerbitan/penulis/' . $idPenulis . '/'));
                //     $fHibahRoyalti = end($fHibahRoyalti);
                // }

                try {
                    DB::beginTransaction();
                    $tglLahir = is_null($request->add_tanggal_lahir) ? NULL : Carbon::createFromFormat('d F Y', $request->input('add_tanggal_lahir'))->format('Y-m-d');
                    DB::table('penerbitan_penulis')->insert([
                        'id' => $idPenulis,
                        'nama' => $request->input('add_nama'),
                        'tempat_lahir' => $request->input('add_tempat_lahir'),
                        'tanggal_lahir' => $tglLahir,
                        'kewarganegaraan' => $request->input('add_kewarganegaraan'),
                        'alamat_domisili' => $request->input('add_alamat_domisili'),
                        'telepon_domisili' => $request->input('add_telepon_domisili'),
                        'ponsel_domisili' => $request->input('add_ponsel_domisili'),
                        'email' => $request->input('add_email'),
                        'nama_kantor' => $request->input('add_nama_kantor'),
                        'alamat_kantor' => $request->input('add_alamat_kantor'),
                        'jabatan_dikantor' => $request->input('add_jabatan_dikantor'),
                        'telepon_kantor' => $request->input('add_telepon_kantor'),
                        'sosmed_fb' => $request->input('add_sosmed_fb'),
                        'sosmed_ig' => $request->input('add_sosmed_ig'),
                        'sosmed_tw' => $request->input('add_sosmed_tw'),
                        'no_rekening' => $request->input('add_no_rek'),
                        'bank' => $request->input('add_bank'),
                        'bank_atasnama' => $request->input('add_bank_atasnama'),
                        'npwp' => $request->input('add_npwp'),
                        'ktp' => $request->input('add_ktp'),
                        'scan_npwp' => $scannpwp,
                        'scan_ktp' => $scanktp,
                        'foto_penulis' => $fotoPenulis,
                        'url_tentang_penulis' => $request->input('add_url_tentang_penulis'),
                        'url_hibah_royalti' => $request->input('add_url_hibah_royalti'),
                        'catatan' => $request->input('add_catatan'),
                        'created_by' => auth()->id(),
                    ]);
                    $insert = [
                        'params' => 'History Create Penulis',
                        'type_history' => 'Create',
                        'id' => $idPenulis,
                        'nama' => $request->input('add_nama'),
                        'tempat_lahir' => $request->input('add_tempat_lahir'),
                        'tanggal_lahir' => $tglLahir,
                        'kewarganegaraan' => $request->input('add_kewarganegaraan'),
                        'alamat_domisili' => $request->input('add_alamat_domisili'),
                        'telepon_domisili' => $request->input('add_telepon_domisili'),
                        'ponsel_domisili' => $request->input('add_ponsel_domisili'),
                        'url_hibah_royalti' => $request->input('add_url_hibah_royalti'),
                        'email' => $request->input('add_email'),
                        'nama_kantor' => $request->input('add_nama_kantor'),
                        'alamat_kantor' => $request->input('add_alamat_kantor'),
                        'jabatan_dikantor' => $request->input('add_jabatan_dikantor'),
                        'telepon_kantor' => $request->input('add_telepon_kantor'),
                        'sosmed_fb' => $request->input('add_sosmed_fb'),
                        'sosmed_ig' => $request->input('add_sosmed_ig'),
                        'sosmed_tw' => $request->input('add_sosmed_tw'),
                        'catatan' => $request->input('add_catatan'),
                        'author_id' => auth()->user()->id,
                        'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                    ];
                    event(new PenulisEvent($insert));
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data penulis berhasil ditambahkan!',
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }
        return view('penerbitan.penulis.create-penulis', [
            'title' => 'Tambah Penulis Penerbitan',
        ]);
    }

    public function updatePenulis(Request $request)
    {
        $penulis = DB::table('penerbitan_penulis')
            ->whereNull('deleted_at')
            ->where('id', $request->id)
            ->first();

        if (is_null($penulis)) {
            abort('404');
        }

        if ($request->ajax()) {
            if ($request->isMethod('POST')) {

                $scanktp = $penulis->scan_ktp;
                $scannpwp = $penulis->scan_npwp;
                $fotoPenulis = $penulis->foto_penulis;
                // $fHibahRoyalti = $penulis->file_hibah_royalti;
                try {
                    if (!is_null($request->file('edit_scan_npwp'))) {
                        $scannpwp = explode('/', $request->file('edit_scan_npwp')->store('penerbitan/penulis/' . $request->id . '/'));
                        $scannpwp = end($scannpwp);
                    }
                    if (!is_null($request->file('edit_scan_ktp'))) {
                        $scanktp = explode('/', $request->file('edit_scan_ktp')->store('penerbitan/penulis/' . $request->id . '/'));
                        $scanktp = end($scanktp);
                    }
                    if (!is_null($request->file('edit_foto_penulis'))) {
                        $fotoPenulis = explode('/', $request->file('edit_foto_penulis')->store('penerbitan/penulis/' . $request->id . '/'));
                        $fotoPenulis = end($fotoPenulis);
                    }
                    // if (!is_null($request->file('edit_file_hibah_royalti'))) {
                    //     $fHibahRoyalti = explode('/', $request->file('edit_file_hibah_royalti')->store('penerbitan/penulis/' . $request->id . '/'));
                    //     $fHibahRoyalti = end($fHibahRoyalti);
                    // }
                    $history = DB::table('penerbitan_penulis')
                        ->where('id', $request->edit_id)
                        ->first();
                    DB::beginTransaction();
                    $updated = DB::table('penerbitan_penulis')
                        ->where('id', $request->id)
                        ->update([
                            'nama' => $request->input('edit_nama'),
                            'tempat_lahir' => $request->input('edit_tempat_lahir'),
                            'tanggal_lahir' => is_null($request->edit_tanggal_lahir) ? null : Carbon::createFromFormat('d F Y', $request->input('edit_tanggal_lahir'))->format('Y-m-d'),
                            'kewarganegaraan' => $request->input('edit_kewarganegaraan'),
                            'alamat_domisili' => $request->input('edit_alamat_domisili'),
                            'telepon_domisili' => $request->input('edit_telepon_domisili'),
                            'ponsel_domisili' => $request->input('edit_ponsel_domisili'),
                            'email' => $request->input('edit_email'),
                            'nama_kantor' => $request->input('edit_nama_kantor'),
                            'alamat_kantor' => $request->input('edit_alamat_kantor'),
                            'jabatan_dikantor' => $request->input('edit_jabatan_dikantor'),
                            'telepon_kantor' => $request->input('edit_telepon_kantor'),
                            'sosmed_fb' => $request->input('edit_sosmed_fb'),
                            'sosmed_ig' => $request->input('edit_sosmed_ig'),
                            'sosmed_tw' => $request->input('edit_sosmed_tw'),
                            'url_tentang_penulis' => $request->input('edit_url_tentang_penulis'),
                            'no_rekening' => $request->input('edit_no_rek'),
                            'bank' => $request->input('edit_bank'),
                            'bank_atasnama' => $request->input('edit_bank_atasnama'),
                            'npwp' => $request->input('edit_npwp'),
                            'ktp' => $request->input('edit_ktp'),
                            'url_hibah_royalti' => $request->input('edit_url_hibah_royalti'),
                            'scan_npwp' => $scannpwp,
                            'scan_ktp' => $scanktp,
                            'foto_penulis' => $fotoPenulis,
                            'catatan' => $request->input('edit_catatan'),
                            'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                            'updated_by' => auth()->id(),
                        ]);
                    // return response()->json($penulis);
                    if ($updated) {

                        if (is_null($history->tanggal_lahir)) {
                            $tglLahirNew = NULL;
                            $tglLahirHis = NULL;
                            if (!is_null($request->edit_tanggal_lahir)) {
                                $tglLahirNew = Carbon::createFromFormat('d F Y', $request->edit_tanggal_lahir)->format('Y-m-d');
                            }
                        } else {
                            $tglLahirNew = NULL;
                            $tglLahirHis = $history->tanggal_lahir;
                            if (!is_null($request->edit_tanggal_lahir)) {
                                $tglLahirNew = Carbon::createFromFormat('d F Y', $request->edit_tanggal_lahir)->format('Y-m-d');
                            }
                        }
                        $insert = [
                            'params' => 'History Update Penulis',
                            'type_history' => 'Update',
                            'id' => $request->id,
                            'nama_his' => $history->nama == $request->edit_nama ? null : $history->nama,
                            'nama_new' => $history->nama == $request->edit_nama ? null : $request->edit_nama,
                            'tanggal_lahir_his' => $tglLahirHis,
                            'tanggal_lahir_new' => $tglLahirNew,
                            'tempat_lahir_his' => $history->tempat_lahir == $request->edit_tempat_lahir ? null : $history->tempat_lahir,
                            'tempat_lahir_new' => $history->tempat_lahir == $request->edit_tempat_lahir ? null : $request->edit_tempat_lahir,
                            'kewarganegaraan_his' => $history->kewarganegaraan == $request->edit_kewarganegaraan ? null : $history->kewarganegaraan,
                            'kewarganegaraan_new' => $history->kewarganegaraan == $request->edit_kewarganegaraan ? null : $request->edit_kewarganegaraan,
                            'alamat_domisili_his' => $history->alamat_domisili == $request->edit_alamat_domisili ? null : $history->alamat_domisili,
                            'alamat_domisili_new' => $history->alamat_domisili == $request->edit_alamat_domisili ? null : $request->edit_alamat_domisili,
                            'ponsel_domisili_his' => $history->ponsel_domisili == $request->edit_ponsel_domisili ? null : $history->ponsel_domisili,
                            'ponsel_domisili_new' => $history->ponsel_domisili == $request->edit_ponsel_domisili ? null : $request->edit_ponsel_domisili,
                            'telepon_domisili_his' => $history->telepon_domisili == $request->edit_telepon_domisili ? null : $history->telepon_domisili,
                            'telepon_domisili_new' => $history->telepon_domisili == $request->edit_telepon_domisili ? null : $request->edit_telepon_domisili,
                            'email_his' => $history->email == $request->edit_email ? null : $history->email,
                            'email_new' => $history->email == $request->edit_email ? null : $request->edit_email,
                            'nama_kantor_his' => $history->nama_kantor == $request->edit_nama_kantor ? null : $history->nama_kantor,
                            'nama_kantor_new' => $history->nama_kantor == $request->edit_nama_kantor ? null : $request->edit_nama_kantor,
                            'jabatan_dikantor_his' => $history->jabatan_dikantor == $request->edit_jabatan_dikantor ? null : $history->jabatan_dikantor,
                            'jabatan_dikantor_new' => $history->jabatan_dikantor == $request->edit_jabatan_dikantor ? null : $request->edit_jabatan_dikantor,
                            'alamat_kantor_his' => $history->alamat_kantor == $request->edit_alamat_kantor ? null : $history->alamat_kantor,
                            'alamat_kantor_new' => $history->alamat_kantor == $request->edit_alamat_kantor ? null : $request->edit_alamat_kantor,
                            'telepon_kantor_his' => $history->telepon_kantor == $request->edit_telepon_kantor ? null : $history->telepon_kantor,
                            'telepon_kantor_new' => $history->telepon_kantor == $request->edit_telepon_kantor ? null : $request->edit_telepon_kantor,
                            'sosmed_fb_his' => $history->sosmed_fb == $request->edit_sosmed_fb ? null : $history->sosmed_fb,
                            'sosmed_fb_new' => $history->sosmed_fb == $request->edit_sosmed_fb ? null : $request->edit_sosmed_fb,
                            'sosmed_ig_his' => $history->sosmed_ig == $request->edit_sosmed_ig ? null : $history->sosmed_ig,
                            'sosmed_ig_new' => $history->sosmed_ig == $request->edit_sosmed_ig ? null : $request->edit_sosmed_ig,
                            'sosmed_tw_his' => $history->sosmed_tw == $request->edit_sosmed_tw ? null : $history->sosmed_tw,
                            'sosmed_tw_new' => $history->sosmed_tw == $request->edit_sosmed_tw ? null : $request->edit_sosmed_tw,
                            'url_hibah_royalti_his' => $history->url_hibah_royalti == $request->edit_url_hibah_royalti ? null : $history->url_hibah_royalti,
                            'url_hibah_royalti_new' => $history->url_hibah_royalti == $request->edit_url_hibah_royalti ? null : $request->edit_url_hibah_royalti,
                            'url_tentang_penulis_his' => $history->url_tentang_penulis == $request->edit_url_tentang_penulis ? null : $history->url_tentang_penulis,
                            'url_tentang_penulis_new' => $history->url_tentang_penulis == $request->edit_url_tentang_penulis ? null : $request->edit_url_tentang_penulis,
                            'bank_his' => $history->bank == $request->edit_bank ? null : $history->bank,
                            'bank_new' => $history->bank == $request->edit_bank ? null : $request->edit_bank,
                            'bank_atasnama_his' => $history->bank_atasnama == $request->edit_bank_atasnama ? null : $history->bank_atasnama,
                            'bank_atasnama_new' => $history->bank_atasnama == $request->edit_bank_atasnama ? null : $request->edit_bank_atasnama,
                            'no_rekening_his' => $history->no_rekening == $request->edit_no_rek ? null : $history->no_rekening,
                            'no_rekening_new' => $history->no_rekening == $request->edit_no_rek ? null : $request->edit_no_rek,
                            'npwp_his' => $history->npwp == $request->edit_npwp ? null : $history->npwp,
                            'npwp_new' => $history->npwp == $request->edit_npwp ? null : $request->edit_npwp,
                            'ktp_his' => $history->ktp == $request->edit_ktp ? null : $history->ktp,
                            'ktp_new' => $history->ktp == $request->edit_ktp ? null : $request->edit_ktp,
                            'catatan_his' => $history->catatan == $request->edit_catatan ? null : $history->catatan,
                            'catatan_new' => $history->catatan == $request->edit_catatan ? null : $request->edit_catatan,
                            'author_id' => auth()->user()->id,
                            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                        ];
                        event(new PenulisEvent($insert));
                    }
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data penulis berhasil ditambahkan!',
                    ]);
                } catch (\Exception $e) {
                    if ($scanktp !== $penulis->scan_ktp) {
                        Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $scanktp);
                    }
                    if ($scannpwp !== $penulis->scan_npwp) {
                        Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $scannpwp);
                    }
                    if ($fotoPenulis !== $penulis->foto_penulis) {
                        Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $fotoPenulis);
                    }
                    // if ($fHibahRoyalti !== $penulis->file_hibah_royalti) {
                    //     Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $fHibahRoyalti);
                    // }
                    DB::rollBack();
                    return abort(500, $e->getMessage());
                }

                if ($penulis->scan_ktp != $scanktp) {
                    Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $penulis->scan_ktp);
                }
                if ($penulis->scan_npwp != $scannpwp) {
                    Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $penulis->scan_npwp);
                }
                if ($penulis->foto_penulis != $fotoPenulis) {
                    Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $penulis->foto_penulis);
                }
                // if ($penulis->file_hibah_royalti != $fHibahRoyalti) {
                //     Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $penulis->file_hibah_royalti);
                // }
                return;
            }
        }

        $penulis = (object) collect($penulis)
            ->map(function ($item, $key) {
                if ($key == 'tanggal_lahir') {
                    return !is_null($item) ? Carbon::createFromFormat('Y-m-d', $item)->format('d F Y') : null;
                }
                return $item;
            })
            ->all();

        return view('penerbitan.penulis.update-penulis', [
            'penulis' => $penulis,
            'title' => 'Update Penulis',
        ]);
    }

    public function deletePenulis(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->id;
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $delete = [
                'params' => 'History Delete Penulis',
                'id' => $id,
                'type_history' => 'Delete',
                'deleted_at' => $tgl,
                'author_id' => auth()->user()->id,
                'modified_at' => $tgl,
            ];
            event(new PenulisEvent($delete));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil hapus data penulis!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function restorePenulis(Request $request)
    {
        $id = $request->id;
        $restored = DB::table('penerbitan_penulis')
            ->where('id', $id)
            ->update(['deleted_at' => null, 'deleted_by' => null]);
        $insert = [
            'params' => 'History Restored Penulis',
            'penerbitan_penulis_id' => $id,
            'type_history' => 'Restore',
            'restored_at' => now(),
            'author_id' => auth()->user()->id,
            'modified_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
        ];
        event(new PenulisEvent($insert));
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengembalikan data penulis!',
        ]);
    }

    public function lihatHistoryPenulis(Request $request)
    {
        if ($request->ajax()) {
            $html = '';
            $id = $request->id;
            $data = DB::table('penerbitan_penulis_history as pph')
                ->join('penerbitan_penulis as pp', 'pp.id', '=', 'pph.penerbitan_penulis_id')
                ->join('users as u', 'u.id', '=', 'pph.author_id')
                ->where('pph.penerbitan_penulis_id', $id)
                ->select('pph.*', 'u.nama')
                ->orderBy('pph.id', 'desc')
                ->paginate(2);

            foreach ($data as $d) {
                switch ($d->type_history) {
                    case 'Create':
                        $html .= '<span class="ticket-item" id="newAppend">';

                        if (!is_null($d->nama_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Penulis dengan nama <b class="text-dark">' .
                                $d->nama_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        $html .=
                            '<div class="ticket-info">
                            <div class="text-muted pt-2">Modified by <a href="' .
                            url('/manajemen-web/user/' . $d->author_id) .
                            '">' .
                            $d->nama .
                            '</a></div>
                            <div class="bullet pt-2"></div>
                            <div class="pt-2">' .
                            Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() .
                            ' (' .
                            Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') .
                            ')</div>
                        </div>
                        </span>';
                        break;
                    case 'Update':
                        $html .= '<span class="ticket-item" id="newAppend">';

                        if (!is_null($d->nama_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Penulis <b class="text-dark">' .
                                $d->nama_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->nama_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->nama_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Penulis <b class="text-dark">' .
                                $d->nama_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->tanggal_lahir_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Tanggal lahir <b class="text-dark">' .
                                Carbon::parse($d->tanggal_lahir_his)->translatedFormat('d M Y') .
                                '</b> diubah menjadi <b class="text-dark">' .
                                Carbon::parse($d->tanggal_lahir_new)->translatedFormat('d M Y') .
                                '</b> </span></div>';
                        } elseif (!is_null($d->tanggal_lahir_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Tanggal lahir <b class="text-dark">' .
                                Carbon::parse($d->tanggal_lahir_new)->translatedFormat('d M Y') .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->tempat_lahir_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Tempat lahir <b class="text-dark">' .
                                $d->tempat_lahir_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->tempat_lahir_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->tempat_lahir_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Tanggal lahir <b class="text-dark">' .
                                $d->tempat_lahir_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->kewarganegaraan_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Kewarganegaraan <b class="text-dark">' .
                                $d->kewarganegaraan_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->kewarganegaraan_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->kewarganegaraan_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Kewarganegaraan <b class="text-dark">' .
                                $d->kewarganegaraan_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->alamat_domisili_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Alamat domisili <b class="text-dark">' .
                                $d->alamat_domisili_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->alamat_domisili_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->alamat_domisili_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Alamat domisili <b class="text-dark">' .
                                $d->alamat_domisili_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->ponsel_domisili_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Ponsel domisili <b class="text-dark">' .
                                $d->ponsel_domisili_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->ponsel_domisili_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->ponsel_domisili_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Ponsel domisili <b class="text-dark">' .
                                $d->ponsel_domisili_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->telepon_domisili_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Telepon domisili <b class="text-dark">' .
                                $d->telepon_domisili_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->telepon_domisili_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->telepon_domisili_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Telepon domisili <b class="text-dark">' .
                                $d->telepon_domisili_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->email_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Email <b class="text-dark">' .
                                $d->email_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->email_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->email_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Email <b class="text-dark">' .
                                $d->email_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->nama_kantor_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Nama kantor <b class="text-dark">' .
                                $d->nama_kantor_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->nama_kantor_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->nama_kantor_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Nama kantor <b class="text-dark">' .
                                $d->nama_kantor_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->jabatan_dikantor_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Jabatan dikantor <b class="text-dark">' .
                                $d->jabatan_dikantor_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->jabatan_dikantor_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->jabatan_dikantor_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Jabatan dikantor <b class="text-dark">' .
                                $d->jabatan_dikantor_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->alamat_kantor_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Alamat kantor <b class="text-dark">' .
                                $d->alamat_kantor_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->alamat_kantor_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->alamat_kantor_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Alamat kantor <b class="text-dark">' .
                                $d->alamat_kantor_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->telepon_kantor_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Telepon kantor <b class="text-dark">' .
                                $d->telepon_kantor_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->telepon_kantor_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->telepon_kantor_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Telepon kantor <b class="text-dark">' .
                                $d->telepon_kantor_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->sosmed_fb_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Sosmed facebook <b class="text-dark">' .
                                $d->sosmed_fb_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->sosmed_fb_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->sosmed_fb_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Sosmed facebook <b class="text-dark">' .
                                $d->sosmed_fb_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->sosmed_ig_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Sosmed instagram <b class="text-dark">' .
                                $d->sosmed_ig_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->sosmed_ig_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->sosmed_ig_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Sosmed instagram <b class="text-dark">' .
                                $d->sosmed_ig_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->sosmed_tw_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Sosmed twitter <b class="text-dark">' .
                                $d->sosmed_tw_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->sosmed_tw_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->sosmed_tw_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Sosmed twitter <b class="text-dark">' .
                                $d->sosmed_tw_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->catatan_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Catatan <b class="text-dark">' .
                                $d->catatan_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->catatan_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->catatan_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Catatan <b class="text-dark">' .
                                $d->catatan_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->url_hibah_royalti_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Url hibah royalti <a target="_blank" href="'.$d->url_hibah_royalti_his.'" class="text-dark"><b>' .
                                    $d->url_hibah_royalti_his .
                                    '</b></a> diubah menjadi <a target="_blank" href="'.$d->url_hibah_royalti_new.'" class="text-dark"><b>' .
                                $d->url_hibah_royalti_new .
                                '</b></a> </span></div>';
                        } elseif (!is_null($d->url_hibah_royalti_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Url hibah royalti <a target="_blank" href="'.$d->url_hibah_royalti_new.'" class="text-dark"><b>' .
                                    $d->url_hibah_royalti_new .
                                    '</b></a> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->url_tentang_penulis_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Url tentang penulis <b class="text-dark">' .
                                $d->url_tentang_penulis_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->url_tentang_penulis_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->url_tentang_penulis_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Url tentang penulis <b class="text-dark">' .
                                $d->url_tentang_penulis_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->bank_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Bank <b class="text-dark">' .
                                $d->bank_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->bank_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->bank_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Bank <b class="text-dark">' .
                                $d->bank_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->bank_atasnama_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Bank atas nama <b class="text-dark">' .
                                $d->bank_atasnama_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->bank_atasnama_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->bank_atasnama_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> Bank atas nama <b class="text-dark">' .
                                $d->bank_atasnama_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->no_rekening_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> No rekening <b class="text-dark">' .
                                $d->no_rekening_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->no_rekening_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->no_rekening_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> No rekening <b class="text-dark">' .
                                $d->no_rekening_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->npwp_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> NPWP <b class="text-dark">' .
                                $d->npwp_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->npwp_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->npwp_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> NPWP <b class="text-dark">' .
                                $d->npwp_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        if (!is_null($d->ktp_his)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> KTP <b class="text-dark">' .
                                $d->ktp_his .
                                '</b> diubah menjadi <b class="text-dark">' .
                                $d->ktp_new .
                                '</b> </span></div>';
                        } elseif (!is_null($d->ktp_new)) {
                            $html .=
                                '<div class="ticket-title">
                                    <span><span class="bullet"></span> KTP <b class="text-dark">' .
                                $d->ktp_new .
                                '</b> ditambahkan. </span></div>';
                        }
                        $html .=
                            '<div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a href="' .
                            url('/manajemen-web/user/' . $d->author_id) .
                            '">' .
                            $d->nama .
                            '</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">' .
                            Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() .
                            ' (' .
                            Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') .
                            ')</div>
                    </div>
                    </span>';
                        break;
                    case 'Delete':
                        $html .=
                            '<span class="ticket-item" id="newAppend">
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Data Penulis dihapus pada <b class="text-dark">' .
                            Carbon::parse($d->deleted_at)->translatedFormat('l, d M Y, H:i') .
                            '</b>.</span>
                            </div>
                            <div class="ticket-info">
                                <div class="text-muted pt-2">Modified by <a href="' .
                            url('/manajemen-web/user/' . $d->author_id) .
                            '">' .
                            $d->nama .
                            '</a></div>
                                <div class="bullet pt-2"></div>
                                <div class="pt-2">' .
                            Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() .
                            ' (' .
                            Carbon::parse($d->modified_at)->translatedFormat('l, d M Y, H:i') .
                            ')</div>
                            </div>
                            </span>';
                        break;
                    case 'Restore':
                        $html .=
                            '<span class="ticket-item" id="newAppend">
                                <div class="ticket-title">
                                    <span><span class="bullet"></span> Data Penulis direstore pada <b class="text-dark">' .
                            Carbon::parse($d->restored_at)->translatedFormat('l, d M Y, H:i') .
                            '</b>.</span>
                                </div>
                                <div class="ticket-info">
                                    <div class="text-muted pt-2">Modified by <a href="' .
                            url('/manajemen-web/user/' . $d->author_id) .
                            '">' .
                            $d->nama .
                            '</a></div>
                                    <div class="bullet pt-2"></div>
                                    <div class="pt-2">' .
                            Carbon::createFromFormat('Y-m-d H:i:s', $d->modified_at, 'Asia/Jakarta')->diffForHumans() .
                            ' (' .
                            Carbon::parse($d->modified_at)->translatedFormat('l d M Y, H:i') .
                            ')</div>
                                </div>
                                </span>';
                        break;
                }
            }
            return $html;
        }
    }

    public function exportAll()
    {
        $tgl = Carbon::now('Asia/Jakarta')->format('dmYHi');
        return (new PenulisExport())->download($tgl.'_penulis.xlsx');
    }
    public function exportData($format)
    {
        $tgl = Carbon::now('Asia/Jakarta')->format('hisdmY');
        if ($format == 'pdf') {
            // $res = DB::table('penerbitan_penulis')
            // ->select('nama','email','tempat_lahir','tanggal_lahir','alamat_domisili','ponsel_domisili','npwp','ktp')
            // ->get();
            if (Cache::has('penulis')) {
                $value = Cache::get('penulis');
            } else {
                $value = Cache::remember('penulis', 600, function () {
                    return  DB::table('penerbitan_penulis')
                        ->select('nama', 'email', 'tempat_lahir', 'tanggal_lahir', 'alamat_domisili', 'ponsel_domisili', 'npwp', 'ktp')
                        ->get();
                    // return Penulis::all();
                });
            }
            $setOption = new Options();
            $setOption->set('dpi', 150);
            $setOption->set('defaultFont', 'sans-serif');
            // $setOption->set('isRemoteEnabled',true);
            $result = new Dompdf($setOption);
            // dd($value);
            $data = [

                'title' => 'PDF Data Penulis',
                'date' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'data' => $value,

            ];
            $html = view('penerbitan.penulis.exportpdf', $data)->render();
            $result->loadHtml($html);
            $result->setPaper('a3', 'landscape');
            $result->render();
            // $result = Pdf::loadView('penerbitan.penulis.exportpdf', $data)->setPaper('a3', 'landscape')->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $type = "stream";
        } else {
            $result = (new PenulisExport);
            $type = "download";
        }
        return $result->$type('penulis_' . $tgl . '.' . $format);
    }
}
