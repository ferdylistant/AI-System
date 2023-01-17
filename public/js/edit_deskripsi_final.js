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
    $("#subJudulFinalButton").click(function (e) {
        e.preventDefault();
        $("#subJudulFinalCol").attr("hidden", "hidden");
        $("#subJudulFinalColInput").removeAttr("hidden");
    });
    $(".batal_edit_sub_judul_final").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#subJudulFinalColInput").attr("hidden", "hidden");
        $("#subJudulFinalCol").removeAttr("hidden");
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
    $("#kelButton").click(function (e) {
        e.preventDefault();
        $("#kelCol").attr("hidden", "hidden");
        $("#kelColInput").removeAttr("hidden");
    });
    $(".batal_edit_kel").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#kelColInput").attr("hidden", "hidden");
        $("#kelCol").removeAttr("hidden");
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
    $("#imprintButton").click(function (e) {
        e.preventDefault();
        $("#imprintCol").attr("hidden", "hidden");
        $("#imprintColInput").removeAttr("hidden");
    });
    $(".batal_edit_imprint").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#imprintColInput").attr("hidden", "hidden");
        $("#imprintCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#judulFinalButton").click(function (e) {
        e.preventDefault();
        $("#judulFinalCol").attr("hidden", "hidden");
        $("#judulFinalColInput").removeAttr("hidden");
    });
    $(".batal_edit_judul_final").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#judulFinalColInput").attr("hidden", "hidden");
        $("#judulFinalCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#kertasIsiButton").click(function (e) {
        e.preventDefault();
        $("#kertasIsiCol").attr("hidden", "hidden");
        $("#kertasIsiColInput").removeAttr("hidden");
    });
    $(".batal_edit_kertas_isi").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#kertasIsiColInput").attr("hidden", "hidden");
        $("#kertasIsiCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#isiWarnaButton").click(function (e) {
        e.preventDefault();
        $("#isiWarnaCol").attr("hidden", "hidden");
        $("#isiWarnaColInput").removeAttr("hidden");
    });
    $(".batal_edit_isi_warna").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#isiWarnaColInput").attr("hidden", "hidden");
        $("#isiWarnaCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#ukuranAsliButton").click(function (e) {
        e.preventDefault();
        $("#ukuranAsliCol").attr("hidden", "hidden");
        $("#ukuranAsliColInput").removeAttr("hidden");
    });
    $(".batal_edit_ukuran_asli").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#ukuranAsliColInput").attr("hidden", "hidden");
        $("#ukuranAsliCol").removeAttr("hidden");
    });
});
$(document).ready(function () {
    $("#isiHurufButton").click(function (e) {
        e.preventDefault();
        $("#isiHurufCol").attr("hidden", "hidden");
        $("#isiHurufColInput").removeAttr("hidden");
    });
    $(".batal_edit_isi_huruf").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#isiHurufColInput").attr("hidden", "hidden");
        $("#isiHurufCol").removeAttr("hidden");
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
$(document).ready(function () {
    $("#bulletButton").click(function (e) {
        e.preventDefault();
        $("#bulletCol").attr("hidden", "hidden");
        $("#bulletColInput").removeAttr("hidden");
    });
    $(".batal_edit_bullet").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#bulletColInput").attr("hidden", "hidden");
        $("#bulletCol").removeAttr("hidden");
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
    $("#sinopsisButton").click(function (e) {
        e.preventDefault();
        $("#sinopsisCol").attr("hidden", "hidden");
        $("#sinopsisColInput").removeAttr("hidden");
    });
    $(".batal_edit_sinopsis").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#sinopsisColInput").attr("hidden", "hidden");
        $("#sinopsisCol").removeAttr("hidden");
    });
});
$(function () {
    $(".select-imprint")
        .select2({
            placeholder: "Pilih imprint",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-kelengkapan")
        .select2({
            placeholder: "Pilih kelengkapan",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-setter")
        .select2({
            placeholder: "Pilih setter",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-korektor")
        .select2({
            placeholder: "Pilih korektor",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-format-buku")
        .select2({
            placeholder: "Pilih format buku",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-kertas-isi")
        .select2({
            placeholder: "Pilih kertas isi",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-isi-warna")
        .select2({
            placeholder: "Pilih isi warna",
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

    function ajaxUpDeskripsiFinal(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/deskripsi/final/edit",
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
                console.log(result);
                notifToast(result.status, result.message);
                location.href = result.route;
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
                notifToast("error", "Data deskripsi final gagal disimpan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fup_deskripsiFinal").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="judul_asli"]').val();
            swal({
                text: "Update data deskripsi final, (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpDeskripsiFinal($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
        }
    });
});
