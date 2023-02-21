function resetFrom(form) {
    form.trigger('reset');
    $('[name="add_judul_buku"]').val('').trigger('change');
    $('[name="add_nama_pemesan"]').val('').trigger('change');
    $('[name="add_pengarang"]').val('').trigger('change');
    $('[name="add_format"]').val('').trigger('change');
    $('[name="add_jml_halaman"]').val('').trigger('change');
    $('[name="add_kertas_isi"]').val('').trigger('change');
    $('[name="add_kertas_isi_tinta"]').val('').trigger('change');
    $('[name="add_kertas_cover"]').val('').trigger('change');
    $('[name="add_kertas_cover_tinta"]').val('').trigger('change');
    $('[name="add_jml_order"]').val('').trigger('change');
    $('[name="add_status_cetak"]').val('').trigger('change');
    $('[name="add_tgl_order"]').val('').trigger('change');
    $('[name="add_tgl_permintaan_selesai"]').val('').trigger('change');
    $('[name="add_keterangan"]').val('').trigger('change');
    $.ajax({
        type: "GET",
        url: window.location.origin + "/jasa-cetak/order-buku/create",
        data: {
            request_: 'getNomorOrder'
        },
        success: function(data) {
            $('[name="add_no_order"]').val(data);
        }
    });
}
$(function() {
    $(".select2").select2({
        placeholder: 'Pilih',
    }).on('change', function(e) {
        if (this.value) {
            $(this).valid();
        }
    });

    $('.datepicker').datepicker({
        format: 'dd MM yyyy',
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
    });

    let addOrderBuku = jqueryValidation_('#fadd_OrderBuku', {
        add_judul_buku: {
            required: true
        },
        add_no_order: {
            required: true
        },
        add_nama_pemesan: {
            required: true
        },
        add_format: {
            required: true
        },
        add_jml_halaman: {
            required: true
        },
        add_kertas_isi: {
            required: true
        },
        add_kertas_isi_tinta: {
            required: true
        },
        add_kertas_cover: {
            required: true
        },
        add_kertas_cover_tinta: {
            required: true
        },
        add_jml_order: {
            required: true
        },
        add_status_cetak: {
            required: true
        },
        add_tgl_order: {
            required: true
        },
        add_tgl_permintaan_selesai: {
            required: true
        },
    });

    function ajaxAddOrderBuku(data) {
        let el = data.get(0);
        let cardWrap = $('.section-body').find('.card');
        $.ajax({
            type: "POST",
            url: window.location.origin + "/jasa-cetak/order-buku/create",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                cardWrap.addClass('card-progress');
            },
            success: function(result) {
                // console.log(result)
                resetFrom(data);
                notifToast(result.status, result.message);
            },
            error: function(err) {
                // console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    addOrderBuku.showErrors(err);
                }
                notifToast('error', 'Data order buku gagal disimpan!');
            },
            complete: function() {
                cardWrap.removeClass('card-progress');
            }
        })
    }

    $('#fadd_OrderBuku').on('submit', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="add_judul_buku"]').val();
            swal({
                    text: 'Tambah data Order Buku (' + nama + ')?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm_) => {
                    if (confirm_) {
                        ajaxAddOrderBuku($(this))
                    }
                });

        }
    })
});
