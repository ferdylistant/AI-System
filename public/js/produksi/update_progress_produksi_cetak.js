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
           { data: 'tracking', name: 'tracking', title: 'Tracking',width: "100%", },
           { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
       ]
    });
    $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
        notifToast("error",message)
    };
    $('#modalTrackProduksi').on('shown.bs.modal',function (e) {
        let id = $(e.relatedTarget).data('id'),
        type = $(e.relatedTarget).data('type'),
        status = $(e.relatedTarget).data('status'),
        tglmulai = $(e.relatedTarget).data('tglmulai'),
        tglselesai = $(e.relatedTarget).data('tglselesai'),
        cardWrap = $("#modalTrackProduksi");
        $.ajax({
            url: window.location.origin + '/produksi/proses/cetak?modal=true',
            type: 'GET',
            data: {
                id:id,
                type:type,
                status:status,
                tglmulai:tglmulai,
                tglselesai:tglselesai
            },
            cache: false,
            success: function (result) {
                cardWrap.find('#statusJob').text(result.status).addClass(result.badge).trigger('change');
                cardWrap.find('#sectionTitle').html(result.sectionTitle).trigger('change');
                cardWrap.find('[name="judul_final"]').val(result.data.judul_final).trigger('change');
                cardWrap.find('#judul_final').html('<i class="fas fa-tasks"></i> '+result.data.judul_final.charAt(0).toUpperCase() + result.data.judul_final.slice(1)).trigger('change');
                Object.entries(result.data).forEach((entry) => {
                    let [key, value] = entry;
                    });
                cardWrap.find('#contentData').html(result.content).trigger('change');
                cardWrap.find('#footerModal').html(result.footer).trigger('change');
                $("#modalTrackProduksi").modal('show');
                $(".select-mesin")
                .select2({
                    placeholder: "Pilih mesin\xa0\xa0",
                })
                .on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                    }
                });
                $(".select-operator")
                    .select2({
                        placeholder: "Pilih operator\xa0\xa0",
                        multiple: true,
                    })
                    .on("change", function (e) {
                        if (this.value) {
                            $(this).valid();
                        }
                    });

                $(".select-status")
                    .select2({
                        placeholder: "Pilih status\xa0\xa0",
                    })
                    .on("change", function (e) {
                        if (this.value) {
                            $(this).valid();
                        }
                    });
            },
            error: function (err) {
                console.log(err);
            },
            complete: function () {
                cardWrap.removeClass('modal-progress')
            }
        });
    });
    $("#modalTrackProduksi").on("hidden.bs.modal", function () {
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
})
