@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
<link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
@endsection

@section('cssNeeded')
<style>

</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('prades.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit/Buat Penerbitan Pracetak Desainer Proses</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="row card-header justify-content-between">
                        <div class="col-auto">
                            @switch($data->status)
                            @case('Antrian')
                            <i class="far fa-circle" style="color:#34395E;"></i>
                            Status Progress:
                            <span class="badge" style="background:#34395E;color:white">{{ $data->status }}</span>
                            @break

                            @case('Pending')
                            <i class="far fa-circle text-danger"></i>
                            Status Progress:
                            <span class="badge badge-danger">{{ $data->status }}</span>
                            @break

                            @case('Proses')
                            <i class="far fa-circle text-success"></i>
                            Status Progress:
                            <span class="badge badge-success">{{ $data->status }}</span>
                            @break

                            @case('Selesai')
                            <i class="far fa-circle text-dark"></i>
                            Status Progress:
                            <span class="badge badge-light">{{ $data->status }}</span>
                            @break

                            @case('Revisi')
                            <i class="far fa-circle text-info"></i>
                            Status Progress:
                            <span class="badge badge-info">{{ $data->status }}</span>
                            @break
                            @endswitch
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle"></i>&nbsp;Proses Saat Ini:
                            @switch($data->proses_saat_ini)
                                @case('Antrian Pengajuan Desain')
                                    <span class="text-dark"> Antri Pengajuan Desain</span>
                                    @break
                                @case('Pengajuan Desain')
                                    <span class="text-dark"> Pengajuan Desain</span>
                                    @break
                                @case('Antrian Desain Back Cover')
                                    <span class="text-dark" class="text-dark"> Antri Desain Back Cover</span>
                                    @break
                                @case('Desain Back Cover')
                                    <span class="text-dark" class="text-dark"> Desain Back Cover</span>
                                    @break
                                @case('Approval Prodev')
                                    <span class="text-dark"> Approval Prodev</span>
                                    @break
                                @case('Antrian Koreksi')
                                    <span class="text-dark"> Antrian Koreksi</span>
                                    @break
                                @case('Koreksi')
                                    <span class="text-dark"> Koreksi</span>
                                    @break
                                @case('Siap Turcet')
                                    <span class="text-dark"> Siap Turcet</span>
                                    @break
                                @case('Turun Cetak')
                                    <span class="text-dark"> Turun Cetak</span>
                                    @break
                                @case('Desain Revisi')
                                    <span class="text-dark"> Desain Revisi</span>
                                    @break
                                @default
                                    <span class="text-danger"> Belum ada proses</span>
                                    @break
                            @endswitch
                            @if ($data->status == 'Revisi')
                            <br>
                                <button type="button" class="btn btn-warning" id="done-revision"
                                data-id="{{$data->id}}" data-judul="{{$data->judul_final}}" data-kode="{{$data->kode}}">
                                    <i class="fas fa-check"></i>&nbsp;Selesai Revisi
                                </button>
                            @endif
                        </div>

                    </div>
                    @if ($data->status == 'Proses' || $data->status == 'Revisi' ||
                    ($data->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk')))
                        @include('penerbitan.pracetak_desainer.include.edit_if')
                    @else
                        @include('penerbitan.pracetak_desainer.include.edit_else')
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('jsRequired')
<script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
@endsection


@section('jsNeeded')
<script src="{{ url('js/edit_pracetak_cover.js') }}"></script>
@endsection
