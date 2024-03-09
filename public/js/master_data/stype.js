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

    // Add Sub Type Start
    $(".select-type")
        .select2({
            placeholder: "Pilih type",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $("#add_type")
    .select2({
        placeholder: "Pilih type",
        ajax: {
            url: window.location.origin + "/master/sub-type/ajax-select",
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

    let addValidate = jqueryValidation_(
        "#fm_addSType",
        {
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
                    $("#md_AddSType").modal("hide");
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
                    addValidate.showErrors(err);
                }
                notifToast("error", err.statusText);
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fm_addSType").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="nama_stype"]').val();
            swal({
                text: "Tambah data sub type (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxAddSType($(this));
                }
            });
        }
        return false;
    });
    // Add Sub Type End

    // Edit Sub Type Start
    $("#edit_type")
    .select2({
        placeholder: "Pilih type",
        ajax: {
            url: window.location.origin + "/master/sub-type/ajax-select",
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

    $("#md_EditSType").on("shown.bs.modal", function (e) {
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

    $("#md_EditSType").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    let editValidate = jqueryValidation_("#fm_EditSType", {
        edit_nama: {
            required: true,
        },
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
                    $("#md_EditSType").modal("hide");
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
                    editValidate.showErrors(err);
                }
                notifToast("error", "Data type gagal disimpan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fm_EditSType").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="edit_nama"]').val();
            swal({
                text: "Ubah data Sub Type (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxEditSType($(this));
                }
            });
        }
    });
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
            text: "Hapus data Sub Type (" + nama + ")?",
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
    $("#tb_SType").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/master/sub-type/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalSType").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Sub Type "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_STypeHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_STypeHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/sub-type/lihat-history",
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
                } else {
                    $("#dataHistory").append(response);
                    $(".thin").animate(
                        { scrollTop: $(".thin").prop("scrollHeight") },
                        800
                    );
                }
            },
            complete: function (params) {
                form.removeClass("modal-progress");
            },
        });
    });
    $("#md_STypeHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false);
    });
    // History Sub Type End
});