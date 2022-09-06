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
            <a href="{{ route('proses.ebook.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Proses Upload E-Book Multimedia</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Data Order E-book&nbsp;
                            - {{Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y')}}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Kode Order</h6>
                                    </div>
                                    {{-- <input type="hidden" id="kode_order" name="kode_order" value="{{ $data->kode_order }}"> --}}
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
                                        <h6 class="mb-1">Tanggal Order</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->tanggal_order))
                                            {{ Carbon\Carbon::parse($data->tanggal_order)->translatedFormat('d F Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Tanggal Upload</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (!is_null($data->tgl_upload))
                                            {{ Carbon\Carbon::parse($data->tgl_upload)->translatedFormat('d F Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Judul Buku</h6>
                                    </div>
                                    {{-- <input type="hidden" id="judul_buku" name="judul_buku" value="{{$data->judul_buku}}"> --}}
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
                            </div>

                            <div class="col-12 col-md-4">

                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Edisi/Cetakan</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                    @if (is_null($data->edisi_cetakan))
                                        -
                                    @else
                                        {{ $data->edisi_cetakan.'/'.$data->tahun_terbit }}
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                    @foreach (json_decode($data->platform_digital) as $i => $pDigi)
                                        <li class="nav-item">
                                        <a class="nav-link {{$i==0?'active show':''}}" id="{{Illuminate\Support\Str::slug($pDigi.'tab','-')}}" data-toggle="tab" href="#{{Illuminate\Support\Str::slug($pDigi.'link','-')}}" role="tab" aria-controls="{{Illuminate\Support\Str::slug($pDigi,'-')}}" aria-selected="true">{{$pDigi}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content tab-bordered" id="myTab3Content">
                                    @foreach (json_decode($data->platform_digital) as $i => $pDigital)
                                        @if (is_null($data->bukti_upload))
                                        <div class="tab-pane fade {{$i==0?'active show':''}}" id="{{Illuminate\Support\Str::slug($pDigital.'link','-')}}" role="tabpanel" aria-labelledby="{{Illuminate\Support\Str::slug($pDigital.'tab','-')}}">
                                            <div class="text-muted text-small font-600-bold">
                                                -Link bukti upload ke platform {{$pDigital}} belum ditambahkan-
                                                <p>
                                                    <a href="{{url('produksi/proses/ebook-multimedia/edit?kode='.$data->order_ebook_id.'&track='.$data->id)}}"> Upload link</a>
                                                </p>
                                            </div>
                                        </div>
                                        @else
                                            @foreach (json_decode($data->bukti_upload) as $j => $bu)
                                                @if ($i == $j)
                                                    @if ($bu == null)
                                                    <div class="tab-pane fade {{$i==0?'active show':''}}" id="{{Illuminate\Support\Str::slug($pDigital.'link','-')}}" role="tabpanel" aria-labelledby="{{Illuminate\Support\Str::slug($pDigital.'tab','-')}}">
                                                        <div class="text-muted text-small font-600-bold">
                                                            -Link bukti upload ke platform {{$pDigital}} belum ditambahkan-
                                                            <p>
                                                                <a href="{{url('produksi/proses/ebook-multimedia/edit?kode='.$data->order_ebook_id.'&track='.$data->id)}}"> Upload link</a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="tab-pane fade {{$i==0?'active show':''}}" id="{{Illuminate\Support\Str::slug($pDigital.'link','-')}}" role="tabpanel" aria-labelledby="{{Illuminate\Support\Str::slug($pDigital.'tab','-')}}">
                                                        <a href="{{$bu}}" target="_blank" class="d-block"><i class="fas fa-link"></i>&nbsp;{{$bu}}</a>
                                                    </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif

                                    @endforeach
                                </div>
                                {{-- <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Bukti Link Upload Platform E-Book</h6>
                                    </div>
                                    <p class="mb-1 text-monospace">
                                        @if (is_null(json_decode($data->bukti_upload)))
                                            <div class="text-muted text-center text-small font-600-bold">Bukti upload link belum ditambahkan..</div>
                                        @else
                                        @foreach (json_decode($data->platform_digital) as $i => $pDigi)
                                            @foreach (Illuminate\Support\Arr::whereNotNull(json_decode($data->bukti_upload)) as $j => $bu)
                                                @if ($i == $j)
                                                    <div class="text-small font-600-bold">
                                                        <a href="{{$bu}}" target="_blank" class="d-block"><i class="fas fa-link"></i>&nbsp;{{$pDigi}}</a>
                                                    </div>
                                                @endif
                                            @endforeach

                                        @endforeach

                                        @endif
                                    </p>
                                </div> --}}
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
{{-- <script src="{{url('js/approval_ebook.js')}}"></script>
<script src="{{url('js/pending_ebook.js')}}"></script> --}}
@endsection

@yield('jsNeededForm')


