<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenulisExport implements WithStyles, FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }
    public function headings(): array
    {
        return [
            'id',
            'nama',
            'tanggal_lahir',
            'tempat_lahir',
            'kewarganegaraan',
            'alamat_domisili',
            'ponsel_domisili',
            'telepon_domisili',
            'email',
            'nama_kantor',
            'jabatan_dikantor',
            'alamat_kantor',
            'telepon_kantor',
            'sosmed_ig',
            'sosmed_tw',
            'file_hibah_royalti',
            'foto_penulis',
            'url_tentang_penulis',
            'bank',
            'bank_atasnama',
            'no_rekening',
            'npwp',
            'ktp',
            'scan_npwp',
            'scan_ktp',
            'scan_ktp',
            'created_by',
            'updated_by',
            'deleted_by',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
    public function collection()
    {
        $res = DB::table('penerbitan_penulis')->get();
        return $res;
    }
}
