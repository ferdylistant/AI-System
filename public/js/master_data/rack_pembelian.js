$(function () {
    let tableRack = $("#tb_Rack").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        drawCallback: () => {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-rack'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover'
                    })
                })
        },
        ajax: {
            url: window.location.origin + "/master/rack-pembelian",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "nama_rack",
                name: "nama_rack",
                title: "Nama Rak",
            },
            {
                data: "location",
                name: "location",
                title: "Lokasi Rak",
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
            url: window.location.origin + "/master/rack-pembelian/ajax/" + type,
            type: "GET",
            cache: false,
            data: {
                id: id,
                nama: nama
            },
            success: function (data) {
                $(el).find("#titleModalRack").html(data.title);
                $(el).find("#contentModalRack").html(data.content);
                $(el).find("#footerModalRack").html(data.footer);
                $(el).removeClass("modal-progress");
                if (type == "add") {
                    let valid = jqueryValidation_("#fm_addRack", {
                            nama_rack: {
                                required: true,
                                remote:
                                    window.location.origin +
                                    "/master/rack-pembelian/ajax/check-duplicate-rack",
                            },
                        },
                        {
                            nama_rack: {
                                remote: "Rack sudah terdaftar!",
                            },
                        }
                    );
                } else if (type == "edit") {
                    let valid = jqueryValidation_("#fm_editRack", {
                        nama_rack: {
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

    $("#md_Rack").on({
        "shown.bs.modal": function (e) {
            let type = $(e.relatedTarget).data("type"),
                id = $(e.relatedTarget).data("id"),
                nama = $(e.relatedTarget).data("nama"),
                el = $("#md_Rack");
            showContentModal(el, type, id, nama);
        },
        "hidden.bs.modal": function () {
            $(this).addClass("modal-progress");
            $(this).find("#contentModalRack").html("").change();
            $(this).find("#titleModalRack").html("").change();
            $(this).find("#footerModalRack").html("").change();
        },
        "submit": function (e) {
            e.preventDefault();
            let ell = $(this).find(':submit').data('el');
            let el = $(ell);
            if (el.valid()) {
                if (ell == "#fm_addRack") {
                    var txt = "Tambah";
                } else if (ell == "#fm_editRack") {
                    var txt = "Update";
                }
                swal({
                    text: txt + " data rack (" + el.find('[name="nama_rack"]').val() + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        txt == "Tambah" ? ajaxAddRack(el) : ajaxEditRack(el);
                    }
                });
            }
        }
    });
    // Modal End

    // Add Rack Start
    function ajaxAddRack(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/rack-pembelian/tambah",
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
                    tableRack.ajax.reload();
                    data.trigger("reset");
                    $("#md_Rack").modal("hide");
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.nama_rack);
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
    // Add Rack End

    // Edit Rack Start
    function ajaxEditRack(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/rack-pembelian/ubah",
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
                    $("#md_Rack").modal("hide");
                    tableRack.ajax.reload();
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.nama_rack);
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
    // Edit Rack End

    // Delete Rack Start
    function ajaxDeleteRack(data) {
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/rack-pembelian/hapus",
            data: data,
            beforeSend: function () {
                $(".btn_DelRack")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                tableRack.ajax.reload();
            },
            error: function (err) {},
            complete: function () {
                $(".btn_DelRack")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }
    $(document).on("click", ".btn_DelRack", function (e) {
        let nama = $(this).data("nama"),
            id = $(this).data("id");
        swal({
            text: "Hapus data rack (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteRack({
                    id: id,
                });
            }
        });
    });
    // Delete Rack End

    // History Rack Start
    $("#md_Rack").on("click", ".load-more", function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_Rack");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/rack-pembelian/ajax/history",
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
    // History Rack End
});
