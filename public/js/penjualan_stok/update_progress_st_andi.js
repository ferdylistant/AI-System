$(document).ready(function () {
    let tabelStok = $('#tb_stokGudangAndi').DataTable({
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
            total_stok = $(e.relatedTarget).data('total_stok')
        judul = $(e.relatedTarget).data('judul')
        cardWrap = $("#modalRack");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=show-modal-rack',
            type: 'GET',
            data: {
                stok_id: stok_id,
                total_stok: total_stok,
            },
            cache: false,
            success: function (result) {
                // console.log(result.contentForm);
                cardWrap.find('#sectionTitle').text(judul.charAt(0).toUpperCase() + judul.slice(1)).trigger('change');
                cardWrap.find('#contentRack').html(result.contentRack).trigger('change');
                cardWrap.find('#contentForm').html(result.contentForm).trigger('change');
                cardWrap.find('[name="stok_id"]').val(result.stok_id).trigger('change');
                cardWrap.find('[name="total_stok"]').val(result.total_stok).trigger('change');
                cardWrap.find('#totalStok').text(result.total_stok).trigger('change');
                cardWrap.find('#totalMasuk').text(result.total_masuk).trigger('change');
                cardWrap.find('#totalBelum').text(result.total_belum).trigger('change');
                cardWrap.find('#totalKeluar').text(result.total_keluar).trigger('change');
                cardWrap.find('#totalPenjualan').text(result.total_penjualan).trigger('change');
                cardWrap.find('#totalLain').text(result.total_lain).trigger('change');
                $('.counter').each(function () {
                    $(this).prop('Counter', 0).animate({
                        Counter: $(this).text()
                    }, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                });
                $('[data-toggle="popover"]').popover();
                $('.select-rack').select2({
                    placeholder: 'Pilih rak',
                    ajax: {
                        url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=select-rack',
                        type: "GET",
                        data: function (params) {
                            var queryParameters = {
                                term: params.term
                            };
                            return queryParameters;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.nama,
                                        id: item.id,
                                    };
                                }),
                            };
                        },
                    },
                }).on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                    }
                });
                $('.select-optgudang').select2({
                    placeholder: 'Pilih operator gudang',
                    ajax: {
                        url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=select-operator-gudang',
                        type: "GET",
                        data: function (params) {
                            var queryParameters = {
                                term: params.term
                            };
                            return queryParameters;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.nama,
                                        id: item.id,
                                    };
                                }),
                            };
                        },
                    },
                }).on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                    }
                });
                $('.datepicker').datepicker({
                    format: 'dd MM yyyy',
                    endDate: new Date(),
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    container: '#modalRack .modal-body'
                });
            },
            error: function (err) {
                // console.log(err);
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
    let addRack = jqueryValidation_('#fm_addRak', {
        "rack[]": {
            required: true,
        },
        "jml_stok[]": {
            required: true,
            number: true
        },
        "tgl_masuk_stok[]": {
            required: true,
            date: true
        },
        "users_id[]": {
            required: true,
        },
    });
    function ajaxAddRak(data) {
        let el = data.get(0),
            cardWrap = $('#modalRack');

        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=add-data-rack',
            type: 'POST',
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: () => {
                cardWrap.addClass("modal-progress");
            },
            success: (res) => {
                // console.log(res);
                notifToast(res.status, res.message);
                if (res.status === 'success') {
                    cardWrap.modal('hide');
                    tabelStok.ajax.reload()
                }
            },
            error: (err) => {
                console.log(err);
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    addRack.showErrors(err);
                }
                notifToast("error", err.statusText);
                cardWrap.removeClass("modal-progress");
            },
            complete: () => {
                cardWrap.removeClass("modal-progress");

            }
        });
    }
    $('#fm_addRak').on('submit', function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                title: 'Apakah penempatan rak dan jumlah sudah sesuai?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxAddRak($(this));
                }
            });
        }
    });
    $('#modalDetailRack').on('show.bs.modal', function (e) {
        let rack_id = $(e.relatedTarget).data('rack_id'),
            stok_id = $(e.relatedTarget).data('stok_id'),
            rack_data_id = $(e.relatedTarget).data('rack_data_id'),
            nama_rak = $(e.relatedTarget).data('nama_rak'),
            cardWrap = $("#modalDetailRack");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=show-modal-rack-detail',
            type: 'GET',
            data: {
                rack_id: rack_id,
                stok_id: stok_id,
                rack_data_id: rack_data_id,
                nama_rak: nama_rak
            },
            cache: false,
            success: function (result) {
                cardWrap.find('#sectionTitle').html("Rack (" + nama_rak + ") " + result.popover).trigger('change');
                cardWrap.find('#contentDetailRack').html(result.content).trigger('change');
                cardWrap.find('button').data('rack_data_id',rack_data_id);
                cardWrap.find('button').data('stok_id',stok_id);
                // cardWrap.find('#totalStok').text(result.total_stok).trigger('change');
                $('[data-toggle="popover"]').popover();
            },
            error: function (err) {
                // console.log(err);
                notifToast('error', err.statusText);
                cardWrap.removeClass('modal-progress')
            },
            complete: function () {
                cardWrap.removeClass('modal-progress')
            }
        });
    });
    $("#modalDetailRack").on("hidden.bs.modal", function (e) {
        // let d = $(e.relatedTarget).attr('id','statusJob');
        // console.log(d);
        $(this).addClass("modal-progress");
    });
    $('#modalDetailRack').on('click', '#btnEditRack', function (e) {
        e.preventDefault();
        let rack_id = $(this).data('rack_id'),
            stok_id = $(this).data('stok_id'),
            id = $(this).data('id'),
            cardWrap = $("#modalEditRack");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=show-modal-rack-edit',
            type: 'GET',
            cache: false,
            data: {
                rack_id: rack_id,
                stok_id: stok_id,
                id: id
            },
            cache: false,
            success: (result) => {
                cardWrap.modal('show')
                // $.fn.modal.Constructor.prototype._enforceFocus = function () { };
                cardWrap.find('#contentEditRackModal').html(result);
                $('#selectOptGudangEdit').select2({
                    placeholder: 'Pilih operator gudang'
                }).on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                    }
                });
            },
            error: (err) => {
                console.log(err);
                notifToast('error', err.statusText);
                cardWrap.removeClass('modal-progress')
            },
            complete: function () {
                cardWrap.removeClass('modal-progress')
            }
        });
    });
    $("#modalEditRack").on("hidden.bs.modal", function (e) {
        // let d = $(e.relatedTarget).attr('id','statusJob');
        // console.log(d);
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
    });
    $('#modalEditRack').on('focus', '#datepickerEditRack', function (e) {
        e.preventDefault();
        $(this).datepicker({
            format: 'dd MM yyyy',
            endDate: new Date(),
            autoclose: true,
            clearBtn: true,
            todayHighlight: true,
            container: '#modalEditRack .modal-body',
        });
    });
    let editRack = jqueryValidation_('#fm_EditRack', {
        edit_jml_stok: {
            required: true
        },
        edit_tgl_masuk_stok: {
            required: true
        },
        "edit_operators_id[]": {
            required: true
        },
    });
    function ajaxEditRak(data) {
        let el = data.get(0),
            cardWrap = $('#modalEditRack');
        cardWrap_Other = $('#modalDetailRack');
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=edit-data-rack',
            type: 'POST',
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: () => {
                cardWrap.addClass("modal-progress");
            },
            success: (res) => {
                // console.log(res);
                notifToast(res.status, res.message);
                if (res.status === 'success') {
                    cardWrap.modal('hide');
                    cardWrap_Other.modal('hide');
                    tabelStok.ajax.reload();
                    $("#modalRack").find('#totalStok').text(res.total_stok).trigger('change');
                    $("#modalRack").find('#totalMasuk').text(res.total_masuk).trigger('change');
                    $("#modalRack").find('#jumlahDalamRak'+res.rack_id).html(res.total_dalam_rak+' pcs').trigger('change');
                    $('.counter').each(function () {
                        $(this).prop('Counter', 0).animate({
                            Counter: $(this).text()
                        }, {
                            duration: 3000,
                            easing: 'swing',
                            step: function (now) {
                                $(this).text(Math.ceil(now));
                            }
                        });
                    });
                }
            },
            error: (err) => {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    editRack.showErrors(err);
                }
                notifToast("error", err.statusText);
                cardWrap.removeClass("modal-progress");
            },
            complete: () => {
                cardWrap.removeClass("modal-progress");

            }
        });
    }
    $('#modalEditRack #fm_EditRack').on('submit', function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                title: 'Apakah perubahan sudah benar?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxEditRak($(this));
                }
            });
        }
    });
    function ajaxDeleteRak(element,nama_rak, id, rack_id, stok_id) {
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=delete-data-rack',
            type: 'POST',
            data: {
                id: id,
                nama_rak: nama_rak,
                rack_id: rack_id,
                stok_id: stok_id
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
                    $('#modalDetailRack').find('#sectionTitle').html("Rack (" + nama_rak + ") " + res.popover).trigger('change');
                    $('[data-toggle="popover"]').popover();
                    var i = 0;
                    //remove post on table
                    element.closest("tr").remove();
                    // $(`#modalTrackProduksi #index_`+id).remove();
                    $('#tableRackDetail').find('tbody tr').each(function (index) {
                        // console.log(index);
                        //change id of first tr
                        $(this).find("td:eq(0)").attr("id", "row_num" + (index + 1))
                        //change hidden input value
                        $(this).find("td:eq(0)").html((index + 1) + '<input type="hidden" name="task_number[]" value=' + (index + 1) + '>')
                    });
                    i--;
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
    $('#modalDetailRack').on('click', '#btnDeleteRack', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let nama_rak = $(this).data('nama_rak');
        let rack_id = $(this).data('rack_id');
        let stok_id = $(this).data('stok_id');
        swal({
            title: 'Apakah yakin ingin menghapus?',
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteRak($(this),nama_rak, id,rack_id,stok_id);
            }
        });
    });
    $('#modalAktivitasRak').on('shown.bs.modal',function(e) {
        e.preventDefault();
        if ($.fn.DataTable.isDataTable('#tb_aktivitasRak')) {
            $('#tb_aktivitasRak').DataTable().destroy();
        }
        $('#sectionTitleActivity').text($('#sectionTitle').text());
        let stok_id = $('[name="stok_id"]').val();
        var start_date;
        var end_date;
        var DateFilterFunction = (function (oSettings, aData, iDataIndex) {
            var dateStart = parseDateValue(start_date);
            var dateEnd = parseDateValue(end_date);
            //Kolom tanggal yang akan kita gunakan berada dalam urutan 1, karena dihitung mulai dari 0
            var evalDate= parseDateValue(aData[1]);
              if ( ( isNaN( dateStart ) && isNaN( dateEnd ) ) ||
                   ( isNaN( dateStart ) && evalDate <= dateEnd ) ||
                   ( dateStart <= evalDate && isNaN( dateEnd ) ) ||
                   ( dateStart <= evalDate && evalDate <= dateEnd ) )
              {
                  return true;
              }
              return false;
        });

        // fungsi untuk converting format tanggal dd/mm/yyyy menjadi format tanggal javascript menggunakan zona aktubrowser
        function parseDateValue(rawDate) {
            var dateArray= rawDate.split("/");
            var parsedDate= new Date(dateArray[2], parseInt(dateArray[1])-1, dateArray[0]);  // -1 because months are from 0 to 11
            return parsedDate;
        }
        let tabelStok = $('#tb_aktivitasRak').DataTable({
            "dom": "<'row'<'col-sm-4'l><'col-sm-5' <'datesearchbox'>><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "fnFooterCallback": function (row, data, start, end, display) {
                let api = this.api();

                total = api
                .column(7,{ page: 'current' })
                .data();

                totalKeseluruhan = api
                .column(7)
                .data();

                var tot = 0;
                for (var i = 0; i < total.length; i++) {
                    if (i === total.length - 1) {
                        // this is the last one
                        tot += total[i] * 1;
                    }
                }
                var totKes = 0;
                for (var i = 0; i < totalKeseluruhan.length; i++) {
                    if (i === totalKeseluruhan.length - 1) {
                        // this is the last one
                        totKes += totalKeseluruhan[i] * 1;
                    }
                }
                api.column(0).footer().innerHTML = 'Total:';
                // Update footer
                api.column(7).footer().innerHTML =
                     tot + ' (' + totKes + ' total keseluruhan)';
            },
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
                url: window.location.origin + "/penjualan-stok/gudang/stok-buku/andi?request_type=table-riwayat-aktivitas-rak",
                data: {
                    stok_id:stok_id
                }
            },
            columns: [
                { data: 'no', name: 'no', title: 'No' },
                { data: 'created_at', name: 'created_at', title: 'Tanggal' },
                { data: 'rack_id', name: 'rack_id', title: 'Di Rak' },
                { data: 'type_history', name: 'type_history', title: 'Uraian' },
                { data: 'type_activity', name: 'type_activity', title: 'Keluar/Masuk' },
                { data: 'created_by', name: 'created_by', title: 'Otorisasi' },
                { data: 'qty', name: 'qty', title: 'Jumlah' },
                { data: 'sisa', name: 'sisa', title: 'Sisa' },
            ]
        });
        $("div.datesearchbox").html('<div class="input-group"> <div class="input-group-addon"> <i class="glyphicon glyphicon-calendar"></i> </div><input type="text" class="form-control pull-right" id="datesearch" placeholder="Filter by date range.."> </div>');

        document.getElementsByClassName("datesearchbox")[0].style.textAlign = "right";

        //konfigurasi daterangepicker pada input dengan id datesearch
        $('#datesearch').daterangepicker({
            autoUpdateInput: false
            });

        //menangani proses saat apply date range
        $('#datesearch').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        start_date=picker.startDate.format('DD/MM/YYYY');
        end_date=picker.endDate.format('DD/MM/YYYY');
        $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
            tabelStok.draw();
        });

        $('#datesearch').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        start_date='';
        end_date='';
        $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            tabelStok.draw();
        });
        $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
            // console.log(helpPage);
            // console.log(message);
            notifToast("error", settings.jqXHR.statusText)
            if (settings && settings.jqXHR && settings.jqXHR.status == 401) {
                window.location.reload();
            }
        };
    });
    $('#md_PindahRak').on('shown.bs.modal', function (e) {
        e.preventDefault();
        let rack_data_id = $(e.relatedTarget).data('rack_data_id'),
            stok_id = $(e.relatedTarget).data('stok_id'),
            cardWrap = $("#md_PindahRak");
        $.ajax({
            url: window.location.origin + "/penjualan-stok/gudang/stok-buku/andi?request_type=show-modal-rack-move",
            type: 'GET',
            data: {
                rack_data_id:rack_data_id,
                stok_id:stok_id,
            },
            cache: false,
            success: (res) => {
                cardWrap.find('#sectionTitle').text("Rak saat ini (" + res.nama + ")").trigger('change');
                cardWrap.find('[name="stok_id"]').val(res.stok_id).trigger('change');
                cardWrap.find('[name="current_rack_id"]').val(res.rack_id).trigger('change');
                cardWrap.find('[name="rack_data_id"]').val(res.id).trigger('change');
                $('.select-rack-move').select2({
                    placeholder: 'Pilih rak',
                    ajax: {
                        url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=select-rack-for-move&rack_id='+res.rack_id,
                        type: "GET",
                        data: function (params) {
                            var queryParameters = {
                                term: params.term
                            };
                            return queryParameters;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.nama,
                                        id: item.id,
                                    };
                                }),
                            };
                        },
                    },
                }).on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                    }
                });

            },
            error: (err) => {
                notifToast('error',err.statusText);
                cardWrap.removeClass('modal-progress');
            },
            complete: () => {
                cardWrap.removeClass('modal-progress');
                $('.datepicker-pindah').datepicker({
                    format: 'dd MM yyyy',
                    endDate: new Date(),
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    container: '#md_PindahRak .modal-content'
                }).on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                    }
                });
            }
        });
    });
    let pindahRak = jqueryValidation_('#fm_PindahRak', {
        rack_id: {
            required: true
        },
        jml_stok_rak: {
            required: true,
            number: true
        },
        tgl_pindah: {
            required: true
        },
        "users_id[]": {
            required: true
        }
    });
    $("#md_PindahRak").on("hidden.bs.modal", function (e) {
        $(this).addClass("modal-progress");
        pindahRak.resetForm();
        $(this).find("form").trigger("reset");
    });
    $('#md_HistoryRack').on('shown.bs.modal', function (e) {
        e.preventDefault();
        let rack_data_id = $(e.relatedTarget).data('rack_data_id'),
            stok_id = $(e.relatedTarget).data('stok_id'),
            cardWrap = $("#md_HistoryRack");
        $.ajax({
            url: window.location.origin + "/penjualan-stok/gudang/stok-buku/andi?request_type=show-modal-rack-history",
            type: 'GET',
            data: {
                rack_data_id:rack_data_id,
                stok_id:stok_id,
            },
            cache: false,
            success: (res) => {
                // console.log(res);
                $("#titleModalDetailHistoryRak").html(
                    '<i class="fas fa-history"></i>&nbsp;History Rack "' +
                        res.title +
                        '"'
                );
                if (res.status === 'error') {
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                } else {
                    $("#load_more").data("id", res.rack_data_id);
                }
                $("#dataHistoryDetailRak").html(res.html);
            },
            error: (err) => {
                notifToast('error',err.statusText);
                cardWrap.removeClass('modal-progress');
            },
            complete: () => {
                cardWrap.removeClass('modal-progress');

            }
        });
    });
    $("#md_HistoryRack").on("hidden.bs.modal", function (e) {
        $(this).addClass("modal-progress");
        $(".load-more").data("paginate", 2);
        $(".load-more").attr("disabled", false).css("cursor", "pointer");
    });
    $(".load-more").click(function (e) {
        e.preventDefault();
        var page = $(this).data("paginate");
        var id = $(this).data("id");
        let form = $("#md_HistoryRack");
        $(this).data("paginate", page + 1);
        $.ajax({
            url:
                window.location.origin + "/penjualan-stok/gudang/stok-buku/andi?request_type=show-modal-rack-history",
            data: {
                rack_data_id: id,
                page: page,
            },
            type: "GET",
            beforeSend: function () {
                form.addClass("modal-progress");
            },
            success: function (response) {
                // console.log(response.html.length);
                if (response.html.length == 0) {
                    $(".load-more").attr("disabled", true).css("cursor", "not-allowed");
                    notifToast("error", "Tidak ada data lagi");
                } else {
                    $("#dataHistoryDetailRak").append(response.html);
                    $('.thin').animate({scrollTop: $('.modal-body').prop("scrollHeight")}, 800);
                }
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
    function ajaxPindahRak(data) {
        let datapost = new FormData(data.get(0)),
            cardWrap = $('#md_PindahRak'),
            cardWrapParent = $('#modalDetailRack');

        $.ajax({
            url: window.location.origin + "/penjualan-stok/gudang/stok-buku/andi?request_type=pindah-rack",
            type: 'POST',
            data: datapost,
            processData: false,
            contentType: false,
            beforeSend: () => {
                cardWrap.addClass("modal-progress");
            },
            success: (res) => {
                // console.log(res);
                notifToast(res.status,res.message);
                if (res.status == 'success') {
                    location.reload();
                }
            },
            error: (err) => {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    pindahRak.showErrors(err);
                }
                notifToast("error", err.statusText);
                cardWrap.removeClass("modal-progress");
            },
            complete: () => {
                cardWrap.removeClass("modal-progress");

            }
        });
    }
    $("#md_PindahRak #fm_PindahRak").submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                title: 'Apakah tujuan pemindahan rak sudah benar?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxPindahRak($(this));
                }
            });
        }
    });
    $('#modalPermohonanRekondisi').on('shown.bs.modal',function(e) {
        e.preventDefault();
        // console.log(e);
        let stok_id = $('[name="stok_id"]').val(),
        total_stok = $('[name="total_stok"]').val(),
        cardWrap = $("#modalPermohonanRekondisi");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=modal-permohonan-rekondisi',
            type: 'GET',
            data: {
                stok_id:stok_id,
                total_stok:total_stok,
            },
            cache: false,
            success: (res) => {
                $('#contentPermohonan').html(res.modal);
                if (res.exist === true) {
                    Object.entries(res.data).forEach((entry) => {
                        let [key, value] = entry;
                        $('#check'+value.rack_id).change(function(e) {
                            if (this.checked)
                                showDetail(this.value,$(this).data('name'),value.total_diletakkan);
                            else
                                just_hide(this.value);
                        });
                    });
                } else {
                    $(".submit-permohonan").attr("disabled", true).css("cursor", "not-allowed");
                }
            },
            error: (err) => {
                notifToast('error',err.statusText)
                cardWrap.removeClass('modal-progress');
            },
            complete: () => {
                cardWrap.removeClass('modal-progress');
            }
        });
    });
    $("#modalPermohonanRekondisi").on("hidden.bs.modal", function (e) {
        $(this).addClass("modal-progress");
        $(".submit-permohonan").attr("disabled", false).css("cursor", "pointer");
    });
    function showDetail(ele,title,total) {
        $('#inputRakRekondisi').append(`<input type="number" class="form-control mb-1 input`+ele+`" min="1" name="jml_rekondisi[]" placeholder="Rak `+title+`">
        <input type="hidden" name="rekondisi_rak_id[]" class="input`+ele+`" value="`+ele+`">
        <input type="hidden" name="nama_rak[]" class="input`+ele+`" value="`+title+`">
        <input type="hidden" name="total_dalam_rak[]" class="input`+ele+`" value="`+total+`">
        `);
    }

    function just_hide(ele) {
        $('.input'+ele).remove();
    }
    let permohonanRek = jqueryValidation_('#fm_PermohonanRekondisi', {
        "rack_id[]": {
            required: true,
        },
        "jml_rekondisi[]": {
            required: true,
            number: true
        }
    });
    function ajaxPermohonanRekondisi(data) {
        let el = data.get(0),
            cardWrap = $('#modalPermohonanRekondisi');
        $.ajax({
            url: window.location.origin + "/penjualan-stok/gudang/stok-buku/andi?request_type=permohonan-rekondisi",
            type: 'POST',
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: () => {
                cardWrap.addClass("modal-progress");
            },
            success: (res) => {
                console.log(res);
                notifToast(res.status, res.message);
                if (res.status === 'success') {
                    location.reload();
                }
            },
            error: (err) => {
                console.log(err);
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach((entry) => {
                        let [key, value] = entry;
                        err[key] = value;
                    });
                    permohonanRek.showErrors(err);
                }
                notifToast("error", err.statusText);
                cardWrap.removeClass("modal-progress");
            },
            complete: () => {
                cardWrap.removeClass("modal-progress");

            }
        });
    }
    $('#modalPermohonanRekondisi #fm_PermohonanRekondisi').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            $("#errTextRack").addClass('d-none');
            swal({
                title: 'Apakah rak dan jumlah buku yang akan direkondisi sudah sesuai?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxPermohonanRekondisi($(this));
                }
            });
        } else {
            $("#errTextRack").text('Rak wajib dipilih!').fadeIn("slow");
        }
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
                    <select class="form-control select-rack" name="rak[]" required></select>
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light text-dark" id="">Jumlah</span>
                    </div>
                    <input type="text" class="form-control" name="jml_stok[]" placeholder="Jumlah yang dimasukkan" required>
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light text-dark" id="">Masuk Gudang</span>
                    </div>
                    <input type="text" class="form-control datepicker" name="tgl_masuk_stok[]"
                        placeholder="DD/MM/YYYY" required>
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light text-dark" id="">Oleh</span>
                    </div>
                    <select class="form-control select-optgudang" name="users_id[` + x + `][]" multiple="multiple" required></select>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-danger remove_field text-danger"
                            data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                    </div>
                </div>`
                ); //add input box
                $('.datepicker').datepicker({
                    format: 'dd MM yyyy',
                    endDate: new Date(),
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    container: '#modalRack .modal-body'
                });
                $('.select-rack').select2({
                    placeholder: 'Pilih rak',
                    ajax: {
                        url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=select-rack',
                        type: "GET",
                        data: function (params) {
                            var queryParameters = {
                                term: params.term
                            };
                            return queryParameters;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.nama,
                                        id: item.id,
                                    };
                                }),
                            };
                        },
                    },
                }).on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                    }
                });
                $('.select-optgudang').select2({
                    placeholder: 'Pilih operator gudang',
                    ajax: {
                        url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=select-operator-gudang',
                        type: "GET",
                        data: function (params) {
                            var queryParameters = {
                                term: params.term
                            };
                            return queryParameters;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.nama,
                                        id: item.id,
                                    };
                                }),
                            };
                        },
                    },
                }).on("change", function (e) {
                    if (this.value) {
                        $(this).valid();
                    }
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
