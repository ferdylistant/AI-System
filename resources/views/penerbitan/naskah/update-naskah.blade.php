@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{ asset('vendors/izitoast/dist/css/iziToast.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendors/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
@endsection

@section('cssNeeded')
<style>
    /*Select2 ReadOnly Start*/
    select[readonly].select2-hidden-accessible+.select2-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection__arrow,
    select[readonly].select2-hidden-accessible+.select2-container .select2-selection__clear {
        display: none;
    }

    /*Select2 ReadOnly End*/
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
        </div>
        <h1>Ubah Data Naskah</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{url('/')}}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{url('/penerbitan/naskah')}}">Data Naskah</a>
            </div>
            <div class="breadcrumb-item">
                Ubah Data Naskah
            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <form id="fedit_Naskah">
                        {!! csrf_field() !!}
                        <div class="card-header">
                            <h4 class="section-title">Form Naskah</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Judul Asli: <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="edit_judul_asli" placeholder="Judul Asli Naskah" style="height: 64px !important;resize:none !important"></textarea>
                                    <input type="hidden" name="edit_id">
                                    <div id="err_edit_judul_asli"></div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Kode Naskah: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="edit_kode" value="" placeholder="Kode Naskah" readonly>
                                        <div id="err_edit_kode"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Kelompok Buku: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                        </div>
                                        <select class="form-control select2" name="edit_kelompok_buku">
                                            <option label="Pilih"></option>
                                            @foreach ($kbuku as $kb)
                                            <option value="{{ $kb->id }}">{{ $kb->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div id="err_edit_kelompok_buku"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Jalur Buku: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                        </div>
                                        <select class="form-control select2" name="edit_jalur_buku">
                                            <option label="Pilih"></option>
                                            @php $arrJB = ['Reguler', 'MoU', 'MoU-Reguler', 'SMK/NonSMK', 'Pro Literasi']; @endphp
                                            @foreach ($arrJB as $v)
                                            <option value="{{ $v }}">{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        <div id="err_edit_jalur_buku"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Tanggal Masuk Naskah: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input type="text" class="form-control datepicker" name="edit_tanggal_masuk_naskah" placeholder="Hari Bulan Tahun">
                                        <div id="err_edit_tanggal_masuk_naskah"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-3 mb-4">
                                    <label>Rencana CD/QR Code:</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_cdqr_code" value="1" id="edit_cdqr_yes">
                                        <label class="form-check-label mr-4" for="edit_cdqr_yes">Ya</label>
                                        <input class="form-check-input" type="radio" name="edit_cdqr_code" value="0" id="edit_cdqr_no">
                                        <label class="form-check-label" for="edit_cdqr_no">Tidak</label>
                                    </div>
                                    <div id="err_edit_cdqr_code" style="display: block;"></div>
                                </div>
                                <div class="form-group col-12 col-md-3 mb-4">
                                    <label class="d-block">Sumber Naskah: <span class="text-danger">*</span></label>
                                    @foreach ($hcsc as $v)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="edit_sumber_naskah[]" id="cb{{ $v }}" value="{{ $v }}" required>
                                        <label class="form-check-label" for="cb{{ $v }}">{{ $v == 'SC' ? 'Soft Copy' : 'Hard Copy' }}</label>
                                    </div>
                                    @endforeach
                                    <div id="err_edit_sumber_naskah" style="display: block;"></div>
                                </div>
                                <div class="col-12 col-md-12" id="urlFile"></div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>PIC Prodev: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                        </div>
                                        <select class="form-control select2" name="edit_pic_prodev">
                                            <option label="Pilih"></option>
                                            @foreach ($user as $u)
                                            <option value="{{ $u->id }}">{{ $u->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div id="err_edit_pic_prodev"></div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6 mb-4">
                                    <label>Penulis: <span class="text-danger">*</span></label>
                                    <select id="edit_penulis" class="form-control select2" name="edit_penulis[]" multiple="" required></select>
                                    <div id="err_edit_penulis"></div>
                                </div>
                                <div class="form-group col-12 mb-4 table-responsive ">
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
                                        <textarea class="form-control" name="edit_keterangan" placeholder="Keterangan Tambahan Naskah" style="height: 100px !important;resize:none !important" required></textarea>
                                        <div id="err_edit_keterangan"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="edit_urgent" class="custom-control-input" id="urgentNaskah">
                                <label class="custom-control-label mr-3 text-dark" for="urgentNaskah">
                                    Naskah Urgent
                                </label>
                            </div>
                            <button type="submit" class="btn btn-warning" id="btnSimpan">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('jsRequired')
<script type="text/javascript" src="{{ asset('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
@endsection


@section('jsNeeded')
<script type="text/javascript">
    function getSelectedPenulis(id, table) {
        $.ajax({
            url: `{{ url('/penerbitan/naskah') }}`,
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
                        <a href="{{ url('penerbitan/penulis/detail-penulis/${data.id}') }}" class="btn btn-sm btn-primary pd-3" target="_blank">Lihat</a>
                    </td>
                </tr>`
                $('#' + table).find('tbody').append(html_)
            },
            error: function(err) {

            }
        });
    }

    function loadDataPenulis() {
        let id = window.location.pathname.split('/').pop(),
            cardWrap = $('.section-body').find('.card');

        $.ajax({
            url: '{!! url("penerbitan/naskah/mengubah-naskah/' + id + '") !!}',
            beforeSend: function() {
                cardWrap.addClass('card-progress');
            },
            success: function(result) {
                let {
                    naskah,
                    penulis,
                    disabled
                } = result;
                for (let p of penulis) {
                    $("#edit_penulis").select2("trigger", "select", {
                        data: {
                            id: p.id,
                            text: p.nama
                        }
                    });
                }
                if (naskah.tgl_pn_prodev) {
                    $('[name="edit_pic_prodev"]').attr("readonly", true);
                }
                for (let n in naskah) {
                    // console.log(naskah[n]);
                    const rdio = ['cdqr_code']
                    if (rdio.includes(n)) {
                        $('[name="edit_' + n + '"]').val([naskah[n]]);
                        $('[name="edit_' + n + '"]').attr('disabled',disabled).change();
                    } else if (n == 'kelompok_buku_id') {
                        $('[name="edit_kelompok_buku"]').val([naskah[n]]).change();
                        $('[name="edit_' + n + '"]').attr('disabled',disabled).change();
                    } else if (n == 'sumber_naskah') {
                        $('#fedit_Naskah').find(':checkbox[name^="edit_sumber_naskah"]').each(function() {
                            $(this).prop("checked", ($.inArray($(this).val(), JSON.parse(naskah[n])) != -1));
                        });
                        $('[name^="edit_' + n + '"]').attr('disabled',disabled).change();
                    } else if (n == 'urgent') {
                        // console.log(naskah[n]);
                        var checked = naskah[n] == 1 ? true:false;
                        var valueData = naskah[n] == 1? 'on' : '0';
                        // $('[name="edit_' + n + '"]').val(valueData).change();
                        $('[name="edit_' + n + '"]').attr('checked',checked).change();
                        $('[name="edit_' + n + '"]').attr('disabled',disabled).change();
                    } else if (n == 'url_file') {
                        if (naskah[n]) {
                            $('#urlFile').html(`<div class="form-group" style="display:none" id="SC">
                                    <label>URL File: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="edit_url_file">
                                    <div id="err_edit_url_file"></div>
                                </div>`);
                            $('[name="edit_' + n + '"]').val(naskah[n]).change();
                            $('#SC').show('slow');
                            $('[name="edit_' + n + '"]').attr('required',true);
                            $('[name="edit_' + n + '"]').attr('disabled',disabled).change();
                        } else {
                            $('#urlFile').html(`<div class="form-group" style="display:none" id="SC">
                                    <label>URL File: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="edit_url_file">
                                    <div id="err_edit_url_file"></div>
                                </div>`);
                            $('[name="edit_' + n + '"]').attr('required',false);
                        }
                    } else {
                        $('[name="edit_' + n + '"]').val(naskah[n]).change();
                        $('[name="edit_' + n + '"]').attr('disabled',disabled).change();
                        // $('#btnSimpan').attr('disabled',disabled).change();
                    }
                }
            },
            error: function(err) {
                // console.log(err)
            },
            complete: function() {
                cardWrap.removeClass('card-progress');
            }

        })
    }

    $(document).ready(function() {
        loadDataPenulis();
        $(".select2").select2({
            placeholder: 'Pilih',
        }).on('change', function(e) {
            if (this.value) {
                $(this).valid();
            }
        });

        $('.datepicker').datepicker({
            format: 'dd MM yyyy',
            autoclose: true,
            clearBtn: true,
            todayHighlight: true
        });

        $('#fileNaskah').on('change', function(e) {
            var fileName = $(this).val().replace('C:\\fakepath\\', " ");
            $(this).next('.custom-file-label').html(fileName);
        })

        $('#edit_penulis').select2({
            multiple: true,
            minimumInputLength: 2,
            minimumResultsForSearch: 10,
            ajax: {
                url: "{{ url('penerbitan/naskah') }}",
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

        let editNaskah = jqueryValidation_('#fedit_Naskah', {
            edit_judul_asli: {
                required: true
            },
            edit_kode: {
                required: true
            },
            edit_kelompok_buku: {
                required: true
            },
            edit_jalur_buku: {
                required: true
            },
            edit_tanggal_masuk_naskah: {
                required: true
            },
            edit_pic_prodev: {
                required: true
            },
        });

        function ajaxEditNaskah(data) {
            let el = data.get(0),
                id = data.find('[name="edit_id"]').val();

            $.ajax({
                type: "POST",
                url: '{!! url("penerbitan/naskah/mengubah-naskah/' + id + '") !!}',
                data: new FormData(el),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
                },
                success: function(result) {
                    console.log(result)
                    notifToast('success', 'Data naskah berhasil diubah!');
                },
                error: function(err) {
                    // console.log(err)
                    rs = err.responseJSON.errors;
                    if (rs != undefined) {
                        err = {};
                        Object.entries(rs).forEach(entry => {
                            let [key, value] = entry;
                            err[key] = value
                        })
                        // console.log(err)
                        editNaskah.showErrors(err);
                    }
                    notifToast('error', 'Data naskah gagal diubah!');
                },
                complete: function() {
                    $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
                }
            })
        }

        $('#fedit_Naskah').on('submit', function(e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="edit_judul_asli"]').val();
                swal({
                        text: 'Ubah data Naskah (' + nama + ')?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((confirm_) => {
                        if (confirm_) {
                            ajaxEditNaskah($(this))
                        }
                    });

            }
        })
    })
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#cbSC').change(function() {
            if (this.checked)
                showDetail(this.value);
            else
                just_hide(this.value);
        });
    });

    function showDetail(ele) {
        $('#' + ele).show('slow');
        $('[name="edit_url_file"]').attr('required',true);
    }

    function just_hide(ele) {
        $('#' + ele).hide('slow');
        $('[name="edit_url_file"]').attr('required',false);
    }
</script>
@endsection
