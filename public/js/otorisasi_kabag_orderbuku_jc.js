loadDataValue();
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
                        $('[name="id"').data('id', data[n]);
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
                        $('#f_OrderBukuJasaCetak .proses_kerja_input').attr('disabled', data[n]['disabled']).change();
                        $('#f_OrderBukuJasaCetak #labelProses').text(data[n]['label']).change();
                        $('#f_OrderBukuJasaCetak button').attr('disabled', data[n]['disabled']).change();
                        break;
                    case 'desain_setter':
                        $('#f_OrderBukuJasaCetak').trigger("reset");
                        $('[name="desain_setter"]').val("").trigger("change");
                        $('#f_OrderBukuJasaCetak [name="desain_setter"]').val(data[n]).change();
                        $('#f_OrderBukuJasaCetak [name="desain_setter"]').attr('required', data[n]['required']).change();
                        $('#f_OrderBukuJasaCetak [name="desain_setter"]').attr('disabled', data[n]['disabled']).change();
                        $('#f_OrderBukuJasaCetak #nonRegulerInfo').html(data[n]['nonreguler']).addClass('text-danger').change();
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
$(function () {
    $(".select-2")
        .select2({
            placeholder: "Pilih..",
            multiple: true,
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    //! SWITCH TOGGLE PROSES KERJA
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="proses"]').val("").trigger("change");
    }
    $(function () {
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
                    notifToast(result.status, result.message);
                    if (result.status == 'error') {
                        resetFrom($('#f_OrderBukuJasaCetak'));
                    } else {
                        location.reload();
                    }
                }
            }).done(function () {
                setTimeout(function () {
                    cardWrap.removeClass('card-progress');
                }, 500);
            });

        });
    });
});
