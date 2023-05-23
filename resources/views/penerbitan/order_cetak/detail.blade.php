@extends('layouts.app')
@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/flipbook/min_version/ipages.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/jquery-magnify/dist/jquery.magnify.min.css') }}">
@endsection

@section('cssNeeded')
    <style>
        .tb-naskah {
            font-size: 12px;
            border: 1px solid #ced4da;
        }

        .tb-naskah,
        .tb-naskah th,
        .tb-naskah td {
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
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Detail Penerbitan Order Cetak Buku</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{url('/')}}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{url('/penerbitan/order-cetak')}}">Data Penerbitan Order Cetak</a>
                </div>
                <div class="breadcrumb-item">
                    Detail Penerbitan Order Cetak Buku
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-primary">
                        <div class="card-header justify-content-between">
                            <div class="col-auto">
                                <h4 class="section-title">
                                    Form Penerbitan Order Cetak
                                    @switch($data->status)
                                        @case('Antrian')
                                            <span class="badge" style="background:#34395E;color:white">Antrian</span>
                                        @break

                                        @case('Pending')
                                            <span class="badge badge-danger">Pending</span>
                                        @break

                                        @case('Proses')
                                            <span class="badge badge-success">Proses</span>
                                        @break

                                        @case('Selesai')
                                            <span class="badge badge-light">Selesai</span>
                                        @break
                                    @endswitch
                                </h4>
                            </div>
                            <div class="col-auto">
                                <span class="bullet text-danger"></span> Kode order: <b>{{ $data->kode_order }}</b>
                                (
                                    @if ($data->status_cetak == '3')
                                        Cetak Ulang
                                    @else
                                        @foreach (json_decode($data->pilihan_terbit) as $i => $pt)
                                            @if (count(json_decode($data->pilihan_terbit)) == 2)
                                                @if ($i == 0)
                                                    {{ $pt }} &
                                                @else
                                                    {{ $pt }}
                                                @endif
                                            @else
                                                {{ $pt }}
                                            @endif
                                        @endforeach
                                    @endif
                                )
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="fadd_Approval">
                                {{-- ! PERSETUJUAN --}}
                                @include('penerbitan.order_cetak.include.persetujuan')
                                {{-- ! PERSETUJUAN BATAS --}}
                            </form>
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
                                            <h6 class="mb-1">Status Cetak</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (!is_null($data->status_cetak))
                                                @switch($data->status_cetak)
                                                    @case(1)
                                                        Buku Baru
                                                    @break

                                                    @case(2)
                                                        Cetak Ulang Revisi
                                                    @break

                                                    @case(3)
                                                        Cetak Ulang
                                                    @break

                                                    @default
                                                        <span class="text-danger">Data Error</span>
                                                @endswitch

                                            @else
                                            -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tipe Order</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ $data->tipe_order == 1 ? 'Buku Umum' : 'Buku Rohani' }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Pilihan Terbit</h6>
                                        </div>
                                        @foreach (json_decode($data->pilihan_terbit) as $pt)
                                            <p class="mb-1 text-monospace">
                                                <span class="bullet"></span>
                                                <span>{{ $pt }}</span>
                                            </p>
                                        @endforeach
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Judul Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->judul_final) ? '-' : $data->judul_final }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Sub Judul</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->sub_judul_final) ? '-' : $data->sub_judul_final }}
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
                                                        <a href="{{ url('/penerbitan/penulis/detail-penulis/' . $pen->id) }}">{{ $pen->nama }}</a>
                                                    </p>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Nama Pena</h6>
                                        </div>
                                        @if ((is_null($data->nama_pena)))
                                        <p class="mb-1 text-monospace">
                                                -
                                        </p>
                                        @else
                                        <ul class="list-unstyled list-inline">
                                            @foreach (json_decode($data->nama_pena) as $pen)
                                                <li class="list-inline-item">
                                                <p class="mb-1 text-monospace">
                                                    <span class="bullet"></span>
                                                    <span>{{ $pen }}</span>
                                                </p>
                                                </li>
                                            @endforeach

                                        </ul>
                                        @endif
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">ISBN</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->isbn) ? '-' : $data->isbn }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Imprint</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->imprint) ? '-' : $imprint }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kelompok Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->nama) ? '-' : $data->nama }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Edisi Cetak</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->edisi_cetak) ? '-' : $data->edisi_cetak }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Format Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->format_buku) ? '-' : $format_buku . ' cm' }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jumlah Halaman Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->jml_hal_final) ? '-' : $data->jml_hal_final . ' Halaman' }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jilid</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{is_null($data->jilid)?'-':$data->jilid}}
                                        </p>
                                    </div>
                                    @if(!is_null($data->jilid))
                                        @if ($data->jilid == 'Binding')
                                        <div class="list-group-item flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Ukuran Binding</h6>
                                            </div>
                                            <p class="mb-1 text-monospace">
                                                {{is_null($data->ukuran_jilid_binding)?'-':$data->ukuran_jilid_binding . ' cm'}}
                                            </p>
                                        </div>
                                        @endif
                                    @endif
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kertas Isi</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->kertas_isi) ? '-' : $data->kertas_isi }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kertas Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->kertas_cover) ? '-' : $data->kertas_cover }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Finishing Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                        @if ((is_null($data->finishing_cover)) || ($data->finishing_cover == []))
                                            -
                                        @else
                                            @foreach (json_decode($data->finishing_cover,true) as $fc)
                                                <span class="bullet"></span>{{ $fc }}<br>
                                            @endforeach
                                        @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jenis Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->jenis_cover) ? '-' : $data->jenis_cover }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Terbit</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->tahun_terbit) ? '-' : $data->tahun_terbit }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jalur Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{$data->jalur_buku}}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jumlah Cetak</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->jumlah_cetak) ? '-' : $data->jumlah_cetak }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Buku Jadi</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->buku_jadi) ? '-' : $data->buku_jadi }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Buku Contoh</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->buku_contoh) ? '-' : $data->buku_contoh }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Keterangan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->keterangan) ? '-' : $data->keterangan }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Perlengkapan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->perlengkapan) ? '-' : $data->perlengkapan }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Warna Isi</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->isi_warna) ? '-' : $data->isi_warna }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Warna Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->warna) ? '-' : $data->warna }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tahun Terbit</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->tahun_terbit) ? '-' : $data->tahun_terbit }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">SPP</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->spp) ? '-' : $data->spp }}
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

@include('penerbitan.order_cetak.include.modal_decline')
@include('penerbitan.order_cetak.include.modal_approve')
@include('penerbitan.order_cetak.include.modal_approve_detail')
@include('penerbitan.order_cetak.include.modal_decline_detail')
@endsection
@section('jsRequired')
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ url('vendors/flipbook/min_version/pdf.min.js') }}"></script>
    <script src="{{ url('vendors/flipbook/min_version/jquery.ipages.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ url('vendors/jquery-magnify/dist/jquery.magnify.min.js') }}"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/approval_order_cetak.js') }}"></script>
    <script src="{{ url('js/decline_order_cetak.js') }}"></script>
@endsection

@yield('jsNeededForm')
