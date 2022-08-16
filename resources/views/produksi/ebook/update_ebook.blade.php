@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
@endsection

@section('cssNeeded')
<style>

</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('ebook.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit Order Produksi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <form id="fup_Ebook">
                        <div class="card-header">
                            <h4>Form Produksi Order Ebook</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tipe Order: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$data->id}}" id="idEbook">
                                    <input type="hidden" name="tipe_order" value="{{$data->tipe_order}}">
                                    <select class="form-control select2" name="up_tipe_order" id="selTipeOrder" required>
                                        <option label="Pilih"></option>
                                        @foreach ($tipeOrd as $value)
                                        <option value="{{ $value['id'] }}" {{$data->tipe_order==$value['id']?'Selected':''}}>{{ $value['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_up_tipe_order"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label class="d-block">Platform E-book: <span class="text-danger">*</span></label>
                                @foreach ($platformDigital as $pD)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="{{ $pD['name'] }}" name="up_platform_digital[]" value="{{ $pD['name'] }}" {{$data->platform_digital==[]?'':in_array($pD['name'], json_decode($data->platform_digital,true))?'checked':''}} required>
                                        <label class="form-check-label" for="{{ $pD['name'] }}">{{ $pD['name'] }}</label>
                                    </div>
                                @endforeach

                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Judul Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_judul_buku" value="{{$data->judul_buku}}" placeholder="Judul buku" required>
                                    <div id="err_up_judul_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Sub Judul Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_sub_judul_buku" value="{{$data->sub_judul}}" placeholder="Sub-Judul buku" required>
                                    <div id="err_up_sub_judul_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penulis: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_penulis[]">
                                        <option label="Pilih"></option>
                                        @foreach ($penulis as $pen)
                                            <option value="{{ $pen->id }}" {{$data->penulis==$pen->nama?'Selected':''}}>{{ $pen->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_up_penulis"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4" id="eISBN">
                                <label>E-ISBN: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-fingerprint"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_eisbn" value="{{$data->eisbn}}" placeholder="Kode E-ISBN" required>
                                    <div id="err_up_eisbn"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penerbit: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_penerbit" value="{{$data->penerbit}}" placeholder="Nama penerbit" required>
                                    <div id="err_up_penerbit"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Imprint: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-building"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_imprint" required>
                                        <option label="Pilih"></option>
                                        @foreach ($imprint as $imp)
                                            <option value="{{ $imp->id }}" {{$data->imprint==$imp->nama?'Selected':''}}>{{ $imp->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_up_imprint"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Edisi: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-bookmark"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_edisi" placeholder="Format romawi" value="{{$edisi}}" required>
                                    <div id="err_up_edisi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Cetakan: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-print"></i></div>
                                    </div>
                                    <input type="number" min="1" class="form-control" name="up_cetakan" placeholder="Format angka" value="{{$cetakan}}" required>
                                    <div id="err_up_cetakan"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Jumlah Halaman: <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <input type="text" class="form-control" name="up_jumlah_halaman_1"  placeholder="Format Romawi" value="{{$jmlHalaman1}}" required>
                                        <div id="err_up_jumlah_halaman_1"></div>
                                    </div>
                                    <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                    <div class="input-group-append">
                                        <input type="text" class="form-control" name="up_jumlah_halaman_2"  placeholder="Format Angka" value="{{$jmlHalaman2}}" required>
                                        <div id="err_up_jumlah_halaman_2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Kelompok Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-table"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_kelompok_buku" required>
                                        <option label="Pilih"></option>
                                        @foreach($kbuku as $kb)
                                            <option value="{{ $kb->nama }}" {{$data->kelompok_buku==$kb->nama?'Selected':''}}>{{ $kb->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_up_kelompok_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Status Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-check-circle"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_status_buku" id="statusBukuEbook" required>
                                        <option label="Pilih"></option>
                                    </select>
                                    <div id="err_up_status_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>SPP: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-pen-square"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_spp" value="{{$data->spp}}" placeholder="Surat Perjanjian Penulis">
                                    <div id="err_up_spp"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tahun Terbit: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker-year" value="{{$data->tahun_terbit}}" name="up_tahun_terbit" placeholder="Tahun" readonly required>
                                    <div id="err_up_tahun_terbit"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Upload: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="up_tgl_upload" value="{{Carbon\Carbon::parse($data->tgl_upload)->translatedFormat('d F Y')}}" placeholder="Tanggal Upload" readonly required>
                                    <div id="err_up_tgl_upload"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Perlengkapan: </label>
                                <textarea class="form-control" name="up_perlengkapan" value="{{$data->perlengkapan}}" placeholder="Perlengkapan"></textarea>
                                <div id="err_up_perlengkapan"></div>

                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Keterangan: </label>
                                <textarea class="form-control" name="up_keterangan" value="{{$data->keterangan}}" placeholder="Keterangan"></textarea>
                                <div id="err_up_keterangan"></div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                    </form>
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
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
@endsection


@section('jsNeeded')
<script src="{{url('js/edit_ebook.js')}}"></script>
@endsection
