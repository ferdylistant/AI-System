@extends('layouts.app')
@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/flipbook/min_version/ipages.min.css') }}">
    <style>
        .scrollbar-deep-purple::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        .scrollbar-deep-purple::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        .scrollbar-deep-purple::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #6777EF;
        }

        .scrollbar-deep-purple {
            scrollbar-color: #6777EF #F5F5F5;
        }

        .bordered-deep-purple::-webkit-scrollbar-track {
            -webkit-box-shadow: none;
            border: 1px solid #6777EF;
        }

        .bordered-deep-purple::-webkit-scrollbar-thumb {
            -webkit-box-shadow: none;
        }

        .square::-webkit-scrollbar-track {
            border-radius: 0 !important;
        }

        .square::-webkit-scrollbar-thumb {
            border-radius: 0 !important;
        }

        .thin::-webkit-scrollbar {
            width: 6px;
        }

        .example-1 {
            position: relative;
            overflow-y: scroll;
            height: 200px;
        }
    </style>
@endsection

@section('content')

    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Detail Pracetak Setter</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-warning">
                        <div class="card-header justify-content-between">
                            <div class="col-auto d-flex">
                                <h4>Data Detail Pracetak Setter&nbsp;
                                    -
                                </h4>
                                @switch($data->status)
                                    @case('Antrian')
                                        <span class="badge" style="background:#34395E;color:white">{{ $data->status }}</span>
                                    @break

                                    @case('Pending')
                                        <span class="badge badge-danger">{{ $data->status }}</span>
                                    @break

                                    @case('Proses')
                                        <span class="badge badge-success">{{ $data->status }}</span>
                                    @break

                                    @case('Selesai')
                                        <span class="badge badge-light">{{ $data->status }}</span>
                                    @break

                                    @case('Revisi')
                                        <span class="badge badge-info">{{ $data->status }}</span>
                                    @break
                                @endswitch
                            </div>
                            @if (!$proof_revisi->isEmpty())
                                <?php $gate = Gate::allows('do_approval', 'approval-deskripsi-produk'); ?>
                                @if (auth()->id() == $data->pic_prodev || auth()->id() == 'be8d42fa88a14406ac201974963d9c1b' || $gate)
                                    &mdash;&mdash;
                                    <button type="button" class="btn btn-warning" id="btn-history-revision"
                                        data-id="{{ $data->id }}" data-kode="{{ $data->kode }}"
                                        data-judul="{{ $data->judul_final }}"><i class="fas fa-history"></i>&nbsp;Riwayat
                                        Proof</button>
                                @endif
                            @endif
                            @if (!$proof_revisi->isEmpty())
                                <?php $gate = Gate::allows('do_approval', 'approval-deskripsi-produk'); ?>
                                &mdash;&mdash;
                                <button type="button" class="btn btn-primary" id="btn-history-setkor"
                                    data-id="{{ $data->id }}" data-kode="{{ $data->kode }}"
                                    data-judul="{{ $data->judul_final }}"><i class="fas fa-history"></i>&nbsp;Riwayat
                                    Setter/Korektor</button>
                                &mdash;&mdash;
                            @endif
                            <div class="col-auto">
                                @if ($data->proses == '1')
                                    <span class="text-danger"><i class="fas fa-exclamation-circle"></i>&nbsp;
                                        @if (!is_null($data->mulai_proof) && is_null($data->selesai_proof))
                                            Sedang proses proof prodev
                                        @else
                                            Sedang proses pengerjaan
                                            {{ is_null($data->selesai_setting) ? 'setter' : 'korektor' }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if (auth()->id() == $data->pic_prodev)
                                @include('penerbitan.pracetak_setter.include.access_prodev')
                            @else
                                @include('penerbitan.pracetak_setter.include.access_setkor')
                            @endif
                            @include('penerbitan.pracetak_setter.include.data_edit')
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
    @include('penerbitan.pracetak_setter.include.modal_revisi')
    @include('penerbitan.pracetak_setter.include.modal_detailrevisi')
    @include('penerbitan.pracetak_setter.include.modal_riwayatsetkor')
@endsection
@section('jsRequired')
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    {{-- <script src="{{ url('vendors/flipbook/min_version/pdf.min.js') }}"></script>
<script src="{{ url('vendors/flipbook/min_version/jquery.ipages.min.js') }}"></script> --}}
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/done_setter.js') }}"></script>
@endsection

@yield('jsNeededForm')
