@extends('layouts.app')
@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/flipbook/min_version/ipages.min.css') }}">
@endsection

@section('content')

    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Detail Editing Proses</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-warning">
                        <div class="card-header justify-content-between">
                            <div class="col-auto d-flex">
                                <h4>Data Detail Editing Proses&nbsp;
                                    -
                                </h4>
                                <span id="status"></span>
                            </div>
                            <div class="col-auto" id="prosesPengerjaan"></div>
                        </div>
                        <div class="card-body">
                            <div class="row" id="proses"></div>
                            <div class="row mb-4">
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kode</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="kode"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Judul Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="judul_final"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Sub-Judul Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="sub_judul_final"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Bullet</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="bullet"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Penulis</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="penulis"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Nama Pena</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="nama_pena"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kelompok Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="nama"></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">

                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jml Halaman Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="jml_hal_perkiraan"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Isi Warna</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="isi_warna"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Isi Huruf</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="isi_huruf"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Sinopsis</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="sinopsis"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">PIC Prodev</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="pic_prodev"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Catatan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="catatan"></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Masuk Editing</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="tgl_masuk_editing"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Editor</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="editor"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Mulai Editor</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="tgl_mulai_edit"></p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Selesai Editor</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="tgl_selesai_edit"></p>
                                    </div>
                                    {{-- <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Copy Editor</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->copy_editor))
                                                -
                                            @else
                                                @foreach ($nama_copyeditor as $nc)
                                                    <span class="bullet"></span>
                                                    <a href="{{url('/manajemen-web/user/' . $nc->id)}}">{{ $nc->nama }}</a>
                                                    <br>
                                                @endforeach
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Mulai Copy Editor</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->tgl_mulai_copyeditor))
                                                -
                                            @else
                                                {{ Carbon\Carbon::parse($data->tgl_mulai_copyeditor)->translatedFormat('l d F Y, H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Selesai Copy Editor</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->tgl_selesai_copyeditor))
                                                -
                                            @else
                                                {{ Carbon\Carbon::parse($data->tgl_selesai_copyeditor)->translatedFormat('l d F Y, H:i') }}
                                            @endif
                                        </p>
                                    </div> --}}
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Bulan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace" id="bulan"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
    @include('penerbitan.editing.include.modal_decline')
    <style>
        #md_updateSubtimeline table {
            width: 100%;
        }

        #md_updateSubtimeline table tr th {
            text-align: center;
        }
    </style>

@endsection
@section('jsRequired')
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    {{-- <script src="{{ url('vendors/flipbook/min_version/pdf.min.js') }}"></script>
    <script src="{{ url('vendors/flipbook/min_version/jquery.ipages.min.js') }}"></script> --}}
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
@endsection

@section('jsNeeded')
<script src="{{url('js/done_editing.js')}}" defer></script>
@endsection

@yield('jsNeededForm')
