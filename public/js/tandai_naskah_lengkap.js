$(function() {
    let tableNaskah = $('#tb_Naskah').DataTable({
        // bSort: true,
        responsive: true,
        autoWidth: true,
        pagingType: 'input',
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: 'Cari...',
            sSearch: '',
        },
        ajax: window.location.origin + "/penerbitan/naskah",
        columns: [{
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
                data: 'masuk_naskah',
                name: 'masuk_naskah',
                title: 'Masuk Naskah'
            },
            {
                data: 'created_by',
                name: 'created_by',
                title: 'Pembuat Naskah'
            },
            {
                data: 'stts_penilaian',
                name: 'stts_penilaian',
                title: 'Penilaian'
            },
            {
                data: 'history',
                name: 'history',
                title: 'History'
            },
            {
                data: 'action',
                name: 'action',
                title: 'Action',
                searchable: false,
                orderable: false
            },
        ],
    });
    // tableNaskah.on('init.dt', function (e) {
    //     tableNaskah.page(56).draw(false);
    //     // tableNaskah.order( [[ 1, 'desc' ]] ).draw( false );
    //     var info = tableNaskah.page.info();
    //     console.log(info.pages);
    // });
    $('[name="status_filter_jb"]').on('change', function() {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableNaskah.column($(this).data('column'))
            .search(val ? val : '', true, false)
            .draw();
    });
    $('[name="status_filter"]').on('change', function() {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableNaskah.column($(this).data('column'))
            .search(val ? val : '', true, false)
            .draw();
    });
});
$(document).ready(function() {
    $(".select-filter-jb")
        .select2({
            placeholder: "Filter Jalur Buku",
        })
        .on("change", function(e) {
            if (this.value) {
                $(".clear_field_jb").removeAttr("hidden");
                // $(this).valid();
            }
        });
});
$(document).ready(function() {
    $(".clear_field_jb").click(function() {
        $(".select-filter-jb").val("").trigger("change");
        $(".clear_field_jb").attr("hidden", "hidden");
    });
});
$(document).ready(function() {
    $(".select-filter").select2({
        placeholder: 'Filter Data Lengkap/Belum\xa0\xa0',
    }).on('change', function(e) {
        if (this.value) {
            $('.clear_field').removeAttr('hidden');
            // $(this).valid();
        }
    });
});
$(document).ready(function() {
    $('.clear_field').click(function() {
        $(".select-filter").val('').trigger('change');
        $('.clear_field').attr('hidden', 'hidden');
    });
});
function ajaxTandaDataLengkap(data) {
    let id = data.data("id");
    let judul_asli = data.data("judul");
    let kode = data.data("kode");
    $.ajax({
        type: "POST",
        url: window.location.origin + "/penerbitan/naskah/tandai-data-lengkap",
        data: {
            id: id,
            judul_asli: judul_asli,
            kode: kode,
        },
        beforeSend: function () {
            $("#sectionDataNaskah").parent().addClass("card-progress");
        },
        success: function (result) {
            if (result.status == "success") {
                notifToast(result.status, result.message);
                location.reload();
            } else {
                notifToast(result.status, result.message);
            }
            // $('#modalDecline').modal('hide');
            // ajax.reload();
            // location.href = result.redirect;
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
                addForm.showErrors(err);
            }
            notifToast("error", "Terjadi kesalahan!");
        },
        complete: function () {
            $("#sectionDataNaskah").parent().removeClass("card-progress");
        },
    });
}
$(document).on("click", ".mark-sent-email", function (e) {
    e.preventDefault();
    let judul_asli = $(this).data("judul");
    let kode = $(this).data("kode");
    swal({
        title:
            "Apakah anda yakin data " +
            kode +
            "_" +
            judul_asli +
            " sudah lengkap?",
        text: "Data naskah dan data penulis harus sudah lengkap..",
        type: "warning",
        html: true,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((confirm_) => {
        if (confirm_) {
            // window.location.href = getLink
            ajaxTandaDataLengkap($(this));
        }
    });
});
