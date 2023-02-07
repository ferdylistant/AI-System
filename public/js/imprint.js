$(function () {
    let tableImprint = $("#tb_Imprint").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/master/imprint",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "nama_imprint",
                name: "nama_imprint",
                title: "Nama Imprint",
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

    // History Imprint Start
    $("#tb_Imprint").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/master/imprint/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalImprint").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Imprint "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_ImprintHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/imprint/lihat-history",
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
    // History Imprint End

    // Add Imprint Start
    $(document).ready(function () {
        function ajaxAddImprint(data) {
            let el = data.get(0);
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/imprint/tambah",
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
                        tableImprint.ajax.reload();
                        data.trigger("reset");
                        $("#md_AddImprint").modal("hide");
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
                    notifToast("error", "Data imprint gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_addImprint").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="nama_imprint"]').val();
                swal({
                    text: "Tambah data imprint (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxAddImprint($(this));
                    }
                });
            }
        });
    });
    // Add Imprint End

    // Edit Imprint Start
    $("#md_EditImprint").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/imprint/ubah",
            data: {
                id: id,
            },
            success: function (result) {
                console.log(result);
                Object.entries(result).forEach((entry) => {
                    let [key, value] = entry;
                    form_.find('[name="edit_' + key + '"]').val(value);
                });
                form_.removeClass("modal-progress");
            },
        });
    });

    $("#md_EditImprint").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });

    $(document).ready(function () {
        function ajaxEditImprint(data) {
            let el = data.get(0);

            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/imprint/ubah",
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
                        $("#md_EditImprint").modal("hide");
                        tableImprint.ajax.reload();
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
                        // edittableImprint.showErrors(err);
                    }
                    notifToast("error", "Data imprint gagal disimpan!");
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }

        $("#fm_EditImprint").on("submit", function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                let nama = $(this).find('[name="edit_nama"]').val();
                swal({
                    text: "Ubah data Imprint (" + nama + ")?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxEditImprint($(this));
                    }
                });
            }
        });
    });
    // Edit Imprint End

    // Delete Imprint Start
    $(document).ready(function () {
        function ajaxDeleteImprint(data) {
            $.ajax({
                type: "POST",
                url: window.location.origin + "/master/imprint/hapus",
                data: data,
                beforeSend: function () {
                    $(".btn_DelImprint")
                        .prop("disabled", true)
                        .addClass("btn-progress");
                },
                success: function (result) {
                    notifToast(result.status, result.message);
                    tableImprint.ajax.reload();
                },
                error: function (err) {},
                complete: function () {
                    $(".btn_DelImprint")
                        .prop("disabled", true)
                        .removeClass("btn-progress");
                },
            });
        }
        $(document).on("click", ".btn_DelImprint", function (e) {
            let nama = $(this).data("nama"),
                id = $(this).data("id");
            swal({
                text: "Hapus data imprint (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeleteImprint({
                        id: id,
                    });
                }
            });
        });
    });
    // Delete Imprint End
});
