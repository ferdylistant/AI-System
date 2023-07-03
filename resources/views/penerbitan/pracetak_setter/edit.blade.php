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
            <h1>Edit/Buat Penerbitan Pracetak Setter Proses</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{url('/')}}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{url('/penerbitan/pracetak/setter')}}">Data Penerbitan Pracetak Setter</a>
                </div>
                <div class="breadcrumb-item">
                    Edit/Buat Pracetak Setter Proses
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <h4 class="section-title">Data Edit/Buat Pracetak Setter Proses</h4>
                        <div class="row card-header justify-content-between">
                            <div class="col-6 text-left" id="status"></div>
                            <div class="col-6 text-right" id="proses_saat_ini"></div>
                        </div>
                        @include('penerbitan.pracetak_setter.include.edit_if')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="https://unpkg.com/imask"></script>
    <script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ url('vendors/js-skeleton-loader-master/index.js') }}"></script>
@endsection


@section('jsNeeded')
    <script src="{{ url('js/penerbitan/edit_pracetak_setter.js') }}" defer></script>
@endsection
