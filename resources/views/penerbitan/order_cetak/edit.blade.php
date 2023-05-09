@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"
        integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg=="
        crossorigin="anonymous" />
@endsection

@section('cssNeeded')
<style>
    .bootstrap-tagsinput{

    width: 100%;

    }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Edit Order Cetak</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{url('/')}}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{url('/penerbitan/order-cetak')}}">Data Order Cetak</a>
                </div>
                <div class="breadcrumb-item">
                    Edit Order Cetak
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header justify-content-between">
                            <div class="col-auto d-flex">
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
                                <span class="bullet text-danger"></span> Kode order: <b>{{ $data->kode_order }}</b> (
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
                                @endforeach)
                            </div>
                        </div>
                        @if ($data->status == 'Proses' ||
                            ($data->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk')))
                            @include('penerbitan.order_cetak.include.edit_if')
                        @else
                            @include('penerbitan.order_cetak.include.edit_else')
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
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"
        integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg=="
        crossorigin="anonymous"></script>
    @endsection


    @section('jsNeeded')
    <script src="{{ url('js/edit_order_cetak.js') }}" defer></script>
@endsection
