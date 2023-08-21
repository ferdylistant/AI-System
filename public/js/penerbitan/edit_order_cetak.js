function loadDataValue() {
    let id = window.location.search.split('?').pop(),
        cardWrap = $('.section-body').find('.card');
    $.ajax({
        url: window.location.origin + "/penerbitan/order-cetak/edit?" + id,
        beforeSend: function () {
            cardWrap.addClass('card-progress');
        },
        success: function (result) {
            let {
                data,
                penulis,
                disable,
                cursor,
                disableCetakUlang
            } = result;
            // console.log(result);
            for (let p of penulis) {
                $(".select-penulis").select2("trigger", "select", {
                    data: {
                        id: p.id,
                        text: p.nama
                    }
                });
            }
            for (let n in data) {
                // console.log(data[n]);
                switch (n) {
                    case 'id':
                        $('[name="up_id"]').attr("data-id", data[n]).change();
                        var id = data[n];
                        break;
                    case 'kelompok_buku_id':
                        $('[name="up_kelompok_buku"]').val([data[n]].id).change();
                        $("#kelBuku").select2("trigger", "select", {
                            data: {
                                id: data[n].id,
                                text: data[n].nama
                            }
                        });
                        $('[name="up_kelompok_buku"]').attr('disabled', disableCetakUlang).change();
                        break;
                    case 'sub_kelompok_buku_id':
                        $('[name="up_sub_kelompok_buku"]').val([data[n]].id).change();
                        $("#sKelBuku").select2("trigger", "select", {
                            data: {
                                id: data[n].id,
                                text: data[n].nama
                            }
                        });
                        $('[name="up_sub_kelompok_buku"]').attr('disabled', disableCetakUlang).change();
                        break;
                    case 'sub_judul_final':
                        if (data[n]) {
                            $('[name="up_' + n + '"]').val(data[n]).change();
                        } else {
                            $('[name="up_' + n + '"]').val('-').change();
                        }
                        $('[name="up_' + n + '"]').attr('disabled', disableCetakUlang).change();
                        break;
                    case 'buku_jadi':
                        $('[name="up_buku_jadi"]').val([data[n]]);
                        $('[name="up_buku_jadi"]').attr('disabled', disableCetakUlang).change();
                        break;
                    // case 'format_buku':
                    //     $('[name="up_format_buku"]').val([data[n]]);
                    //     break;
                    case 'finishing_cover':
                        $.map(JSON.parse(data[n]), function (item) {
                            $(".select-finishing-cover").select2("trigger", "select", {
                                data: {
                                    id: item,
                                    text: item
                                }
                            });
                        });
                        $('.select-finishing-cover').attr('disabled', disableCetakUlang).change();
                        break;
                    case 'jumlah_cetak':
                        $('[name="up_' + n + '"]').val(data[n]).change();
                        if (disableCetakUlang == true) {
                            $('#fup_OrderCetak #jumCetakInput').focus();
                        }
                        break;
                    case 'ukuran_jilid_binding':
                        if (data[n]) {
                            $('#ukuranBinding').show('slow');
                            $('#ukuranBinding').html(`<div class="form-group" style="display:none" id="divBinding"><label>Ukuran Binding: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-ruler"></i></div>
                                </div>
                                <input type="text" class="form-control" name="up_ukuran_jilid_binding" placeholder="Ukuran Binding" required>
                                <div id="err_up_ukuran_jilid_binding"></div>
                                <div class="input-group-append">
                                    <span class="input-group-text"><strong>cm</strong></span>
                                </div>
                                </div></div>`);
                            $('[name="up_' + n + '"]').val([data[n]]).change();
                            $('[name="up_' + n + '"]').attr('disabled', disableCetakUlang).change();
                            $('#divBinding').show('slow');
                        } else {
                            $('#ukuranBinding').html(`<div class="form-group" style="display:none" id="divBinding"><label>Ukuran Binding: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-ruler"></i></div>
                                </div>
                                <input type="text" class="form-control" name="up_ukuran_jilid_binding" placeholder="Ukuran Binding" required>
                                <div id="err_up_ukuran_jilid_binding"></div>
                                <div class="input-group-append">
                                    <span class="input-group-text"><strong>cm</strong></span>
                                </div>
                                </div></div>`);
                        }
                        break;
                    case 'posisi_layout':
                        if (data[n]) {
                            $('[name="up_' + n + '"]').val(data[n]).change();
                            $('[name="up_' + n + '"]').attr('disabled', disableCetakUlang).change();
                            $.ajax({
                                url: window.location.origin + "/list/list-dami-data",
                                type: "GET",
                                data: "value=" + data[n] + "&id=" + id,
                                beforeSend: function () {
                                    cardWrap.addClass('card-progress');
                                },
                                success: function (hasil) {
                                    $("#fup_OrderCetak #dami").empty();
                                    $("#fup_OrderCetak #dami").html(hasil);
                                },
                                error: function (hasil) {
                                    // $("#fup_OrderCetak #dami").empty();
                                    $("#fup_OrderCetak #dami").html(hasil);
                                },
                                complete: function () {
                                    cardWrap.removeClass('card-progress');
                                }
                            });

                        }
                        break;
                    default:
                        $('[name="up_' + n + '"]').val(data[n]).change();
                        $('[name="up_' + n + '"]').attr('disabled', disableCetakUlang).change();
                        break;
                }
            }
            $('#fup_OrderCetak button').attr('disabled', disable).change();
            $('#fup_OrderCetak button').css('cursor', cursor).change();
        },
        error: function (err) {
            console.log(err);
            notifToast("error", "Terjadi kesalahan!");
        },
        complete: function () {
            cardWrap.removeClass('card-progress');
        }

    })
}
$(document).ready(function () {
    $(".select2")
        .select2({
            placeholder: "Pilih",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-finishing-cover")
        .select2({
            placeholder: "Pilih",
            multiple: true,
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-penulis")
        .select2({
            multiple: true,
            disabled: true,
            ajax: {
                url: window.location.origin + "/penerbitan/naskah",
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
        });
    $(".datepicker-year").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        clearBtn: true,
    });
    $(".datepicker").datepicker({
        format: "dd MM yyyy",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
    });
    $(document).ready(function () {
        $.ajax({
            type: "GET",
            url: window.location.origin + "/list/get-layout",
            success: function (hasil) {
                var list = hasil;
                if (typeof hasil == "string") {
                    list = JSON.parse(hasil);
                }
                $.each(hasil, function (index, data) {
                    $("#posisiLayout").append(
                        $("<option></option>")
                            .attr("value", data.value)
                            .text(data.label)
                    );
                });
                loadDataValue();
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            },
        });
    });
    $("#posisiLayout").on("change", function () {
        let cardWrap = $('.section-body').find('.card');
        var val = $(this).val();
        $.ajax({
            url: window.location.origin + "/list/list-dami",
            type: "GET",
            data: "value=" + val,
            beforeSend: function () {
                cardWrap.addClass('card-progress');
            },
            success: function (hasil) {
                $("#dami").empty();
                $("#dami").html(hasil);
            },
            error: function (hasil) {
                $("#dami").empty();
                $("#dami").html(hasil);
            },
            complete: function () {
                cardWrap.removeClass('card-progress');
            }
        });
    });
    let editFormValid = jqueryValidation_("#fup_OrderCetak", {
        up_edisi_cetak: {
            required: true,
            number: true
        },
        up_jml_hal_final: {
            required: true,
            number: true
        },
        up_kelompok_buku: {
            required: true,
        },
        up_sub_kelompok_buku: {
            required: true,
        },
        up_tipe_order: {
            required: true,
        },
        up_posisi_layout: {
            required: true,
        },
        up_dami: {
            required: true,
        },
        up_format_buku: {
            required: true,
        },
        up_jilid: {
            required: true,
        },
        up_ukuran_jilid_binding: {
            number: true,
        },
        up_kertas_isi: {
            required: true,
        },
        up_isi_warna: {
            required: true,
        },
        up_jenis_cover: {
            required: true,
        },
        up_kertas_cover: {
            required: true,
        },
        up_warna_cover: {
            required: true,
        },
        up_finishing_cover: {
            required: true,
        },
        up_buku_jadi: {
            required: true,
        },
        up_jumlah_cetak: {
            required: true,
        },
        up_tahun_terbit: {
            required: true,
        },
        up_tgl_permintaan_jadi: {
            required: true,
        },
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
    }).on("select2:select", function (e) {
        getSubKelompokVal(e.params.data.id);
    });
    function getSubKelompokVal(id) {
        $("#sKelBuku").select2({
            placeholder: 'Pilih',
            ajax: {
                url: window.location.origin + "/penerbitan/naskah/membuat-naskah",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        request_select: "selectSub",
                        term: params.term,
                        kelompok_id: id,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.nama,
                            };
                        }),
                    };
                },
            },
        });
    }
    function ajaxUpOrderCetak(data) {
        let el = data.get(0);
        let id = data.data("id");
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/order-cetak/edit?id=" +
                id,
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                console.log(result);
                if (result.status == "error") {
                    // resetFrom(data);
                    notifToast(result.status, result.message);
                } else {
                    notifToast(result.status, result.message);
                    window.location.reload();
                }
            },
            error: function (err) {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    editFormValid.showErrors(err)
                }
                notifToast("error", "Data order cetak gagal disimpan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fup_OrderCetak").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="up_judul_final"]').val();
            swal({
                text: "Ubah data order cetak (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpOrderCetak($(this));
                }
            });
        }
    });
    $(document).ready(function () {
        $('#jilidChange').change(function () {
            if (this.value == 'Binding')
                showDetail(this.value);
            else
                just_hide(this.value);
        });
    });

    function showDetail(ele) {
        $('#div' + ele).show('slow');
        $('#ukuranBinding').show('slow');
        $('#formJilid').attr("class", "form-group col-12 col-md-3 mb-4");
    }

    function just_hide(ele) {
        $('#divBinding').hide('slow');
        $('#ukuranBinding').hide('slow');
        $('#formJilid').attr("class", "form-group col-12 col-md-6 mb-4");
    }

});
