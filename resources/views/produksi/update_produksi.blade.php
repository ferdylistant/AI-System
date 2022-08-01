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
            <a href="{{ route('produksi.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit Order Produksi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <form id="fup_Produksi">
                        <div class="card-header">
                            <h4>Form Produksi</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{$data->id}}" id="idProd">
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tipe Order: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                                    </div>
                                    <select class="form-control select2 tip-or" name="up_tipe_order" id="selTipeOrder" required>
                                        <option label="Pilih"></option>
                                        @foreach ($tipeOrd as $value)
                                        <option value="{{ $value['id'] }}" {{$data->tipe_order==$value['id']?'Selected':''}}>{{ $value['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_up_tipe_order"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Status Cetak: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-file"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_status_cetak" required>
                                        <option label="Pilih"></option>
                                        @foreach ($statusCetak as $val)
                                            <option value="{{ $val['id'] }}" {{$data->status_cetak==$val['id']?'Selected':''}}>{{ $val['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_up_status_cetak"></div>
                                </div>
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
                            <div class="form-group col-12 col-md-6 mb-4" id="gridPlatformDigital">
                                <label class="d-block">Platform E-book: <span class="text-danger">*</span></label>
                                @foreach ($platformDigital as $pD)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="{{ $pD['name'] }}" name="up_platform_digital[]" value="{{ $pD['name'] }}" {{$data->platform_digital==[]?'':in_array($pD['name'], json_decode($data->platform_digital,true))?'checked':''}} required>
                                        <label class="form-check-label" for="{{ $pD['name'] }}">{{ $pD['name'] }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Pilihan Terbit: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-file"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_pilihan_terbit" id="pilihanTerbit" required>
                                    </select>
                                    <div id="err_up_pilihan_terbit"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-2 mb-4">
                                <label>Urgent: <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    @foreach ($urgent as $urg)
                                        <input class="form-check-input" type="radio" name="up_urgent" value="{{ $urg['id'] }}" id="up_urgent" required {{$data->urgent==$urg['id']?'checked':''}}>
                                        <label class="form-check-label mr-4" for="up_urgent">{{ $urg['name'] }}</label>
                                    @endforeach

                                </div>
                                <div id="err_up_tentang_penulis" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penulis: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_penulis" value="{{$data->penulis}}" placeholder="Penulis" required>
                                    <div id="err_up_penulis"></div>
                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>ISBN: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-fingerprint"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_isbn" value="{{$data->isbn}}" placeholder="Kode ISBN" required>
                                    <div id="err_up_isbn"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
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
                                            <option value="{{ $imp['name'] }}" {{$data->imprint==$imp['name']?'Selected':''}}>{{ $imp['name'] }}</option>
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
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Posisi Layout <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-arrows-alt"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_posisi_layout" id="posisiLayout" required>
                                        <option label="Pilih"></option>
                                    </select>
                                    <div id="err_up_posisi_layout"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Dami <span class="text-danger">* Data dari posisi layout </span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-quote-left"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_dami" id="dami" required>
                                        <option label="Pilih"></option>
                                    </select>
                                    <div id="err_up_dami"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Format Buku <span class="text-danger">* Data dari dami</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-ruler-combined"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_format_buku" id="formatBuku" required>
                                        <option label="Pilih"></option>
                                    </select>
                                    <div id="err_up_format_buku"></div>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><strong>cm</strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Jumlah Halaman: <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <input type="text" class="form-control" name="up_jumlah_halaman_1" value="{{$jmlHalaman1}}" placeholder="Format Romawi" required>
                                        <div id="err_up_jumlah_halaman_1"></div>
                                    </div>
                                    <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                    <div class="input-group-append">
                                        <input type="text" class="form-control" name="up_jumlah_halaman_2" value="{{$jmlHalaman2}}" placeholder="Format Angka" required>
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
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Kertas Isi: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-scroll"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_kertas_isi" value="{{$data->kertas_isi}}" placeholder="Kertas isi" required>
                                    <div id="err_up_kertas_isi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Warna Isi: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-palette"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_warna_isi" value="{{$data->warna_isi}}" placeholder="Warna isi" required>
                                    <div id="err_up_warna_isi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Kertas Cover: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_kertas_cover" value="{{$data->kertas_cover}}" placeholder="Kertas cover" required>
                                    <div id="err_up_kertas_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Warna Cover: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-palette"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_warna_cover" value="{{$data->warna_cover}}" placeholder="Warna cover" required>
                                    <div id="err_up_warna_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Efek Cover: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-swatchbook"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_efek_cover" value="{{$data->efek_cover}}" placeholder="Efek cover" required>
                                    <div id="err_up_efek_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Jenis Cover: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-map"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_jenis_cover" value="{{$data->jenis_cover}}" placeholder="Jenis cover" required>
                                    <div id="err_up_jenis_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4" id="formJilid">
                                <label>Jilid: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-hand-spock"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_jilid" id="jilidChange" required>
                                        <option label="Pilih"></option>
                                        @foreach($jilid as $j)
                                            <option value="{{ $j['id'] }}" {{$data->jilid==$j['id']?'Selected':''}}>{{ $j['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_up_jilid"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4" id="ukuranBending">
                                <label>Ukuran Bending: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-ruler"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_ukuran_bending" value="{{$bending}}" placeholder="Ukuran Bending" required>
                                    <div id="err_up_ukuran_bending"></div>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><strong>cm</strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tahun Terbit: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker-year" name="up_tahun_terbit" value="{{$data->tahun_terbit}}" placeholder="Tahun" required>
                                    <div id="err_up_tahun_terbit"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Permintaan Jadi: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="up_tgl_permintaan_jadi" placeholder="Hari Bulan Tahun" value="{{date('d F Y',strtotime($data->tgl_permintaan_jadi))}}" required>
                                    <div id="err_up_tgl_permintaan_jadi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Buku Jadi: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-journal-whills"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_buku_jadi" value="{{$data->buku_jadi}}" placeholder="Buku jadi" required>
                                    <div id="err_up_buku_jadi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Status Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-check-circle"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_status_buku" id="statusBuku" required>
                                        <option label="Pilih"></option>
                                    </select>
                                    <div id="err_up_status_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Jumlah Cetak: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-copy"></i></div>
                                    </div>
                                    <input type="number" class="form-control" name="up_jumlah_cetak" min="1" placeholder="Jumlah cetak" required>
                                    <div id="err_up_jumlah_cetak"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>SPP: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-copy"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_spp" placeholder="Surat Perjanjian Penulis">
                                    <div id="err_up_spp"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Buku Contoh: </label>
                                <textarea class="form-control" name="up_buku_contoh"  placeholder="Buku contoh"></textarea>
                                <div id="err_up_jumlah_halaman"></div>

                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Perlengkapan: </label>
                                <textarea class="form-control" name="up_perlengkapan"  placeholder="Perlengkapan"></textarea>
                                <div id="err_up_perlengkapan"></div>

                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Keterangan: </label>
                                <textarea class="form-control" name="up_keterangan"  placeholder="Keterangan"></textarea>
                                <div id="err_up_keterangan"></div>

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
<script src="{{ url('js/edit_produksi.js') }}"></script>
@endsection
