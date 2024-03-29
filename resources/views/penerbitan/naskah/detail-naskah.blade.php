@extends('layouts.app')
<?php
/* #################### Catatan #####################
    keyword [
        pn1 = Penilaian Prodev,
        pn2 = Penilaian Editor/Setter (if requried)
        pn3 = Penilaian Pemasaran,
        pn4 = Penilaian Operasional
    ]
    ##################################################### */

// $arrPilihan_ = ["Baik", "Cukup", "Kurang"];
?>
@section('cssRequired')
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.css') }}">
    {{-- <link rel="stylesheet" href="{{ url('vendors/flipbook/min_version/ipages.min.css') }}"> --}}
@endsection

@section('cssNeeded')
    <style>
        .tb-naskah {
            font-size: 12px;
            border: 1px solid #ced4da;
        }

        .tb-naskah,
        .tb-naskah th,
        .tb-naskah td {
            height: auto !important;
            padding: 10px 15px 10px 15px !important;
        }

        .tb-naskah th {
            width: 35%;
            color: #868ba1;
            background-color: #E9ECEF;
        }

        .accordion-body {
            overflow: auto;
            max-height: 500px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Detail Naskah</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{url('/')}}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{url('/penerbitan/naskah')}}">Data Naskah</a>
                </div>
                <div class="breadcrumb-item">
                    Detail Naskah
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="section-title">Data Naskah</h4>
                            <div class="card-header-action">
                                <a class="btn btn-icon btn-dark" data-collapse="#detailNaskahCollapseCard" href="#">
                                    <i class="fas fa-minus"></i>
                                </a>
                            </div>
                        </div>
                        <div id="detailNaskahCollapseCard" class="collapse show" style="">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table tb-naskah">
                                        <tbody>
                                            <tr>
                                                <th>Kode naskah:</th>
                                                <td>{{ $naskah->kode }}</td>
                                            </tr>
                                            <tr>
                                                <th>Judul Asli:</th>
                                                <td>{{ $naskah->judul_asli }}</td>
                                            </tr>
                                            <tr>
                                                <th>Naskah Masuk:</th>
                                                <td>{{ $naskah->tanggal_masuk_naskah }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kelompok Buku:</th>
                                                <td>{{ $naskah->kelompok_buku }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jalur Buku:</th>
                                                <td>{{ $naskah->jalur_buku }}</td>
                                            </tr>
                                            <tr>
                                                <th>Rencana CD/QR Code:</th>
                                                <td>{{ $naskah->cdqr_code }}</td>
                                            </tr>
                                            <tr>
                                                <th>Urgent:</th>
                                                <td>{{ $naskah->urgent }}</td>
                                            </tr>
                                            <tr>
                                                <th>PIC Prodev</th>
                                                <td>
                                                    <a href="{{ url('manajemen-web/user/' . $naskah->pic_prodev) }}"
                                                        target="_blank">
                                                        {{ $naskah->npic_prodev }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Penulis</th>
                                                <td>
                                                    <ol style="padding-left: 15px !important;">
                                                        @foreach ($penulis as $p)
                                                            <li><a href="{{ url('penerbitan/penulis/detail-penulis/' . $p->id) }}"
                                                                    target="_blank">
                                                                    {{ $p->nama }}</a></li>
                                                        @endforeach
                                                    </ol>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Sumber Naskah:</th>
                                                <td>
                                                    @if (!is_null($naskah->sumber_naskah))
                                                        @foreach (json_decode($naskah->sumber_naskah) as $sn)
                                                            <span class="bullet"></span>
                                                            @switch($sn)
                                                                @case('SC')
                                                                    Soft Copy
                                                                @break

                                                                @case('HC')
                                                                    Hard Copy
                                                                @break
                                                            @endswitch
                                                        @endforeach
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                            @if (!is_null($naskah->url_file))
                                                <tr>
                                                    <th>URL File</th>
                                                    <td>
                                                        <a href="{{ $naskah->url_file }}" class="text-primary"
                                                            target="_blank">{{ $naskah->url_file }}</a>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($naskah->pic_prodev)
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="section-title">Penilaian Naskah</h4>
                                <div class="card-header-action">
                                    <a class="btn btn-icon btn-dark" data-collapse="#detailPenilaianCollapseCard" href="#">
                                        <i class="fas fa-minus"></i>
                                    </a>
                                </div>
                            </div>
                            <div id="detailPenilaianCollapseCard" class="collapse show" style="">
                                <div id="form_penilaian" class="card-body">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link {{ $startPn == 'guest' ? 'active' : ($startPn == 'prodev' ? 'active' : '') }}"
                                                id="prodev-tab" data-toggle="tab" data-penilaian="Prodev"
                                                data-naskahid="{!! $naskah->id !!}" href="#pn_Prodev" role="tab"
                                                aria-controls="prodev">Prodev&nbsp;<span
                                                    class="{{ $badgeDraf }}">{{ $badgeDrafText }}</span></a>

                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ in_array($startPn, ['editor', 'setter']) ? 'active' : '' }}"
                                                id="editset-tab" data-toggle="tab" data-penilaian="EditSet"
                                                data-naskahid="{!! $naskah->id !!}" href="#pn_EditSet" role="tab"
                                                aria-controls="editset">Editor/Setter</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ $startPn == 'mpenerbitan' ? 'active' : '' }}"
                                                id="mpenerbitan-tab" data-toggle="tab" data-penilaian="mPenerbitan"
                                                data-naskahid="{!! $naskah->id !!}" href="#pn_mPenerbitan" role="tab"
                                                aria-controls="mpenerbitan">M.Penerbitan</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ $startPn == 'mpemasaran' ? 'active' : '' }}"
                                                id="mpemasaran-tab" data-toggle="tab" data-penilaian="mPemasaran"
                                                data-naskahid="{!! $naskah->id !!}" href="#pn_mPemasaran" role="tab"
                                                aria-controls="mpemasaran">M.Pemasaran</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ $startPn == 'dpemasaran' ? 'active' : '' }}"
                                                id="dpemasaran-tab" data-toggle="tab" data-penilaian="dPemasaran"
                                                data-naskahid="{!! $naskah->id !!}" href="#pn_dPemasaran" role="tab"
                                                aria-controls="dpemasaran">D.Pemasaran</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link {{ $startPn == 'direksi' ? 'active' : '' }}"
                                                id="direksi-tab"data-toggle="tab" data-penilaian="Direksi"
                                                data-naskahid="{!! $naskah->id !!}" href="#pn_Direksi" role="tab"
                                                aria-controls="direksi">Direksi</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div class="tab-pane fade {{ $startPn == 'guest' ? 'show active' : ($startPn == 'prodev' ? 'show active' : '') }}"
                                            id="pn_Prodev" role="tabpanel" aria-labelledby="prodev-tab">
                                            <div class="col-12" id="Prodev"></div>
                                        </div>
                                        <div class="tab-pane fade {{ in_array($startPn, ['editor', 'setter']) ? 'show active' : '' }}"
                                            id="pn_EditSet" role="tabpanel" aria-labelledby="editset-tab">
                                            <div class="col-12" id="EditSet"></div>
                                        </div>
                                        <div class="tab-pane fade {{ $startPn == 'mpenerbitan' ? 'show active' : '' }}"
                                            id="pn_mPenerbitan" role="tabpanel" aria-labelledby="mpenerbitan-tab">
                                            <div class="col-12" id="mPenerbitan"></div>
                                        </div>
                                        <div class="tab-pane fade {{ $startPn == 'mpemasaran' ? 'show active' : '' }}"
                                            id="pn_mPemasaran" role="tabpanel" aria-labelledby="mpemasaran-tab">
                                            <div class="col-12" id="mPemasaran"></div>
                                        </div>
                                        <div class="tab-pane fade {{ $startPn == 'dpemasaran' ? 'show active' : '' }}"
                                            id="pn_dPemasaran" role="tabpanel" aria-labelledby="dpemasaran-tab">
                                            <div class="col-12" id="dPemasaran"></div>
                                        </div>
                                        <div class="tab-pane fade {{ $startPn == 'direksi' ? 'show active' : '' }}"
                                            id="pn_Direksi" role="tabpanel" aria-labelledby="direksi-tab">
                                            <div class="col-12" id="Direksi"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>
    @include('penerbitan.naskah.page.modal-history-penilaian')
@endsection

@section('jsRequired')
    <script src="{{ asset('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ asset('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    {{-- <script src="{{ asset('vendors/flipbook/min_version/pdf.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('vendors/flipbook/min_version/jquery.ipages.min.js') }}"></script> --}}
    <script src="{{ asset('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendors/js-skeleton-loader-master/index.js') }}"></script>
@endsection

@section('jsNeeded')
    <script src="{{ asset('js/penerbitan/penilaian_naskah.js') }}"></script>
@endsection

@yield('jsNeededForm')
