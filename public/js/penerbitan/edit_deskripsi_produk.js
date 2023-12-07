
$(document).ready(function () {
    var max_fields = 5; //maximum input boxes allowed
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
                '<div class="input-group-append"><textarea name="alt_judul[]" class="form-control" placeholder="Alternatif judul"/></textarea><button type="button" class="btn btn-outline-danger remove_field text-danger align-self-center" data-toggle="tooltip" title="Hapus Field"><i class="fas fa-times"></i></button></div>'
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
    $("#namaPenaButton").click(function (e) {
        e.preventDefault();
        $("#namaPenaCol").attr("hidden", "hidden");
        $("#namaPenaColInput").removeAttr("hidden");
    });
    $(".batal_edit_nama_pena").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#namaPenaColInput").attr("hidden", "hidden");
        $("#namaPenaCol").removeAttr("hidden");
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
    $("#eButton").click(function (e) {
        e.preventDefault();
        $("#eCol").attr("hidden", "hidden");
        $("#eColInput").removeAttr("hidden");
    });
    $(".batal_edit_editor").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#eColInput").attr("hidden", "hidden");
        $("#eCol").removeAttr("hidden");
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
    $("#altButton").click(function (e) {
        e.preventDefault();
        $("#altCol").attr("hidden", "hidden");
        $("#altColInput").removeAttr("hidden");
    });
    $(".batal_edit_alt").click(function (e) {
        //user click on remove text
        e.preventDefault();
        $("#altColInput").attr("hidden", "hidden");
        $("#altCol").removeAttr("hidden");
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
    $(".select-editor")
        .select2({
            placeholder: "Pilih editor",
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
    $(".select-sub")
        .select2({
            placeholder: "Pilih\xa0\xa0",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
        let kelompok_id = $('[name="kelompok_id"]').val();
        $("#sKelBuku").select2({
                    placeholder: 'Pilih\xa0\xa0',
                    ajax: {
                        url: window.location.origin + "/penerbitan/naskah/membuat-naskah",
                        type: "GET",
                        data: function (params) {
                            var queryParameters = {
                                request_select: "selectSub",
                                term: params.term,
                                kelompok_id: kelompok_id,
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
    $(".datepicker").datepicker({
        format: "MM yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        clearBtn: true,
    });

    function ajaxUpDeskripsiProduk(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/deskripsi/produk/edit",
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
                // console.log(result);
                notifToast(result.status, result.message);
                if (result.status == 'success') {
                    location.href = result.route;
                }
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
                notifToast("error", "Data order cetak gagal disimpan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fup_deskripsiProduk").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="judul_asli"]').val();
            swal({
                text: "Update data deskripsi produk, (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpDeskripsiProduk($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
        }
    });
});
