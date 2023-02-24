//!Format Penulisan ISBN dan HARGA
var HARGA = document.getElementById('HARGA');
var maskHARGA = {
    mask: '000000000'
};
var mask = IMask(HARGA, maskHARGA,reverse = true);
$(function() {
    loadDataValue();
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
        showOn: "both"
    });

    let editOrderBuku = jqueryValidation_('#fedit_OrderBuku', {
        edit_judul_buku: {
            required: true
        },
        edit_no_order: {
            required: true
        },
        edit_nama_pemesan: {
            required: true
        },
        edit_format: {
            required: true
        },
        edit_jml_halaman: {
            required: true
        },
        edit_kertas_isi: {
            required: true
        },
        edit_kertas_isi_tinta: {
            required: true
        },
        edit_kertas_cover: {
            required: true
        },
        edit_kertas_cover_tinta: {
            required: true
        },
        edit_jml_order: {
            required: true
        },
        edit_status_cetak: {
            required: true
        },
        edit_tgl_order: {
            required: true
        },
        edit_tgl_permintaan_selesai: {
            required: true
        },
    });

    function ajaxEditOrderBuku(data) {
        let el = data.get(0);
        let cardWrap = $('.section-body').find('.card');
        let id = data.data('id');
        // console.log(id);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/jasa-cetak/order-buku/edit?id="+id,
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                cardWrap.addClass('card-progress');
            },
            success: function(result) {
                console.log(result)
                // resetFrom(data);
                loadDataValue();
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
                    editOrderBuku.showErrors(err);
                }
                notifToast('error', 'Data order buku gagal disimpan!');
            },
            complete: function() {
                cardWrap.removeClass('card-progress');
            }
        })
    }

    $('#fedit_OrderBuku').on('submit', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="edit_judul_buku"]').val();
            swal({
                    text: 'Perbarui data Order Buku (' + nama + ')?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm_) => {
                    if (confirm_) {
                        ajaxEditOrderBuku($(this))
                    }
                });

        }
    })
});
function loadDataValue() {
    let id = window.location.search.split('?').pop(),
        cardWrap = $('.section-body').find('.card');
    $.ajax({
        url: window.location.origin + "/jasa-cetak/order-buku/edit?" + id+"&request_=getValue",
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
                        $('[name="edit_id"]').attr("data-id", data[n]).change();
                        break;
                    case 'created_by':
                        $('#createdBy').text(data[n]).change();
                        break;
                    case 'created_at':
                        $('#createdAt').text(data[n]).change();
                        break;
                    case 'jml_order':
                        // console.log(data[n]);
                        // $('[name="edit_' + n + '"]').val(data[n]);
                        $('[name="edit_' + n + '').tagsinput('add',data[n]);
                        break;
                    default:
                        $('[name="edit_' + n + '"]').val(data[n]).change();
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
