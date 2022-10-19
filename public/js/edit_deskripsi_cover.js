$(document).ready(function() {
	var max_fields      = 15; //maximum input boxes allowed
	var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
	var add_button      = $(".add_field_button"); //Add button ID

	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			$(wrapper).append('<div class="input-group-append"><input type="text" name="bullet[]" class="form-control" placeholder="Bullet"/><button type="button" class="btn btn-outline-danger remove_field text-danger align-self-center" data-toggle="tooltip" title="Hapus Field"><i class="fas fa-times"></i></button></div>'); //add input box
		}
	});

	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;
	})
});
$(document).ready(function() {
    $('#formatBukuButton').click(function(e) {
        e.preventDefault();
        $('#formatBukuCol').attr('hidden','hidden');
        $('#formatBukuColInput').removeAttr('hidden');
    });
    $('.batal_edit_format').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#formatBukuColInput').attr('hidden','hidden');
        $('#formatBukuCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#desBackButton').click(function(e) {
        e.preventDefault();
        $('#desBackCol').attr('hidden','hidden');
        $('#desBackColInput').removeAttr('hidden');
    });
    $('.batal_edit_desback').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#desBackColInput').attr('hidden','hidden');
        $('#desBackCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#desFrontButton').click(function(e) {
        e.preventDefault();
        $('#desFrontCol').attr('hidden','hidden');
        $('#desFrontColInput').removeAttr('hidden');
    });
    $('.batal_edit_desfront').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#desFrontColInput').attr('hidden','hidden');
        $('#desFrontCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#kelButton').click(function(e) {
        e.preventDefault();
        $('#kelCol').attr('hidden','hidden');
        $('#kelColInput').removeAttr('hidden');
    });
    $('.batal_edit_kel').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#kelColInput').attr('hidden','hidden');
        $('#kelCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#catButton').click(function(e) {
        e.preventDefault();
        $('#catCol').attr('hidden','hidden');
        $('#catColInput').removeAttr('hidden');
    });
    $('.batal_edit_cat').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#catColInput').attr('hidden','hidden');
        $('#catCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#imprintButton').click(function(e) {
        e.preventDefault();
        $('#imprintCol').attr('hidden','hidden');
        $('#imprintColInput').removeAttr('hidden');
    });
    $('.batal_edit_imprint').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#imprintColInput').attr('hidden','hidden');
        $('#imprintCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#judulFinalButton').click(function(e) {
        e.preventDefault();
        $('#judulFinalCol').attr('hidden','hidden');
        $('#judulFinalColInput').removeAttr('hidden');
    });
    $('.batal_edit_judul_final').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#judulFinalColInput').attr('hidden','hidden');
        $('#judulFinalCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#subJudulFinalButton').click(function(e) {
        e.preventDefault();
        $('#subJudulFinalCol').attr('hidden','hidden');
        $('#subJudulFinalColInput').removeAttr('hidden');
    });
    $('.batal_edit_sub_judul_final').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#subJudulFinalColInput').attr('hidden','hidden');
        $('#subJudulFinalCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#jilidButton').click(function(e) {
        e.preventDefault();
        $('#jilidCol').attr('hidden','hidden');
        $('#jilidColInput').removeAttr('hidden');
    });
    $('.batal_edit_jilid').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#jilidColInput').attr('hidden','hidden');
        $('#jilidCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#finishingButton').click(function(e) {
        e.preventDefault();
        $('#finishingCol').attr('hidden','hidden');
        $('#finishingColInput').removeAttr('hidden');
    });
    $('.batal_edit_finishing').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#finishingColInput').attr('hidden','hidden');
        $('#finishingCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#tipografiButton').click(function(e) {
        e.preventDefault();
        $('#tipografiCol').attr('hidden','hidden');
        $('#tipografiColInput').removeAttr('hidden');
    });
    $('.batal_edit_tipografi').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#tipografiColInput').attr('hidden','hidden');
        $('#tipografiCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#warnaButton').click(function(e) {
        e.preventDefault();
        $('#warnaCol').attr('hidden','hidden');
        $('#warnaColInput').removeAttr('hidden');
    });
    $('.batal_edit_warna').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#warnaColInput').attr('hidden','hidden');
        $('#warnaCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#bulanButton').click(function(e) {
        e.preventDefault();
        $('#bulanCol').attr('hidden','hidden');
        $('#bulanColInput').removeAttr('hidden');
    });
    $('.batal_edit_bulan').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#bulanColInput').attr('hidden','hidden');
        $('#bulanCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#bulletButton').click(function(e) {
        e.preventDefault();
        $('#bulletCol').attr('hidden','hidden');
        $('#bulletColInput').removeAttr('hidden');
    });
    $('.batal_edit_bullet').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#bulletColInput').attr('hidden','hidden');
        $('#bulletCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#desainerButton').click(function(e) {
        e.preventDefault();
        $('#desainerCol').attr('hidden','hidden');
        $('#desainerColInput').removeAttr('hidden');
    });
    $('.batal_edit_desainer').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#desainerColInput').attr('hidden','hidden');
        $('#desainerCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#korektorButton').click(function(e) {
        e.preventDefault();
        $('#korektorCol').attr('hidden','hidden');
        $('#korektorColInput').removeAttr('hidden');
    });
    $('.batal_edit_korektor').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#korektorColInput').attr('hidden','hidden');
        $('#korektorCol').removeAttr('hidden');
	})
});
$(document).ready(function() {
    $('#sinopsisButton').click(function(e) {
        e.preventDefault();
        $('#sinopsisCol').attr('hidden','hidden');
        $('#sinopsisColInput').removeAttr('hidden');
    });
    $('.batal_edit_sinopsis').click(function(e){ //user click on remove text
		e.preventDefault();
        $('#sinopsisColInput').attr('hidden','hidden');
        $('#sinopsisCol').removeAttr('hidden');
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
    $(".select-desainer").select2({
        placeholder: 'Pilih desainer',
    }).on('change', function(e) {
        if(this.value) {
            $(this).valid();
        }
    });
    $(".select-jilid").select2({
        placeholder: 'Pilih jilid',
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
    $(".select-finishing-cover").select2({
        placeholder: 'Pilih finishing cover',
    }).on('change', function(e) {
        if(this.value) {
            $(this).valid();
        }
    });
    $(".select-isi-warna").select2({
        placeholder: 'Pilih isi warna',
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

    function ajaxUpDeskripsiCover(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/deskripsi/cover/edit",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                // resetFrom(data);
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
                notifToast('error', 'Data deskripsi cover gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }

    $('#fup_deskripsiCover').on('submit', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            let nama = $(this).find('[name="judul_asli"]').val();
            swal({
                text: 'Update data deskripsi cover, ('+nama+')?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxUpDeskripsiCover($(this))
                }
            });

        }
        else {
            notifToast('error', 'Periksa kembali form Anda!');
        }
    })
})
