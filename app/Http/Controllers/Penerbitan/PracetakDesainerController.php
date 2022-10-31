<?php

namespace App\Http\Controllers\Penerbitan;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class PracetakDesainerController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
                $data = DB::table('pracetak_cover as pc')
                    ->join('deskripsi_cover as dc','dc.id','=','pc.deskripsi_cover_id')
                    ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                    ->join('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
                    ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                    ->whereNull('df.deleted_at')
                    ->select(
                        'pc.*',
                        'pn.kode',
                        'pn.pic_prodev',
                        'pn.jalur_buku',
                        'dp.naskah_id',
                        'dp.imprint',
                        'dp.judul_final'
                        )
                    ->orderBy('pc.tgl_masuk_cover','ASC')
                    ->get();
                $update = Gate::allows('do_create', 'ubah-atau-buat-des-final');

                return DataTables::of($data)
                        // ->addIndexColumn()
                        ->addColumn('kode', function($data) {
                            return $data->kode;
                        })
                        ->addColumn('judul_final', function($data) {
                            return $data->judul_final;
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
                        ->addColumn('jalur_buku', function($data) {
                            if (!is_null($data->jalur_buku)) {
                                $res = $data->jalur_buku;
                            } else {
                                $res = '-';
                            }
                            return $res;
                        })
                        ->addColumn('desainer', function($data) {
                            if (!is_null($data->desainer)) {
                                $res = '';
                                foreach (json_decode($data->desainer,true) as $q) {
                                    $res .= '<span class="d-block">-&nbsp;'.DB::table('users')->where('id',$q)->whereNull('deleted_at')->first()->nama.'</span>';
                                }
                            } else {
                                $res = '-';
                            }
                            return $res;
                        })
                        ->addColumn('tgl_masuk_cover', function($data) {
                            return Carbon::parse($data->tgl_masuk_cover)->translatedFormat('l d M Y H:i');

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
                                $date = '<button type="button" class="btn btn-sm btn-dark btn-icon mr-1 btn-history" data-id="'.$data->id.'" data-judulfinal="'.$data->judul_final.'"><i class="fas fa-history"></i>&nbsp;History</button>';
                                return $date;
                            }
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('penerbitan/pracetak/designer/detail?desc='.$data->id.'&kode='.$data->kode).'"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                if ($data->status == 'Selesai') {
                                    if (Gate::allows('do_approval','approval-deskripsi-produk')) {
                                        $btn .= '<a href="'.url('penerbitan/pracetak/designer/edit?desc='.$data->id.'&kode='.$data->kode).'"
                                        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                        <div><i class="fas fa-edit"></i></div></a>';
                                    }
                                } else {
                                    if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b') || (Gate::allows('do_approval','approval-deskripsi-produk'))) {
                                        $btn .= '<a href="'.url('penerbitan/pracetak/designer/edit?desc='.$data->id.'&kode='.$data->kode).'"
                                        class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                        <div><i class="fas fa-edit"></i></div></a>';
                                    }
                                }
                            }
                            if (Gate::allows('do_approval','action-progress-des-final')) {
                                if ((auth()->id() == $data->pic_prodev) || (auth()->id() == 'be8d42fa88a14406ac201974963d9c1b')) {
                                    switch ($data->status) {
                                        case 'Antrian':
                                            $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-icon mr-1 mt-1 btn-status-prades" style="background:#34395E;color:white" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_final.'" data-toggle="modal" data-target="#md_UpdateStatusPracetakDesainer" title="Update Status">
                                            <div>'.$data->status.'</div></a>';
                                            break;
                                        case 'Pending':
                                            $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-danger btn-icon mr-1 mt-1 btn-status-prades" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_final.'" data-toggle="modal" data-target="#md_UpdateStatusPracetakDesainer" title="Update Status">
                                            <div>'.$data->status.'</div></a>';
                                            break;
                                        case 'Proses':
                                            $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-success btn-icon mr-1 mt-1 btn-status-prades" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_final.'" data-toggle="modal" data-target="#md_UpdateStatusPracetakDesainer" title="Update Status">
                                            <div>'.$data->status.'</div></a>';
                                            break;
                                        case 'Selesai':
                                            $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-light btn-icon mr-1 mt-1 btn-status-prades" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_final.'" data-toggle="modal" data-target="#md_UpdateStatusPracetakDesainer" title="Update Status">
                                            <div>'.$data->status.'</div></a>';
                                            break;
                                        case 'Revisi':
                                            $btn .= '<a href="javascript:void(0)" class="d-block btn btn-sm btn-info btn-icon mr-1 mt-1 btn-status-prades" data-id="'.$data->id.'" data-kode="'.$data->kode.'" data-judul="'.$data->judul_final.'" data-toggle="modal" data-target="#md_UpdateStatusPracetakDesainer" title="Update Status">
                                            <div>'.$data->status.'</div></a>';
                                            break;
                                        default:
                                            return abort(410);
                                            break;
                                    }
                                }
                            } else {
                                switch ($data->status) {
                                    case 'Antrian':
                                        $btn .= '<span class="d-block badge badge-secondary mr-1 mt-1">'.$data->status.'</span>';
                                        break;
                                    case 'Pending':
                                        $btn .= '<span class="d-block badge badge-danger mr-1 mt-1">'.$data->status.'</span>';
                                        break;
                                    case 'Proses':
                                        $btn .= '<span class="d-block badge badge-success mr-1 mt-1">'.$data->status.'</span>';
                                        break;
                                    case 'Selesai':
                                        $btn .= '<span class="d-block badge badge-light mr-1 mt-1">'.$data->status.'</span>';
                                        break;
                                    case 'Revisi':
                                        $btn .= '<span class="d-block badge badge-info mr-1 mt-1">'.$data->status.'</span>';
                                        break;
                                    default:
                                        return abort(410);
                                        break;
                                }
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'kode',
                            'judul_asli',
                            'penulis',
                            'jalur_buku',
                            'imprint',
                            'judul_final',
                            'tgl_deskripsi',
                            'pic_prodev',
                            'history',
                            'action'
                            ])
                        ->make(true);

        }
        $data = DB::table('pracetak_cover as pc')
        ->join('deskripsi_cover as dc','dc.id','=','pc.deskripsi_cover_id')
        ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
        ->join('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
        ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
        ->whereNull('df.deleted_at')
        ->select(
            'pc.*',
            'pn.kode',
            'pn.pic_prodev',
            'pn.jalur_buku',
            'dp.naskah_id',
            'dp.imprint',
            'dp.judul_final'
            )
        ->orderBy('pc.tgl_masuk_cover','ASC')
        ->get();
                    //Isi Warna Enum
            $type = DB::select(DB::raw("SHOW COLUMNS FROM deskripsi_final WHERE Field = 'status'"))[0]->Type;
            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
            $statusProgress = explode("','", $matches[1]);

        return view('penerbitan.des_final.index', [
            'title' => 'Deskripsi Final',
            'status_progress' => Arr::sort($statusProgress),
            'count' => count($data)
        ]);
    }
}
