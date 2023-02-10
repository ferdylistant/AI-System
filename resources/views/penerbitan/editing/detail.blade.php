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
                        <div class="card-header">
                            <h4>Data Detail Editing Proses&nbsp;
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
                            @endswitch
                            <div class="col">
                                @if ($data->proses == '1')
                                    <span class="text-danger"><i class="fas fa-exclamation-circle"></i>&nbsp;Sedang proses
                                            pengerjaan {{is_null($data->tgl_selesai_edit)?'editor':'copy editor'}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if ($data->proses == '1')
                                    <?php
                                    $label = "editor";
                                    // $label = is_null($data->tgl_selesai_edit)?"editor":"copyeditor";
                                    $dataRole  = $data->editor;
                                    // $dataRole  = is_null($data->tgl_selesai_edit)?$data->editor:$data->copy_editor;
                                    ?>
                                    @switch($data->jalur_buku)
                                        @case('Reguler')
                                            @foreach (json_decode($dataRole, true) as $edt)
                                                @if (auth()->id() == $edt)
                                                    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-editing-reguler'))
                                                        <div class="col-auto">
                                                            <div class="mb-4">
                                                                <button type="submit" class="btn btn-success"
                                                                    id="btn-done-editing" data-id="{{ $data->id }}"
                                                                    data-kode="{{ $data->kode }}" data-autor="{{$label}}">
                                                                    <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @break

                                        @case('MoU')
                                            @foreach (json_decode($dataRole) as $edt)
                                                @if (auth()->id() == $edt)
                                                    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-editing-mou'))
                                                        <div class="col-auto">
                                                            <div class="mb-4">
                                                                <button type="submit" class="btn btn-success"
                                                                    id="btn-done-editing" data-id="{{ $data->id }}"
                                                                    data-kode="{{ $data->kode }}" data-autor="{{$label}}">
                                                                    <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @break

                                        @case('MoU-Reguler')
                                            @foreach (json_decode($dataRole) as $edt)
                                                @if (auth()->id() == $edt)
                                                    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-editing-reguler') ||
                                                        Gate::allows('do_create', 'otorisasi-'.$label.'-editing-mou'))
                                                        <div class="col-auto">
                                                            <div class="mb-4">
                                                                <button type="submit" class="btn btn-success"
                                                                    id="btn-done-editing" data-id="{{ $data->id }}"
                                                                    data-kode="{{ $data->kode }}" data-autor="{{$label}}">
                                                                    <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @break

                                        @case('SMK/NonSMK')
                                            @foreach (json_decode($dataRole) as $edt)
                                                @if (auth()->id() == $edt)
                                                    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-editing-smk'))
                                                        <div class="col-auto">
                                                            <div class="mb-4">
                                                                <button type="submit" class="btn btn-success"
                                                                    id="btn-done-editing" data-id="{{ $data->id }}"
                                                                    data-kode="{{ $data->kode }}" data-autor="{{$label}}">
                                                                    <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @break

                                        @case('Pro Literasi')
                                            @foreach (json_decode($dataRole) as $edt)
                                                @if (auth()->id() == $edt)
                                                    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-editing-reguler') ||
                                                        Gate::allows('do_create', 'otorisasi-editor-editing-mou'))
                                                    @endif
                                                @endif
                                            @endforeach
                                        @break
                                    @endswitch
                                @endif

                            </div>
                            <div class="row mb-4">
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kode</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">{{ $data->kode }}</p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Judul Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->judul_final))
                                                -
                                            @else
                                                {{ $data->judul_final }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Sub-Judul Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->sub_judul_final))
                                                -
                                            @else
                                                {{ $data->sub_judul_final }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Bullet</h6>
                                        </div>
                                        @if (is_null($data->bullet) || $data->bullet == '[]')
                                            <p class="mb-1 text-monospace">
                                                -
                                            </p>
                                        @else
                                            <ul class="list-unstyled list-inline">
                                                @foreach (json_decode($data->bullet) as $bullet)
                                                    <li class="list-inline-item">
                                                        <p class="mb-1 text-monospace">
                                                            <span class="bullet"></span>
                                                            <span>{{ $bullet }}</span>
                                                        </p>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Penulis</h6>
                                        </div>
                                        <ul class="list-unstyled list-inline">
                                            <li class="list-inline-item">
                                                @foreach ($penulis as $pen)
                                                    <p class="mb-1 text-monospace">
                                                        <span class="bullet"></span>
                                                        <a href="{{ url('/penerbitan/penulis/detail-penulis/' . $pen->id) }}">{{ $pen->nama }}</a>
                                                    </p>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Nama Pena</h6>
                                        </div>
                                        @if ((is_null($data->nama_pena)))
                                        <p class="mb-1 text-monospace">
                                                -
                                        </p>
                                        @else
                                        <ul class="list-unstyled list-inline">
                                            @foreach (json_decode($data->nama_pena) as $pen)
                                                <li class="list-inline-item">
                                                <p class="mb-1 text-monospace">
                                                    <span class="bullet"></span>
                                                    <span>{{ $pen }}</span>
                                                </p>
                                                </li>
                                            @endforeach

                                        </ul>
                                        @endif
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kelompok Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->nama))
                                                -
                                            @else
                                                {{ $data->nama }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">

                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jml Halaman Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->jml_hal_perkiraan))
                                                -
                                            @else
                                                {{ $data->jml_hal_perkiraan }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Isi Warna</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->isi_warna))
                                                -
                                            @else
                                                {{ $data->isi_warna }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Isi Huruf</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->isi_huruf))
                                                -
                                            @else
                                                {{ $data->isi_huruf }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Sinopsis</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->sinopsis))
                                                -
                                            @else
                                                {{ $data->sinopsis }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">PIC Prodev</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($pic))
                                                -
                                            @else
                                            <a href="{{ url('/manajemen-web/user/' . $pic->id) }}">{{ $pic->nama }}</a>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Catatan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->catatan))
                                                -
                                            @else
                                                {{ $data->catatan }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Masuk Editing</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->tgl_masuk_editing))
                                                -
                                            @else
                                                {{ Carbon\Carbon::parse($data->tgl_masuk_editing)->translatedFormat('l d F Y, H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Editor</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->editor))
                                                -
                                            @else
                                                @foreach ($nama_editor as $ne)
                                                    <span class="bullet"></span>
                                                    <a href="{{url('/manajemen-web/user/' . $ne->id)}}">{{ $ne->nama }}</a>
                                                    <br>
                                                @endforeach
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Mulai Editor</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->tgl_mulai_edit))
                                                -
                                            @else
                                                {{ Carbon\Carbon::parse($data->tgl_mulai_edit)->translatedFormat('l d F Y, H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Selesai Editor</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->tgl_selesai_edit))
                                                -
                                            @else
                                                {{ Carbon\Carbon::parse($data->tgl_selesai_edit)->translatedFormat('l d F Y, H:i') }}
                                            @endif
                                        </p>
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
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->bulan))
                                                -
                                            @else
                                                {{ Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y') }}
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
<script src="{{url('js/done_editing.js')}}"></script>
@endsection

@yield('jsNeededForm')
