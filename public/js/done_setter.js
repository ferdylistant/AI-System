$(function () {
    //! Setter / Korektor
    function ajaxDoneSetting(id, autor) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/pracetak/setter/selesai/" +
                autor + "/" + id,
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
                notifToast("error", "Terjadi Kesalahan!");
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
        var label = autor == "setter" ? "setting " : "koreksi ";
        swal({
            title: "Yakin telah menyelesaikan proses " + label + kode + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kesalahan.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDoneSetting(id, autor);
            }
        });
    });

    //! Prodev
    //* APPROVAL
    function ajaxApproveProdevSetter(id) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/pracetak/setter/approve?id="+id,
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
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            },
        });
    }
    $("#btn-approve-prodev").on("click", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let kode = $(this).data("kode");
        let judul = $(this).data("judul");
        swal({
            title: "Yakin menyetujui setting naskah " + judul + "-" + kode + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kekeliruan data.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxApproveProdevSetter(id);
            }
        });
    });

    //* REVISION
    $("#btn-prodev-revision").on("click", function () {
        var id = $(this).data("id");
        var kode = $(this).data("kode");
        var judul = $(this).data("judul");
        var action_gm = $(this).data("action_gm");
        var ket_revisi = $(this).data("ket_revisi");
        var status = $(this).data("status");
        $("#_id").val(id);
        $("#kode").val(kode);
        $("#judul_final").val(judul);
        // $('#titleModal').html('Konfirmasi Persetujuan Pending');
        $("#titleModalDetPraset").html(
            '<i class="fas fa-tools"></i>&nbsp;' + kode + "-" + judul
        );
        if (status == "Proses") {
            $("#contentData").html(`<div class='form-group'><label for='ket_revisi' class='col-form-label'>Keterangan Revisi</label><textarea class='form-control' name='ket_revisi' id='ket_revisi' rows='4'></textarea></div>`);
            $("#footerDecline").html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Submit</button>');
        } else {
            $("#contentData").html(
                `<div class='form-group mb-4'>
            <label for='status'>Status:</label>
            <p id='status'><span class='badge badge-info'>` +
                status +
                `</span></p>
            <label for='action_gm'>Tanggal dimulai revisi:</label>
            <p id='action_gm'>` +
                action_gm +
                `</p>
            <label for='deadline_revisi'>Deadline revisi sampai tanggal:</label>
            <p id='deadline_revisi'>` +
                deadline_revisi +
                `</p>
            </div><div class='form-group mb-4'>
            <label for='alasan'>Alasan:</label>
            <p id='alasan'>` +
                alasan +
                `</p></div>`
            );
            $("#footerDecline").html(
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'
            );
        }
        $("#modalRevisiProdev").modal("show");
        $(document).on("click", "#submit-revisi", function (e) {
            e.preventDefault();
            var getLink = $(this).attr("href");
            swal({
                title: "Apakah anda yakin?",
                text: "Pembatalan pending akan meneruskan proses penyetujuan",
                type: "warning",
                html: true,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    window.location.href = getLink;
                }
            });
        });
    });
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="ket_revisi"]').val("").trigger("change");
    }
    let addForm = jqueryValidation_("#fadd_Revisi", {
        ket_revisi: { required: true },
    });

    function ajaxPracetakSetterRevision(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/pracetak/setter/revision",
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
                    // $("#modalRevisi").modal("hide");\
                    console.log(result.message);
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
                notifToast("error", "Terjadi kesalahan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fadd_Revisi").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="kode"]').val();
            let judul = $(this).find('[name="judul_final"]').val();
            swal({
                title: 'Yakin naskah "' + kode + "-" + judul + '" direvisi?',
                text: "Setelah naskah telah selesai direvisi, Anda dapat menyetujui kembali naskah yang sudah direvisi oleh setter",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxPracetakSetterRevision($(this));
                }
            });
        }
    });

    //* HISTORY REVISION
    $('#btn-history-revision').click(function(e) {
        var id = $(this).data('id');
        var judul = $(this).data('judul');
        // console.log(id);
        $.post(window.location.origin + "/penerbitan/pracetak/setter/lihat-informasi-proof", {
            id: id
        }, function(data) {
            // console.log(data);
            $('#titleModalSetter').html(
                '<i class="fas fa-history"></i>&nbsp;Riwayat Proof "' + judul + '"');
            $('#load_more').data('id', id);
            $('#dataInformasiProof').html(data);
            $('#md_InformasiProof').modal('show');
        });
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/penerbitan/pracetak/setter/lihat-informasi-proof",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            beforeSend: function () {
                $(".load-more").text("Loading...");
            },
            success: function (response) {
                if (response.length == 0) {
                    notifToast("error", "Tidak ada data lagi");
                }
                $("#dataInformasiProof").append(response);
                // Setting little delay while displaying new content
                // setTimeout(function() {
                //     // appending posts after last post with class="post"
                //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                // }, 2000);
            },
            complete: function (params) {
                $(".load-more").text("Load more").fadeIn("slow");
            },
        });
    });
});
