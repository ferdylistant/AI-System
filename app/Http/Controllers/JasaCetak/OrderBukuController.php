<?php

namespace App\Http\Controllers\JasaCetak;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderBukuController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = DB::table('jasa_cetak_order_buku')
            ->orderBy('tgl_order', 'ASC')
            ->get();

            return DataTables::of($data)
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
                    'history',
                    'action'
                ])
                ->make(true);
        }
        $data = DB::table('jasa_cetak_order_buku')
            ->orderBy('tgl_order', 'ASC')
            ->get();
        //Status
        $type = DB::select(DB::raw("SHOW COLUMNS FROM jasa_cetak_order_buku WHERE Field = 'status'"))[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $statusProgress = explode("','", $matches[1]);
        return view('jasa_cetak.order_buku.index', [
            'title' => 'Jasa Cetak Order Buku',
            'status_progress' => $statusProgress,
            'count' => count($data)
        ]);
    }
}
