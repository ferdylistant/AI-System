<?php

namespace App\Http\Controllers\PenjualanStok;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Events\convertNumberToRoman;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\{DB, Gate};
use Symfony\Component\Console\Input\Input;

class StokAndiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pj_st_andi as st')
                ->leftJoin('penerbitan_naskah as pn', 'pn.id', '=', 'st.naskah_id')
                ->leftJoin('deskripsi_produk as dp', 'pn.id', '=', 'dp.naskah_id')
                ->leftJoin('deskripsi_final as df', 'dp.id', '=', 'df.deskripsi_produk_id')
                ->leftJoin('pracetak_setter as ps', 'df.id', '=', 'ps.deskripsi_final_id')
                ->leftJoin('deskripsi_turun_cetak as dtc', 'df.id', '=', 'dtc.pracetak_setter_id')
                ->leftJoin('penerbitan_m_kelompok_buku as kb', function ($q) {
                    $q->on('pn.kelompok_buku_id', '=', 'kb.id')
                        ->whereNull('kb.deleted_at');
                })
                ->leftJoin('penerbitan_m_s_kelompok_buku as skb', function ($q) {
                    $q->on('pn.sub_kelompok_buku_id', '=', 'skb.id')
                        ->whereNull('skb.deleted_at');
                })
                ->orderBy('pn.kode', 'asc')
                ->select(
                    'st.*',
                    'ps.edisi_cetak',
                    'ps.pengajuan_harga',
                    'df.sub_judul_final',
                    'dp.judul_final',
                    'dp.naskah_id',
                    'dp.format_buku',
                    'dp.imprint',
                    'pn.kode',
                    'kb.nama as nama_kb',
                    'skb.nama as nama_skb',
                )
                ->get();
            if ($request->isMethod('GET')) {
                switch ($request->request_type) {
                    case 'table-index':
                        return self::datatableIndex($data);
                        break;
                    case 'show-track-timeline':
                        return self::trackTimeline($request);
                        break;
                    case 'show-modal-rack':
                        return self::showModalRack($request);
                        break;
                    case 'select-rack':
                        return self::selectRack($request);
                        break;
                }
            } else {
                switch ($request->request_type) {
                    case 'add-data-rack':
                        return self::addDataRack($request);
                        break;
                }
            }
        }
        return view('penjualan_stok.gudang.stok_andi.index', [
            'title' => 'Stok Gudang Andi'
        ]);
    }
    protected function datatableIndex($data)
    {
        $update = Gate::allows('do_update', 'pic-data-produksi');
        return DataTables::of($data)
            ->addColumn('kode_sku', function ($data) {
                return $data->kode_sku;
            })
            ->addColumn('kode', function ($data) {
                return $data->kode;
            })
            ->addColumn('judul_final', function ($data) {
                return ucfirst($data->judul_final);
            })
            ->addColumn('sub_judul_final', function ($data) {
                return $data->sub_judul_final ?? '-';
            })
            ->addColumn('kelompok_buku', function ($data) {
                return $data->nama_kb . ' <i class="fas fa-chevron-right"></i> ' . $data->nama_skb;
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
            ->addColumn('imprint', function ($data) {
                $res = DB::table('imprint')->where('id', $data->imprint)->whereNull('deleted_at')->first()->nama ?? '-';
                return $res;
            })
            ->addColumn('total_stok', function ($data) {
                return $data->total_stok;
            })
            ->addColumn('rack', function ($data) {
                return '<button class="btn btn-light" data-toggle="modal" data-judul="' . $data->judul_final . '" data-stok_id="' . $data->id . '" data-target="#modalRack" data-backdrop="static">
                <i class="fas fa-border-all"></i> Rak Buku
                </button>';
            })
            ->addColumn('action', function ($data) use ($update) {
                $btn = '<a href="' . url('produksi/proses/cetak/detail?no=' . $data->kode_sku . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-primary btn-icon mr-1" data-toggle="tooltip" title="Lihat Detail">
                                    <div><i class="fas fa-envelope-open-text"></i></div></a>';
                if ($update) {
                    $btn .= '<a href="' . url('produksi/proses/cetak/edit?no=' . $data->kode_sku . '&naskah=' . $data->kode) . '"
                                    class="d-block btn btn-sm btn-warning btn-icon mr-1 mt-1" data-toggle="tooltip" title="Edit Data">
                                    <div><i class="fas fa-edit"></i></div></a>';
                }
                return $btn;
            })
            ->rawColumns([
                'kode_sku',
                'kode',
                'judul_final',
                'sub_judul_final',
                'kelompok_buku',
                'penulis',
                'imprint',
                'total_stok',
                'rack',
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
    protected function showModalRack($request)
    {
        try {
            $stok_id = $request->stok_id;
            $dataRack = DB::table('pj_st_rack_data as rd')
                ->leftJoin('pj_st_rack_master as rm', function ($q) {
                    $q->on('rd.rack_id', '=', 'rm.id')
                        ->whereNull('rm.deleted_at');
                })
                ->leftJoin('pj_st_andi as st', 'rd.stok_id', '=', 'st.id')
                ->where('rd.stok_id', $stok_id)
                ->select('rd.*', 'rm.kode', 'rm.nama', 'st.total_stok')
                ->orderBy('rd.created_at', 'ASC');
            $contentRack = '<p class="text-danger">Belum ada stok yang diiput dalam rak.</p>';
            if ($dataRack->exists()) {
                $contentRack = self::contentRack($dataRack->get());
            }
            $contentForm = self::contentForm();


            return [
                'stok_id' => $stok_id,
                'contentForm' => $contentForm,
                'contentRack' => $contentRack
            ];
        } catch (\Exception $e) {
            return abort(500, $e->getMessage());
        }
    }
    private function contentRack($data) //!BELUM
    {
        $contentRack = '';
        $contentRack .= '<div class="scroll-riwayat">
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
        foreach ($data as $i => $kg) {
            $i++;
            if (!is_null($kg->tgl_diterima)) {
                $btnAction = '<span class="badge badge-light">No action</span>';
            } else {
                $btnAction = '<a href="javascript:void(0)"
                                    class="btn-block btn btn-sm btn-outline-warning btn-icon mr-1 mt-1" id="btnEditRiwayatKirim" data-id="' . $kg->id . '" data-dibuat="' . Carbon::parse($kg->created_at)->translatedFormat('l, d M Y - H:i:s') . '" data-toggle="modal" data-target="#modalEditRiwayatKirim">
                                    <i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)"
                                    class="btn-block btn btn-sm btn-outline-danger btn-icon mr-1 mt-1" id="btnDeleteRiwayatKirim" data-id="' . $kg->id . '" data-toggle="tooltip" title="Hapus Data">
                                    <i class="fas fa-trash"></i></a>';
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
                        return is_null($item) ? '<small class="badge badge-danger">menunggu</small>' : $item;
                        break;
                    case 'created_at':
                        return Carbon::parse($item)->format('d-m-Y H:i:s');
                        break;
                    default:
                        return is_null($item) ? '-' : $item;
                        break;
                }
            })->all();
            $contentRack .= '<tr id="index_' . $kg->id . '">
                                  <td id="row_num' . $i . '">' . $i . '<input type="hidden" name="task_number[]" value=' . $i . '></td>
                                  <td>' . $kg->created_at . '</td>
                                  <td>' . $kg->users_id . '</td>
                                  <td>' . $kg->tgl_diterima . '</td>
                                  <td>' . $kg->diterima_oleh . '</td>
                                  <td id="indexJmlKirim' . $kg->id . '">' . $kg->jml_dikirim . ' eks</td>
                                  <td>' . $btnAction . '</td>
                                </tr>';
        }
        $contentRack .= '</tbody>
            </table>
            </div>';
        return $contentRack;
    }
    private function contentForm()
    {
        $contentForm = '<div class="input-group mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text bg-light text-dark" id="">Rak</span>
        </div>
        <select id="selectRack" class="form-control" name="rak[]" required></select>
        <div class="input-group-prepend">
            <span class="input-group-text bg-light text-dark" id="">Jumlah</span>
        </div>
        <input type="text" class="form-control" name="jml_stok[]" required>
        <div class="input-group-prepend">
            <span class="input-group-text bg-light text-dark" id="">Masuk Gudang</span>
        </div>
        <input type="text" class="form-control datepicker" name="tgl_masuk_stok[]"
            placeholder="DD/MM/YYYY" required>
        <div class="input-group-prepend">
            <span class="input-group-text bg-light text-dark" id="">Oleh</span>
        </div>
        <input type="text" class="form-control" name="users_id[]" required>
        </div>';
        return $contentForm;
    }
    protected function addDataRack($request)
    {
        $stok_id = $request->stok_id;
        $rak = $request->rak;
        $jml_stok = $request->jml_stok;
        $tgl_masuk_stok = $request->tgl_masuk_stok;
        $users_id = $request->users_id;
        $author = auth()->id();
        for ($count = 0; $count < count($rak); $count++) {
            $data[] = [
                'rack_id' => $rak[$count],
                'stok_id' => $stok_id,
                'jml_stok'  => $jml_stok[$count],
                'tgl_masuk_stok'  => $tgl_masuk_stok[$count],
                'users_id'  => $users_id[$count],
                'created_by' => $author
            ];

        }
        $unique = array_unique($rak);
        if (count($unique) < count($rak)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rak tidak boleh duplikat!'
            ]);
        }
        return response()->json($data);
    }
    protected function selectRack($request)
    {
        $data = DB::table('pj_st_rack_master')
            ->whereNull('deleted_at')
            ->where('nama', 'like', '%' . $request->input('term') . '%')
            ->get();
        return response()->json($data);
    }
}
