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
        <h1>Detail Deskripsi Produk</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{url('/')}}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{url('/penerbitan/deskripsi/produk')}}">Data Deskripsi Produk</a>
            </div>
            <div class="breadcrumb-item">
                Detail Deskripsi Produk
            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="section-title">Data Deskripsi Produk&nbsp;
                            -
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
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- APROVAL --}}
                            @if (Gate::allows('do_approval','approval-deskripsi-produk'))
                                @if ($data->status == 'Selesai')
                            <div class="col-auto mr-auto">
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-success" id="btn-approve-despro" data-id="{{$data->id}}" data-kode="{{$data->kode}}">
                                        <i class="fas fa-check"></i>&nbsp;Setujui</button>
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
                                            <div class="user-name">Yogyakarta, {{is_null($data->action_gm)?Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l d F Y'):Carbon\Carbon::parse($data->action_gm)->translatedFormat('l d F Y')}}</div>
                                                @if($data->status == 'Acc')
                                                <div class="text-job text-success">
                                                    <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                                                </div>
                                                @elseif($data->status == 'Revisi')
                                                <div class="text-job text-danger">
                                                    <a href="javascript:void(0)" id="btn-revision" class="text-danger" data-id="{{$data->id}}" data-kode="{{$data->kode}}" data-judul_asli="{{$data->judul_asli}}" data-status="Revisi" data-action_gm="{{Carbon\Carbon::parse($data->action_gm)->translatedFormat('l, d F Y, H:i')}}"
                                                    data-alasan_revisi="{{$data->alasan_revisi}}" data-deadline_revisi="{{Carbon\Carbon::parse($data->deadline_revisi)->translatedFormat('l, d F Y, H:i')}}">
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
                                                <span class="bullet"></span>
                                                <a href="{{ url('/penerbitan/penulis/detail-penulis/' . $pen->id) }}">{{ $pen->nama }}</a>
                                            </p>
                                            </li>
                                        @endforeach

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
                                            {{$format_buku}}&nbsp;cm
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
                                    <a href="{{ url('/manajemen-web/user/' . $editor->id) }}">{{ $editor->nama }}</a>

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
                                        <a href="{{ url('/manajemen-web/user/' . $pic->id) }}">{{ $pic->nama }}</a>
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
                                        {{ $imprint }}
                                    @endif
                                    </p>
                                </div>
                                <div class="list-group-item flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Judul Final</h6>
                                    </div>
                                    <p class="mb-1 text-monospace" id="judulFinalChange">
                                    @if (is_null($data->judul_final))
                                        -
                                    @else
                                        {{ $data->judul_final }}
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
                                    @if ((is_null($data->alt_judul)) || (json_decode($data->alt_judul) == [null]))
                                        -
                                    @else
                                        @foreach (json_decode($data->alt_judul) as $key => $alt)
                                        @if (Gate::allows('do_approval','approval-deskripsi-produk'))
                                            @if ($data->status == 'Selesai')
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="alt_judul_radio" data-id="{{$data->id}}" value="{{$alt}}" id="altJudulRadio"
                                                    {{(is_null($data->judul_final)?($key<1?'checked':''):($data->judul_final==$alt?'checked':''))}}>
                                                    <label class="form-check-label" for="altJudulRadio">
                                                        {{$alt}}
                                                    @if ($key < 1)
                                                        <span class="text-danger font-weight-bold">(Usulan Penulis)</span>
                                                    @endif
                                                    </label>
                                                </div>
                                            @else
                                            <p><span class="bullet"></span>{{ $alt }}
                                                @if ($key < 1)
                                                <i class="fas fa-check text-danger"></i><span class="text-danger font-weight-bold"> Usulan Penulis</span>
                                                    @if (!is_null($data->judul_final))
                                                        @if ($data->judul_final==$alt)
                                                        & <i class="fas fa-check text-success"></i><span class="text-success font-weight-bold"> Dipilih Direksi</span>
                                                        @endif
                                                    @endif
                                                @else
                                                     @if (!is_null($data->judul_final))
                                                        @if ($data->judul_final==$alt)
                                                        <i class="fas fa-check text-success"></i><span class="text-success font-weight-bold"> Dipilih Direksi</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </p>
                                            @endif

                                        @else
                                        <p><span class="bullet"></span>{{ $alt }}
                                            @if ($key < 1)
                                            <i class="fas fa-check text-danger"></i><span class="text-danger font-weight-bold"> Usulan Penulis</span>
                                                @if (!is_null($data->judul_final))
                                                    @if ($data->judul_final==$alt)
                                                    & <i class="fas fa-check text-success"></i><span class="text-success font-weight-bold"> Dipilih Direksi</span>
                                                    @endif
                                                @endif
                                            @else
                                                 @if (!is_null($data->judul_final))
                                                    @if ($data->judul_final==$alt)
                                                    <i class="fas fa-check text-success"></i><span class="text-success font-weight-bold"> Dipilih Direksi</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </p>
                                        @endif
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
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/pdf.min.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/jquery.ipages.min.js')}}"></script>
<script src="{{url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
@endsection

@section('jsNeeded')
<script src="{{url('js/approval_despro.js')}}"></script>
<script src="{{url('js/revisi_despro.js')}}"></script>
<script>
$(function(){
    $(document).ajaxSend(function() {
        $("#overlay").fadeIn(300);　
    });
    $('input[type=radio][name=alt_judul_radio]').change(function(){
        var id = $(this).data('id');
        var judul_val = $(this).val();
        $.ajax({
            url: "{{route('despro.pilihjudul')}}",
            type: 'POST',
            data: { id: id, judul: judul_val },
            dataType: 'json',
            success: function(data){
                console.log(data);
                if (data.status == 'success'){
                    $('#judulFinalChange').html(data.data);
                    notifToast(data.status, data.message);
                } else {
                    notifToast(data.status, data.message);
                }
            }
        }).done(function() {
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
        });
    });
});
</script>
@endsection

@yield('jsNeededForm')


