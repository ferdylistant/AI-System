$("#tb_Orcet").on("click", ".btn-history", function (e) {
    var id = $(this).data("id");
    var judul = $(this).data("judulfinal");
    $.post(
        window.location.origin + "/penerbitan/order-cetak/ajax/lihat-history",
        {
            id: id,
        },
        function (data) {
            $("#titleModalOrderCetak").html(
                '<i class="fas fa-history"></i>&nbsp;History Progress Order Cetak "' +
                    judul +
                    '"'
            );
            $("#load_more").data("id", id);
            $("#dataHistoryOrderCetak").html(data);
            $("#md_OrderCetakHistory").modal("show");
        }
    );
});
$(document).ready(function () {
    $("#tb_Orcet").on("click", ".btn-status-orcetak", function (e) {
        e.preventDefault();
        let id = $(this).data("id"),
            kode = $(this).data("kode"),
            judul = $(this).data("judul");
        $("#id").val(id);
        $("#kode").val(kode);
        $("#judulFinal").val(judul);
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
    function ajaxUpdateStatusOrderCetak(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/order-cetak/ajax/update-status-progress",
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
                    $("#fm_UpdateStatusOrderCetak").trigger("reset");
                } else {
                    notifToast(result.status, result.message);
                    $("#fm_UpdateStatusOrderCetak").trigger("reset");
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
    $("#fm_UpdateStatusOrderCetak").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="kode"]').val();
            let judul = $(this).find('[name="judul_final"]').val();
            swal({
                title:
                    "Yakin mengubah status proses order cetak " +
                    kode +
                    "-" +
                    judul +
                    "?",
                text: "Anda sebagai Admin penerbitan tetap dapat mengubah kembali data yang sudah Anda perbarui saat ini.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpdateStatusOrderCetak($(this));
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
        $(this).data("paginate", page + 1);

        $.ajax({
            url:
                window.location.origin +
                "/penerbitan/order-cetak/ajax/lihat-history",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            beforeSend: function () {
                $(".load-more").text("Loading...");
            },
            success: function (response) {
                if (response.length == 0) {
                    notifToast("error", "Tidak ada data lagi");
                }
                $("#dataHistoryOrderCetak").append(response);
            },
            complete: function (params) {
                $(".load-more").text("Load more").fadeIn("slow");
            },
        });
    });
    let tableOrderCetak = $("#tb_Orcet").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/penerbitan/order-cetak",
        },
        columns: [
            {
                data: "no_order",
                name: "no_order",
                title: "Kode Order",
            },
            {
                data: "kode",
                name: "kode",
                title: "Kode Naskah",
            },
            {
                data: "tipe_order",
                name: "tipe_order",
                title: "Tipe Order",
            },
            {
                data: "judul_final",
                name: "judul_final",
                title: "Judul Buku",
            },
            {
                data: "jalur_buku",
                name: "jalur_buku",
                title: "Jalur Buku",
            },
            {
                data: "status_penyetujuan",
                name: "status_penyetujuan",
                title: "Penyetujuan",
            },
            {
                data: "history",
                name: "history",
                title: "History",
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
        tableOrderCetak
            .column($(this).data("column"))
            .search(val ? val : "", true, false)
            .draw();
    });
});
