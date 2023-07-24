$(function(){
    $('[name="status_filter"]').val('').trigger('change');
    let tableOrderCetak = $("#tb_Orcet").DataTable({
        "responsive": true,
        "autoWidth": false,
        pagingType: "input",
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: "Cari...",
            sSearch: "",
            lengthMenu: "_MENU_ /halaman",
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
                data: "status_cetak",
                name: "status_cetak",
                title: "Status Cetak",
            },
            {
                data: "status_penyetujuan",
                name: "status_penyetujuan",
                title: "Penyetujuan",
                "width":"15%"
            },
            {
                data: "history",
                name: "history",
                title: "History",
            },
            {
                data: "tracker",
                name: "tracker",
                title: "Tracker",
            },
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
    loadCountData();
    $('[name="status_filter"]').on("change", function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableOrderCetak
            .column($(this).data("column"))
            .search(val ? val : "", true, false)
            .draw();
    });
    $("#tb_Orcet").on("click", ".btn-tracker", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("judulfinal");
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: window.location.origin +
                "/penerbitan/order-cetak/ajax/lihat-tracking",
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
    $('#tb_Orcet').on('click', '.btn-history', function(e) {
        var id = $(this).data('id');
        var judul = $(this).data('judulfinal');
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: window.location.origin + "/penerbitan/order-cetak/ajax/lihat-history-order-cetak",
            type: "POST",
            data: {
                id: id
            },
            cache:false,
            beforeSend: function () {
                cardWrap.addClass('card-progress');
            },
            success: function (data) {
                $('#titleModalOrderCetak').html(
                    '<i class="fas fa-history"></i>&nbsp;History Progress Order Cetak "' + judul +
                    '"');
                $('#load_more').data('id', id);
                $('#dataHistoryOrderCetak').html(data);
                $('#md_OrderCetakHistory').modal('show');
            },
            complete: function () {
                cardWrap.removeClass('card-progress');
            }
        })
    });
    function loadCountData() {
        $.get(window.location.origin + "/penerbitan/order-cetak?count_data=true", function (data) {
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
    $("#md_UpdateStatusOrderCetak").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            cardWrap = $(this);
        $.ajax({
            url: window.location.origin + "/penerbitan/order-cetak?show_status=true",
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
    $("#md_UpdateStatusOrderCetak").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
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
                notifToast(result.status, result.message);
                $("#fm_UpdateStatusOrderCetak").trigger("reset");
                if (result.status == "success") {
                    tableOrderCetak.ajax.reload();
                    $("#md_UpdateStatusOrderCetak").modal('hide');
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
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_OrderCetakHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url:
                window.location.origin +
                "/penerbitan/order-cetak/ajax/lihat-history-order-cetak",
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
                    $("#dataHistoryOrderCetak").append(response);
                    $('.thin').animate({scrollTop: $('.thin').prop("scrollHeight")}, 800);
                }
            },
            complete: function (params) {
                form.removeClass("modal-progress");
            },
        });
    });
    $('#md_OrderCetakHistory').on('hidden.bs.modal', function () {
        $('.load-more').data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
    });
    $('#btnTambahCetul').on('click',function (e) {
        e.preventDefault();
        // console.log(e);
        $.ajax({
            url: window.location.origin + '/penerbitan/order-cetak/ajax/modal-cetak-ulang',
            type: 'GET',
            cache: false,
            beforeSend: function () {
                $('#overlay').fadeIn(300);
            },
            success: function (result) {
                $('#dataModalCetakUlang').html(result.data);
                $('#btnModalCetul').html(result.btn);
                $(".select-naskah-cetul")
                .select2({
                    placeholder: "Pilih Status",
                })
                .on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                    }
                });
                $('#modalTambahCetakUlang').modal('show');
            },
            error: function (err) {
                notifToast('error','Gagal memuat modal!')
            },
            complete: function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            },
        });
    });
    $('#formTambahCetakUlang').on('submit',function (e) {
        e.preventDefault();
        let id = $(this).find('[name="naskah_baru"]').val();
        $.ajax({
            url: window.location.origin + '/penerbitan/order-cetak/ajax/submit-cetul?' + id,
            beforeSend: function () {
                $('#modalTambahCetakUlang').addClass("modal-progress");
            },
            success: function (result) {
                console.log(result);
                // window.location.href = "/penerbitan/order-cetak/add/cetak-ulang?naskah="+result.naskah+"&orcet_id="+result.orcet_id;
                $('#formTambahCetakUlang').trigger('reset');
                $("#modalTambahCetakUlang").modal("hide");
                tableOrderCetak.ajax.reload();
                notifToast('success', 'Data cetak ulang berhasil ditambahkan!');
            },
            error: function (err) {
                console.log(err);
                notifToast("error", "Terjadi kesalahan!");
            },
            complete: function () {
                $('#modalTambahCetakUlang').removeClass("modal-progress");
            },
        });
    })
});
