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
                        text: '<i class="text-secondary bi bi-printer"></i> Print',
                        exportOptions: {
                            columns: [0,1,2,4,5,6,7,8,9]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="text-danger bi bi-filetype-pdf"></i> PDF',
                        exportOptions: {
                            columns: [0,1,2,4,5,6,7,8,9]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="text-success bi bi-filetype-xlsx"></i> Excel',
                        exportOptions: {
                            columns: [0,1,2,4,5,6,7,8,9]
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
        // console.log(message);
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
                cardWrap.find('#sectionTitle').html(result.sectionTitle).trigger('change');
                cardWrap.find('[name="produksi_id"]').val(result.data.id).trigger('change');
                cardWrap.find('[name="proses_tahap"]').val(result.proses_tahap).trigger('change');cardWrap.find('#contentData').html(result.content).trigger('change');
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
    })
});
