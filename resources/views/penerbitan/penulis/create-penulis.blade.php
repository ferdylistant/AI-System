@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
<link rel="stylesheet" href="{{url('vendors/summernote/dist/summernote-bs4.css')}}">
@endsection

@section('cssNeeded')
<style>
    .image-preview, #callback-preview {
        height: 200px;
    }
    .form-control.is-invalid + .select2 {
        border: 1px solid #dc3545 !important;
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ url('penerbitan/penulis')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Buat Data Penulis</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <form id="fadd_Penulis">
                    <div class="card-header">
                        <h4>Form Penulis</h4>
                    </div>
                    <div class="card-body">
                        <h5>#1</h5><hr>
                        <div class="row mb-5">
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Nama: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_nama" placeholder="Nama Penulis">
                                    <div id="err_add_nama"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Kewarganegaraan: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                    </div>
                                    <select class="form-control select2" name="add_kewarganegaraan">
                                        <option label="Pilih"></option>
                                        <option value="WNI">WNI</option>
                                        <option value="WNA">WNA</option>
                                    </select>
                                    <div id="err_add_kewarganegaraan"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tempat Lahir: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-globe"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_tempat_lahir" placeholder="Daerah - Negara">
                                    <div id="err_add_tempat_lahir"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tanggal Lahir: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="add_tanggal_lahir" placeholder="Hari Bulan Tahun">
                                    <div id="err_add_tanggal_lahir"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Telepon: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_telepon_domisili" placeholder="No.Telepon Aktif">
                                    <div id="err_add_telepon_domisili"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Ponsel: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-mobile"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_ponsel_domisili" placeholder="No.Ponsel Aktif">
                                    <div id="err_add_ponsel_domisili"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Email: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_email" placeholder="Alamat Email Aktif">
                                    <div id="err_add_email"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Alamat Domisili: </label>
                                <div class="input-group">
                                    <textarea class="form-control" name="add_alamat_domisili" placeholder="Alamat Lengkap Saat Ini"></textarea>
                                    <div id="err_add_alamat_domisili"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Facebook: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fab fa-facebook-square"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_sosmed_fb" placeholder="Akun Facebook">
                                    <div id="err_add_sosmed_fb"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Twitter: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fab fa-twitter-square"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_sosmed_tw" placeholder="Akun Twitter">
                                    <div id="err_add_sosmed_tw"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Instagram: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fab fa-instagram"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_sosmed_ig" placeholder="Akun Instagram">
                                    <div id="err_add_sosmed_ig"></div>
                                </div>
                            </div>
                        </div>
                        
                        <h5>#2</h5><hr>
                        <div class="row mb-5">
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Nama Kantor: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-building"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_nama_kantor" placeholder="Nama Kantor Saat Ini">
                                    <div id="err_add_nama_kantor"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Jabatan Kantor: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user-circle"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_jabatan_dikantor" placeholder="Jabatan Dikantor Saat Ini">
                                    <div id="err_add_jabatan_dikantor"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Telepon Kantor: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_telepon_kantor" placeholder="Telepon Kantor Saat Ini">
                                    <div id="err_add_telepon_kantor"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Alamat Kantor: </label>
                                <div class="input-group">
                                    <textarea class="form-control" name="add_alamat_kantor" placeholder="Alamat Lengkap Kantor Saat Ini"></textarea>
                                    <div id="err_add_alamat_kantor"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>No Rekening: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="far fa-credit-card"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_no_rek" placeholder="No.Rekening">
                                    <div id="err_add_no_rek"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Nama Bank: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-university"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_bank" placeholder="Nama Bank">
                                    <div id="err_add_bank"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Atas Nama: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_bank_atasnama" placeholder="Atas Nama No.Rekening">
                                    <div id="err_add_bank_atasnama"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>NPWP: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-gavel"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_npwp" placeholder="NPWP">
                                    <div id="err_add_npwp"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>No.KTP: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="add_ktp" placeholder="No.KTP">
                                    <div id="err_add_ktp"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Scan NPWP: </label>
                                <div class="input-group">
                                    <div id="ip_npwp" class="image-preview">
                                        <label for="image-upload" id="il_npwp">Pilih File</label>
                                        <input type="file" name="add_scan_npwp" id="iu_npwp" />
                                    </div>
                                    <div id="err_add_scan_npwp"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Scan KTP: </label>
                                <div class="input-group">
                                    <div id="ip_ktp" class="image-preview">
                                        <label for="image-upload" id="il_ktp">Pilih File</label>
                                        <input type="file" name="add_scan_ktp" id="iu_ktp" />
                                    </div>
                                    <div id="err_add_scan_ktp" style="display: block;"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Foto Penulis: </label>
                                <div class="input-group">
                                    <div id="ip_pp" class="image-preview">
                                        <label for="image-upload" id="il_pp">Pilih File</label>
                                        <input type="file" name="add_foto_penulis" id="iu_pp" />
                                    </div>
                                    <div id="err_add_foto_penulis" style="display: block;"></div>
                                </div>
                            </div>
                        </div>

                        <h5>#3</h5><hr>
                        <div class="row">
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>File Tentang Penulis (<span class="text-danger">.pdf</span>) </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="add_file_tentang_penulis" id="fileTP">
                                    <label class="custom-file-label" for="fileTP">Choose file</label>
                                </div>
                                <div id="err_add_file_tentang_penulis" style="display: block;"></div>
                            </div>
                            <div class="form-group col-12 mb-4">
                                <label>Tentang Penulis: </label>
                                <div class="input-group">
                                    <textarea class="form-control summernote-penulis" name="add_tentang_penulis"></textarea>
                                    <div id="err_add_tentang_penulis"></div>
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
<script src="{{url('vendors/upload-preview/assets/js/jquery.uploadPreview.min.js')}}"></script>
<script src="{{url('vendors/summernote/dist/summernote-bs4.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
@endsection


@section('jsNeeded')
<script>
$(function() {
    $(".select2").select2({
        placeholder: 'Pilih',
        minimumResultsForSearch: Infinity
    }).on('change', function() {
        $(this).valid();
    });

    $('.datepicker').datepicker({
        format: 'dd MM yyyy'
    });

    $(".summernote-penulis").summernote({
        dialogsInBody: true,
        minHeight: 150,
        width: 1920,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['paragraph']]
        ]
    });

    $.uploadPreview({
        input_field: "#iu_npwp",  
        preview_box: "#ip_npwp", 
        label_field: "#il_npwp",  
        label_default: "Pilih File",  
        label_selected: "Pilih File"
    });

    $.uploadPreview({
        input_field: "#iu_ktp",  
        preview_box: "#ip_ktp", 
        label_field: "#il_ktp",  
        label_default: "Pilih File",  
        label_selected: "Pilih File"
    });

    $.uploadPreview({
        input_field: "#iu_pp",  
        preview_box: "#ip_pp", 
        label_field: "#il_pp",  
        label_default: "Pilih File",  
        label_selected: "Pilih File"
    });

    let addCabang = jqueryValidation_('#fadd_Penulis', {
        add_nama: {required: true},
        add_kewarganegaraan: {required: true},
        add_tempat_lahir: {required: true},
        add_tanggal_lahir: {required: true},
        add_telp: {min:0, maxlength:20,  number: true},
        add_ponsel: {min:0, maxlength:20,  number: true},
        add_telp_kantor: {min:0, maxlength:20,  number: true},
        add_file_tentang_penulis: { extension: "pdf", maxsize: 2000000, },
        add_scan_ktp: { extension: "jpg,jpeg,png" },
        add_scan_npwp: { extension: "jpg,jpeg,png" },
        add_foto_penulis: { extension: "jpg,jpeg,png" },
    });

    async function ajaxAddPenulis(data) {
        let el = new FormData(data.get(0));
        let imgFP = $(data).find('[name="add_foto_penulis"]')[0].files[0];
        let imgKTP = $(data).find('[name="add_scan_ktp"]')[0].files[0];
        let imgNPWP = $(data).find('[name="add_scan_npwp"]')[0].files[0];

        if(imgFP) {
            imgFP = await resizeImage({
                file: imgFP,
                maxSize: 500
            });
            el.append('add_foto_penulis', imgFP);
        }
        if(imgKTP) {
            imgKTP = await resizeImage({
                file: imgKTP,
                maxSize: 700
            });
            el.append('add_scan_ktp', imgKTP);
        }
        if(imgNPWP) {
            imgNPWP = await resizeImage({
                file: imgNPWP,
                maxSize: 700
            });
            el.append('add_scan_npwp', imgNPWP);
        }

        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/penulis/membuat-penulis')}}",
            data: el,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                data.find('[type="file"]').each((i, el) => {
                    $(el).parent().removeAttr('style')
                })
                $('.summernote-penulis').summernote('reset');
                data.trigger('reset');
                notifToast('success', 'Data penulis berhasil disimpan!');
            },
            error: function(err) {
                rs = err.responseJSON.errors;
                if(rs != undefined) {
                    err = {};
                    console.log(rs)
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    addCabang.showErrors(err);
                }
                notifToast('error', 'Data penulis gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }
    
    $('#fadd_Penulis').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="add_nama"]').val();
            swal({
                text: 'Tambah data Penulis ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxAddPenulis($(this))
                }
            });
            
        }
    })
})
</script>
@endsection