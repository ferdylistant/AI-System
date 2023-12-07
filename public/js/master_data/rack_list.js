
$(function () {
    let tableRackList = $("#tb_RackList").DataTable({
        "responsive": true,
        "autoWidth": false,
        pagingType: "input",
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/master/rack-list",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "nama",
                name: "nama",
                title: "Nama Rak",
            },
            {
                data: "location",
                name: "location",
                title: "Lokasi Rak",
            },
            {
                data: "total_item",
                name: "total_item",
                title: "Total di dalam rak",
            },
            {
                data: "created_at",
                name: "created_at",
                title: "Tanggal Dibuat",
            },
            {
                data: "created_by",
                name: "created_by",
                title: "Dibuat Oleh",
            },
            {
                data: "updated_at",
                name: "updated_at",
                title: "Tanggal Diubah",
            },
            {
                data: "updated_by",
                name: "updated_by",
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
        notifToast("error",settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    // History FBuku Start
    $("#tb_RackList").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/master/rack-list/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalRackList").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Operator Mesin "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_RackListHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_RackListHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/rack-list/lihat-history",
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
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistory").append(response);
                    $('.thin').animate({scrollTop: $('.thin').prop("scrollHeight")}, 800);
                }
                // Setting little delay while displaying new content
                // setTimeout(function() {
                //     // appending posts after last post with class="post"
                //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                // }, 2000);
            },
            complete: function (params) {
                form.removeClass("modal-progress");
            },
        });
    });
    $("#md_RackListHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
    });
    // History FBuku End
    let addForm = jqueryValidation_("#fm_addRackList", {
        nama: {
            required: true,
            remote: window.location.origin + "/master/rack-list/ajax/check-duplicate-rack"
        },
    },
    {
        nama: {
            remote: "Rak sudah terdaftar!"
        }
    });
    // Add FBuku Start
    $(document).ready(function () {
        function ajaxAddJMesin(data) {
            let el = data.get(0);
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/rack-list/tambah",
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
                        tableRackList.ajax.reload();
                        data.trigger("reset");
                        $("#md_AddRackList").modal("hide");
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
                        addForm.showErrors(err);
                    }
                    notifToast("error", "Data rak gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_addRackList").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="nama"]').val();
                swal({
                    text: "Tambah data rak (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxAddJMesin($(this));
                    }
                });
            }
        });
    });
    // Add Operator Mesin End

    // Edit Operator Mesin Start
    $("#md_EditRackList").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/rack-list/ubah",
            type: 'GET',
            data: {
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

    $("#md_EditRackList").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    let editForm = jqueryValidation_("#fm_EditRackList", {
        edit_nama: { required: true },
    });
    $(document).ready(function () {
        function ajaxEditRackList(data) {
            let el = data.get(0);

            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/rack-list/ubah",
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
                        $("#md_EditRackList").modal("hide");
                        tableRackList.ajax.reload();
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
                        editForm.showErrors(err);
                    }
                    notifToast("error", "Data rak gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_EditRackList").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="edit_nama"]').val();
                swal({
                    text: "Ubah data rak (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxEditRackList($(this));
                    }
                });
            }
        });
    });

    // Edit Operator Mesin End

    // Delete Operator Mesin Start
    $(document).ready(function () {
        function ajaxDeleteRackList(data) {
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/rack-list/hapus",
                data: data,
                beforeSend: function () {
                    $(".btn_DelRackList")
                        .prop("disabled", true)
                        .addClass("btn-progress");
                },
                success: function (result) {
                    notifToast(result.status, result.message);
                    tableRackList.ajax.reload();
                },
                error: function (err) {},
                complete: function () {
                    $(".btn_DelRackList")
                        .prop("disabled", true)
                        .removeClass("btn-progress");
                },
            });
        }
        $(document).on("click", ".btn_DelRackList", function (e) {
            let nama = $(this).data("nama"),
                id = $(this).data("id");
            swal({
                text: "Hapus data rak (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeleteRackList({
                        id: id,
                    });
                }
            });
        });
    });
    // Delete Operator Mesin End
});
