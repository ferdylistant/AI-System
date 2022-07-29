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
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tipe Order: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$data->id}}">
                                    <input type="hidden" name="kode_order" value="{{$data->kode_order}}">
                                    <input type="hidden" name="tipe_order" value="{{$data->tipe_order}}">
                                    <select class="form-control select2" name="up_tipe_order" required>
                                        <option label="Pilih" {{is_null($data->tipe_order)?'Selected':''}}></option>
                                        @foreach ($tipeOrd as $value)
                                        <option value="{{ $value['id'] }}"{{$data->tipe_order==$value['id']?'Selected':''}}>{{ $value['name'] }}</option>
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
                                        <option label="Pilih" {{is_null($data->status_cetak)?'Selected':''}}></option>
                                        @foreach ($statusCetak as $val)
                                            <option value="{{ $val['id'] }}"{{$data->status_cetak==$val['id']?'Selected':''}}>{{ $val['name'] }}</option>
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
                                    <input type="text" class="form-control" name="up_judul_buku" value="{{ is_null($data->judul_buku) ? '': $data->judul_buku }}" placeholder="Judul buku">
                                    <div id="err_up_judul_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Sub Judul Buku: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_sub_judul_buku" value="{{ is_null($data->sub_judul) ? '': $data->sub_judul }}" placeholder="Sub-Judul buku">
                                    <div id="err_up_sub_judul_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label class="d-block">Platform E-book: <span class="text-danger">*</span></label>
                                @foreach ($platformDigital as $pD)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="{{ $pD['name'] }}" name="up_platform_digital[]" value="{{ $pD['name'] }}" {{in_array($pD['name'], json_decode($data->platform_digital))?'checked':''}}>
                                        <label class="form-check-label" for="{{ $pD['name'] }}">{{ $pD['name'] }}</label>
                                    </div>
                                @endforeach

                            </div>
                            <div class="form-group col-12 col-md-2 mb-4">
                                <label>Urgent: <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    @foreach ($urgent as $urg)
                                        <input class="form-check-input" type="radio" name="up_urgent" value="{{ $urg['id'] }}" id="up_urgent"{{$data->urgent==$urg['id']?'Checked':''}}>
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
                                    <input type="text" class="form-control" name="up_penulis" value="{{ $data->penulis }}" placeholder="Penulis">
                                    <div id="err_up_penulis"></div>
                                </div>
                            </div>

                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>ISBN: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-fingerprint"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_isbn" value="{{ $data->isbn }}" placeholder="Kode ISBN">
                                    <div id="err_up_isbn"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>E-ISBN: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-fingerprint"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_eisbn" value="{{ $data->eisbn }}" placeholder="Kode E-ISBN">
                                    <div id="err_up_eisbn"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penerbit: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_penerbit" value="{{ $data->penerbit }}" placeholder="Nama penerbit">
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
                                        <option label="Pilih" {{ is_null($data->imprint)?'Selected':'' }}></option>
                                        @foreach ($imprint as $imp)
                                            <option value="{{ $imp['name'] }}" {{ $data->imprint==$imp['name']?'Selected':'' }}>{{ $imp['name'] }}</option>
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
                                    <input type="text" class="form-control" name="up_edisi" value="{{ $edisi }}" placeholder="Format romawi">
                                    <div id="err_up_edisi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Cetakan: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-print"></i></div>
                                    </div>
                                    <input type="number" min="1" class="form-control" name="up_cetakan" value="{{ $cetakan }}" placeholder="Format angka">
                                    <div id="err_up_cetakan"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Format Buku <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <input type="text" class="form-control" name="up_format_buku_1" value="{{ $formatBuku1 }}" placeholder="Format angka">
                                        <div id="err_up_format_buku_1"></div>
                                    </div>
                                    <span class="input-group-text"><i class="fas fa-times"></i></span>
                                    <input type="text" class="form-control" name="up_format_buku_2" value="{{ $formatBuku2 }}" placeholder="Format angka">
                                    <div id="err_up_format_buku_2"></div>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><strong>cm</strong></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Jumlah Halaman: <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <input type="text" class="form-control" name="up_jumlah_halaman_1" value="{{ $jmlHalaman1 }}" placeholder="Format Romawi" required>
                                        <div id="err_up_jumlah_halaman_1"></div>
                                    </div>
                                    <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                    <div class="input-group-append">
                                        <input type="text" class="form-control" name="up_jumlah_halaman_2" value="{{ $jmlHalaman2 }}" placeholder="Format Angka" required>
                                        <div id="err_up_jumlah_halaman_2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Kelompok Buku: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-table"></i></div>
                                    </div>
                                    <select class="form-control select2" name="up_kelompok_buku" required>
                                        <option label="Pilih" {{is_null($data->kelompok_buku)?'Selected':''}}></option>
                                        @foreach($kbuku as $kb)
                                            <option value="{{ $kb->nama }}" {{ $data->kelompok_buku==$kb->nama?'Selected':'' }}>{{ $kb->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_up_kelompok_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Kertas Isi: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-scroll"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_kertas_isi" value="{{ $data->kertas_isi }}" placeholder="Kertas isi">
                                    <div id="err_up_kertas_isi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Warna Isi: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-palette"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_warna_isi" value="{{ $data->warna_isi }}" placeholder="Warna isi">
                                    <div id="err_up_warna_isi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Kertas Cover: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-sticky-note"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_kertas_cover" value="{{ $data->kertas_cover }}" placeholder="Kertas cover">
                                    <div id="err_up_kertas_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Warna Cover: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-palette"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_warna_cover" value="{{ $data->warna_cover }}" placeholder="Warna cover">
                                    <div id="err_up_warna_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Efek Cover: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-swatchbook"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_efek_cover" value="{{ $data->efek_cover }}" placeholder="Efek cover">
                                    <div id="err_up_efek_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Jenis Cover: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-map"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_jenis_cover" value="{{ $data->jenis_cover }}" placeholder="Jenis cover">
                                    <div id="err_up_jenis_cover"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Jahit Kawat: </label>
                                <div class="form-check">
                                    @foreach ($jahitKawat as $jk)
                                        <input class="form-check-input" type="radio" name="up_jahit_kawat" value="{{ $jk['id'] }}" id="up_jahit_kawat" {{ $data->jahit_kawat==$jk['id']?'Checked':''}}>
                                        <label class="form-check-label mr-4" for="up_jahit_kawat">{{ $jk['name'] }}</label>
                                    @endforeach

                                </div>
                                <div id="err_up_tentang_penulis" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Jahit Benang: </label>
                                <div class="form-check">
                                    @foreach ($jahitBenang as $jb)
                                        <input class="form-check-input" type="radio" name="up_jahit_benang" value="{{ $jb['id'] }}" id="up_jahit_benang" {{ $data->jahit_benang==$jb['id']?'Checked':'' }}>
                                        <label class="form-check-label mr-4" for="up_jahit_benang">{{ $jb['name'] }}</label>
                                    @endforeach

                                </div>
                                <div id="err_up_tentang_penulis" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Bending: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-hand-spock"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_bending" value="{{ $data->bending }}" placeholder="Bending">
                                    <div id="err_up_bending"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Terbit: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="up_tanggal_terbit" value="{{ date('d F Y', strtotime($data->tanggal_terbit)) }}" placeholder="Hari Bulan Tahun">
                                    <div id="err_up_tanggal_terbit"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Permintaan Jadi: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="up_tgl_permintaan_jadi" value="{{ date('d F Y', strtotime($data->tgl_permintaan_jadi)) }}" placeholder="Hari Bulan Tahun">
                                    <div id="err_up_tgl_permintaan_jadi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Buku Jadi: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-journal-whills"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_buku_jadi" value="{{ $data->buku_jadi }}" placeholder="Buku jadi">
                                    <div id="err_up_buku_jadi"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Jumlah Cetak: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-copy"></i></div>
                                    </div>
                                    <input type="number" class="form-control" name="up_jumlah_cetak" min="0" value="{{ $data->jumlah_cetak }}" placeholder="Jumlah cetak">
                                    <div id="err_up_jumlah_cetak"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Buku Contoh: </label>
                                <textarea class="form-control" name="up_buku_contoh" placeholder="Buku contoh">{{ $data->buku_contoh }}</textarea>
                                <div id="err_up_jumlah_halaman"></div>

                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Perlengkapan: </label>
                                <textarea class="form-control" name="up_perlengkapan" placeholder="Perlengkapan">{{ $data->perlengkapan }}</textarea>
                                <div id="err_up_perlengkapan"></div>

                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Keterangan: </label>
                                <textarea class="form-control" name="up_keterangan"  placeholder="Keterangan">{{ $data->keterangan }}</textarea>
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
    function resetFrom(form) {
    form.trigger('reset');
        $('[name="up_tipe_order"]').val('').trigger('change');
        $('[name="up_status_cetak"]').val('').trigger('change');
        $('[name="up_judul_buku"]').val('').trigger('change');
        $('[name="up_platform_digital[]"]').val('').trigger('change');
        $('[name="up_urgent"]').val('').trigger('change');
        $('[name="up_isbn"]').val('').trigger('change');
        $('[name="up_edisi]"]').val('').trigger('change');
        $('[name="up_cetakan"]').val('').trigger('change');
        $('[name="up_tanggal_terbit"]').val('').trigger('change');
        $('[name="up_tgl_permintaan_jadi"]').val('').trigger('change');
        $('[name="up_format_buku_1"]').val('').trigger('change');
        $('[name="up_format_buku_2"]').val('').trigger('change');

        // $.ajax({
        //     type: "GET",
        //     url: "{{url('penerbitan/naskah')}}",
        //     data: {request_: 'getKodeNaskah'},
        //     success: function(data) {
        //         $('[name="up_kode"]').val(data);
        //     }
        // });
    }
    let upNaskah = jqueryValidation_('#fup_Produksi', {
        up_tipe_order: {required: true},
        up_status_cetak: {required: true},
        up_judul_buku: {required: true},
        up_platform_digital: {required: true},
        up_urgent: {required: true},
        up_isbn: {required: true},
        up_edisi: {required: true},
        up_cetakan: {required: true},
        up_tanggal_terbit: {required: true},
        up_tgl_permintaan_jadi: {required: true},
        up_format_buku_1: {required: true},
        up_format_buku_2: {required: true},
    });

    function ajaxUpProduksi(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: "{{ route('produksi.update')}}",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                resetFrom(data);
                notifToast('success', 'Data produksi berhasil diubah!');
                location.href = result.route;
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
                    upNaskah.showErrors(err);
                }
                notifToast('error', 'Data produksi gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fup_Produksi').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="up_judul_buku"]').val();
            swal({
                text: 'Ubah data Produksi ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxUpProduksi($(this))
                }
            });

        }
    })
})
</script>
@endsection
