@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"
        integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg=="
        crossorigin="anonymous" />
@endsection

@section('cssNeeded')
<style>
.bootstrap-tagsinput{

width: 100%;

}
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
        </div>
        <h1>Edit/Buat Penerbitan Deskripsi Cover</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{url('/')}}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{url('/penerbitan/deskripsi/cover')}}">Data Penerbitan Deskripsi Cover</a>
            </div>
            <div class="breadcrumb-item">
                Edit/Buat Penerbitan Deskripsi Cover
            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="section-title">Form Deskripsi Cover</h4>
                        <div class="card-header-action">
                            @switch($data->status)
                                @case('Antrian')
                                <i class="far fa-circle" style="color:#34395E;"></i>
                                Status Progress:
                                <span class="badge" style="background:#34395E;color:white">{{$data->status}}</span>
                                    @break
                                @case('Pending')
                                <i class="far fa-circle text-danger"></i>
                                Status Progress:
                                <span class="badge badge-danger">{{$data->status}}</span>
                                    @break
                                @case('Proses')
                                <i class="far fa-circle text-success"></i>
                                Status Progress:
                                <span class="badge badge-success">{{$data->status}}</span>
                                    @break
                                @case('Selesai')
                                <i class="far fa-circle text-dark"></i>
                                Status Progress:
                                <span class="badge badge-light">{{$data->status}}</span>
                                    @break
                                @case('Revisi')
                                <i class="far fa-circle text-info"></i>
                                Status Progress:
                                <span class="badge badge-info">{{$data->status}}</span>
                                    @break
                                @case('Acc')
                                <i class="far fa-circle text-dark"></i>
                                Status Progress:
                                <span class="badge badge-light">{{$data->status}}</span>
                                    @break

                                @default
                                <i class="fas fa-lock text-dark"></i>
                                Status Progress:
                                <span class="badge badge-secondary">{{$data->status}}</span>
                            @endswitch
                        </div>
                    </div>
                @if ($data->status == 'Proses'  || ($data->status == 'Selesai' && Gate::allows('do_approval','approval-deskripsi-produk')))
                    <form id="fup_deskripsiCover">
                        {!! csrf_field() !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Kode naskah:</th>
                                                    <td class="table-active text-right">{{$data->kode}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Judul Asli:</th>
                                                    <input type="hidden" name="judul_asli" value="{{$data->judul_asli}}">
                                                    <td class="table-active text-right">{{$data->judul_asli}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                                    @if (!is_null($data->judul_final))
                                                    <td class="table-active text-right">
                                                        {{$data->judul_final}}
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Sub-Judul Final:</th>
                                                    @if (!is_null($data->sub_judul_final))
                                                    <td class="table-active text-right" id="subJudulFinalCol">
                                                        {{$data->sub_judul_final}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="subJudulFinalButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="subJudulFinalColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="sub_judul_final" class="form-control" cols="30" rows="10">{{$data->sub_judul_final}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_sub_judul_final text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="subJudulFinalCol">
                                                        <a href="javascript:void(0)" id="subJudulFinalButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="subJudulFinalColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="sub_judul_final" class="form-control" cols="30" rows="10"></textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_sub_judul_final text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Kelompok Buku:</th>
                                                    <td class="table-active text-right">{{$data->nama}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Penulis:</th>
                                                    <td class="table-active text-right">
                                                        @foreach ($penulis as $p)
                                                            {{$p->nama}}-<br>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Nama Pena: <span
                                                            class="text-danger">*Kosongkan jika sama dengan nama
                                                            asli penulis</span></th>
                                                    @if (!is_null($data->nama_pena))
                                                        <td class="table-active text-right" id="namaPenaCol">
                                                            @foreach (json_decode($data->nama_pena) as $np)
                                                            {{ $np }}-<br>
                                                            @endforeach
                                                            <p class="text-small">
                                                                <a href="javascript:void(0)" id="namaPenaButton"><i
                                                                        class="fa fa-pen"></i>&nbsp;Edit</a>
                                                            </p>
                                                        </td>
                                                        <td class="table-active text-left" id="namaPenaColInput"
                                                            hidden>
                                                            <div class="input-group">
                                                                <input type="text" name="nama_pena"
                                                                    class="form-control input-tag"
                                                                    placeholder="Nama pena / Alias"
                                                                    data-role="tagsinput"
                                                                    value="{{ $nama_pena }}">
                                                                <div class="input-group-append">
                                                                    <button type="button"
                                                                        class="btn btn-outline-danger batal_edit_nama_pena text-danger align-self-center"
                                                                        data-toggle="tooltip" title="Batal Edit"><i
                                                                            class="fas fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td class="table-active text-left">
                                                            <input type="text" name="nama_pena"
                                                                class="form-control input-tag"
                                                                placeholder="Nama pena / Alias"
                                                                data-role="tagsinput">
                                                        </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Imprint:</th>
                                                    <td class="table-active text-right">{{$nama_imprint}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Des Front Cover: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->des_front_cover))
                                                    <td class="table-active text-right" id="desFrontCol">
                                                        {{$data->des_front_cover}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="desFrontButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="desFrontColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="des_front_cover" class="form-control" cols="30" rows="10" required>{{$data->des_front_cover}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_desfront text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <textarea name="des_front_cover" class="form-control" cols="30" rows="10" placeholder="Deskripsi cover depan.." required></textarea>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Des Back Cover: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->des_back_cover))
                                                    <td class="table-active text-right" id="desBackCol">
                                                        {{$data->des_back_cover}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="desBackButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="desBackColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="des_back_cover" class="form-control" cols="30" rows="10" required>{{$data->des_back_cover}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_desback text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <textarea name="des_back_cover" class="form-control" cols="30" rows="10" placeholder="Deskripsi cover belakang.." required></textarea>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Finishing Cover:</th>
                                                    @if (!is_null($data->finishing_cover))
                                                    <td class="table-active text-right" id="finishingCol">
                                                        @foreach (json_decode($data->finishing_cover,true) as $fc)
                                                            <span class="bullet"></span>{{$fc}}<br>
                                                        @endforeach
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="finishingButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="finishingColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="finishing_cover[]" class="form-control select-finishing-cover" multiple="multiple">
                                                                <option label="Pilih finishing cover"></option>
                                                                @foreach ($finishing_cover as $i => $f)
                                                                {{$sel = '';}}
                                                                @if (in_array($f,json_decode($data->finishing_cover,true)))
                                                                {{$sel = ' selected="selected" ';}}
                                                                @endif
                                                                    <option value="{{$f}}" {{$sel}}>{{$f}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_finishing text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="finishingCol">
                                                        <a href="javascript:void(0)" id="finishingButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="finishingColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="finishing_cover[]" class="form-control select-finishing-cover" multiple="multiple">
                                                                <option label="Pilih finishing cover"></option>
                                                                @foreach ($finishing_cover as $f)
                                                                    <option value="{{$f}}" {{$data->finishing_cover==$f?'Selected':''}}>{{$f}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_finishing text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Format Buku: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->format_buku))
                                                    <td class="table-active text-right" id="formatBukuCol">
                                                        {{$format_buku}} cm
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="formatBukuButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="formatBukuColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="format_buku" class="form-control select-format-buku" required>
                                                                <option label="Pilih format buku"></option>
                                                                @foreach ($format_buku_list as $fb)
                                                                    <option value="{{$fb->id}}" {{$data->format_buku==$fb->id?'Selected':''}}>{{$fb->jenis_format}}&nbsp;cm&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_format text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="format_buku" class="form-control select-format-buku" required>
                                                            <option label="Pilih format buku"></option>
                                                            @foreach ($format_buku_list as $fb)
                                                                <option value="{{$fb->id}}">{{$fb->jenis_format}}&nbsp;cm&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Jilid: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->jilid))
                                                    <td class="table-active text-right" id="jilidCol">
                                                        {{$data->jilid}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="jilidButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="jilidColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="jilid" class="form-control select-jilid" required>
                                                                <option label="Pilih jilid"></option>
                                                                @foreach ($jilid as $j)
                                                                    <option value="{{$j}}" {{$data->jilid==$j?'Selected':''}}>{{$j}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_jilid text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="jilid" class="form-control select-jilid" required>
                                                            <option label="Pilih jilid"></option>
                                                            @foreach ($jilid as $j)
                                                                <option value="{{$j}}">{{$j}}&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Tipografi:</th>
                                                    @if (!is_null($data->tipografi))
                                                    <td class="table-active text-right" id="tipografiCol">
                                                        {{$data->tipografi}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="tipografiButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="tipografiColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="tipografi" class="form-control" cols="30" rows="10">{{$data->tipografi}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_tipografi text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="tipografiCol">
                                                        <a href="javascript:void(0)" id="tipografiButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="tipografiColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="tipografi" class="form-control" cols="30" rows="10"></textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_tipografi text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Warna:</th>
                                                    @if (!is_null($data->warna))
                                                    <td class="table-active text-right" id="warnaCol">
                                                        {{$data->warna}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="warnaButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="warnaColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="warna" class="form-control" cols="30" rows="10">{{$data->warna}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_warna text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="warnaCol">
                                                        <a href="javascript:void(0)" id="warnaButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="warnaColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="warna" class="form-control" cols="30" rows="10"></textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_warna text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Kelengkapan:</th>
                                                    @if (!is_null($data->kelengkapan))
                                                    <td class="table-active text-right" id="kelCol">
                                                        {{$data->kelengkapan}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="kelButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="kelColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="kelengkapan" class="form-control select-kelengkapan">
                                                                <option label="Pilih kelengkapan"></option>
                                                                @foreach ($kelengkapan as $k)
                                                                    <option value="{{$k}}" {{$data->kelengkapan==$k?'Selected':''}}>{{$k}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_kel text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="kelCol">
                                                        <a href="javascript:void(0)" id="kelButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="kelColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="kelengkapan" class="form-control select-kelengkapan">
                                                                <option label="Pilih kelengkapan"></option>
                                                                @foreach ($kelengkapan as $k)
                                                                    <option value="{{$k}}" {{$data->kelengkapan==$k?'Selected':''}}>{{$k}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_kel text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Catatan:</th>
                                                    @if (!is_null($data->catatan))
                                                    <td class="table-active text-right" id="catCol">
                                                        {{$data->catatan}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="catButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="catColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="catatan" class="form-control" cols="30" rows="10">{{$data->catatan}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_cat text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="catCol">
                                                        <a href="javascript:void(0)" id="catButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="catColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="catatan" class="form-control" cols="30" rows="10"></textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_cat text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Bullet</th>
                                                    @if ((is_null($data->bullet)) || ($data->bullet == '[]'))
                                                    <td class="table-active text-left">
                                                        <div class="input_fields_wrap">
                                                            <button class="add_field_button btn btn-outline-primary mb-1"><i class="fas fa-plus-circle"></i> More Fields</button>
                                                            <div class="input-group">
                                                                <input type="text" name="bullet[]" placeholder="Bullet" class="form-control">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="bulletCol">
                                                        @foreach (json_decode($data->bullet,true) as $key => $aj)
                                                            <span class="bullet"></span>{{$aj}}<br>
                                                        @endforeach
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="bulletButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="bulletColInput" hidden>
                                                        <div class="input_fields_wrap">
                                                            <button class="add_field_button btn btn-outline-primary mb-1"><i class="fas fa-plus-circle"></i> More Fields</button>
                                                            <button class="btn btn-outline-danger batal_edit_bullet text-danger align-self-center mb-1" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            @foreach (Illuminate\Support\Arr::whereNotNull(json_decode($data->bullet,true)) as $k => $bullet)
                                                            <div>
                                                                <div class="input-group">
                                                                    <input type="text" name="bullet[]" value="{{$bullet}}" placeholder="Bullet" class="form-control">
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Usulan Desainer: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->desainer))
                                                    <td class="table-active text-right" id="desainerCol">
                                                        {{$nama_desainer->nama}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="desainerButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="desainerColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="desainer" class="form-control select-desainer" required>
                                                                <option label="Pilih desainer"></option>
                                                                @foreach ($desainer as $s)
                                                                    <option value="{{$s->id}}" {{$data->desainer==$s->id?'Selected':''}}>{{$s->nama}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_desainer text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="desainer" class="form-control select-desainer" required>
                                                            <option label="Pilih desainer"></option>
                                                            @foreach ($desainer as $s)
                                                                <option value="{{$s->id}}">{{$s->nama}}&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Contoh Cover: </th>
                                                    @if (!is_null($data->contoh_cover))
                                                    <td class="table-active text-right" id="contohCoverCol">
                                                        {{$data->contoh_cover}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="contohCoverButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="contohCoverColInput" hidden>
                                                        <div class="input-group">
                                                        <input type="text" class="form-control" name="contoh_cover" pattern="^(https?://)?(?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,})$" value="{{$data->contoh_cover}}"  placeholder="https://example.com/">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_contoh_cover text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="contohCoverCol">
                                                        <a href="javascript:void(0)" id="contohCoverButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="contohCoverColInput" hidden>
                                                        <div class="input-group">
                                                        <input type="text" class="form-control" name="contoh_cover" pattern="^(https?://)?(?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,})$" placeholder="https://example.com/">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_contoh_cover text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->bulan))
                                                    <td class="table-active text-right" id="bulanCol">
                                                        {{Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y')}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="bulanButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="bulanColInput" hidden>
                                                        <div class="input-group">
                                                            <input name="bulan" class="form-control datepicker" value="{{Carbon\Carbon::createFromFormat('Y-m-d',$data->bulan,'Asia/Jakarta')->format('F Y')}}" placeholder="Bulan proses" readonly required>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_bulan text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <input name="bulan" class="form-control datepicker" placeholder="Bulan proses" readonly required>
                                                    </td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                                <input type="hidden" name="id" value="{{$data->id}}">
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                @else
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Kode naskah:</th>
                                                <td class="table-active text-right">{{$data->kode}}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Judul Asli:</th>
                                                <input type="hidden" name="judul_asli" value="{{$data->judul_asli}}">
                                                <td class="table-active text-right">{{$data->judul_asli}}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                                <input type="hidden" name="judul_final" value="{{$data->judul_final}}">
                                                <td class="table-active text-right">{{$data->judul_final}}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Sub-Judul Final:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->sub_judul_final))
                                                        {{$data->sub_judul_final}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Kelompok Buku:</th>
                                                <td class="table-active text-right">{{$data->nama}}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Penulis:</th>
                                                <td class="table-active text-right">
                                                    @foreach ($penulis as $p)
                                                        {{$p->nama}}-<br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Nama Pena: <span
                                                        class="text-danger">*Kosongkan jika sama dengan nama asli
                                                        penulis</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->nama_pena))
                                                        {{ $nama_pena }}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Imprint:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->imprint))
                                                        {{$nama_imprint}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Des Front Cover: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->des_front_cover))
                                                        {{$data->des_front_cover}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Des Back Cover: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->des_back_cover))
                                                        {{$data->des_back_cover}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Finishing Cover:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->finishing_cover))
                                                        @foreach (json_decode($data->finishing_cover,true) as $fc)
                                                            <span class="bullet"></span>{{$fc}}<br>
                                                        @endforeach
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Format Buku: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->format_buku))
                                                        {{$format_buku}} cm
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Jilid: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->jilid))
                                                        {{$data->jilid}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Tipografi:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->tipografi))
                                                        {{$data->tipografi}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Warna:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->warna))
                                                        {{$data->warna}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Kelengkapan:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->kelengkapan))
                                                        {{$data->kelengkapan}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Catatan:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->catatan))
                                                        {{$data->catatan}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Bullet</th>
                                                <td class="table-active text-right">
                                                    @if ((is_null($data->bullet)) || ($data->bullet == '[]'))
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @else
                                                        @foreach (json_decode($data->bullet,true) as $key => $aj)
                                                            <span class="bullet"></span>{{$aj}}<br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Usulan Desainer: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->desainer))
                                                        {{$nama_desainer->nama}}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Contoh Cover: </th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->contoh_cover))
                                                        {{$data->contoh_cover}}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->bulan))
                                                        {{Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y')}}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('jsRequired')
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/additional-methods.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"
        integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg=="
        crossorigin="anonymous"></script>
@endsection


@section('jsNeeded')
<script src="{{ url('js/penerbitan/edit_deskripsi_cover.js') }}"></script>
@endsection
