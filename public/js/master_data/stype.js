$(function () {
    let tableSType = $("#tb_SType").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        drawCallback: () => {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-stype'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover'
                    })
                })
        },
        ajax: {
            url: window.location.origin + "/master/sub-type",
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
                data: "nama_stype",
                name: "nama_stype",
                title: "Nama",
            },
            {
                data: "type_id",
                name: "type_id",
                title: "Type",
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
            url: window.location.origin + "/master/sub-type/ajax/" + type,
            type: "GET",
            cache: false,
            data: {
                id: id,
                nama: nama
            },
            success: function (data) {
                $(el).find("#titleModalSubType").html(data.title);
                $(el).find("#contentModalSubType").html(data.content);
                $(el).find("#footerModalSubType").html(data.footer);
                $(el).removeClass("modal-progress");
                $(".select-type")
                    .select2({
                        placeholder: "Pilih type",
                    })
                    .on("change", function (e) {
                        if (this.value) {
                            $(this).valid();
                        }
                    });
                if (type == "add") {
                    $("#add_type")
                        .select2({
                            placeholder: "Pilih type",
                            ajax: {
                                url: window.location.origin + "/master/sub-type/ajax/select",
                                type: "GET",
                                data: function (params) {
                                    var queryParameters = {
                                        term: params.term,
                                    };
                                    return queryParameters;
                                },
                                processResults: function (data) {
                                    return {
                                        results: $.map(data, function (item) {
                                            return {
                                                text: item.nama,
                                                id: item.id,
                                            };
                                        }),
                                    };
                                },
                            },
                        });
                    let valid = jqueryValidation_("#fm_addSType", {
                            nama_type: {
                                required: true,
                            },
                            nama_stype: {
                                required: true,
                                remote:
                                    window.location.origin +
                                    "/master/sub-type/ajax/check-duplicate-stype",
                            },
                        },
                        {
                            nama_stype: {
                                remote: "Sub Type sudah terdaftar!",
                            },
                        }
                    );
                } else if (type == "edit") {
                    $("#edit_type")
                        .select2({
                            placeholder: "Pilih type",
                            ajax: {
                                url: window.location.origin + "/master/sub-type/ajax/select",
                                type: "GET",
                                data: function (params) {
                                    var queryParameters = {
                                        term: params.term,
                                    };
                                    return queryParameters;
                                },
                                processResults: function (data) {
                                    return {
                                        results: $.map(data, function (item) {
                                            return {
                                                text: item.nama,
                                                id: item.id,
                                            };
                                        }),
                                    };
                                },
                            },
                        });
                    let valid = jqueryValidation_("#fm_editSType", {
                        nama_stype: {
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

    $("#md_SType").on({
        "shown.bs.modal": function (e) {
            let type = $(e.relatedTarget).data("type"),
                id = $(e.relatedTarget).data("id"),
                nama = $(e.relatedTarget).data("nama"),
                el = $("#md_SType");
            showContentModal(el, type, id, nama);
        },
        "hidden.bs.modal": function () {
            $(this).addClass("modal-progress");
            $(this).find("#contentModalSubType").html("").change();
            $(this).find("#titleModalSubType").html("").change();
            $(this).find("#footerModalSubType").html("").change();
        },
        "submit": function (e) {
            e.preventDefault();
            let ell = $(this).find(':submit').data('el');
            let el = $(ell);
            if (el.valid()) {
                if (ell == "#fm_addSType") {
                    var txt = "Tambah data sub type (" + el.find('[name="nama_stype"]').val() + ")?";
                } else if (ell == "#fm_editSType") {
                    var txt = "Update data sub type (" + el.find('[name="edit_nama"]').val() + ")?";
                }
                swal({
                    text: txt,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ell == "#fm_addSType" ? ajaxAddSType(el) : ajaxEditSType(el);
                    }
                });
            }
        }
    });
    // Modal End

    // Add Sub Type Start
    function ajaxAddSType(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/sub-type/tambah",
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
                    tableSType.ajax.reload();
                    data.trigger("reset");
                    $("#md_SType").modal("hide");
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.nama_stype);
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
    // Add Sub Type End

    // Edit Sub Type Start
    $("#md_SType").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/sub-type/ubah",
            data: {
                id: id,
            },
            success: function (result) {
                $("#edit_type").select2("trigger", "select", {
                    data: {
                        id: result.type_id,
                        text: result.nama_type
                    }
                })
                Object.entries(result).forEach((entry) => {
                    let [key, value] = entry;
                    form_.find('[name="edit_' + key + '"]').val(value);
                });
                form_.removeClass("modal-progress");
            },
        });
    });
    function ajaxEditSType(data) {
        let el = data.get(0);

        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/sub-type/ubah",
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
                    $("#md_SType").modal("hide");
                    tableSType.ajax.reload();
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.edit_nama);
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
    // Edit Sub Type End

    // Delete Sub Type Start
    function ajaxDeleteSType(data) {
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/sub-type/hapus",
            data: data,
            beforeSend: function () {
                $(".btn_DelType")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                tableSType.ajax.reload();
            },
            error: function (err) {},
            complete: function () {
                $(".btn_DelType")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }
    $(document).on("click", ".btn_DelSType", function (e) {
        let nama = $(this).data("nama"),
            id = $(this).data("id");
        swal({
            text: "Hapus data sub type (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteSType({
                    id: id,
                });
            }
        });
    });
    // Delete Sub Type End

    // History Type Start
    $("#md_SType").on("click", ".load-more", function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_SType");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/sub-type/ajax/history",
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
    // History Sub Type End
});
