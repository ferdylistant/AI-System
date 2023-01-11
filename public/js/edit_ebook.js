
$(document).ready(function(){
    $(".select2")
        .select2({
            placeholder: "Pilih",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $(".select-penulis")
        .select2({
            placeholder: "Pilih penulis",
            multiple: true,
            disabled: true
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
    $(".datepicker-upload").datepicker({
        format: "dd MM yyyy",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
    });
});
$(function () {
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="up_tipe_order"]').val("").trigger("change");
        $('[name="up_eisbn"]').val("").trigger("change");
        $('[name="up_edisi_cetak]"]').val("").trigger("change");
        $('[name="up_tahun_terbit"]').val("").trigger("change");
        $('[name="up_tgl_upload"]').val("").trigger("change");
    }

    function ajaxUpOrderEbook(data) {
        let el = data.get(0);
        let id = data.data('id');
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/order-ebook/edit?id=" + id,
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
                if (result.status == 'error') {
                    resetFrom(data);
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

    $("#fup_OrderEbook").on("submit", function (e) {
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
                    ajaxUpOrderEbook($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
        }
    });
});
$(document).ready(function(){
    //!Format Penulisan ISBN dan HARGA
    var ISBN = document.getElementById('EISBN');
    var maskISBN = {
        mask: '0000000000000'
    };
    var mask = IMask(ISBN, maskISBN, reverse = true);
});
