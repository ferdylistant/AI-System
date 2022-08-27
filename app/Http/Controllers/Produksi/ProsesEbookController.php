<?php

namespace App\Http\Controllers\Produksi;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB, Storage, Gate};

class ProsesEbookController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            if($request->input('request_') === 'table-proses-produksi-ebook') {
                $data = DB::table('proses_ebook_multimedia as pem')
                    ->join('produksi_order_ebook as poe', 'poe.id', '=', 'pem.order_ebook_id')
                    ->select(
                        'pem.*',
                        'poe.kode_order',
                        'poe.judul_buku',
                        'poe.penulis',
                        'poe.edisi_cetakan',
                        'poe.platform_digital',
                        'poe.tahun_terbit',
                        'poe.created_at as tanggal_order',
                        'poe.tgl_upload',
                        'poe.jumlah_halaman',
                        'poe.keterangan',
                        )
                    ->get();
                $update = Gate::allows('do_update', 'ubah-data-multimedia');
                // $start=1;
                return DataTables::of($data)
                        // ->addColumn('no', function($no) use (&$start) {
                        //     return $start++;
                        // })
                        ->addColumn('no_order', function($data) {
                            return $data->kode_order;
                        })
                        ->addColumn('judul_buku', function($data) {
                            return $data->judul_buku;
                        })
                        // ->addColumn('penulis', function($data) {
                        //     return $data->penulis;
                        // })
                        ->addColumn('edisi_cetakan', function($data) {
                            return $data->edisi_cetakan.'/'.$data->tahun_terbit;
                        })
                        ->addColumn('platform_digital', function($data) {
                            $convert = json_decode($data->platform_digital,true);
                            $sort = sort($convert);
                            return implode(', ', $convert);
                        })
                        ->addColumn('tanggal_order', function($data) {
                            return Carbon::parse($data->tanggal_order)->translatedFormat('d F Y');
                        })
                        ->addColumn('tgl_upload', function($data) {
                            return Carbon::parse($data->tgl_upload)->translatedFormat('d F Y');
                        })
                        ->addColumn('bukti_upload', function($data)  {
                            if(is_null($data->bukti_upload)){
                                $convert ='-';
                            } else{
                                $convert = json_decode(urldecode($data->bukti_upload));
                                // $convert = '<a href="www.kreatic.id"><i class="fas fa-circle"></i>&nbsp;TITIT</a>';
                            }
                            return $convert;
                        })
                        ->addColumn('action', function($data) use ($update) {
                            $btn = '<a href="'.url('produksi/proses/ebook-multimedia/detail?kode='.$data->order_ebook_id.'&track='.$data->id).'"
                                    class="d-flex btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                            if($update) {
                                $btn .= '<a href="'.url('produksi/proses/ebook-multimedia/edit?kode='.$data->order_ebook_id.'&track='.$data->id).'"
                                    class="d-flex btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                            }
                            return $btn;
                        })
                        ->rawColumns([
                            'no_order',
                            'judul_buku',
                            'edisi_cetakan',
                            'platform_digital',
                            'tanggal_order',
                            'tgl_upload',
                            'bukti_upload',
                            'action'
                            ])
                        ->make(true);
            }
        }

        return view('produksi.proses_ebook_multimedia.index', [
            'title' => 'Proses Produksi E-Book Multimedia'
        ]);
    }
    public function detailProduksi(Request $request)
    {
        $kode = $request->get('kode');
        $track = $request->get('track');
        $data = DB::table('proses_ebook_multimedia as pem')
        ->join('produksi_order_ebook as poe', 'poe.id', '=', 'pem.order_ebook_id')
        ->where('pem.id', $track)
        ->where('pem.order_ebook_id', $kode)
        ->select(
            'pem.*',
            'poe.id as order_ebook_id',
            'poe.tipe_order',
            'poe.kode_order',
            'poe.judul_buku',
            'poe.sub_judul',
            'poe.penulis',
            'poe.platform_digital',
            'poe.edisi_cetakan',
            'poe.tahun_terbit',
            'poe.created_at as tanggal_order',
            'poe.tgl_upload',
            'poe.jumlah_halaman',
            'poe.status_buku',
            'poe.penerbit',
            'poe.imprint',
            'poe.kelompok_buku',
            'poe.spp',
            'poe.keterangan',
            )
        ->first();
        // $dt = json_decode($data->platform_digital);
        // $count = count($dt);
        // return response()->json($count);
        if(is_null($data)) {
            return redirect()->route('proses.ebook.view');
        }

        return view('produksi.proses_ebook_multimedia.detail', [
            'title' => 'Detail Data Proses Produksi Order E-Book',
            'data' => $data,
        ]);
    }
    public function updateProduksi(Request $request)
    {
        if ($request->ajax()) {
            if ($request->isMethod('POST')) {
                // $request->validate([
                //     'bukti_upload.*' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                // ], [
                //     'required' => 'This field is requried'
                // ]);
                foreach ($request->bukti_upload as $value) {
                    if(is_null($value)) continue;
                    $buktiUpload[] = urlencode($value);
                }
                DB::table('proses_ebook_multimedia')
                    ->where('id', $request->id)
                    ->update([
                    'bukti_upload' => json_encode($buktiUpload),
                    'updated_by'=> auth()->id()
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data bukti upload e-book berhasil diposting',
                    'route' => route('proses.ebook.view')
                ]);
            }
        }
        $kodeOr = $request->get('kode');
        $track = $request->get('track');
        $data = DB::table('proses_ebook_multimedia as pem')->join('produksi_order_ebook as poe', 'poe.id', '=', 'pem.order_ebook_id')
            ->where('pem.id', $track)
            ->where('pem.order_ebook_id', $kodeOr)
            ->select(
                'pem.*',
                'poe.kode_order',
                'poe.tipe_order',
                'poe.platform_digital',
                'poe.status_buku',
                'poe.created_at as tanggal_order',
                'poe.tgl_upload',
                'poe.judul_buku',
                'poe.sub_judul',
                'poe.penulis',
                'poe.penerbit',
                'poe.imprint',
                'poe.edisi_cetakan',
                'poe.tahun_terbit',
                'poe.kelompok_buku',
                'poe.jumlah_halaman',
                'poe.spp',
                'poe.keterangan',
                )
            ->first();
        $platformDigital = array(['name'=>'Moco'],['name'=>'Google Book'],['name'=>'Gramedia'],['name'=>'Esentral'],
        ['name'=>'Bahanaflik'],['name'=> 'Indopustaka']);
        return view('produksi.proses_ebook_multimedia.update', [
            'title' => 'Update Bukti Upload E-book Multimedia',
            'data' => $data,
            'platformDigital' => $platformDigital
        ]);
    }
}
