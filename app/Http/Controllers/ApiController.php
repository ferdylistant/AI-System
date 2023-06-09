<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Illuminate\Support\Facades\{Auth, DB};

class ApiController extends Controller
{
    public function checkAuth(Request $request)
    {
        try {
            $id = $request->id;
            DB::beginTransaction();
            $activity = Auth::check() ? 'online':'offline';
            DB::table('users')->where('id',$id)->update([
                'status_activity' => $activity
            ]);
            DB::commit();
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500,$e->getMessage());
        }
    }
    public function updateStatusActivity(Request $request)
    {
        try {
            $id = $request->id;
            $status = $request->status;
            DB::beginTransaction();
            if ($id == NULL) {
                if ($status != auth()->user()->status_activity) {
                    DB::table('users')->where('id',auth()->id())->update([
                        'status_activity' => 'offline'
                    ]);
                }
            } else {
                if ($status != auth()->user()->status_activity) {
                    DB::table('users')->where('id',auth()->id())->update([
                        'status_activity' => $status
                    ]);
                }
            }
            DB::commit();
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            return abort(500,$e->getMessage());
        }
    }
    public function requestAjax(Request $request)
    {
        if ($request->ajax()) {
            try {
                switch ($request->list) {
                    case 'jenis-mesin':
                        return $this->listJenisMesin($request);
                        break;
                    case 'status-cetak':
                        return $this->listStatusCetak($request);
                        break;
                    case 'get-layout':
                        return $this->getPosisiLayout($request);
                        break;
                    case 'list-dami':
                        return $this->listDami($request);
                        break;
                    case 'list-dami-data':
                        return $this->listDamiData($request);
                        break;
                }
            } catch (\Exception $e) {
                return abort(500);
            }
        } else {
            return abort(405);
        }
    }
    protected function getPosisiLayout($request)
    {
        $posisiLayout = (object)[['value' => '1', 'label' => 'Potrait'], ['value' => '2', 'label' => 'Landscape']];
        return response()->json($posisiLayout);
    }
    protected function listDami($request)
    {
        $valueLayout = $request->get('value');
        if ($valueLayout == '1') {
            $data = (object)[
                [
                    'value' => '16',
                    'label' => '16',
                ],
                [
                    'value' => '24',
                    'label' => '24',
                ],
                [
                    'value' => '32',
                    'label' => '32',
                ],
            ];
        } elseif ($valueLayout == '2') {
            $data = (object)[
                [
                    'value' => '12',
                    'label' => '12',
                ],
                [
                    'value' => '16',
                    'label' => '16',
                ],
                [
                    'value' => '32',
                    'label' => '32',
                ],
            ];
        }
        echo '<option label="Pilih"></option>';
        foreach ($data as $key => $value) {
            echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
        }
    }
    public function listFormatBuku(Request $request)
    {
        $posisi = $request->get('posisi');
        $dami = $request->get('dami');
        if ($posisi == '1' && $dami == '16') {
            $data = (object)[
                [
                    'value' => '20 x 18',
                    'label' => '20 x 18',
                ],
                [
                    'value' => '21 x 29,7',
                    'label' => '21 x 29,7',
                ],
                [
                    'value' => '16 x 25',
                    'label' => '16 x 25',
                ],
                [
                    'value' => '17,5 x 25',
                    'label' => '17,5 x 25',
                ],
                [
                    'value' => '19 x 25',
                    'label' => '19 x 25',
                ],
                [
                    'value' => '17,5 x 24,5',
                    'label' => '17,5 x 24,5',
                ],
            ];
        } elseif ($posisi == '1' && $dami == '24') {
            $data = (object)[
                [
                    'value' => '19 x 24',
                    'label' => '19 x 24',
                ],
                [
                    'value' => '19 x 23',
                    'label' => '19 x 23',
                ],
                [
                    'value' => '21 x 21',
                    'label' => '21 x 21',
                ],
                [
                    'value' => '19 x 19',
                    'label' => '19 x 19',
                ],
            ];
        } elseif ($posisi == '1' && $dami == '32') {
            $data = (object)[
                [
                    'value' => '10 x 15',
                    'label' => '10 x 15',
                ],
                [
                    'value' => '13 x 19',
                    'label' => '13 x 19',
                ],
                [
                    'value' => '12 x 19',
                    'label' => '12 x 19',
                ],
                [
                    'value' => '11 x 18',
                    'label' => '11 x 18',
                ],
                [
                    'value' => '14 x 21',
                    'label' => '14 x 21',
                ],
                [
                    'value' => '17,5 x 24,5',
                    'label' => '17,5 x 24,5',
                ],
                [
                    'value' => '16 x 23',
                    'label' => '16 x 23',
                ],
                [
                    'value' => '15 x 19',
                    'label' => '15 x 19',
                ],
                [
                    'value' => '15 x 15',
                    'label' => '15 x 15',
                ],
                [
                    'value' => '14 x 19',
                    'label' => '14 x 19',
                ],
                [
                    'value' => '16,5 x 21',
                    'label' => '16,5 x 21',
                ],
                [
                    'value' => '15 x 21',
                    'label' => '15 x 21',
                ],
                [
                    'value' => '14,5 x 21',
                    'label' => '14,5 x 21',
                ],
                [
                    'value' => '15 x 23',
                    'label' => '15 x 23',
                ],
            ];
        } elseif ($posisi == '2' && $dami == '12') {
            $data = (object)[
                [
                    'value' => '30 x 14',
                    'label' => '30 x 14',
                ]
            ];
        } elseif ($posisi == '2' && $dami == '16') {
            $data = (object)[
                [
                    'value' => '18 x 14,5',
                    'label' => '18 x 14,5',
                ],
                [
                    'value' => '25 x 20',
                    'label' => '25 x 20',
                ],
                [
                    'value' => '29,5 x 20,5',
                    'label' => '29,5 x 20,5',
                ],
                [
                    'value' => '21 x 15',
                    'label' => '21 x 15',
                ],
            ];
        } elseif ($posisi == '2' && $dami == '32') {
            $data = (object)[
                [
                    'value' => '22 x 15,5',
                    'label' => '22 x 15,5',
                ]
            ];
        }

        echo '<option label="Pilih"></option>';
        foreach ($data as $key => $value) {
            echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
        }
    }
    public function listStatusBuku(Request $request)
    {
        $statusBuku = (object)[['value' => '1', 'label' => 'Reguler'], ['value' => '2', 'label' => 'MOU']];
        if ($request->has('id')) {
            $id = $request->get('id');
            $data = DB::table('produksi_order_cetak')->where('id', $id)->first();
            echo '<option label="Pilih"></option>';
            foreach ($statusBuku as $key => $value) {
                if ($data->status_buku == $value['value']) {
                    echo '<option value="' . $value['value'] . '" selected>' . $value['label'] . '</option>';
                } else {
                    echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
                }
            }
        } else {
            return response()->json($statusBuku);
        }
    }
    public function listStatusBukuEbook(Request $request)
    {
        $statusBuku = (object)[['value' => '1', 'label' => 'Reguler'], ['value' => '2', 'label' => 'MOU']];
        if ($request->has('id')) {
            $id = $request->get('id');
            $data = DB::table('produksi_order_ebook')->where('id', $id)->first();
            echo '<option label="Pilih"></option>';
            foreach ($statusBuku as $key => $value) {
                if ($data->status_buku == $value['value']) {
                    echo '<option value="' . $value['value'] . '" selected>' . $value['label'] . '</option>';
                } else {
                    echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
                }
            }
        } else {
            return response()->json($statusBuku);
        }
    }
    public function listPilihanTerbit(Request $request)
    {
        $pilihanTerbit = (object)[['value' => '1', 'label' => 'Cetak Fisik'], ['value' => '2', 'label' =>  'Cetak Fisik + E-Book']];
        if ($request->has('id')) {
            $id = $request->get('id');
            $data = DB::table('produksi_order_cetak')->where('id', $id)->first();
            echo '<option label="Pilih"></option>';
            foreach ($pilihanTerbit as $key => $value) {
                if ($data->pilihan_terbit == $value['value']) {
                    echo '<option value="' . $value['value'] . '" selected>' . $value['label'] . '</option>';
                } else {
                    echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
                }
            }
        } else {
            return response()->json($pilihanTerbit);
        }
    }
    protected function listDamiData($request)
    {
        $valueLayout = $request->get('value');
        if ($valueLayout == '1') {
            $dami = (object)[
                [
                    'value' => '16',
                    'label' => '16',
                ],
                [
                    'value' => '24',
                    'label' => '24',
                ],
                [
                    'value' => '32',
                    'label' => '32',
                ],
            ];
        } elseif ($valueLayout == '2') {
            $dami = (object)[
                [
                    'value' => '12',
                    'label' => '12',
                ],
                [
                    'value' => '16',
                    'label' => '16',
                ],
                [
                    'value' => '32',
                    'label' => '32',
                ],
            ];
        }
        $id = $request->get('id');
        $data = DB::table('order_cetak')->where('id', $id)->first();
        echo '<option label="Pilih"></option>';
        foreach ($dami as $key => $value) {
            if ($data->dami == $value['value']) {
                echo '<option value="' . $value['value'] . '" selected>' . $value['label'] . '</option>';
            } else {
                echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
            }
        }
    }
    protected function listStatusCetak($request)
    {
        $statusCetak = array(['value' => 1, 'label' => 'Buku Baru'], ['value' => 2, 'label' => 'Cetak Ulang Revisi'], ['value' => 3, 'label' => 'Cetak Ulang']);
        $id = $request->get('id');
        $data = DB::table('order_cetak')->where('id', $id)->first();
        if (!is_null($data)) {
            $html = '';
            $html .= '<option label="Pilih"></option>';
            foreach ($statusCetak as $key => $value) {
                if ($data->status_cetak == $value['value']) {
                    $html .= '<option value="' . $value['value'] . '" selected>' . $value['label'] . '</option>';
                } else {
                    $html .= '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
                }
            }
            return response()->json([
                'params' => 1,
                'data' => $html
            ]);
        } else {
            return response()->json([
                'params' => 0,
                'data' => $statusCetak
            ]);
        }
    }
    protected function listJenisMesin($request)
    {
        $jenisMesin = (object)[['value' => '1', 'label' => 'POD'], ['value' => '2', 'label' => 'Mesin Besar']];
        $id = $request->get('id');
        $data = DB::table('order_cetak')->where('id', $id)->first();
        if (!is_null($data)) {
            $html = '';
            $html .= '<option label="Pilih"></option>';
            foreach ($jenisMesin as $key => $value) {
                if ($data->jenis_mesin == $value['value']) {
                    $html .= '<option value="' . $value['value'] . '" selected>' . $value['label'] . '</option>';
                } else {
                    $html .= '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
                }
            }
            return response()->json([
                'params' => 1,
                'data' => $html
            ]);
        } else {
            return response()->json([
                'params' => 0,
                'data' => $jenisMesin
            ]);
        }
    }
    public function updateTanggalUploadEbook(Request $request)
    {
        $id = $request->id;
        $tanggalUpload = $request->value;
        $history_tanggal = $request->history;
        $ebook = DB::table('produksi_order_ebook')->where('id', $id)
            ->update([
                'tgl_upload' => Carbon::createFromFormat('d F Y', $tanggalUpload)->format('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id,
            ]);
        $penyetujuan = DB::table('produksi_penyetujuan_order_ebook')->where('produksi_order_ebook_id', $id)->update([
            'tgl_upload_history' => $history_tanggal,
        ]);
        $data = [
            'status' => 'success',
            'message' => 'Berhasil mengubah tanggal upload ebook',
        ];
        return response()->json($data);
    }
    public function updateTanggalJadiCetak(Request $request)
    {
        $id = $request->id;
        $tanggalUpload = $request->value;
        $history_tanggal = $request->history;
        $ebook = DB::table('produksi_order_cetak')->where('id', $id)
            ->update([
                'tgl_permintaan_jadi' => Carbon::createFromFormat('d F Y', $tanggalUpload)->format('Y-m-d'),
                'updated_by' => Auth::user()->id,
            ]);
        $penyetujuan = DB::table('produksi_penyetujuan_order_cetak')->where('produksi_order_cetak_id', $id)->update([
            'tgl_permintaan_jadi_history' => $history_tanggal,
        ]);
        $data = [
            'status' => 'success',
            'message' => 'Berhasil mengubah tanggal permintaan jadi cetak',
        ];
        return response()->json($data);
    }
    public function updateJumlahCetak(Request $request)
    {
        $id = $request->id;
        $jumlah = $request->value;
        $history_jumlah = $request->history;
        $cetak = DB::table('produksi_order_cetak')->where('id', $id)
            ->update([
                'jumlah_cetak' => $jumlah,
                'updated_by' => Auth::user()->id,
            ]);
        $penyetujuan = DB::table('produksi_penyetujuan_order_cetak')->where('produksi_order_cetak_id', $id)->update([
            'jumlah_cetak_history' => $history_jumlah,
        ]);
        $data = [
            'status' => 'success',
            'message' => 'Berhasil mengubah jumlah cetak',
        ];
        return response()->json($data);
    }
}
