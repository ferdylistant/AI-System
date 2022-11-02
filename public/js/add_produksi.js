$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: window.location.origin + "/list-jenis-mesin",
        success: function (hasil) {
            var list = hasil;
            if (typeof hasil == "string") {
                list = JSON.parse(hasil);
            }
            $.each(hasil, function (index, data) {
                $("#jenisMesin").append(
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
});
$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: window.location.origin + "/list-pilihan-terbit",
        success: function (hasil) {
            var list = hasil;
            if (typeof hasil == "string") {
                list = JSON.parse(hasil);
            }
            $.each(hasil, function (index, data) {
                $("#pilihanTerbit").append(
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
});
$(document).ready(function () {
    $("#gridPlatformDigital").hide();
    $("#eISBN").hide();
    $("#pilihanTerbit").change(function () {
        var tipe = $(this).val();
        $.ajax({
            url: window.location.origin + "/list-status-cetak",
            type: "GET",
            data: "value=" + tipe,
            success: function (hasil) {
                $("#statusCetak").empty();
                $("#statusCetak").html(hasil);
            },
            error: function (hasil) {
                $("#statusCetak").empty();
                $("#statusCetak").html(hasil);
            },
        });
        if (tipe == "1") {
            $("#penulis").attr("class", "form-group col-12 col-md-4 mb-4");
            $("#gridPlatformDigital").hide();
            $("#eISBN").hide();
        } else {
            $("#penulis").attr("class", "form-group col-12 col-md-6 mb-4");
            $("#gridPlatformDigital").show();
            $("#eISBN").show();
        }
    });
});
$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: window.location.origin + "/list-status-buku",
        success: function (hasil) {
            var list = hasil;
            if (typeof hasil == "string") {
                list = JSON.parse(hasil);
            }
            $.each(hasil, function (index, data) {
                $("#statusBuku").append(
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
});
$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: window.location.origin + "/get-layout",
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
    $("#posisiLayout").on("change", function () {
        var val = $(this).val();
        $.ajax({
            url: window.location.origin + "/list-dami",
            type: "GET",
            data: "value=" + val,
            success: function (hasil) {
                $("#dami").empty();
                $("#dami").html(hasil);
            },
            error: function (hasil) {
                $("#dami").empty();
                $("#dami").html(hasil);
            },
        });
    });
    $("#dami").on("change", function () {
        var valDam = $(this).val();
        var valPos = $("#posisiLayout").val();
        $.ajax({
            url: window.location.origin + "/list-format-buku",
            type: "GET",
            data: "dami=" + valDam + "&posisi=" + valPos,
            success: function (hasil) {
                $("#formatBuku").html(hasil);
            },
            error: function (hasil) {
                $("#formatBuku").html(hasil);
            },
        });
    });
});
$(document).ready(function () {
    $("#ukuranBending").hide();
    $("#jilidChange").on("change", function () {
        var tipe = $(this).val();
        if (tipe != "1") {
            $("#formJilid").attr("class", "form-group col-12 col-md-6 mb-4");
            $("#ukuranBending").hide();
        } else {
            $("#formJilid").attr("class", "form-group col-12 col-md-3 mb-4");
            $("#ukuranBending").show();
        }
    });
});
$(function () {
    $(".select2")
        .select2({
            placeholder: "Pilih",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
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
        startDate: "-0d",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
    });
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="add_tipe_order"]').val("").trigger("change");
        $('[name="add_status_cetak"]').val("").trigger("change");
        $('[name="add_judul_buku"]').val("").trigger("change");
        $('[name="add_platform_digital[]"]').val("").trigger("change");
        $('[name="add_urgent"]').val("").trigger("change");
        $('[name="add_isbn"]').val("").trigger("change");
        $('[name="add_edisi]"]').val("").trigger("change");
        $('[name="add_cetakan"]').val("").trigger("change");
        $('[name="add_tahun_terbit"]').val("").trigger("change");
        $('[name="add_tgl_permintaan_jadi"]').val("").trigger("change");
        $('[name="add_jilid"]').val("").trigger("change");
        $('[name="add_posisi_layout"]').val("").trigger("change");
        $('[name="add_ukuran_bending"]').val("").trigger("change");
    }
    // let addNaskah = jqueryValidation_('#fadd_Produksi', {
    //     add_tipe_order: {required: true},
    //     add_status_cetak: {required: true},
    //     add_judul_buku: {required: true},
    //     add_platform_digital: {required: true},
    //     add_urgent: {required: true},
    //     add_isbn: {required: true},
    //     add_edisi: {required: true},
    //     add_cetakan: {required: true},
    //     add_tgl_permintaan_jadi: {required: true},
    //     add_jilid : {required: true},
    //     add_posisi_layout: {required: true},
    //     add_ukuran_bending: {required: true},
    // });

    function ajaxAddProduksi(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/order-cetak/create",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                resetFrom(data);
                notifToast(result.status, result.message);
                location.href = result.redirect;
            },
            error: function (err) {
                // console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    // addNaskah.showErrors(err);
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

    $("#fadd_Produksi").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="add_judul_buku"]').val();
            swal({
                text: "Tambah data order cetak, (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxAddProduksi($(this));
                }
            });
        }
    });
});
