$(function () {
    $("#tb_Orcet").DataTable({
        bSort: true,
        responsive: true,
        autoWidth: true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_ items/page",
        },
        ajax: {
            url: window.location.origin + "/penerbitan/order-cetak",
            data: {
                request_: "table-orcet",
            },
        },
        columns: [
            {
                data: "no_order",
                name: "no_order",
                title: "Kode Order",
            },
            {
                data: "kode",
                name: "kode",
                title: "Kode Naskah",
            },
            {
                data: "tipe_order",
                name: "tipe_order",
                title: "Tipe Order",
            },
            {
                data: "judul_final",
                name: "judul_final",
                title: "Judul Buku",
            },
            {
                data: "jalur_buku",
                name: "jalur_buku",
                title: "Jalur Buku",
            },
            {
                data: "status_penyetujuan",
                name: "status_penyetujuan",
                title: "Penyetujuan",
            },
            {
                data: "history",
                name: "history",
                title: "History",
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
});
