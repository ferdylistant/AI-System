$(document).ready(function () {
    $("#btn-decline").on("click", function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let jabatan = $(this).data('jabatan');
        let jb = $(this).data('judul');
        // $("#titleModal").html("Konfirmasi Persetujuan Pending");
        $("#id_").val(id);
        // $("#kode_Order").val(ko);
        $("#judul_Buku").val(jb);
        $("#type_Jabatan").val(jabatan);
        $("#modalDecline").modal("show");
    });
});
$(document).ready(function () {
    $("#btn-decline-detail").on("click", function (e) {
        e.preventDefault();
        let tgl = $(this).data('tgl');
        let catatan = $(this).data('catatan');
        $("#tglAction").text(tgl);
        if (catatan == '') {
             catatan = 'Tidak ada catatan';
        } else {
             catatan = catatan;
        }
        $("#catatan").text(catatan);
        $("#modalDeclineDetail").modal("show");
    });
});

$(function () {
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="catatan_action"]').val("").trigger("change");
    }
    let addForm = jqueryValidation_("#fadd_Catatan", {
        catatan_action: { required: true },
    });

    function ajaxDeclineOrderEbook(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url:
                window.location.origin + "/penerbitan/order-ebook/ajax/decline",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                resetFrom(data);
                if (result.status == "success") {
                    notifToast(result.status, result.message);
                    location.reload();
                } else {
                    $("#modalPending").modal("hide");
                    notifToast(result.status, result.message);
                }
                // $('#modalDecline').modal('hide');
                // ajax.reload();
                // location.href = result.redirect;
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
                    addForm.showErrors(err);
                }
                notifToast("error", "Gagal melakukan pending!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fadd_Catatan").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let judul = $(this).find('[name="judul_final"]').val();
            swal({
                title:
                    "Yakin order e-book '" + judul + "' ditolak dengan catatan?",
                text: "Setelah ditolak, keputusan tidak bisa diubah kembali..",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeclineOrderEbook($(this));
                }
            });
        }
    });
});
