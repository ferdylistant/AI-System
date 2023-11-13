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
                    <a href="{{ url('/') }}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{ url('/penerbitan/order-cetak') }}">Data Penerbitan Order Cetak</a>
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
                                </h4>
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
                                <br>
                                @if (!is_null($data->file_analisis))
                                @php
                                    $ext = pathinfo(storage_path().'/penerbitan/order_cetak/file_analisis/'.$data->id.'/'.$data->file_analisis, PATHINFO_EXTENSION);
                                    switch ($ext) {
                                        case 'xls':
                                            $fa = 'excel';
                                            $cl = 'success';
                                            $txt = 'Excel';
                                            break;
                                        case 'xlsx':
                                            $fa = 'excel';
                                            $cl = 'success';
                                            $txt = 'Excel';
                                            break;
                                        case 'csv':
                                            $fa = 'csv';
                                            $cl = 'success';
                                            $txt = 'Excel';
                                            break;
                                        case 'docx':
                                            $fa = 'word';
                                            $cl = 'primary';
                                            $txt = 'Word';
                                            break;
                                        case 'pdf':
                                            $fa = 'pdf';
                                            $cl = 'danger';
                                            $txt = 'PDF';
                                            break;
                                    }
                                @endphp
                                <a href="{!!url('/storage/penerbitan/order_cetak/file_analisis/'.$data->id.'/'.$data->file_analisis)!!}"  class="btn btn-{{$cl}} mr-1" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;" download>
                                    <i class="fas fa-file-{{$fa}}"></i> Download {{$txt}} Analisis</a>
                                @endif
                                <a target="_blank" href="{{url('/penerbitan/order-cetak/print/'.$data->id)}}" class="btn btn-light float-right" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">
                                    <i class="fas fa-print"></i> Cetak Formulir
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="mb-3" method="POST" id="fadd_Approval">
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
                                        <p class="mb-1 text-monospace kode_order" id="kode_order"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Status Cetak</h6>
                                        </div>
                                        <p class="mb-1 text-monospace status_cetak" id="status_cetak"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tipe Order</h6>
                                        </div>
                                        <p class="mb-1 text-monospace tipe_order" id="tipe_order"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Pilihan Terbit</h6>
                                        </div>
                                        <p class="mb-1 text-monospace pilihan_terbit" id="pilihan_terbit"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Judul Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace judul_final" id="judul_final"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Sub Judul</h6>
                                        </div>
                                        <p class="mb-1 text-monospace sub_judul_final" id="sub_judul_final"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Penulis</h6>
                                        </div>
                                        <p class="mb-1 text-monospace penulis" id="penulis"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Nama Pena</h6>
                                        </div>
                                        <p class="mb-1 text-monospace nama_pena" id="nama_pena"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">ISBN</h6>
                                        </div>
                                        <p class="mb-1 text-monospace isbn" id="isbn"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Imprint</h6>
                                        </div>
                                        <p class="mb-1 text-monospace imprint" id="imprint"></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kelompok Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace nama" id="nama"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Edisi Cetak</h6>
                                        </div>
                                        <p class="mb-1 text-monospace edisi_cetak" id="edisi_cetak"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Format Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace format_buku" id="format_buku"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jumlah Halaman Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace jml_hal_final" id="jml_hal_final"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jilid</h6>
                                        </div>
                                        <p class="mb-1 text-monospace jilid" id="jilid"></p>
                                    </div>

                                    <div class="list-group-item flex-column align-items-start" id="divBinding">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Ukuran Binding</h6>
                                        </div>
                                        <p class="mb-1 text-monospace ukuran_jilid_binding" id="ukuran_jilid_binding"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kertas Isi</h6>
                                        </div>
                                        <p class="mb-1 text-monospace kertas_isi" id="kertas_isi"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kertas Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace kertas_cover" id="kertas_cover"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Finishing Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace finishing_cover" id="finishing_cover"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jenis Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace jenis_cover" id="jenis_cover"></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jalur Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace jalur_buku" id="jalur_buku"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jumlah Cetak</h6>
                                        </div>
                                        <p class="mb-1 text-monospace jumlah_cetak" id="jumlah_cetak"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Buku Jadi</h6>
                                        </div>
                                        <p class="mb-1 text-monospace buku_jadi" id="buku_jadi"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Buku Contoh</h6>
                                        </div>
                                        <p class="mb-1 text-monospace buku_contoh" id="buku_contoh"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Keterangan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace keterangan" id="keterangan"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Perlengkapan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace perlengkapan" id="perlengkapan"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Warna Isi</h6>
                                        </div>
                                        <p class="mb-1 text-monospace isi_warna" id="isi_warna"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Warna Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace warna" id="warna"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tahun Terbit</h6>
                                        </div>
                                        <p class="mb-1 text-monospace tahun_terbit" id="tahun_terbit"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">SPP</h6>
                                        </div>
                                        <p class="mb-1 text-monospace spp" id="spp"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

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
    <script src="{{ url('vendors/js-skeleton-loader-master/index.js') }}"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/penerbitan/detail_order_cetak.js') }}"></script>
    <script src="{{ url('js/penerbitan/approval_order_cetak.js') }}"></script>
    <script src="{{ url('js/penerbitan/decline_order_cetak.js') }}"></script>
@endsection

@yield('jsNeededForm')
