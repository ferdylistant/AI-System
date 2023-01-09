$(function() {
    $('#tb_OrderEbook').DataTable({
       responsive: true,
       processing: true,
       serverSide: true,
       language: {
           searchPlaceholder: 'Search...',
           sSearch: '',
           lengthMenu: '_MENU_ items/page',
       },
       ajax: {
           url: window.location.origin + "/penerbitan/order-ebook",
           data: {"request_": "table-order-ebook"}
       },
       columns: [
           { data: 'no_order', name: 'no_order', title: 'Kode Order' },
           { data: 'kode', name: 'kode', title: 'Kode Naskah' },
           { data: 'tipe_order', name: 'tipe_order', title: 'Tipe Order' },
           { data: 'judul_buku', name: 'judul_buku', title: 'Judul Buku'},
           { data: 'eisbn', name: 'eisbn', title: 'E-ISBN'},
           { data: 'tahun_terbit', name: 'tahun_terbit', title: 'Tahun Terbit'},
           { data: 'tgl_upload', name: 'tgl_upload', title: 'Tanggal Upload'},
        //    { data: 'status_penyetujuan', name: 'status_penyetujuan', title: 'Penyetujuan' },
           { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
       ]
   });
});
