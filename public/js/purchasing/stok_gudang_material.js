$(function () {
    let tableStok = $("#tb_Stok").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        // drawCallback: () => {
        //     var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-category'))
        //         var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        //             return new bootstrap.Tooltip(tooltipTriggerEl, {
        //                 trigger: 'hover'
        //             })
        //         })
        // },
        ajax: {
            url: window.location.origin + "/purchasing/stok-gudang-material",
        },
        columns: [
            {
                data: "no",
                name: "no",
                title: "No",
            },
            {
                data: "kode_stok",
                name: "kode_stok",
                title: "Kode Stok",
            },
            {
                data: "nama_stok",
                name: "nama_stok",
                title: "Nama Stok",
            },
            {
                data: "satuan",
                name: "satuan",
                title: "Unit Stok",
            },
            {
                data: "jumlah_stok",
                name: "jumlah_stok",
                title: "Jumlah Stok",
            },
            {
                data: "tanggal",
                name: "tanggal",
                title: "Tanggal",
            },
        ],
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        notifToast("error", settings.jqXHR.statusText);
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
});
