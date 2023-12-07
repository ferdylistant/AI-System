
$(function () {
    loadDataValue();
    // $(".select-2")
    //     .select2({
    //         placeholder: "Pilih..",
    //         multiple: true,
    //     })
    //     .on("change", function (e) {
    //         if (this.value) {
    //             $(this).valid();
    //         }
    //     });
    //! UBAH DELEGASI SUBJEK
    function ajaxUpDelegasiOrderBukuJasaCetak(data) {
        let el = data.get(0);
        let id = data.data('id');
        let no_order = data.data('no_order');
        $.ajax({
            type: "POST",
            url: window.location.origin + "/jasa-cetak/order-buku/otorisasi-kabag?request_=delegasi&id="+id+"&no_order="+no_order,
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $("#overlay").fadeIn(300);
            },
            success: function (result) {
                // console.log(result);
                notifToast(result.status, result.message);

                if (result.status == 'success') {
                    location.reload();
                }
            },
            error: function (err) {
                notifToast("error", err.statusText);
            },
            complete: function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            },
        });
    }
    $("#f_OrderBukuJasaCetak").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('.judul_buku').text();
            swal({
                text: "Update data delegasi order buku jasa cetak, (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpDelegasiOrderBukuJasaCetak($(this));
                }
            });
        } else {
            notifToast("error", "Periksa kembali form Anda!");
        }
    });
    //! SWITCH TOGGLE PROSES KERJA
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="proses"]').val("").trigger("change");
    }
    $('#f_OrderBukuJasaCetak #prosesKerja').click(function () {
        var id = $(this).data('id');
        var no_order = $(this).data('no_order');
        var label = $(this).data('label_db');
        var author = $(this).data('author_db');
        let cardWrap = $('.section-body').find('.card');
        if (this.checked) {
            value = '1';
        } else {
            value = '0';
        }
        let val = value;
        $.ajax({
            url: window.location.origin + "/jasa-cetak/order-buku/otorisasi-kabag?request_=proses-kerja",
            type: 'POST',
            data: {
                id: id,
                no_order: no_order,
                proses: val,
                label: label,
                author: author
            },
            dataType: 'json',
            beforeSend: function () {
                cardWrap.addClass('card-progress');
            },
            success: function (result) {
                // console.log(result);
                notifToast(result.status, result.message);
                if (result.status == 'error') {
                    resetFrom($('#f_OrderBukuJasaCetak'));
                } else {
                    location.reload();
                }
            },
            error: function (err) {
                notifToast("error", err.statusText);
            }
        }).done(function () {
            setTimeout(function () {
                cardWrap.removeClass('card-progress');
            }, 500);
        });
    });
    //! DONE REVISION ACT KABAG
    function ajaxDoneRevisionOrderBuku(id,kode) {
        let cardWrap = $('.section-body').find('.card');
        $.ajax({
            type: "POST",
            url: window.location.origin + "/jasa-cetak/order-buku/otorisasi-kabag?request_=done-revision",
            data: {
                id: id,
                no_order : kode
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
    $("#buttonAct").on("click",'#done-revision', function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let kode = $(this).data("no_order");
        let judul = $(this).data("judul");
        swal({
            title: "Yakin desain/setter telah selesai merevisi order buku '" + judul + "-" + kode + "'?",
            text: "Harap diperiksa kembali, supaya tidak terjadi kekeliruan data.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDoneRevisionOrderBuku(id,kode);
            }
        });
    });
});

