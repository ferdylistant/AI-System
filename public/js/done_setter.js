$(function () {
    function ajaxDoneSetting(id,autor) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/pracetak/setter/selesai/" +
                autor+"/"+id,
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
                // $('button[type="submit"]').prop('disabled', false).
                //     removeClass('btn-progress')
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            },
        });
    }

    $("#btn-done-praset").on("click", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let kode = $(this).data("kode");
        let autor = $(this).data("autor");
        var label = autor=="setter"?"setting ":"koreksi ";
        swal({
            title: "Yakin telah menyelesaikan proses "+ label + kode + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kesalahan.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDoneSetting(id,autor);
            }
        });
    });
});
