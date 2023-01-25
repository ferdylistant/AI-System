$(function () {
    function ajaxApproveDespro(id) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/deskripsi/produk/ajax/approve?id=" +
                id,
            processData: false,
            contentType: false,
            beforeSend: function () {
                // $('button[type="submit"]').prop('disabled', true).
                //     addClass('btn-progress')
                $("#overlay").fadeIn(300);
            },
            success: function (result) {
                console.log(result);
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
                // $('button[type="submit"]').prop('disabled', false).
                //     removeClass('btn-progress')
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            },
        });
    }

    $("#btn-approve-despro").on("click", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let kode = $(this).data("kode");
        swal({
            title: "Yakin menyetujui naskah deskripsi produk " + kode + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kekeliruan data.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxApproveDespro(id);
            }
        });
    });
});
