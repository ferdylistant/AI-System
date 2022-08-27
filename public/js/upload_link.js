function resetFrom(form) {
    form.trigger('reset');
        $('[name="bukti_upload[]"]').val('').trigger('change');
    }
function ajaxUpProsesProduksiEbook(data) {
    let el = data.get(0);
    console.log(el);
    $.ajax({
        type: "POST",
        url: window.location.origin + "/produksi/proses/ebook-multimedia/edit",
        data: new FormData(el),
        processData: false,
        contentType: false,
        beforeSend: function() {
            $('button[type="submit"]').prop('disabled', true).
                addClass('btn-progress')
        },
        success: function(result) {
            // console.log(result.message);
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
            }
            notifToast('error', 'Upload link gagal!');
        },
        complete: function() {
            $('button[type="submit"]').prop('disabled', false).
                removeClass('btn-progress')
        }
    })
}

$('#fup_prosesEbook').on('submit', function(e) {
    e.preventDefault();
    if($(this).valid()) {
        swal({
            text: 'Upload bukti link order e-book?',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((confirm_) => {
            if (confirm_) {
                ajaxUpProsesProduksiEbook($(this))
            }
        });

    }
    else {
        notifToast('error', 'Periksa kembali form Anda!');
    }
})
