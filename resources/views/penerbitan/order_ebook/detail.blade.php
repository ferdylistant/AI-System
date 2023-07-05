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
            <h1>Detail Penerbitan Order E-Book</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{url('/')}}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{url('/penerbitan/order-ebook')}}">Data Penerbitan Order E-Book</a>
                </div>
                <div class="breadcrumb-item">
                    Detail Penerbitan Order E-Book
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-primary">
                        <div class="card-header justify-content-between">
                            <div class="col-auto">
                                <h4 class="section-title">Form Penerbitan Order E-book</h4>
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
                            </div>
                            <div class="col-auto">
                                <span class="bullet text-danger"></span> Kode order: <b>{{$data->kode_order}}</b> (@foreach (json_decode($data->pilihan_terbit) as $i => $pt)
                                    @if (count(json_decode($data->pilihan_terbit)) == 2)
                                        @if ($i == 0)
                                        {{$pt}} &
                                        @else
                                        {{$pt}}
                                        @endif
                                    @else
                                    {{$pt}}
                                    @endif

                                @endforeach)
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="mb-3" method="POST" id="fadd_Approval">
                                {{--! PERSETUJUAN --}}
                                @include('penerbitan.order_ebook.include.persetujuan')
                                {{--! PERSETUJUAN BATAS --}}
                                <div class="row mb-4">
                                    <div class="col-12 col-md-4">
                                        <div class="list-group-item flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Kode Order</h6>
                                            </div>
                                            <input type="hidden" id="kode_order" name="kode_order"
                                                value="{{ $data->kode_order }}">
                                            <p class="mb-1 text-monospace">{{ $data->kode_order }}</p>
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
                                                <h6 class="mb-1">Platform Digital</h6>
                                            </div>
                                            @foreach ($platform_digital as $pDigital)
                                            <p class="mb-1 text-monospace">
                                                <span class="bullet"></span>
                                                <span>{{ $pDigital }}</span>
                                            </p>
                                            @endforeach
                                        </div>
                                        <div class="list-group-item flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Jalur Buku</h6>
                                            </div>
                                            <p class="mb-1 text-monospace">
                                                {{ $data->jalur_buku }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="list-group-item flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Judul Buku</h6>
                                            </div>
                                            <input type="hidden" id="judul_final" name="judul_final"
                                                value="{{ $data->judul_final }}">
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
                                                <h6 class="mb-1">Sub-Judul Buku</h6>
                                            </div>
                                            <p class="mb-1 text-monospace">
                                                @if (is_null($data->sub_judul_final))
                                                    -
                                                @else
                                                    {{ $data->sub_judul_final }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="list-group-item flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Penulis</h6>
                                            </div>
                                            <p class="mb-1 text-monospace">
                                                @foreach ($penulis as $pen)
                                                    <p class="mb-1 text-monospace">
                                                        <span class="bullet"></span>
                                                        <a
                                                            href="{{ url('/penerbitan/penulis/detail-penulis/' . $pen->id) }}">{{ $pen->nama }}</a>
                                                    </p>
                                                @endforeach
                                            </p>
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
                                                <h6 class="mb-1">Imprint</h6>
                                            </div>
                                            <p class="mb-1 text-monospace">
                                                @if (is_null($data->imprint))
                                                    -
                                                @else
                                                    {{ $imprint }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="list-group-item flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Tahun Terbit</h6>
                                            </div>
                                            <p class="mb-1 text-monospace">
                                                @if (is_null($data->tahun_terbit))
                                                    -
                                                @else
                                                    {{ $data->tahun_terbit }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">

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
                                                <h6 class="mb-1">Jumlah Halaman</h6>
                                            </div>
                                            <p class="mb-1 text-monospace">
                                                @if (is_null($data->jml_hal_final))
                                                    -
                                                @else
                                                    {{ $data->jml_hal_final }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="list-group-item flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">SPP</h6>
                                            </div>
                                            <p class="mb-1 text-monospace">
                                                @if (is_null($data->spp))
                                                    -
                                                @else
                                                    {{ $data->spp }}
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
                                                <h6 class="mb-1">Perlengkapan</h6>
                                            </div>
                                            <p class="mb-1 text-monospace">
                                                @if (is_null($data->perlengkapan))
                                                    -
                                                @else
                                                    {{ $data->perlengkapan }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </form>
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



    @include('penerbitan.order_ebook.include.modal_decline')
    @include('penerbitan.order_ebook.include.modal_approve')
    @include('penerbitan.order_ebook.include.modal_approve_detail')
    @include('penerbitan.order_ebook.include.modal_decline_detail')
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
    <script src="{{ url('js/penerbitan/approval_order_ebook.js') }}"></script>
    <script src="{{ url('js/penerbitan/decline_order_ebook.js') }}"></script>
@endsection

@yield('jsNeededForm')
