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
            <a href="{{ route('produksi.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Order Produksi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Data Order Produksi</h4>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-between">
                            @if (Gate::allows('do_approval', 'menyetujui-order'))
                            <div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <button type="button" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Disetujui</button>
                                    <button type="button" class="btn btn-danger" id="btn-decline"><i class="fas fa-times" data-id="" data-toggle="modal" data-target="#modalDecline"></i>&nbsp;Ditolak</button>
                                </div>
                            </div>
                            @endif
                            <div class="col-auto">
                                <div class="mb-4">
                                    Persetujuan:
                                    @if ($data->status_penyetujuan == '1')
                                        <span class="badge badge-danger">Pending</span>
                                    @elseif ($data->status_penyetujuan == '2')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif ($data->status_penyetujuan == '3')
                                        <span class="badge badge-dark">Ditolak</span>
                                    @endif

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
                                    <p class="mb-1 text-monospace">{{ $data->kode_order }}</p>
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
                                        <h6 class="mb-1">Pilihan Terbit</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->pilihan_terbit == '1')
                                            Cetak Fisik
                                        @elseif ($data->pilihan_terbit == '2')
                                            E-Book
                                        @elseif ($data->pilihan_terbit == '3')
                                            Cetak Fisik + E-Book
                                        @endif
                                    </p>
                                </div>
                                @if ($data->tipe_order != '2')
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
                                @endif
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
                                        <h6 class="mb-1">Status Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->status_buku == 1)
                                            Reguler
                                        @else
                                            MOU
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
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">E-ISBN</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->eisbn))
                                        -
                                    @else
                                        {{ $data->eisbn }}
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
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tanggal Permintaan Jadi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->tgl_permintaan_jadi))
                                            {{ date('d F Y', strtotime($data->tgl_permintaan_jadi)) }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Buku Contoh</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->buku_contoh))
                                        -
                                    @else
                                        {{ $data->buku_contoh }}
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
                                        <h6 class="mb-1">Penerbit</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->penerbit))
                                        -
                                    @else
                                        {{ $data->penerbit }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Imprint</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->imprint))
                                        -
                                    @else
                                        {{ $data->imprint }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Edisi/Cetakan</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->edisi_cetakan))
                                        -
                                    @else
                                        {{ $data->edisi_cetakan }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kelompok Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->kelompok_buku))
                                        -
                                    @else
                                        {{ $data->kelompok_buku }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Posisi Layout</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if ($data->posisi_layout == '1')
                                        Potrait
                                    @elseif ($data->posisi_layout == '2')
                                        Landscape
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Dami</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->dami))
                                        -
                                    @else
                                        {{ $data->dami }}
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
                            </div>
                            <div class="col-12 col-md-4">
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
                                        <h6 class="mb-1">Warna Isi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->warna_isi))
                                        -
                                    @else
                                        {{ $data->warna_isi }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kertas Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->kertas_cover))
                                        -
                                    @else
                                        {{ $data->kertas_cover }}
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
                                        <h6 class="mb-1">Jenis Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->jenis_cover))
                                        -
                                    @else
                                        {{ $data->jenis_cover }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jilid</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if($data->jilid == '1')
                                            Bending
                                        @elseif ($data->jilid == '2')
                                            Jahit Kawat
                                        @elseif ($data->jilid == '3')
                                            Jahit Benang
                                        @elseif ($data->jilid == '4')
                                            Hardcover
                                        @endif
                                    </p>
                                </div>
                                @if ($data->jilid == '1')
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Ukuran Jilid Bending</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if(is_null($data->ukuran_jilid_bending))
                                            -
                                        @else
                                            {{ $data->ukuran_jilid_bending }}
                                        @endif
                                    </p>
                                </div>
                                @endif
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jumlah Cetak</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->jumlah_cetak))
                                        -
                                    @else
                                        {{ $data->jumlah_cetak.' eks' }}
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


