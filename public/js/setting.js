$(function () {
    let tableMenu = $("#tb_Menu").DataTable({
        bSort: false,
        responsive: true,
        autoWidth: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/setting",
            data: {
                request_: "table-menu",
            },
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                title: "No",
                orderable: false,
                searchable: false,
                width: "10%",
            },
            {
                data: "name",
                name: "name",
                sDefaultContent: "-",
                title: "Nama",
            },
            {
                data: "nama_bagian",
                name: "nama_bagian",
                title: "Bagian",
            },
            {
                data: "level",
                name: "level",
                sDefaultContent: "-",
                title: "Level",
            },
            {
                data: "url",
                name: "url",
                sDefaultContent: "-",
                title: "Url",
            },
            {
                data: "icon",
                name: "icon",
                sDefaultContent: "-",
                title: "Icon",
            },
            {
                data: "action",
                name: "action",
                title: "Aksi",
                searchable: false,
                orderable: false,
            },
        ],
    });

    let tableBagian = $("#tb_SectionMenu").DataTable({
        bSort: false,
        responsive: true,
        autoWidth: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/setting",
            data: {
                request_: "table-section",
            },
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                title: "No",
                orderable: false,
                searchable: false,
                width: "10%",
            },
            {
                data: "name",
                name: "name",
                title: "Nama",
            },
            {
                data: "order_ab",
                name: "order_ab",
                title: "Order Urutan",
            },
            {
                data: "action",
                name: "action",
                title: "Aksi",
                searchable: false,
                orderable: false,
            },
        ],
    });

    let tablePermission = $("#tb_Permission").DataTable({
        bSort: false,
        responsive: true,
        autoWidth: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/setting",
            data: {
                request_: "table-permission",
            },
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                title: "No",
                orderable: false,
                searchable: false,
                width: "10%",
            },
            {
                data: "name",
                name: "name",
                sDefaultContent: "-",
                title: "Nama",
            },
            {
                data: "nama_access",
                name: "nama_access",
                sDefaultContent: "-",
                title: "Menu",
            },
            {
                data: "nama_bagian",
                name: "nama_bagian",
                sDefaultContent: "-",
                title: "Bagian",
            },
            {
                data: "type",
                name: "type",
                sDefaultContent: "-",
                title: "Type",
            },
            {
                data: "url",
                name: "url",
                sDefaultContent: "-",
                title: "Url",
            },
            {
                data: "raw",
                name: "raw",
                sDefaultContent: "-",
                title: "Kata Kunci",
            },
            {
                data: "action",
                name: "action",
                title: "Aksi",
                searchable: false,
                orderable: false,
            },
        ],
    });

    let editMenu = jqueryValidation_("#fm_EditMenu", {
        add_name: {
            required: true,
        },
        add_bagian: {
            required: true,
        },
        add_level: {
            required: true,
        },
        add_order_menu: {
            required: true,
        },
        add_url: {
            required: true,
        },
        add_icon: {
            required: true,
        },
    });
    let addMenu = jqueryValidation_("#fm_AddMenu", {
        add_name: {
            required: true,
        },
        add_bagian: {
            required: true,
        },
        add_level: {
            required: true,
        },
        add_order_menu: {
            required: true,
        },
        add_url: {
            required: true,
        },
        add_icon: {
            required: true,
        },
    });
    let editSectionMenu = jqueryValidation_("#fm_EditSectionMenu", {
        add_nama: {
            required: true,
        },
        add_order_ab: {
            number: true,
        },
    });
    let addSectionMenu = jqueryValidation_("#fm_AddSectionMenu", {
        add_nama: {
            required: true,
        },
        add_order_ab: {
            number: true,
        },
    });
    let addDivJab = jqueryValidation_("#fm_AddDivJab", {
        add_djnama: {
            required: true,
        },
    });
    let editDivJab = jqueryValidation_("#fm_EditDivJab", {
        edit_djnama: {
            required: true,
        },
    });

    function ajaxAddMenu(data) {
        let el = data.get(0);
        // console.log(data)
        $.ajax({
            type: "POST",
            url: window.location.origin + "/setting/create/menu",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                // console.log(result);
                notifToast(result.status, result.message);
                if (result.status == "success") {
                    tableMenu.ajax.reload();
                    data.trigger("reset");
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
                    addMenu.showErrors(err);
                }
                notifToast("error", "Terjadi kesalahan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    function ajaxEditMenu(data) {
        let el = data.get(0);

        $.ajax({
            type: "POST",
            url: window.location.origin + "/setting/update/menu",
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
                    tableMenu.ajax.reload();
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
                    editMenu.showErrors(err);
                }
                notifToast("error", "Terjadi kesalahan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }
    function ajaxAddSectionMenu(data) {
        let el = data.get(0);
        // console.log(data)
        $.ajax({
            type: "POST",
            url: window.location.origin + "/setting/create/section-menu",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                // console.log(result);
                if (result.status == "success") {
                    tableBagian.ajax.reload();
                    notifToast(result.status, result.message);
                    data.trigger("reset");
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
                    addSectionMenu.showErrors(err);
                }
                notifToast("error", "Terjadi kesalahan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    function ajaxEditSectionMenu(data) {
        let el = data.get(0);

        $.ajax({
            type: "POST",
            url: window.location.origin + "/setting/update/section-menu",
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
                    tableBagian.ajax.reload();
                    notifToast(result.status, result.message);
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
                    editSectionMenu.showErrors(err);
                }
                notifToast("error", "Terjadi kesalahan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    function ajaxDeleteMenu(data) {
        $.ajax({
            type: "POST",
            url: window.location.origin + "/setting/delete/menu",
            data: data,
            beforeSend: function () {
                $(".btn_DelMenu")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                if (result.status == "success") {
                    tableMenu.ajax.reload();
                }
                notifToast(result.status, result.message);
            },
            error: function (err) {},
            complete: function () {
                $(".btn_DelMenu")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }

    function ajaxAddDivJab(data, cat, type) {
        let el = data.get(0);
        type = type.toLowerCase();

        $.ajax({
            type: "POST",
            url:
                "{!!url('manajemen-web/struktur-ao/" +
                cat +
                "/" +
                type +
                "')!!}",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast("success", "Data " + type + " berhasil disimpan!");
                if (type == "divisi") {
                    tableDivisi.ajax.reload();
                } else if (type == "jabatan") {
                    tableJabatan.ajax.reload();
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
                    addDivJab.showErrors(err);
                }
                notifToast("error", "Data " + type + " gagal disimpan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    function ajaxEditDivJab(data, cat, type) {
        let el = data.get(0);
        type = type.toLowerCase();

        $.ajax({
            type: "POST",
            url:
                "{!!url('manajemen-web/struktur-ao/" +
                cat +
                "/" +
                type +
                "')!!}",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast("success", "Data " + type + " berhasil diubah!");
                if (type == "divisi") {
                    tableDivisi.ajax.reload();
                } else if (type == "jabatan") {
                    tableJabatan.ajax.reload();
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
                    editDivJab.showErrors(err);
                }
                notifToast("error", "Data " + type + " gagal diubah!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    function ajaxDeleteDivJab(data, type) {
        type = type.toLowerCase();

        $.ajax({
            type: "POST",
            url: "{!!url('manajemen-web/struktur-ao/delete/" + type + "')!!}",
            data: data,
            beforeSend: function () {
                $(".btn_DelDivJab")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast("success", "Data " + type + " berhasil dihapus!");
                if (type == "divisi") {
                    tableDivisi.ajax.reload();
                } else if (type == "jabatan") {
                    tableJabatan.ajax.reload();
                }
            },
            error: function (err) {},
            complete: function () {
                $(".btn_DelDivJab")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }

    // On Submit //
    $("#fm_AddSectionMenu").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let section = $(this).find('[name="add_nama"]').val();
            swal({
                text: "Tambah data section menu (" + section + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxAddSectionMenu($(this));
                }
            });
        }
    });
    $("#fm_EditSectionMenu").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let section = $(this).find('[name="edit_oldnama"]').val();
            swal({
                text: "Ubah data section menu (" + section + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxEditSectionMenu($(this));
                }
            });
        }
    });
    $("#fm_AddMenu").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="add_name"]').val();
            swal({
                text: "Tambah data (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxAddMenu($(this));
                }
            });
        }
    });

    $("#fm_EditMenu").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="edit_name"]').val();
            swal({
                text: "Ubah data (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxEditMenu($(this));
                }
            });
        }
    });
    // Proses Hapus //
    $("#tb_Menu").on("click", ".btn_DelMenu", function (e) {
        let nama = $(this).data("nama"),
            id = $(this).data("id");

        swal({
            text: "Hapus data menu (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteMenu({
                    id: id,
                });
            }
        });
    });

    $("#tb_Divisi, #tb_Jabatan").on("click", ".btn_DelDivJab", function (e) {
        e.preventDefault();
        let nama = $(this).data("nama"),
            id = $(this).data("id"),
            type = $(this).data("tipe");

        swal({
            text: "Hapus data " + type + " (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteDivJab(
                    {
                        id: id,
                    },
                    type
                );
            }
        });
    });
    $(".select-bagian")
        .select2({
            placeholder: "Pilih bagian",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $("#edit_bagian")
        .select2({
            ajax: {
                url: window.location.origin + "/setting",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        request_: "selectBagian",
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                            };
                        }),
                    };
                },
            },
        }).on("select2:select", function (e) {
            getEditParentVal(e.params.data.id);
        });
    $("#add_bagian")
        .select2({
            ajax: {
                url: window.location.origin + "/setting",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        request_: "selectBagian",
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                            };
                        }),
                    };
                },
            },
        })
        .on("select2:select", function (e) {
            getParentVal(e.params.data.id);
        });
    function getEditParentVal(id) {
        $("#edit_parent").select2({
            ajax: {
                url: window.location.origin + "/setting",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        request_: "selectParent",
                        term: params.term,
                        id: id,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.name,
                            };
                        }),
                    };
                },
            },
        });
    }
    function getParentVal(id) {
        $("#add_parent").select2({
            ajax: {
                url: window.location.origin + "/setting",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        request_: "selectParent",
                        term: params.term,
                        id: id,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                            };
                        }),
                    };
                },
            },
        });
    }
    // On Modal Open //
    $("#md_EditMenu").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/setting",
            data: {
                request_: "data-menu",
                id: id,
            },
            success: function (result) {
                for (let r in result) {
                    const rdio = ["level"];
                    if (rdio.includes(r)) {
                        $('[name="edit_' + r + '"]').val([result[r]]);
                        if (result[r] == 1) {
                            $("[name='edit_parent']").attr("required",false);
                        } else {
                            $("[name='edit_parent']").attr("required",true);
                        }
                    } else if (r == "bagian_id") {
                        // console.log([result[r]]);
                        // $('[name="edit_bagian"]').val([result[r]]).change();
                        $('[name="edit_bagian"]').select2("trigger", "select", {
                            data: {
                                id: result[r].id,
                                text: result[r].name
                            }
                        });
                    }  else if (r == "parent_id") {
                        console.log(result[r]);
                        if (result[r]) {
                            // console.log(result[r]);
                            $("#parentId")
                                .html(`<div class="form-group row mb-4 lvl" id="level2">
                            <label class="col-form-label text-md-right col-12 col-md-3">Parent Level</label>
                            <div class="col-sm-12 col-md-9">
                                <select id="edit_parent" name="edit_parent" class="form-control select-parent" required></select>
                                <div id="err_edit_parent"></div>
                            </div>
                            </div>`);
                            // $("#level2").show("slow");
                            // if ($('[name="edit_parent"]').data('select2')) {
                            // }
                            // $('[name="edit_parent"]').select2('destroy');
                            $('[name="edit_parent"]').val([result[r]]);
                            $('[name="edit_parent"]').change();
                            // $('[name="edit_parent"]').select2("trigger", "select", {
                            //     data: {
                            //         id: result[r].id,
                            //         text: result[r].name
                            //     }
                            // });
                        } else {
                            $("#parentId")
                                .html(`<div class="form-group row mb-4 lvl" style="display:none" id="level2">
                            <label class="col-form-label text-md-right col-12 col-md-3">Parent Level</label>
                            <div class="col-sm-12 col-md-9">
                                <select id="edit_parent" name="edit_parent" class="form-control select-parent" required></select>
                                <div id="err_edit_parent"></div>
                            </div>
                        </div>`);
                        }
                    } else {
                        $('[name="edit_' + r + '"]')
                            .val(result[r])
                            .change();
                    }
                }
                // Object.entries(result).forEach(entry => {
                //     let [key, value] = entry;
                //     // const rdio = ['level']
                //     if (key == 'bagian_id') {
                //         $('[name="edit_bagian"]').val(value).change();
                //     } else if (key == 'parent_id') {
                //         if (value) {
                //             $('#parentId').html(`<div class="form-group row mb-4 lvl" style="display:none" id="level2">
                //             <label class="col-form-label text-md-right col-12 col-md-3">Parent Level</label>
                //             <div class="col-sm-12 col-md-9">
                //                 <select id="edit_parent" name="edit_parent" class="form-control select-parent" required></select>
                //                 <div id="err_edit_parent"></div>
                //             </div>
                //             </div>`);
                //             $('[name="edit_' + key + '"]').val(value).change();
                //             $('#level2').show('slow');
                //         } else {
                //             $('#parentId').html(`<div class="form-group row mb-4 lvl" style="display:none" id="level2">
                //             <label class="col-form-label text-md-right col-12 col-md-3">Parent Level</label>
                //             <div class="col-sm-12 col-md-9">
                //                 <select id="edit_parent" name="edit_parent" class="form-control select-parent" required></select>
                //                 <div id="err_edit_parent"></div>
                //             </div>
                //         </div>`);
                //         }
                //     } else {
                //         // $('[name="edit_' + r + '"]').val(result[r]).change();
                //         form_.find('[name="edit_' + key + '"]').val(value);
                //     }

                // });
                form_.removeClass("modal-progress");
            },
        });
    });
    $("#md_EditMenu").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    $("#md_EditSectionMenu").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/setting",
            data: {
                request_: "data-section-menu",
                id: id,
            },
            success: function (result) {
                Object.entries(result).forEach((entry) => {
                    let [key, value] = entry;
                    form_.find('[name="edit_' + key + '"]').val(value);
                });
                form_.removeClass("modal-progress");
            },
        });
    });
    $("#md_EditSectionMenu").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });

    $("#md_AddDivJab").on("shown.bs.modal", function (e) {
        let type = $(e.relatedTarget).data("tipe");
        $(this)
            .find(".modal-body h5")
            .html("#Tambah " + type);
        $(this).find('[name="add_type"]').val(type);
        $(this)
            .find('[name="add_djnama"]')
            .attr("placeholder", "Tambah " + type);
        $(this).removeClass("modal-progress");
    });
    $("#md_AddDivJab").on("hidden.bs.modal", function (e) {
        $(this)
            .find('[name="add_djnama"]')
            .attr("placeholder", "")
            .removeClass("is-invalid");
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });

    $("#md_EditDivJab").on("shown.bs.modal", function (e) {
        let type = $(e.relatedTarget).data("tipe"),
            nama = $(e.relatedTarget).data("nama"),
            id = $(e.relatedTarget).data("id");

        $(this)
            .find(".modal-body h5")
            .html("#Ubah " + type);
        $(this).find('[name="edit_type"]').val(type);
        $(this).find('[name="edit_djid"]').val(id);
        $(this).find('[name="edit_djoldnama"]').val(nama);
        $(this)
            .find('[name="edit_djnama"]')
            .val(nama)
            .attr("placeholder", "Tambah " + type);
        $(this).removeClass("modal-progress");
    });
    $("#md_EditDivJab").on("hidden.bs.modal", function () {
        $(this)
            .find('[name="edit_djnama"]')
            .attr("placeholder", "")
            .removeClass("is-invalid");
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    $(document).ready(function () {
        $("input[name$=add_level]").change(function () {
            var res = $(this).val();
            if (res == 2) {
                $("[name='add_parent']").attr("required",true);
            } else {
                $("[name='add_parent']").attr("required",false);
            }
            $("div.lvl").hide("slow");
            $("#level" + res).show("slow");
        });
    });
    $(document).ready(function () {
        $("input[name$=edit_level]").change(function () {
            var res = $(this).val();
            if (res == 2) {
                $("[name='edit_parent']").attr("required",true);
                $("div.lvl").show("slow");
                // $("#level" + res).show("slow");
            } else {
                $("[name='edit_parent']").attr("required",false);
                $("div.lvl").hide("slow");
                // $("#level" + res).hide("slow");
            }

        });
    });
});
