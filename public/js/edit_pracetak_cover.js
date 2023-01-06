$(function () {
    //! SELECT2
    $(".select-proses")
        .select2({
            placeholder: "Pilih proses saat ini",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-desainer")
        .select2({
            placeholder: "Pilih desainer",
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
    //! DATEPICKER
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
    //! PROSES UPDATE FORM
    function ajaxUpPracetakDesainer(data) {
        let el = data.get(0);
        let id = data.data('id');
        let idp = data.data('id_praset');
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/pracetak/designer/edit?id="+id+"&id_praset="+idp,
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $("#overlay").fadeIn(300);
            },
            success: function (result) {
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
                notifToast("error", "Data pracetak cover gagal disimpan!");
            },
            complete: function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            },
        });
    }
    function ajaxSelesaiRevisiDesainer(id) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/pracetak/designer/revision-done?id="+id,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $("#overlay").fadeIn(300);
            },
            success: function (result) {
                if (result.status == "error") {
                    notifToast(result.status, result.message);
                } else {
                    notifToast(result.status, result.message);
                    location.reload();
                }
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
                }
                notifToast("error", "Gagal melakukan penyetujuan!");
            },
            complete: function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            },
        });
    }
    //! CONFIRM SWEETALERT
    $("#fup_pracetakDesainer").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="judul_final"]').val();
            swal({
                text: "Update data pracetak cover naskah, (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpPracetakDesainer($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
        }
    });
    $("#done-revision").on("click", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let kode = $(this).data("kode");
        let judul = $(this).data("judul");
        swal({
            title: "Yakin desainer telah selesai merevisi naskah " + judul + "-" + kode + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kekeliruan data.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxSelesaiRevisiDesainer(id);
            }
        });
    });
});

//! TOGGLE INPUT
$(document).ready(function () {
    $("#korektorButton").click(function (e) {
        e.preventDefault();
        $("#korektorCol").attr("hidden", "hidden");
        $("#korektorColInput").removeAttr("hidden");
    });
    $(".batal_edit_korektor").click(function (e) {
        e.preventDefault();
        $("#korektorColInput").attr("hidden", "hidden");
        $("#korektorCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#desainerButton").click(function (e) {
        e.preventDefault();
        $("#desainerCol").attr("hidden", "hidden");
        $("#desainerColInput").removeAttr("hidden");
    });
    $(".batal_edit_desainer").click(function (e) {
        e.preventDefault();
        $("#desainerColInput").attr("hidden", "hidden");
        $("#desainerCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#jmlHalButton").click(function (e) {
        e.preventDefault();
        $("#jmlHalCol").attr("hidden", "hidden");
        $("#jmlHalColInput").removeAttr("hidden");
    });
    $(".batal_edit_jml").click(function (e) {
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
        e.preventDefault();
        $("#copyrightSelColInput").attr("hidden", "hidden");
        $("#copyrightSelCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#isbnButton").click(function (e) {
        e.preventDefault();
        $("#isbnCol").attr("hidden", "hidden");
        $("#isbnColInput").removeAttr("hidden");
    });
    $(".batal_edit_isbn").click(function (e) {
        e.preventDefault();
        $("#isbnColInput").attr("hidden", "hidden");
        $("#isbnCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#hargaButton").click(function (e) {
        e.preventDefault();
        $("#hargaCol").attr("hidden", "hidden");
        $("#hargaColInput").removeAttr("hidden");
    });
    $(".batal_edit_pengajuan_harga").click(function (e) {
        e.preventDefault();
        $("#hargaColInput").attr("hidden", "hidden");
        $("#hargaCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#bulanButton").click(function (e) {
        e.preventDefault();
        $("#bulanCol").attr("hidden", "hidden");
        $("#bulanColInput").removeAttr("hidden");
    });
    $(".batal_edit_bulan").click(function (e) {
        e.preventDefault();
        $("#bulanColInput").attr("hidden", "hidden");
        $("#bulanCol").removeAttr("hidden");
    });
});

//! SWITCH TOGGLE PROSES KERJA
function resetFrom(form) {
    form.trigger("reset");
    $('[name="proses"]').val("").trigger("change");
}
$(function() {
    $('#prosesKerja').click(function() {
        var id = $(this).data('id');
        var desainer = $('.select-desainer').val();
        var korektor = $('.select-korektor').val();
        if (this.checked) {
            value = '1';
        } else {
            value = '0';
        }
        let val = value;
        $.ajax({
            url: window.location.origin + "/penerbitan/pracetak/designer/proses-kerja",
            type: 'POST',
            data: {
                id: id,
                proses: val,
                desainer: desainer,
                korektor: korektor,
            },
            dataType: 'json',
            beforeSend: function() {
                $("#overlay").fadeIn(300);
            },
            success: function(result) {
                // console.log(result);
                if (result.status == 'error') {
                    notifToast(result.status, result.message);
                    resetFrom($('#fup_pracetakDesainer'));
                } else {
                    notifToast(result.status, result.message);
                    location.reload();
                }
            }
        }).done(function() {
            setTimeout(function() {
                $("#overlay").fadeOut(300);
            }, 500);
        });

    });
    $("input[type=\"checkbox\"]").click(function(){
        //localStorage:
        localStorage.setItem("option", value);
    });
    //localStorage:
    var itemValue = localStorage.getItem("option");
    if (itemValue !== null) {
        $("input[value=\""+itemValue+"\"]").click();
    }
});

