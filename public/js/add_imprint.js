$(function() {
    function resetFrom(form) {
    form.trigger('reset');
        $('[name="add_nama"]').val('').trigger('change');
    }
    let addImprint = jqueryValidation_('#fadd_Imprint', {
        add_nama: {required: true},
    });

    function ajaxAddImprint(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/imprint/tambah-imprint",
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
                    addImprint.showErrors(err);
                }
                notifToast('error', 'Data produksi gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fadd_Imprint').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="add_nama"]').val();
            swal({
                text: 'Tambah data Imprint ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxAddImprint($(this))
                }
            });

        }
    })
});
