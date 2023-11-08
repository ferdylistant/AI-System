<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>
        Form Order Cetak
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ url('images/logo.png') }}">
</head>
<style type="text/css">
    h2 {
        text-align: center;

        font-size: 22px;

        margin-bottom: 50px;
    }
    h3 {
        text-align: center;

        font-size: 22px;

        margin-bottom: 50px;
    }

    .center {
        text-align: center;
    }

    .center img {
        display: block;
    }

    .section {
        margin-top: 30px;

        padding: 50px;

        background: #fff;
    }

    hr.style-eight {
        overflow: visible;
        /* For IE */
        padding: 0;
        border: none;
        border-top: medium double #333;
        color: #333;
        text-align: center;
    }

    hr.style-eight:after {
        content: "••●••";
        display: inline-block;
        position: relative;
        top: -0.9em;
        font-size: 1.5em;
        padding: 0 0.25em;
        background: white;
    }

    img.filter-img {
        -webkit-filter: grayscale(100%);
        /* Safari 6.0 - 9.0 */
        filter: grayscale(100%);
    }

    .ml7-min {
        margin-left: -304px;
    }
    .ml6-min {
        margin-left: -342px;
    }
    .ml5-min {
        margin-left: -291px;
    }
    .ml4-min {
        margin-left: -250px;
    }
    .ml3-min {
        margin-left: -264px;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="center">
            @if ($data->tipe_order == 1)
                @php
                    $size = 70;
                    $src = 'https://2.bp.blogspot.com/-26OOkCpYY7w/XBSmEG3LxSI/AAAAAAAAB_M/Gm7jUOqIBgwbu1eiOAjempNujA7CB48wgCK4BGAYYCw/s400/Lowongan%2BKerja%2BPenerbit%2BAndi%2BKaltim.jpg';
                @endphp
            @else
                @php
                    $size = 80;
                    $src = 'data:image/jpeg;base64,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';
                @endphp
            @endif
            <img src="{{$src}}"
                class="img-fluid mb-1" width="{{$size}}" height="{{$src}}" alt="" />
        </div>
        <center class="mb-2">

            <small>PENERBIT ANDI</small>
            <br />
            <small>Jl. Beo 36-40 Telp. (0274) 561881, Fax. 588282 E-Mail:
                penerbitan@andipublisher.com; Yogyakarta 55281</small>
        </center>
        <h2 class="font-weight-bold">
            @php
                $tit = $data->tipe_order == 1 ? 'UMUM':'ROHANI';
            @endphp
            <u>ORDER CETAK BUKU {{$tit}}</u>
        </h2>
        @if ($data->urgent == 'Urgent')
            <h3 class="font-weight-bold">URGENT</h3>
        @endif
        <!-- <hr class="style-eight"> -->
        <div class="row">
            <div class="col-12 col-md-11">
                <div class="flex-column align-items-start mb-4">
                    <span class="float-left">Kode Order&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $data->kode_order }}</span>
                    <span class="float-right">Status Cetak: {{ $data->status_cetak }}</span>
                </div>
                <div class="flex-column align-items-start ml5-min">
                    <div class="d-flex w-100 justify-content-between">
                        <span class="mb-1">Pilihan Terbit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->pilihan_terbit }}</span>

                    </div>

                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span class="mb-1">Platform Ebook&nbsp;&nbsp;: {{ $data->platform_digital_ebook_id }}</span>

                    </div>

                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span class="mb-1">Judul Buku&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            <b>{{ $data->judul_final }}</b></span>
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <span class="mb-1">Sub
                        Judul&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $data->sub_judul_final }}</span>
                    <div class="d-flex w-100 justify-content-between">
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Penulis&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->penulis }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">ISBN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->isbn }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start mb-4">
                    <span class="float-left">Penerbit-Imprint&nbsp;:
                        {{ $data->imprint }}</span>
                    <span class="float-right">Gol/Kelompok: {{ $data->kelompok_buku_id }}</span>
                </div>
                <div class="flex-column align-items-start ml4-min">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Edisi/Cetak&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->edisi_cetak }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start mb-4">
                    <span class="float-left">Format Buku&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $data->format_buku }}</span>
                    <span class="float-right">Perlengkapan: {{ $data->perlengkapan }}</span>
                </div>
                <div class="flex-column align-items-start ml6-min">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Jml. Halaman&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->jml_hal_final.' halaman' }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Jilid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->jilid }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start mb-4">
                    <span class="float-left">Kertas Isi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $data->kertas_isi }}</span>
                    <span class="float-right">Warna Isi: {{ $data->isi_warna }}</span>
                </div>
                <div class="flex-column align-items-start ml3-min">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Efek Cover&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->finishing_cover }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start mb-4">
                    <span class="float-left">Kertas Cover&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $data->kertas_cover }}</span>
                    <span class="float-right">Warna Cover: {{ $data->warna }}</span>
                </div>
                <div class="flex-column align-items-start ml7-min">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Jenis Cover&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->jenis_cover }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Buku Jadi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->buku_jadi }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Jml. Cetak&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->jumlah_cetak.' eks' }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Buku Contoh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->buku_contoh }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Status Buku&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->jalur_buku }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">Keterangan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->keterangan }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <span
                            class="mb-1">SPP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                            {{ $data->spp }}</span>
                    </div>
                </div>
                <div class="flex-column align-items-start mb-4">
                    <span class="float-left">Tgl. Terbit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $data->urgent }}</span>
                    <span class="float-right">Tahun Terbit: {{ $data->tahun_terbit }}</span>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-12">
                <span class="mb-1">Jogja, {{ $data->tgl_selesai_order }}</span>
            </div>
            <br>
            <br>
            <br>
            <br>
            <div class="col-12">
                @foreach ($departemen as $d)
                <span class="mb-1">{{ $d }}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>
</body>

</html>
