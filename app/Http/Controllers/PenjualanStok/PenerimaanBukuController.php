<?php

namespace App\Http\Controllers\PenjualanStok;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Events\{PenjualanStokEvent,convertNumberToRoman,ProduksiEvent};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Gate};

class PenerimaanBukuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pj_st_penerimaan as sp')
                ->join('proses_produksi_cetak as ppc', 'sp.produksi_id', '=', 'ppc.id')
                ->join('order_cetak as oc', 'oc.id', '=', 'ppc.order_cetak_id')
                ->join('deskripsi_turun_cetak as dtc', 'dtc.id', '=', 'oc.deskripsi_turun_cetak_id')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                    $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                        ->whereNull('kb.deleted_at');
                })
                ->orderBy('oc.kode_order', 'asc')
                ->select(
                    'sp.*',
                    'ppc.naskah_dari_divisi',
                    'oc.kode_order',
                    'oc.status_cetak',
                    'oc.tahun_terbit',
                    'oc.tgl_permintaan_jadi',
                    'oc.jumlah_cetak',
                    'oc.ukuran_jilid_binding',
                    'oc.jenis_cover',
                    'oc.keterangan',
                    'oc.posisi_layout',
                    'oc.keterangan',
                    'oc.dami',
                    'oc.buku_jadi',
                    'ps.edisi_cetak',
                    'ps.pengajuan_harga',
                    'df.kertas_isi',
                    'dp.judul_final',
                    'dp.naskah_id',
                    'dp.format_buku',
                    'dp.imprint',
                    'dp.jml_hal_perkiraan',
                    'dc.finishing_cover',
                    'dc.warna',
                    'pn.id as naskah_id',
                    'pn.kode',
                    'kb.nama'
                )
                ->get();
            if ($request->isMethod('GET')) {
                switch ($request->request_type) {
                    case 'table-index':
                        return $this->datatableIndex($data);
                        break;
                    case 'show-track-timeline':
                        return $this->trackTimeline($request);
                        break;
                    case 'show-modal-pengiriman':
                        return $this->showModalPengiriman($request);
                        break;
                }
            } else {
                switch ($request->request_type) {
                    case 'terima-buku':
                        return $this->penerimaanBuku($request);
                        break;
                }
            }
        }
        return view('penjualan_stok.gudang.penerimaan_buku_andi.index', [
            'title' => 'Penerimaan Buku Andi'
        ]);
    }
    protected function datatableIndex($data)
    {
        $update = Gate::allows('do_update', 'pic-data-produksi');
        return DataTables::of($data)
            ->addColumn('kode_order', function ($data) {
                return $data->kode_order;
            })
            ->addColumn('kode', function ($data) {
                return $data->kode;
            })
            ->addColumn('naskah_dari_divisi', function ($data) {
                return $data->naskah_dari_divisi;
            })
            ->addColumn('judul_final', function ($data) {
                return $data->judul_final;
            })
            ->addColumn('status_cetak', function ($data) {
                switch ($data->status_cetak) {
                    case 1:
                        $res = 'Buku Baru';
                        break;
                    case 2:
                        $res = 'Cetak Ulang Revisi';
                        break;
                    case 3:
                        $res = 'Cetak Ulang';
                        break;
                    default:
                        $res = '-';
                        break;
                }
                return $res;
            })
            ->addColumn('penulis', function ($data) {
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
            })
            ->addColumn('edisi_cetak', function ($data) {
                $roman = event(new convertNumberToRoman($data->edisi_cetak));
                $edisiCetak = implode('', $roman) . '/' . $data->edisi_cetak;
                return $edisiCetak . '/' . $data->tahun_terbit;
            })
            ->addColumn('imprint', function ($data) {
                $res = DB::table('imprint')->where('id', $data->imprint)->whereNull('deleted_at')->first()->nama ?? '-';
                return $res;
            })
            ->addColumn('buku_jadi', function ($data) {
                return $data->buku_jadi;
            })
            ->addColumn('cek_pengiriman', function ($data) {
                $dataTrack = DB::table('proses_produksi_track')
                    ->where('produksi_id', $data->produksi_id)
                    ->where('proses_tahap', 'Kirim Gudang')
                    ->first();
                $statusColor = $dataTrack->status == 'sedang dalam proses'?'warning':'success';
                $res = '<button class="btn btn-sm btn-light btn-icon position-relative" data-track_id="' . $dataTrack->id . '"
                data-judulfinal="'.$data->judul_final.'" data-status="'.$dataTrack->status.'" data-naskah_id="'.$data->naskah_id.'"
                data-produksi_id="'.$dataTrack->produksi_id.'" data-proses_tahap="'.$dataTrack->proses_tahap.'"
                data-statuscolor="'.$statusColor.'" data-toggle="modal" data-target="#modalPenerimaanBuku" data-backdrop="static">
                <i class="fas fa-truck-loading"></i> Pengiriman
                <span class="position-absolute translate-middle p-1 bg-'.$statusColor.' border border-light rounded-circle" style="top:0;right:0">
                </span>
                </button>';
                return $res;
            })
            ->addColumn('action', function ($data) use ($update) {
                $btn = '<a href="' . url('produksi/proses/cetak/detail?no=' . $data->kode_order . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                if ($update) {
                    $btn .= '<a href="' . url('produksi/proses/cetak/edit?no=' . $data->kode_order . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                }
                return $btn;
            })
            ->rawColumns([
                'kode_order',
                'kode',
                'naskah_dari_divisi',
                'judul_final',
                'status_cetak',
                'penulis',
                'edisi_cetak',
                'imprint',
                'cek_pengiriman',
                'action'
            ])
            ->make(true);
    }
    protected function trackTimeline($request)
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
    protected function showModalPengiriman($request)
    {
        try {
            $content = '';
            $track_id = $request->track_id;
            $data = DB::table('proses_produksi_track_riwayat')->where('track_id', $track_id)->get();
            $totalDiterima = DB::table('proses_produksi_track_riwayat')
                ->whereNotNull('tgl_diterima')
                ->where('track_id', $track_id)
                ->orderBy('created_at', 'asc')
                ->select(DB::raw('IFNULL(SUM(jml_dikirim),0) as total_diterima'))
                ->get();
            $content .= '<div class="form-group">
                        <label for="historyKirim">Riwayat Kirim</label>
                            <div class="scroll-riwayat">
                            <table class="table table-striped" style="width:100%" id="tableRiwayatKirim">
                            <thead style="position: sticky;top:0">
                              <tr>
                                <th scope="col" style="background: #eee;">No</th>
                                <th scope="col" style="background: #eee;">Tanggal Kirim</th>
                                <th scope="col" style="background: #eee;">Otorisasi Oleh</th>
                                <th scope="col" style="background: #eee;">Tanggal Diterima</th>
                                <th scope="col" style="background: #eee;">Penerima</th>
                                <th scope="col" style="background: #eee;">Jumlah Kirim</th>
                                <th scope="col" style="background: #eee;">Action</th>
                              </tr>
                            </thead>
                            <tbody>';
            $totalKirim = NULL;
            foreach ($data as $i => $kg) {
                $i++;
                if (!is_null($kg->tgl_diterima)) {
                    $btnAction = '<span class="badge badge-light">No action</span>';
                } else {
                    $btnAction = '<button type="button" class="btn-block btn btn-sm btn-outline-warning btn-icon mr-1 mt-1"
                    id="btnTerimaBuku" data-id="' . $kg->id . '" data-jml_diterima="'.$kg->jml_dikirim.'">
                                    Terima</button>';
                }
                $kg = (object)collect($kg)->map(function ($item, $key) {
                    switch ($key) {
                        case 'users_id':
                            return DB::table('users')->where('id', $item)->first()->nama;
                            break;
                        case 'diterima_oleh':
                            return is_null($item) ? '-' : DB::table('users')->where('id', $item)->first()->nama;
                            break;
                        case 'tgl_diterima':
                            return is_null($item) ? '<small class="badge badge-danger">menunggu</small>' : Carbon::parse($item)->format('d-m-Y H:i:s');
                            break;
                        case 'created_at':
                            return Carbon::parse($item)->format('d-m-Y H:i:s');
                            break;
                        default:
                            return is_null($item) ? '-' : $item;
                            break;
                    }
                })->all();
                $content .= '<tr id="index_' . $kg->id . '">
                                  <td id="row_num' . $i . '">' . $i . '<input type="hidden" name="task_number[]" value=' . $i . '></td>
                                  <td>' . $kg->created_at . '</td>
                                  <td>' . $kg->users_id . '</td>
                                  <td id="indexTglTerima' . $kg->id . '">' . $kg->tgl_diterima . '</td>
                                  <td id="indexDiterimaOleh' . $kg->id . '">' . $kg->diterima_oleh . '</td>
                                  <td id="indexJmlKirim' . $kg->id . '">' . $kg->jml_dikirim . ' eks</td>
                                  <td id="indexAction'.$kg->id.'">' . $btnAction . '</td>
                                </tr>';
                $totalKirim += $kg->jml_dikirim;
            }
            $content .= '</tbody>
            </table>
            </div>
            </div>
            <div class="alert d-flex justify-content-between" style="background: #141517;
            background: -webkit-linear-gradient(to right, #6777ef, #141517);
            background: linear-gradient(to right, #6777ef, #141517);
            " role="alert">
                <div class="col-auto">
                    <span class="bullet"></span><span>Total Kirim</span><br>
                    <span class="bullet"></span><span>Total Diterima  <a href="javascript:void(0)" class="text-warning" tabindex="0" role="button"
                    data-toggle="popover" data-trigger="focus" title="Informasi"
                    data-content="Total diterima adalah total yang diterima dan diotorisasi oleh departemen Penjualan & Stok.">
                    <abbr title="">
                    <i class="fas fa-info-circle me-3"></i>
                    </abbr>
                    </a></span>
                </div>
                <div class="col-auto">
                    <span class="text-center" id="totDikirim">' . $totalKirim . ' eks</span><br>
                    <span class="text-center" id="totDiterima">' . $totalDiterima[0]->total_diterima . ' eks</span>
                </div>
            </div>';
            return [
                'content' => $content
            ];
        } catch (\Exception $e) {
            return abort(500, $e->getMessage());
        }
    }
    protected function penerimaanBuku($request)
    {
        try {
            $id = $request->id;
            $jml_diterima = $request->jml_diterima;
            $naskah_id = $request->naskah_id;
            $produksi_id = $request->produksi_id;
            $catatan = $request->catatan;
            $tgl = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $session = auth()->id();
            $data = DB::table('pj_st_andi')->where('naskah_id',$naskah_id);
            if ($data->exists()) {
                //! Jumlah stok ditotal
                $jml_diterima = $data->first()->total_stok + $jml_diterima;
                $update = [
                    'params' => 'Update Jumlah Stok Andi',
                    'id' => $data->first()->id,
                    'total_stok' => $jml_diterima
                ];
                event(new PenjualanStokEvent($update));
            } else {
                $naskah = DB::table('deskripsi_turun_cetak as dtc')
                ->join('pracetak_setter as ps', 'ps.id', '=', 'dtc.pracetak_setter_id')
                ->join('deskripsi_final as df', 'df.id', '=', 'ps.deskripsi_final_id')
                ->join('pracetak_cover as pc', 'pc.id', '=', 'dtc.pracetak_cover_id')
                ->join('deskripsi_cover as dc', 'dc.id', '=', 'pc.deskripsi_cover_id')
                ->join('deskripsi_produk as dp', 'dp.id', '=', 'dc.deskripsi_produk_id')
                ->join('penerbitan_naskah as pn', 'pn.id', '=', 'dp.naskah_id')
                ->join('penerbitan_m_kelompok_buku as kb', function ($q) {
                    $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                        ->whereNull('kb.deleted_at');
                })
                ->join('penerbitan_m_s_kelompok_buku as skb', function ($q) {
                    $q->on('pn.sub_kelompok_buku_id', '=', 'skb.id')
                        ->whereNull('skb.deleted_at');
                })
                ->where('pn.id',$naskah_id)
                ->select(
                    'dtc.*',
                    'pn.id as naskah_id',
                    'kb.kode as kode_kb',
                    'skb.kode as kode_skb'
                )
                ->first();
                //Format SKU 00-000-0000-000000
                $tipeOrder = Str::padLeft($naskah->tipe_order, 2, '0');
                //! Insert Stok Andi
                $insert = [
                    'params' => 'Insert Stok Andi',
                    'id' => Uuid::uuid4()->toString(),
                    'naskah_id' => $naskah_id,
                    'kode_sku' => self::generateKodeSku($tipeOrder, $naskah->kode_kb,$naskah->kode_skb),
                    'total_stok' => $jml_diterima
                ];
                event(new PenjualanStokEvent($insert));
            }
            //!Update Riwayat Pengiriman
            $update = [
                'params' => 'Update Penerimaan Buku',
                'id' => $id,
                'catatan' => $catatan,
                'tgl_diterima' => $tgl,
                'diterima_oleh' => $session
            ];
            event(new ProduksiEvent($update));
            return response()->json([
                'status' => 'success',
                'message' => 'Stok bertambah!',
                'data' => [
                    'tgl_diterima' => Carbon::parse($tgl)->format('d-m-Y H:i:s'),
                    'penerima' => DB::table('users')->where('id',$session)->first()->nama,
                    'action' => '<span class="badge badge-light">No action</span>',
                    'total_diterima' => $jml_diterima
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    private function generateKodeSku($tipeOrder,$kode_kb,$kode_skb)
    {
        $kodeKb = (int)substr($kode_kb, -3);
        $kodeSKB = $kode_skb ?? '0000';
        $kodeSKU = $tipeOrder.'-'.$kodeKb.'-'.$kodeSKB;
        $checkLast = DB::table('pj_st_andi')
        ->whereRaw('SUBSTRING_INDEX(kode_sku, '-', 3) == '.$kodeSKU.'');
        if ($checkLast->exists()) {
            $sortNumber = substr($checkLast->first()->kode_sku,-6);
            $kodeFix =  $kodeSKU.'-'.sprintf("%06d",$sortNumber+1);
        } else {
            $kodeFix = $kodeSKU.'-000001';
        }
        return $kodeFix;
    }
}
