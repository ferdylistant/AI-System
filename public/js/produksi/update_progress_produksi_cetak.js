$(function() {
    $('#tb_prosesProduksi').DataTable({
        "responsive": true,
        "autoWidth": false,
        pagingType: "input",
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: "Cari...",
            sSearch: "",
            lengthMenu: "_MENU_ /halaman",
        },
       ajax: {
           url: window.location.origin + "/produksi/proses/cetak",
       },
       columns: [
           // { data: 'no', name: 'no', title: 'No' },
           { data: 'kode_order', name: 'kode_order', title: 'No Order' },
           { data: 'kode', name: 'kode', title: 'Kode Naskah' },
           { data: 'naskah_dari_divisi', name: 'naskah_dari_divisi', title: 'Div' },
           { data: 'judul_final', name: 'judul_final', title: 'Judul Buku' },
           { data: 'status_cetak', name: 'status_cetak', title: 'Status Cetak' },
           { data: 'penulis', name: 'penulis', title: 'Penulis'},
           { data: 'edisi_cetak', name: 'edisi_cetak', title: 'Edisi Cetak'},
           { data: 'jenis_jilid', name: 'jenis_jilid', title: 'Jenis Jilid'},
           { data: 'buku_jadi', name: 'buku_jadi', title: 'Buku Jadi'},
           { data: 'tracking', name: 'tracking', title: 'Tracking' },
           { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
       ]
    });
    $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
        notifToast("error",message)
    };
})
