
$(function () {
    let tableSubKelompokBuku = $("#tb_SubKelompokBuku").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/master/sub-kelompok-buku",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "kode_sub",
                name: "kode_sub",
                title: "Kode",
            },
            {
                data: "nama",
                name: "nama",
                title: "Nama",
            },
            {
                data: "kelompok_id",
                name: "kelompok_id",
                title: "Kelompok Buku",
            },
            {
                data: "created_at",
                name: "created_at",
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
        notifToast("error",settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    // History KelompokBuku Start
    $("#tb_SubKelompokBuku").on("click", ".btn-history", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("nama");
        $.post(
            window.location.origin + "/master/sub-kelompok-buku/lihat-history",
            {
                id: id,
            },
            function (data) {
                $("#titleModalSubKelompokBuku").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Sub-Kelompok Buku "' +
                    judul +
                    '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistory").html(data);
                $("#md_SubKelompokBukuHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_SubKelompokBukuHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: window.location.origin + "/master/sub-kelompok-buku/lihat-history",
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
                    $(".load-more").attr("disabled", true).css('cursor','not-allowed');
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistory").append(response);
                    $('.thin').animate({ scrollTop: $('.thin').prop("scrollHeight") }, 800);
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
    $("#md_SubKelompokBukuHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
    });
    // History Kelompok Buku End
    $(".select-kb")
        .select2({
            placeholder: "Pilih kelompok buku",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
    $("#add_Kb")
    .select2({
        placeholder: "Pilih kelompok buku",
        ajax: {
            url: window.location.origin + "/master/sub-kelompok-buku/ajax-select",
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
    $("#edit_Kb")
    .select2({
        placeholder: "Pilih kelompok buku",
        ajax: {
            url: window.location.origin + "/master/sub-kelompok-buku/ajax-select",
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
    let addValidate = jqueryValidation_("#fm_addSKBuku", {
        nama_kelompok_buku: {
            required: true,
        },
        nama_sub_kelompok_buku: {
            required: true,
        },
    });
    // Add Kelompok Buku Start
    function ajaxAddSubKelompokBuku(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/sub-kelompok-buku/tambah",
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
                    tableSubKelompokBuku.ajax.reload();
                    data.trigger("reset");
                    $("#md_AddSubKelompokBuku").modal("hide");
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
                notifToast(
                    "error",
                    "Data Sub-kelompok-buku gagal disimpan!"
                );
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fm_addSKBuku").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="nama_sub_kelompok_buku"]').val();
            swal({
                text: "Tambah data sub-kelompok buku (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxAddSubKelompokBuku($(this));
                }
            });
        }
    });
    // Add KelompokBuku End

    // Edit KelompokBuku Start
    $("#md_EditSubKelompokBuku").on("shown.bs.modal", function (e) {
        let id = $(e.relatedTarget).data("id"),
            form_ = $(this);
        $.ajax({
            url: window.location.origin + "/master/sub-kelompok-buku/ubah",
            data: {
                id: id,
            },
            success: function (result) {
                $("#edit_Kb").select2("trigger", "select", {
                    data: {
                        id: result.kelompok_id,
                        text: result.kelompok_nama
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

    $("#md_EditSubKelompokBuku").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    let editValidate = jqueryValidation_("#fm_EditSubKelompokBuku", {
        edit_kelompok_id: {
            required: true,
        },
        edit_nama: {
            required: true,
        },
    });
    function ajaxEditSubKelompokBuku(data) {
        let el = data.get(0);

        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/sub-kelompok-buku/ubah",
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
                    $("#md_EditSubKelompokBuku").modal("hide");
                    tableSubKelompokBuku.ajax.reload();
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
                notifToast("error", "Data Sub-kelompok buku gagal disimpan!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fm_EditSubKelompokBuku").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let nama = $(this).find('[name="edit_nama"]').val();
            swal({
                text: "Ubah data Sub-Kelompok Buku (" + nama + ")?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxEditSubKelompokBuku($(this));
                }
            });
        }
    });
    // Edit KelompokBuku End

    // Delete KelompokBuku Start
    function ajaxDeleteSubKelompokBuku(data) {
        $.ajax({
            type: "POST",
            url: window.location.origin + "/master/sub-kelompok-buku/hapus",
            data: data,
            beforeSend: function () {
                $(".btn_DelSubKelompokBuku")
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                notifToast(result.status, result.message);
                tableSubKelompokBuku.ajax.reload();
            },
            error: function (err) { },
            complete: function () {
                $(".btn_DelSubKelompokBuku")
                    .prop("disabled", true)
                    .removeClass("btn-progress");
            },
        });
    }
    $(document).on("click", ".btn_DelSubKelompokBuku", function (e) {
        let nama = $(this).data("nama"),
            id = $(this).data("id");
        swal({
            text: "Hapus data sub kelompok buku (" + nama + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteSubKelompokBuku({
                    id: id,
                });
            }
        });
    });
    // Delete KelompokBuku End
});
