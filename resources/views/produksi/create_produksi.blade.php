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
            <a href="{{ url('penerbitan/naskah') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Buat Order Produksi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <form id="fadd_Produksi">
                        <div class="card-header">
                            <h4>Form Produksi</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tipe Order: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_tipe_order" required>
                                        <option label="Pilih"></option>
                                        @foreach ($tipeOrd as $value)
                                        <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_add_tipe_order"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Status Cetak: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-file"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_status_cetak" required>
                                        <option label="Pilih"></option>
                                        @foreach ($statusCetak as $val)
                                            <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_add_status_cetak"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Judul Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_judul_buku"  placeholder="Judul buku">
                                    <div id="err_add_judul_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Sub Judul Buku: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_sub_judul_buku"  placeholder="Sub-Judul buku">
                                    <div id="err_add_sub_judul_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label class="d-block">Platform E-book: <span class="text-danger">*</span></label>
                                @foreach ($platformDigital as $pD)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="{{ $pD['name'] }}" name="add_platform_digital[]" value="{{ $pD['name'] }}" >
                                        <label class="form-check-label" for="{{ $pD['name'] }}">{{ $pD['name'] }}</label>
                                    </div>
                                @endforeach

                            </div>
                            <div class="form-group col-12 col-md-2 mb-4">
                                <label>Urgent: <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    @foreach ($urgent as $urg)
                                        <input class="form-check-input" type="radio" name="add_urgent" value="{{ $urg['id'] }}" id="add_urgent">
                                        <label class="form-check-label mr-4" for="add_urgent">{{ $urg['name'] }}</label>
                                    @endforeach

                                </div>
                                <div id="err_add_tentang_penulis" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penulis: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_penulis"  placeholder="Penulis">
                                    <div id="err_add_penulis"></div>
                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>ISBN: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-fingerprint"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_isbn"  placeholder="Kode ISBN">
                                    <div id="err_add_isbn"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>E-ISBN: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-fingerprint"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_eisbn" placeholder="Kode E-ISBN">
                                    <div id="err_add_eisbn"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penerbit: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_penerbit"  placeholder="Nama penerbit">
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
                                            <option value="{{ $imp['name'] }}">{{ $imp['name'] }}</option>
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
                                    <input type="text" class="form-control" name="add_edisi" placeholder="Format romawi">
                                    <div id="err_add_edisi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Cetakan: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-print"></i></div>
                                    </div>
                                    <input type="number" min="1" class="form-control" name="add_cetakan" placeholder="Format angka">
                                    <div id="err_add_cetakan"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Format Buku</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <input type="text" class="form-control" name="add_format_buku_1" placeholder="Format angka">
                                        <div id="err_add_format_buku_1"></div>
                                    </div>
                                    <span class="input-group-text"><i class="fas fa-times"></i></span>
                                    <input type="text" class="form-control" name="add_format_buku_2" placeholder="Format angka">
                                    <div id="err_add_format_buku_2"></div>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><strong>cm</strong></span>
                                    </div>
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
                                <label>Kelompok Buku: </label>
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
                                <label>Kertas Isi: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-scroll"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_kertas_isi"  placeholder="Kertas isi">
                                    <div id="err_add_kertas_isi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Warna Isi: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-palette"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_warna_isi"  placeholder="Warna isi">
                                    <div id="err_add_warna_isi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Kertas Cover: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_kertas_cover"  placeholder="Kertas cover">
                                    <div id="err_add_kertas_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Warna Cover: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-palette"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_warna_cover"  placeholder="Warna cover">
                                    <div id="err_add_warna_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Efek Cover: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-swatchbook"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_efek_cover"  placeholder="Efek cover">
                                    <div id="err_add_efek_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Jenis Cover: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-map"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_jenis_cover"  placeholder="Jenis cover">
                                    <div id="err_add_jenis_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Jahit Kawat: </label>
                                <div class="form-check">
                                    @foreach ($jahitKawat as $jk)
                                        <input class="form-check-input" type="radio" name="add_jahit_kawat" value="{{ $jk['id'] }}" id="add_jahit_kawat">
                                        <label class="form-check-label mr-4" for="add_jahit_kawat">{{ $jk['name'] }}</label>
                                    @endforeach

                                </div>
                                <div id="err_add_tentang_penulis" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Jahit Benang: </label>
                                <div class="form-check">
                                    @foreach ($jahitBenang as $jb)
                                        <input class="form-check-input" type="radio" name="add_jahit_benang" value="{{ $jb['id'] }}" id="add_jahit_benang">
                                        <label class="form-check-label mr-4" for="add_jahit_benang">{{ $jb['name'] }}</label>
                                    @endforeach

                                </div>
                                <div id="err_add_tentang_penulis" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Bending: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-hand-spock"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_bending"  placeholder="Bending">
                                    <div id="err_add_bending"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tanggal Terbit: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="add_tanggal_terbit" placeholder="Hari Bulan Tahun">
                                    <div id="err_add_tanggal_terbit"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Buku Jadi: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-journal-whills"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_buku_jadi"  placeholder="Buku jadi">
                                    <div id="err_add_buku_jadi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Jumlah Cetak: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-copy"></i></div>
                                    </div>
                                    <input type="number" class="form-control" name="add_jumlah_cetak" min="1" placeholder="Jumlah cetak">
                                    <div id="err_add_jumlah_cetak"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Buku Contoh: </label>
                                <textarea class="form-control" name="add_buku_contoh"  placeholder="Buku contoh"></textarea>
                                <div id="err_add_jumlah_halaman"></div>

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
<script>
$(function() {
    $(".select2").select2({
        placeholder: 'Pilih',
    }).on('change', function(e) {
        if(this.value) {
            $(this).valid();
        }
    });

    $('.datepicker').datepicker({
        format: 'dd MM yyyy'
    });

    // $('.custom-file-input').on('change',function(e){
    //     var fileName = $(this).val().replace('C:\\fakepath\\', " ");
    //     $(this).next('.custom-file-label').html(fileName);
    // });

    let addNaskah = jqueryValidation_('#fadd_Produksi', {
        add_tipe_order: {required: true},
        add_status_cetak: {required: true},
        add_judul_buku: {required: true},
        add_platform_digital: {required: true},
        add_urgent: {required: true},
        add_isbn: {required: true},
        add_edisi: {required: true},
        add_cetakan: {required: true},
        add_tanggal_terbit: {required: true},
    });

    function ajaxAddProduksi(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: "{{ route('produksi.create')}}",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                console.log(result)
                resetFrom(data);
                notifToast('success', 'Data produksi berhasil disimpan!');
            },
            error: function(err) {
                console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if(rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    addNaskah.showErrors(err);
                }
                notifToast('error', 'Data produksi gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fadd_Produksi').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="add_judul_buku"]').val();
            swal({
                text: 'Tambah data Produksi ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxAddProduksi($(this))
                }
            });

        }
    })
})
</script>
@endsection
