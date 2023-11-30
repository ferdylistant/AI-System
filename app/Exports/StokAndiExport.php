<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use App\Events\convertNumberToRoman;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class StokAndiExport implements FromView
{
    use Exportable;

    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     $data = DB::table('pj_st_andi')->where('id',$this->id)->first();
    //     return $data;
    // }
    public function view(): View
    {
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
        ->where('st.id',$this->id)
        ->orderBy('pn.kode', 'asc')
        ->select(
            'dp.judul_final',
            'df.sub_judul_final',
            'ps.edisi_cetak',
            'ps.pengajuan_harga',
            'ps.isbn',
            'dp.naskah_id',
            'dp.format_buku',
            'dp.imprint',
            'pn.kode',
            'kb.nama as nama_kb',
            'skb.nama as nama_skb',
            'st.*',
        )->first();
        $zone1 = $data->pengajuan_harga;
        $data = (object)collect($data)->map(function($item,$key) {
            switch ($key) {
                case 'judul_final':
                    return ucfirst($item);
                    break;
                case 'sub_judul_final':
                    return ucfirst($item);
                    break;
                case 'is_active':
                    return $item == 0?'<span class="badge badge-danger">Tidak Aktif</span>':'<span class="badge badge-success">Aktif</span>';
                    break;
                case 'created_at':
                    return Carbon::parse($item)->translatedFormat('l d F Y H:i');
                    break;
                case 'format_buku':
                    $res = DB::table('format_buku')->whereNull('deleted_at')->where('id',$item)->first()->jenis_format.' cm';
                    return $res;
                    break;
                case 'imprint':
                    $res = DB::table('imprint')->whereNull('deleted_at')->where('id',$item)->first()->nama;
                    return $res;
                    break;
                case 'edisi_cetak':
                    $roman = event(new convertNumberToRoman($item));
                    $item = implode('',$roman).'/'.$item;
                    return $item;
                    break;
                default:
                    return $item ?? '-';
                break;
            }
        })->except(['id','naskah_id','pengajuan_harga','created_at'])->all();
        $hargaJual = DB::table('pj_st_harga_jual as hj')->join('pj_st_master_harga_jual as  mhj','hj.master_harga_jual_id','=','mhj.id')
        ->where('hj.stok_id',$this->id)
        ->whereNull('mhj.deleted_at')
        ->select('hj.*','mhj.nama')
        ->get();
        return view('penjualan_stok.gudang.stok_andi.include.cetak_pdf', [
            'id' => $this->id,
            'data' => $data,
            'harga_jual' => $hargaJual,
            'zone1' => $zone1,
        ]);
    }
}
