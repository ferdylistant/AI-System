<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class PenulisExport implements FromCollection
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $res = DB::table('penerbitan_penulis')->get();
        return $res;
    }
}
