var baseUrl = window.location.origin + "/penerbitan/naskah";
$(document).ready(function () {
    $('#fm_FilterPenilaian').trigger("reset");
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover'
        })
    })
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
    function loadDOM() {
        var start_date;
        var end_date;
        var DateFilterFunction = (function (oSettings, aData, iDataIndex) {
            var dateStart = parseDateValue(start_date);
            var dateEnd = parseDateValue(end_date);
            //Kolom tanggal yang akan kita gunakan berada dalam urutan 1, karena dihitung mulai dari 0
            var evalDate = parseDateValue(aData[7]);
            if ((isNaN(dateStart) && isNaN(dateEnd)) ||
                (isNaN(dateStart) && evalDate <= dateEnd) ||
                (dateStart <= evalDate && isNaN(dateEnd)) ||
                (dateStart <= evalDate && evalDate <= dateEnd)) {
                return true;
            }
            return false;
        });

        // fungsi untuk converting format tanggal dd/mm/yyyy menjadi format tanggal javascript menggunakan zona waktubrowser
        function parseDateValue(rawDate) {
            var dateArray = rawDate.split("/");
            var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[0]);  // -1 because months are from 0 to 11
            return parsedDate;
        }
        $("div.jalbuk").html(`<div class="input-group mb-1">
        <select data-column="4" name="status_filter_jb" id="status_filter_jb"
            class="form-control select-filter-jb status_filter_jb">
            <option label="Pilih Filter Data"></option>
        </select>
        <button type="button"
            class="btn btn-outline-danger clear_field_jb text-danger align-self-center"
            data-toggle="tooltip" title="Reset" hidden><i
                class="fas fa-times"></i></button>
        </div>`);
        $("div.kelengkapan").html(`<div class="input-group mb-1">
        <select data-column="8" name="status_filter" id="status_filter"
            class="form-control select-filter status_filter">
            <option label="Pilih Filter Data"></option>
        </select>
        <button type="button"
            class="btn btn-outline-danger clear_field text-danger align-self-center"
            data-toggle="tooltip" title="Reset" hidden><i
                class="fas fa-times"></i></button>
        </div>`);
        $("div.keputusan").html(`<div class="input-group mb-1">
        <select data-column="8" name="status_filter_kep" id="status_filter_kep"
            class="form-control select-filter-kep status_filter_kep">
            <option label="Pilih Filter Data"></option>
        </select>
        <button type="button"
            class="btn btn-outline-danger clear_field_kep text-danger align-self-center"
            data-toggle="tooltip" title="Reset" hidden><i
                class="fas fa-times"></i></button>
        </div>`);
        $("div.datesearchbox").html('<div class="input-group"><input type="text" class="form-control pull-right deletable" id="datesearch" name="datefilter" placeholder="Filter by date range.."> </div>');
        $('input.deletable').wrap('<span class="deleteicon"></span>').after($('<span>x</span>').click(function () {
            $(this).prev('input').val('').trigger('change');
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            tableNaskah.draw();
        }));
        document.getElementsByClassName("datesearchbox")[0].style.textAlign = "right";
        //konfigurasi daterangepicker pada input dengan id datesearch
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            showDropdowns: true,
            "timePicker24Hour": true,
            maxDate: moment(),
            ranges: {
                'Hari Ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Sebelumnya': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "Apply",
                "cancelLabel": "Clear",
                "daysOfWeek": [
                    "Min",
                    "Sen",
                    "Sel",
                    "Rab",
                    "Kam",
                    "Jum",
                    "Sab"
                ],
                "monthNames": [
                    "Januari",
                    "Februari",
                    "Maret",
                    "April",
                    "Mei",
                    "Juni",
                    "Juli",
                    "Agustus",
                    "September",
                    "Oktober",
                    "November",
                    "Desember"
                ],
                "firstDay": 1
            },
            "linkedCalendars": false,
            "opens": "center",
            "cancelClass": "btn-danger"
        });

        //menangani proses saat apply date range
        $('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            start_date = picker.startDate.format('DD/MM/YYYY');
            end_date = picker.endDate.format('DD/MM/YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
            tableNaskah.draw();
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            start_date = '';
            end_date = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            tableNaskah.draw();
        });
        $("div.totalData").html(`<span class="badge badge-warning"
        style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i
            class="fas fa-database"></i> Total data naskah
        masuk: <b id="totalNaskah">0</b></span>`);
        $(".status_filter_jb").select2({
            minimumResultsForSearch: -1,
            placeholder: 'Filter Jalur Buku',
            ajax: {
                url: baseUrl + "?request_=selectFilterJalurbuku",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        term: params.term
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item,
                                id: item,
                            };
                        }),
                    };
                },
            },
        }).on("change", function (e) {
            if (this.value) {
                $(".clear_field_jb").removeAttr("hidden");
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
            }
        });
        $(".status_filter").select2({
            minimumResultsForSearch: -1,
            placeholder: 'Filter Data Lengkap/Belum',
            ajax: {
                url: baseUrl + "?request_=selectFilterKelengkapanData",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        term: params.term
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item,
                                id: item,
                            };
                        }),
                    };
                }
            }
        }).on('change', function () {
            if (this.value) {
                $(".clear_field").removeAttr("hidden");
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                tableNaskah.column($(this).data('column'))
                    .search(val ? val : '', true, false)
                    .draw();
            }
        });
        $(".status_filter_kep").select2({
            minimumResultsForSearch: -1,
            placeholder: 'Keputusan Final',
            ajax: {
                url: baseUrl + "?request_=selectFilterKeputusanDireksi",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        term: params.term
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    // console.log(data);
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item,
                                id: item,
                            };
                        }),
                    };
                }
            }
        }).on('change', function () {
            if (this.value) {
                $(".clear_field_kep").removeAttr("hidden");
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                tableNaskah.column($(this).data('column'))
                    .search(val ? val : '', true, false)
                    .draw();
            }
        });
        $(".clear_field_jb").on('click', function (e) {
            var checkReg = false;
            if ($(".select-filter-jb").val() === 'Reguler') {
                var checkReg = true
            }
            $(".select-filter-jb").val(null).trigger("change");
            var val = $.fn.dataTable.util.escapeRegex($(".select-filter-jb").val());
            tableNaskah.column($(".select-filter-jb").data('column'))
                .search(val ? val : '', true, false)
                .draw();
            if (checkReg === true) {
                $("#countPenilaian").attr("hidden", checkReg);
            }
            $(".clear_field_jb").attr("hidden", "hidden");
        });
        $('.clear_field').click(function () {
            $(".select-filter").val(null).trigger('change');
            var val = $.fn.dataTable.util.escapeRegex($(".select-filter").val());
            tableNaskah.column($(".select-filter").data('column'))
                .search(val ? val : '', true, false)
                .draw();
            $('.clear_field').attr('hidden', 'hidden');
        });
        $('.clear_field_kep').click(function () {
            $(".select-filter-kep").val(null).trigger('change');
            var val = $.fn.dataTable.util.escapeRegex($(".select-filter-kep").val());
            tableNaskah.column($(".select-filter-kep").data('column'))
                .search(val ? val : '', true, false)
                .draw();
            $('.clear_field_kep').attr('hidden', 'hidden');
        });
    }
    $(".select-filter-jb").val("").trigger("change");
    $(".select-filter").val("").trigger("change");
    let tableNaskah = $('#tb_Naskah').DataTable({
        // "bSort": false,
        "responsive": true,
        "autoWidth": false,
        "aaSorting": [],
        "dom": "<'row'<'col-sm-3' <'jalbuk'>><'col-sm-3'<'kelengkapan'>><'col-sm-3'<'keputusan'>><'col-sm-3' <'datesearchbox'>>>" + "<'row'<'col-sm-3'B><'col-sm-6 text-center' <'totalData'>><'col-sm-3'f>>" +
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
                            columns: [0, 1, 2, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="text-danger fa fa-file-pdf"></i> PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="text-success fa fa-file-excel"></i> Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 4, 5, 6, 7]
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
        drawCallback: () => {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-naskah'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover'
                    })
                })
                // console.log(tooltipTriggerList);
        },
        ajax: {
            url: baseUrl,
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
            data: 'penulis',
            name: 'penulis',
            title: 'Penulis'
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
            data: 'created_at',
            name: 'created_at',
            title: 'Dibuat pada'
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
        console.log(settings);
        console.log(message);
        console.log(helpPage);
        notifToast("error", settings.jqXHR.statusText)
        if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
            window.location.reload();
        }
    };
    loadDOM();
    loadData();

    var checkBoxes = $('input:checkbox');
    checkBoxes.change(function () {
        $('button[type="submit"]').prop('disabled', checkBoxes.filter(':checked').length < 1);
        $('button[type="reset"]').prop('disabled', checkBoxes.filter(':checked').length < 1);
    });
    $('input:checkbox').change();
    $("input:checkbox").on('click', function () {
        // in the handler, 'this' refers to the box clicked on
        var $box = $(this);
        if ($box.is(":checked")) {
            // the name of the box is retrieved using the .attr() method
            // as it is assumed and expected to be immutable
            var group = "input:checkbox[name='" + $box.attr("name") + "']";
            // the checked state of the group/box on the other hand will change
            // and the current value is retrieved using .prop() method
            $(group).prop("checked", false);
            $box.prop("checked", true);
        } else {
            $box.prop("checked", false);
        }
    });
    $('#fm_FilterPenilaian').submit(function (e) {
        e.preventDefault();
        let el = $(this).get(0),
            cardWrap = $(this).closest(".card");
        $.ajax({
            type: 'POST',
            url: baseUrl + '/ajax/filter-penilaian',
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                cardWrap.addClass("card-progress");
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: (res) => {
                // console.log(res);
                if ($.fn.DataTable.isDataTable('#tb_Naskah')) {
                    $('#tb_Naskah').DataTable().destroy();
                }
                var tableNaskah = $('#tb_Naskah').DataTable({
                    // "bSort": false,
                    "responsive": true,
                    "autoWidth": false,
                    "aaSorting": [],
                    "dom": "<'row'<'col-sm-4' <'jalbuk'>><'col-sm-4'<'kelengkapan'>><'col-sm-4'<'keputusan'>>>" + "<'row'<'col-sm-3'B><'col-sm-6 text-center' <'totalData'>><'col-sm-3'f>>" +
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
                                        columns: [0, 1, 2, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: '<i class="text-danger fa fa-file-pdf"></i> PDF',
                                    exportOptions: {
                                        columns: [0, 1, 2, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'excel',
                                    text: '<i class="text-success fa fa-file-excel"></i> Excel',
                                    exportOptions: {
                                        columns: [0, 1, 2, 4, 5, 6, 7]
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
                    data: res.data,
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
                        data: 'penulis',
                        name: 'penulis',
                        title: 'Penulis'
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
                        data: 'created_at',
                        name: 'created_at',
                        title: 'Dibuat pada'
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
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover'
                    })
                });
                loadDOM();
                loadData();
                $('html, body').animate({
                    scrollTop: $("#div_filterPenilaian").offset().top
                }, 800);
            },
            error: (err) => {
                console.log(err);
            },
            complete: () => {
                cardWrap.removeClass("card-progress");
                $('button[type="submit"]')
                    .prop("disabled", false)
                    .removeClass("btn-progress");
            }
        })
    })
    $('#fm_FilterPenilaian button[type="reset"]').click(function (e) {
        $('#fm_FilterPenilaian').trigger('reset');
        $(".select-filter-jb").val("").trigger("change");
        $(".clear_field_jb").attr("hidden", "hidden");
        $(".select-filter").val("").trigger("change");
        $(".clear_field").attr("hidden", "hidden");
        $('button[type="submit"]').attr("disabled", true).change();
        $('button[type="reset"]').attr("disabled", true).change();
        if ($.fn.DataTable.isDataTable('#tb_Naskah')) {
            $('#tb_Naskah').DataTable().destroy();
        }
        var tableNaskah = $('#tb_Naskah').DataTable({
            // "bSort": false,
            "responsive": true,
            "autoWidth": false,
            "aaSorting": [],
            "dom": "<'row'<'col-sm-4' <'jalbuk'>><'col-sm-4'<'kelengkapan'>><'col-sm-4'<'keputusan'>>>" + "<'row'<'col-sm-3'B><'col-sm-6 text-center' <'totalData'>><'col-sm-3'f>>" +
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
                                columns: [0, 1, 2, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="text-danger fa fa-file-pdf"></i> PDF',
                            exportOptions: {
                                columns: [0, 1, 2, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="text-success fa fa-file-excel"></i> Excel',
                            exportOptions: {
                                columns: [0, 1, 2, 4, 5, 6, 7]
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
                        return new bootstrap.Tooltip(tooltipTriggerEl, {
                            trigger: 'hover'
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
                data: 'penulis',
                name: 'penulis',
                title: 'Penulis'
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
        loadDOM();
        loadData();
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
