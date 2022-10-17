<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class DeskripsiCoverController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
                $data = DB::table('deskripsi_cover as dc')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->join('deskripsi_final as df', 'dp.id','=','df.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->whereNull('dc.deleted_at')
                    ->select(
                        'dc.*',
                        'pn.kode',
                        'pn.judul_asli',
                        'pn.pic_prodev',
                        'pn.kelompok_buku_id',
                        'dp.naskah_id',
                        'dp.judul_final',
                        'df.sub_judul_final',
                        'df.bullet'
                        )
                    ->orderBy('df.tgl_deskripsi','ASC')
                    ->get();
                $update = Gate::allows('do_create', 'ubah-atau-buat-des-final');

                return DataTables::of($data)
                        // ->addIndexColumn()
                        ->addColumn('kode', function($data) {
                            return $data->kode;
                        })
                        ->addColumn('judul_asli', function($data) {
                            return $data->judul_asli;
                        })
                        ->addColumn('penulis', function($data) {
                            // return $data->penulis;
                            $result = '';
                            $res = DB::table('penerbitan_naskah_penulis as pnp')
                            ->join('penerbitan_penulis as pp',function($q) {
                                $q->on('pnp.penulis_id','=','pp.id')
                                ->whereNull('pp.deleted_at');
                            })
                            ->where('pnp.naskah_id','=',$data->naskah_id)
                            ->select('pp.nama')
                            // ->pluck('pp.nama');
                            ->get();
                            foreach ($res as $q) {
                                $result .= '<span class="d-block">-&nbsp;'.$q->nama.'</span>';
                            }
                            return $result;
                            //  $res;
                        })
                        ->addColumn('kelompok_buku', function($data) {
                            if (!is_null($data->kelompok_buku_id)) {
                                $res = DB::table('penerbitan_m_kelompok_buku')->where('id',$data->kelompok_buku_id)->first()->nama;
                            } else {
                                $res = '-';
                            }
                            return $res;
                        })
                        ->addColumn('judul_final', function($data) {
                            if(is_null($data->judul_final)) {
                                $res = '-';
                            } else {
                                $res = $data->judul_final;
                            }
                            return $res;
                        })
                        ->addColumn('tgl_deskripsi', function($data) {
                            return Carbon::parse($data->tgl_deskripsi)->translatedFormat('d M Y');

                        })
                        ->addColumn('pic_prodev', function($data) {
                            $result = '';
                            $res = DB::table('users')->where('id',$data->pic_prodev)->whereNull('deleted_at')
                            ->select('nama')
                            ->get();
                            foreach (json_decode($res) as $q) {
                                $result .= $q->nama;
                            }
                            return $result;
                        })
                        ->addColumn('history', function ($data) {
                            $historyData = DB::table('deskripsi_final_history')->where('deskripsi_final_id',$data->id)->get();
                            if($historyData->isEmpty()) {
                                return '-';
                            } else {
                                $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="'.$data->id.'" data-judulasli="'.$data->judul_asli.'"><i class="fas fa-history"></i>&nbsp;History</button>';
                                return $date;
                            }
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('penerbitan/deskripsi/cover/detail?desc='.$data->id.'&kode='.$data->kode).'"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';

                            if($data->status != 'Terkunci'){
                                if($update) {
                                    if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                                        $btn .= '<a href="'.url('penerbitan/deskripsi/cover/edit?desc='.$data->id.'&kode='.$data->kode).'"
                                            class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                            <div><i class="fas fa-edit"></i></div></a>';
                                    }
                                }
                            }

                            if (Gate::allows('do_approval','action-progress-des-produk')) {
                                if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                                    if ($data->status == 'Terkunci') {
                                        $btn .= '<span class="d-block badge badge-dark mr-1 mt-1"><i class="fas fa-lock"></i>&nbsp;'.$data->status.'</span>';
                                    } elseif ($data->status == 'Antrian'){
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-descov" style="background:#34395E;color:white" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Pending') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-descov" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Proses') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-descov" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    } elseif ($data->status == 'Selesai') {
                                        $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-light btn-icon mr-1 mt-1 btn-status-descov" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_asli.'" data-toggle="modal" data-target="#md_UpdateStatusDesFinal" title="Update Status">
                                        <div>'.$data->status.'</div></a>';
                                    }
                                }
                            } else {
                                if($data->status == 'Terkunci'){
                                    $btn .= '<span class="d-block badge badge-dark mr-1 mt-1"><i class="fas fa-lock"></i>&nbsp;'.$data->status.'</span>';
                                } elseif($data->status == 'Antrian'){
                                    $btn .= '<span class="d-block badge badge-secondary mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Pending') {
                                    $btn .= '<span class="d-block badge badge-danger mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Proses') {
                                    $btn .= '<span class="d-block badge badge-success mr-1 mt-1">'.$data->status.'</span>';
                                } elseif ($data->status == 'Selesai') {
                                    $btn .= '<span class="d-block badge badge-light mr-1 mt-1">'.$data->status.'</span>';
                                }
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'kode',
                            'judul_asli',
                            'penulis',
                            'kelompok_buku',
                            'judul_final',
                            'tgl_deskripsi',
                            'pic_prodev',
                            'history',
                            'action'
                            ])
                        ->make(true);

        }
        $data = DB::table('deskripsi_final as df')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->whereNull('df.deleted_at')
                    ->select(
                        'df.*',
                        'pn.kode',
                        'pn.judul_asli',
                        'pn.pic_prodev',
                        'dp.naskah_id',
                        'dp.imprint',
                        'dp.judul_final'
                        )
                    ->orderBy('df.tgl_deskripsi','ASC')
                    ->get();
                    //Isi Warna Enum
            $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_cover WHERE Field = 'status'"))[0]->Type;
            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
            $statusProgress = explode("','", $matches[1]);
            $filterSort = Arr::sort($statusProgress);
            // Arr::except($filterSort,['4'])
        return view('penerbitan.des_cover.index', [
            'title' => 'Deskripsi Cover',
            'status_progress' => $filterSort,
            'count' => count($data)
        ]);
    }
}
