$(document).ready(function() {
	var max_fields      = 5; //maximum input boxes allowed
	var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
	var add_button      = $(".add_field_button"); //Add button ID

	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			$(wrapper).append('<div class="input-group-append"><input type="text" name="alt_judul[]" class="form-control" placeholder="Alternatif judul" required/><button type="button" class="btn btn-outline-danger remove_field text-danger align-self-center" data-toggle="tooltip" title="Hapus Field"><i class="fas fa-times"></i></button></div>'); //add input box
		}
	});

	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;
	})
});
$(function() {
    $(".select-imprint").select2({
        placeholder: 'Pilih imprint',
    }).on('change', function(e) {
        if(this.value) {
            $(this).valid();
        }
    });
    $(".select-kelengkapan").select2({
        placeholder: 'Pilih kelengkapan',
    }).on('change', function(e) {
        if(this.value) {
            $(this).valid();
        }
    });
    $(".select-format-buku").select2({
        placeholder: 'Pilih format buku',
    }).on('change', function(e) {
        if(this.value) {
            $(this).valid();
        }
    });

    $('.datepicker').datepicker({
        format: 'MM yyyy',
        viewMode: "months",
        minViewMode: "months",
        autoclose:true,
        clearBtn: true
    });
    function resetFrom(form) {
    form.trigger('reset');
        $('[name="up_tipe_order"]').val('').trigger('change');
        $('[name="up_jenis_mesin"]').val('').trigger('change');
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
    //     up_tgl_permintaan_jadi: {required: true},
    // });

    function ajaxUpDeskripsiProduk(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/order-cetak/edit",
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
                    // upNaskah.showErrors(err);
                }
                notifToast('error', 'Data order cetak gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fup_deskripsiProduk').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="judul_asli"]').val();
            swal({
                text: 'Update data deskripsi produk, ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxUpDeskripsiProduk($(this))
                }
            });

        }
        else {
            notifToast('error', 'Periksa kembali form Anda!');
        }
    })
})
