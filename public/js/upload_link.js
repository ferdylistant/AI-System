// $(document).ready(function() {
//     $('[id^="bukti_upload"]').on('keyup', function() {
//         console.log(this);
//         if ($(this).val().length == 0) {
//             $('button[type="submit"]').attr('disabled', 'disabled');
//         }
//         else {
//             $('button[type="submit"]').attr('disabled', false);
//         }
//     });
// });
$(document).ready(function() {
    $(".select-filter-jb").select2({
        placeholder: 'Pilih Jalur Buku',
    }).on('change', function(e) {
        if(this.value) {
            $('.clear_field_jb').removeAttr('hidden');
            $(this).valid();
        }
    });
});
$(document).ready(function(){
    $('.clear_field_jb').click( function () {
        $(".select-filter-jb").val('').trigger('change');
        $('.clear_field_jb').attr('hidden','hidden');
   });
});
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
            if(result.status == 'error') {
                resetFrom(data);
                notifToast(result.status, result.message);
            } else {
                resetFrom(data);
                notifToast(result.status, result.message);
                location.href = result.route;
            }
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
$(document).on('click', '.mark-sent-email',function(e){
    e.preventDefault();
    var getLink = $(this).attr('href');
    swal({
        title: 'Apakah anda yakin?',
        text:  'Email harus sudah dikirim ke penulis',
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

