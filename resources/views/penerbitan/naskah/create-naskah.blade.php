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
        <h1>Buat Naskah</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <form id="fadd_Naskah">
                    <div class="card-header">
                        <h4>Form Naskah</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Judul Asli: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_judul_asli"  placeholder="Judul Asli Naskah">
                                    <div id="err_add_judul_asli"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Kode Naskah: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_kode" value="{{$kode}}" placeholder="Kode Naskah" readonly>
                                    <div id="err_add_kode"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Kelompok Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_kelompok_buku">
                                        <option label="Pilih"></option>
                                        @foreach($kbuku as $kb)
                                        <option value="{{$kb->id}}">{{$kb->nama}}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_add_kelompok_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Jalur Buku: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_jalur_buku">
                                        <option label="Pilih"></option>
                                        <option value="Reguler">Reguler</option>
                                        <option value="MoU">MoU</option>
                                        <option value="MoU-Reguler">MoU - Reguler</option>
                                        <option value="SMK/NonSMK">SMK/NonSMK</option>
                                        <option value="Pro Literasi">Pro Literasi</option>
                                    </select>
                                    <div id="err_add_jalur_buku"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Alamat Email: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_email"  placeholder="Alamat Email Aktif">
                                    <div id="err_add_email"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tanggal Masuk Naskah: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="add_tanggal_masuk_naskah" placeholder="Hari Bulan Tahun">
                                    <div id="err_add_tanggal_masuk_naskah"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tentang Penulis: <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="add_tentang_penulis" value="1" id="add_tp_yes">
                                    <label class="form-check-label mr-4" for="add_tp_yes">Ada</label>
                                    <input class="form-check-input" type="radio" name="add_tentang_penulis" value="0" id="add_tp_no">
                                    <label class="form-check-label" for="add_tp_no">Tidak</label>
                                </div>
                                <div id="err_add_tentang_penulis" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Hard Copy: <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="add_hard_copy" value="1" id="add_hc_yes">
                                    <label class="form-check-label mr-4" for="add_hc_yes">Ya</label>
                                    <input class="form-check-input" type="radio" name="add_hard_copy" value="0" id="add_hc_no">
                                    <label class="form-check-label" for="add_hc_no">Tidak</label>
                                </div>
                                <div id="err_add_hard_copy" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Soft Copy: <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="add_soft_copy" value="1" id="add_sc_yes">
                                    <label class="form-check-label mr-4" for="add_sc_yes">Ya</label>
                                    <input class="form-check-input" type="radio" name="add_soft_copy" value="0" id="add_sc_no">
                                    <label class="form-check-label" for="add_sc_no">Tidak</label>
                                </div>
                                <div id="err_add_soft_copy" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Rencana CD/QR Code: <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="add_cdqr_code" value="1" id="add_cdqr_yes">
                                    <label class="form-check-label mr-4" for="add_cdqr_yes">Ya</label>
                                    <input class="form-check-input" type="radio" name="add_cdqr_code" value="0" id="add_cdqr_no">
                                    <label class="form-check-label" for="add_cdqr_no">Tidak</label>
                                </div>
                                <div id="err_add_cdqr_code" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>PIC Prodev: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_pic_prodev">
                                        <option label="Pilih"></option>
                                        @foreach($user as $u)
                                        <option value="{{$u->id}}">{{$u->nama}}</option>
                                        @endforeach
                                    </select>
                                    <div id="err_add_pic_prodev"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penulis: <span class="text-danger">*</span></label>
                                <select id="add_penulis" class="form-control select2" name="add_penulis[]" multiple="" required></select>
                                <div id="err_add_penulis"></div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>URL File: <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" name="add_url_file" required>
                                <div id="err_add_url_file"></div>
                            </div>
                            {{-- <div class="form-group col-12 col-md-6 mb-4">
                                <label>File Naskah (.pdf) <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="add_file_naskah" id="fileNaskah">
                                    <label class="custom-file-label" for="fileNaskah">Choose file</label>
                                </div>
                                <div id="err_add_file_naskah" style="display: block;"></div>
                            </div> --}}

                            {{-- <div class="form-group col-12 col-md-6 mb-4">
                                <label>File Tambahan Naskah (.rar; .zip)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="add_file_tambahan_naskah" id="fileTambahanNaskah">
                                    <label class="custom-file-label" for="fileTambahanNaskah">Choose file</label>
                                </div>
                                <div id="err_add_file_tambahan_naskah" style="display: block;"></div>
                            </div> --}}
                            <div class="form-group col-12 mb-4">
                                <table id="tb_selectedPenulis" class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Telepon / Ponsel</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="form-group col-12 mb-4">
                                <label>Keterangan: </label>
                                <div class="input-group">
                                    <textarea class="form-control" name="add_keterangan" placeholder="Keterangan Tambahan Naskah"></textarea>
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
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
@endsection


