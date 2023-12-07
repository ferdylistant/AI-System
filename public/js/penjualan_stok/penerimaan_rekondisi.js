
$(document).ready(function() {
    let tablePenerimaanRekondisi = $('#tb_penerimaanRekondisi').DataTable({
        "responsive": true,
        "autoWidth": false,
        dom: 'Bfrtip',
        buttons: [
            'pageLength',
            'spacer',
            {
                extend: 'collection',
                text: 'Exports',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="text-secondary fa fa-print"></i> Print',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="text-danger fa fa-file-pdf"></i> PDF',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="text-success fa fa-file-excel"></i> Excel',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }
                    },
                ]
            },
        ],
        pagingType: "input",
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: "Cari...",
            sSearch: "",
            lengthMenu: "_MENU_ /halaman",
        },
        ajax: {
            url: window.location.origin + "/penjualan-stok/gudang/penerimaan-rekondisi",
            complete: () => {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-class'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl,{
                        trigger : 'hover'
                    })
                })
            }
        },
        columns: [
            { data: 'kode_rekondisi', name: 'kode_rekondisi', title: 'Kode Rekondisi' },
            { data: 'kode_naskah', name: 'kode_naskah', title: 'Kode Naskah' },
            { data: 'judul_final', name: 'judul_final', title: 'Judul Final' },
            { data: 'penulis', name: 'penulis', title: 'Penulis' },
            { data: 'created_at', name: 'created_at', title: 'Dibuat' },
            { data: 'cek_pengiriman', name: 'cek_pengiriman', title: 'Cek Pengiriman'},
            { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false },
        ]
    });
    new $.fn.dataTable.Buttons(tablePenerimaanRekondisi, {
        buttons: [
            'print', 'excel', 'pdf'
        ]
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        // console.log(message);
        notifToast("error",settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    $('#modalPenerimaanRekondisi').on('shown.bs.modal', function(e) {
        let id = $(e.relatedTarget).data('id'),
            produksi_id = $(e.relatedTarget).data('produksi_id'),
            status = $(e.relatedTarget).data('status'),
            judul = $(e.relatedTarget).data('judul'),
            cardWrap = $("#modalPenerimaanRekondisi");
            cardWrap.find('#modalPenerimaanTitle').html('<i class="fas fa-truck-loading"></i> ' + judul.charAt(0).toUpperCase() + judul.slice(1)).trigger('change');

            $.ajax({
                url: window.location.origin + '/penjualan-stok/gudang/penerimaan-rekondisi/'+id,
                type: 'GET',
            data: {
                status: status,
            },
            cache: false,
            success: function (result) {
                cardWrap.find('#statusJob').attr('class', result.badge).trigger('change');
                cardWrap.find('#statusJob').text(result.status).trigger('change');
                cardWrap.find('[name="id"]').val(id).trigger('change');
                cardWrap.find('[name="produksi_id"]').val(produksi_id).trigger('change');
                cardWrap.find('#contentData').html(result.content).trigger('change');
                cardWrap.find('#footerModal').html(result.footer).trigger('change');
                $('[data-toggle="popover"]').popover();
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-class'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl,{
                        trigger : 'hover'
                    })
                });

            },
            error: function (err) {
                console.log(err);
                notifToast('error', err.statusText);
                cardWrap.removeClass('modal-progress')
            },
            complete: function () {
                cardWrap.removeClass('modal-progress')
            }
            });
    });
});
