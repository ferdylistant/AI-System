
$(function () {
    loadData();
    //! PROSES UPDATE FORM
    function ajaxUpPracetakSetter(data) {
        let el = data.get(0);
        let id = data.data('id');
        let idp = data.data('id_pracov');
        // console.log(id);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/pracetak/setter/edit?id="+id+"&id_pracov="+idp,
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
    function ajaxSelesaiRevisiSetter(id) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/pracetak/setter/revision-done?id="+id,
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
    $("#done-revision").on("click", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let kode = $(this).data("kode");
        let judul = $(this).data("judul");
        swal({
            title: "Yakin setter telah selesai merevisi naskah " + judul + "-" + kode + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kekeliruan data.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxSelesaiRevisiSetter(id);
            }
        });
    });
});
//! LOAD DATA
function loadData() {

    let path = window.location.search.split("?").pop();
    $.ajax({
        type: "GET",
        url: window.location.origin + "/penerbitan/pracetak/setter/edit?" + path,
        processData: false,
        contentType: false,
        beforeSend: function () {
            $("#overlay").fadeIn(300);
        },
        success: function (result) {
            // console.log(result);
            for (let n in result) {
                switch (n) {
                    case "id":
                        // console.log(result[n]['data']);
                        $("#fup_pracetakSetter").data("id", result[n]['data']);
                        // $("#headerInfo").html(result[n]['html']);
                        break;
                    case "id_pracov":
                        $("#fup_pracetakSetter").data("id_pracov", result[n]['data']);
                        break;
                    case "judul_final":
                        $("#fup_pracetakSetter").find('[name="judul_final"]').val(result[n]['data']);
                        $("#" + n).text(result[n]['data']);
                        break;
                    case "penulis":
                        $("#" + n).html(result[n]);
                        break;
                    case "nama_pena":
                        $("#" + n).html(result[n]['data']).addClass(result[n]['textColor']);
                        break;
                    case "bullet":
                        // console.log(result[n]);
                        $("#" + n).html(result[n]['data']).addClass(result[n]['textColor']);
                        break;
                    case 'status':
                        $("#"+n).html(result[n]);
                        break;
                    case 'proses_saat_ini':
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
                    case 'jml_hal_final':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".jmlHalColInput").html(result[n]['htmlHidden']).change();
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
                    case 'setter':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".setterColInput").html(result[n]['htmlHidden']).change();
                        $("#setterButton").click(function (e) {
                            e.preventDefault();
                            $("#setterCol").attr("hidden", "hidden");
                            $("#setterColInput").removeAttr("hidden");
                        });
                        $(".batal_edit_setter").click(function (e) {
                            e.preventDefault();
                            $("#setterColInput").attr("hidden", "hidden");
                            $("#setterCol").removeAttr("hidden");
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
                    case 'edisi_cetak':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".edCetakColInput").html(result[n]['htmlHidden']).change();
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
                        break;
                    case 'mulai_p_copyright':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".copyrightColInput").html(result[n]['htmlHidden']).change();
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
                        $(".datepicker-copyright").datepicker({
                            format: "dd MM yyyy",
                            autoclose: true,
                            clearBtn: true,
                            todayHighlight: true,
                        });
                        break;
                    case 'selesai_p_copyright':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".copyrightSelColInput").html(result[n]['htmlHidden']).change();
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
                        $(".datepicker-copyright").datepicker({
                            format: "dd MM yyyy",
                            autoclose: true,
                            clearBtn: true,
                            todayHighlight: true,
                        });
                        break;
                    case 'isbn':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".isbnColInput").html(result[n]['htmlHidden']).change();
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
                        //!Format Penulisan ISBN
                        if (result[n]['hiddenForm'] == false) {
                            var ISBN = document.getElementById('ISBN');
                            var maskISBN = {
                                mask: '0000000000000'
                            };
                            var mask = IMask(ISBN, maskISBN,reverse = true);
                        }
                        break;
                    case 'pengajuan_harga':
                        $("."+n).html(result[n]['data']).addClass(result[n]['textColor']);
                        $("."+n).html(result[n]['data']).addClass(result[n]['textAlign']);
                        $("."+n).html(result[n]['data']).attr('id', result[n]['idCol']);
                        $(".hargaColInput").html(result[n]['htmlHidden']).change();
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
                        //!Format Penulisan HARGA
                        if (result[n]['hiddenForm'] == false) {
                            var HARGA = document.getElementById('HARGA');
                            var maskHARGA = {
                                mask: '000000000'
                            };
                            var mask = IMask(HARGA, maskHARGA,reverse = true);
                        }
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
                    case 'proses':
                        $('#fup_pracetakSetter').trigger("reset");
                        $('[name="'+n+'"]').val("").trigger("change");
                        $('#fup_pracetakSetter [name="'+n+'"]').val(result[n]['data']).change();
                        $('#fup_pracetakSetter [name="'+n+'"]').data('id', result[n]['id']).change();
                        $('#fup_pracetakSetter [name="'+n+'"]').attr('checked', result[n]['checked']).change();
                        $('#fup_pracetakSetter [name="'+n+'"]').attr('disabled', result[n]['disabled']).change();
                        $('#fup_pracetakSetter [name="'+n+'"]').css('cursor',result[n]['cursor']).change();
                        $('#fup_pracetakSetter #labelProses').text(result[n]['label']).change();
                        $('#fup_pracetakSetter #labelProses').css('cursor',result[n]['cursor']).change();
                        $('#fup_pracetakSetter button').attr('disabled', result[n]['disabled']).change();
                        $('#fup_pracetakSetter button').css('cursor',result[n]['cursor']).change();
                        break;
                    default:
                        $("#" + n).text(result[n]['data']).addClass(result[n]['textColor']);
                        break;
                }
            }
        },
        error: function (err) {
            // console.log(err);
            notifToast("error", "Data pracetak setter gagal dimuat!");
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
        var setter = $('.select-setter').val();
        var korektor = $('.select-korektor').val();
        if (this.checked) {
            value = '1';
        } else {
            value = '0';
        }
        let val = value;
        // console.log(id);
        $.ajax({
            url: window.location.origin + "/penerbitan/pracetak/setter/proses-kerja",
            type: 'POST',
            data: {
                id: id,
                proses: val,
                setter: setter,
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
                    resetFrom($('#fup_pracetakSetter'));
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
});

