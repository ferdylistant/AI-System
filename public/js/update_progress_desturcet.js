$(function() {
    $('[name="status_filter"]').val('').trigger('change');
    let tableDesTurunCetak = $('#tb_DesTurCet').DataTable({
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
        ajax: window.location.origin + '/penerbitan/deskripsi/turun-cetak',
        columns: [{
                data: 'kode',
                name: 'kode',
                title: 'Kode'
            },
            {
                data: 'judul_final',
                name: 'judul_final',
                title: 'Judul Final'
            },
            {
                data: 'penulis',
                name: 'penulis',
                title: 'Penulis',
            },
            {
                data: 'nama_pena',
                name: 'nama_pena',
                title: 'Nama Pena',
            },
            {
                data: 'format_buku',
                name: 'format_buku',
                title: 'Format Buku',
            },
            {
                data: 'pic_prodev',
                name: 'pic_prodev',
                title: 'PIC Prodev'
            },
            {
                data: 'tgl_masuk',
                name: 'tgl_masuk',
                title: 'Tgl Masuk'
            },
            {
                data: 'history',
                name: 'history',
                title: 'History Progress'
            },
            {
                data: 'action',
                name: 'action',
                title: 'Action',
                orderable: false
            },
        ],

    });
    loadDataCount();
    $('[name="status_filter"]').on('change', function() {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableDesTurunCetak.column($(this).data('column'))
            .search(val ? val : '', true, false)
            .draw();
    });
});
function loadDataCount() {
    $.ajax({
        url: window.location.origin + "/penerbitan/deskripsi/turun-cetak?count_data=true",
        type: "get",
        dataType: "json",
        success: function (response) {
            $("#countData").html(response);
        },
    });
}
$(document).ready(function () {
    $("#tb_DesTurCet").on("click", ".btn-history", function (e) {
        var id = $(this).data("id");
        var judul = $(this).data("judulfinal");
        // console.log(judul);
        $.post(
            window.location.origin +
                "/penerbitan/deskripsi/turun-cetak/lihat-history",
            { id: id },
            function (data) {
                $("#titleModalDesturcet").html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Naskah "' +
                        judul +
                        '"'
                );
                $("#load_more").data("id", id);
                $("#dataHistoryDesturcet").html(data);
                $("#md_DesturcetHistory").modal("show");
            }
        );
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        $(this).data("paginate", page + 1);

        $.ajax({
            url:
                window.location.origin +
                "/penerbitan/deskripsi/turun-cetak/lihat-history",
            data: {
                id: id,
                page: page,
            },
            type: "post",
            beforeSend: function () {
                $(".load-more").text("Loading...");
            },
            success: function (response) {
                // console.log(response);
                if (response.length == 0) {
                    notifToast("error", "Tidak ada data lagi");
                }
                $("#dataHistoryDesturcet").append(response);
                // Setting little delay while displaying new content
                // setTimeout(function() {
                //     // appending posts after last post with class="post"
                //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                // }, 2000);
            },
            complete: function (params) {
                $(".load-more").text("Load more").fadeIn("slow");
            },
        });
    });
    $("#md_DesturcetHistory").on("hidden.bs.modal", function () {
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false);
    });
});
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
    $("#tb_DesTurCet").on("click", ".btn-status-desturcet", function (e) {
        e.preventDefault();
        let id = $(this).data("id"),
            kode = $(this).data("kode"),
            judul = $(this).data("judul");
        $("#id").val(id);
        $("#kode").val(kode);
        $("#judulFinal").val(judul);
    });
});
$(document).ready(function () {
    function ajaxUpdateStatusDeskripsiTurunCetak(data) {
        let el = data.get(0);
        // console.log(el);
        $.ajax({
            type: "POST",
            url:
                window.location.origin +
                "/penerbitan/deskripsi/turun-cetak/update-status-progress",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (result) {
                // console.log(result);
                notifToast(result.status, result.message);
                $("#fm_UpdateStatusDesturcet").trigger("reset");
                if (result.status == "success") {
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

    $("#fm_UpdateStatusDesturcet").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            let kode = $(this).find('[name="kode"]').val();
            let judul = $(this).find('[name="judul_final"]').val();
            swal({
                title:
                    "Yakin mengubah status deskripsi turun cetak " +
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
                    ajaxUpdateStatusDeskripsiTurunCetak($(this));
                }
            });
        }
    });
});
