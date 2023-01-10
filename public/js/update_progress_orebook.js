$(function() {
    let tableOrderEbook = $('#tb_OrderEbook').DataTable({
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
       },
       columns: [
           { data: 'no_order', name: 'no_order', title: 'Kode Order' },
           { data: 'kode', name: 'kode', title: 'Kode Naskah' },
           { data: 'tipe_order', name: 'tipe_order', title: 'Tipe Order' },
           { data: 'judul_final', name: 'judul_final', title: 'Judul Buku'},
           { data: 'jalur_buku', name: 'jalur_buku', title: 'Jalur Buku'},
           { data: 'penulis', name: 'penulis', title: 'Penulis'},
           { data: 'status_penyetujuan', name: 'status_penyetujuan', title: 'Penyetujuan' },
           { data: 'action', name: 'action', title: 'Action', orderable: false},
       ]
   });
   $('[name="status_filter"]').on('change', function() {
    var val = $.fn.dataTable.util.escapeRegex($(this).val());
    tableOrderEbook.column($(this).data('column'))
        .search(val ? val : '', true, false)
        .draw();
});
});
