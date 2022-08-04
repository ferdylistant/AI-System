$(document).ready(function() {
    var id = $('#id').val();
    var jb = $('#judul_buku').val();
    var ko = $('#kode_order').val();

    $('#btn-decline').on('click', function() {
        $('#titleModal').html('Konfirmasi Penolakan');
        $('#id_').val(id);
        $('#kode_Order').val(ko);
        $('#judul_Buku').val(jb);
        $('#judulBuku').text('#'+ko+'-'+jb);
        $('#modalDecline').modal('show');
    });
});
$(function() {
function resetFrom(form) {
    form.trigger('reset');
        $('[name="keterangan"]').val('').trigger('change');
    }
    let addForm = jqueryValidation_('#fadd_Keterangan', {
        keterangan: {required: true},
    });

    function ajaxDeclineProduksi(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/produksi/order-cetak/ajax/decline",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                resetFrom(data);
                notifToast(result.status, result.message);
                // location.href = result.redirect;

            },
            error: function(err) {
                // console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if(rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    addForm.showErrors(err);
                }
                notifToast('error', 'Gagal melakukan penolakkan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fadd_Keterangan').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let kode = $(this).find('[name="kode_order"]').val();
            let judul = $(this).find('[name="judul_buku"]').val();
            swal({
                title: 'Yakin menolak produksi #'+kode+'-'+judul+'?',
                text: 'Setelah menolak, Anda tidak dapat mengubah kembali data yang sudah Anda tolak!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxDeclineProduksi($(this))
                }
            });

        }
    })
});
