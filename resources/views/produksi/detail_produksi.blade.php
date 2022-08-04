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
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{url('vendors/flipbook/min_version/ipages.min.css')}}">
@endsection

@section('cssNeeded')
<style>
    .tb-naskah {
        font-size: 12px;
        border: 1px solid #ced4da;
    }
    .tb-naskah, .tb-naskah th, .tb-naskah td {
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
    .stamp {
        /* transform: rotate(12deg); */
        color: #555;
        font-size: 1rem;
        font-weight: 700;
        border: 0.25rem solid #555;
        display: inline-block;
        padding: 0.25rem 1rem;
        text-transform: uppercase;
        border-radius: 1rem;
        font-family: 'Courier';
        -webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
        -webkit-mask-size: 944px 604px;
        mix-blend-mode: multiply;
    }

    .is-nope {
        color: #D23;
        border: 0.5rem double #D23;
        transform: rotate(3deg);
            -webkit-mask-position: 2rem 3rem;
        font-size: 2rem;
    }

    .is-approved {
        color: #0A9928;
        border: 0.5rem double #0A9928;
        -webkit-mask-position: 13rem 6rem;
        transform: rotate(-14deg);
        -webkit-mask-position: 2rem 3rem;
        border-radius: 0;
    }

    .is-draft {
        color: #C4C4C4;
        border: 1rem double #C4C4C4;
        transform: rotate(-5deg);
        font-size: 6rem;
        font-family: "Open sans", Helvetica, Arial, sans-serif;
        border-radius: 0;
        padding: 0.5rem;
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('produksi.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Order Produksi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Data Order Produksi&nbsp;
                            <span class="text-danger">
                                @if ($data->status_cetak == '3')
                                    @if (Gate::allows('do_approval', 'persetujuan-manpen'))
                                    *Jabatan Anda tidak memiliki akses untuk data ini.
                                    @endif
                                @else
                                    @if (Gate::allows('do_approval', 'persetujuan-manstok'))
                                    *Jabatan Anda tidak memiliki akses untuk data ini.
                                    @endif
                                @endif
                            </span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-auto mr-auto">
                                <div class="mb-4">
                                {{-- APPROVAL ORDER PRODUKSI --}}
                                @if ($prod_penyetujuan->isEmpty())
                                    @if ($data->status_cetak == '1' OR $data->status_cetak == '2')
                                        @if (Gate::allows('do_approval', 'persetujuan-manpen'))
                                        <button type="button" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Disetujui</button>
                                        @endif
                                    @elseif ($data->status_cetak == '3')
                                        @if (Gate::allows('do_approval', 'persetujuan-manstok'))
                                            <button type="button" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Disetujui</button>
                                        @endif
                                    @endif
                                    @if (Gate::allows('do_approval', 'persetujuan-dirop'))
                                        <button type="button" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Disetujui</button>
                                    @elseif (Gate::allows('do_approval', 'persetujuan-dirke'))
                                        <button type="button" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Disetujui</button>
                                    @elseif (Gate::allows('do_approval', 'persetujuan-dirut'))
                                        <button type="button" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Disetujui</button>
                                    @endif
                                @endif
                                {{-- Decline ORDER PRODUKSI --}}
                                @if ($prod_penyetujuan->isEmpty())
                                    @if (Gate::allows('do_decline', 'penolakan-dirop'))
                                        <button type="button" class="btn btn-danger" id="btn-decline"><i class="fas fa-times" data-toggle="modal" data-target="#modalDecline"></i>&nbsp;Ditolak</button>
                                    @elseif (Gate::allows('do_decline', 'penolakan-dirke'))
                                        <button type="button" class="btn btn-danger" id="btn-decline"><i class="fas fa-times" data-toggle="modal" data-target="#modalDecline"></i>&nbsp;Ditolak</button>
                                    @elseif (Gate::allows('do_decline', 'penolakan-dirut'))
                                        <button type="button" class="btn btn-danger" id="btn-decline"><i class="fas fa-times" data-toggle="modal" data-target="#modalDecline"></i>&nbsp;Ditolak</button>
                                    @endif
                                @endif
                                </div>
                            </div>
                            <div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <div class="user-item">
                                        <div class="user-details">
                                        @if ($data->status_cetak == '3')
                                            <div class="user-name">Manajer Stok:</div>
                                            <div class="text-job text-muted">
                                                @if(is_null($p_mstok))
                                                &nbsp;
                                                @elseif($p_mstok->action == '1')
                                                    <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                                @elseif($p_mstok->action == '2')
                                                    <i class="fas fa-times-circle"></i>&nbsp;Telah Ditolak
                                                @endif
                                            </div>
                                            <div class="user-cta">
                                                <span><u>{{ $dirut->nama }}</u></span>
                                            </div>
                                        @else
                                            <div class="user-name">Manajer Penerbitan:</div>
                                            <div class="text-job text-muted">
                                                @if (is_null($p_mp))
                                                    &nbsp;
                                                @elseif($p_mp->action == '1')
                                                    <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                                @elseif($p_mp->action == '2')
                                                    <i class="fas fa-times-circle"></i>&nbsp;Telah Ditolak
                                                @endif
                                            </div>
                                            <div class="user-cta">
                                                <span class="text-underline"><u>{{ $m_penerbitan->nama }}</u></span>
                                            </div>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <div class="user-item">
                                        <div class="user-details">
                                          <div class="user-name">Direktur Operasional:</div>
                                          <div class="text-job text-muted">
                                            @if(is_null($p_dirop))
                                                &nbsp;
                                            @elseif($p_dirop->action == '1')
                                                <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                            @elseif($p_dirop->action == '2')
                                                <i class="fas fa-times-circle"></i>&nbsp;Telah Ditolak
                                            @endif
                                          </div>
                                          <div class="user-cta">
                                            <span><u>{{ $dirop->nama }}</u></span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div><div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <div class="user-item">
                                        <div class="user-details">
                                          <div class="user-name">Direktur Keuangan:</div>
                                          <div class="text-job text-muted">
                                            @if(is_null($p_dirke))
                                                &nbsp;
                                            @elseif($p_dirke->action == '1')
                                                <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                            @elseif($p_dirke->action == '2')
                                                <i class="fas fa-times-circle"></i>&nbsp;Telah Ditolak
                                            @endif
                                          </div>
                                          <div class="user-cta">
                                            <span><u>{{ $dirke->nama }}</u></span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div><div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <div class="user-item">
                                        <div class="user-details">
                                          <div class="user-name">Direktur Utama:</div>
                                          <div class="text-job text-muted">
                                            @if(is_null($p_dirut))
                                                &nbsp;
                                            @elseif($p_dirut->action == '1')
                                                <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                            @elseif($p_dirut->action == '2')
                                                <i class="fas fa-times-circle"></i>&nbsp;Telah Ditolak
                                            @endif
                                          </div>
                                          <div class="user-cta">
                                            <span><u>{{ $dirut->nama }}</u></span>
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
                                        <h6 class="mb-1">Kode Order</h6>
                                    </div>
                                    <input type="hidden" id="id" value="{{ $data->id }}">
                                    <input type="hidden" id="kode_order" value="{{ $data->kode_order }}">
                                    <p class="mb-1 text-monospace">{{ $data->kode_order }}</p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Status Cetak</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->status_cetak == 1)
                                            Buku Baru
                                        @elseif ($data->status_cetak == 2)
                                            Cetak Ulang Revisi
                                        @elseif ($data->status_cetak == 3)
                                            Cetak Ulang
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Pilihan Terbit</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->pilihan_terbit == '1')
                                            Cetak Fisik
                                        @elseif ($data->pilihan_terbit == '2')
                                            E-Book
                                        @elseif ($data->pilihan_terbit == '3')
                                            Cetak Fisik + E-Book
                                        @endif
                                    </p>
                                </div>
                                @if ($data->tipe_order != '2')
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Platform Digital</h6>
                                    </div>
                                    <ul class="list-unstyled list-inline">
                                        @foreach (json_decode($data->platform_digital) as $pDigital)
                                            <li class="list-inline-item">
                                            <p class="mb-1 text-monospace">
                                                <i class="fas fa-check text-success"></i>
                                                <span>{{ $pDigital }}</span>
                                            </p>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                                @endif
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Urgent</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->urgent == 1)
                                            Ya
                                        @else
                                            Tidak
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Status Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if ($data->status_buku == 1)
                                            Reguler
                                        @else
                                            MOU
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">ISBN</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->isbn))
                                        -
                                    @else
                                        {{ $data->isbn }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">E-ISBN</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->eisbn))
                                        -
                                    @else
                                        {{ $data->eisbn }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tahun Terbit</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (is_null($data->tahun_terbit))
                                            -
                                        @else
                                            {{ $data->tahun_terbit }}
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tanggal Permintaan Jadi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->tgl_permintaan_jadi))
                                            {{ date('d F Y', strtotime($data->tgl_permintaan_jadi)) }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Buku Contoh</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->buku_contoh))
                                        -
                                    @else
                                        {{ $data->buku_contoh }}
                                    @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Judul Buku</h6>
                                    </div>
                                    <input type="hidden" id="judul_buku" value="{{$data->judul_buku}}">
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->judul_buku))
                                        -
                                    @else
                                        {{ $data->judul_buku }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Sub-Judul Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->sub_judul))
                                        -
                                    @else
                                        {{ $data->sub_judul }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Penulis</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->penulis))
                                        -
                                    @else
                                        {{ $data->penulis }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Penerbit</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->penerbit))
                                        -
                                    @else
                                        {{ $data->penerbit }}
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
                                        {{ $data->imprint }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Edisi/Cetakan</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->edisi_cetakan))
                                        -
                                    @else
                                        {{ $data->edisi_cetakan }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kelompok Buku</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->kelompok_buku))
                                        -
                                    @else
                                        {{ $data->kelompok_buku }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Posisi Layout</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if ($data->posisi_layout == '1')
                                        Potrait
                                    @elseif ($data->posisi_layout == '2')
                                        Landscape
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Dami</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->dami))
                                        -
                                    @else
                                        {{ $data->dami }}
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
                                        {{ $data->format_buku }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jumlah Halaman</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->jumlah_halaman))
                                        -
                                    @else
                                        {{ $data->jumlah_halaman }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Buku Jadi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->buku_jadi))
                                        -
                                    @else
                                        {{ $data->buku_jadi }}
                                    @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kertas Isi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->kertas_isi))
                                        -
                                    @else
                                        {{ $data->kertas_isi }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Warna Isi</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->warna_isi))
                                        -
                                    @else
                                        {{ $data->warna_isi }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kertas Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->kertas_cover))
                                        -
                                    @else
                                        {{ $data->kertas_cover }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Warna Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->warna_cover))
                                        -
                                    @else
                                        {{ $data->warna_cover }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Efek Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->efek_cover))
                                        -
                                    @else
                                        {{ $data->efek_cover }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jenis Cover</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->jenis_cover))
                                        -
                                    @else
                                        {{ $data->jenis_cover }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jilid</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if($data->jilid == '1')
                                            Bending
                                        @elseif ($data->jilid == '2')
                                            Jahit Kawat
                                        @elseif ($data->jilid == '3')
                                            Jahit Benang
                                        @elseif ($data->jilid == '4')
                                            Hardcover
                                        @endif
                                    </p>
                                </div>
                                @if ($data->jilid == '1')
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Ukuran Jilid Bending</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if(is_null($data->ukuran_jilid_bending))
                                            -
                                        @else
                                            {{ $data->ukuran_jilid_bending }}
                                        @endif
                                    </p>
                                </div>
                                @endif
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Jumlah Cetak</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->jumlah_cetak))
                                        -
                                    @else
                                        {{ $data->jumlah_cetak.' eks' }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">SPP</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->spp))
                                        -
                                    @else
                                        {{ $data->spp }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Keterangan</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->keterangan))
                                        -
                                    @else
                                        {{ $data->keterangan }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Perlengkapan</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->perlengkapan))
                                        -
                                    @else
                                        {{ $data->perlengkapan }}
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



@include('produksi.include.modal_decline')

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
<script src="{{url('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
@endsection

@section('jsNeeded')
<script src="{{url('js/decline_order.js')}}"></script>
@endsection

@yield('jsNeededForm')


