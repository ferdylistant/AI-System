$(function () {
    $("[name='status_filter']").val("").trigger("change");
    let tableDesFinal = $('#tb_DesFinal').DataTable({
        "bSort": false,
        "responsive": true,
        "autoWidth": true,
        pagingType: 'input',
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: 'Cari...',
            sSearch: '',
            lengthMenu: '_MENU_ /halaman',
        },
        ajax: window.location.origin + "/penerbitan/deskripsi/final"
        ,
        columns: [
            // { data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'No', orderable: false, searchable: false, "width": "5%" },
            { data: 'kode', name: 'kode', title: 'Kode' },
            { data: 'judul_asli', name: 'judul_asli', title: 'Judul Asli' },
            { data: 'penulis', name: 'penulis', title: 'Penulis' },
            { data: 'nama_pena', name: 'nama_pena', title: 'Nama Pena' },
            { data: 'jalur_buku', name: 'jalur_buku', title: 'Jalur Buku' },
            { data: 'imprint', name: 'imprint', title: 'Imprint' },
            { data: 'judul_final', name: 'judul_final', title: 'Judul Final' },
            { data: 'tgl_deskripsi', name: 'tgl_deskripsi', title: 'Tgl Deskripsi' },
            { data: 'pic_prodev', name: 'pic_prodev', title: 'PIC Prodev' },
            { data: 'history', name: 'history', title: 'History Progress' },
            { data: 'action', name: 'action', title: 'Action', orderable: false },
        ],

    });
    loadDataCount();
    $('[name="status_filter"]').on('change', function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableDesFinal.column($(this).data('column'))
            .search(val ? val : '', true, false)
            .draw();
    });
    $('#tb_DesFinal').on('click', '.btn-history', function (e) {
        var id = $(this).data('id');
        var judul = $(this).data('judulfinal');
        $.post(window.location.origin + "/penerbitan/deskripsi/final/lihat-history",
        { id: id }, function (data) {
            $('#titleModalDesfin').html('<i class="fas fa-history"></i>&nbsp;History Perubahan Naskah "' + judul + '"');
            $('#load_more').data('id', id);
            $('#dataHistoryDesfin').html(data);
            $('#md_DesfinHistory').modal('show');
        });
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_DesfinHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url:
                window.location.origin +
                "/penerbitan/deskripsi/final/lihat-history",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            cache: false,
            beforeSend: function () {
                form.addClass("modal-progress");
            },
            success: function (response) {
                if (response.length == 0) {
                    $(".load-more").attr("disabled", true);
                    notifToast("error", "Tidak ada data lagi");
                }
                $("#dataHistoryDesfin").append(response);
                // Setting little delay while displaying new content
                // setTimeout(function() {
                //     // appending posts after last post with class="post"
                //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                // }, 2000);
            },
            complete: function (params) {
                form.removeClass("modal-progress");
            },
        });
    });
    $("#md_DesfinHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false);
    });
});
function loadDataCount() {
    $.ajax({
        url: window.location.origin + "/penerbitan/deskripsi/final?count_data=true",
        type: "get",
        dataType: "json",
        success: function (response) {
            $("#countData").html(response);
        },
    });
}
$(document).ready(function () {
    $(".select-status")
        .select2({
            placeholder: "Pilih Status",
        })
        .on("change", function (e) {
            if (this.value) {
                $(this).valid();
            }
        });
});
$(document).ready(function () {
    $(".select-filter")
        .select2({
            placeholder: "Filter Status\xa0\xa0",
        })
        .on("change", function (e) {
            if (this.value) {
                $(".clear_field").removeAttr("hidden");
                // $(this).valid();
            }
        });
});
$(document).ready(function () {
    $(".clear_field").click(function () {
        $(".select-filter").val("").trigger("change");
        $(".clear_field").attr("hidden", "hidden");
    });
});
$(document).ready(function () {
    $("#tb_DesFinal").on("click", ".btn-status-desfin", function (e) {
        e.preventDefault();
        let id = $(this).data("id"),
            kode = $(this).data("kode"),
            judul = $(this).data("judul");
        $("#id").val(id);
        $("#kode").val(kode);
        $("#judulAsli").val(judul);
    });
});
$(document).ready(function () {
    function ajaxUpdateStatusDeskripsiFinal(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/deskripsi/final/update-status-progress",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                if (result.status == "error") {
                    notifToast(result.status, result.message);
                    $("#fm_UpdateStatusDesfin").trigger("reset");
                } else {
                    notifToast(result.status, result.message);
                    $("#fm_UpdateStatusDesfin").trigger("reset");
                    location.reload();
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
                    // addForm.showErrors(err);
                }
                notifToast("error", "Gagal melakukan ubah status!");
            },
            complete: function () {
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            },
        });
    }

    $("#fm_UpdateStatusDesfin").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="kode"]').val();
            let judul = $(this).find('[name="judul_asli"]').val();
            swal({
                title:
                    "Yakin mengubah status deskripsi final " +
                    kode +
                    "-" +
                    judul +
                    "?",
                text: "Anda sebagai Prodev tetap dapat mengubah kembali data yang sudah Anda perbarui saat ini.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxUpdateStatusDeskripsiFinal($(this));
                }
            });
        }
    });
});
