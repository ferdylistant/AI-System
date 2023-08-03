$(function () {
    let tabelPenerimaan = $('#tb_stokGudangAndi').DataTable({
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
            url: window.location.origin + "/penjualan-stok/gudang/stok-buku/andi?request_type=table-index",
        },
        columns: [
            // { data: 'no', name: 'no', title: 'No' },
            { data: 'kode_sku', name: 'kode_sku', title: 'Kode SKU' },
            { data: 'kode', name: 'kode', title: 'Kode Naskah' },
            { data: 'judul_final', name: 'judul_final', title: 'Judul Buku' },
            { data: 'sub_judul_final', name: 'sub_judul_final', title: 'Sub-Judul' },
            { data: 'kelompok_buku', name: 'kelompok_buku', title: 'Kategori' },
            { data: 'penulis', name: 'penulis', title: 'Penulis' },
            { data: 'imprint', name: 'imprint', title: 'Imprint' },
            { data: 'total_stok', name: 'total_stok', title: 'Total Stok' },
            { data: 'rack', name: 'rack', title: 'Rak' },
            { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false },
        ]
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        // console.log(helpPage);
        // console.log(message);
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
    $('#modalRack').on('shown.bs.modal', function (e) {
        let stok_id = $(e.relatedTarget).data('stok_id'),
            judul = $(e.relatedTarget).data('judul')
            cardWrap = $("#modalRack");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=show-modal-rack',
            type: 'GET',
            data: {
                stok_id: stok_id
            },
            cache: false,
            success: function (result) {
                console.log(result.contentForm);
                cardWrap.find('#sectionTitle').text(judul.charAt(0).toUpperCase() + judul.slice(1)).trigger('change');
                cardWrap.find('#contentRack').html(result.contentRack).trigger('change');
                cardWrap.find('#contentForm').html(result.contentForm).trigger('change');
                $('[data-toggle="popover"]').popover();
                $('.datepicker').datepicker({
                    format: 'dd MM yyyy',
                    autoclose: true,
                    clearBtn: true,
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
    $("#modalRack").on("hidden.bs.modal", function (e) {
        // let d = $(e.relatedTarget).attr('id','statusJob');
        // console.log(d);
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    function ajaxTerimaBuku(data) {
        let cardWrap = $("#modalRack");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/penerimaan-buku/andi?request_type=terima-buku',
            type: 'POST',
            data: data,
            cache: false,
            success: function (result) {
                console.log(result);
                cardWrap.find('#indexTglTerima'+data.id).html(result.data.tgl_diterima).change();
                cardWrap.find('#indexDiterimaOleh'+data.id).html(result.data.penerima).change();
                cardWrap.find('#indexAction'+data.id).html(result.data.action).change();
                cardWrap.find('#totDiterima').html(result.data.total_diterima+' eks').change();
                notifToast(result.status,result.message);
                tabelPenerimaan.ajax.reload();
                swal.close();
            },
            error: function (err) {
                notifToast('error','Terjadi kesalahan!')
            }
        });
    }
    $("#modalRack").on('click', '#btnTerimaBuku', function () {
        var id = $(this).data('id');
        var jml_diterima = $(this).data('jml_diterima');
        var naskah_id = $("#modalRack").find('[name="naskah_id"]').val();
        var produksi_id = $("#modalRack").find('[name="produksi_id"]').val();
        var proses_tahap = $("#modalRack").find('[name="proses_tahap"]').val();
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
        let cardWrap = $("#modalRack");
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
    $("#modalRack").on('click', '#btnSubmitSelesai', function (e) {
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
    $(document).ready(function () {
        var max_fields = 20; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function (e) {
            //on add input button click
            e.preventDefault();
            if (x < max_fields) {
                //max input box allowed
                x++; //text box increment
                $(wrapper).append(
                    `<div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light text-dark" id="">Rak</span>
                    </div>
                    <input type="text" class="form-control">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light text-dark" id="">Jumlah</span>
                    </div>
                    <input type="text" class="form-control">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light text-dark" id="">Masuk Gudang</span>
                    </div>
                    <input type="text" class="form-control datepicker" name="tgl_masuk_stok"
                        placeholder="DD/MM/YYYY">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light text-dark" id="">Oleh</span>
                    </div>
                    <input type="text" class="form-control">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-danger remove_field text-danger"
                            data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                    </div>
                </div>`
                ); //add input box
                $('.datepicker').datepicker({
                    format: 'dd MM yyyy',
                    autoclose: true,
                    clearBtn: true,
                });
            }
        });

        $(wrapper).on("click", ".remove_field", function (e) {
            //user click on remove text
            e.preventDefault();
            $(this).closest(".input-group").remove();
            x--;
        });
    });
    //Untuk mengaktifkan inputan di Sweetalert
    $.fn.modal.Constructor.prototype._enforceFocus = function () { };
});
