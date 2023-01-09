$(document).ready(function () {
    var max_fields = 15; //maximum input boxes allowed
    var wrapper = $(".input_fields_wrap"); //Fields wrapper
    var add_button = $(".add_field_button"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function (e) {
        //on add input button click
        e.preventDefault();
        if (x < max_fields) {
            //max input box allowed
            x++; //text box increment
            $(wrapper).append(
                '<div class="input-group-append"><input type="text" name="bullet[]" class="form-control" placeholder="Bullet"/><button type="button" class="btn btn-outline-danger remove_field text-danger align-self-center" data-toggle="tooltip" title="Hapus Field"><i class="fas fa-times"></i></button></div>'
            ); //add input box
        }
    });

    $(wrapper).on("click", ".remove_field", function (e) {
        //user click on remove text
        e.preventDefault();
        $(this).parent("div").remove();
        x--;
    });
});
$(document).ready(function () {
    $("#isiEdisiCetakButton").click(function (e) {
        e.preventDefault();
        $("#isiEdisiCetakCol").attr("hidden", "hidden");
        $("#isiEdisiCetakColInput").removeAttr("hidden");
    });
    $(".batal_edit_edisi_cetak").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#isiEdisiCetakColInput").attr("hidden", "hidden");
        $("#isiEdisiCetakCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#tipeOrderButton").click(function (e) {
        e.preventDefault();
        $("#tipeOrderCol").attr("hidden", "hidden");
        $("#tipeOrderColInput").removeAttr("hidden");
    });
    $(".batal_edit_tipe_order").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#tipeOrderColInput").attr("hidden", "hidden");
        $("#isiEdisiCetakCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#formatBukuButton").click(function (e) {
        e.preventDefault();
        $("#formatBukuCol").attr("hidden", "hidden");
        $("#formatBukuColInput").removeAttr("hidden");
    });
    $(".batal_edit_format").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#formatBukuColInput").attr("hidden", "hidden");
        $("#formatBukuCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#bulanButton").click(function (e) {
        e.preventDefault();
        $("#bulanCol").attr("hidden", "hidden");
        $("#bulanColInput").removeAttr("hidden");
    });
    $(".batal_edit_bulan").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#bulanColInput").attr("hidden", "hidden");
        $("#bulanCol").removeAttr("hidden");
    });
});
$(function () {
    $(".select-format-buku")
        .select2({
            placeholder: "Pilih format buku",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-tipe-order")
        .select2({
            placeholder: "Pilih tipe order",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".datepicker").datepicker({
        format: "MM yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        clearBtn: true,
    });

    function ajaxUpDeskripsiTurunCetak(data) {
        let el = data.get(0);
        let id = data.data('id');
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/deskripsi/turun-cetak/edit?id="+id,
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                // resetFrom(data);
                notifToast(result.status, result.message);
                location.href = result.route;
                // console.log(result);
                // location.reload();
            },
            error: function (err) {
                // console.log(err.responseJSON);
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    // upNaskah.showErrors(err);
                }
                notifToast("error", "Data deskripsi turun cetak gagal disimpan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fup_deskripsiTurunCetak").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="judul_final"]').val();
            // let id = $(this).data('id');
            swal({
                text: "Update data deskripsi turun cetak, (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpDeskripsiTurunCetak($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
        }
    });
});
$(document).ready(function () {
    $("#isiEdisiCetakButton").click(function (e) {
        e.preventDefault();
        $("#isiEdisiCetakCol").attr("hidden", "hidden");
        $("#isiEdisiCetakColInput").removeAttr("hidden");
    });
    $(".batal_edit_edisi_cetak").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#isiEdisiCetakColInput").attr("hidden", "hidden");
        $("#isiEdisiCetakCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#formatBukuButton").click(function (e) {
        e.preventDefault();
        $("#formatBukuCol").attr("hidden", "hidden");
        $("#formatBukuColInput").removeAttr("hidden");
    });
    $(".batal_edit_format").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#formatBukuColInput").attr("hidden", "hidden");
        $("#formatBukuCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#bulanButton").click(function (e) {
        e.preventDefault();
        $("#bulanCol").attr("hidden", "hidden");
        $("#bulanColInput").removeAttr("hidden");
    });
    $(".batal_edit_bulan").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#bulanColInput").attr("hidden", "hidden");
        $("#bulanCol").removeAttr("hidden");
    });
});
