$(function () {
    let tablePlatform = $("#tb_Platform").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/master/platform-digital",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "nama_platform",
                name: "nama_platform",
                title: "Nama Platform",
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

    // History Platform Start
    $("#tb_Platform").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/master/platform-digital/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalPlatform").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Platform Ebook "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_PlatformHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        $(this).data("paginate", page + 1);

        $.ajax({
            url:
                window.location.origin +
                "/master/platform-digital/lihat-history",
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
    // History Platform End

    // Add Platform Start
    $(document).ready(function () {
        function ajaxAddPlatform(data) {
            let el = data.get(0);
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/platform-digital/tambah",
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
                        tablePlatform.ajax.reload();
                        data.trigger("reset");
                        $("#md_AddPlatformEbook").modal("hide");
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
                    notifToast(
                        "error",
                        "Data platform digital gagal disimpan!"
                    );
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_addPDigital").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="nama_platform"]').val();
                swal({
                    text: "Tambah data platform (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxAddPlatform($(this));
                    }
                });
            }
        });
    });
    // Add Platform End

    // Edit Platform Start
    $("#md_EditPlatformEbook").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/platform-digital/ubah",
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

    $("#md_EditPlatformEbook").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });

    $(document).ready(function () {
        function ajaxEditPlatform(data) {
            let el = data.get(0);

            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/platform-digital/ubah",
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
                        $("#md_EditPlatformEbook").modal("hide");
                        tablePlatform.ajax.reload();
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
                        // edittablePlatform.showErrors(err);
                    }
                    notifToast("error", "Data platform gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_EditPlatform").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="edit_nama"]').val();
                swal({
                    text: "Ubah data Platform (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxEditPlatform($(this));
                    }
                });
            }
        });
    });

    // Edit Platform End

    // Delete Platform Start
    $(document).ready(function () {
        function ajaxDeletePlatform(data) {
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/platform-digital/hapus",
                data: data,
                beforeSend: function () {
                    $(".btn_DelPlatform")
                        .prop("disabled", true)
                        .addClass("btn-progress");
                },
                success: function (result) {
                    if (result.status == "success") {
                        tablePlatform.ajax.reload();
                        notifToast(result.status, result.message);
                    }
                },
                error: function (err) {},
                complete: function () {
                    $(".btn_DelPlatform")
                        .prop("disabled", true)
                        .removeClass("btn-progress");
                },
            });
        }
        $(document).on("click", ".btn_DelPlatform", function (e) {
            let nama = $(this).data("nama"),
                id = $(this).data("id");
            swal({
                text: "Hapus data platform (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeletePlatform({
                        id: id,
                    });
                }
            });
        });
    });
    // Delete Platform End

    // Restore Platform Start
    $(document).on("click", "#restore-platform", function (e) {
        e.preventDefault();
        var getLink = $(this).attr("href");
        swal({
            title: "Apakah anda yakin?",
            text: "Data akan dikembalikan",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                window.location.href = getLink;
            }
        });
    });
    // Restore Platform End
});
