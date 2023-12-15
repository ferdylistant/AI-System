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
            <th rowspan="2">Nama</th>
            <th rowspan="2">Tanggal Lahir</th>
            <th rowspan="2">Tempat Lahir</th>
            <th rowspan="2">Kewarganegaraan</th>
            <th rowspan="2">Alamat Domisili</th>
            <th rowspan="2">Ponsel Domisili</th>
            <th rowspan="2">Telepon Domisili</th>
            <th rowspan="2">Email</th>
            <th rowspan="2">Nama kantor</th>
            <th rowspan="2">Jabatan</th>
            <th rowspan="2">Alamat Kantor</th>
            <th rowspan="2">Telepon Kantor</th>
            <th rowspan="2">Instagram</th>
            <th rowspan="2">Facebook</th>
            <th rowspan="2">Twitter</th>
            <th rowspan="2">File Hibah Royalti</th>
            <th rowspan="2">Foto Penulis</th>
            <th rowspan="2">Tentang Penulis</th>
            <th rowspan="2">Bank</th>
            <th rowspan="2">Atas Nama</th>
            <th rowspan="2">No Rekening</th>
            <th rowspan="2">NPWP</th>
            <th rowspan="2">Scan NPWP</th>
            <th rowspan="2">KTP</th>
            <th rowspan="2">Scan KTP</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $i => $v)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $v->nama }}</td>
                <td>{{ $v->tanggal_lahir }}</td>
                <td>{{ $v->tempat_lahir }}</td>
                <td>{{ $v->kewarganegaraan }}</td>
                <td>{{ $v->alamat_domisili }}</td>
                <td>{{ $v->ponsel_domisili }}</td>
                <td>{{ $v->telepon_domisili }}</td>
                <td>{{ $v->email }}</td>
                <td>{{ $v->nama_kantor }}</td>
                <td>{{ $v->jabatan_dikantor }}</td>
                <td>{{ $v->alamat_kantor }}</td>
                <td>{{ $v->telepon_kantor }}</td>
                <td>{{ $v->sosmed_ig }}</td>
                <td>{{ $v->sosmed_fb }}</td>
                <td>{{ $v->sosmed_tw }}</td>
                <td>{{ $v->url_hibah_royalti }}</td>
                <td>{{ is_null($v->foto_penulis) ? '-': url('storage/penerbitan/penulis/'.$v->id.'/'.$v->foto_penulis) }}</td>
                <td>{{ $v->url_tentang_penulis }}</td>
                <td>{{ $v->bank }}</td>
                <td>{{ $v->bank_atasnama }}</td>
                <td>{{ $v->no_rekening }}</td>
                <td>{{ $v->npwp }}</td>
                <td>{{ is_null($v->scan_npwp) ? '-': url('storage/penerbitan/penulis/'.$v->id.'/'.$v->scan_npwp) }}</td>
                <td>{{ $v->ktp }}</td>
                <td>{{ is_null($v->scan_ktp) ? '-': url('storage/penerbitan/penulis/'.$v->id.'/'.$v->scan_ktp) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
