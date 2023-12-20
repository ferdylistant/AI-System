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
            <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
        </div>
        <h1>Buat Naskah</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{url('/')}}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{url('/penerbitan/naskah')}}">Data Naskah</a>
            </div>
            <div class="breadcrumb-item">
                Buat Naskah
            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <form id="fadd_Naskah">
                        {!! csrf_field() !!}
                        <div class="card-header">
                            <h4 class="section-title">Form Naskah</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Judul Asli: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="add_judul_asli" placeholder="Judul Asli Naskah" style="height: 64px !important;resize:none !important"></textarea>
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
                                        <select id="kelBuku" class="form-control select2" name="add_kelompok_buku">
                                            <option label="Pilih"></option>
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
                                    <label>Rencana CD/QR Code:</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="add_cdqr_code" value="1" id="add_cdqr_yes">
                                        <label class="form-check-label mr-4" for="add_cdqr_yes">Ya</label>
                                        <input class="form-check-input" type="radio" name="add_cdqr_code" value="0" id="add_cdqr_no">
                                        <label class="form-check-label" for="add_cdqr_no">Tidak</label>
                                    </div>
                                    {{-- <div id="err_add_cdqr_code" style="display: block;"></div> --}}
                                </div>
                                <div class="form-group col-12 col-md-3 mb-4">
                                    <label class="d-block">Sumber Naskah: <span class="text-danger">*</span></label>
                                    @php
                                        $hcsc = ['HC','SC'];
                                    @endphp
                                    @foreach ($hcsc as $v)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="add_sumber_naskah[]" id="label{{$v}}" value="{{$v}}" required>
                                        <label class="form-check-label" for="label{{$v}}">{{$v == 'HC'? 'Hard Copy':'Soft Copy'}}</label>
                                    </div>
                                    @endforeach
                                    <div id="err_add_sumber_naskah" style="display: block;"></div>
                                </div>
                                <div class="form-group col-12 col-md-12 mb-4" style="display:none" id="SC">
                                    <label>URL File: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" pattern="^(https?://)?(?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,})$" name="add_url_file">
                                    <div id="err_add_url_file"></div>
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
                                    <label>Keterangan: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="add_keterangan" placeholder="Keterangan Tambahan Naskah" style="height: 100px !important;resize:none !important" required></textarea>
                                        <div id="err_add_keterangan"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="add_urgent" class="custom-control-input" id="urgentNaskah">
                                <label class="custom-control-label mr-3 text-dark" for="urgentNaskah">
                                    Naskah Urgent
                                </label>
                            </div>
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
                $('#' + table).find('tbody').append(html_)
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
        $('[name="add_urgent"]').val('').trigger('change');
        $('#tb_selectedPenulis').find('tbody').empty();
        // $('[name="add_file_naskah"]').next('.custom-file-label').html('Choose file');
        $.ajax({
            type: "GET",
            url: "{{url('penerbitan/naskah')}}",
            data: {
                request_: 'getKodeNaskah'
            },
            success: function(data) {
                $('[name="add_kode"]').val(data);
            }
        });
    }
    $(function() {
        $(".select2").select2({
            placeholder: 'Pilih',
        }).on('change', function(e) {
            if (this.value) {
                $(this).valid();
            }
        });
        $('#kelBuku').select2({
            placeholder: 'Pilih',
            ajax: {
                url: window.location.origin + "/penerbitan/naskah/membuat-naskah",
                type: "GET",
                cache: true,
                data: function (params) {
                    var queryParameters = {
                        request_select: "selectKategoriAll",
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.id,
                            };
                        }),
                    };
                },
            },
        });
        $('.datepicker').datepicker({
            format: 'dd MM yyyy',
            autoclose: true,
            clearBtn: true,
        });

        $('.custom-file-input').on('change', function(e) {
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
                data: function(params) {
                    var queryParameters = {
                        request_: 'selectPenulis',
                        term: params.term
                    }
                    return queryParameters;
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
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
            $('#prow_' + e.params.data.id).remove();
        });

        let addNaskah = jqueryValidation_('#fadd_Naskah', {
            add_judul_asli: {
                required: true
            },
            add_kode: {
                required: true
            },
            add_kelompok_buku: {
                required: true
            },
            add_jalur_buku: {
                required: true
            },
            add_tanggal_masuk_naskah: {
                required: true
            },
            add_tentang_penulis: {
                required: true
            },
            add_pic_prodev: {
                required: true
            },
            // add_file_naskah: {
            //     required: true,
            //     extension: "pdf",
            //     maxsize: 500000,
            // },
            // add_file_tambahan_naskah: {
            //     extension: "rar|zip",
            //     maxsize: 500000,
            // }
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
                    resetFrom(data);
                    notifToast('success', 'Data naskah berhasil disimpan!');
                },
                error: function(err) {
                    // console.log(err.responseJSON)
                    rs = err.responseJSON.errors;
                    if (rs != undefined) {
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
            if ($(this).valid()) {
                let nama = $(this).find('[name="add_judul_asli"]').val();
                swal({
                        text: 'Tambah data Naskah (' + nama + ')?',
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
<script>
    $(document).ready(function() {
        $('#labelSC').change(function() {
            if (this.checked)
                showDetail(this.value);
            else
                just_hide(this.value);
        });
    });

    function showDetail(ele) {
        $('#' + ele).show('slow');
        $('[name="add_url_file"]').attr('required',true);
    }

    function just_hide(ele) {
        $('#' + ele).hide('slow');
        $('[name="add_url_file"]').attr('required',false);
    }
</script>
@endsection
