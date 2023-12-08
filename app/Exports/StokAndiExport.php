<?php

namespace App\Exports;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Events\convertNumberToRoman;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StokAndiExport implements FromView,WithStyles,ShouldAutoSize
{
    use Exportable;

    public $penulis;
    public $imprint;
    public $min;
    public $max;

    public function __construct($penulis, $imprint, $min, $max)
    {
        $this->penulis = $penulis;
        $this->imprint = $imprint;
        $this->min = $min;
        $this->max = $max;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ]
                ],
            2 => ['font' => ['bold' => true]]
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $penulis = (string)$this->penulis;
        $imprint = (string)$this->imprint;
        $min = (int)$this->min;
        $max = (int)$this->max;
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
            ->leftJoin('format_buku as fb', function ($q) {
                $q->on('dp.format_buku', '=', 'fb.id')
                    ->whereNull('fb.deleted_at');
            })
            ->leftJoin('imprint as im', function ($q) {
                $q->on('dp.imprint', '=', 'im.id')
                    ->whereNull('im.deleted_at');
            })
            ->when($imprint != 'null', function ($query) use ($imprint) {
                return $query->where('im.nama', $imprint);
            })
            ->when($min != 0, function ($query) use ($min) {
                return $query->where('st.total_stok', '>=', $min);
            })
            ->when($max != 0, function ($query) use ($max) {
                return $query->where('st.total_stok', '<=', $max);
            })
            ->orderBy('pn.kode', 'asc')
            ->select(
                'st.*',
                'pn.kode as kode_naskah',
                'ps.edisi_cetak',
                'ps.pengajuan_harga as zona1',
                'ps.isbn',
                'df.sub_judul_final',
                'dp.judul_final',
                'dp.naskah_id',
                'fb.jenis_format as format_buku',
                'im.nama as imprint',
                'kb.nama as kelompok_buku',
                'skb.nama as sub_kelompok_buku',
            )
            ->get();
        $data = (object)collect($data)->map(function($item,$key){
            $res = (object)collect($item)->put('penulis',DB::table('penerbitan_naskah_penulis as pnp')
            ->join('penerbitan_penulis as pp', function ($q) {
                $q->on('pnp.penulis_id', '=', 'pp.id')
                    ->whereNull('pp.deleted_at');
            })
            ->where('pnp.naskah_id', '=', $item->naskah_id)
            ->select('pp.nama')
            ->first()->nama)->all();
            return $res;
        });
        $data = (object)collect($data)->when($penulis != 'null', function($query) use ($penulis) {
            return $query->where('penulis',$penulis);
        });
        $hargaJual = (object)collect($data)->map(function($item,$key) {
            $master = DB::table('pj_st_master_harga_jual')->whereNull('deleted_at')->get();
            $hargaJual = DB::table('pj_st_harga_jual')->where('stok_id',$item->id)->get();
            $col = collect($hargaJual)->map(function($val){
                return $val->master_harga_jual_id;
            })->all();
            foreach($master as $mas) {
                if(in_array($mas->id,$col)) {
                    $hargaJual = DB::table('pj_st_harga_jual')
                    ->where('stok_id',$item->id)
                    ->where('master_harga_jual_id',$mas->id)
                    ->first();
                    $item = (object)collect($item)->put($mas->nama,$hargaJual->harga)->all();
                } else {
                    $item = (object)collect($item)->put($mas->nama,'0')->all();
                }
            }
            return $item;
        });
        // $hargaJual = (object)collect($data)->map(function($item,$key){
        //     $hargaJual = DB::table('pj_st_harga_jual')->where('stok_id',$item->id)->get();
        //     $col = (object)collect($hargaJual)->map(function($val){
        //         return $val->master_harga_jual_id;
        //     })->all();
        //     return $col;
        // })->all();
        $master = DB::table('pj_st_master_harga_jual')->whereNull('deleted_at')->get();
        foreach ($data as $value) {
            $roman = event(new convertNumberToRoman($value->edisi_cetak));
            $edisiCetak = implode('', $roman) . '/' . $value->edisi_cetak;
            (object)$view[] = (object)[
                'id' => $value->id,
                'kode_sku' => $value->kode_sku,
                'kode_naskah' => $value->kode_naskah,
                'judul_final' => ucfirst($value->judul_final),
                'sub_judul_final' => ucfirst($value->sub_judul_final),
                'penulis' => $value->penulis,
                'kelompok_buku' => ucfirst($value->kelompok_buku),
                'sub_kelompok_buku' => ucfirst($value->sub_kelompok_buku),
                'imprint' => $value->imprint,
                'format_buku' => $value->format_buku . ' cm',
                'edisi_cetak' => $edisiCetak,
                'total_stok' => $value->total_stok,
                'isbn' => (int)$value->isbn,
                'is_active' => $value->is_active == '1' ? 'Aktif':'Tidak Aktif',
                'zona1' => $value->zona1
            ];
        }
        // dd($hargaJual);
        return view('penjualan_stok.gudang.stok_andi.include.export_excel', [
            'body' => $view,
            'colspan' => count($master)+1,
            'master' => $master,
            'harga_jual' => $hargaJual,
        ]);
    }
    public function failed(Throwable $e): void
    {

    }
}
