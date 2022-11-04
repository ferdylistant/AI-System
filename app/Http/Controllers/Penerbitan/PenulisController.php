<?php

namespace App\Http\Controllers\Penerbitan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage, Gate};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;


class PenulisController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->input('request_') === 'table-penulis') {
                $data = DB::table('penerbitan_penulis')
                    ->whereNull('deleted_at')
                    ->select('id', 'nama', 'email', 'ponsel_domisili', 'ktp')
                    ->get();
                $data = collect($data)->map(function ($val) {
                    $val->email = is_null($val->email) ? '-' : $val->email;
                    $val->ponsel_domisili = is_null($val->ponsel_domisili) ? '-' : $val->ponsel_domisili;
                    $val->ktp = is_null($val->ktp) ? '-' : $val->ktp;
                    return $val;
                });

                $update = Gate::allows('do_update', 'ubah-data-penulis');
                $delete = Gate::allows('do_delete', 'hapus-data-penulis');
                return Datatables::of($data)
                    ->addColumn('action', function ($data) use ($update, $delete) {
                        $btn = '<a href="' . url('penerbitan/penulis/detail-penulis/' . $data->id) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                        if ($update) {
                            $btn .= '<a href="' . url('penerbitan/penulis/mengubah-penulis/' . $data->id) . '"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1">
                                    <div><i class="fas fa-edit"></i></div></a>';
                        }
                        if ($delete) {
                            $btn .= '<a href="#" class="d-block btn btn-sm btn_DelPenulis btn-danger btn-icon mr-1 mt-1"
                                    data-id ="' . $data->id . '" data-nama="' . $data->nama . '">
                                    <div><i class="fas fa-trash-alt"></i></div></a>';
                        }
                        return $btn;
                    })
                    ->make(true);
            }
        }
        return view('penerbitan.penulis.index', [
            'title' => 'Penulis Penerbitan'
        ]);
    }

    public function detailPenulis(Request $request)
    {
        $penulis = DB::table('penerbitan_penulis')->whereNull('deleted_at')
            ->where('id', $request->id)->first();
        if (is_null($penulis)) {
            abort(404);
        }

        $penulis = (object)collect($penulis)->map(function ($val, $key) {
            if (
                !in_array($key, ['scan_ktp', 'scan_npwp', 'foto_penulis', 'file_hibah_royalti'])
                and $val == ''
            ) {
                return '-';
            }
            if ($key == 'tanggal_lahir') {
                $val = Carbon::createFromFormat('Y-m-d', $val)->format('d F Y');
            }
            return $val;
        })->all();

        return view('penerbitan.penulis.detail-penulis', [
            'penulis' => $penulis,
            'title' => 'Detail Penulis Penerbitan'
        ]);
    }

    public function createPenulis(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                $request->validate([
                    'add_nama' => 'required',
                    'add_telepon_domisili' => 'required',
                    'add_ponsel_domisili' => 'required',
                    'add_ktp' => 'nullable|unique:penerbitan_penulis,ktp',
                    'add_npwp' => 'nullable|unique:penerbitan_penulis,npwp',
                    'add_scan_ktp' => 'nullable|mimes:jpg,jpeg,png',
                    'add_scan_npwp' => 'nullable|mimes:jpg,jpeg,png',
                    'add_file_hibah_royalti' => 'nullable|mimes:pdf',
                    'add_url_tentang_penulis' => 'nullable|url'
                ], [
                    'required' => 'This field is requried'
                ]);

                $idPenulis = Str::uuid()->getHex();
                $scanktp = null;
                $scannpwp = null;
                $fotoPenulis = null;
                $fHibahRoyalti = null;
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

                if (!is_null($request->file('add_file_hibah_royalti'))) {
                    $fHibahRoyalti = explode('/', $request->file('add_file_hibah_royalti')->store('penerbitan/penulis/' . $idPenulis . '/'));
                    $fHibahRoyalti = end($fHibahRoyalti);
                }

                DB::table('penerbitan_penulis')->insert([
                    'id' => $idPenulis,
                    'nama' => $request->input('add_nama'),
                    'tempat_lahir' => $request->input('add_tempat_lahir'),
                    'tanggal_lahir' => Carbon::createFromFormat('d F Y', $request->input('add_tanggal_lahir'))
                        ->format('Y-m-d'),
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
                    'file_hibah_royalti' => $fHibahRoyalti,
                    'created_by' => auth()->id()
                ]);

                return;
            }
        }
        return view('penerbitan.penulis.create-penulis', [
            'title' => 'Tambah Penulis Penerbitan'
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
                $fHibahRoyalti = $penulis->file_hibah_royalti;
                try {
                    if (!is_null($request->file('edit_scan_npwp'))) {
                        $scannpwp = explode('/', $request->file('edit_scan_npwp')
                            ->store('penerbitan/penulis/' . $request->id . '/'));
                        $scannpwp = end($scannpwp);
                    }
                    if (!is_null($request->file('edit_scan_ktp'))) {
                        $scanktp = explode('/', $request->file('edit_scan_ktp')
                            ->store('penerbitan/penulis/' . $request->id . '/'));
                        $scanktp = end($scanktp);
                    }
                    if (!is_null($request->file('edit_foto_penulis'))) {
                        $fotoPenulis = explode('/', $request->file('edit_foto_penulis')
                            ->store('penerbitan/penulis/' . $request->id . '/'));
                        $fotoPenulis = end($fotoPenulis);
                    }
                    if (!is_null($request->file('edit_file_hibah_royalti'))) {
                        $fHibahRoyalti = explode('/', $request->file('edit_file_hibah_royalti')
                            ->store('penerbitan/penulis/' . $request->id . '/'));
                        $fHibahRoyalti = end($fHibahRoyalti);
                    }
                    DB::table('penerbitan_penulis')->where('id', $request->id)
                        ->update([
                            'nama' => $request->input('edit_nama'),
                            'tempat_lahir' => $request->input('edit_tempat_lahir'),
                            'tanggal_lahir' => Carbon::createFromFormat('d F Y', $request->input('edit_tanggal_lahir'))
                                ->format('Y-m-d'),
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
                            'file_hibah_royalti' => $fHibahRoyalti,
                            'scan_npwp' => $scannpwp,
                            'scan_ktp' => $scanktp,
                            'foto_penulis' => $fotoPenulis,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => auth()->id()
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
                    if ($fHibahRoyalti !== $penulis->file_hibah_royalti) {
                        Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $fHibahRoyalti);
                    }
                    return abort(500);
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
                if ($penulis->file_hibah_royalti != $fHibahRoyalti) {
                    Storage::delete('penerbitan/penulis/' . $penulis->id . '/' . $penulis->file_hibah_royalti);
                }
                return;
            }
        }

        $penulis = (object)collect($penulis)->map(function ($item, $key) {
            if ($key == 'tanggal_lahir') {
                return Carbon::createFromFormat('Y-m-d', $item)->format('d F Y');
            }
            return $item;
        })->all();

        return view('penerbitan.penulis.update-penulis', [
            'penulis' => $penulis,
            'title' => 'Update Penulis',
        ]);
    }

    public function deletePenulis(Request $request)
    {
        $data = DB::table('penerbitan_penulis')
            ->where('id', $request->input('id'))
            ->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => auth()->id()
            ]);
        return $data;
    }
}
