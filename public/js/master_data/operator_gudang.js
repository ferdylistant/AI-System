
$(function () {
    let tableOperatorGudang = $("#tb_OperatorGudang").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/master/operator-gudang",
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
                title: "Nama Operator Gudang",
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
    $("#tb_OperatorGudang").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/master/operator-gudang/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalOperatorGudang").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Operator Mesin "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_OperatorGudangHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_OperatorGudangHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/operator-gudang/lihat-history",
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
    $("#md_OperatorGudangHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
    });
    // History FBuku End
    let addForm = jqueryValidation_("#fm_addOperatorGudang", {
        nama: {
            required: true,
            remote: window.location.origin + "/master/operator-gudang/ajax/check-duplicate-operator-gudang"
        },
    },
    {
        nama: {
            remote: "Operator gudang sudah terdaftar!"
        }
    });
    // Add FBuku Start
    $(document).ready(function () {
        function ajaxAddJMesin(data) {
            let el = data.get(0);
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/operator-gudang/tambah",
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
                        tableOperatorGudang.ajax.reload();
                        data.trigger("reset");
                        $("#md_AddOperatorGudang").modal("hide");
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
                    notifToast("error", "Data operator gudang gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_addOperatorGudang").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="nama"]').val();
                swal({
                    text: "Tambah data operator gudang (" + nama + ")?",
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
    $("#md_EditOperatorGudang").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/operator-gudang/ubah",
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

    $("#md_EditOperatorGudang").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    let editForm = jqueryValidation_("#fm_EditOperatorGudang", {
        edit_nama: { required: true },
    });
    $(document).ready(function () {
        function ajaxEditOperatorGudang(data) {
            let el = data.get(0);

            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/operator-gudang/ubah",
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
                        $("#md_EditOperatorGudang").modal("hide");
                        tableOperatorGudang.ajax.reload();
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
                    notifToast("error", "Data operator gudang gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_EditOperatorGudang").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="edit_nama"]').val();
                swal({
                    text: "Ubah data Operator Gudang (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxEditOperatorGudang($(this));
                    }
                });
            }
        });
    });

    // Edit Operator Mesin End

    // Delete Operator Mesin Start
    $(document).ready(function () {
        function ajaxDeleteOperatorGudang(data) {
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/operator-gudang/hapus",
                data: data,
                beforeSend: function () {
                    $(".btn_DelOperatorGudang")
                        .prop("disabled", true)
                        .addClass("btn-progress");
                },
                success: function (result) {
                    notifToast(result.status, result.message);
                    tableOperatorGudang.ajax.reload();
                },
                error: function (err) {},
                complete: function () {
                    $(".btn_DelOperatorGudang")
                        .prop("disabled", true)
                        .removeClass("btn-progress");
                },
            });
        }
        $(document).on("click", ".btn_DelOperatorGudang", function (e) {
            let nama = $(this).data("nama"),
                id = $(this).data("id");
            swal({
                text: "Hapus data Operator Gudang (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeleteOperatorGudang({
                        id: id,
                    });
                }
            });
        });
    });
    // Delete Operator Mesin End
});
