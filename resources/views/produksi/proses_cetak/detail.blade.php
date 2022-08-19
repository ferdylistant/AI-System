@extends('layouts.app')
@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{url('vendors/flipbook/min_version/ipages.min.css')}}">
@endsection

@section('cssNeeded')
<style>
    .tb-naskah {
        font-size: 12px;
        border: 1px solid #ced4da;
    }
    .tb-naskah, .tb-naskah th, .tb-naskah td {
        height: auto !important;
        padding: 10px 15px 10px 15px !important;
    }
    .tb-naskah th {
        width: 35%;
        color: #868ba1;
        background-color: #E9ECEF;
    }
    .accordion-body {
        overflow: auto;
        max-height: 500px;
    }
    .stamp {
        /* transform: rotate(12deg); */
        color: #555;
        font-size: 1rem;
        font-weight: 700;
        border: 0.25rem solid #555;
        display: inline-block;
        padding: 0.25rem 1rem;
        text-transform: uppercase;
        border-radius: 1rem;
        font-family: 'Courier';
        -webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
        -webkit-mask-size: 944px 604px;
        mix-blend-mode: multiply;
    }

    .is-nope {
        color: #D23;
        border: 0.5rem double #D23;
        transform: rotate(3deg);
            -webkit-mask-position: 2rem 3rem;
        font-size: 2rem;
    }

    .is-approved {
        color: #0A9928;
        border: 0.5rem double #0A9928;
        -webkit-mask-position: 13rem 6rem;
        transform: rotate(-14deg);
        -webkit-mask-position: 2rem 3rem;
        border-radius: 0;
    }

    .is-draft {
        color: #C4C4C4;
        border: 1rem double #C4C4C4;
        transform: rotate(-5deg);
        font-size: 6rem;
        font-family: "Open sans", Helvetica, Arial, sans-serif;
        border-radius: 0;
        padding: 0.5rem;
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('ebook.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Proses Produksi Order Cetak</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h4>Data Produksi Order Cetak&nbsp;
                            - {{Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y')}}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kode Order</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">{{ $data->kode_order }}</p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tipe Order</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->tipe_order == '1')
                                            Buku Umum
                                        @elseif ($data->tipe_order == '2')
                                            Buku Rohani
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Status Cetak</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->status_cetak == '1')
                                            Buku Baru
                                        @elseif ($data->status_cetak == '2')
                                            Cetak Ulang Revisi
                                        @elseif ($data->status_cetak == '3')
                                            Cetak Ulang
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tanggal Order</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->tanggal_order))
                                            {{Carbon\Carbon::parse($data->tanggal_order)->translatedFormat('d F Y')}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tanggal Permintaan Jadi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->tgl_permintaan_jadi))
                                            {{Carbon\Carbon::parse($data->tgl_permintaan_jadi)->translatedFormat('d F Y')}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Edisi Cetakan</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->edisi_cetakan))
                                        -
                                    @else
                                        {{ $data->edisi_cetakan.'/'.$data->tahun_terbit }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Plat</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->plat))
                                            {{Carbon\Carbon::parse($data->plat)->translatedFormat('d F Y')}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Lipat Isi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->lipat_isi))
                                            {{Carbon\Carbon::parse($data->lipat_isi)->translatedFormat('d F Y')}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Potong 3 Sisi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->potong_3_sisi))
                                            {{Carbon\Carbon::parse($data->potong_3_sisi)->translatedFormat('d F Y')}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Judul Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->judul_buku))
                                        -
                                    @else
                                        {{ $data->judul_buku }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Sub-Judul Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->sub_judul))
                                        -
                                    @else
                                        {{ $data->sub_judul }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Penulis</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->penulis))
                                        -
                                    @else
                                        {{ $data->penulis }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Format Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->format_buku))
                                        -
                                    @else
                                        {{ $data->format_buku }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jumlah Cetak</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->jumlah_cetak))
                                        -
                                    @else
                                        {{ $data->jumlah_cetak }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jumlah Halaman</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->jumlah_halaman))
                                        -
                                    @else
                                        {{ $data->jumlah_halaman }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Cetak Isi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->cetak_isi))
                                            {{Carbon\Carbon::parse($data->cetak_isi)->translatedFormat('d F Y')}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->cover))
                                            {{Carbon\Carbon::parse($data->cover)->translatedFormat('d F Y')}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jilid</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->jilid))
                                            {{Carbon\Carbon::parse($data->jilid)->translatedFormat('d F Y')}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jenis Jilid</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->jenis_jilid))
                                        -
                                    @else
                                        @if($data->jenis_jilid == '1')
                                        Bending&nbsp;{{$data->ukuran_bending}}
                                        @elseif($data->jenis_jilid == '2')
                                        Jahit Kawat
                                        @elseif ($data->jenis_jilid == '3')
                                        Jahit Benang
                                        @elseif ($data->jenis_jilid == '4')
                                        Hardcover
                                        @endif
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kertas Isi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->kertas_isi))
                                        -
                                    @else
                                        {{ $data->kertas_isi }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Efek Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->efek_cover))
                                        -
                                    @else
                                        {{ $data->efek_cover }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Warna Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->warna_cover))
                                        -
                                    @else
                                        {{ $data->warna_cover }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Buku Jadi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->buku_jadi))
                                        -
                                    @else
                                        {{ $data->buku_jadi }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Keterangan</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->keterangan))
                                        -
                                    @else
                                        {{ $data->keterangan }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Katern</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->katern))
                                        -
                                    @else
                                        {{ $data->katern }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Mesin</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->mesin))
                                        -
                                    @else
                                        {{ $data->mesin }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Wrapping</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->wrapping))
                                            {{Carbon\Carbon::parse($data->wrapping)->translatedFormat('d F Y')}}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Harga</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->harga))
                                        -
                                    @else
                                        {{ $data->harga }}
                                    @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    #md_updateSubtimeline table {
        width: 100%;
    }
    #md_updateSubtimeline table tr th {
        text-align: center;
    }
</style>

@endsection
@section('jsRequired')
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/additional-methods.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/pdf.min.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/jquery.ipages.min.js')}}"></script>
<script src="{{url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
@endsection

@section('jsNeeded')
@endsection

@yield('jsNeededForm')


