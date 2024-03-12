$(function () {
    let tableType = $("#tb_Type").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        drawCallback: () => {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-type'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover'
                    })
                })
        },
        ajax: {
            url: window.location.origin + "/master/type",
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
                data: "nama_type",
                name: "nama_type",
                title: "Nama Type",
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
        notifToast("error", settings.jqXHR.statusText);
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };

    // Modal Start
    function showContentModal(el, type, id, nama) {
        $.ajax({
            url: window.location.origin + "/master/type/ajax/" + type,
            type: "GET",
            cache: false,
            data: {
                id: id,
                nama: nama
            },
            success: function (data) {
                $(el).find("#titleModalType").html(data.title);
                $(el).find("#contentModalType").html(data.content);
                $(el).find("#footerModalType").html(data.footer);
                $(el).removeClass("modal-progress");
                if (type == "add") {
                    let valid = jqueryValidation_("#fm_addType", {
                            nama_type: {
                                required: true,
                                remote:
                                    window.location.origin +
                                    "/master/type/ajax/check-duplicate-type",
                            },
                        },
                        {
                            nama_type: {
                                remote: "Type sudah terdaftar!",
                            },
                        }
                    );
                } else if (type == "edit") {
                    let valid = jqueryValidation_("#fm_editType", {
                        nama_type: {
                            required: true,
                        },
                    });
                } else if (type == "history") {
                    $("#load_more").data("id", id);
                }
            },
            error: function (data) {
                console.log(data);
            },
        })
    }

    $("#md_Type").on({
        "shown.bs.modal": function (e) {
            let type = $(e.relatedTarget).data("type"),
                id = $(e.relatedTarget).data("id"),
                nama = $(e.relatedTarget).data("nama"),
                el = $("#md_Type");
            showContentModal(el, type, id, nama);
        },
        "hidden.bs.modal": function () {
            $(this).addClass("modal-progress");
            $(this).find("#contentModalType").html("").change();
            $(this).find("#titleModalType").html("").change();
            $(this).find("#footerModalType").html("").change();
        },
        "submit": function (e) {
            e.preventDefault();
            let ell = $(this).find(':submit').data('el');
            let el = $(ell);
            if (el.valid()) {
                if (ell == "#fm_addType") {
                    var txt = "Tambah";
                } else if (ell == "#fm_editType") {
                    var txt = "Update";
                }
                swal({
                    text: txt + " data type (" + el.find('[name="nama_type"]').val() + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        txt == "Tambah" ? ajaxAddType(el) : ajaxEditType(el);
                    }
                });
            }
        }
    });
    // Modal End

    // Add Type Start
    function ajaxAddType(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/type/tambah",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                if (result.status == "success") {
                    notifToast(result.status, result.message);
                    tableType.ajax.reload();
                    data.trigger("reset");
                    $("#md_Type").modal("hide");
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.nama_type);
                } else {
                    notifToast(result.status, result.message);
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
    // Add Type End

    // Edit Type Start
    function ajaxEditType(data) {
        let el = data.get(0);

        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/type/ubah",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                if (result.status == "success") {
                    notifToast(result.status, result.message);
                    $("#md_Type").modal("hide");
                    tableType.ajax.reload();
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.nama_type);
                } else {
                    notifToast(result.status, result.message);
                }
            },
            error: function (err) {
                rs = err.responseJSON.errors;
                if (rs !== undefined) {
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
    // Edit Type End

    // Delete Type Start
    function ajaxDeleteType(data) {
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/type/hapus",
            data: data,
            beforeSend: function () {
                $(".btn_DelType")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                tableType.ajax.reload();
            },
            error: function (err) {},
            complete: function () {
                $(".btn_DelType")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }
    $(document).on("click", ".btn_DelType", function (e) {
        let nama = $(this).data("nama"),
            id = $(this).data("id");
        swal({
            text: "Hapus data type (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteType({
                    id: id,
                });
            }
        });
    });
    // Delete Type End

    // History Type Start
    $("#md_Type").on("click", ".load-more", function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_Type");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/type/ajax/history",
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
                if (response.content.length == 0) {
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistory").append(response.content);
                    $(".thin").animate(
                        { scrollTop: $(".thin").prop("scrollHeight") },
                        800
                    );
                }
            },
            error: function (err) {
                notifToast("error", err.responseJSON.message);
            },
            complete: function (params) {
                form.removeClass("modal-progress");
            },
        });
    });
    // History Type End
});
