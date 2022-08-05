$(document).ready(function() {
    var id = $('#id').val();
    var jb = $('#judul_buku').val();
    var ko = $('#kode_order').val();
    var sc = $('#statusCetak').val();
    var kp = $('#ketVal').val();
    $('#btn-decline').on('click', function() {
        $('#titleModal').html('Konfirmasi Penolakan');
        $('#id_').val(id);
        $('#kode_Order').val(ko);
        $('#judul_Buku').val(jb);
        $('#judulBuku').text('#'+ko+'-'+jb);
        $('#status_cetak').val(sc);
        if(!kp){
            $('#contentData').html("<label for='keterangan'>Alasan</label><textarea class='form-control' name='keterangan' rows='4'></textarea>");
            $('#footerDecline').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Konfirmasi</button>');
        } else {
            $('#contentData').html("<label for='keterangan'>Alasan</label><p>"+kp+"</p>");
            $('#footerDecline').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>');
        }
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
                if(result.status == 'success') {
                    notifToast('success', 'Data produksi berhasil ditolak!');
                    location.reload();
                } else {
                    $('#modalDecline').modal('hide');
                    notifToast(result.status, result.message);
                }
                // $('#modalDecline').modal('hide');
                // ajax.reload();
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