@section('jsNeeded')
<script>
function getSelectedPenulis(id, table) {
    $.ajax({
        url: `{{url('/penerbitan/naskah')}}`,
        data: {
            request_: 'selectedPenulis',
            id: id
        },
        success: function(data) {
            html_ = `<tr id="prow_${data.id}">
                        <td>${data.nama}</td>
                        <td>${data.email}</td>
                        <td>${data.ponsel_domisili!=undefined?data.ponsel_domisili:(data.telepon_domisili!=undefined?data.telepon_domisili:'-')}</td>
                        <td>
                            <a href="{{url('penerbitan/penulis/detail-penulis/${data.id}')}}" class="btn btn-sm btn-primary pd-3" target="_blank">Lihat</a>
                        </td>
                    </tr>`
            $('#'+table).find('tbody').append(html_)
        },
        error: function(err) {

        }
    });
}
function resetFrom(form) {
    form.trigger('reset');
    $('[name="add_kelompok_buku"]').val('').trigger('change');
    $('[name="add_jalur_buku"]').val('').trigger('change');
    $('[name="add_pic_prodev"]').val('').trigger('change');
    $('[name="add_penulis[]"]').val('').trigger('change');
    $('#tb_selectedPenulis').find('tbody').empty();
    $('[name="add_file_naskah"]').next('.custom-file-label').html('Choose file');
    $.ajax({
        type: "GET",
        url: "{{url('penerbitan/naskah')}}",
        data: {request_: 'getKodeNaskah'},
        success: function(data) {
            $('[name="add_kode"]').val(data);
        }
    });
}
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

    $('.custom-file-input').on('change',function(e){
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");
        $(this).next('.custom-file-label').html(fileName);
    })

    $('#add_penulis').select2({
        multiple: true,
        minimumInputLength: 1,
        minimumResultsForSearch: 10,
        ajax: {
            url: "{{url('penerbitan/naskah')}}",
            type: "GET",
            delay: 650,
            data: function (params) {
                var queryParameters = {
                    request_: 'selectPenulis',
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                                text: item.nama,
                                id: item.id
                            }
                    })
                };
            }
        }
    }).on('select2:select', function(e) {
        getSelectedPenulis(e.params.data.id, 'tb_selectedPenulis');
    }).on('select2:unselect', function(e) {
        $('#prow_'+e.params.data.id).remove();
    });

    let addNaskah = jqueryValidation_('#fadd_Naskah', {
        add_judul_asli: {required: true},
        add_kode: {required: true},
        add_kelompok_buku: {required: true},
        add_jalur_buku: {required: true},
        add_tanggal_masuk_naskah: {required: true},
        add_tentang_penulis: {required: true},
        add_hard_copy: {required: true},
        add_soft_copy: {required: true},
        add_cdqr_code: {required: true},
        add_pic_prodev: {required: true},
        add_file_naskah: { required: true, extension: "pdf", maxsize: 500000, },
        add_file_tambahan_naskah: { extension: "rar|zip", maxsize: 500000, }
    });

    function ajaxAddNaskah(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/membuat-naskah')}}",
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
                notifToast('success', 'Data naskah berhasil disimpan!');
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
                notifToast('error', 'Data naskah gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fadd_Naskah').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="add_judul_asli"]').val();
            swal({
                text: 'Tambah data Naskah ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxAddNaskah($(this))
                }
            });

        }
    })
})
</script>
@endsection
