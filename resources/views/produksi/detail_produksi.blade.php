@extends('layouts.app')
<?php
    /* #################### Catatan #####################
    keyword [
        pn1 = Penilaian Prodev,
        pn2 = Penilaian Editor/Setter (if requried)
        pn3 = Penilaian Pemasaran,
        pn4 = Penilaian Operasional
    ]
    ##################################################### */

    // $arrPilihan_ = ["Baik", "Cukup", "Kurang"];
?>
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
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ url('penerbitan/naskah') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Order Produksi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Data Order Produksiiiii</h4>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <button type="button" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Disetujui</button>
                                    <button type="button" class="btn btn-danger" id="btn-decline"><i class="fas fa-times" data-id="" data-toggle="modal" data-target="#modalDecline"></i>&nbsp;Ditolak</button>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-4">
                                    Author:<h6 class="text-dark lead"> {{ $author->nama }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kode Order</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">{{ $data->id }}</p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Status Cetak</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->status_cetak == 1)
                                            Buku Baru
                                        @elseif ($data->status_cetak == 2)
                                            Cetak Ulang Revisi
                                        @elseif ($data->status_cetak == 3)
                                            Cetak Ulang
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Platform Digital</h6>
                                    </div>
                                    <ul class="list-unstyled list-inline">
                                        @foreach (json_decode($data->platform_digital) as $pDigital)
                                            <li class="list-inline-item">
                                            <p class="mb-1 text-monospace">
                                                <i class="fas fa-check text-success"></i>
                                                <span>{{ $pDigital }}</span>
                                            </p>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Urgent</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->urgent == 1)
                                            Ya
                                        @else
                                            Tidak
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">ISBN</h6>
                                    </div>
                                    @if (is_null($data->isbn))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->isbn }}</p>
                                    @endif

                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">E-ISBN</h6>
                                    </div>
                                    @if (is_null($data->eisbn))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->eisbn }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tanggal Terbit</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->tanggal_terbit))
                                            {{ date('d F Y', strtotime($data->tanggal_terbit)) }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Buku Contoh</h6>
                                    </div>
                                    @if (is_null($data->buku_contoh))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->buku_contoh }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Buku Jadi</h6>
                                    </div>
                                    @if (is_null($data->buku_jadi))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->buku_jadi }}</p>
                                    @endif
                                </div>

                            </div>
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Judul Buku</h6>
                                    </div>
                                    @if (is_null($data->judul_buku))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->judul_buku }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Sub-Judul Buku</h6>
                                    </div>
                                    @if (is_null($data->sub_judul))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->sub_judul }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Penulis</h6>
                                    </div>
                                    @if (is_null($data->penulis))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->penulis }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Penerbit</h6>
                                    </div>
                                    @if (is_null($data->penerbit))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->penerbit }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Edisi/Cetakan</h6>
                                    </div>
                                    @if (is_null($data->edisi_cetakan))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->edisi_cetakan }}</p>
                                    @endif

                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kelompok Buku</h6>
                                    </div>
                                    @if (is_null($data->kelompok_buku))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->kelompok_buku }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Format Buku</h6>
                                    </div>
                                    @if (is_null($data->format_buku))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->format_buku }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jumlah Halaman</h6>
                                    </div>
                                    @if (is_null($data->jumlah_halaman))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->jumlah_halaman }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jumlah Cetak</h6>
                                    </div>
                                    @if (is_null($data->jumlah_cetak))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->jumlah_cetak }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Perlengkapan</h6>
                                    </div>
                                    @if (is_null($data->perlengkapan))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->perlengkapan }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kertas Isi</h6>
                                    </div>
                                    @if (is_null($data->kertas_isi))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->kertas_isi }}</p>
                                    @endif

                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Warna Isi</h6>
                                    </div>
                                    @if (is_null($data->warna_isi))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->warna_isi }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kertas Cover</h6>
                                    </div>
                                    @if (is_null($data->kertas_cover))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->kertas_cover }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Warna Cover</h6>
                                    </div>
                                    @if (is_null($data->warna_cover))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->warna_cover }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Efek Cover</h6>
                                    </div>
                                    @if (is_null($data->efek_cover))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->efek_cover }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jenis Cover</h6>
                                    </div>
                                    @if (is_null($data->jenis_cover))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->jenis_cover }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jahit Kawat</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if($data->jahit_kawat == 1)
                                            Iya
                                        @else
                                            Tidak
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jahit Benang</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if($data->jahit_benang == 1)
                                            Iya
                                        @else
                                            Tidak
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Bending</h6>
                                    </div>
                                    @if (is_null($data->bending))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->bending }}</p>
                                    @endif
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Keterangan</h6>
                                    </div>
                                    @if (is_null($data->keterangan))
                                        <p class="mb-1 text-monospace">-</p>
                                    @else
                                        <p class="mb-1 text-monospace">{{ $data->keterangan }}</p>
                                    @endif

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



@include('produksi.include.modal_decline')

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
<script src="{{url('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
@endsection

@section('jsNeeded')
<script>
    $(document).ready(function() {
        $('#btn-decline').on('click', function() {
            $('#titleModal').html('Keterangan Penolakan');
            $('#id').val($(this).data('id'));
            $('#modalDecline').modal('show');
        });
    });
</script>
@endsection

@yield('jsNeededForm')


