$(function () {
    $('[name="status_filter"]').val("").trigger("change");
    let tableDesProduk = $("#tb_DesProduk").DataTable({
        // bSort: false,
        "responsive": true,
        "autoWidth": false,
        autoWidth: true,
        pagingType: 'input',
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: "Cari...",
            sSearch: "",
            lengthMenu: "_MENU_ /halaman",
        },
        ajax: window.location.origin + "/penerbitan/deskripsi/produk",
        columns: [
            // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: "kode", name: "kode", title: "Kode" },
            { data: "judul_asli", name: "judul_asli", title: "Judul Asli" },
            {
                data: "penulis",
                name: "penulis",
                title: "Penulis",
                width: "15%",
            },
            { data: "nama_pena", name: "nama_pena", title: "Nama Pena" },
            { data: "jalur_buku", name: "jalur_buku", title: "Jalur Buku" },
            { data: "imprint", name: "imprint", title: "Imprint" },
            { data: "judul_final", name: "judul_final", title: "Judul Final" },
            {
                data: "tgl_deskripsi",
                name: "tgl_deskripsi",
                title: "Tgl Deskripsi",
            },
            { data: "pic_prodev", name: "pic_prodev", title: "PIC Prodev" },
            { data: "history", name: "history", title: "History Progress" },
            { data: "tracker", name: "tracker", title: "Tracker" },
            {
                data: "action",
                name: "action",
                title: "Action",
                orderable: false,
            },
        ],
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        notifToast("error",settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    loadDataCount();
    $('[name="status_filter"]').on("change", function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableDesProduk
            .column($(this).data("column"))
            .search(val ? val : "", true, false)
            .draw();
    });
    function loadDataCount() {
        $.ajax({
            url: window.location.origin + "/penerbitan/deskripsi/produk?count_data=true",
            type: "get",
            dataType: "json",
            success: function (response) {
                $("#count_despro").prop('Counter', 0).animate({
                    Counter: response
                }, {
                    duration: 1000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            },
        });
    }
    $("#tb_DesProduk").on("click", ".btn-tracker", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("judulasli");
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: window.location.origin +
                "/penerbitan/deskripsi/produk/ajax/lihat-tracking",
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
    $("#tb_DesProduk").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("judulasli");
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: window.location.origin +
                "/penerbitan/deskripsi/produk/ajax/lihat-history",
            type: "post",
            data: { id: id },
            cache: false,
            beforeSend: function () {
                cardWrap.addClass("card-progress");
            },
            success: function (data) {
                $("#titleModalDespro").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Naskah "' +
                    judul +
                    '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_DesproHistory").modal("show");
            },
            complete: function () {
                cardWrap.removeClass("card-progress");
            }
        });
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_DesproHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url:
                window.location.origin +
                "/penerbitan/deskripsi/produk/ajax/lihat-history",
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
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistory").append(response);
                    $('.thin').animate({ scrollTop: $('.thin').prop("scrollHeight") }, 800);
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
    $("#md_DesproHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
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
        $("#md_UpdateStatusDesProduk").on("shown.bs.modal", function (e) {
            let id = $(e.relatedTarget).data("id"),
                cardWrap = $(this);
            $.ajax({
                url: window.location.origin + "/penerbitan/deskripsi/produk?show_status=true",
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
        $("#md_UpdateStatusDesProduk").on("hidden.bs.modal", function () {
            $(this).find("form").trigger("reset");
            $(this).addClass("modal-progress");
        });
    });
    function ajaxUpdateStatusDeskripsiProduk(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/deskripsi/produk/ajax/update-status-progress",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                $("#fm_UpdateStatusDespro").trigger("reset");
                if (result.status == "success") {
                    tableDesProduk.ajax.reload();
                    $("#md_UpdateStatusDesProduk").modal('hide');
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
                    addForm.showErrors(err);
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

    $("#fm_UpdateStatusDespro").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="kode"]').val();
            let judul = $(this).find('[name="judul_asli"]').val();
            swal({
                title:
                    "Yakin mengubah status deskripsi produk " +
                    kode +
                    "-" +
                    judul +
                    "?",
                text: "Anda sebagai Prodev tetap dapat mengubah kembali data yang sudah Anda perbarui saat ini.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpdateStatusDeskripsiProduk($(this));
                }
            });
        }
    });
});
