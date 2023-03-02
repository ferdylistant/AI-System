$(function () {
    $('[name="status_filter"]').val("").change();
    let tableOrderBuku = $("#tb_OrderBuku").DataTable({
        responsive: true,
        autoWidth: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/jasa-cetak/order-buku",
        },
        columns: [
            {
                data: "no_order",
                name: "no_order",
                title: "Kode Order",
            },
            {
                data: "nama_pemesan",
                name: "nama_pemesan",
                title: "Nama Pemesan",
            },
            {
                data: "judul_buku",
                name: "judul_buku",
                title: "Judul Buku",
            },
            {
                data: "jalur_proses",
                name: "jalur_proses",
                title: "Jalur Proses",
            },
            {
                data: "status_cetak",
                name: "status_cetak",
                title: "Status Cetak",
            },
            {
                data: "history",
                name: "history",
                title: "History",
            },
            {
                data: "status",
                name: "status",
                title: "Status",
            },
            {
                data: "action",
                name: "action",
                title: "Action",
                searchable: false,
                orderable: false,
            },
        ],
    });
    $('[name="status_filter"]').on("change", function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableOrderBuku
            .column($(this).data("column"))
            .search(val ? val : "", true, false)
            .draw();
    });
});
$("#tb_OrderBuku").on("click", ".btn-history", function (e) {
    var id = $(this).data("id");
    var judul = $(this).data("judul");
    let cardWrap = $(".section-body").find(".card");
    $.ajax({
        url:
            window.location.origin +
            "/jasa-cetak/order-buku/ajax/lihat-history",
        type: "POST",
        data: {
            id: id,
        },
        cache: false,
        beforeSend: function () {
            cardWrap.addClass("card-progress");
        },
        success: function (data) {
            // console.log(data);
            $("#titleModalOrderBuku").html(
                '<i class="fas fa-history"></i>&nbsp;History Progress Order Buku "' +
                    judul +
                    '"'
            );
            $("#load_more").data("id", id);
            $("#dataHistoryOrderBuku").html(data);
            $("#md_OrderBukuHistory").modal("show");
        },
        complete: function () {
            cardWrap.removeClass("card-progress");
        },
    });
});
$(document).ready(function () {
    $("#tb_OrderBuku").on("click", ".btn-status-order-buku", function (e) {
        e.preventDefault();
        let id = $(this).data("id"),
            kode = $(this).data("no_order"),
            judul = $(this).data("judul"),
            cardWrap = $("#md_UpdateStatusJasaCetakOrderBuku");
        $.ajax({
            url:
                window.location.origin +
                "/jasa-cetak/order-buku/ajax/catch-value-status",
            data: {
                id: id,
                no_order: kode,
                judul_buku: judul,
            },
            beforeSend: function () {
                cardWrap.addClass("modal-progress");
            },
            success: function (result) {
                let { data } = result;
                for (let n in data) {
                    // console.log(data[n]);
                    $('[name="' + n + '"]')
                        .val(data[n])
                        .change();
                }
            },
            error: function (err) {
                notifToast("error", "Terjadi kesalahan!");
            },
            complete: function () {
                cardWrap.removeClass("modal-progress");
            },
        });
    });
});
$(function () {
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
    $(".clear_field").click(function () {
        $(".select-filter").val("").trigger("change");
        $(".clear_field").attr("hidden", "hidden");
    });
    $(".select-status")
        .select2({
            placeholder: "Pilih Status",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    function ajaxUpdateStatusOrderBuku(data) {
        let el = data.get(0);
        let form = $("#md_UpdateStatusJasaCetakOrderBuku");
        // console.log(el);
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/jasa-cetak/order-buku/ajax/update-status-progress",
            data: new FormData(el),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                form.addClass("modal-progress");
            },
            success: function (result) {
                if (result.status == "error") {
                    notifToast(result.status, result.message);
                    $("#fm_UpdateStatusOrderBuku").trigger("reset");
                } else {
                    notifToast(result.status, result.message);
                    $("#fm_UpdateStatusOrderBuku").trigger("reset");
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
                form.removeClass("modal-progress");
            },
        });
    }
    $("#fm_UpdateStatusOrderBuku").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="no_order"]').val();
            let judul = $(this).find('[name="judul_buku"]').val();
            swal({
                title:
                    "Yakin mengubah status proses order buku " +
                    kode +
                    "-" +
                    judul +
                    "?",
                text: "Anda sebagai CS jasa cetak tetap dapat mengubah kembali data yang sudah Anda perbarui saat ini.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpdateStatusOrderBuku($(this));
                }
            });
        }
    });
});
$(function () {
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_OrderBukuHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url:
                window.location.origin +
                "/jasa-cetak/order-buku/ajax/lihat-history",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            cache: false,
            beforeSend: function () {
                form.addClass("modal-progress");
            },
            success: function (response) {
                if (response.length == 0) {
                    $(".load-more").attr("disabled", true);
                    notifToast("error", "Tidak ada data lagi");
                }
                $("#dataHistoryOrderBuku").append(response);
            },
            complete: function (params) {
                // console.log();
                form.removeClass("modal-progress");
            },
        });
    });
    $("#md_OrderBukuHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false);
    });
});
