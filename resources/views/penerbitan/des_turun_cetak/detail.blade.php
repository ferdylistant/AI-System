@extends('layouts.app')
@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/flipbook/min_version/ipages.min.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('desturcet.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Detail Deskripsi Turun Cetak</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Data Deskripsi Turun Cetak&nbsp;
                                -
                            </h4>
                            @if ($data->status == 'Antrian')
                                <span class="badge" style="background:#34395E;color:white">{{ $data->status }}</span>
                            @elseif ($data->status == 'Pending')
                                <span class="badge badge-danger">{{ $data->status }}</span>
                            @elseif ($data->status == 'Proses')
                                <span class="badge badge-success">{{ $data->status }}</span>
                            @elseif ($data->status == 'Selesai')
                                <span class="badge badge-light">{{ $data->status }}</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kode</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">{{ $data->kode }}</p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Judul Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->judul_final))
                                                -
                                            @else
                                                {{ $data->judul_final }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Penulis</h6>
                                        </div>
                                        <ul class="list-unstyled list-inline">
                                            <li class="list-inline-item">
                                                @foreach ($penulis as $pen)
                                                    <p class="mb-1 text-monospace">
                                                        <span class="bullet"></span>
                                                        <span>{{ $pen->nama }}</span>
                                                    </p>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Edisi Cetak Tahun</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->edisi_cetak))
                                                -
                                            @else
                                                {{ $data->edisi_cetak }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">ISBN</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->isbn))
                                                -
                                            @else
                                                {{ $data->isbn }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Format Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->format_buku))
                                                -
                                            @else
                                                {{ $data->format_buku }}&nbsp;cm
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kelompok Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->nama))
                                                -
                                            @else
                                                {{ $data->nama }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jumlah Halaman Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->jml_hal_final))
                                                -
                                            @else
                                                {{ $data->jml_hal_final }} Halaman
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Deskripsi Produk</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            <span class="text-danger">Masih Kosong</span>
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Masuk</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->tgl_masuk))
                                                -
                                            @else
                                                {{ Carbon\Carbon::parse($data->tgl_masuk)->translatedFormat('l d F Y, H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Sasaran Pasar</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($sasaran_pasar))
                                                -
                                            @else
                                                {{ $sasaran_pasar }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Bulan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->bulan))
                                                -
                                            @else
                                                {{ $data->bulan }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">PIC Prodev</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($pic))
                                                -
                                            @else
                                                {{ $pic }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Contoh Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->url_file))
                                                -
                                            @else
                                                <a href="{{ $data->url_file }}">{{ $data->url_file }}</a>
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
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ url('vendors/flipbook/min_version/pdf.min.js') }}"></script>
    <script src="{{ url('vendors/flipbook/min_version/jquery.ipages.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
@endsection

@section('jsNeeded')
@endsection

@yield('jsNeededForm')
