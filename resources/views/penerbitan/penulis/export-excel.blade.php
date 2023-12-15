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
                <th>No</th>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>Tempat Lahir</th>
                <th>Kewarganegaraan</th>
                <th>Alamat Domisili</th>
                <th>Ponsel Domisili</th>
                <th>Telepon Domisili</th>
                <th>Email</th>
                <th>Nama kantor</th>
                <th>Jabatan</th>
                <th>Alamat Kantor</th>
                <th>Telepon Kantor</th>
                <th>Instagram</th>
                <th>Facebook</th>
                <th>Twitter</th>
                <th>File Hibah Royalti</th>
                <th>Foto Penulis</th>
                <th>Tentang Penulis</th>
                <th>Bank</th>
                <th>Atas Nama</th>
                <th>No Rekening</th>
                <th>NPWP</th>
                <th>Scan NPWP</th>
                <th>KTP</th>
                <th>Scan KTP</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $v)
                <tr>
                    <td>{{ $i + 1 }}</td>
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
                    <td>{{ is_null($v->foto_penulis) ? '-' : url('storage/penerbitan/penulis/' . $v->id . '/' . $v->foto_penulis) }}
                    </td>
                    <td>{{ $v->url_tentang_penulis }}</td>
                    <td>{{ $v->bank }}</td>
                    <td>{{ $v->bank_atasnama }}</td>
                    <td>{{ $v->no_rekening }}</td>
                    <td>{{ $v->npwp }}</td>
                    <td>{{ is_null($v->scan_npwp) ? '-' : url('storage/penerbitan/penulis/' . $v->id . '/' . $v->scan_npwp) }}
                    </td>
                    <td>{{ $v->ktp }}</td>
                    <td>{{ is_null($v->scan_ktp) ? '-' : url('storage/penerbitan/penulis/' . $v->id . '/' . $v->scan_ktp) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
