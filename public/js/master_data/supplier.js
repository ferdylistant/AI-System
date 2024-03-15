$(function () {
    let tableSupplier = $("#tb_Supplier").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        drawCallback: () => {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-supplier'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover'
                    })
                })
        },
        ajax: {
            url: window.location.origin + "/master/supplier",
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
                data: "nama_supplier",
                name: "nama_supplier",
                title: "Nama Supplier",
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
            url: window.location.origin + "/master/supplier/ajax/" + type,
            type: "GET",
            cache: false,
            data: {
                id: id,
                nama: nama
            },
            success: function (data) {
                $(el).find("#titleModalSupplier").html(data.title);
                $(el).find("#contentModalSupplier").html(data.content);
                $(el).find("#footerModalSupplier").html(data.footer);
                $(el).removeClass("modal-progress");
                $(".select-sub-wilayah")
                .select2({
                    placeholder: "Pilih sub wilayah",
                });
                var npwpElements = document.querySelectorAll('#npwp');
                var maskNPWP = {
                    mask: '00.000.000.0-000.000'
                };
                npwpElements.forEach(function(element) {
                    IMask(element, maskNPWP, {reverse: true});
                });
                if (type == "add") {
                    $("#select_wilayah")
                    .select2({
                        placeholder: "Pilih wilayah",
                        ajax: {
                            url: window.location.origin + "/master/supplier/ajax/select-wilayah",
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
                                            text: item.name,
                                            id: item.name + "-" + item.id,
                                        };
                                    }),
                                };
                            },
                            cache: true,
                        },
                    })
                    .on("change", function (e) {
                        if (this.value) {
                            $(this).valid();
                            $('#select_sub_wilayah').val(null);
                            $("#select_sub_wilayah")
                            .select2({
                                placeholder: "Pilih sub wilayah",
                                ajax: {
                                    url: window.location.origin + "/master/supplier/ajax/select-sub-wilayah",
                                    type: "GET",
                                    data: {
                                        id: this.value.split('-')[1]
                                    },
                                    processResults: function (data) {
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.name,
                                                    id: item.name + "-" + item.id,
                                                };
                                            }),
                                        };
                                    },
                                    cache: true,
                                },
                            });
                        }
                    });
                    let valid = jqueryValidation_("#fm_addSupplier", {
                            nama_supplier: {
                                required: true,
                            },
                            alamat_supplier: {
                                required: true,
                            },
                            wilayah_supplier: {
                                required: true,
                            },
                            sub_wilayah_supplier: {
                                required: true,
                            },
                            telepon_supplier: {
                                required: true,
                            },
                            fax_supplier: {
                                required: true,
                            },
                            kontak_supplier: {
                                required: true,
                            },
                            npwp_supplier: {
                                required: true,
                            }
                        }
                    );
                } else if (type == "edit") {
                    let valid = jqueryValidation_("#fm_editSupplier", {
                        kode_supplier: {
                            required: true,
                        },
                        nama_supplier: {
                            required: true,
                        },
                        alamat_supplier: {
                            required: true,
                        }
                    });
                }
            },
            error: function (data) {
                console.log(data);
            },
        })
    }

    $("#md_Supplier").on({
        "shown.bs.modal": function (e) {
            let type = $(e.relatedTarget).data("type"),
                id = $(e.relatedTarget).data("id"),
                nama = $(e.relatedTarget).data("nama"),
                el = $("#md_Supplier");
            showContentModal(el, type, id, nama);
        },
        "hidden.bs.modal": function () {
            $(this).addClass("modal-progress");
            $(this).find("#contentModalSupplier").html("").change();
            $(this).find("#titleModalSupplier").html("").change();
            $(this).find("#footerModalSupplier").html("").change();
        },
        "submit": function (e) {
            e.preventDefault();
            let ell = $(this).find(':submit').data('el');
            let el = $(ell);
            if (el.valid()) {
                if (ell == "#fm_addSupplier") {
                    var txt = "Tambah";
                } else if (ell == "#fm_editSupplier") {
                    var txt = "Update";
                }
                swal({
                    text: txt + " data supplier (" + el.find('[name="nama_supplier"]').val() + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        txt == "Tambah" ? ajaxAddSupplier(el) : ajaxEditSupplier(el);
                    }
                });
            }
        }
    });
    // Modal End

    // Add Supplier Start
    function ajaxAddSupplier(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/supplier/tambah",
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
                    tableSupplier.ajax.reload();
                    data.trigger("reset");
                    $("#md_Supplier").modal("hide");
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.kode_supplier);
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
    // Add Supplier End

    // Edit Supplier Start
    function ajaxEditSupplier(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/supplier/ubah",
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
                    $("#md_Supplier").modal("hide");
                    tableSupplier.ajax.reload();
                } else if(result.status == "error") {
                    notifToast(result.status, result.errors.kode_supplier);
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
    // Edit Supplier End

    // Delete Supplier Start
    function ajaxDeleteSupplier(data) {
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/supplier/hapus",
            data: data,
            beforeSend: function () {
                $(".btn_DelSupplier")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                tableSupplier.ajax.reload();
            },
            error: function (err) {},
            complete: function () {
                $(".btn_DelSupplier")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }
    $(document).on("click", ".btn_DelSupplier", function (e) {
        let nama = $(this).data("nama"),
            id = $(this).data("id");
        swal({
            text: "Hapus data supplier (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteSupplier({
                    id: id,
                });
            }
        });
    });
    // Delete Supplier End
});
