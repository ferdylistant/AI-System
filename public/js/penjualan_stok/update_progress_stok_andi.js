$(function(){
    let tabelGudang = $('#tb_stokGudangAndi').DataTable({
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
            url: window.location.origin + "/penjualan-stok/gudang/andi?request_type=table-index",
        },
        columns: [
            // { data: 'no', name: 'no', title: 'No' },
            { data: 'kode_order', name: 'kode_order', title: 'No Order' },
            { data: 'kode', name: 'kode', title: 'Kode Naskah' },
            { data: 'naskah_dari_divisi', name: 'naskah_dari_divisi', title: 'Div' },
            { data: 'judul_final', name: 'judul_final', title: 'Judul Buku' },
            { data: 'status_cetak', name: 'status_cetak', title: 'Status Cetak' },
            { data: 'penulis', name: 'penulis', title: 'Penulis' },
            { data: 'edisi_cetak', name: 'edisi_cetak', title: 'Edisi Cetak' },
            { data: 'imprint', name: 'imprint', title: 'Imprint' },
            { data: 'cek_pengiriman', name: 'cek_pengiriman', title: 'Cek Pengiriman' },
            { data: 'tracking_timeline', name: 'tracking_timeline', title: 'Tracking Timeline' },
            { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false },
        ]
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        notifToast("error",settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    $("#tb_stokGudangAndi").on("click", ".btn-tracker", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var judul = $(this).data("judulfinal");
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: window.location.origin +
                "/penjualan-stok/gudang?request_type=show-track-timeline",
            type: "GET",
            data: { id: id },
            cache: false,
            beforeSend: function () {
                cardWrap.addClass("card-progress");
            },
            success: function (data) {
                $("#titleModalTracker").html('<i class="fas fa-file-signature"></i>&nbsp;Tracking Progress Naskah "' + judul + '"');
                $("#dataShowTracking").html(data);
                $("#md_Tracker").modal("show");
            },
            complete: function () {
                cardWrap.removeClass("card-progress");
            }
        });
    });
    $('#modalRiwayatPengirimanGudang').on('shown.bs.modal', function (e) {
        let track_id = $(e.relatedTarget).data('track_id'),
            judul_final = $(e.relatedTarget).data('judulfinal'),
            status = $(e.relatedTarget).data('status'),
            statuscolor = $(e.relatedTarget).data('statuscolor'),
            cardWrap = $("#modalRiwayatPengirimanGudang");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/andi?request_type=show-modal-pengiriman',
            type: 'GET',
            data: {
                track_id: track_id,
            },
            cache: false,
            success: function (result) {
                cardWrap.find('#statusJob').addClass('badge-'+statuscolor).trigger('change');
                cardWrap.find('#statusJob').text(status).trigger('change');
                // cardWrap.find('[name="produksi_id"]').val(result.data.id).trigger('change');
                // cardWrap.find('[name="proses_tahap"]').val(result.proses_tahap).trigger('change');
                cardWrap.find('#judul_final').html('<i class="fas fa-tasks"></i> ' + judul_final.charAt(0).toUpperCase() + judul_final.slice(1)).trigger('change');
                cardWrap.find('#contentData').html(result.content).trigger('change');
                $('[data-toggle="popover"]').popover();
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
    $("#modalRiwayatPengirimanGudang").on("hidden.bs.modal", function (e) {
        // let d = $(e.relatedTarget).attr('id','statusJob');
        // console.log(d);
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
});
