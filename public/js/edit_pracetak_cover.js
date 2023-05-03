$(function () {
    loadData();
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
                notifToast(result.status, result.message);
                if (result.status == "success") {
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
    $("#proses_saat_ini").on("click",'#done-revision', function (e) {
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
function loadData() {
    let path = window.location.search.split("?").pop();
    $.ajax({
        type: "GET",
        url: window.location.origin + "/penerbitan/pracetak/designer/edit?" + path,
        processData: false,
        contentType: false,
        beforeSend: function () {
            $("#overlay").fadeIn(300);
        },
        success: function (result) {
            for (let n in result) {
                switch (n) {
                    case "id":
                        $("#fup_pracetakDesainer").data("id", result[n]['data']);
                        break;
                    case "id_praset":
                        $("#fup_pracetakDesainer").data("id_praset", result[n]['data']);
                        break;
                    case "judul_final":
                        $("#fup_pracetakDesainer").find('[name="judul_final"]').val(result[n]['data']);
                        $("#" + n).text(result[n]['data']);
                        break;
                    case "penulis":
                        $("#" + n).html(result[n]);
                        break;
                    case "status":
                        $("#"+n).html(result[n]);
                        break;
                    case "proses_saat_ini":
                        $("#"+n).html(result[n]['htmlHeader']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".prosColInput").html(result[n]['htmlHidden']).change();
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
                        $(".select-proses")
                            .select2({
                                placeholder: "Pilih proses saat ini",
                            })
                            .on("change", function (e) {
                                if (this.value) {
                                    $(this).valid();
                                }
                            });
                        break;
                    case 'catatan':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".catColInput").html(result[n]['htmlHidden']).change();
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
                        break;
                    case 'desainer':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".desainerColInput").html(result[n]['htmlHidden']).change();
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
                        break;
                    case 'korektor':
                        $("#requiredKorektor").text(result[n]['required']).addClass(result[n]['requiredColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".korektorColInput").html(result[n]['htmlHidden']).change();
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
                        break;
                    case 'bulan':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".bulanColInput").html(result[n]['htmlHidden']).change();
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
                        $(".datepicker").datepicker({
                            format: "MM yyyy",
                            viewMode: "months",
                            minViewMode: "months",
                            autoclose: true,
                            clearBtn: true,
                        });
                        break;
                    case 'finishing_cover':
                        $("#"+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        break;
                    case 'contoh_cover':
                        $("#"+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        break;
                    case "bullet":
                        // console.log(result[n]);
                        $("#" + n).html(result[n]['data']).addClass(result[n]['textColor']);
                        break;
                    case 'proses':
                        $('#fup_pracetakDesainer').trigger("reset");
                        $('[name="'+n+'"]').val("").trigger("change");
                        $('#fup_pracetakDesainer [name="'+n+'"]').val(result[n]['data']).change();
                        $('#fup_pracetakDesainer [name="'+n+'"]').data('id', result[n]['id']).change();
                        $('#fup_pracetakDesainer [name="'+n+'"]').attr('checked', result[n]['checked']).change();
                        $('#fup_pracetakDesainer [name="'+n+'"]').attr('disabled', result[n]['disabled']).change();
                        $('#fup_pracetakDesainer [name="'+n+'"]').css('cursor',result[n]['cursor']).change();
                        $('#fup_pracetakDesainer #labelProses').text(result[n]['label']).change();
                        $('#fup_pracetakDesainer #labelProses').css('cursor',result[n]['cursor']).change();
                        $('#fup_pracetakDesainer button').attr('disabled', result[n]['disabled']).change();
                        $('#fup_pracetakDesainer button').css('cursor',result[n]['cursor']).change();
                        break;
                    default:
                        $("#" + n).text(result[n]['data']).addClass(result[n]['textColor']);
                        break;
                }
            }
        },
        error: function (err) {
            // console.log(err.responseJSON);
            notifToast("error", "Data gagal dimuat!");
        }
    }).done(function () {
        setTimeout(function () {
            $("#overlay").fadeOut(300);
        }, 500);
    });
}


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
                notifToast(result.status, result.message);
                if (result.status == 'error') {
                    resetFrom($('#fup_pracetakDesainer'));
                } else {
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

