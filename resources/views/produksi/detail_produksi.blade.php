@extends('layouts.app')
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
        <h1>Detail Order Produksi Cetak</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Data Order Produksi&nbsp;
                            - {{Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y')}}
                            <span class="text-danger">
                                @if ($data->status_cetak == '3')
                                    @if (Gate::allows('do_approval', 'persetujuan-order-cetak'))
                                        @if ($prod_penyetujuan->m_penerbitan == auth()->id())
                                            *Jabatan Anda tidak memiliki akses untuk data ini.
                                        @endif
                                    @endif
                                @else
                                    @if (Gate::allows('do_approval', 'persetujuan-order-cetak'))
                                        @if ($prod_penyetujuan->m_stok == auth()->id())
                                            *Jabatan Anda tidak memiliki akses untuk data ini.
                                        @endif
                                    @endif
                                @endif
                            </span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="fadd_Approval">
                            <div class="row justify-content-between">
                                <div class="col-auto mr-auto">
                                    <div class="mb-4">
                                        @if (!is_null($data_penolakan))
                                                <input type="hidden" id="ketVal" value="{{$data_penolakan->ket_pending}}">
                                                <input type="hidden" id="pendingSampai" value="{{Carbon\Carbon::parse($data_penolakan->pending_sampai)->translatedFormat('d F Y')}}">
                                            @endif
                                    {{-- APPROVAL ORDER PRODUKSI --}}
                                    @if (Gate::allows('do_approval', 'persetujuan-order-cetak'))
                                        @if ($prod_penyetujuan->status_general == 'Proses')
                                            @if ($data->status_cetak == '1' OR $data->status_cetak == '2')
                                                @if ($prod_penyetujuan->m_penerbitan == auth()->id())
                                                    @if ($prod_penyetujuan->m_penerbitan_act == '1')
                                                        <button type="submit" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Setujui</button>
                                                    @endif
                                                @endif
                                            @elseif ($data->status_cetak == '3')
                                                @if ($prod_penyetujuan->m_stok == auth()->id())
                                                    @if ($prod_penyetujuan->m_stok_act == '1')
                                                        <button type="submit" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Setujui</button>
                                                    @endif
                                                @endif
                                            @endif
                                            @if ($prod_penyetujuan->d_operasional == auth()->id())
                                                @if ($prod_penyetujuan->d_operasional_act == '1')
                                                    <button type="submit" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Setujui</button>
                                                @endif
                                            @elseif ($prod_penyetujuan->d_keuangan == auth()->id())
                                                @if ($prod_penyetujuan->d_keuangan_act == '1')
                                                    <button type="submit" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Setujui</button>
                                                @endif
                                            @elseif ($prod_penyetujuan->d_utama == auth()->id())
                                                @if ($prod_penyetujuan->d_utama_act == '1')
                                                <button type="submit" class="btn btn-success" id="btn-approve"><i class="fas fa-check"></i>&nbsp;Setujui</button>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                    {{-- Decline ORDER PRODUKSI --}}
                                    @if (Gate::allows('do_decline', 'persetujuan-pending'))
                                            @if ($prod_penyetujuan->status_general == 'Proses')
                                                @if ($prod_penyetujuan->d_operasional == auth()->id())
                                                    @if ($prod_penyetujuan->d_operasional_act == '1')
                                                        <button type="button" class="btn btn-danger" id="btn-pending"><i class="fas fa-hourglass-start" data-toggle="modal" data-target="#modalPending"></i>&nbsp;Pending</button>
                                                    @endif
                                                @elseif ($prod_penyetujuan->d_keuangan == auth()->id())
                                                    @if ($prod_penyetujuan->d_keuangan_act == '1')
                                                        <button type="button" class="btn btn-danger" id="btn-pending"><i class="fas fa-hourglass-start" data-toggle="modal" data-target="#modalPending"></i>&nbsp;Pending</button>
                                                    @endif
                                                @elseif ($prod_penyetujuan->d_utama == auth()->id())
                                                    @if ($prod_penyetujuan->d_utama_act == '1')
                                                        <button type="button" class="btn btn-danger" id="btn-pending"><i class="fas fa-hourglass-start" data-toggle="modal" data-target="#modalPending"></i>&nbsp;Pending</button>
                                                    @endif
                                                @endif
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
                                                    @if($p_mstok->m_stok_act == '1')
                                                    <div class="text-job text-muted">
                                                    <i class="fas fa-exclamation-circle"></i>&nbsp;Belum ada tanggapan
                                                    </div>
                                                    @elseif($p_mstok->m_stok_act == '3')
                                                    <div class="text-job text-success">
                                                        <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                                    </div>
                                                    @elseif($p_mstok->m_stok_act == '2')
                                                    <div class="text-job text-danger">
                                                        <i class="fas fa-minus-circle"></i>&nbsp;Dipending
                                                    </div>
                                                    @endif
                                                <div class="user-cta">
                                                    <span><u>{{ $m_stok->nama }}</u></span>
                                                </div>
                                            @else
                                                <div class="user-name">Manajer Penerbitan:</div>
                                                    @if ($p_mp->m_penerbitan_act == '1')
                                                    <div class="text-job text-muted">
                                                        <i class="fas fa-exclamation-circle"></i>&nbsp;Belum ada tanggapan
                                                    </div>
                                                    @elseif($p_mp->m_penerbitan_act == '3')
                                                    <div class="text-job text-success">
                                                        <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                                    </div>
                                                    @endif
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
                                                @if($p_dirop->d_operasional_act == '1')
                                                <div class="text-job text-muted">
                                                    <i class="fas fa-exclamation-circle"></i>&nbsp;Belum ada tanggapan
                                                </div>
                                                @elseif($p_dirop->d_operasional_act == '3')
                                                <div class="text-job text-success">
                                                    <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                                </div>
                                                @elseif($p_dirop->d_operasional_act == '2')
                                                <div class="text-job text-muted">
                                                    <a href="javascript:void(0)" id="btn-pending" class="text-danger" data-toggle="modal" data-target="#modalDecline"><i class="fas fa-minus-circle"></i>&nbsp;Dipending</a>
                                                </div>
                                                @endif
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
                                                @if($p_dirke->d_keuangan_act == '1')
                                                <div class="text-job text-muted">
                                                    <i class="fas fa-exclamation-circle"></i>&nbsp;Belum ada tanggapan
                                                </div>
                                                @elseif($p_dirke->d_keuangan_act == '3')
                                                <div class="text-job text-success">
                                                    <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                                </div>
                                                @elseif($p_dirke->d_keuangan_act == '2')
                                                <div class="text-job text-muted">
                                                    <a href="javascript:void(0)" id="btn-pending" class="text-danger" data-toggle="modal" data-target="#modalDecline"><i class="fas fa-minus-circle"></i>&nbsp;Dipending</a>
                                                </div>
                                                @endif
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
                                                @if($p_dirut->d_utama_act == '1')
                                                <div class="text-job text-muted">
                                                    <i class="fas fa-exclamation-circle"></i>&nbsp;Belum ada tanggapan
                                                </div>
                                                @elseif($p_dirut->d_utama_act == '3')
                                                <div class="text-job text-success">
                                                    <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                                </div>
                                                @elseif($p_dirut->d_utama_act == '2')
                                                <div class="text-job text-muted">
                                                    <a href="javascript:void(0)" id="btn-pending" class="text-danger" data-toggle="modal" data-target="#modalDecline"><i class="fas fa-minus-circle"></i>&nbsp;Dipending</a>
                                                </div>
                                                @endif
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
                                        <input type="hidden" id="id" name="id" value="{{ $id }}">
                                        <input type="hidden" id="kode_order" name="kode_order" value="{{ $data->kode_order }}">
                                        <p class="mb-1 text-monospace">{{ $data->kode_order }}</p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tipe Order</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if ($data->tipe_order == '1')
                                                Buku Umum
                                            @elseif ($data->tipe_order == '2')
                                                Buku Rohani
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jenis Mesin</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if ($data->jenis_mesin == '1')
                                                POD
                                            @elseif ($data->jenis_mesin == '2')
                                                Mesin Besar
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Status Cetak</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            <input type="hidden" id="statusCetak" name="status_cetak" value="{{$data->status_cetak}}">
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
                                                Cetak Fisik + E-Book
                                            @endif
                                        </p>
                                    </div>
                                    @if ($data->pilihan_terbit != '1')
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Platform Digital</h6>
                                        </div>
                                        <ul class="list-unstyled list-inline">
                                            @foreach (json_decode($data->platform_digital) as $pDigital)
                                                <li class="list-inline-item">
                                                <p class="mb-1 text-monospace">
                                                    <i class="fas fa-check text-dark"></i>
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
                                    @if ($data->pilihan_terbit != '1')
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
                                    @endif
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
                                                @if (Gate::allows('do_decline', 'persetujuan-pending'))
                                                    @if ($prod_penyetujuan->status_general == 'Proses')
                                                    <a href="javascript:void(0)" id="btn-edit-tgl-jadi" class="text-primary" data-toggle="tooltip" data-placement="bottom" title="Edit data">{{ Carbon\Carbon::parse($data->tgl_permintaan_jadi)->translatedFormat('d F Y') }}</a>
                                                    <div class="input-group" id="edit-tgl-jadi" style="display: none;">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                                        </div>
                                                        <input type="hidden" id="historyTgl" value="{{$data->tgl_permintaan_jadi}}">
                                                        <input type="text" class="form-control datepicker" id="upTglJadi" value="{{ Carbon\Carbon::parse($data->tgl_permintaan_jadi)->translatedFormat('d F Y') }}" placeholder="Tanggal Upload" readonly required>
                                                        <button type="button" class="close" aria-label="Close">

                                                            <span aria-hidden="true">&times;</span>

                                                        </button>
                                                    </div>
                                                    @else
                                                    {{ Carbon\Carbon::parse($data->tgl_permintaan_jadi)->translatedFormat('d F Y') }}
                                                    @endif
                                                @else
                                                {{ Carbon\Carbon::parse($data->tgl_permintaan_jadi)->translatedFormat('d F Y') }}
                                                @endif
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
                                        <input type="hidden" id="judul_buku" name="judul_buku" value="{{$data->judul_buku}}">
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
                                            {{ $data->format_buku }} cm
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
                                            @if (Gate::allows('do_decline', 'persetujuan-pending'))
                                                @if ($prod_penyetujuan->status_general == 'Proses')
                                                <a href="javascript:void(0)" id="btn-edit-jml-cetak" class="text-primary" data-toggle="tooltip" data-placement="bottom" title="Edit data">{{ $data->jumlah_cetak . ' eks' }}</a>
                                                <div class="input-group" id="edit-jml-cetak" style="display: none;">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-copy"></i></div>
                                                    </div>
                                                    <input type="hidden" id="historyJumlahCetak" value="{{$data->jumlah_cetak}}">
                                                    <input type="number" class="form-control" min="1" id="upTglJadi" value="{{ $data->jumlah_cetak }}" placeholder="Jumlah cetak" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><button type="button" class="close" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button></span>
                                                    </div>

                                                </div>
                                                @else
                                                {{ $data->jumlah_cetak.' eks' }}
                                                @endif
                                            @else
                                            {{ $data->jumlah_cetak.' eks' }}
                                            @endif
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
                        </form>
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
@endsection

@section('jsNeeded')
<script src="{{url('js/approval_cetak.js')}}"></script>
<script src="{{url('js/pending_cetak.js')}}"></script>
@endsection

@yield('jsNeededForm')


