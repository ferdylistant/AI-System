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
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
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
                jqueryValidation_("#formTambahRekondisi", {
                    naskah_rekondisi: {
                        required: true,
                    },
                    jml_rekondisi: {
                        required: true,
                        number: true
                    },
                });

            },
            error: (err) => {

            },
            complete:()=> {
                $(this).removeClass('modal-progress')
            }
        })
    })
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
                    ajaxAddTrackData($(this));
                }
            });
        }
    })
});
