$(function () {
    let tableHargaJual = $("#tb_HargaJual").DataTable({
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
            url: window.location.origin + "/master/harga-jual",
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
                title: "Nama Harga Jual",
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
    $("#tb_HargaJual").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/master/harga-jual/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalHargaJual").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Harga Jual "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_HargaJualHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_HargaJualHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/harga-jual/lihat-history",
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
    $("#md_HargaJualHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
    });
    // History FBuku End
    let addForm = jqueryValidation_("#fm_addHargaJual", {
        nama: {
            required: true,
            remote: window.location.origin + "/master/harga-jual/ajax/check-duplicate-harga-jual"
        },
    },
    {
        nama: {
            remote: "Nama Harga Jual sudah terdaftar!"
        }
    });
    // Add FBuku Start
    $(document).ready(function () {
        function ajaxAddHargaJual(data) {
            let el = data.get(0);
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/harga-jual/tambah",
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
                        tableHargaJual.ajax.reload();
                        data.trigger("reset");
                        $("#md_AddHargaJual").modal("hide");
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
                    notifToast("error", "Data harga jual gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_addHargaJual").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="nama"]').val();
                swal({
                    text: "Tambah data harga jual (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxAddHargaJual($(this));
                    }
                });
            }
        });
    });
    // Add Operator Mesin End

    // Edit Operator Mesin Start
    $("#md_EditHargaJual").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/harga-jual/ubah",
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

    $("#md_EditHargaJual").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    let editForm = jqueryValidation_("#fm_EditHargaJual", {
        edit_nama: { required: true },
    });
    $(document).ready(function () {
        function ajaxEditHargaJual(data) {
            let el = data.get(0);

            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/harga-jual/ubah",
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
                        $("#md_EditHargaJual").modal("hide");
                        tableHargaJual.ajax.reload();
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
                    notifToast("error", "Data harga jual gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_EditHargaJual").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="edit_nama"]').val();
                swal({
                    text: "Ubah data harga jual (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxEditHargaJual($(this));
                    }
                });
            }
        });
    });

    // Edit Operator Mesin End

    // Delete Operator Mesin Start
    $(document).ready(function () {
        function ajaxDeleteHargaJual(data) {
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/harga-jual/hapus",
                data: data,
                beforeSend: function () {
                    $(".btn_DelHargaJual")
                        .prop("disabled", true)
                        .addClass("btn-progress");
                },
                success: function (result) {
                    notifToast(result.status, result.message);
                    tableHargaJual.ajax.reload();
                },
                error: function (err) {},
                complete: function () {
                    $(".btn_DelHargaJual")
                        .prop("disabled", true)
                        .removeClass("btn-progress");
                },
            });
        }
        $(document).on("click", ".btn_DelHargaJual", function (e) {
            let nama = $(this).data("nama"),
                id = $(this).data("id");
            swal({
                text: "Hapus data harga jual (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeleteHargaJual({
                        id: id,
                    });
                }
            });
        });
    });
    // Delete Operator Mesin End
});
