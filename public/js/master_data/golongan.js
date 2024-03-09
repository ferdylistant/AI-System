$(document).ready(function () {
    let tableGolongan = $("#tb_Golongan").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        drawCallback: () => {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-gol'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover'
                    })
                })
                // console.log(tooltipTriggerList);
        },
        ajax: {
            url: window.location.origin + "/master/golongan",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "kode",
                name: "kode",
                title: "Kode",
            },
            {
                data: "nama",
                name: "nama",
                title: "Nama Golongan",
            },
            {
                data: "tgl_dibuat",
                name: "tgl_dibuat",
                title: "Tanggal Dibuat",
            },
            {
                data: "dibuat_oleh",
                name: "dibuat_oleh",
                title: "Dibuat Oleh",
            },
            {
                data: "diubah_terakhir",
                name: "diubah_terakhir",
                title: "Diubah Terakhir",
            },
            {
                data: "diubah_oleh",
                name: "diubah_oleh",
                title: "Diubah Oleh",
            },
            {
                data: "history",
                name: "history",
                title: "History Data",
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
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        notifToast("error", settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    function showContentModal(el, type, id, nama) {
        $.ajax({
            url: window.location.origin + "/master/golongan/ajax/" + type,
            type: "GET",
            cache: false,
            data: {
                id: id,
                nama: nama
            },
            success: function (data) {
                $(el).find("#titleModalGolongan").html(data.title);
                $(el).find("#dataContent").html(data.content);
                $(el).find("#footer_Golongan").html(data.footer);
                $(el).removeClass("modal-progress");
                if (type != "history") {
                    let valid = jqueryValidation_("#fm_" + type + "Golongan", {
                        nama_golongan: {
                            required: true,
                        }
                    });
                } else {
                    $("#load_more").data("id", id);
                }
            },
            error: function (data) {
                console.log(data);
            },
        })
    }
    function ajaxAddGolongan(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/golongan/tambah",
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
                if (result.status == "success") {
                    tableGolongan.ajax.reload();
                    data.trigger("reset");
                }
            },
            error: function (err) {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    valid.showErrors(err);
                }
                notifToast(
                    "error",
                    err.responseJSON.message ? err.responseJSON.message : "Data gagal disimpan!"
                );
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }
    function ajaxEditGolongan(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/golongan/ubah",
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
                if (result.status == "success") {
                    tableGolongan.ajax.reload();
                    $("#md_Golongan").modal("hide");
                }
            },
            error: function (err) {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    valid.showErrors(err);
                }
                notifToast(
                    "error",
                    err.responseJSON.message ? err.responseJSON.message : "Data gagal disimpan!"
                );
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }
    function ajaxDeleteGolongan(data) {
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/golongan/hapus",
            data: data,
            beforeSend: function () {
                $(".btn_DelGolongan")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                tableGolongan.ajax.reload();
            },
            error: function (err) { },
            complete: function () {
                $(".btn_DelGolongan")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }
    $("#md_Golongan").on({
        "shown.bs.modal": function (e) {
            let type = $(e.relatedTarget).data("type"),
                id = $(e.relatedTarget).data("id"),
                nama = $(e.relatedTarget).data("nama"),
                el = $("#md_Golongan");
            showContentModal(el, type, id, nama);
        },
        "hidden.bs.modal": function () {
            $(this).addClass("modal-progress");
            $(this).find("#dataContent").html("").change();
            $(this).find("#titleModalGolongan").html("").change();
            $(this).find("#footer_Golongan").html("").change();
        },
        "submit": function (e) {
            e.preventDefault();
            let ell = $(this).find(':submit').data('el');
            let el = $(ell);
            if (el.valid()) {
                if (ell == "#fm_addGolongan") {
                    var txt = "Tambah";
                } else if (ell == "#fm_editGolongan") {
                    var txt = "Update";
                }
                swal({
                    text: txt + " data golongan (" + el.find('[name="nama_golongan"]').val() + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        txt == "Tambah" ? ajaxAddGolongan(el) : ajaxEditGolongan(el);
                    }
                });
            }
        }
    });
    $("#md_Golongan").on("click",".load-more",function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_Golongan");
        $(this).data("paginate", page + 1);
        $.ajax({
            url: window.location.origin + "/master/golongan/ajax/history",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            cache: false,
            beforeSend: function () {
              form.addClass("modal-progress");
            },
            success: function (data) {
                if (data.content.length == 0) {
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistory").append(data.content);
                    $(".thin").animate(
                        { scrollTop: $(".thin").prop("scrollHeight") },
                        800
                    );
                }
            },
            error: function (err) {
                notifToast("error", err.responseJSON.message);
            },
            complete: function () {
              form.removeClass("modal-progress");
            },
        });
    });
    $(document).on("click", ".btn_DelGolongan", function (e) {
        let nama = $(this).data("nama"),
            id = $(this).data("id");
        swal({
            text: "Hapus data golongan (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteGolongan({
                    id: id,
                });
            }
        });
    });
})
