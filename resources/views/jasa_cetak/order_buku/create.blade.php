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
        <h1>Form Order Jasa Cetak</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <form id="fadd_OrderBuku">
                        <div class="card-header">
                            <h4>Buat Order Buku</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Nomor Order: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-barcode"></i></div>
                                        </div>
                                        <input type="hidden" name="request_" value="createOrder">
                                        <input type="text" class="form-control" name="add_no_order" value="{{$kode}}" placeholder="Nomor Order" readonly>
                                        <div id="err_add_no_order"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Judul Buku: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="add_judul_buku" placeholder="Judul Buku"></textarea>
                                        <div id="err_add_judul_buku"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Nama Pemesan: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="add_nama_pemesan" placeholder="Nama Pemesan">
                                        <div id="err_add_nama_pemesan"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Pengarang:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="add_pengarang" placeholder="Pengarang">
                                        <div id="err_add_pengarang"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Format: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="add_format" placeholder="Format Buku">
                                        <div id="err_add_format"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Jumlah Halaman: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="add_jml_halaman" min="1" placeholder="Jumlah Halaman">
                                        <div id="err_add_jml_halaman"></div>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><b>Hal</b></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-3 mb-4">
                                    <label>Jenis Kertas Isi: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="add_kertas_isi" placeholder="Jenis Kertas Isi">
                                        <div id="err_add_kertas_isi"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-3 mb-4">
                                    <label>Tinta: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="add_kertas_isi_tinta" placeholder="Tinta">
                                        <div id="err_add_kertas_isi_tinta"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-3 mb-4">
                                    <label>Jenis Kertas Cover: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="add_kertas_cover" placeholder="Jenis Kertas Cover">
                                        <div id="err_add_kertas_cover"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-3 mb-4">
                                    <label>Tinta: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="add_kertas_cover_tinta" placeholder="Tinta">
                                        <div id="err_add_kertas_cover_tinta"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Perencanaan Jumlah Order: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        {{-- <input type="number" class="form-control" name="add_jml_order" min="1" placeholder="Jumlah Order"> --}}
                                        <input type="text" name="add_jml_order" class="form-control input-tag" placeholder="Jumlah Order" data-role="tagsinput">
                                        <div id="err_add_jml_order"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Status Cetak: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                        </div>
                                        <select class="form-control select2" name="add_status_cetak">
                                            <option label="Pilih"></option>
                                            @foreach($status_cetak as $sc)
                                            <option value="{{$sc}}">{{$sc}}</option>
                                            @endforeach
                                        </select>
                                        <div id="err_add_status_cetak"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Tanggal Order: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input type="text" class="form-control datepicker" name="add_tgl_order" placeholder="Hari Bulan Tahun">
                                        <div id="err_add_tgl_order"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Tanggal Permintaan Selesai: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input type="text" class="form-control datepicker" name="add_tgl_permintaan_selesai" placeholder="Hari Bulan Tahun">
                                        <div id="err_add_tgl_permintaan_selesai"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 mb-4">
                                    <label>Keterangan:</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="add_keterangan" placeholder="Keterangan Order Buku" required></textarea>
                                        <div id="err_add_keterangan"></div>
                                    </div>
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
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"
        integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg=="
        crossorigin="anonymous"></script>
@endsection


@section('jsNeeded')
<script src="{{ url('js/jasa_cetak/create_orderbuku_jasacetak.js') }}"></script>
@endsection
