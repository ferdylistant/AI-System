
var baseUrl = window.location.origin + "/penerbitan/naskah";
$(document).ready(function () {
    function loadData() {
        $.ajax({
            url: baseUrl + "?request_=getCountNaskah",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                // console.log(data);
                $("#totalNaskah").prop('Counter', 0).animate({
                    Counter: data
                }, {
                    duration: 4000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            },
        });
    }
    $(".select-filter-jb").val("").trigger("change");
    $(".select-filter").val("").trigger("change");
    let tableNaskah = $('#tb_Naskah').DataTable({
        // "bSort": false,
        "responsive": true,
        "autoWidth": false,
        "aaSorting": [],
        "dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        scrollX: true,
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
                            columns: [0,1,2,4,5,6,7,8,9]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="text-danger fa fa-file-pdf"></i> PDF',
                        exportOptions: {
                            columns: [0,1,2,4,5,6,7,8,9]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="text-success fa fa-file-excel"></i> Excel',
                        exportOptions: {
                            columns: [0,1,2,4,5,6,7,8,9]
                        }
                    },
                ]
            },
        ],
        fixedColumns: {
            left: 0,
            right: 2
        },
        pagingType: 'input',
        processing: true,
        serverSide: false,
        language: {
            searchPlaceholder: 'Cari...',
            sSearch: '',
            lengthMenu: "_MENU_ /halaman",
        },
        ajax: {
            url: baseUrl,
            complete: () => {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl,{
                        trigger : 'hover'
                    })
                })
            }
        },
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
            data: 'action',
            name: 'action',
            title: 'Action',
            searchable: false,
            orderable: false
        },
        ],
    });
    new $.fn.dataTable.Buttons(tableNaskah, {
        buttons: [
            'print', 'excel', 'pdf'
        ]
    });

    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        notifToast("error", settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    loadData();
    // tableNaskah.on('init.dt', function (e) {
    //     tableNaskah.page(56).draw(false);
    //     // tableNaskah.order( [[ 1, 'desc' ]] ).draw( false );
    //     var info = tableNaskah.page.info();
    //     console.log(info.pages);
    // });
    $('[name="status_filter_jb"]').on('change', function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableNaskah.column($(this).data('column'))
            .search(val ? val : '', true, false)
            .draw();
        cardWrap = $(this).closest(".card");
        if (val == "Reguler") {
            $.ajax({
                url: baseUrl + "?request_=getCountNaskahReguler",
                type: "GET",
                dataType: "JSON",
                beforeSend: function () {
                    cardWrap.addClass("card-progress");
                },
                success: function (data) {
                    // console.log(data);
                    $("#countPenilaian").attr("hidden", false);
                    $("#countPenilaian").html(data.html);
                    $("#naskahBelumDinilai").prop('Counter', 0).animate({
                        Counter: data.belumDinilai
                    }, {
                        duration: 4000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now) + ' Naskah');
                        }
                    });
                    $("#animate-count-prodev").prop('Counter', 0).animate({
                        Counter: data.countProdev
                    }, {
                        duration: 4000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                    $("#animate-count-mpenerbitan").prop('Counter', 0).animate({
                        Counter: data.countMPenerbitan
                    }, {
                        duration: 4000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                    $("#animate-count-mpemasaran").prop('Counter', 0).animate({
                        Counter: data.countMPemasaran
                    }, {
                        duration: 4000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                    $("#animate-count-dpemasaran").prop('Counter', 0).animate({
                        Counter: data.countDPemasaran
                    }, {
                        duration: 4000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                    $("#animate-count-direksi").prop('Counter', 0).animate({
                        Counter: data.countDireksi
                    }, {
                        duration: 4000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                },
                complete: function () {
                    cardWrap.removeClass("card-progress");
                }
            });
        } else {
            cardWrap.addClass("card-progress");
            setTimeout(function () {
                cardWrap.removeClass("card-progress");
            }, 1000);
            $("#countPenilaian").attr("hidden", true);
        }
    });
    $('[name="status_filter"]').on('change', function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableNaskah.column($(this).data('column'))
            .search(val ? val : '', true, false)
            .draw();
    });
    $("#tb_Naskah").on("click", ".btn-tracker", function (e) {
        var id = $(this).data("id");
        var judul = $(this).data("judulasli");
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: baseUrl + "/ajax/lihat-tracker",
            type: "POST",
            data: {
                id: id,
            },
            cache: false,
            beforeSend: function () {
                cardWrap.addClass("card-progress");
            },
            success: function (data) {
                // console.log(data);
                $("#titleModalTracker").html('<i class="fas fa-file-signature"></i>&nbsp;Tracking Progress Naskah "' + judul + '"');
                $("#dataShowTracking").html(data);
                $("#md_Tracker").modal("show");
            },
            complete: function () {
                cardWrap.removeClass("card-progress");
            },
        });
    });
    $("#tb_Naskah").on("click", ".btn-history", function (e) {
        var id = $(this).data("id");
        var judul = $(this).data("judulasli");
        let cardWrap = $(this).closest(".card");
        $.ajax({
            url: baseUrl + "/ajax/lihat-history",
            type: "POST",
            data: {
                id: id,
            },
            cache: false,
            beforeSend: function () {
                cardWrap.addClass("card-progress");
            },
            success: function (data) {
                // console.log(data);
                $("#titleModalNaskah").html('<i class="fas fa-history"></i>&nbsp;History Progress Naskah "' + judul + '"');
                $("#load_more").data("id", id);
                $("#dataHistoryNaskah").html(data);
                $("#md_NaskahHistory").modal("show");
            },
            complete: function () {
                cardWrap.removeClass("card-progress");
            },
        });
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_NaskahHistory");
        $(this).data("paginate", page + 1);

        $.ajax({
            url: baseUrl + "/ajax/lihat-history",
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
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistoryNaskah").append(response);
                    $('.thin').animate({ scrollTop: $('.thin').prop("scrollHeight") }, 800);
                }
            },
            complete: function (params) {
                // console.log();
                form.removeClass("modal-progress");
            },
        });
    });
    $('#md_NaskahHistory').on('hidden.bs.modal', function () {
        $('.load-more').data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
    });
    $(".select-filter-jb")
        .select2({
            placeholder: "Filter Jalur Buku",
        })
        .on("change", function (e) {
            if (this.value) {
                $(".clear_field_jb").removeAttr("hidden");
                // $(this).valid();
            }
        });
    $(".clear_field_jb").click(function () {
        $(".select-filter-jb").val("").trigger("change");
        $(".clear_field_jb").attr("hidden", "hidden");
    });
    $(".select-filter").select2({
        placeholder: 'Filter Data Lengkap/Belum\xa0\xa0',
    }).on('change', function (e) {
        if (this.value) {
            $('.clear_field').removeAttr('hidden');
            // $(this).valid();
        }
    });
    $('.clear_field').click(function () {
        $(".select-filter").val('').trigger('change');
        $('.clear_field').attr('hidden', 'hidden');
    });
    $(document).on("click", ".action-modal", function (e) {
        e.preventDefault();
        let role = $(this).data('role');
        let cardWrap = $(this).closest(".card");
        // console.log(role);
        $.ajax({
            url: baseUrl + "?request_=getModalDetailJumlahPenilaian",
            type: 'GET',
            data: {
                role: role
            },
            cache: false,
            beforeSend: function () {
                cardWrap.addClass('card-progress');
            },
            success: function (data) {
                $("#modalPenilaian").find("#titleModalPenilaian").text(data.titleModal);
                $("#modalPenilaian").find("#totalNaskahDinilai").text(data.totalNaskah);
                $("#modalPenilaian").find("#contentModal").html(data.content);
                $("#modalPenilaian").find("#boxContent").attr('style', 'box-shadow:' + data.style);
                $("#modalPenilaian").find("#badgeTotal").addClass('badge ' + data.class);
                $("#modalPenilaian").find("#scrollBar").addClass('container-fluid p-3 example-2 ' + data.scroll + ' thin');
                $("#modalPenilaian").modal("show");
            },
            error: function (err) {
                notifToast("error", "Gagal memuat!");
            },
            complete: function () {
                cardWrap.removeClass('card-progress');
            }
        });
    });
    $('#modalPenilaian').on('hidden.bs.modal', function () {
        $(this).find(".modal-content").removeAttr('style');
        $(this).find("#badgeTotal").removeClass();
        $(this).find("#scrollBar").removeClass();
    });
    //! Tandai naskah lengkap
    function ajaxTandaDataLengkap(data) {
        let id = data.data("id");
        let judul_asli = data.data("judul");
        let kode = data.data("kode");
        $.ajax({
            type: "POST",
            url: baseUrl + "/tandai-data-lengkap",
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
    function ajaxDeleteNaskah(data) {
        let id = data.data("id");
        let cardWrap = data.closest(".card");
        $.ajax({
            type: "POST",
            url: baseUrl + "/ajax/delete-naskah",
            data: {
                id: id,
            },
            beforeSend: function () {
                cardWrap.addClass("card-progress");
            },
            success: function (result) {
                if (result.status == "success") {
                    tableNaskah.ajax.reload();
                }
                notifToast(result.status, result.message);
            },
            error: function (err) {
                notifToast("error", err);
            },
            complete: function () {
                cardWrap.removeClass("card-progress");
            },
        });
    }
    $("#tb_Naskah").on("click", ".btn-delete-naskah", function (e) {
        e.preventDefault();
        let judul_asli = $(this).data("judul");
        let kode = $(this).data("kode");
        swal({
            title:
                "Apakah anda yakin ingin data " +
                kode +
                "_" +
                judul_asli +
                " dihapus?",
            text: "Data naskah yang terhapus dapat dimunculkan kembali pada halaman restore..",
            type: "warning",
            html: true,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                // window.location.href = getLink
                ajaxDeleteNaskah($(this));
            }
        });
    });
});


