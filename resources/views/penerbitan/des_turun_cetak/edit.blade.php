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
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Edit/Buat Penerbitan Deskripsi Turun Cetak</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <div class="card-header-action">
                                @if ($data->status == 'Antrian')
                                    <i class="far fa-circle" style="color:#34395E;"></i>
                                    Status Progress:
                                    <span class="badge" style="background:#34395E;color:white">{{ $data->status }}</span>
                                @elseif ($data->status == 'Pending')
                                    <i class="far fa-circle text-danger"></i>
                                    Status Progress:
                                    <span class="badge badge-danger">{{ $data->status }}</span>
                                @elseif ($data->status == 'Proses')
                                    <i class="far fa-circle text-success"></i>
                                    Status Progress:
                                    <span class="badge badge-success">{{ $data->status }}</span>
                                @elseif ($data->status == 'Selesai')
                                    <i class="far fa-circle text-dark"></i>
                                    Status Progress:
                                    <span class="badge badge-light">{{ $data->status }}</span>
                                @endif
                            </div>
                        </div>
                        @if ($data->status == 'Proses' ||
                            ($data->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk')))
                            @include('penerbitan.des_turun_cetak.include.edit_if')
                        @else
                            @include('penerbitan.des_turun_cetak.include.edit_else')
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
    <script src="{{ url('js/edit_deskripsi_turun_cetak.js') }}"></script>
@endsection
