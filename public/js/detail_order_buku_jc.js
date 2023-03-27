loadDataValue();
function loadDataValue() {
    let id = window.location.search.split('?').pop(),
        cardWrap = $('.section-body').find('.card');
    $.ajax({
        url: window.location.origin + "/jasa-cetak/order-buku/detail?" + id+"&request_=getValue",
        beforeSend: function () {
            cardWrap.addClass('card-progress');
        },
        success: function (result) {
            let {
                data,cs
            } = result;
            for (let n in data) {
                // console.log(data[n]);
                switch (n) {
                    case 'id':
                        if (data[n] != '') {
                            $('#buttonAct').html(data[n]).change();
                        }
                        break;
                    case 'proses':
                        if (data[n] != '') {
                            $('#buttonAct').html(data[n]).change();
                        }
                        break;
                    case 'jml_order':
                        if (cs == true) {
                            $('.jml-order-text').text('*Pilih sebelum klik deal');
                        }
                        $('.'+n).html(data[n]).change();
                        break;
                    case 'harga_final':
                        // console.log(data[n]);
                        if (data[n] == null) {
                            $('.'+n).text('Harga belum dipilih!').addClass('text-danger');
                        } else {
                            $('.' + n).text(data[n]).removeClass('text-danger').change();
                        }

                        break;
                    case 'status':
                        $('.'+n).html(data[n]).change();
                        break;
                    default:
                        $('.' + n).text(data[n]).change();
                        break;
                }
            }
        },
        error: function (err) {
            notifToast("error", "Terjadi kesalahan!");
        },
        complete: function () {
            cardWrap.removeClass('card-progress');
        }

    })
}
$(function () {
    $("#buttonAct").on("click","#btn-decline", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var no_order = $(this).data("no_order");
        let cardWrap = $('.section-body').find('.card');
        $.ajax({
            type: 'GET',
            url: window.location.origin + "/jasa-cetak/order-buku/detail?request_=modal-decline-revisi",
            data: {
                order : id,
                kode : no_order,
            },
            beforeSend: function () {
                cardWrap.addClass('card-progress');
            },
            success: function (response) {
                $("#modalDecline").find("#titleModalOrderBuku").html(response.title).addClass('text-danger');
                $("#modalDecline").find("form #contentData").html(response.html);
                $("#modalDecline").find("form #footerDecline").html(response.footer);
                $("[name='id']").val(response.data['id']);
                $("[name='no_order']").val(response.data['no_order']);
                $("[name='judul_buku']").val(response.data['judul_buku']);
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
    function ajaxPilihHarga(no_order,id,harga_final,cardWrap) {
        $.ajax({
            url:
                window.location.origin + "/jasa-cetak/order-buku/detail?request_=pilih-harga",
            data: {
                no_order: no_order,
                id: id,
                harga_final: harga_final,
            },
            type: "post",
            dataType: "json",
            beforeSend: function () {
                cardWrap.addClass('card-progress');
            },
            success: function (response) {
                // console.log(response);
                $('.harga_final').removeClass('text-danger');
                if (response.status == 'success') {
                    $('.harga_final').text(response.data).change();
                }
                notifToast(response.status, response.message);
            },
            error: function (err) {
                // console.log(err);
                notifToast('error', err.statusText);
            },
            complete: function (params) {
                // console.log();
                cardWrap.removeClass("card-progress");
            },
        });
    }
    $(".jml_order").on("change",'input[type=radio][name=harga_final_radio]',function(){

        var no_order = $(this).data('no_order');
        var id = $(this).data('id');
        var harga_final = $(this).val();
        let cardWrap = $('.section-body').find('.card');
        ajaxPilihHarga(no_order,id,harga_final,cardWrap);

    });
    //! DECLINE
    var addForm = jqueryValidation_("#fadd_Decline", {
        ket_revisi: { required: true },
        keterangan_decline: { required: true },
    });
    function ajaxDeclineOrderBuku(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/jasa-cetak/order-buku/detail?request_=approval-order&type=decline",
            data: new FormData(el),
            processData: false,
            contentType: false,
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
            let kode = $(this).find('[name="no_order"]').val();
            let judul = $(this).find('[name="judul_buku"]').val();
            let decline_revisi = $(this).find('[name="decline_revisi"]').val();
            var title = decline_revisi == 'approve_revisi' ? 'harus direvisi' : 'tidak deal';
            var text = decline_revisi == 'approve_revisi' ? 'Setelah order buku direvisi, proses akan kembali kepada Anda untuk pengecekkan ulang.' :
            'Setelah order buku tidak deal, proses tidak dilanjutkan, namun data tetap tersimpan sebagai arsip order buku.';
            swal({
                title: 'Yakin order buku "' + kode + "-" + judul + '" '+title+'?',
                text: text,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeclineOrderBuku($(this));
                }
            });
        }
    });
    //! APPROVE
    function ajaxApproveOrderBuku(id,kode,decline_revisi) {
        let cardWrap = $('.section-body').find('.card');
        $.ajax({
            type: "POST",
            url: window.location.origin + "/jasa-cetak/order-buku/detail?request_=approval-order&type=approve",
            data: {
                id: id,
                no_order : kode,
                decline_revisi : decline_revisi
            },
            beforeSend: function () {
                cardWrap.addClass("card-progress");
            },
            success: function (result) {
                // console.log(result);
                notifToast(result.status, result.message);
                if (result.status == "success") {
                    location.reload();
                }
            },
            error: function (err) {
                notifToast("error", err.statusText);
            },
            complete: function () {
                cardWrap.removeClass("card-progress");
            },
        });
    }
    $("#buttonAct").on("click",'#btn-approve', function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let kode = $(this).data("no_order");
        let judul = $(this).data("judul");
        let decline_revisi = $(this).data("decline_revisi");
        var title = decline_revisi == 'approve_revisi' ? 'selesai proof' : 'deal';
        var text = decline_revisi == 'approve_revisi' ? "Setelah order buku proof akan segera dikoreksi. Selanjutnya kabag akan memproses alur order buku." :
        "Setelah order buku deal, status proses masuk 'Antrian'. Selanjutnya kabag akan memproses alur order buku.";
        swal({
            title: 'Yakin order buku "' + kode + "-" + judul + '" '+title+'?',
            text: text,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxApproveOrderBuku(id,kode,decline_revisi);
            }
        });
    });
    //! DONE DESAIN,KOREKTOR,PRACETAK
    function ajaxDoneWork(id,no_order,autor) {
        $.ajax({
            type: "POST",
            url:
                window.location.origin + "/jasa-cetak/order-buku/detail?request_=done-work",
            data: {
                id : id,
                no_order : no_order,
                author : autor
            },
            beforeSend: function () {
                $("#overlay").fadeIn(300);
            },
            success: function (result) {
                notifToast(result.status, result.message);
                if (result.status == "success") {
                    location.reload();
                }
            },
            error: function (err) {
                notifToast("error", "Terjadi Kesalahan!");
            },
            complete: function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            },
        });
    }
    $("#buttonAct").on("click","#btn-done-order-buku", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let no_order = $(this).data("no_order");
        let autor = $(this).data("author");
        var label = autor == "desain_setter" ? "desain/setter " : autor+" ";
        swal({
            title: "Yakin telah menyelesaikan proses " + label + no_order + "?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kesalahan.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDoneWork(id, no_order, autor);
            }
        });
    });
})
