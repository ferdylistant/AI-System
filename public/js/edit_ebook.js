$(document).ready(function(){
    var id = $('#idEbook').val();
    $.ajax({
        type: 'GET',
        url: window.location.origin + '/list-status-buku-ebook',
        data: 'id='+id,
        success:function(hasil)
        {

            $("#statusBukuEbook").html(hasil);
            // console.log(hasil);
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
        startDate: '-0d',
        autoclose:true,
        clearBtn: true,
        todayHighlight: true
    });
    function resetFrom(form) {
    form.trigger('reset');
        $('[name="up_tipe_order"]').val('').trigger('change');
        $('[name="up_judul_buku"]').val('').trigger('change');
        $('[name="up_platform_digital[]"]').val('').trigger('change');
        $('[name="up_urgent"]').val('').trigger('change');
        $('[name="up_eisbn"]').val('').trigger('change');
        $('[name="up_edisi]"]').val('').trigger('change');
        $('[name="up_cetakan"]').val('').trigger('change');
        $('[name="up_tahun_terbit"]').val('').trigger('change');
        $('[name="up_tgl_upload"]').val('').trigger('change');
    }

    function ajaxUpProduksiEbook(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/order-ebook/edit",
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
                }
                notifToast('error', 'Data order e-book gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fup_Ebook').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="up_judul_buku"]').val();
            swal({
                text: 'Ubah data order e-book ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxUpProduksiEbook($(this))
                }
            });

        }
        else {
            notifToast('error', 'Periksa kembali form Anda!');
        }
    })
})
