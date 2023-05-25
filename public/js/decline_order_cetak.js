$(function () {
    $("#btn-decline").on("click", function () {
        let id = $(this).data('id');
        let departemen = $(this).data('departemen');
        let jb = $(this).data('judul');
        // $("#titleModal").html("Konfirmasi Persetujuan Pending");
        $("#id_").val(id);
        // $("#kode_Order").val(ko);
        $("#judul_Buku").val(jb);
        $("#type_Departemen").val(departemen);
        $("#modalDecline").modal("show");
    });
    $("body").on("click","#btn-decline-detail", function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            url: window.location.origin + "/penerbitan/order-cetak/ajax/decline-detail?id=" + id,
            type: 'POST',
            cache: false,
            beforeSend: function () {
                $("#overlay").fadeIn(300);
            },
            success: function(data) {
                $('#modalDeclineDetail').find("#avatarHref").attr('href',window.location.origin + '/storage/users/' + data.users['id'] + '/' + data.users['avatar']);
                $('#modalDeclineDetail').find("#imgAvatar").attr('src',window.location.origin + '/storage/users/' + data.users['id'] + '/' + data.users['avatar']);
                $('#modalDeclineDetail').find("#namaUser").text(data.users['nama']);
                $('#modalDeclineDetail').find("#jabatanTitle").text(data.users['jabatan_id']);
                $('#modalDeclineDetail').find("#tglAction").text(data.tgl);
                if (data.catatan == null) {
                    catatan = 'Tidak ada catatan';
                } else {
                    catatan = data.catatan;
                }
                $('#modalDeclineDetail').find("#catatan").text(catatan);
                $("#modalDeclineDetail").modal("show");
            },
            error: function (err) {
                notifToast("error","Gagal memuat!");
            },
            complete: function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            }
        });

    });
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="catatan_action"]').val("").trigger("change");
    }
    let addForm = jqueryValidation_("#fadd_Catatan", {
        catatan_action: { required: true },
    });

    function ajaxDeclineOrderCetak(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url:
                window.location.origin + "/penerbitan/order-cetak/ajax/decline",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                // console.log(result);
                // resetFrom(data);
                notifToast(result.status, result.message);
                if (result.status == "success") {
                    location.reload();
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
                notifToast("error", "Gagal melakukan penolakan!");
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
                    "Yakin order cetak '" + judul + "' ditolak dengan catatan?",
                text: "Setelah ditolak, keputusan tidak bisa diubah kembali..",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeclineOrderCetak($(this));
                }
            });
        }
    });
});
