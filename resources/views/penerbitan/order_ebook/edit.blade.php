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
                <a href="{{ route('ebook.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Edit Order E-book</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-success">
                        <div class="card-header justify-content-between">
                            <div class="col-auto d-flex">
                                <h4>Form Penerbitan Order E-book</h4>
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
                        @if ($data->status == 'Proses' ||  ($data->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk')))
                            @include('penerbitan.order_ebook.include.edit_if')
                        @else
                            @include('penerbitan.order_ebook.include.edit_else')
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
    @if ($data->status == 'Proses' ||  ($data->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk')))
    <script src="https://unpkg.com/imask"></script>
    @endif
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
@endsection


@section('jsNeeded')
    <script src="{{ url('js/edit_ebook.js') }}"></script>
@endsection
