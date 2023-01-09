$(function() {
    $('#tb_Orcet').DataTable({
        "bSort": true,
        "responsive": true,
        'autoWidth': true,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        },
        ajax: {
            url: window.location.origin + "/penerbitan/order-cetak",
            data: {
                "request_": "table-orcet"
            }
        },
        columns: [{
                data: 'no_order',
                name: 'no_order',
                title: 'Kode Order'
            },
            {
                data: 'tipe_order',
                name: 'tipe_order',
                title: 'Tipe Order'
            },
            {
                data: 'pilihan_terbit',
                name: 'pilihan_terbit',
                title: 'Pilihan Terbit'
            },
            {
                data: 'status_cetak',
                name: 'status_cetak',
                title: 'Status Cetak'
            },
            {
                data: 'judul_buku',
                name: 'judul_buku',
                title: 'Judul Buku'
            },
            {
                data: 'isbn',
                name: 'isbn',
                title: 'ISBN'
            },
            {
                data: 'urgent',
                name: 'urgent',
                title: 'Urgent'
            },
            {
                data: 'status_penyetujuan',
                name: 'status_penyetujuan',
                title: 'Penyetujuan'
            },
            {
                data: 'action',
                name: 'action',
                title: 'Action',
                searchable: false,
                orderable: false
            },
        ]
    });
})
