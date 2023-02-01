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
            <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
        </div>
        <h1>Detail Deskripsi Cover</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Data Deskripsi Cover&nbsp;
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
                            @endif
                    </div>
                    <div class="card-body">
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
                                    @if ((is_null($data->bullet)) || ($data->bullet == '[]'))
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
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Format Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (is_null($data->format_buku))
                                            -
                                        @else
                                            {{$format_buku}}&nbsp;cm
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
                                        {{ $imprint }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jilid</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->jilid))
                                        -
                                    @else
                                        {{ $data->jilid }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Warna</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->warna))
                                        -
                                    @else
                                        {{ $data->warna }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tipografi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->tipografi))
                                        -
                                    @else
                                        {{ $data->tipografi }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Finishing Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if ((is_null($data->finishing_cover)) || ($data->finishing_cover == []))
                                        -
                                    @else
                                        @foreach (json_decode($data->finishing_cover,true) as $fc)
                                            <span class="bullet"></span>{{ $fc }}<br>
                                        @endforeach
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Contoh Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->contoh_cover))
                                        -
                                    @else
                                        <a href="{{ $data->contoh_cover }}">{{ $data->contoh_cover }}</a>
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Usulan Desainer</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->desainer))
                                        -
                                    @else
                                    <a href="{{ url('/manajemen-web/user/' . $desainer->id) }}">{{ $desainer->nama }}</a>
                                    @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
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
                                        <h6 class="mb-1">Deskripsi Front Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->des_front_cover))
                                        -
                                    @else
                                        {{ $data->des_front_cover }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Deskripsi Back Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->des_back_cover))
                                        -
                                    @else
                                        {{ $data->des_back_cover }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">PIC Prodev</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (is_null($data->pic_prodev))
                                        -
                                        @else
                                        <a href="{{ url('/manajemen-web/user/' . $pic->id) }}">{{ $pic->nama }}</a>
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
                                        {{ Carbon\Carbon::parse($data->tgl_deskripsi)->translatedFormat('l d F Y, H:i') }}
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
@endsection

@yield('jsNeededForm')


