$(document).ready(function () {
    let tableRekondisiProduksi = $('#tb_rekondisiProduksi').DataTable({
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
            url: window.location.origin + "/produksi/rekondisi",
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
            { data: 'kirim_gudang', name: 'kirim_gudang', title: 'Kirim Gudang'},
            { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false },
        ]
    });
    new $.fn.dataTable.Buttons(tableRekondisiProduksi, {
        buttons: [
            'print', 'excel', 'pdf'
        ]
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        console.log(message);
        notifToast("error",settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    $('#md_addRekondisi').on('shown.bs.modal', function(e) {
        $.ajax({
            url: window.location.origin + "/produksi/rekondisi/create",
            type: 'GET',
            cache:false,
            beforeSend: () => {
                $(this).addClass('modal-progress')
            },
            success: (result) => {
                $('#dataModalRekondisi').html(result.content);
                $('#btnModalRekondisi').html(result.btn);
                $('[data-toggle="popover"]').popover();
                $(".select-rekondisi")
                .select2({
                    placeholder: "Pilih",
                })
                .on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                        $('#formOther').show('slow');
                    } else {
                        $('#formOther').hide('slow');
                    }
                });
            },
            error: (err) => {

            },
            complete:()=> {
                $(this).removeClass('modal-progress')
            }
        })
    })
    let formAddRekondisi = jqueryValidation_("#formTambahRekondisi", {
        produksi_id: {
            required: true,
        },
        jml_rekondisi: {
            required: true,
            number: true
        },
    });
    function ajaxAddRekondisi(data) {
        let el = new FormData(data.get(0)),
            cardWrap = $('#md_addRekondisi');

        $.ajax({
            url: window.location.origin + '/produksi/rekondisi',
            type: 'POST',
            data: el,
            processData: false,
            contentType: false,
            beforeSend: function () {
                cardWrap.addClass("modal-progress");
            },
            success: function (res) {
                // console.log(res);
                notifToast(res.status, res.message);
                if (res.status == 'success') {
                    tableRekondisiProduksi.ajax.reload();
                    cardWrap.modal("hide");
                }
            },
            error: function (err) {
                // console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    formAddRekondisi.showErrors(err);
                }
                notifToast("error", "Terjadi kesalahan!");
                cardWrap.removeClass("modal-progress");
            },
            complete: function () {
                cardWrap.removeClass("modal-progress");
            },
        });
    }
    $('#formTambahRekondisi').submit(function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                text: "Apakah data rekondisi sudah benar?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxAddRekondisi($(this));
                }
            });
        }
    })
    $('#modalPengirimanRekondisi').on('shown.bs.modal',function (e) {
        let id = $(e.relatedTarget).data('id'),
            produksi_id = $(e.relatedTarget).data('produksi_id'),
            status = $(e.relatedTarget).data('status'),
            judul = $(e.relatedTarget).data('judul'),
            cardWrap = $("#modalPengirimanRekondisi");
            cardWrap.find('#modalPengirimanTitle').html('<i class="fas fa-truck-loading"></i> ' + judul.charAt(0).toUpperCase() + judul.slice(1)).trigger('change');

            $.ajax({
                url: window.location.origin + '/produksi/rekondisi/'+id,
                type: 'GET',
            data: {
                id: id,
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
                })
                var jmlDikirim = document.getElementById('jmlDikirim');
                var maskjmlDikirim = {
                    mask: '0000000000000'
                };
                var mask = IMask(jmlDikirim, maskjmlDikirim, reverse = true);

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
    let formKirimRekondisi = jqueryValidation_("#form_Tracking", {
        jml_kirim: {
            required: true,
            number: true
        },
    });
    function ajaxKirimRekondisi(data) {
        let el = new FormData(data.get(0)),
        cardWrap = $('#modalPengirimanRekondisi');

        $.ajax({
            url: window.location.origin + "/produksi/rekondisi?request_type=submit-kirim-gudang",
            type: 'POST',
            data: el,
            processData: false,
            contentType: false,
            beforeSend: function () {
                cardWrap.addClass("modal-progress");
            },
            success: function (res) {
                console.log(res);
                notifToast(res.status, res.message);
                if (res.status == 'success') {
                    tableRekondisiProduksi.ajax.reload();
                    $("#modalPengirimanRekondisi").modal("hide");
                }
            },
            error: function (err) {
                // console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    formKirimRekondisi.showErrors(err);
                }
                notifToast("error", "Terjadi kesalahan!");
                cardWrap.removeClass("modal-progress");
            },
            complete: function () {
                cardWrap.removeClass("modal-progress");
            },
        });
    }
    $('#form_Tracking').submit(function (e) {
        e.preventDefault();

        if ($(this).valid()) {
            swal({
                text: "Apakah data pengiriman rekondisi sudah benar?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxKirimRekondisi($(this));
                }
            });
        }
    });
    //Edit Riwayat Kirim
    $('#modalEditRiwayatKirim').on('shown.bs.modal', function (e) {
        let id = $(e.relatedTarget).data('id'),
            tglmulai =  $(e.relatedTarget).data('dibuat'),
            cardWrap = $("#modalEditRiwayatKirim");
        $.ajax({
            url: window.location.origin + "/produksi/rekondisi/"+id+"/edit?request_type=show-modal-edit-riwayat",
            type: 'GET',
            success: function (res) {
                $('#modalEditRiwayatKirim').find('#titleEditRiwayat').text(tglmulai).change();
                $('#modalEditRiwayatKirim').find('[name="id"]').val(id).change();
                $('#modalEditRiwayatKirim').find('[name="track_id"]').val(res.rekondisi_id).change();
                $('#modalEditRiwayatKirim').find('[name="jml_dikirim"]').val(res.jml_kirim).change();
                $('#modalEditRiwayatKirim').find('[name="catatan"]').val(res.catatan).change();
                var jmlDikirim = document.getElementById('editJmlDikirim');
                var maskjmlDikirim = {
                    mask: '0000000000000'
                };
                var mask = IMask(jmlDikirim, maskjmlDikirim, reverse = true);
                cardWrap.removeClass('modal-progress')
            }
        });
    });
    $("#modalEditRiwayatKirim").on("hidden.bs.modal", function (e) {
        // let d = $(e.relatedTarget).attr('id','statusJob');
        // console.log(d);
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
        $(this).modal('hide');
    });
    function ajaxEditRiwayatKirim(data) {
        let el = data.get(0);
        $.ajax({
            url: window.location.origin + "/produksi/rekondisi?request_type=update-kirim-gudang",
            type: 'POST',
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (res) {
                notifToast(res.status, res.message);
                if (res.status == 'success') {
                    // $("#modalTrackProduksi #indexCatatan"+id).html(res.data.catatan).change();
                    $("#modalPengirimanRekondisi #indexJmlKirim"+res.data.id).html(res.data.jml_dikirim+' eks').change();
                    $("#modalPengirimanRekondisi #totDikirim").html(res.data.total_dikirim+' eks').change();
                    $("#modalEditRiwayatKirim").modal("hide");
                }
            },
            error: function (err) {
                // console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    editRiwayat.showErrors(err);
                }
                notifToast("error", "Terjadi kesalahan!");
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }
    $('#form_EditRiwayat').on('submit', function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                text: "Apakah Anda yakin ingin mengubah data?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxEditRiwayatKirim($(this));
                }
            });
        }
    });
    //Delete Riwayat Kirim
    function ajaxDeleteRiwayatKirim(element, id, rekondisi_id) {
        $.ajax({
            url: window.location.origin + "/produksi/rekondisi/"+id,
            type: 'DELETE',
            data: {
                rekondisi_id: rekondisi_id
            },
            cache: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (res) {
                // console.log(res);
                notifToast(res.status, res.message);
                if (res.status == 'success') {
                    if (res.load === true) {
                        location.reload();
                    }
                    var i = 0;
                    //remove post on table
                    element.closest("tr").remove();
                    // $(`#modalTrackProduksi #index_`+id).remove();
                    $('#tableRiwayatKirim').find('tbody tr').each(function (index) {
                        // console.log(index);
                        //change id of first tr
                        $(this).find("td:eq(0)").attr("id", "row_num" + (index + 1))
                        //change hidden input value
                        $(this).find("td:eq(0)").html((index + 1) + '<input type="hidden" name="task_number[]" value=' + (index + 1) + '>')
                    });
                    i--;
                    $("#modalPengirimanRekondisi #totDikirim").html(res.data+' eks').change();
                }
            },
            error: function (err) {
                notifToast("error", "Terjadi kesalahan!");
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }
    $('#form_Tracking').on('click', '#btnDeleteRiwayatKirim', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let rekondisi_id = $(this).data('rekondisi_id');
        swal({
            text: "Apakah Anda yakin ingin menghapus?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteRiwayatKirim($(this), id, rekondisi_id);
            }
        });
    });
});
