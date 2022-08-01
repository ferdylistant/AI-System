$(document).ready(function(){
    var id = $('#idProd').val();
    // console.log(id);
    $.ajax({
        type: 'GET',
        url: window.location.origin + '/list-pilihan-terbit',
        data: 'id='+ id,
        success:function(hasil)
        {
            $("#pilihanTerbit").html(hasil);
        },
        error:function(xhr, status, error){
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    });
});
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
$(document).ready(function(){
    var id = $('#idProd').val();
    $.ajax({
        type: 'GET',
        url: window.location.origin + '/get-layout',
        data: 'id='+ id,
        success:function(hasil)
        {
            $("#posisiLayout").html(hasil);
        },
        error:function(xhr, status, error){
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    });
    $("#posisiLayout").on("change", function(){
        var val = $(this).val();
        $.ajax({
            url: window.location.origin + '/list-dami',
            type:'GET',
            data:'value='+val,
            success:function(hasil)
            {
                $("#dami").empty();
                $("#dami").html(hasil);
            },
            error:function(hasil)
            {
                $("#dami").empty();
                $("#dami").html(hasil);
            }
        });
    });
    $("#dami").on("change", function(){
        var valDam = $(this).val();
        var valPos = $('#posisiLayout').val();
        $.ajax({
            url: window.location.origin + '/list-format-buku',
            type:'GET',
            data: 'dami='+valDam+'&posisi='+valPos,
            success:function(hasil)
            {
                $("#formatBuku").html(hasil);
            },
            error:function(hasil)
            {
                $("#formatBuku").html(hasil);
            }
        });
    });
})
$(document).ready(function() {
    // e.preventDefault();
    var tipeAwal = $('select[name=up_tipe_order] option').filter(':Selected').val();
    if(tipeAwal == '2'){
        $('#gridPlatformDigital').hide();
    }
    $('#selTipeOrder').on('change', function() {
        var tipe = $(this).val();
        if (tipe !== '2') {
            $('#gridPlatformDigital').show();
        } else {
            $('#gridPlatformDigital').hide();
        }
    });
});
$(document).ready(function() {
    $('#ukuranBending').hide();
    var tipeAwal = $('select[name=up_jilid] option').filter(':Selected').val();
    if(tipeAwal == '1'){
        $('#formJilid').attr('class', 'form-group col-12 col-md-3 mb-4');
        $('#ukuranBending').show();
    }
    $('#jilidChange').on('change', function() {
        var tipe = $(this).val();
        if (tipe != '1') {
            $('#formJilid').attr('class', 'form-group col-12 col-md-6 mb-4');
            $('#ukuranBending').hide();
        } else {
            $('#formJilid').attr('class', 'form-group col-12 col-md-3 mb-4');
            $('#ukuranBending').show();
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

    $('.datepicker').datepicker({
        format: 'dd MM yyyy'
    });
    function resetFrom(form) {
    form.trigger('reset');
        $('[name="up_tipe_order"]').val('').trigger('change');
        $('[name="up_status_cetak"]').val('').trigger('change');
        $('[name="up_judul_buku"]').val('').trigger('change');
        $('[name="up_platform_digital[]"]').val('').trigger('change');
        $('[name="up_urgent"]').val('').trigger('change');
        $('[name="up_isbn"]').val('').trigger('change');
        $('[name="up_edisi]"]').val('').trigger('change');
        $('[name="up_cetakan"]').val('').trigger('change');
        $('[name="up_tahun_terbit"]').val('').trigger('change');
        $('[name="up_tgl_permintaan_jadi"]').val('').trigger('change');
        $('[name="up_jilid"]').val('').trigger('change');
        $('[name="up_posisi_layout"]').val('').trigger('change');
        $('[name="up_ukuran_bending"]').val('').trigger('change');
    }
    // let upNaskah = jqueryValidation_('#fup_Produksi', {
    //     up_tipe_order: {required: true},
    //     up_status_cetak: {required: true},
    //     up_judul_buku: {required: true},
    //     up_platform_digital: {required: true},
    //     up_urgent: {required: true},
    //     up_isbn: {required: true},
    //     up_edisi: {required: true},
    //     up_cetakan: {required: true},
    //     up_tanggal_terbit: {required: true},
    //     up_tgl_permintaan_jadi: {required: true},
    //     up_format_buku_1: {required: true},
    // });

    function ajaxUpProduksi(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/produksi/order-cetak/edit",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                resetFrom(data);
                notifToast('success', 'Data produksi berhasil diubah!');
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
                    upNaskah.showErrors(err);
                }
                notifToast('error', 'Data produksi gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fup_Produksi').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="up_judul_buku"]').val();
            swal({
                text: 'Ubah data Produksi ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxUpProduksi($(this))
                }
            });

        }
    })
})
