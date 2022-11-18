$(document).ready(function() {
    $(".select-filter-jb")
        .select2({
            placeholder: "Filter Jalur Buku",
        })
        .on("change", function(e) {
            if (this.value) {
                $(".clear_field_jb").removeAttr("hidden");
                // $(this).valid();
            }
        });
});
$(document).ready(function() {
    $(".clear_field_jb").click(function() {
        $(".select-filter-jb").val("").trigger("change");
        $(".clear_field_jb").attr("hidden", "hidden");
    });
});
$(document).ready(function() {
    $(".select-filter").select2({
        placeholder: 'Filter Data Lengkap/Belum\xa0\xa0',
    }).on('change', function(e) {
        if (this.value) {
            $('.clear_field').removeAttr('hidden');
            // $(this).valid();
        }
    });
});
$(document).ready(function() {
    $('.clear_field').click(function() {
        $(".select-filter").val('').trigger('change');
        $('.clear_field').attr('hidden', 'hidden');
    });
});
function ajaxTandaDataLengkap(data) {
    let id = data.data("id");
    let judul_asli = data.data("judul");
    let kode = data.data("kode");
    $.ajax({
        type: "POST",
        url: window.location.origin + "/penerbitan/naskah/tandai-data-lengkap",
        data: {
            id: id,
            judul_asli: judul_asli,
            kode: kode,
        },
        beforeSend: function () {
            $("#sectionDataNaskah").parent().addClass("card-progress");
        },
        success: function (result) {
            if (result.status == "success") {
                notifToast(result.status, result.message);
                location.reload();
            } else {
                notifToast(result.status, result.message);
            }
            // $('#modalDecline').modal('hide');
            // ajax.reload();
            // location.href = result.redirect;
        },
        error: function (err) {
            // console.log(err.responseJSON)
            rs = err.responseJSON.errors;
            if (rs != undefined) {
                err = {};
                Object.entries(rs).forEach((entry) => {
                    let [key, value] = entry;
                    err[key] = value;
                });
                addForm.showErrors(err);
            }
            notifToast("error", "Terjadi kesalahan!");
        },
        complete: function () {
            $("#sectionDataNaskah").parent().removeClass("card-progress");
        },
    });
}
$(document).on("click", ".mark-sent-email", function (e) {
    e.preventDefault();
    let id = $(this).data("id");
    let judul_asli = $(this).data("judul");
    let kode = $(this).data("kode");
    var getLink = $(this).attr("href");
    swal({
        title:
            "Apakah anda yakin data " +
            kode +
            "_" +
            judul_asli +
            " sudah lengkap?",
        text: "Data naskah dan data penulis harus sudah lengkap..",
        type: "warning",
        html: true,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((confirm_) => {
        if (confirm_) {
            // window.location.href = getLink
            ajaxTandaDataLengkap($(this));
        }
    });
});
