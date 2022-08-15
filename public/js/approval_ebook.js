$(function(){
    $('.datepicker').datepicker({
        format: 'dd MM yyyy',
        startDate: '-10d',
        autoclose:true,
        clearBtn: true,
        todayHighlight: true
    });
    $('#btn-edit-tgl-upload').on('click', function(){
        $('#edit-tgl-upload').removeAttr('style');
        $('#btn-edit-tgl-upload').hide();
    });
    $('.close').on('click', function(){
        $('#edit-tgl-upload').attr('style', 'display:none');
        $('#btn-edit-tgl-upload').show();
    });
    $('#upTglUpload').change(function (e) {
        e.preventDefault();

        // get the value of the dropdown
        var id = $('#id').val();
        var hisTglUpload = $('#historyTgl').val();
        var selectedValue = $(this).val();
        console.log(hisTglUpload);
        // make the ajax call (needs to be POST since you're sending data)
        $.ajax({
              url: window.location.origin + '/update-tanggal-upload-ebook',
              type:'POST',  // change your route to use POST too
              datatype:'JSON',
              context: this,
              data: {
                history: hisTglUpload,
                value: selectedValue,
                id: id
              },
              success: function( res ) {
                  //var html = res;// no need to waste a variable, just use it directly
                  notifToast(res.status, res.message);
                  location.reload();
              },
              error: function() {
                notifToast('error', 'Terjadi kesalahan');
              }
        });
    });
    function ajaxApproveProduksiEbook(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/produksi/order-ebook/ajax/approve",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                // resetFrom(data);
                notifToast(result.status, result.message);
                location.reload();
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
                notifToast('error', 'Gagal melakukan penyetujuan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fadd_Approval').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let kode = $(this).find('[name="kode_order"]').val();
            let judul = $(this).find('[name="judul_buku"]').val();
            swal({
                title: 'Yakin menyetujui produksi #'+kode+'-'+judul+'?',
                text: 'Setelah menyetujui, Anda tidak dapat mengubah kembali data yang sudah Anda setujui!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxApproveProduksiEbook($(this))
                }
            });

        }
    })
});
