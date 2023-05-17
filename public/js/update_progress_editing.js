$(function() {
    $('[name="status_filter"]').val("").trigger('change');
    let tableDesProduk = $('#tb_Editing').DataTable({
        // "bSort": false,
        "responsive": true,
        "autoWidth": true,
        pagingType: 'input',
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: 'Cari...',
            sSearch: '',
            lengthMenu: '_MENU_ /halaman',
        },
        ajax: window.location.origin + "/penerbitan/editing",
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
                data: 'penulis',
                name: 'penulis',
                title: 'Penulis',
            },
            {
                data: 'nama_pena',
                name: 'nama_pena',
                title: 'Nama Pena',
            },
            {
                data: 'jalur_buku',
                name: 'jalur_buku',
                title: 'Jalur Buku'
            },
            {
                data: 'tgl_masuk_editing',
                name: 'tgl_masuk_editing',
                title: 'Tgl Masuk Editing'
            },
            {
                data: 'pic_prodev',
                name: 'pic_prodev',
                title: 'PIC Prodev'
            },
            {
                data: 'editor',
                name: 'editor',
                title: 'Editor'
            },
            // {
            //     data: 'copy_editor',
            //     name: 'copy_editor',
            //     title: 'Copy Editor'
            // },
            {
                data: 'history',
                name: 'history',
                title: 'History Progress'
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
        tableDesProduk.column($(this).data('column'))
            .search(val ? val : '', true, false)
            .draw();
    });
    $('#tb_Editing').on('click', '.btn-history', function(e) {
        var id = $(this).data('id');
        var judul = $(this).data('judulfinal');
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: window.location.origin + "/penerbitan/editing/lihat-history",
            type: "POST",
            data: {
                id: id
            },
            cache: false,
            beforeSend: function() {
                cardWrap.addClass("card-progress");
            },
            success: function(data) {
               // console.log(data);
               $('#titleModalEditing').html(
                   '<i class="fas fa-history"></i>&nbsp;History Perubahan Naskah "' + judul + '"');
               $('#load_more').data('id', id);
               $('#dataHistoryEditing').html(data);
               $('#md_EditingHistory').modal('show');
            },
            complete: function() {
                cardWrap.removeClass("card-progress");
            },
        });
    });
});
function loadCountData() {
    $.get(window.location.origin + "/penerbitan/editing?count_data=true", function(data) {
        $('#countData').prop('Counter',0).animate({
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
        let form = $("#md_EditingHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/penerbitan/editing/lihat-history",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            beforeSend: function () {
                form.addClass("modal-progress");
            },
            success: function (response) {
                // $("div , .tickets-list").slice(0, 2).slideDown();
                if (response.length == 0) {
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistoryEditing").append(response);
                    $('.thin').animate({scrollTop: $('.thin').prop("scrollHeight")}, 800);
                }
            },
            complete: function (params) {
                form.removeClass("modal-progress");
            },
        });
    });
    $("#md_EditingHistory").on("hidden.bs.modal", function () {
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
            // if (this.value == 'Pending') {
            //     $("#ketStatusPending").show('slow');
            // } else {
            //     $("#ketStatusPending").hide('slow');
            // }
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
    $("#tb_Editing").on("click", ".btn-status-editing", function (e) {
        e.preventDefault();
        let id = $(this).data("id"),
            kode = $(this).data("kode"),
            judul = $(this).data("judul");
        $("#id").val(id);
        $("#kode").val(kode);
        $("#judulFinal").val(judul);
    });
});
$(document).ready(function () {
    function ajaxUpdateStatusEditing(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/editing/update-status-progress",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                // console.log(result);
                if (result.status == "error") {
                    notifToast(result.status, result.message);
                    $("#fm_UpdateStatusEditing").trigger("reset");
                    $('[name="status"]').val("").trigger("change");
                } else {
                    notifToast(result.status, result.message);
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

    $("#fm_UpdateStatusEditing").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="kode"]').val();
            let judul = $(this).find('[name="judul_final"]').val();
            swal({
                title:
                    "Yakin mengubah status editing proses " +
                    kode +
                    "-" +
                    judul +
                    "?",
                text: "Anda sebagai Kabag Editor tetap dapat mengubah kembali data yang sudah Anda perbarui saat ini.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpdateStatusEditing($(this));
                }
            });
        }
    });
});
