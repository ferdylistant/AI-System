$(function () {
    //! Desainer / Korektor
    function ajaxDoneDesain(id, autor) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/pracetak/designer/selesai/" +
                autor + "/" + id,
            processData: false,
            contentType: false,
            beforeSend: function () {
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
    $("#btn-done-prades").on("click", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let kode = $(this).data("kode");
        let autor = $(this).data("autor");
        var label = autor == "desainer" ? "desainer " : "koreksi ";
        swal({
            title: "Yakin telah menyelesaikan proses " + label + kode + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kesalahan.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDoneDesain(id, autor);
            }
        });
    });

    //! Prodev
    //* APPROVAL
    function ajaxApproveProdev(id) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/pracetak/designer/approve?id="+id,
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
            title: "Yakin menyetujui pengajuan desain " + judul + "-" + kode + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kekeliruan data.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxApproveProdev(id);
            }
        });
    });

    //* REVISION
    $("#btn-prodev-revision").on("click", function () {
        var id = $(this).data("id");
        var kode = $(this).data("kode");
        var judul = $(this).data("judul");
        var status = $(this).data("status");
        $("#_id").val(id);
        $("#kode").val(kode);
        $("#judul_final").val(judul);
        // $('#titleModal').html('Konfirmasi Persetujuan Pending');
        $("#titleModalDetPrades").html(
            '<i class="fas fa-tools"></i>&nbsp;' + kode + "-" + judul
        );
        if (status == "Proses") {
            $("#contentData").html(`<div class='form-group'><label for='ket_revisi' class='col-form-label'>Keterangan Revisi</label><textarea class='form-control' name='ket_revisi' id='ket_revisi' rows='4'></textarea></div>`);
            $("#footerDecline").html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Submit</button>');
        }
        $("#modalRevisiProdev").modal("show");
    });
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="ket_revisi"]').val("").trigger("change");
    }
    let addForm = jqueryValidation_("#fadd_Revisi", {
        ket_revisi: { required: true },
    });

    function ajaxPracetakDesignerRevision(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/pracetak/designer/revision",
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
                    ajaxPracetakDesignerRevision($(this));
                }
            });
        }
    });

    //* HISTORY REVISION
    $('#btn-history-revision-proof').click(function(e) {
        var id = $(this).data('id');
        var judul = $(this).data('judul');
        // console.log(id);
        $.post(window.location.origin + "/penerbitan/pracetak/designer/lihat-informasi-proof", {
            id: id
        }, function(data) {
            // console.log(data);
            $('#titleModalCover').html(
                '<i class="fas fa-history"></i>&nbsp;Riwayat Proof "' + judul + '"');
            $('#load_more').data('id', id);
            $('#dataInformasiProofProdevDesain').html(data);
            $('#md_InformasiProofProd').modal('show');
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
                $("#dataInformasiProofProdevDesain").append(response);
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
    //* HISTORY SETTER-KOREKTOR
    $('#btn-history-deskor').click(function(e) {
        var id = $(this).data('id');
        var judul = $(this).data('judul');
        // console.log(id);
        $.post(window.location.origin + "/penerbitan/pracetak/designer/lihat-proses-deskor", {
            id: id
        }, function(data) {
            // console.log(data);
            $('#titleModalDeskor').html(
                '<i class="fas fa-history"></i>&nbsp;Riwayat progress Desainer & Korektor naskah"' + judul + '"');
            $('#load_more_deskor').data('id', id);
            $('#dataRiwayatDeskor').html(data);
            $('#md_RiwayatDeskor').modal('show');
        });
    });
    $(".load-more-deskor").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/penerbitan/pracetak/designer/lihat-proses-deskor",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            beforeSend: function () {
                $(".load-more-deskor").text("Loading...");
            },
            success: function (response) {
                if (response.length == 0) {
                    notifToast("error", "Tidak ada data lagi");
                }
                $("#dataRiwayatDeskor").append(response);
                // Setting little delay while displaying new content
                // setTimeout(function() {
                //     // appending posts after last post with class="post"
                //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                // }, 2000);
            },
            complete: function (params) {
                $(".load-more-deskor").text("Load more").fadeIn("slow");
            },
        });
    });
});
