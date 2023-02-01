$(function () {
    let tableFBuku = $("#tb_Fbuku").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/master/format-buku",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "jenis_format",
                name: "jenis_format",
                title: "Jenis Format Buku",
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

    // History FBuku Start
    $("#tb_Fbuku").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/master/format-buku/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalFormatBuku").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Format Buku "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_FormatBukuHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/format-buku/lihat-history",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            cache: false,
            beforeSend: function () {
                $(".load-more").text("Loading...");
            },
            success: function (response) {
                if (response.length == 0) {
                    notifToast("error", "Tidak ada data lagi");
                }
                $("#dataHistory").append(response);
                // Setting little delay while displaying new content
                // setTimeout(function() {
                //     // appending posts after last post with class="post"
                //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                // }, 2000);
            },
            complete: function (params) {
                $(".load-more").text("Load more").fadeIn("slow");
            },
        });
    });
    // History FBuku End

    // Add FBuku Start
    $(document).ready(function () {
        function ajaxAddFBuku(data) {
            let el = data.get(0);
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/format-buku/tambah",
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
                        tableFBuku.ajax.reload();
                        data.trigger("reset");
                        $("#md_AddFBuku").modal("hide");
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
                    }
                    notifToast("error", "Data format buku gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_addFBuku").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="nama_format_buku"]').val();
                swal({
                    text: "Tambah data format buku (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxAddFBuku($(this));
                    }
                });
            }
        });
    });
    // Add FBuku End

    // Edit FBuku Start
    $("#md_EditFormatBuku").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/format-buku/ubah",
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

    $("#md_EditFormatBuku").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });

    $(document).ready(function () {
        function ajaxEditFBuku(data) {
            let el = data.get(0);

            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/format-buku/ubah",
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
                        notifToast(result.status, result.message);
                        $("#md_EditFormatBuku").modal("hide");
                        tableFBuku.ajax.reload();
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
                        // edittableFBuku.showErrors(err);
                    }
                    notifToast("error", "Data format buku gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_EditFBuku").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="edit_jenis_format"]').val();
                swal({
                    text: "Ubah data FBuku (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxEditFBuku($(this));
                    }
                });
            }
        });
    });

    // Edit FBuku End

    // Delete FBuku Start
    $(document).ready(function () {
        function ajaxDeleteFBuku(data) {
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/format-buku/hapus",
                data: data,
                beforeSend: function () {
                    $(".btn_DelFBuku")
                        .prop("disabled", true)
                        .addClass("btn-progress");
                },
                success: function (result) {
                    notifToast(result.status, result.message);
                    tableFBuku.ajax.reload();
                },
                error: function (err) {},
                complete: function () {
                    $(".btn_DelFBuku")
                        .prop("disabled", true)
                        .removeClass("btn-progress");
                },
            });
        }
        $(document).on("click", ".btn_DelFBuku", function (e) {
            let nama = $(this).data("nama"),
                id = $(this).data("id");
            swal({
                text: "Hapus data format buku (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeleteFBuku({
                        id: id,
                    });
                }
            });
        });
    });
    // Delete FBuku End
});
