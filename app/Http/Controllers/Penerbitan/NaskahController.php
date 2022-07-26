<?php

namespace App\Http\Controllers\Penerbitan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};
use Illuminate\Support\Str;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class NaskahController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-naskah') {
                $data = DB::table('penerbitan_naskah as pn')
                    ->join('penerbitan_m_kelompok_buku as mkb', 'pn.kelompok_buku_id', '=', 'mkb.id')
                    ->join('penerbitan_pn_stts as stts', 'pn.id', '=', 'stts.naskah_id')
                    ->whereNull('pn.deleted_at')
                    ->leftJoin('timeline as t', 'pn.id', 't.naskah_id')
                    ->select('pn.id',
                    'pn.kode',
                    'pn.judul_asli',
                    'mkb.nama as kelompok_buku',
                    'pn.jalur_buku',
                    'pn.tanggal_masuk_naskah',
                    'stts.tgl_pn_selesai',
                    'stts.tgl_pn_editor',
                    'stts.tgl_pn_setter',
                    'stts.tgl_pn_prodev',
                    'stts.tgl_pn_m_pemasaran',
                    'stts.tgl_pn_m_penerbitan',
                    'stts.tgl_pn_d_pemasaran',
                    'stts.tgl_pn_prodev',
                    'stts.tgl_pn_direksi')
                    ->get();
                $update = Gate::allows('do_update', 'ubah-data-naskah');

                return Datatables::of($data)
                        ->addColumn('stts_penilaian', function($data) {
                            if(is_null($data->tgl_pn_selesai)) {
                                $btn = '<span class="badge badge-dark">Belum Selesai</span>';
                            }else {
                                $btn = Carbon::createFromFormat('Y-m-d h:i:s',$data->tgl_pn_selesai)->format('d F Y');
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_editor_setter', function($data) {
                            if(is_null($data->tgl_pn_editor)) {
                                if ($data->jalur_buku=='Pro Literasi') {
                                    $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                }
                                else{
                                    $btn = '<span class="badge badge-warning">Tidak perlu penilaian</span>';
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_m_penerbitan', function($data) {
                            if(is_null($data->tgl_pn_m_penerbitan)) {
                                if($data->jalur_buku=='Reguler' OR $data->jalur_buku=='MoU-Reguler' OR $data->jalur_buku=='SMK/NonSMK')
                                {
                                    if(is_null($data->tgl_pn_prodev)) {
                                        $btn = "<span class='badge badge-primary'>Menunggu penilaian Prodev</span>";
                                    } 
                                    else {
                                        $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                    }
                                } else {
                                    $btn = "<span class='badge badge-warning'>Tidak perlu penilaian</span>";
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_m_pemasaran', function($data) {
                            if(is_null($data->tgl_pn_m_pemasaran)) {
                                if($data->jalur_buku=='Reguler' OR $data->jalur_buku=='MoU-Reguler') {
                                    $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                } else{
                                    $btn = "<span class='badge badge-warning'>Tidak perlu penilaian</span>";
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_d_pemasaran', function($data) {
                            if(is_null($data->tgl_pn_d_pemasaran)) {
                                if ($data->jalur_buku=='Reguler' or $data->jalur_buku=='MoU-Reguler') {
                                    $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                } else {
                                    $btn = "<span class='badge badge-warning'>Tidak perlu penilaian</span>";                                    
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_prodev', function($data) {
                            if(is_null($data->tgl_pn_prodev)) {
                                $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('penilaian_direksi', function($data) {
                            if(is_null($data->tgl_pn_direksi)) {
                                if($data->jalur_buku=='Reguler' OR $data->jalur_buku=='MoU-Reguler')
                                {
                                    $btn = "<span class='badge badge-danger'>Belum ada penilaian</span>";
                                } else {
                                    $btn = "<span class='badge badge-warning'>Tidak perlu penilaian</span>";
                                }
                            }else {
                                $btn = '<span class="badge badge-success">Sudah dinilai</span>';
                            }
                            return $btn;
                        })
                        ->addColumn('action', function($data) use($update) {
                            $btn = '<a href="'.url('penerbitan/naskah/melihat-naskah/'.$data->id).'"
                                    class="btn btn-sm btn-primary btn-icon mr-1">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('penerbitan/naskah/mengubah-naskah/'.$data->id).'"
                                    class="btn btn-sm btn-warning btn-icon mr-1">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns(['penilaian_editor_setter','penilaian_m_penerbitan','penilaian_m_pemasaran','penilaian_d_pemasaran','penilaian_prodev','penilaian_direksi', 'stts_penilaian', 'action'])
                        ->make(true);
            } elseif($request->input('request_') === 'selectPenulis') {
                $data = DB::table('penerbitan_penulis')
                        ->whereNull('deleted_at')
                        ->where('nama', 'like', '%'.$request->input('term').'%')
                        ->get();

                return $data;
            } elseif($request->input('request_') === 'selectedPenulis') {
                $data = DB::table('penerbitan_penulis')
                            ->where('id', $request->input('id'))
                            ->first();
                return $data;
            } elseif($request->input('request_') === 'getKodeNaskah') {
                return self::generateId();
            } elseif($request->input('request_') === 'getPICTimeline') {
                $data = DB::table('users')->whereNull('deleted_at')->where('status', '1')
                            ->where('nama', 'like', '%'.$request->input('term').'%')->select('id', 'nama')
                            ->get();
                return $data;
            }
        }

        return view('penerbitan.naskah.index', [
            'title' => 'Naskah Penerbitan'
        ]);
    }

    public function createNaskah(Request $request) {
        if($request->ajax()) {
            if($request->isMethod('POST')) {
                $request->validate([
                    'add_judul_asli' => 'required',
                    'add_kode' => 'required|unique:penerbitan_naskah,kode',
                    'add_kelompok_buku' => 'required',
                    'add_jalur_buku' => 'required',
                    'add_tanggal_masuk_naskah' => 'required',
                    'add_tentang_penulis' => 'required',
                    'add_hard_copy' => 'required',
                    'add_soft_copy' => 'required',
                    'add_cdqr_code' => 'required',
                    'add_pic_prodev' => 'required',
                    'add_file_naskah' => 'required|mimes:pdf',
                    'add_file_tambahan_naskah' => 'mimes:rar,zip',
                    'add_penulis' => 'required'
                ], [
                    'required' => 'This field is requried'
                ]);

                DB::beginTransaction();

                try {
                    $idN = Str::uuid()->getHex();

                    foreach($request->input('add_penulis') as $p) {
                        $daftarPenulis[] = [
                            'naskah_id' => $idN,
                            'penulis_id' => $p
                        ];
                    }
                    $fileN = explode('/', $request->file('add_file_naskah')->store('penerbitan/naskah/'.$idN));
                    $fileNaskah[] = [
                            'id' => Str::uuid()->getHex(),
                            'naskah_id' => $idN,
                            'kategori' => 'File Naskah Asli',
                            'file' => end($fileN)
                        ];
                    if($request->file('add_file_tambahan_naskah')) {
                        $fileN = explode('/', $request->file('add_file_tambahan_naskah')->store('penerbitan/naskah/'.$idN));
                        $fileNaskah[] = [
                            'id' => Str::uuid()->getHex(),
                            'naskah_id' => $idN,
                            'kategori' => 'File Tambahan Naskah',
                            'file' => end($fileN)
                        ];
                    }

                    DB::table('penerbitan_naskah_penulis')->insert($daftarPenulis);
                    DB::table('penerbitan_naskah_files')->insert($fileNaskah);
                    $penilaian = $this->alurPenilaian($request->input('add_jalur_buku'), 'create-notif-from-naskah', [
                        'id_prodev' => $request->input('add_pic_prodev'),
                        'form_id' => $idN
                    ]);

                    DB::table('penerbitan_naskah')->insert([
                        'id' => $idN,
                        'kode' => $request->input('add_kode'),
                        'judul_asli' => $request->input('add_judul_asli'),
                        'tanggal_masuk_naskah' => Carbon::createFromFormat('d F Y', $request->input('add_tanggal_masuk_naskah'))
                            ->format('Y-m-d'),
                        'email' => $request->input('add_email'),
                        'kelompok_buku_id' => $request->input('add_kelompok_buku'),
                        'jalur_buku' => $request->input('add_jalur_buku'),
                        'tentang_penulis' => $request->input('add_tentang_penulis'),
                        'hard_copy' => $request->input('add_hard_copy'),
                        'soft_copy' => $request->input('add_soft_copy'),
                        'cdqr_code' => $request->input('add_cdqr_code'),
                        'keterangan' => $request->input('add_keterangan'),
                        'pic_prodev' => $request->input('add_pic_prodev'),
                        'penilaian_naskah' => ($penilaian['penilaian_naskah']?'1':'0'),
                        'created_by' => auth()->id()
                    ]);

                    DB::table('penerbitan_pn_stts')->insert([
                        'id' => Str::uuid()->getHex(),
                        'naskah_id' => $idN,
                        'tgl_naskah_masuk' => Carbon::createFromFormat('d F Y', $request->input('add_tanggal_masuk_naskah'))
                            ->format('Y-m-d'),
                        'tgl_pn_selesai' => ($penilaian['selesai_penilaian']>0)?date('Y-m-d H:i:s'):null
                    ]);

                    DB::commit();
                    return;
                } catch(\Exception $e) {
                    Storage::deleteDirectory('penerbitan/naskah/'.$idN);
                    DB::rollback();
                    return abort(500, $e->getMessage());
                }
            } else {
                return abort('404');
            }
        }

        $kbuku = DB::table('penerbitan_m_kelompok_buku')
                    ->get();
        $user = DB::table('users')->get();

        return view('penerbitan.naskah.create-naskah', [
            'kode' => self::generateId(),
            'kbuku' => $kbuku,
            'user' => $user
        ]);
    }

    public function updateNaskah(Request $request) {
        $naskah = DB::table('penerbitan_naskah as pn')
                    ->join('penerbitan_pn_stts as pns', 'pn.id', '=', 'pns.naskah_id')
                    ->whereNull('pn.deleted_at')
                    ->where('pn.id', $request->id)
                    ->select(DB::raw('pn.*, pns.tgl_pn_prodev'))
                    ->first();

        if(is_null($naskah)) { return abort(404); }
        $naskah = (object)collect($naskah)->map(function($item, $key) {
            if($key == 'tanggal_masuk_naskah' AND $item != '') {
                return Carbon::createFromFormat('Y-m-d', $item)->format('d F Y');
            }
            return $item;
        })->all();

        $fileNaskah = DB::table('penerbitan_naskah_files')->where('naskah_id', $naskah->id)
                        ->get();

        if($request->ajax()) {
            if($request->isMethod('GET')) {
                $penulis = DB::table('penerbitan_naskah_penulis as pnp')
                            ->join('penerbitan_penulis as pp', 'pnp.penulis_id', '=', 'pp.id')
                            ->where('naskah_id', $naskah->id)
                            ->select('pp.id', 'pp.nama')
                            ->get();

                return ['naskah' => $naskah, 'penulis' => $penulis];
            }elseif($request->isMethod('POST')) {
                $request->validate([
                    'edit_judul_asli' => 'required',
                    'edit_kode' => 'required|unique:penerbitan_naskah,kode,'.$request->id,
                    'edit_kelompok_buku' => 'required',
                    'edit_jalur_buku' => 'required',
                    'edit_tanggal_masuk_naskah' => 'required',
                    'edit_tentang_penulis' => 'required',
                    'edit_hard_copy' => 'required',
                    'edit_soft_copy' => 'required',
                    'edit_cdqr_code' => 'required',
                    'edit_pic_prodev' => 'required',
                    'edit_file_naskah' => 'mimes:pdf',
                    'edit_file_tambahan_naskah' => 'mimes:rar,zip',
                    'edit_penulis' => 'required'
                ], [
                    'required' => 'This field is requried'
                ]);

                DB::beginTransaction();
                try {
                    if(!is_null($request->file('edit_file_naskah'))){
                        $fNaskah = explode('/', $request->file('edit_file_naskah')->store('penerbitan/naskah/'.$naskah->id));
                        $fNaskah = end($fNaskah);

                        DB::table('penerbitan_naskah_files')
                            ->where('naskah_id', $naskah->id)
                            ->where('kategori', 'File Naskah Asli')
                            ->update([
                                'file' => $fNaskah,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                    }

                    if(!is_null($request->file('edit_file_tambahan_naskah'))){
                        $ftNaskah = explode('/', $request->file('edit_file_tambahan_naskah')->store('penerbitan/naskah/'.$naskah->id));
                        $ftNaskah = end($ftNaskah);

                        if(!is_null($request->file('edit_file_tambahan_naskah'))){
                            DB::table('penerbitan_naskah_files')->insert([
                                'id' => Str::uuid()->getHex(),
                                'naskah_id' => $naskah->id,
                                'kategori' => 'File Tambahan Naskah',
                                'file' => $ftNaskah,
                            ]);
                        } else {
                            DB::table('penerbitan_naskah_files')
                                ->where('naskah_id', $naskah->id)
                                ->where('kategori', 'File Tambahan Naskah')
                                ->update([
                                    'file' => $ftNaskah,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);
                        }
                    }

                    foreach($request->input('edit_penulis') as $p) {
                        $daftarPenulis[] = [
                            'naskah_id' => $naskah->id,
                            'penulis_id' => $p
                        ];
                    }

                    DB::table('penerbitan_naskah_penulis')
                        ->where('naskah_id', $naskah->id)->delete();
                    DB::table('penerbitan_naskah_penulis')
                        ->insert($daftarPenulis);

                    if($request->input('edit_pic_prodev')!=$naskah->pic_prodev) { // Update Notifikasi jika naskah diubah. (Hanya prodev)
                        $this->alurPenilaian($request->input('edit_jalur_buku'), 'update-notif-from-naskah', [
                            'id_prodev' => $request->input('edit_pic_prodev'),
                            'form_id' => $naskah->id
                        ]);
                    }

                    DB::table('penerbitan_naskah')
                        ->where('id', $naskah->id)->update([
                        'judul_asli' => $request->input('edit_judul_asli'),
                        'tanggal_masuk_naskah' => Carbon::createFromFormat('d F Y', $request->input('edit_tanggal_masuk_naskah'))
                            ->format('Y-m-d'),
                        'email' => $request->input('edit_email'),
                        'kelompok_buku_id' => $request->input('edit_kelompok_buku'),
                        'jalur_buku' => $request->input('edit_jalur_buku'),
                        'tentang_penulis' => $request->input('edit_tentang_penulis'),
                        'hard_copy' => $request->input('edit_hard_copy'),
                        'soft_copy' => $request->input('edit_soft_copy'),
                        'cdqr_code' => $request->input('edit_cdqr_code'),
                        'keterangan' => $request->input('edit_keterangan'),
                        'pic_prodev' => $request->input('edit_pic_prodev'),
                        'keterangan' => $request->input('edit_keterangan'),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    DB::commit();
                    if(!is_null($request->file('edit_file_naskah'))){
                        Storage::delete('penerbitan/naskah/'.$naskah->id.'/'.
                            $fileNaskah->where('kategori', 'File Naskah Asli')->pluck('file')->first());
                    }
                    if(!is_null($request->file('edit_file_tambahan_naskah'))){
                        Storage::delete('penerbitan/naskah/'.$naskah->id.'/'.
                            $fileNaskah->where('kategori', 'File Tambahan Naskah')->pluck('file')->first());
                    }
                    return;
                } catch(\Exception $e) {
                    if(!is_null($request->file('edit_file_naskah'))){
                        Storage::delete('penerbitan/naskah/'.$naskah->id.'/'.$fNaskah);
                    }
                    if(!is_null($request->file('edit_file_tambahan_naskah'))){
                        Storage::delete('penerbitan/naskah/'.$naskah->id.'/'.$ftNaskah);
                    }
                    DB::rollback();
                    return abort(500, $e->getMessage());
                }
            }else { return abort(404); }
        }

        $kbuku = DB::table('penerbitan_m_kelompok_buku')->get();
        $user = DB::table('users')->get();

        return view('penerbitan.naskah.update-naskah', [
            'kbuku' => $kbuku,
            'user' => $user
        ]);
    }

    public function viewNaskah(Request $request) {
        $naskah = DB::table('penerbitan_naskah as pn')
                    ->leftJoin('penerbitan_naskah_files as pnf', 'pn.id', '=', 'pnf.naskah_id')
                    ->leftJoin('penerbitan_m_kelompok_buku as pmkb', 'pn.kelompok_buku_id', '=', 'pmkb.id')
                    ->leftJoin('users as u', 'pn.pic_prodev', '=', 'u.id')
                    ->whereNull('pn.deleted_at')
                    ->where('pn.id', $request->id)
                    ->select(DB::raw('pn.*, pmkb.nama as kelompok_buku, u.nama as npic_prodev'))
                    ->first();
        if(is_null($naskah)) { abort(404); }

        $naskah = (object)collect($naskah)->map(function($item, $key) {
            switch($key) {
                case 'tanggal_masuk_naskah':
                    return $item!=''?Carbon::createFromFormat('Y-m-d', $item)->format('d F Y'):'-';
                case 'tentang_penulis':
                    return $item?'Ya':'Tidak';
                case 'hard_copy':
                    return $item?'Ya':'Tidak';
                case 'soft_copy':
                    return $item?'Ya':'Tidak';
                case 'cdqr_code':
                    return $item?'Ya':'Tidak';
                default:
                    return $item;
            }
        })->all();


        // Penilaian Naskah
        if(Gate::allows('do_update', 'naskah-pn-prodev')) {
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
        $getData = DB::table('penerbitan_naskah_files')
                        ->where('naskah_id', $naskah->id)
                        ->get();
        $fileNaskah['pdf'] = $getData->where('kategori', 'File Naskah Asli')->pluck('file')->first();
        $fileNaskah['rar'] = $getData->where('kategori', 'File Tambahan Naskah')->pluck('file')->first();

        return view('penerbitan.naskah.detail-naskah', [
            'naskah' => $naskah, 'penulis' => $penulis, 'startPn' => $startPn,
            'fileNaskah' => (object)$fileNaskah
        ]);
    }

    public static function generateId() {
        $last = DB::table('penerbitan_naskah')
                    ->select('kode')
                    ->where('created_at', '>', strtotime(date('Y-m-d').'00:00:01'))
                    ->orderBy('created_at', 'desc')
                    ->first();

        if(is_null($last)) {
            $id = 'NA'.date('Ymd').'001';
        } else {
            $id = (int)substr($last->kode, -3);
            $id = 'NA'.date('Ymd').sprintf('%03s', $id+1);
        }
        return $id;
    }

    protected function alurPenilaian($jalbuk, $action=null, $data=null){
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

        if($jalbuk=='Reguler' OR $jalbuk=='MoU-Reguler') {
            if($action=='create-notif-from-naskah') {
                $id_notif = Str::uuid()->getHex();
                DB::table('notif')->insert([
                    [   'id' => $id_notif,
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => 'ebca07da8aad42c4aee304e3a6b81001', // Prodev
                        'form_id' => $data['form_id'], ],
                    [   'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => 'a213b689b8274f4dbe19b3fb24d66840', // Pemasaran
                        'form_id' => $data['form_id'], ],
                    [   'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => '9beba245308543ce821efe8a3ba965e3', // M.Pemasaran
                        'form_id' => $data['form_id'], ],
                    [   'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => '12b852d92d284ab5a654c26e8856fffd', // M.Penerbitan
                        'form_id' => $data['form_id'], ],
                ]);
                DB::table('notif_detail')->insert([
                    'notif_id' => $id_notif,
                    'user_id' => $data['id_prodev']
                ]);

                return [ 'selesai_penilaian' => 0, 'penilaian_naskah' => 1 ];

            } elseif($action=='update-notif-from-naskah') {
                $notif = DB::table('notif')->whereNull('expired')->where('permission_id', 'ebca07da8aad42c4aee304e3a6b81001') // Hanya untuk prodev
                            ->where('form_id', $data['form_id'])->first();
                DB::table('notif_detail')->where('notif_id', $notif->id)
                            ->update(['seen' => '1', 'updated_at' => date('Y-m-d H:i:s')]);
                DB::table('notif_detail')->insert([
                    'notif_id' => $notif->id,
                    'user_id' => $data['id_prodev']
                ]);
            }

        } elseif($jalbuk == 'Pro Literasi') {
            if($action=='create-notif-from-naskah') {
                DB::table('notif')->insert([
                    [   'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => '5d793b19c75046b9a4d75d067e8e33b2', // Editor
                        'form_id' => $data['form_id'], ],
                    [   'id' => Str::uuid()->getHex(),
                        'section' => 'Penerbitan',
                        'type' => 'Penilaian Naskah',
                        'permission_id' => '33c3711d787d416082c0519356547b0c', // Setter
                        'form_id' => $data['form_id'], ],
                ]);

                return [ 'selesai_penilaian' => 0, 'penilaian_naskah' => 1 ];

            } elseif($action=='update-notif-from-naskah') {

            }

        } else { // Jalur Buku MoU & SMK/NonSMK
            if($action=='create-notif-from-naskah') {
                return [ 'selesai_penilaian' => 1, 'penilaian_naskah' => 0 ];

            } elseif($action=='update-notif-from-naskah') {

            }
        }
    }

    public function timelineNaskah(Request $request) {
        switch($request->cat) {
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

    protected function timeline($request) {
        if($request->input('request_')=='form') { // Load modal
            $naskah = DB::table('penerbitan_naskah as pn')->whereNull('deleted_at')
                            ->where('id', $request->input('id'))->first();
            $timeline = [];

            if($request->input('method_')=='add') {
                $arrPic = [];

            } elseif($request->input('method_')=='edit') {
                $timeline = DB::table('timeline as t')->join('timeline_detail as td', 't.id', '=', 'td.timeline_id')
                                ->where('t.naskah_id', $request->input('id'))->get();

                foreach($timeline as $t) {
                    if($t->proses == 'Proses Produksi') {
                        $produksi = Carbon::createFromFormat('Y-m-d', $t->tanggal_mulai)->format('d M Y').' - '.
                                    Carbon::createFromFormat('Y-m-d', $t->tanggal_selesai)->format('d M Y');
                    } else {
                        $penerbitan = Carbon::createFromFormat('Y-m-d', $t->tanggal_mulai)->format('d M Y').' - '.
                                    Carbon::createFromFormat('Y-m-d', $t->tanggal_selesai)->format('d M Y');
                    }
                    $tgl_buku_jadi = $t->tgl_buku_jadi;
                }

                $timeline->produksi = $produksi;
                $timeline->penerbitan = $penerbitan;
                $timeline->tgl_buku_jadi = Carbon::createFromFormat('Y-m-d', $tgl_buku_jadi)->format('d M Y');

            } else { return abort(404); }

            $naskah = (object)collect($naskah)->map(function($v, $i) {
                if($i=='tanggal_masuk_naskah') {
                    return Carbon::createFromFormat('Y-m-d', $v)->format('d M Y');
                } else {
                    return $v;
                }
            })->all();

            return view('penerbitan.naskah.page.modal-timeline', [
                'naskah' => $naskah,
                'method' => $request->input('method_'),
                'timeline' => $timeline
            ]);

        } elseif($request->input('request_')=='submit') {
            $tlId = Str::uuid()->getHex();
            $tlDet = []; $subtl = [];

            try {
                $master = DB::table('timeline_m_tb')->whereNull('deleted_at')->orderBy('order_tb')->get();
                foreach($master->where('kategori', 'PROSES')->all() as $v) {
                    $masterSubTl = DB::table('timeline_m_subtl as ms')
                                    ->join('timeline_m_tb as mt', 'ms.bagian', '=', 'mt.detail')
                                    ->where('ms.proses', $v->detail)
                                    ->whereNull('ms.deleted_at')->get();

                    if($v->detail == 'Proses Produksi') {
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
                    foreach($masterSubTl as $vv) {
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
            }catch(\Exception $e) {
                return $e->getMessage();
            }


            die();

        } else {
            abort(404);
        }
    }

    protected function subTimeline($request) {
        if($request->input('category') == 'update-subtl') {
            $id = explode('_', $request->input('id'));
            return DB::table('timeline_sub')->where('id', $id[1])
                    ->update([
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => auth()->id()
                    ]);

        } elseif($request->input('category') == 'add-subtl') {
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

    public function ajaxFromCalendar(Request $request, $cat) {
        switch($request->cat) {
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

    protected function fCalendarEvents($request) {
        $data = DB::table('penerbitan_naskah as pn')
                ->leftJoin('timeline as t', 'pn.id', '=', 't.naskah_id')
                ->select(DB::raw(' pn.id, pn.judul_asli, pn.tanggal_masuk_naskah AS tgl_naskah_masuk,
                    DATE_FORMAT(t.tgl_buku_jadi, "%Y-%m-%d") AS tgl_buku_jadi'))
                ->where(function($q) use($request) {
                    return $q->where('pn.tanggal_masuk_naskah', '>', $request->input('start'))
                                ->where('pn.tanggal_masuk_naskah', '<', $request->input('end'));
                })
                ->orWhere(function($q) use($request) {
                    return $q->where('t.tgl_buku_jadi', '>', $request->input('start'))
                                ->where('t.tgl_buku_jadi', '<', $request->input('end'));
                })->get();
        $result = [];

        foreach($data as $d) {
            $result[] = [
                'id' => $d->id,
                'title' => $d->judul_asli,
                'start' => is_null($d->tgl_buku_jadi)?$d->tgl_naskah_masuk:$d->tgl_buku_jadi,
                'classNames' => [is_null($d->tgl_buku_jadi)?'nMasuk':'nJadi']
            ];
        }

        return $result;
    }

    protected function fCalendarDetails($request) {
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
