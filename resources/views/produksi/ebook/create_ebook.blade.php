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
        <h1>Buat Order Produksi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <form id="fadd_Produksi">
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
                                    <select class="form-control select2" name="add_tipe_order" id="selTipeOrder" required>
                                        <option label="Pilih"></option>
                                        @foreach ($tipeOrd as $value)
                                        <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_add_tipe_order"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label class="d-block">Platform E-book: <span class="text-danger">*</span></label>
                                @foreach ($platformDigital as $pD)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="{{ $pD['name'] }}" name="add_platform_digital[]" value="{{ $pD['name'] }}" required>
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
                                    <input type="text" class="form-control" name="add_judul_buku"  placeholder="Judul buku" required>
                                    <div id="err_add_judul_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Sub Judul Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_sub_judul_buku"  placeholder="Sub-Judul buku" required>
                                    <div id="err_add_sub_judul_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penulis: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_penulis[]">
                                        <option label="Pilih"></option>
                                        @foreach ($penulis as $pen)
                                            <option value="{{ $pen->id }}">{{ $pen->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_add_penulis"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4" id="eISBN">
                                <label>E-ISBN: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-fingerprint"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_eisbn" placeholder="Kode E-ISBN" required>
                                    <div id="err_add_eisbn"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penerbit: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_penerbit"  placeholder="Nama penerbit" required>
                                    <div id="err_add_penerbit"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Imprint: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-building"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_imprint" required>
                                        <option label="Pilih"></option>
                                        @foreach ($imprint as $imp)
                                            <option value="{{ $imp->id }}">{{ $imp->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_add_imprint"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Edisi: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-bookmark"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_edisi" placeholder="Format romawi" required>
                                    <div id="err_add_edisi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Cetakan: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-print"></i></div>
                                    </div>
                                    <input type="number" min="1" class="form-control" name="add_cetakan" placeholder="Format angka" required>
                                    <div id="err_add_cetakan"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Jumlah Halaman: <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <input type="text" class="form-control" name="add_jumlah_halaman_1"  placeholder="Format Romawi" required>
                                        <div id="err_add_jumlah_halaman_1"></div>
                                    </div>
                                    <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                    <div class="input-group-append">
                                        <input type="text" class="form-control" name="add_jumlah_halaman_2"  placeholder="Format Angka" required>
                                        <div id="err_add_jumlah_halaman_2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Kelompok Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-table"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_kelompok_buku" required>
                                        <option label="Pilih"></option>
                                        @foreach($kbuku as $kb)
                                            <option value="{{ $kb->nama }}">{{ $kb->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_add_kelompok_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tahun Terbit: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker-year" name="add_tahun_terbit" placeholder="Tahun" required>
                                    <div id="err_add_tahun_terbit"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Status Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-check-circle"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_status_buku" id="statusBuku" required>
                                        <option label="Pilih"></option>
                                    </select>
                                    <div id="err_add_status_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>SPP: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-pen-square"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_spp" placeholder="Surat Perjanjian Penulis">
                                    <div id="err_add_spp"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Perlengkapan: </label>
                                <textarea class="form-control" name="add_perlengkapan"  placeholder="Perlengkapan"></textarea>
                                <div id="err_add_perlengkapan"></div>

                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Keterangan: </label>
                                <textarea class="form-control" name="add_keterangan"  placeholder="Keterangan"></textarea>
                                <div id="err_add_keterangan"></div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Simpan</button>
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
<script src="{{url('js/add_produksi.js')}}"></script>
@endsection
