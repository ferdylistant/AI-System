$(function () {
    loadData();
    function ajaxDoneEditing(id,autor) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/editing/selesai/" +
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

    $("#proses").on("click",'#btn-done-editing', function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let kode = $(this).data("kode");
        let autor = $(this).data("autor");
        var label = autor=="editor"?"editing ":"copy editing ";
        swal({
            title: "Yakin telah menyelesaikan proses "+ label + kode + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kesalahan.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDoneEditing(id,autor);
            }
        });
    });
    $("#proses").on("click", '#btn-revisi-editing',function (e) {
        e.preventDefault();
        let id = window.location.search.split('?').pop(),
        cardWrap = $('.section-body').find('.card');
        $.ajax({
            type: 'GET',
            url: window.location.origin + "/penerbitan/editing/detail?" + id + "&request_=show-modal-revisi",
            beforeSend: function () {
                cardWrap.addClass('card-progress');
            },
            success: function (response) {
                // console.log(response);
                $("#modalDecline").find("#titleModalEditing").html(response.title).addClass('text-danger');
                $("#modalDecline").find("form #contentData").html(response.html);
                $("#modalDecline").find("form #footerDecline").html(response.footer);
                $("[name='id']").val(response.data['id']);
                $("[name='kode']").val(response.data['kode']);
                $("[name='judul_final']").val(response.data['judul_final']);
                $('#modalDecline').modal("show");

            },
            error: function (err) {
                notifToast('error', err.statusText);
            },
            complete: function (params) {
                cardWrap.removeClass('card-progress');
            }
        });
    });
    //! DECLINE
    var addForm = jqueryValidation_("#fadd_Decline", {
        ket_revisi: { required: true },
    });
    function ajaxDeclineEditing(data) {
        let id = data.find('[name="id"]').val();
        let kode = data.find('[name="kode"]').val();
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/editing/detail?editing=" + id +"&kode=" + kode,
            data: data.serialize(),
            beforeSend: function () {
                $('#modalDecline').addClass("modal-progress");
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
                    addForm.showErrors(err);
                }
                notifToast("error", err.statusText);
            },
            complete: function () {
                $('#modalDecline').removeClass("modal-progress");
            },
        });
    }
    $('#fadd_Decline').on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="kode"]').val();
            let judul = $(this).find('[name="judul_final"]').val();
            swal({
                title: 'Yakin order buku "' + kode + "-" + judul + '" harus direvisi?',
                text: 'Setelah data editing direvisi, proses akan kembali kepada Anda untuk pengecekkan ulang.',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeclineEditing($(this));
                }
            });
        }
    });
    function loadData() {
        let id = window.location.search.split('?').pop(),
            cardWrap = $('.section-body').find('.card');
        $.ajax({
            type: 'GET',
            url: window.location.origin + "/penerbitan/editing/detail?" + id + "&request_=load-data",
            beforeSend: function () {
                cardWrap.addClass('card-progress');
            },
            success: function (response) {
                // console.log(response);
                for (n in response) {
                    switch (n) {
                        case 'status':
                            $('#'+n).html(response[n]['status']);
                            $('#prosesPengerjaan').html(response[n]['proses']);
                            break;
                        default:
                            $('#'+n).html(response[n]);
                            break;
                    }
                }
            },
            error: function (err) {
                // console.log(err);
                notifToast('error', 'Gagal memuat data!');
            },
            complete: function (params) {
                cardWrap.removeClass('card-progress');
            }
        });
    }
});

