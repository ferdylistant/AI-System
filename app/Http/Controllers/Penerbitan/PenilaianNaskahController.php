<?php

namespace App\Http\Controllers\Penerbitan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{DB, Gate, Storage};

class PenilaianNaskahController extends Controller
{
    public function index(Request $request)
    {
        switch ($request->cat) {
            case 'prodev':
                return $this->penilaianProdev($request);
            case 'editset':
                return $this->penilaianEditSet($request);
            case 'mpemasaran':
                return $this->penilaianMPemasaran($request);
            case 'dpemasaran':
                return $this->penilaianDPemasaran($request);
            case 'penerbitan':
                return $this->penilaianPenerbitan($request);
            case 'direksi':
                return $this->penilaianDireksi($request);
            case 'form-prodev':
                return $this->formPenilaianProdev($request);
            case 'form-editset':
                return $this->formPenilaianEditorSetter($request);
            case 'form-mpemasaran':
                return $this->formPenilaianMPemasaran($request);
            case 'form-dpemasaran':
                return $this->formPenilaianDPemasaran($request);
            case 'form-mpenerbitan':
                return $this->formPenilaianPenerbitan($request);
            case 'form-direksi':
                return $this->formPenilaianDireksi($request);
            default:
                return abort(500);
        }
    }

    protected function formPenilaianProdev($request)
    {
        $request->validate([
            'naskah_id' => 'required'
        ]);

        $naskah = DB::table('penerbitan_naskah as pn')
                    ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                    ->whereNull('pn.deleted_at')
                    ->where('pn.id', $request->input('naskah_id'))
                    ->select(DB::raw('pn.id, pn.jalur_buku, pn.pic_prodev, pns.tgl_pn_prodev,
                        pns.tgl_pn_direksi'))
                    ->first();
        if (is_null($naskah)) { abort(404); }

        if ($naskah->jalur_buku=='Reguler' OR $naskah->jalur_buku=='MoU-Reguler') {
            if (Gate::allows('do_update', 'naskah-pn-prodev') AND $naskah->pic_prodev == auth()->id()) {
                if (is_null($naskah->tgl_pn_prodev)) { // Add form prodev
                    $kbuku = DB::table('penerbitan_m_kelompok_buku')->get();
                    return view('penerbitan.naskah.page.tab-prodev', [
                        'form' => 'add',
                        'naskah' => $naskah,
                        'kbuku' => $kbuku,
                    ]);

                } elseif (is_null($naskah->tgl_pn_direksi)) { // Edit form prodev
                    $pn_prodev = DB::table('penerbitan_pn_prodev')->where('naskah_id', $naskah->id)->first();
                    $fileProdev = DB::table('penerbitan_naskah_files')->where('naskah_id', $naskah->id)
                                    ->where('kategori', 'File Tambahan Naskah Prodev')->first();
                    $kbuku = DB::table('penerbitan_m_kelompok_buku')->get();
                    return view('penerbitan.naskah.page.tab-prodev', [
                        'form' => 'edit',
                        'naskah' => $naskah,
                        'pn_prodev' => $pn_prodev,
                        'kbuku' => $kbuku,
                        'fileProdev' => $fileProdev
                    ]);

                } else {
                    $pn_prodev = DB::table('penerbitan_pn_prodev')->where('naskah_id', $naskah->id)->first();
                    $fileProdev = DB::table('penerbitan_naskah_files')->where('naskah_id', $naskah->id)
                                    ->where('kategori', 'File Tambahan Naskah Prodev')->first();
                    $kbuku = DB::table('penerbitan_m_kelompok_buku')->get();
                    return view('penerbitan.naskah.page.tab-prodev', [
                        'form' => 'view',
                        'naskah' => $naskah,
                        'pn_prodev' => $pn_prodev,
                        'kbuku' => $kbuku,
                        'fileProdev' => $fileProdev
                    ]);

                }
            } else {
                if (is_null($naskah->tgl_pn_prodev)) {
                    return '<h5>Prodev belum membuat penilaian.</h5>';
                } else {
                    $pn_prodev = DB::table('penerbitan_pn_prodev')->where('naskah_id', $naskah->id)->first();
                    $fileProdev = DB::table('penerbitan_naskah_files')->where('naskah_id', $naskah->id)
                                    ->where('kategori', 'File Tambahan Naskah Prodev')->first();
                    $kbuku = DB::table('penerbitan_m_kelompok_buku')->get();
                    return view('penerbitan.naskah.page.tab-prodev', [
                        'form' => 'view',
                        'naskah' => $naskah,
                        'pn_prodev' => $pn_prodev,
                        'kbuku' => $kbuku,
                        'fileProdev' => $fileProdev
                    ]);
                }
            }
        } else {
            return '<h5>Naskah ('.$naskah->jalur_buku.') tidak dinilai Prodev.</h5>';
        }
    }

    protected function formPenilaianEditorSetter($request)
    {
        $request->validate([
            'naskah_id' => 'required'
        ]);

        $naskah = DB::table('penerbitan_naskah as pn')->whereNull('pn.deleted_at')
                        ->where('pn.id', $request->input('naskah_id'))
                        ->first();
        if (is_null($naskah)) { abort(404); }

        if ($naskah->jalur_buku=='Pro Literasi') {
            if (Gate::allows('do_update', 'naskah-pn-editor') or Gate::allows('do_update', 'naskah-pn-setter')) {
                $userActive = Gate::allows('do_update', 'naskah-pn-editor')?'Editor':'Setter';
                $pn_editset = DB::table('penerbitan_pn_editor_setter')->where('naskah_id', $naskah->id)->first();
                if (is_null($pn_editset)) { // Add form EditSet
                    $pnEdit = false;
                    return view('penerbitan.naskah.page.tab-editset', [
                        'form' => 'add-edit',
                        'naskah' => $naskah,
                        'userActive' => $userActive,
                        'pnEdit' => $pnEdit
                    ]);

                } elseif (!is_null($pn_editset) AND $naskah->selesai_penilaian < 1) { // Edit form EditSet
                    $pnEdit = true;
                    return view('penerbitan.naskah.page.tab-editset', [
                        'form' => 'add-edit',
                        'naskah' => $naskah,
                        'pn_editset' => $pn_editset,
                        'userActive' => $userActive,
                        'pnEdit' => $pnEdit
                    ]);

                } else {
                    return view('penerbitan.naskah.page.tab-editset', [
                        'form' => 'view',
                        'pn_editset' => $pn_editset,
                    ]);
                }
            } else {
                if (is_null($naskah->penilaian_editset)) {
                    return '<h5>Editor/Setter belum membauat penilaian.</h5>';

                } else {
                    $pn_editset = DB::table('penerbitan_pn_editor_setter')->where('naskah_id', $naskah->id)->first();
                    return view('penerbitan.naskah.page.tab-editset', [
                        'form' => 'view',
                        'pn_editset' => $pn_editset,
                    ]);

                }
            }
        } else {
            return '<h5>Naskah ('.$naskah->jalur_buku.') tidak dinilai Editor/Setter.</h5>';
        }
    }

    protected function formPenilaianMPemasaran($request)
    {
        $request->validate([
            'naskah_id' => 'required'
        ]);

        $naskah = DB::table('penerbitan_naskah as pn')
                        ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                        ->whereNull('pn.deleted_at')
                        ->where('pn.id', $request->input('naskah_id'))
                        ->select(DB::raw('pn.id, pn.jalur_buku, pns.tgl_pn_m_pemasaran, pns.tgl_pn_direksi'))
                        ->first();
        $pilar = DB::table('penerbitan_pn_mm')->where('keyword', 'pilar')
                    ->get();
        if (is_null($naskah)) { abort(404); }

        if ($naskah->jalur_buku=='Reguler' OR $naskah->jalur_buku=='MoU-Reguler') {
            if (Gate::allows('do_update', 'naskah-pn-mpemasaran')) {
                $pn_pemasaran = DB::table('penerbitan_pn_pemasaran')
                                ->where('naskah_id', $naskah->id)
                                ->where('created_by', auth()->id())
                                ->first();
                if (is_null($pn_pemasaran)) {
                    return view('penerbitan.naskah.page.tab-mpemasaran', [
                        'form' => 'add',
                        'naskah' => $naskah,
                        'pilar' => $pilar
                    ]);

                } elseif (is_null($naskah->tgl_pn_direksi) AND !is_null($pn_pemasaran)) {
                    return view('penerbitan.naskah.page.tab-mpemasaran', [
                        'form' => 'edit',
                        'naskah' => $naskah,
                        'pn_pemasaran' => $pn_pemasaran,
                        'pilar' => $pilar
                    ]);

                } else {
                    $pn_pemasaran = DB::table('penerbitan_pn_pemasaran')
                        ->where('naskah_id', $naskah->id)
                        ->where('pic', 'M')->get();
                    return view('penerbitan.naskah.page.tab-mpemasaran', [
                        'form' => 'view',
                        'naskah' => $naskah,
                        'pn_pemasaran' => $pn_pemasaran,
                        'pilar' => $pilar
                    ]);
                }
            } else {
                $pn_pemasaran = DB::table('penerbitan_pn_pemasaran as pnp')
                                    ->join('users as u', 'pnp.created_by', '=', 'u.id')
                                    ->where('pnp.naskah_id', $naskah->id)
                                    ->where('pnp.pic', 'M')
                                    ->select('pnp.*', 'u.nama as nama_manager')
                                    ->get();
                if (count($pn_pemasaran)<1) {
                    return '<h5>Pemasaran belum membuat penilaian.</h5>';
                } else {
                    return view('penerbitan.naskah.page.tab-mpemasaran', [
                        'form' => 'view',
                        'naskah' => $naskah,
                        'pn_pemasaran' => $pn_pemasaran,
                        'pilar' => $pilar
                    ]);
                }

            }
        } else {
            return '<h5>Naskah ('.$naskah->jalur_buku.') tidak dinilai Pemasaran.</h5>';
        }
    }

    protected function formPenilaianDPemasaran($request)
    {
        $request->validate([
            'naskah_id' => 'required'
        ]);

        $naskah = DB::table('penerbitan_naskah as pn')
                    ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                    ->whereNull('pn.deleted_at')
                    ->where('pn.id', $request->input('naskah_id'))
                    ->select(DB::raw('pn.id, pn.jalur_buku, pns.tgl_pn_d_pemasaran, pns.tgl_pn_direksi'))
                    ->first();
        if (is_null($naskah)) { abort(404); }
        if ($naskah->jalur_buku=='Reguler' OR $naskah->jalur_buku=='MoU-Reguler') {
            if (Gate::allows('do_update', 'naskah-pn-dpemasaran')) {
                $pn_pemasaran = DB::table('penerbitan_pn_pemasaran')
                                    ->where('naskah_id', $naskah->id)
                                    ->get();
                $pn_dpemasaran = collect($pn_pemasaran)->where('created_by', auth()->id())->first();

                if (is_null($naskah->tgl_pn_d_pemasaran) AND is_null($pn_dpemasaran)) {
                    $pn_mpemasaran = collect($pn_pemasaran)->where('pic', 'M')->all();
                    if(count($pn_mpemasaran) < 2) {
                        return '<h5>Manager Pemasaran belum membuat penilaian (Min 2 Penilaian).</h5>';
                    }

                    return view('penerbitan.naskah.page.tab-dpemasaran', [
                        'form' => 'add',
                        'naskah' => $naskah,
                    ]);

                } elseif (is_null($naskah->tgl_pn_direksi) AND !is_null($pn_dpemasaran)) {
                    return view('penerbitan.naskah.page.tab-dpemasaran', [
                        'form' => 'edit',
                        'naskah' => $naskah,
                        'pn_pemasaran' => $pn_dpemasaran,
                    ]);

                } else {
                    return view('penerbitan.naskah.page.tab-dpemasaran', [
                        'form' => 'view',
                        'naskah' => $naskah,
                        'pn_pemasaran' => $pn_dpemasaran,
                    ]);
                }
            } else {
                if (is_null($naskah->tgl_pn_d_pemasaran)) {
                    return '<h5>Direktur Pemasaran belum membuat penilaian.</h5>';
                } else {
                    $pn_dpemasaran = DB::table('penerbitan_pn_pemasaran as pnp')
                                        ->join('users as u', 'pnp.created_by', '=', 'u.id')
                                        ->where('pnp.naskah_id', $naskah->id)
                                        ->where('pnp.pic', 'D')
                                        ->select('pnp.*', 'u.nama as nama_direktur')
                                        ->first();
                    return view('penerbitan.naskah.page.tab-dpemasaran', [
                        'form' => 'view',
                        'naskah' => $naskah,
                        'pn_pemasaran' => $pn_dpemasaran,
                    ]);
                }

            }
        } else {
            return '<h5>Naskah ('.$naskah->jalur_buku.') tidak dinilai Pemasaran.</h5>';
        }


    }

    protected function formPenilaianPenerbitan($request)
    {
        $request->validate([
            'naskah_id' => 'required'
        ]);

        $naskah = DB::table('penerbitan_naskah as pn')
                        ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                        ->whereNull('pn.deleted_at')
                        ->where('pn.id', $request->input('naskah_id'))
                        ->select(DB::raw('pn.id, pn.jalur_buku, pns.tgl_pn_prodev, pns.tgl_pn_m_penerbitan,
                            pns.tgl_pn_direksi'))
                        ->first();
        if (is_null($naskah)) { abort(404); }

        if ($naskah->jalur_buku=='Reguler' OR $naskah->jalur_buku=='MoU-Reguler' OR $naskah->jalur_buku=='SMK/NonSMK') {
            if (Gate::allows('do_update', 'naskah-pn-mpenerbitan')) {
                if (is_null($naskah->tgl_pn_m_penerbitan)) {
                    if (is_null($naskah->tgl_pn_prodev)) {
                        return '<h5>Prodev belum mengisi penilaian (Menunggu penilaian prodev)</h5>';
                    }

                    return view('penerbitan.naskah.page.tab-penerbitan', [
                        'form' => 'add',
                        'naskah' => $naskah
                    ]);

                } elseif (is_null($naskah->tgl_pn_direksi)) {
                    $pn_penerbitan = DB::table('penerbitan_pn_penerbitan')->where('naskah_id', $naskah->id)->first();
                    return view('penerbitan.naskah.page.tab-penerbitan', [
                        'form' => 'edit',
                        'naskah' => $naskah,
                        'pn_penerbitan' => $pn_penerbitan,
                    ]);

                } else {
                    $pn_penerbitan = DB::table('penerbitan_pn_penerbitan')->where('naskah_id', $naskah->id)->first();
                    return view('penerbitan.naskah.page.tab-penerbitan', [
                        'form' => 'view',
                        'naskah' => $naskah,
                        'pn_penerbitan' => $pn_penerbitan,
                    ]);

                }
            } else {
                if (is_null($naskah->tgl_pn_m_penerbitan)) {
                    return '<h5>Penerbitan belum membauat penilaian.</h5>';
                } else {
                    $pn_penerbitan = DB::table('penerbitan_pn_penerbitan')->where('naskah_id', $naskah->id)->first();
                    return view('penerbitan.naskah.page.tab-penerbitan', [
                        'form' => 'view',
                        'naskah' => $naskah,
                        'pn_penerbitan' => $pn_penerbitan,
                    ]);

                }
            }
        } else {
            return '<h5>Naskah ('.$naskah->jalur_buku.') tidak dinilai Penerbitan.</h5>';
        }
    }

    protected function formPenilaianDireksi($request)
    {
        $request->validate([
            'naskah_id' => 'required'
        ]);

        $naskah = DB::table('penerbitan_naskah as pn')
                    ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                    ->whereNull('pn.deleted_at')
                    ->where('pn.id', $request->input('naskah_id'))
                    ->select(DB::raw('pn.id, pn.jalur_buku, pns.tgl_pn_m_penerbitan, pns.tgl_pn_d_pemasaran,
                        pns.tgl_pn_direksi'))
                    ->first();
        if (is_null($naskah)) { abort(404); }

        if ($naskah->jalur_buku=='Reguler' OR $naskah->jalur_buku=='MoU-Reguler') {
            if (Gate::allows('do_update', 'naskah-pn-direksi')) {
                if (!is_null($naskah->tgl_pn_d_pemasaran) AND !is_null($naskah->tgl_pn_m_penerbitan)) {
                    if (is_null($naskah->tgl_pn_direksi)) { // Add form direksi
                        return view('penerbitan.naskah.page.tab-direksi', [
                            'form' => 'add',
                            'naskah' => $naskah,
                        ]);

                    } else {
                        $pn_direksi = DB::table('penerbitan_pn_direksi')->where('naskah_id', $naskah->id)->first();
                        return view('penerbitan.naskah.page.tab-direksi', [
                            'form' => 'view',
                            'naskah' => $naskah,
                            'pn_direksi' => $pn_direksi
                        ]);
                    }
                } else {
                    return '<h5>Direksi belum bisa mengisi penilaian.</h5>';
                }

            } else {
                if (is_null($naskah->tgl_pn_direksi)) {
                    return '<h5>Direksi belum membuat penilaian.</h5>';
                } else {
                    $pn_direksi = DB::table('penerbitan_pn_direksi')->where('naskah_id', $naskah->id)->first();
                    return view('penerbitan.naskah.page.tab-direksi', [
                        'form' => 'view',
                        'naskah' => $naskah,
                        'pn_direksi' => $pn_direksi
                    ]);
                }
            }

        } else {
            return '<h5>Naskah ('.$naskah->jalur_buku.') tidak dinilai Direksi.</h5>';
        }
    }

    protected function penilaianProdev($request)
    {
        $request->validate([
            'pn1_sistematika' => 'required',
            'pn1_nilai_keilmuan' => 'required',
            'pn1_kelompok_buku_id' => 'required',
            'pn1_skala_penilaian' => 'required',
            'pn1_saran' => 'required',
        ], [
            'required' => 'This field is requried'
        ]);

        DB::beginTransaction();
        try {
            if ($request->input('pn1_form')=='Add') {
                if($request->file('pn1_file_tambahan')) {
                    $fileN = explode('/', $request->file('pn1_file_tambahan')
                                ->store('penerbitan/naskah/'.$request->input('pn1_naskah_id')));
                    DB::table('penerbitan_naskah_files')->insert([
                        'id' => Str::uuid()->getHex(),
                        'naskah_id' => $request->input('pn1_naskah_id'),
                        'kategori' => 'File Tambahan Naskah Prodev',
                        'file' => end($fileN)
                    ]);
                }
                DB::table('penerbitan_pn_prodev')->insert([
                    'id' => Str::uuid()->getHex(),
                    'naskah_id' => $request->input('pn1_naskah_id'),
                    'sistematika' => $request->input('pn1_sistematika'),
                    'nilai_keilmuan' => $request->input('pn1_nilai_keilmuan'),
                    'kelompok_buku_id' => $request->input('pn1_kelompok_buku_id'),
                    'isi_materi' => $request->input('pn1_isi_materi'),
                    'sasaran_keilmuan' => $request->input('pn1_sasaran_keilmuan'),
                    'sasaran_pasar' => $request->input('pn1_sasaran_pasar'),
                    'sumber_dana_pasar' => $request->input('pn1_sumber_dana_pasar'),
                    'skala_penilaian' => $request->input('pn1_skala_penilaian'),
                    'saran' => $request->input('pn1_saran'),
                    'potensi' => $request->input('pn1_potensi'),
                    'created_by' => auth()->id(),
                ]);
                DB::table('penerbitan_pn_stts')->where('naskah_id',$request->input('pn1_naskah_id'))
                    ->update([
                        'tgl_pn_prodev' => date('Y-m-d H:i:s')
                    ]);
                $notif = DB::table('notif')->whereNull('expired')->where('permission_id', 'ebca07da8aad42c4aee304e3a6b81001') // Notif Prodev
                            ->where('form_id', $request->input('pn1_naskah_id'))->first();
                if (!is_null($notif)) {
                    DB::table('notif')->where('id', $notif->id)->update(['expired' => date('Y-m-d H:i:s')]);
                    DB::table('notif_detail')->where('notif_id', $notif->id)->where('user_id', auth()->id())
                        ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                }

            } else {
                if($request->file('pn1_file_tambahan')) {
                    $fileN = explode('/', $request->file('pn1_file_tambahan')
                                ->store('penerbitan/naskah/'.$request->input('pn1_naskah_id')));
                    $oldFile = DB::table('penerbitan_naskah_files')->where('naskah_id', $request->input('pn1_naskah_id'))
                                ->where('kategori', 'File Tambahan Naskah Prodev')->first();
                    if($oldFile) {
                        Storage::delete('penerbitan/naskah/'.$request->input('pn1_naskah_id').'/'.
                            $oldFile->file);
                        DB::table('penerbitan_naskah_files')->where('id', $oldFile->id)->update([
                            'file' => end($fileN)
                        ]);
                    } else {
                        DB::table('penerbitan_naskah_files')->insert([
                            'id' => Str::uuid()->getHex(),
                            'naskah_id' => $request->input('pn1_naskah_id'),
                            'kategori' => 'File Tambahan Naskah Prodev',
                            'file' => end($fileN)
                        ]);
                    }
                }
                DB::table('penerbitan_pn_prodev')->where('id', $request->input('pn1_id'))->update([
                    'sistematika' => $request->input('pn1_sistematika'),
                    'nilai_keilmuan' => $request->input('pn1_nilai_keilmuan'),
                    'kelompok_buku_id' => $request->input('pn1_kelompok_buku_id'),
                    'isi_materi' => $request->input('pn1_isi_materi'),
                    'sasaran_keilmuan' => $request->input('pn1_sasaran_keilmuan'),
                    'sasaran_pasar' => $request->input('pn1_sasaran_pasar'),
                    'sumber_dana_pasar' => $request->input('pn1_sumber_dana_pasar'),
                    'skala_penilaian' => $request->input('pn1_skala_penilaian'),
                    'saran' => $request->input('pn1_saran'),
                    'potensi' => $request->input('pn1_potensi'),
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            DB::commit();
            return;
        } catch(\Exception $e) {
            DB::rollback();
            return abort(500, $e->getMessage());
        }
    }

    protected function penilaianEditSet($request)
    {
        if ($request->input('pn2_pic') == 'Editor') {
            $request->validate([
                'pn2_penilaian_editor_umum' => 'required',
                'pn2_penilaian_bahasa' => 'required',
                'pn2_penilaian_sistematika' => 'required',
                'pn2_penilaian_konsistensi' => 'required',
                'pn2_catatan_bahasa' => 'required',
                'pn2_catatan_sistematika' => 'required',
                'pn2_catatan_konsistensi' => 'required',
                'pn2_perlu_proses_edit' => 'required',
                'pn2_proses_editor' => 'required',
            ], [
                'required' => 'This field is requried'
            ]);

            if ($request->input('pn2_form')=='Add') {
                DB::table('penerbitan_pn_editor_setter')->insert([
                    'id' => Str::uuid()->getHex(),
                    'naskah_id' => $request->input('pn2_naskah_id'),
                    'penilaian_editor_umum' => $request->input('pn2_penilaian_editor_umum'),
                    'penilaian_bahasa' => $request->input('pn2_penilaian_bahasa'),
                    'catatan_bahasa' => $request->input('pn2_catatan_bahasa'),
                    'penilaian_sistematika' => $request->input('pn2_penilaian_sistematika'),
                    'catatan_sistematika' => $request->input('pn2_catatan_sistematika'),
                    'penilaian_konsistensi' => $request->input('pn2_penilaian_konsistensi'),
                    'catatan_konsistensi' => $request->input('pn2_catatan_konsistensi'),
                    'perlu_proses_edit' => $request->input('pn2_perlu_proses_edit'),
                    'proses_editor' => $request->input('pn2_proses_editor'),
                    'penilai_editing' => auth()->id(),
                    'editing_created_at' => date('Y-m-d H:i:s')
                ]);

                DB::table('notif')->whereNull('expired')->where('permission_id', '5d793b19c75046b9a4d75d067e8e33b2')
                    ->where('form_id', $request->input('pn2_naskah_id'))->update(['expired' => date('Y-m-d H:i:s')]);

            } else {
                DB::table('penerbitan_pn_editor_setter')->where('id', $request->input('pn2_id'))->update([
                    'penilaian_editor_umum' => $request->input('pn2_penilaian_editor_umum'),
                    'penilaian_bahasa' => $request->input('pn2_penilaian_bahasa'),
                    'catatan_bahasa' => $request->input('pn2_catatan_bahasa'),
                    'penilaian_sistematika' => $request->input('pn2_penilaian_sistematika'),
                    'catatan_sistematika' => $request->input('pn2_catatan_sistematika'),
                    'penilaian_konsistensi' => $request->input('pn2_penilaian_konsistensi'),
                    'catatan_konsistensi' => $request->input('pn2_catatan_konsistensi'),
                    'perlu_proses_edit' => $request->input('pn2_perlu_proses_edit'),
                    'proses_editor' => $request->input('pn2_proses_editor'),
                    'penilai_editing' => auth()->id(),
                    'editing_updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            return;
        } elseif ($request->input('pn2_pic') == 'Setter') {
            $request->validate([
                'pn2_perlu_proses_setting' => 'required',
                'pn2_proses_setting' => 'required',
            ], [
                'required' => 'This field is requried'
            ]);

            try {
                DB::table('penerbitan_pn_editor_setter')
                    ->where('id', $request->input('pn2_id'))->update([
                    'perlu_proses_setting' => $request->input('pn2_perlu_proses_setting'),
                    'proses_setting' => $request->input('pn2_proses_setting'),
                    'penilai_setting' => auth()->id(),
                    'setting_created_at' => date('Y-m-d H:i:s')
                ]);

                DB::table('penerbitan_naskah')->where('id', $request->input('pn2_naskah_id'))
                    ->update([
                        'penilaian_editset' => '1',
                        'selesai_penilaian' => '1',
                    ]);

                DB::table('notif')->whereNull('expired')->where('permission_id', '33c3711d787d416082c0519356547b0c')
                    ->where('form_id', $request->input('pn2_naskah_id'))->update(['expired' => date('Y-m-d H:i:s')]);

                DB::commit();
                return;
            } catch(\Exception $e) {
                DB::rollback();
                return abort(500);
            }
        } else { return abort(500);}
    }

    protected function penilaianMPemasaran($request)
    {
        $request->validate([
            'pn3_prospek_pasar' => 'required',
            'pn3_potensi_dana' => 'required',
            'pn3_dstb' => 'required',
            'pn3_pilar' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if ($request->input('pn3_form')=='Add') {
                DB::table('penerbitan_pn_pemasaran')->insert([
                    'id' => Str::uuid()->getHex(),
                    'naskah_id' => $request->input('pn3_naskah_id'),
                    'pic' => 'M',
                    'prospek_pasar' => $request->input('pn3_prospek_pasar'),
                    'potensi_dana' => $request->input('pn3_potensi_dana'),
                    'ds_tb' => json_encode($request->input('pn3_dstb')),
                    'pilar' => json_encode($request->input('pn3_pilar')),
                    'created_by' => auth()->id(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $pemasaran = DB::table('penerbitan_pn_pemasaran')->where('naskah_id', $request->input('pn3_naskah_id'))
                                ->where('pic', 'M')->get();
                if(count($pemasaran) > 1) {
                    DB::table('penerbitan_pn_stts')->where('naskah_id', $request->input('pn3_naskah_id'))->update([
                        'tgl_pn_m_pemasaran' => date('Y-m-d H:i:s')
                    ]);
                    DB::table('notif')->whereNull('expired')->where('permission_id', 'a213b689b8274f4dbe19b3fb24d66840')
                        ->where('form_id', $request->input('pn3_naskah_id'))->update(['expired' => date('Y-m-d H:i:s')]);
                }

                // $pn = DB::table('penerbitan_pn_penerbitan')->where('naskah_id', $request->input('pn3_naskah_id'))->first();
                // if (!is_null($pn)) {
                //     DB::table('notif')->insert([
                //         'id' => Str::uuid()->getHex(),
                //         'section' => 'Penerbitan',
                //         'type' => 'Penilaian Naskah',
                //         'permission_id' => '8791f143a90e42e2a4d1d0d6b1254bad', // Direksi
                //         'form_id' => $request->input('pn3_naskah_id'), ]);
                // }

            } else {
                DB::table('penerbitan_pn_pemasaran')->where('id', $request->input('pn3_id'))
                    ->where('created_by', auth()->id())
                    ->update([
                        'prospek_pasar' => $request->input('pn3_prospek_pasar'),
                        'potensi_dana' => $request->input('pn3_potensi_dana'),
                        'ds_tb' => json_encode($request->input('pn3_dstb')),
                        'pilar' => json_encode($request->input('pn3_pilar')),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }

            DB::commit();
            return;
        } catch(\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }

    protected function penilaianDPemasaran($request)
    {
        $request->validate([
            'pn6_prospek_pasar' => 'required',
            'pn6_potensi_dana' => 'required',
            'pn6_dstb' => 'required',
            'pn6_pilar' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if ($request->input('pn6_form')=='Add') {
                DB::table('penerbitan_pn_pemasaran')->insert([
                    'id' => Str::uuid()->getHex(),
                    'naskah_id' => $request->input('pn6_naskah_id'),
                    'pic' => 'D',
                    'prospek_pasar' => $request->input('pn6_prospek_pasar'),
                    'potensi_dana' => $request->input('pn6_potensi_dana'),
                    'ds_tb' => $request->input('pn6_dstb'),
                    'pilar' => $request->input('pn6_pilar'),
                    'created_by' => auth()->id(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                DB::table('penerbitan_pn_stts')->where('naskah_id', $request->input('pn6_naskah_id'))->update([
                    'tgl_pn_d_pemasaran' => date('Y-m-d H:i:s')
                ]);

                $pn = DB::table('penerbitan_pn_penerbitan')->where('naskah_id', $request->input('pn6_naskah_id'))->first();
                if (!is_null($pn)) {
                    DB::table('notif')->insert([
                        'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => '8791f143a90e42e2a4d1d0d6b1254bad', // Direksi
                        'form_id' => $request->input('pn6_naskah_id'), ]);
                }

            } else {
                DB::table('penerbitan_pn_pemasaran')->where('id', $request->input('pn6_id'))
                    ->where('created_by', auth()->id())
                    ->update([
                        'prospek_pasar' => $request->input('pn6_prospek_pasar'),
                        'potensi_dana' => $request->input('pn6_potensi_dana'),
                        'ds_tb' => $request->input('pn6_dstb'),
                        'pilar' => $request->input('pn6_pilar'),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }

            DB::commit();
            return;
        } catch(\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }

    protected function penilaianPenerbitan($request)
    {
        $request->validate([
            'pn4_saran' => 'required',
        ]);

        DB::beginTransaction();
        try {
            if ($request->input('pn4_form')=='Add') {
                DB::table('penerbitan_pn_penerbitan')->insert([
                    'id' => Str::uuid()->getHex(),
                    'naskah_id' => $request->input('pn4_naskah_id'),
                    'penilaian_umum' => $request->input('pn4_penilaian_umum'),
                    'saran' => $request->input('pn4_saran'),
                    'potensi' => $request->input('pn4_potensi'),
                    'catatan' => $request->input('pn4_catatan'),
                    'created_by' => auth()->id()
                ]);

                DB::table('penerbitan_pn_stts')->where('naskah_id', $request->input('pn4_naskah_id'))
                    ->update([
                        'tgl_pn_m_penerbitan' => date('Y-m-d H:i:s')
                    ]);
                DB::table('notif')->whereNull('expired')->where('permission_id', '12b852d92d284ab5a654c26e8856fffd')
                    ->where('form_id', $request->input('pn4_naskah_id'))->update([
                        'expired' => date('Y-m-d H:i:s')
                    ]);

            } else {
                DB::table('penerbitan_pn_penerbitan')->where('id', $request->input('pn4_id'))
                    ->update([
                        'penilaian_umum' => $request->input('pn4_penilaian_umum'),
                        'saran' => $request->input('pn4_saran'),
                        'potensi' => $request->input('pn4_potensi'),
                        'catatan' => $request->input('pn4_catatan'),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }

            $pn = DB::table('penerbitan_pn_stts')->where('naskah_id', $request->input('pn4_naskah_id'))
                        ->whereNotNull('tgl_pn_m_pemasaran', )->first();
            if (!is_null($pn)) {
                DB::table('notif')->insert([
                    'id' => Str::uuid()->getHex(),
                    'section' => 'Penerbitan',
                    'type' => 'Penilaian Naskah',
                    'permission_id' => '8791f143a90e42e2a4d1d0d6b1254bad', // Direksi
                    'form_id' => $request->input('pn4_naskah_id'), ]);
            }

            DB::commit();
            return;
        } catch(\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }

    protected function penilaianDireksi($request)
    {
        $request->validate([
            'pn5_keputusan' => 'required',
            'pn5_judul_final' => 'required',
            'pn5_sub_judul_final' => 'required',
        ]);

        DB::beginTransaction();
        try {
            DB::table('penerbitan_naskah')->where('id', $request->input('pn5_naskah_id'))
                ->update([
                    'penilaian_direksi' => '1',
                    'selesai_penilaian' => '1'
                ]);

            DB::table('penerbitan_pn_direksi')->insert([
                'id' => Str::uuid()->getHex(),
                'naskah_id' => $request->input('pn5_naskah_id'),
                'judul_final' => $request->input('pn5_judul_final'),
                'sub_judul_final' => $request->input('pn5_sub_judul_final'),
                'keputusan_final' => $request->input('pn5_keputusan'),
                'catatan' => $request->input('pn5_catatan'),
                'created_by' => auth()->id()
            ]);
            DB::table('penerbitan_pn_stts')->where('naskah_id', $request->input('pn5_naskah_id'))
                    ->update([
                        'tgl_pn_direksi' => date('Y-m-d H:i:s'),
                        'tgl_pn_selesai' => date('Y-m-d H:i:s')
                    ]);
            DB::table('notif')->whereNull('expired')->where('permission_id', '8791f143a90e42e2a4d1d0d6b1254bad')
                ->where('form_id', $request->input('pn5_naskah_id'))->update(['expired' => date('Y-m-d H:i:s')]);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return abort(500);
        }
        return;
    }
}
