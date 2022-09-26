$(function(){
    $('.load-more').click(function(){
        var page = $(this).data('paginate');
        $(this).data('paginate', page+1);

            $.ajax({
                url: window.location.origin + '/penerbitan/deskripsi/produk/lihat-history',
                type: 'post',
                data: {page:page},
                beforeSend:function(){
                    $(".load-more").text("Loading...");
                },
                success: function(response){

                    // Setting little delay while displaying new content
                    setTimeout(function() {
                        // appending posts after last post with class="post"
                        $("#dataHistory:last").after(response).show().fadeIn("slow");
                    }, 2000);

                }
            });

    });
});
$(document).ready(function() {
    $(".select-status").select2({
        placeholder: 'Pilih Status',

    }).on('change', function(e) {
        if(this.value) {
            $(this).valid();
        }
    });
});
$(document).ready(function() {
    $(".select-filter").select2({
        placeholder: 'Pilih Filter Status',
    }).on('change', function(e) {
        if(this.value) {
            $('.clear_field').removeAttr('hidden');
            $(this).valid();
        }
    });
});
$(document).ready(function(){
    $('.clear_field').click( function () {
        $(".select-filter").val('').trigger('change');
        $('.clear_field').attr('hidden','hidden');
   });
});
$(document).ready(function() {
    $('#tb_DesProduk').on('click','.btn-status-despro', function(e) {
        e.preventDefault();
        let id = $(this).data('id'),
            kode = $(this).data('kode'),
            judul = $(this).data('judul');
        $('#id').val(id);
        $('#kode').val(kode);
        $('#judulAsli').val(judul);
    })
});
$(document).ready(function(){
    function ajaxUpdateStatusDeskripsiProduk(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/deskripsi/produk/update-status-progress",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                notifToast(result.status, result.message);
                $('#fm_UpdateStatusDespro').trigger('reset');
                location.reload();

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
                notifToast('error', 'Gagal melakukan ubah status!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fm_UpdateStatusDespro').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let kode = $(this).find('[name="kode"]').val();
            let judul = $(this).find('[name="judul_asli"]').val();
            swal({
                title: 'Yakin mengubah status deskripsi produk '+kode+'-'+judul+'?',
                text: 'Anda sebagai Prodev tetap dapat mengubah kembali data yang sudah Anda perbarui saat ini.',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxUpdateStatusDeskripsiProduk($(this))
                }
            });

        }
    });
});
