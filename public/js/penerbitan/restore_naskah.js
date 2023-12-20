
var baseUrl = window.location.origin + "/penerbitan/naskah";
$(document).ready(function () {
    let tableRestoreNaskah = $('#tb_RestoreNaskah').DataTable({
        // "bSort": false,
        "responsive": true,
        "autoWidth": false,
        pagingType: 'input',
        "aaSorting": [],
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: 'Cari...',
            sSearch: '',
            lengthMenu: "_MENU_ /halaman",
        },
        ajax: baseUrl + "/restore",
        columns: [
            {
                data: 'kode',
                name: 'kode',
                title: 'Kode'
            },
            {
                data: 'judul_asli',
                name: 'judul_asli',
                title: 'Judul Asli'
            },
            {
                data: 'pic_prodev',
                name: 'pic_prodev',
                title: 'PIC Prodev'
            },
            {
                data: 'jalur_buku',
                name: 'jalur_buku',
                title: 'Jalur Buku'
            },
            {
                data: 'tanggal_masuk_naskah',
                name: 'tanggal_masuk_naskah',
                title: 'Masuk Naskah'
            },
            {
                data: 'deleted_at',
                name: 'deleted_at',
                title: 'Tgl Dihapus'
            },
            {
                data: 'deleted_by',
                name: 'deleted_by',
                title: 'Dihapus Oleh'
            },
            {
                data: 'action',
                name: 'action',
                title: 'Restore',
                searchable: false,
                orderable: false
            },
        ],
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        console.log(message);
        notifToast("error", message)
    };
    function ajaxRestoreNaskah(data) {
        let cardWrap = $('#tb_RestoreNaskah').closest('.card');
        $.ajax({
            type: "POST",
            url: baseUrl + "/restore",
            data: data,
            beforeSend: function () {
                cardWrap.addClass("card-progress");
            },
            success: function (result) {
                if (result.status == "success") {
                    tableRestoreNaskah.ajax.reload();
                }
                notifToast(result.status, result.message);
            },
            error: function (err) {
                console.log(err);
                cardWrap.removeClass("card-progress");
                notifToast('error', 'Terjadi kesalahan!')
            },
            complete: function () {
                cardWrap.removeClass("card-progress");
            },
        });
    }
    $('#tb_RestoreNaskah').on("click", ".btn-restore-naskah", function (e) {
        let judul = $(this).data("judul"),
            id = $(this).data("id"),
            kode = $(this).data("kode");
        swal({
            text: "Kembalikan data naskah (" + kode + '-' + judul + ")?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxRestoreNaskah({
                    id: id
                })
            }
        });
    });
});


