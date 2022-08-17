$(document).ready(function(){
    $.ajax({
        type: 'GET',
        url: window.location.origin + '/list-status-buku',
        success:function(hasil)
        {
            var list = hasil;
            if ( typeof(hasil) == "string" ){
                list = JSON.parse(hasil);
            }
            $.each( hasil, function( index, data ){
                $("#statusBuku").append($("<option></option>").attr("value",data.value).text(data.label));
            });
        },
        error:function(xhr, status, error){
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    });
});
$(function() {
$(".select2").select2({
    placeholder: 'Pilih',
}).on('change', function(e) {
    if(this.value) {
        $(this).valid();
    }
});

$('.datepicker-year').datepicker({
    format: 'yyyy',
    viewMode: "years",
    minViewMode: "years",
    autoclose:true,
    clearBtn: true
});
$('.datepicker').datepicker({
    format: 'dd MM yyyy',
    startDate: '-10d',
    autoclose:true,
    clearBtn: true,
    todayHighlight: true
});
function resetFrom(form) {
form.trigger('reset');
    $('[name="add_tipe_order"]').val('').trigger('change');
    $('[name="add_judul_buku"]').val('').trigger('change');
    $('[name="add_platform_digital[]"]').val('').trigger('change');
    $('[name="add_eisbn"]').val('').trigger('change');
    $('[name="add_edisi]"]').val('').trigger('change');
    $('[name="add_cetakan"]').val('').trigger('change');
    $('[name="add_tahun_terbit"]').val('').trigger('change');
    $('[name="add_tgl_upload"]').val('').trigger('change');
}

function ajaxAddProduksiEbook(data) {
    let el = data.get(0);
    $.ajax({
        type: "POST",
        url: window.location.origin + "/penerbitan/order-ebook/create",
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
            location.href = result.redirect;

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
                // addNaskah.showErrors(err);
            }
            notifToast('error', 'Data order e-book gagal disimpan!');
        },
        complete: function() {
            $('button[type="submit"]').prop('disabled', false).
                removeClass('btn-progress')
        }
    })
}

$('#fadd_Ebook').on('submit', function(e) {
    e.preventDefault();
    if($(this).valid()) {
        let nama = $(this).find('[name="add_judul_buku"]').val();
        swal({
            text: 'Tambah data order e-book ('+nama+')?',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((confirm_) => {
            if (confirm_) {
                ajaxAddProduksiEbook($(this))
            }
        });

    }
})
})
