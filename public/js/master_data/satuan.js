$(function () {
    let tableSatuan = $("#tb_Satuan").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        drawCallback: () => {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-satuan'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover'
                    })
                })
        },
        ajax: {
            url: window.location.origin + "/master/satuan",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "nama_satuan",
                name: "nama_satuan",
                title: "Nama Satuan",
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
            url: window.location.origin + "/master/satuan/ajax/" + type,
            type: "GET",
            cache: false,
            data: {
                id: id,
                nama: nama
            },
            success: function (data) {
                $(el).find("#titleModalSatuan").html(data.title);
                $(el).find("#contentModalSatuan").html(data.content);
                $(el).find("#footerModalSatuan").html(data.footer);
                $(el).removeClass("modal-progress");
                if (type == "add") {
                    let valid = jqueryValidation_("#fm_addSatuan", {
                            nama_satuan: {
                                required: true,
                                remote:
                                    window.location.origin +
                                    "/master/satuan/ajax/check-duplicate-name",
                            },
                        },
                        {
                            nama_satuan: {
                                remote: "Satuan sudah terdaftar!",
                            },
                        }
                    );
                } else if (type == "edit") {
                    let valid = jqueryValidation_("#fm_editSatuan", {
                        nama_satuan: {
                            required: true,
                        },
                    });
                }
            },
            error: function (data) {
                console.log(data);
            },
        })
    }

    $("#md_Satuan").on({
        "shown.bs.modal": function (e) {
            let type = $(e.relatedTarget).data("type"),
                id = $(e.relatedTarget).data("id"),
                nama = $(e.relatedTarget).data("nama"),
                el = $("#md_Satuan");
            showContentModal(el, type, id, nama);
        },
        "hidden.bs.modal": function () {
            $(this).addClass("modal-progress");
            $(this).find("#contentModalSatuan").html("").change();
            $(this).find("#titleModalSatuan").html("").change();
            $(this).find("#footerModalSatuan").html("").change();
        },
        "submit": function (e) {
            e.preventDefault();
            let ell = $(this).find(':submit').data('el');
            let el = $(ell);
            if (el.valid()) {
                if (ell == "#fm_addSatuan") {
                    var txt = "Tambah";
                } else if (ell == "#fm_editSatuan") {
                    var txt = "Update";
                }
                swal({
                    text: txt + " data satuan (" + el.find('[name="nama_satuan"]').val() + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        txt == "Tambah" ? ajaxAddSatuan(el) : ajaxEditSatuan(el);
                    }
                });
            }
        }
    });
    // Modal End

    // Add Satuan Start
    function ajaxAddSatuan(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/satuan/tambah",
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
                    tableSatuan.ajax.reload();
                    data.trigger("reset");
                    $("#md_Satuan").modal("hide");
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.nama_satuan);
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
    // Add Satuan End

    // Edit Satuan Start
    function ajaxEditSatuan(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/satuan/ubah",
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
                    $("#md_Satuan").modal("hide");
                    tableSatuan.ajax.reload();
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.nama_satuan);
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
    // Edit Satuan End

    // Delete Satuan Start
    function ajaxDeleteSatuan(data) {
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/satuan/hapus",
            data: data,
            beforeSend: function () {
                $(".btn_DelSatuan")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                tableSatuan.ajax.reload();
            },
            error: function (err) {},
            complete: function () {
                $(".btn_DelSatuan")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }
    $(document).on("click", ".btn_DelSatuan", function (e) {
        let nama = $(this).data("nama"),
            id = $(this).data("id");
        swal({
            text: "Hapus data satuan (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteSatuan({
                    id: id,
                });
            }
        });
    });
    // Delete Satuan End
});
