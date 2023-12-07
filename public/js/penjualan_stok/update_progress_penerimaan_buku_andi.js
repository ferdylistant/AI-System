
$(function () {
    let tabelPenerimaan = $('#tb_penerimaanBuku').DataTable({
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
            url: window.location.origin + "/penjualan-stok/gudang/penerimaan-buku/andi?request_type=table-index",
        },
        columns: [
            // { data: 'no', name: 'no', title: 'No' },
            { data: 'kode_order', name: 'kode_order', title: 'No Order' },
            { data: 'kode', name: 'kode', title: 'Kode Naskah' },
            { data: 'naskah_dari_divisi', name: 'naskah_dari_divisi', title: 'Div' },
            { data: 'judul_final', name: 'judul_final', title: 'Judul Buku' },
            { data: 'kelompok_buku', name: 'kelompok_buku', title: 'Kategori' },
            { data: 'status_cetak', name: 'status_cetak', title: 'Status Cetak' },
            { data: 'penulis', name: 'penulis', title: 'Penulis' },
            { data: 'edisi_cetak', name: 'edisi_cetak', title: 'Edisi Cetak' },
            { data: 'imprint', name: 'imprint', title: 'Imprint' },
            { data: 'cek_pengiriman', name: 'cek_pengiriman', title: 'Cek Pengiriman' },
            { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false },
        ]
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        notifToast("error", settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    $("#tb_penerimaanBuku").on("click", ".btn-tracker", function (e) {
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
    $('#modalPenerimaanBuku').on('shown.bs.modal', function (e) {
        let track_id = $(e.relatedTarget).data('track_id'),
            judul_final = $(e.relatedTarget).data('judulfinal'),
            status = $(e.relatedTarget).data('status'),
            statuscolor = $(e.relatedTarget).data('statuscolor'),
            naskah_id = $(e.relatedTarget).data('naskah_id'),
            produksi_id = $(e.relatedTarget).data('produksi_id'),
            proses_tahap = $(e.relatedTarget).data('proses_tahap'),
            jml_cetak = $(e.relatedTarget).data('jml_cetak'),
            cardWrap = $("#modalPenerimaanBuku");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/penerimaan-buku/andi?request_type=show-modal-pengiriman',
            type: 'GET',
            data: {
                track_id: track_id,
                status: status,
                jml_cetak: jml_cetak
            },
            cache: false,
            success: function (result) {
                cardWrap.find('#statusJob').addClass('badge-' + statuscolor).trigger('change');
                cardWrap.find('#statusJob').text(status).trigger('change');
                if (status !== 'selesai') {
                    cardWrap.find('#btnSelesaiPengiriman').html(`<button type="button" id="btnSubmitSelesai" data-id="`+track_id+`" class="btn btn-sm btn-success btn-block" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;"><i class="fas fa-check-circle"></i> Tandai Selesai</button>`);
                } else {
                    cardWrap.find('#btnSelesaiPengiriman').remove();
                }
                cardWrap.find('[name="naskah_id"]').val(naskah_id).trigger('change');
                cardWrap.find('[name="produksi_id"]').val(produksi_id).trigger('change');
                cardWrap.find('[name="proses_tahap"]').val(proses_tahap).trigger('change');
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
    $("#modalPenerimaanBuku").on("hidden.bs.modal", function (e) {
        // let d = $(e.relatedTarget).attr('id','statusJob');
        // console.log(d);
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    function ajaxTerimaBuku(data) {
        let cardWrap = $("#modalPenerimaanBuku");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/penerimaan-buku/andi?request_type=terima-buku',
            type: 'POST',
            data: data,
            cache: false,
            success: function (result) {
                notifToast(result.status,result.message);
                if (result.status === 'success') {
                    cardWrap.find('#indexTglTerima'+data.id).html(result.data.tgl_diterima).change();
                    cardWrap.find('#indexDiterimaOleh'+data.id).html(result.data.penerima).change();
                    cardWrap.find('#indexAction'+data.id).html(result.data.action).change();
                    cardWrap.find('#totDiterima').html(result.data.total_diterima+' eks').change();
                    cardWrap.find('#totKekurangan').html(result.data.total_kekurangan+' eks').change();
                }
                tabelPenerimaan.ajax.reload();
                swal.close();
            },
            error: function (err) {
                notifToast('error','Terjadi kesalahan!')
            }
        });
    }
    $("#modalPenerimaanBuku").on('click', '#btnTerimaBuku', function () {
        var id = $(this).data('id');
        var jml_cetak = $(this).data('jml_cetak');
        var jml_diterima = $(this).data('jml_diterima');
        var naskah_id = $("#modalPenerimaanBuku").find('[name="naskah_id"]').val();
        var produksi_id = $("#modalPenerimaanBuku").find('[name="produksi_id"]').val();
        var proses_tahap = $("#modalPenerimaanBuku").find('[name="proses_tahap"]').val();
        // var id = $(this).data('id');
        var textarea = document.createElement('textarea');
        textarea.rows = 3;
        textarea.className = 'swal-content__textarea';
        // Set swal return value every time an onkeyup event is fired in this textarea
        textarea.onkeyup = function () {
            swal.setActionValue({
                confirm: this.value
            });
        };
        swal({
            title: 'Apakah jumlah kirim sudah benar?',
            text: 'Catatan (Opsional)',
            content: textarea,
            icon: "warning",
            dangerMode: true,
            buttons: {
                cancel: {
                    text: 'Cancel',
                    visible: true
                },
                confirm: {
                    text: 'Submit',
                    closeModal: false
                }
            }
        }).then(function (value) {
            if (value !== null) {
                var data = {
                    id: id,
                    jml_cetak: jml_cetak,
                    jml_diterima: jml_diterima,
                    catatan: value === true ? null : value,
                    naskah_id: naskah_id,
                    produksi_id: produksi_id,
                    proses_tahap: proses_tahap
                };
                ajaxTerimaBuku(data)
            }
        });
    });
    function ajaxSelesaiPenerimaan(id) {
        let cardWrap = $("#modalPenerimaanBuku");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/penerimaan-buku/andi?request_type=selesai-penerimaan',
            type: 'POST',
            data: {id:id},
            cache: false,
            beforeSend: function () {
                cardWrap.addClass('modal-progress')
            },
            success: function (res) {
                console.log(res);
                notifToast(res.status,res.message);
                if (res.status == 'success') {
                    tabelPenerimaan.ajax.reload();
                    cardWrap.modal('hide');
                } else {
                    cardWrap.removeClass('modal-progress');
                }
            },
            error: function (err) {
                console.log(err);
                cardWrap.removeClass('modal-progress');
            },
            complete: function () {
                cardWrap.removeClass('modal-progress');
            }
        });
    }
    $("#modalPenerimaanBuku").on('click', '#btnSubmitSelesai', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        swal({
            title: 'Apakah semua pengiriman sudah diterima?',
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxSelesaiPenerimaan(id);
            }
        });
    });
    //Untuk mengaktifkan inputan di Sweetalert
    $.fn.modal.Constructor.prototype._enforceFocus = function () { };
});
