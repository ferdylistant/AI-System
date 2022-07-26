@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
<link rel="stylesheet" href="{{url('vendors/jquery-magnify/dist/jquery.magnify.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/summernote/dist/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{url('vendors/flipbook/min_version/ipages.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
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
            <a href="{{ url('penerbitan/penulis') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Ubah Data Penulis</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <form id="fedit_Penulis">
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
                                    <input type="hidden" name="edit_id" value="{{$penulis->id}}">
                                    <input type="text" class="form-control" name="edit_nama" value="{{$penulis->nama}}" placeholder="Nama Penulis">
                                    <div id="err_edit_nama"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Kewarganegaraan: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                    </div>
                                    <select class="form-control select2" name="edit_kewarganegaraan">
                                        <option label="Pilih" {{is_null($penulis->kewarganegaraan)?'Selected':''}}></option>
                                        <option value="WNI" {{$penulis->kewarganegaraan=='WNI'?'Selected':''}}>WNI</option>
                                        <option value="WNA" {{$penulis->kewarganegaraan=='WNA'?'Selected':''}}>WNA</option>
                                    </select>
                                    <div id="err_edit_kewarganegaraan"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tempat Lahir: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-globe"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_tempat_lahir" value="{{$penulis->tempat_lahir}}" placeholder="Daerah - Negara">
                                    <div id="err_edit_tempat_lahir"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tanggal Lahir: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="edit_tanggal_lahir" value="{{$penulis->tanggal_lahir}}" placeholder="Hari Bulan Tahun">
                                    <div id="err_edit_tanggal_lahir"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Telepon: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_telepon_domisili" value="{{$penulis->telepon_domisili}}" placeholder="No.Telepon Aktif">
                                    <div id="err_edit_telepon_domisili"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Ponsel: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-mobile"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_ponsel_domisili" value="{{$penulis->ponsel_domisili}}" placeholder="No.Ponsel Aktif">
                                    <div id="err_edit_ponsel_domisili"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Email: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_email" value="{{$penulis->email}}" placeholder="Alamat Email Aktif">
                                    <div id="err_edit_email"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Alamat Domisili: </label>
                                <div class="input-group">
                                    <textarea class="form-control" name="edit_alamat_domisili" placeholder="Alamat Lengkap Saat Ini">{{$penulis->alamat_domisili}}</textarea>
                                    <div id="err_edit_alamat_domisili"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Facebook: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fab fa-facebook-square"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_sosmed_fb" value="{{$penulis->sosmed_fb}}" placeholder="Akun Facebook">
                                    <div id="err_edit_sosmed_fb"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Twitter: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fab fa-twitter-square"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_sosmed_tw" value="{{$penulis->sosmed_tw}}" placeholder="Akun Twitter">
                                    <div id="err_edit_sosmed_tw"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Instagram: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fab fa-instagram"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_sosmed_ig" value="{{$penulis->sosmed_ig}}" placeholder="Akun Instagram">
                                    <div id="err_edit_sosmed_ig"></div>
                                </div>
                            </div>
                        </div>
                        
                        <h5>#2</h5><hr>
                        <div class="row  mb-5">
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Nama Kantor: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-building"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_nama_kantor" value="{{$penulis->nama_kantor}}" placeholder="Nama Kantor Saat Ini">
                                    <div id="err_edit_nama_kantor"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Jabatan Kantor: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user-circle"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_jabatan_dikantor" value="{{$penulis->jabatan_dikantor}}" placeholder="Jabatan Dikantor Saat Ini">
                                    <div id="err_edit_jabatan_dikantor"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Telepon Kantor: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_telepon_kantor" value="{{$penulis->telepon_kantor}}" placeholder="Telepon Kantor Saat Ini">
                                    <div id="err_edit_telepon_kantor"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Alamat Kantor: </label>
                                <div class="input-group">
                                    <textarea class="form-control" name="edit_alamat_kantor" placeholder="Alamat Lengkap Kantor Saat Ini">{{$penulis->alamat_kantor}}</textarea>
                                    <div id="err_edit_alamat_kantor"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row  mb-5">
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>No Rekening: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="far fa-credit-card"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_no_rek" value="{{$penulis->no_rekening}}" placeholder="No.Rekening">
                                    <div id="err_edit_no_rek"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Nama Bank: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-university"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_bank" value="{{$penulis->bank}}" placeholder="Nama Bank">
                                    <div id="err_edit_bank"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Atas Nama: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_bank_atasnama" value="{{$penulis->bank_atasnama}}" placeholder="Atas Nama No.Rekening">
                                    <div id="err_edit_bank_atasnama"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>NPWP: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-gavel"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_npwp" value="{{$penulis->npwp}}" placeholder="NPWP">
                                    <div id="err_edit_npwp"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>No.KTP: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="edit_ktp" value="{{$penulis->ktp}}" placeholder="No.KTP">
                                    <div id="err_edit_ktp"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Scan NPWP: </label>
                                @if(!is_null($penulis->scan_npwp)) 
                                <a class="btn btn-sm btn-primary" data-magnify="gallery"
                                    href="{{ url('storage/penerbitan/penulis/'.$penulis->id.'/'.$penulis->scan_npwp) }}"
                                >Lihat</a>
                                @endif
                                <div class="input-group">
                                    <div id="ip_npwp" class="image-preview">
                                        <label for="image-upload" id="il_npwp">Pilih File</label>
                                        <input type="file" name="edit_scan_npwp" id="iu_npwp" />
                                    </div>
                                    <div id="err_edit_scan_npwp" style="display: block;"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Scan KTP: </label>
                                @if(!is_null($penulis->scan_ktp)) 
                                <a class="btn btn-sm btn-primary" data-magnify="gallery"
                                    href="{{ url('storage/penerbitan/penulis/'.$penulis->id.'/'.$penulis->scan_ktp) }}"
                                >Lihat</a>
                                @endif
                                <div class="input-group">
                                    <div id="ip_ktp" class="image-preview">
                                        <label for="image-upload" id="il_ktp">Pilih File</label>
                                        <input type="file" name="edit_scan_ktp" id="iu_ktp" />
                                    </div>
                                    <div id="err_edit_scan_ktp" style="display: block;"></div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Foto Penulis: </label>
                                @if(!is_null($penulis->foto_penulis)) 
                                <a class="btn btn-sm btn-primary" data-magnify="gallery"
                                    href="{{ url('storage/penerbitan/penulis/'.$penulis->id.'/'.$penulis->foto_penulis) }}"
                                >Lihat</a>
                                @endif
                                <div class="input-group">
                                    <div id="ip_pp" class="image-preview">
                                        <label for="image-upload" id="il_pp">Pilih File</label>
                                        <input type="file" name="edit_foto_penulis" id="iu_pp" />
                                    </div>
                                    <div id="err_edit_foto_penulis" style="display: block;"></div>
                                </div>
                            </div>
                        </div>

                        <h5>#3</h5><hr>
                        <div class="row  mb-5">
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>File Tentang Penulis (<span class="text-danger">.pdf</span>) </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="edit_file_tentang_penulis" id="fileTP">
                                    <label class="custom-file-label" for="fileTP">Choose file</label>
                                </div>
                                <div id="err_edit_file_tentang_penulis" style="display: block;"></div>
                                @if(!is_null($penulis->file_tentang_penulis))
                                <div class="ipgs-flipbook" data-pdf-src="{{url('storage/penerbitan/penulis/'.$penulis->id.'/'.$penulis->file_tentang_penulis)}}" 
                                    data-book-engine="onepageswipe" style="max-height: 300px;"></div>
                                @endif
                            </div>
                            <div class="form-group col-8 mb-4">
                                <label>Tentang Penulis: </label>
                                <div class="input-group">
                                    <textarea class="form-control summernote-penulis" name="edit_tentang_penulis">{{$penulis->tentang_penulis}}</textarea>
                                    <div id="err_edit_tentang_penulis"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-warning">Simpan</button>
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
<script src="{{url('vendors/jquery-magnify/dist/jquery.magnify.min.js')}}"></script>
<script src="{{url('vendors/summernote/dist/summernote-bs4.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/pdf.min.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/jquery.ipages.min.js')}}"></script>
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
    $('[data-magnify]').magnify({
        resizable: false,
        headerToolbar: [
            'close'
        ],
    });

    $(".summernote-penulis").summernote({
        dialogsInBody: true,
        minHeight: 300,
        width: 1920,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['paragraph']]
        ]
    });

    $('.ipgs-flipbook').ipages({
        toolbarControls: [
            {type:'share',        active:false},
            {type:'sound',        active:false, optional: false},
            {type:'outline',      active:false},
            {type:'thumbnails',   active:true},
            {type:'gotofirst',    active:true},
            {type:'prev',         active:true},
            {type:'pagenumber',   active:true},
            {type:'next',         active:true},
            {type:'gotolast',     active:true},
            {type:'zoom-in',      active:false},
            {type:'zoom-out',     active:false},
            {type:'zoom-default', active:false},
            {type:'optional',     active:false},
            {type:'download',     active:false, optional: false},
            {type:'fullscreen',   active:true, optional: false},
        ],
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

    let editCabang = jqueryValidation_('#fedit_Penulis', {
        edit_nama: {required: true},
        edit_kewarganegaraan: {required: true},
        edit_tempat_lahir: {required: true},
        edit_tanggal_lahir: {required: true},
        edit_telp: {min:0, maxlength:20,  number: true},
        edit_ponsel: {min:0, maxlength:20,  number: true},
        edit_telp_kantor: {min:0, maxlength:20,  number: true},
        edit_file_tentang_penulis: { extension: "pdf", maxsize: 500000, },
        edit_scan_ktp: { extension: "jpg,jpeg,png", maxsize: 500000, },
        edit_scan_npwp: { extension: "jpg,jpeg,png", maxsize: 500000, },
        edit_foto_penulis: { extension: "jpg,jpeg,png", maxsize: 500000, },
    });

    function ajaxEditPenulis(data) {
        let el = data.get(0),
            id = data.find('[name="edit_id"]').val();
        $.ajax({
            type: "POST",
            url: "{!!url('penerbitan/penulis/mengubah-penulis/"+id+"')!!}",
            data: new FormData(el),
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
                notifToast('success', 'Data penulis berhasil disimpan!', true);
            },
            error: function(err) {
                rs = err.responseJSON.errors;
                if(rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    editCabang.showErrors(err);
                }
                
                notifToast('error', 'Data penulis gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }
    
    $('#fedit_Penulis').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="edit_nama"]').val();
            swal({
                text: 'Ubah data Penulis ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxEditPenulis($(this))
                }
            });
            
        }
    })
})
</script>
@endsection