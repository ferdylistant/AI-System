$(function () {
    let tableSGolongan = $("#tb_SType").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        drawCallback: () => {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-sgolongan'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover'
                    })
                })
        },
        ajax: {
            url: window.location.origin + "/master/sub-golongan",
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
                data: "nama_sgolongan",
                name: "nama_sgolongan",
                title: "Nama",
            },
            {
                data: "golongan_id",
                name: "golongan_id",
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
            url: window.location.origin + "/master/sub-golongan/ajax/" + type,
            type: "GET",
            cache: false,
            data: {
                id: id,
                nama: nama
            },
            success: function (data) {
                $(el).find("#titleModalSubGolongan").html(data.title);
                $(el).find("#contentModalSubGolongan").html(data.content);
                $(el).find("#footerModalSubGolongan").html(data.footer);
                $(el).removeClass("modal-progress");
                $(".select-golongan")
                    .select2({
                        placeholder: "Pilih golongan",
                    })
                    .on("change", function (e) {
                        if (this.value) {
                            $(this).valid();
                        }
                    });
                if (type == "add") {
                    $("#add_golongan")
                        .select2({
                            placeholder: "Pilih golongan",
                            ajax: {
                                url: window.location.origin + "/master/sub-golongan/ajax/select",
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
                    let valid = jqueryValidation_("#fm_addSGolongan", {
                            nama_golongan: {
                                required: true,
                            },
                            nama_sgolongan: {
                                required: true,
                                remote:
                                    window.location.origin +
                                    "/master/sub-golongan/ajax/check-duplicate-sgolongan",
                            },
                        },
                        {
                            nama_sgolongan: {
                                remote: "Sub Golongan sudah terdaftar!",
                            },
                        }
                    );
                } else if (type == "edit") {
                    $("#edit_golongan")
                        .select2({
                            placeholder: "Pilih golongan",
                            ajax: {
                                url: window.location.origin + "/master/sub-golongan/ajax/select",
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
                    let valid = jqueryValidation_("#fm_editSGolongan", {
                        nama_sgolongan: {
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

    $("#md_SGolongan").on({
        "shown.bs.modal": function (e) {
            let type = $(e.relatedTarget).data("type"),
                id = $(e.relatedTarget).data("id"),
                nama = $(e.relatedTarget).data("nama"),
                el = $("#md_SGolongan");
            showContentModal(el, type, id, nama);
        },
        "hidden.bs.modal": function () {
            $(this).addClass("modal-progress");
            $(this).find("#contentModalSubGolongan").html("").change();
            $(this).find("#titleModalSubGolongan").html("").change();
            $(this).find("#footerModalSubGolongan").html("").change();
        },
        "submit": function (e) {
            e.preventDefault();
            let ell = $(this).find(':submit').data('el');
            let el = $(ell);
            if (el.valid()) {
                if (ell == "#fm_addSGolongan") {
                    var txt = "Tambah data sub golongan (" + el.find('[name="nama_sgolongan"]').val() + ")?";
                } else if (ell == "#fm_editSGolongan") {
                    var txt = "Update data sub golongan (" + el.find('[name="edit_nama"]').val() + ")?";
                }
                swal({
                    text: txt,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ell == "#fm_addSGolongan" ? ajaxAddSGolongan(el) : ajaxEditSGolongan(el);
                    }
                });
            }
        }
    });
    // Modal End

    // Add Sub Type Start
    function ajaxAddSGolongan(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/sub-golongan/tambah",
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
                    tableSGolongan.ajax.reload();
                    data.trigger("reset");
                    $("#md_SGolongan").modal("hide");
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.nama_sgolongan);
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
    $("#md_SGolongan").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/sub-golongan/ubah",
            data: {
                id: id,
            },
            success: function (result) {
                $("#edit_type").select2("trigger", "select", {
                    data: {
                        id: result.golongan_id,
                        text: result.nama_golongan
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
    function ajaxEditSGolongan(data) {
        let el = data.get(0);

        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/sub-golongan/ubah",
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
                    $("#md_SGolongan").modal("hide");
                    tableSGolongan.ajax.reload();
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
    function ajaxDeleteSGolongan(data) {
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/sub-golongan/hapus",
            data: data,
            beforeSend: function () {
                $(".btn_DelSGolongan")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                tableSGolongan.ajax.reload();
            },
            error: function (err) {},
            complete: function () {
                $(".btn_DelSGolongan")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }
    $(document).on("click", ".btn_DelSGolongan", function (e) {
        let nama = $(this).data("nama"),
            id = $(this).data("id");
        swal({
            text: "Hapus data sub golongan (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteSGolongan({
                    id: id,
                });
            }
        });
    });
    // Delete Sub Type End

    // History Type Start
    $("#md_SGolongan").on("click", ".load-more", function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_SGolongan");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/sub-golongan/ajax/history",
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
