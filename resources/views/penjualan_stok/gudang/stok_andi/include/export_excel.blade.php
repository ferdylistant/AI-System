<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Kode SKU</th>
            <th rowspan="2">Kode Naskah</th>
            <th rowspan="2">Judul</th>
            <th rowspan="2">Sub-Judul</th>
            <th rowspan="2">Penulis</th>
            <th rowspan="2">Kelompok Buku</th>
            <th rowspan="2">Sub-Kelompok Buku</th>
            <th rowspan="2">Imprint</th>
            <th rowspan="2">Format Buku</th>
            <th rowspan="2">Edisi Cetak</th>
            <th rowspan="2">Total Stok</th>
            <th rowspan="2">ISBN</th>
            <th rowspan="2">Status</th>
            <th colspan="{{$colspan}}">Harga</th>
        </tr>
        <tr>
            <th scope="col">Zona 1</th>
            @foreach ($master as $m)
            <th scope="col">{{$m->nama}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($body as $i => $v)
        @php
            $hargaJual = DB::table('pj_st_harga_jual')->where('stok_id',$v->id)->get();
            $col = collect($hargaJual)->map(function($val){
                return $val->master_harga_jual_id;
            })->all();
        @endphp
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $v->kode_sku }}</td>
                <td>{{ $v->kode_naskah }}</td>
                <td>{{ $v->judul_final }}</td>
                <td>{{ $v->sub_judul_final }}</td>
                <td>{{ $v->penulis }}</td>
                <td>{{ $v->kelompok_buku }}</td>
                <td>{{ $v->sub_kelompok_buku }}</td>
                <td>{{ $v->imprint }}</td>
                <td>{{ $v->format_buku }}</td>
                <td>{{ $v->edisi_cetak }}</td>
                <td>{{ $v->total_stok }}</td>
                <td>{{ $v->isbn }}</td>
                <td>{{ $v->is_active }}</td>
                <td>{{ $v->zona1 }}</td>
                @foreach ($master as $ms)
                @if (in_array($ms->id,$col))
                    @php
                        $hargaJual = DB::table('pj_st_harga_jual')
                        ->where('stok_id',$v->id)
                        ->where('master_harga_jual_id',$ms->id)
                        ->first();
                    @endphp
                <td>{{ $hargaJual->harga }}</td>
                @else
                <td>0</td>

                @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
