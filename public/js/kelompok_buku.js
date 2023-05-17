$(function () {
    let tableKelompokBuku = $("#tb_KelompokBuku").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/master/kelompok-buku",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "nama_kelompok_buku",
                name: "nama_kelompok_buku",
                title: "Nama Kelompok Buku",
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

    // History KelompokBuku Start
    $("#tb_KelompokBuku").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/master/kelompok-buku/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalKelompokBuku").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Kelompok Buku "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_KelompokBukuHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_KelompokBukuHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/kelompok-buku/lihat-history",
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
    $("#md_KelompokBukuHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false);
    });
    // History Kelompok Buku End

    // Add Kelompok Buku Start
    $(document).ready(function () {
        function ajaxAddKelompokBuku(data) {
            let el = data.get(0);
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/kelompok-buku/tambah",
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
                        tableKelompokBuku.ajax.reload();
                        data.trigger("reset");
                        $("#md_AddKelompokBuku").modal("hide");
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
                        "Data kelompok-buku digital gagal disimpan!"
                    );
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_addKBuku").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="nama_kelompok_buku"]').val();
                swal({
                    text: "Tambah data kelompok-buku (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxAddKelompokBuku($(this));
                    }
                });
            }
        });
    });
    // Add KelompokBuku End

    // Edit KelompokBuku Start
    $("#md_EditKelompokBuku").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/kelompok-buku/ubah",
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

    $("#md_EditKelompokBuku").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });

    $(document).ready(function () {
        function ajaxEditKelompokBuku(data) {
            let el = data.get(0);

            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/kelompok-buku/ubah",
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
                        $("#md_EditKelompokBuku").modal("hide");
                        tableKelompokBuku.ajax.reload();
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
                        // edittableKelompokBuku.showErrors(err);
                    }
                    notifToast("error", "Data kelompok-buku gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_EditKelompokBuku").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="edit_nama"]').val();
                swal({
                    text: "Ubah data Kelompok Buku (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxEditKelompokBuku($(this));
                    }
                });
            }
        });
    });
    // Edit KelompokBuku End

    // Delete KelompokBuku Start
    $(document).ready(function () {
        function ajaxDeleteKelompokBuku(data) {
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/kelompok-buku/hapus",
                data: data,
                beforeSend: function () {
                    $(".btn_DelKelompokBuku")
                        .prop("disabled", true)
                        .addClass("btn-progress");
                },
                success: function (result) {
                    notifToast(result.status, result.message);
                    tableKelompokBuku.ajax.reload();
                },
                error: function (err) {},
                complete: function () {
                    $(".btn_DelKelompokBuku")
                        .prop("disabled", true)
                        .removeClass("btn-progress");
                },
            });
        }
        $(document).on("click", ".btn_DelKelompokBuku", function (e) {
            let nama = $(this).data("nama"),
                id = $(this).data("id");
            swal({
                text: "Hapus data kelompok buku (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeleteKelompokBuku({
                        id: id,
                    });
                }
            });
        });
    });
    // Delete KelompokBuku End
});
