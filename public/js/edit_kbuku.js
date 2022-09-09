$(function() {
    function resetFrom(form) {
    form.trigger('reset');
        $('[name="up_nama"]').val('').trigger('change');
    }
    let upKbuku = jqueryValidation_('#fup_Kbuku', {
        up_nama: {required: true},
    });

    function ajaxUpKbuku(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/kelompok-buku/ubah",
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
                    upKbuku.showErrors(err);
                }
                notifToast(err.status, err.message);
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fup_Kbuku').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="up_nama"]').val();
            swal({
                text: 'Ubah data kelompok buku ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxUpKbuku($(this))
                }
            });

        }
        else {
            notifToast('error', 'Periksa kembali form Anda!');
        }
    })
})
