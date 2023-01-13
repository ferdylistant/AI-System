$(function () {
    $("#btn-approve").on("click", function () {
        let id = $(this).data('id');
        let jabatan = $(this).data('jabatan');
        let jb = $(this).data('judul');
        // $("#titleModal").html("Konfirmasi Persetujuan Pending");
        $("#_id").val(id);
        // $("#kode_Order").val(ko);
        $("#judul_BukuApproval").val(jb);
        $("#type_JabatanApproval").val(jabatan);
        $("#modalApproval").modal("show");
        // console.log(jabatan);
    });
});
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("body").on("click","#btn-approve-detail", function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.post(window.location.origin + "/penerbitan/order-ebook/ajax/approve-detail?id=" + id, function (data) {

            // console.log(data);
            $("#namaUserApprove").text(data.users);
            $("#tglActionApprove").text(data.tgl);
            if (data.catatan == null) {
                catatan = 'Tidak ada catatan';
            } else {
                catatan = data.catatan;
            }
            $("#catatanApprove").text(catatan);
            $("#modalApprovalDetail").modal("show");
        })

    });
});

$(function () {
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="catatan_action"]').val("").trigger("change");
    }
    // let addForm = jqueryValidation_("#fadd_Catatan", {
    //     catatan_action: { required: true },
    // });

    function ajaxApproveOrderEbook(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url:
                window.location.origin + "/penerbitan/order-ebook/ajax/approve",
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
                resetFrom(data);
                if (result.status == "success") {
                    notifToast(result.status, result.message);
                    location.reload();
                } else {
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
                    // addForm.showErrors(err);
                }
                notifToast("error", "Gagal melakukan approval!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fadd_CatatanApproval").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let judul = $(this).find('[name="judul_final"]').val();
            // console.log(judul);
            swal({
                title:
                    "Yakin order e-book '" + judul + "' diterima dengan catatan atau tidak dengan catatan?",
                text: "Setelah diterima, keputusan tidak bisa diubah kembali..",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxApproveOrderEbook($(this));
                }
            });
        }
    });
});
