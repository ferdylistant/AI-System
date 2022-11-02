$(function () {
    $(".datepicker").datepicker({
        format: "dd MM yyyy",
        startDate: "-10d",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
    });

    function ajaxUpProsesProduksi(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/produksi/proses/cetak/edit",
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
                    // upNaskah.showErrors(err);
                }
                notifToast(
                    "error",
                    "Data produksi order cetak gagal disimpan!"
                );
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fup_prosesProduksi").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="judul_buku"]').val();
            swal({
                text: "Ubah data produksi order cetak, (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpProsesProduksi($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
        }
    });
});
