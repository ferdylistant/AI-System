$(document).ready(function () {
    $("#korektorButton").click(function (e) {
        e.preventDefault();
        $("#korektorCol").attr("hidden", "hidden");
        $("#korektorColInput").removeAttr("hidden");
    });
    $(".batal_edit_korektor").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#korektorColInput").attr("hidden", "hidden");
        $("#korektorCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#setterButton").click(function (e) {
        e.preventDefault();
        $("#setterCol").attr("hidden", "hidden");
        $("#setterColInput").removeAttr("hidden");
    });
    $(".batal_edit_setter").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#setterColInput").attr("hidden", "hidden");
        $("#setterCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#jmlHalButton").click(function (e) {
        e.preventDefault();
        $("#jmlHalCol").attr("hidden", "hidden");
        $("#jmlHalColInput").removeAttr("hidden");
    });
    $(".batal_edit_jml").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#jmlHalColInput").attr("hidden", "hidden");
        $("#jmlHalCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#edCetakButton").click(function (e) {
        e.preventDefault();
        $("#edCetakCol").attr("hidden", "hidden");
        $("#edCetakColInput").removeAttr("hidden");
    });
    $(".batal_edit_edisicetak").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#edCetakColInput").attr("hidden", "hidden");
        $("#edCetakCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#catButton").click(function (e) {
        e.preventDefault();
        $("#catCol").attr("hidden", "hidden");
        $("#catColInput").removeAttr("hidden");
    });
    $(".batal_edit_cat").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#catColInput").attr("hidden", "hidden");
        $("#catCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#prosButton").click(function (e) {
        e.preventDefault();
        $("#prosCol").attr("hidden", "hidden");
        $("#prosColInput").removeAttr("hidden");
    });
    $(".batal_edit_proses").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#prosColInput").attr("hidden", "hidden");
        $("#prosCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#copyrightButton").click(function (e) {
        e.preventDefault();
        $("#copyrightCol").attr("hidden", "hidden");
        $("#copyrightColInput").removeAttr("hidden");
    });
    $(".batal_edit_copyright").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#copyrightColInput").attr("hidden", "hidden");
        $("#copyrightCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#copyrightSelButton").click(function (e) {
        e.preventDefault();
        $("#copyrightSelCol").attr("hidden", "hidden");
        $("#copyrightSelColInput").removeAttr("hidden");
    });
    $(".batal_edit_selesaicopyright").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#copyrightSelColInput").attr("hidden", "hidden");
        $("#copyrightSelCol").removeAttr("hidden");
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
    $(".select-proses")
        .select2({
            placeholder: "Pilih proses saat ini",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-setter")
        .select2({
            placeholder: "Pilih setter",
            multiple: true,
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-korektor")
        .select2({
            placeholder: "Pilih korektor",
            multiple: true,
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });

    $(".datepicker-copyright").datepicker({
        format: "dd MM yyyy",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
    });
    $(".datepicker").datepicker({
        format: "MM yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        clearBtn: true,
    });

    function ajaxUpPracetakSetter(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/pracetak/setter/edit",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $("#overlay").fadeIn(300);
            },
            success: function (result) {
                // console.log(result);
                notifToast(result.status, result.message);
                location.reload();
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
                notifToast("error", "Data pracetak setter gagal disimpan!");
            },
            complete: function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            },
        });
    }

    $("#fup_pracetakSetter").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="judul_final"]').val();
            swal({
                text: "Update data pracetak setter naskah, (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpPracetakSetter($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
        }
    });
});
