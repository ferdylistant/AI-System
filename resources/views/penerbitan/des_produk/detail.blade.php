@extends('layouts.app')
@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{url('vendors/flipbook/min_version/ipages.min.css')}}">
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('despro.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Deskripsi Produk</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Data Deskripsi Produk&nbsp;
                            -
                        </h4>
                            @if ($data->status == 'Antrian')
                                <span class="badge" style="background:#34395E;color:white">{{$data->status}}</span>
                            @elseif ($data->status == 'Pending')
                                <span class="badge badge-danger">{{$data->status}}</span>
                            @elseif ($data->status == 'Proses')
                                <span class="badge badge-success">{{$data->status}}</span>
                            @elseif ($data->status == 'Selesai')
                                <span class="badge badge-light">{{$data->status}}</span>
                            @elseif ($data->status == 'Revisi')
                                <span class="badge badge-info">{{$data->status}}</span>
                            @elseif ($data->status == 'Acc')
                                <span class="badge badge-light">{{$data->status}}</span>
                            @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- APROVAL --}}
                            @if (Gate::allows('do_approval','approval-deskripsi-produk'))
                                @if ($data->status == 'Selesai')
                            <div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Setujui</button>
                                    <button type="button" class="btn btn-danger" id="btn-revision"
                                    data-id="{{$data->id}}" data-kode="{{$data->kode}}" data-judul_asli="{{$data->judul_asli}}" data-status="{{$data->status}}"
                                    ><i class="fas fa-tools"></i>&nbsp;Revisi</button>
                                </div>
                            </div>
                            @endif
                        @endif
                            <div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <div class="user-item">
                                        <div class="user-details">
                                            <div class="user-name">Yogyakarta, {{Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y')}}</div>
                                                @if($data->status == 'Acc')
                                                <div class="text-job text-success">
                                                    <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                                </div>
                                                @elseif($data->status == 'Revisi')
                                                <div class="text-job text-danger">
                                                    <a href="javascript:void(0)" id="btn-pending" class="text-danger" data-status="Revisi" data-action_gm="{{$data->action_gm}}"
                                                    data-alasan_revisi="{{$data->alasan_revisi}}" data-deadline_revisi="{{$data->deadline_revisi}}">
                                                    <i class="fas fa-tools"></i>&nbsp;Direvisi</a>
                                                </div>
                                                @else
                                                <div class="text-job text-muted">
                                                    &nbsp;
                                                </div>
                                                @endif
                                            <div class="user-cta">
                                                <span><u>GM. Penerbitan</u></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        <h6 class="mb-1">Judul Asli</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (is_null($data->judul_asli))
                                            -
                                        @else
                                            {{$data->judul_asli}}
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Penulis</h6>
                                    </div>
                                    <ul class="list-unstyled list-inline">
                                        @foreach ($penulis as $pen)
                                            <li class="list-inline-item">
                                            <p class="mb-1 text-monospace">
                                                <i class="fas fa-check text-dark"></i>
                                                <span>{{ $pen->nama }}</span>
                                            </p>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kelompok Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (is_null($data->nama))
                                            -
                                        @else
                                            {{$data->nama}}
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
                                            {{$data->format_buku}}&nbsp;cm
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jml Halaman Perkiraan</h6>
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
                                        <h6 class="mb-1">Kelengkapan</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->kelengkapan))
                                        -
                                    @else
                                        {{ $data->kelengkapan }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Usulan Editor</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($editor))
                                        -
                                    @else
                                        {{ $editor->nama }}
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
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tanggal Deskripsi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->tgl_deskripsi))
                                        -
                                    @else
                                        {{ Carbon\Carbon::parse($data->tgl_deskripsi)->translatedFormat('d F Y') }}
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
                                            {{ $pic->nama }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">

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
                                        <h6 class="mb-1">Judul Final</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->judul_final))
                                        -
                                    @else
                                        @if (Gate::allows('do_approval','approval-deskripsi-produk'))
                                            @if ($data->status == 'Proses')
                                            <a href="javascript:void(0)" id="btn-edit-tgl-upload" class="text-primary" data-toggle="tooltip" data-placement="bottom" title="Edit data">{{ Carbon\Carbon::parse($data->tgl_upload)->translatedFormat('d F Y') }}</a>
                                            <div class="input-group" id="edit-tgl-upload" style="display: none;">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                                </div>
                                                <input type="hidden" id="historyTgl" value="{{$data->tgl_upload}}">
                                                <input type="text" class="form-control datepicker" id="upTglUpload" value="{{ Carbon\Carbon::parse($data->tgl_upload)->translatedFormat('d F Y') }}" placeholder="Tanggal Upload" readonly required>
                                                <button type="button" class="close" aria-label="Close">

                                                    <span aria-hidden="true">&times;</span>

                                                </button>
                                            </div>
                                            @else
                                            {{ $data->judul_final }}
                                            @endif
                                        @else
                                            {{ $data->judul_final }}
                                        @endif
                                    @endif
                                    </p>
                                </div>
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
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Alternatif Judul</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->alt_judul))
                                        -
                                    @else
                                        @foreach (json_decode($data->alt_judul) as $alt)
                                        <p>-{{ $alt }}</p>

                                        @endforeach
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



@include('penerbitan.des_produk.include.modal_revisi')

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
@endsection

@section('jsNeeded')
<script src="{{url('js/approval_despro.js')}}"></script>
<script src="{{url('js/revisi_despro.js')}}"></script>
@endsection

@yield('jsNeededForm')


