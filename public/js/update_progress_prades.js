$(function() {
    $('[name="status_filter"]').val('').trigger('change');
    $('[name="status_proses_saat_ini"]').val('').trigger('change');
    let tableDesCover = $('#tb_PraDes').DataTable({
        "responsive": true,
        "autoWidth": true,
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: 'Cari...',
            sSearch: '',
            lengthMenu: '_MENU_ /halaman',
        },
        ajax: window.location.origin + "/penerbitan/pracetak/designer",
        columns: [
            // { data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'No', orderable: false, searchable: false, "width": "5%" },
            {
                data: 'kode',
                name: 'kode',
                title: 'Kode'
            },
            {
                data: 'judul_final',
                name: 'judul_final',
                title: 'Judul Final'
            },
            {
                data: 'desainer',
                name: 'desainer',
                title: 'Desainer',
            },
            {
                data: 'korektor',
                name: 'korektor',
                title: 'Korektor',
            },
            {
                data: 'penulis',
                name: 'penulis',
                title: 'Penulis',
            },
            {
                data: 'jalur_buku',
                name: 'jalur_buku',
                title: 'Jalur Buku'
            },
            {
                data: 'tgl_masuk_cover',
                name: 'tgl_masuk_cover',
                title: 'Tgl Masuk'
            },
            {
                data: 'proses_saat_ini',
                name: 'proses_saat_ini',
                title: 'Proses Saat Ini'
            },
            {
                data: 'pic_prodev',
                name: 'pic_prodev',
                title: 'PIC Prodev'
            },
            {
                data: 'history',
                name: 'history',
                title: 'History Progress'
            },
            {
                data: 'tracker',
                name: 'tracker',
                title: 'Tracker'
            },
            {
                data: 'action',
                name: 'action',
                title: 'Action',
                orderable: false
            },
        ],

    });
    loadCountData();
    $('[name="status_filter"]').on('change', function() {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableDesCover.column($(this).data('column'))
            .search(val ? val : '', true, false)
            .draw();
    });
    $('[name="status_proses_saat_ini"]').on('change', function() {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableDesCover.column($(this).data('column'))
            .search(val ? val : '', true, false)
            .draw();
    });
    $("#tb_PraDes").on("click", ".btn-tracker", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("judulfinal");
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: window.location.origin +
                "/penerbitan/pracetak/designer/ajax/lihat-tracking",
            type: "post",
            data: { id: id },
            cache: false,
            beforeSend: function () {
                cardWrap.addClass("card-progress");
            },
            success: function (data) {
                $("#titleModalTracker").html('<i class="fas fa-file-signature"></i>&nbsp;Tracking Progress Naskah "' + judul + '"');
                $("#dataShowTracking").html(data);
                $("#md_Tracker").modal("show");
            },
            complete: function () {
                cardWrap.removeClass("card-progress");
            }
        });
    });
    $("#tb_PraDes").on("click", ".btn-history", function (e) {
        var id = $(this).data("id");
        var judul = $(this).data("judulfinal");
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: window.location.origin + "/penerbitan/pracetak/designer/ajax/lihat-history",
            type: "post",
            data: {
                id: id,
            },
            cache: false,
            beforeSend: function () {
                cardWrap.addClass("card-progress");
            },
            success: function (data) {
                $("#titleModalPraDes").html(
                    '<i class="fas fa-history"></i>&nbsp;History Progress Pracetak Desainer "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistoryPraDes").html(data);
                $("#md_PraDesHistory").modal("show");
            },
            complete: function () {
                cardWrap.removeClass("card-progress");
            }
        });
    });
});
function loadCountData() {
    $.get(window.location.origin + "/penerbitan/pracetak/designer?count_data=true", function (data) {
        $("#countData").prop('Counter',0).animate({
            Counter: data
        }, {
            duration: 1000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
}
$(function () {
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_PraDesHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url:
                window.location.origin +
                "/penerbitan/pracetak/designer/ajax/lihat-history",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            beforeSend: function () {
                form.addClass("modal-progress");
            },
            success: function (response) {
                if (response.length == 0) {
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistoryPraDes").append(response);
                    $('.thin').animate({scrollTop: $('.thin').prop("scrollHeight")}, 800);
                }
                // Setting little delay while displaying new content
                // setTimeout(function() {
                //     // appending posts after last post with class="post"
                //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                // }, 2000);
            },
            complete: function (params) {
                form.removeClass("modal-progress");
            },
        });
    });
    $("#md_PraDesHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
    });
});
$(document).ready(function () {
    $(".select-status")
        .select2({
            placeholder: "Pilih Status",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
});
$(document).ready(function () {
    $(".select-filter")
        .select2({
            placeholder: "Filter Status\xa0\xa0",
        })
        .on("change", function (e) {
            if (this.value) {
                $(".clear_field").removeAttr("hidden");
                // $(this).valid();
            }
        });
});
$(document).ready(function () {
    $(".clear_field").click(function () {
        $(".select-filter").val("").trigger("change");
        $(".clear_field").attr("hidden", "hidden");
    });
});
$(document).ready(function () {
    $(".select-proses-saat-ini")
        .select2({
            placeholder: "Filter proses saat ini\xa0\xa0",
        })
        .on("change", function (e) {
            if (this.value) {
                $(".clear_field_proses_saat_ini").removeAttr("hidden");
                // $(this).valid();
            }
        });
});
$(document).ready(function () {
    $(".clear_field_proses_saat_ini").click(function () {
        $(".select-proses-saat-ini").val("").trigger("change");
        $(".clear_field_proses_saat_ini").attr("hidden", "hidden");
    });
});
$(document).ready(function () {
    $("#md_UpdateStatusPracetakDesainer").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            cardWrap = $(this);
        $.ajax({
            url: window.location.origin + "/penerbitan/pracetak/designer?show_status=true",
            type: "GET",
            data: {
                id: id,
            },
            cache: false,
            success: function (result) {
                Object.entries(result).forEach((entry) => {
                    let [key, value] = entry;
                    cardWrap.find('[name="' + key + '"]').val(value).trigger('change');
                });
            },
            error: function (err) {
                console.error(err);
            },
            complete: function () {
                cardWrap.removeClass('modal-progress')
            }
        });
    });
    $("#md_UpdateStatusPracetakDesainer").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
});
$(document).ready(function () {
    function ajaxUpdateStatusPrades(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/pracetak/designer/ajax/update-status-progress",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                if (result.status == "error") {
                    notifToast(result.status, result.message);
                    $("#fm_UpdateStatusPraDes").trigger("reset");
                } else {
                    notifToast(result.status, result.message);
                    $("#fm_UpdateStatusPraDes").trigger("reset");
                    location.reload();
                }
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
                    // addForm.showErrors(err);
                }
                notifToast("error", "Gagal melakukan ubah status!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fm_UpdateStatusPraDes").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="kode"]').val();
            let judul = $(this).find('[name="judul_final"]').val();
            swal({
                title:
                    "Yakin mengubah status proses pracetak desainer " +
                    kode +
                    "-" +
                    judul +
                    "?",
                text: "Anda sebagai Kabag Pracetak tetap dapat mengubah kembali data yang sudah Anda perbarui saat ini.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpdateStatusPrades($(this));
                }
            });
        }
    });
});
