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
                console.log(res);
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
            nama_rak = $(e.relatedTarget).data('nama_rak'),
            cardWrap = $("#modalDetailRack");
        $.ajax({
            url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=show-modal-rack-detail',
            type: 'GET',
            data: {
                rack_id: rack_id,
                stok_id: stok_id,
                nama_rak: nama_rak
            },
            cache: false,
            success: function (result) {
                cardWrap.find('#sectionTitle').html("Rack (" + nama_rak + ") " + result.popover).trigger('change');
                cardWrap.find('#contentDetailRack').html(result.content).trigger('change');
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
    $(document).ready(function () {
        $('#btn_PermohonanRekondisi').click(function(e) {
            e.preventDefault();
            let stok_id = $('[name="stok_id"]').val();
            let total_stok = $('[name="total_stok"]').val();
            $.ajax({
                url: window.location.origin + '/penjualan-stok/gudang/stok-buku/andi?request_type=modal-permohonan-rekondisi',
                type: 'GET',
                data: {
                    stok_id:stok_id,
                    total_stok:total_stok,
                },
                cache: false,
                success: (res) => {
                    $('#showModalPermohonanRekondisi').html(res.modal);
                    $('#modalPermohonanRekondisi').modal('show')
                    if (res.exist === true) {
                        Object.entries(res.data).forEach((entry) => {
                            let [key, value] = entry;
                            $('#check'+value.rack_id).change(function(e) {
                                if (this.checked)
                                    showDetail(this.value,$(this).data('name'));
                                else
                                    just_hide(this.value);
                            });
                        });
                    }
                },
                error: (err) => {
                    notifToast('error',err.statusText)
                    $('#modalPermohonanRekondisi').removeClass("modal-progress");
                },
                complete: () => {
                    $('#modalPermohonanRekondisi').removeClass("modal-progress");
                }
            });
        });
    });
    function showDetail(ele,title) {
        $('#inputRakRekondisi').append(`<input type="text" class="form-control mb-1" name="jml_rekondisi[]" id="input`+ele+`" placeholder="Rak `+title+`">`);
    }

    function just_hide(ele) {
        $('#input'+ele).remove();
    }
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
