$(function() {
    function resetFrom(form) {
    form.trigger('reset');
        $('[name="jenis_format"]').val('').trigger('change');
    }
    let upFbuku = jqueryValidation_('#fup_Fbuku', {
        jenis_format: {required: true},
    });

    function ajaxUpFbuku(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/format-buku/ubah",
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
                location.href = result.route;
            },
            error: function(err) {
                console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if(rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    upFbuku.showErrors(err);
                }
                notifToast(err.status, err.message);
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fup_Fbuku').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="jenis_format"]').val();
            swal({
                text: 'Ubah data format buku ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxUpFbuku($(this))
                }
            });

        }
        else {
            notifToast('error', 'Periksa kembali form Anda!');
        }
    })
})
