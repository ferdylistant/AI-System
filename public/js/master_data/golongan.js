$(document).ready(function () {
    let tableGolongan = $("#tb_Golongan").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/master/golongan",
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
                data: "nama",
                name: "nama",
                title: "Nama Golongan",
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
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        notifToast("error",settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    let addValidate = jqueryValidation_("#fm_addGolongan", {
        nama_golongan: {
            required: true,
        }
    })
    function showContentModal(el,type,id,nama) {
        $.ajax({
            url: window.location.origin + "/master/golongan/ajax/" + type,
            type: "GET",
            cache: false,
            data: {
                id: id,
                nama: nama
            },
            success: function (data) {
                console.log(data);
                $(el).find("#titleModalGolongan").html(data.title);
                $(el).find("#dataContent").html(data.content);
                $(el).find("#footer_Golongan").html(data.footer);
                $(el).removeClass("modal-progress");
            },
            error: function (data) {
                console.log(data);
            },
        })
    }
    $("#md_Golongan").on({
        "shown.bs.modal": function (e) {
            let type = $(e.relatedTarget).data("type"),
                id = $(e.relatedTarget).data("id"),
                nama = $(e.relatedTarget).data("nama"),
                el = $("#md_Golongan");
            showContentModal(el,type,id,nama);
        },
        "hidden.bs.modal": function () {
            $(this).addClass("modal-progress");
        },
        "submit > #fm_addGolongan": function (e) {
            e.preventDefault();
            if ($("#fm_addGolongan").valid()) {
                console.log(e);
                // ajaxAddGolongan($(this));
            }
        }
    })
})
