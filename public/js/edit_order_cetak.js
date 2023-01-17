$(document).ready(function () {
    let cardWrap = $('.section-body').find('.card');
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
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
        },
    });
    $(document).ready(function () {
        $("#posisiLayout").on("change", function () {
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
    });
});
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
                penulis
            } = result;
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
                        break;
                    case 'kelompok_buku_id':
                        $('[name="up_kelompok_buku"]').val([data[n]]).change();
                        break;
                    case 'sub_judul_final':
                        if (data[n]) {
                            $('[name="up_' + n + '"]').val(data[n]).change();
                        } else {
                            $('[name="up_' + n + '"]').val('-').change();
                        }
                        break;
                    case 'buku_jadi':
                        $('[name="up_buku_jadi"]').val([data[n]]);
                        break;
                    case 'finishing_cover':
                        $.map(JSON.parse(data[n]), function (item) {
                            $(".select-finishing-cover").select2("trigger", "select", {
                                data: {
                                    id: item,
                                    text: item
                                }
                            });
                        });
                        break;
                    case 'ukuran_jilid_binding':
                        if (data[n]) {
                            $('#ukuranBinding').show('slow');
                            $('#ukuranBinding').html(`<div class="form-group" style="display:none" id="divBinding"><label>Ukuran Binding: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-ruler"></i></div>
                                </div>
                                <input type="number" class="form-control" min="1" name="up_ukuran_jilid_binding" placeholder="Ukuran Binding" required>
                                <div id="err_up_ukuran_jilid_binding"></div>
                                <div class="input-group-append">
                                    <span class="input-group-text"><strong>cm</strong></span>
                                </div>
                                </div></div>`);
                            $('[name="up_' + n + '"]').val([data[n]]).change()
                            $('#divBinding').show('slow');
                        } else {
                            $('#ukuranBinding').html(`<div class="form-group" style="display:none" id="divBinding"><label>Ukuran Binding: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-ruler"></i></div>
                                </div>
                                <input type="number" class="form-control" min="1" name="up_ukuran_jilid_binding" placeholder="Ukuran Binding" required>
                                <div id="err_up_ukuran_jilid_binding"></div>
                                <div class="input-group-append">
                                    <span class="input-group-text"><strong>cm</strong></span>
                                </div>
                                </div></div>`);
                        }
                        break;
                    case 'dami':
                        $('[name="up_' + n + '"]').select2("trigger", "select", {
                            data: {
                                id: data[n],
                                text: data[n]
                            }
                        });
                        break;
                    default:
                        $('[name="up_' + n + '"]').val(data[n]).change();
                        break;
                }
            }
        },
        error: function (err) {
            // console.log(err)
        },
        complete: function () {
            cardWrap.removeClass('card-progress');
        }

    })
}
$(function () {
    loadDataValue();
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
                console.log(err.responseJSON);
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                }
                notifToast("error", "Data order e-book gagal disimpan!");
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
                text: "Ubah data order e-book (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpOrderCetak($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
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