function loadDataValue() {
    let id = window.location.search.split('?').pop(),
        cardWrap = $('.section-body').find('.card');
    $.ajax({
        url: window.location.origin + "/jasa-cetak/order-buku/otorisasi-kabag?" + id + "&request_=getValue",
        beforeSend: function () {
            cardWrap.addClass('card-progress');
        },
        success: function (result) {
            let {
                data
            } = result;
            for (let n in data) {
                // console.log(data[n]);
                switch (n) {
                    case 'id':
                        $('[name="id"').data('id', data[n]['id']);
                        $('#buttonAct').html(data[n]['html']).change();
                        $('#buttonAct p').addClass('text-justify').change();
                        break;
                    case 'no_order':
                        $('.' + n).text(data[n]).change();
                        $('[name="id"').data('no_order', data[n]);
                        break;
                    case 'jalur_proses':
                        // console.log(data[n]['html']);
                        $('#timelineProgress').html(data[n]['html']);
                        $('.' + n).html(data[n]['item']).change();
                        $('#f_OrderBukuJasaCetak .select-desain').attr('disabled', data[n]['disabled']);
                        break;
                    case 'status':
                        $('.' + n + '_proses').html(data[n]).change();
                        break;
                    case 'proses':
                        // console.log(data[n]['prosesValue']);
                        $('#f_OrderBukuJasaCetak').trigger("reset");
                        $('[name="proses"]').val("").trigger("change");
                        // $('#f_OrderBukuJasaCetak .custom-switch checkbox').val("").trigger("reset");
                        $('#f_OrderBukuJasaCetak [name="proses"]').data('id', data[n]['id']).change();
                        $('#f_OrderBukuJasaCetak [name="proses"]').data('no_order', data[n]['no_order']).change();
                        $('#f_OrderBukuJasaCetak [name="proses"]').data('author_db', data[n]['authorDB']).change();
                        $('#f_OrderBukuJasaCetak [name="proses"]').data('label_db', data[n]['labelDB']).change();
                        $('#f_OrderBukuJasaCetak [name="proses"]').val(data[n]['prosesValue']).change();
                        $('#f_OrderBukuJasaCetak .proses_kerja_input').attr('checked', data[n]['checked']).change();
                        $('#f_OrderBukuJasaCetak select').attr('disabled', data[n]['disabled']).change();
                        $('#f_OrderBukuJasaCetak select').css('cursor', data[n]['cursor']).change();
                        $('#f_OrderBukuJasaCetak .proses_kerja_input').attr('disabled', data[n]['disabled']).change();
                        $('#f_OrderBukuJasaCetak .proses_kerja_input').css('cursor', data[n]['cursor']).change();
                        $('#f_OrderBukuJasaCetak #labelProses').text(data[n]['label']).change();
                        $('#f_OrderBukuJasaCetak #labelProses').css('cursor',data[n]['cursor']).change();
                        $('#f_OrderBukuJasaCetak button').attr('disabled', data[n]['disabled']).change();
                        $('#f_OrderBukuJasaCetak button').css('cursor', data[n]['cursor']).change();
                        break;
                    case 'desain_setter':
                        // console.log(data[n]);
                        $('.'+n).html(data[n]).change();
                        $(".select-desain")
                            .select2({
                                placeholder: "Pilih desain / setter",
                                multiple: true,
                            })
                            .on("change", function (e) {
                                if (this.value) {
                                    $(this).valid();
                                }
                            });
                        break;
                    case 'korektor':
                        $('.'+n).html(data[n]).change();
                        $(".select-korektor")
                            .select2({
                                placeholder: "Pilih korektor",
                                multiple: true,
                            })
                            .on("change", function (e) {
                                if (this.value) {
                                    $(this).valid();
                                }
                            });
                        // if (data[n] != null) {
                        //     for (let i in data[n]) {
                        //         // console.log(data[n][i].nama);
                        //         $(".select-korektor").select2("trigger", "select" ,{
                        //             data: {
                        //                 id: data[n][i].id,
                        //                 text: data[n][i].nama
                        //             }
                        //         });
                        //     }
                        // }
                        break;
                    case 'pracetak':
                        $('.'+n).html(data[n]).change();
                        $(".select-pracetak")
                            .select2({
                                placeholder: "Pilih pracetak",
                                multiple: true,
                            })
                            .on("change", function (e) {
                                if (this.value) {
                                    $(this).valid();
                                }
                            });
                        break;
                    case 'created_by':
                        $('#createdBy').text(data[n]).change();
                        break;
                    case 'created_at':
                        $('#createdAt').text(data[n]).change();
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

