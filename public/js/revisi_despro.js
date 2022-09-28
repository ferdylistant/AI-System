$(document).ready(function() {
    $('#btn-revision').on('click', function() {
        var id = $(this).data('id');
        var kode = $(this).data('kode');
        var judulAsli = $(this).data('judul_asli');
        var action_gm = $(this).data('action_gm');
        var alasan = $(this).data('alasan_revisi');
        var deadline_revisi = $(this).data('deadline_revisi');
        var status = $(this).data('status');
        // $('#titleModal').html('Konfirmasi Persetujuan Pending');
        $('#titleModalDetDespro').html('<i class="fas fa-tools"></i>&nbsp;'+kode+'-'+judulAsli);
        if(status == 'Selesai'){
            $('#contentData').html("<div class='form-group mb-4'><label for='deadline_revisi' class='col-form-label'>Deadline revisi sampai tanggal:</label><div class='input-group'><div class='input-group-prepend'><div class='input-group-text'><i class='fas fa-calendar-alt'></i></div></div><input type='text' class='form-control datepicker' id='deadline_revisi' name='deadline_revisi' readonly required></div><div class='form-group'><label for='alasan' class='col-form-label'>Alasan</label><textarea class='form-control' name='alasan' id='alasan' rows='4'></textarea></div>");
            $('#footerDecline').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Konfirmasi</button>');
        } else {
            $('#contentData').html("<div class='form-group mb-4'><label for='deadline_revisi'>Deadline revisi sampai tanggal:</label><p id='deadline_revisi'>"+deadline_revisi+"</p></div><div class='form-group mb-4'><label for='alasan'>Alasan:</label><p id='alasan'>"+kp+"</p></div>");
            $('#footerDecline').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>');
        }
        $('.datepicker').datepicker({
            format: 'dd MM yyyy',
            startDate: '+1d',
            autoclose:true,
            clearBtn: true,
            todayHighlight: true
        });
        $('#modalRevisi').modal('show');
        $(document).on('click', '#batalkan-pending',function(e){
            e.preventDefault();
            var getLink = $(this).attr('href');
            swal({
                title: 'Apakah anda yakin?',
                text:  'Pembatalan pending akan meneruskan proses penyetujuan',
                type: 'warning',
                html: true,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    window.location.href = getLink
                }
            });
        });
    });
});

$(function() {
    function resetFrom(form) {
        form.trigger('reset');
            $('[name="deadline_revisi"]').val('').trigger('change');
            $('[name="alasan"]').val('').trigger('change');
        }
        let addForm = jqueryValidation_('#fadd_Keterangan', {
            deadline_revisi: {required: true},
            keterangan: {required: true},
        });

        function ajaxPendingProduksiEbook(data) {
            let el = data.get(0);
            $.ajax({
                type: "POST",
                url: window.location.origin + "/penerbitan/order-ebook/ajax/pending",
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
                        notifToast(result.status, result.message);
                        location.reload();
                    } else {
                        $('#modalPending').modal('hide');
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
                    notifToast('error', 'Gagal melakukan pending!');
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
                    title: 'Yakin order e-book #'+kode+'-'+judul+' dipending?',
                    text: 'Setelah tanggal dipending selesai, Anda dapat menyetujui kembali data yang sudah Anda pending',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm_) => {
                    if (confirm_) {
                        ajaxPendingProduksiEbook($(this))
                    }
                });

            }
        })
    });
