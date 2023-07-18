$(function () {
    let tableProduksi = $('#tb_prosesProduksi').DataTable({
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
            url: window.location.origin + "/produksi/proses/cetak",
        },
        columns: [
            // { data: 'no', name: 'no', title: 'No' },
            { data: 'kode_order', name: 'kode_order', title: 'No Order' },
            { data: 'kode', name: 'kode', title: 'Kode Naskah' },
            { data: 'naskah_dari_divisi', name: 'naskah_dari_divisi', title: 'Div' },
            { data: 'judul_final', name: 'judul_final', title: 'Judul Buku' },
            { data: 'status_cetak', name: 'status_cetak', title: 'Status Cetak' },
            { data: 'penulis', name: 'penulis', title: 'Penulis' },
            { data: 'edisi_cetak', name: 'edisi_cetak', title: 'Edisi Cetak' },
            { data: 'jenis_jilid', name: 'jenis_jilid', title: 'Jenis Jilid' },
            { data: 'buku_jadi', name: 'buku_jadi', title: 'Buku Jadi' },
            { data: 'tracking', name: 'tracking', title: 'Tracking', width: "100%", },
            { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false },
        ]
    });
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        notifToast("error", message)
        window.location.reload();
    };
    function loadSelected(idm, ido) {
        $.ajax({
            url: window.location.origin + "/produksi/proses/cetak/ajax/selected",
            type: 'GET',
            data: {
                id_mesin: idm,
                id_operator: ido,
            },
            cache: false,
            success: function (result) {
                $("#jenisMesin").select2("trigger", "select", {
                    data: {
                        id: result.mesin.id,
                        text: result.mesin.nama
                    }
                });
                Object.entries(result.operator).forEach((entry) => {
                    let [key, value] = entry;
                    $("#operatorId").select2("trigger", "select", {
                        data: {
                            id: value.id,
                            text: value.nama
                        }
                    })
                });
            }
        });
    }
    $('#modalTrackProduksi').on('shown.bs.modal', function (e) {
        let id = $(e.relatedTarget).data('id'),
            type = $(e.relatedTarget).data('type'),
            status = $(e.relatedTarget).data('status'),
            tglmulai = $(e.relatedTarget).data('tglmulai'),
            tglselesai = $(e.relatedTarget).data('tglselesai'),
            cardWrap = $("#modalTrackProduksi");
        $.ajax({
            url: window.location.origin + '/produksi/proses/cetak?modal=true',
            type: 'GET',
            data: {
                id: id,
                type: type,
                status: status,
                tglmulai: tglmulai,
                tglselesai: tglselesai
            },
            cache: false,
            success: function (result) {
                cardWrap.find('#statusJob').attr('class', result.badge).trigger('change');
                cardWrap.find('#statusJob').text(result.status).trigger('change');
                cardWrap.find('#sectionTitle').html(result.sectionTitle).trigger('change');
                cardWrap.find('[name="produksi_id"]').val(result.data.id).trigger('change');
                cardWrap.find('[name="proses_tahap"]').val(result.proses_tahap).trigger('change');
                cardWrap.find('#judul_final').html('<i class="fas fa-tasks"></i> ' + result.data.judul_final.charAt(0).toUpperCase() + result.data.judul_final.slice(1)).trigger('change');
                cardWrap.find('#contentData').html(result.content).trigger('change');
                cardWrap.find('#footerModal').html(result.footer).trigger('change');
                $('[data-toggle="popover"]').popover();
                if (result.proses_tahap !== 'Kirim Gudang') {
                    if (result.trackData) {
                        loadSelected(result.trackData.mesin, result.trackData.operator);
                    }
                }
                if (result.proses_tahap === 'Kirim Gudang') {
                    var jmlDikirim = document.getElementById('jmlDikirim');
                    var maskjmlDikirim = {
                        mask: '0000000000000'
                    };
                    var mask = IMask(jmlDikirim, maskjmlDikirim, reverse = true);
                }
                $("#modalTrackProduksi").modal('show');
                $(".select-mesin")
                    .select2({
                        placeholder: "Pilih mesin\xa0\xa0",
                    })
                    .on("change", function (e) {
                        if (this.value) {
                            $(this).valid();
                        }
                    });
                $(".select-operator")
                    .select2({
                        placeholder: "Pilih operator\xa0\xa0",
                        multiple: true,
                    })
                    .on("change", function (e) {
                        if (this.value) {
                            $(this).valid();
                        }
                    });

                $(".select-status")
                    .select2({
                        placeholder: "Pilih status\xa0\xa0",
                    })
                    .on("change", function (e) {
                        if (this.value) {
                            $(this).valid();
                        }
                    });
                $("#jenisMesin")
                    .select2({
                        ajax: {
                            url: window.location.origin + "/produksi/proses/cetak/ajax/select",
                            type: "GET",
                            data: function (params) {
                                var queryParameters = {
                                    request_: "selectMesin",
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
                    });
                $("#operatorId")
                    .select2({
                        ajax: {
                            url: window.location.origin + "/produksi/proses/cetak/ajax/select",
                            type: "GET",
                            data: function (params) {
                                var queryParameters = {
                                    request_: "selectOperator",
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
                    });
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
    });
    $("#modalTrackProduksi").on("hidden.bs.modal", function (e) {
        // let d = $(e.relatedTarget).attr('id','statusJob');
        // console.log(d);
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
        $(this).find("#statusJob").removeAttr("class");
    });
    let addTrack = jqueryValidation_("#form_Tracking", {
        mesin: {
            required: true,
        },
        operator: {
            required: true,
        },
        status: {
            required: true,
        },
        jml_dikirim: {
            required: true,
        }
    });
    let editRiwayat = jqueryValidation_("#form_EditRiwayat", {
        edit_jml_dikirim: {
            required: true,
        }
    });
    //Submit Tracking
    function ajaxAddTrackData(data) {
        let el = data.get(0);
        $.ajax({
            url: window.location.origin + "/produksi/proses/cetak/ajax/submit-track",
            type: 'POST',
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type="submit"]')
                    .prop("disabled", true)
                    .addClass("btn-progress");
            },
            success: function (res) {
                // console.log(res);
                notifToast(res.status, res.message);
                if (res.status == 'success') {
                    tableProduksi.ajax.reload();
                    $("#modalTrackProduksi").modal("hide");
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
                    addTrack.showErrors(err);
                }
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
    $('#form_Tracking').on('submit', function (e) {
        e.preventDefault();
        let proses = $(this).find('[name="proses_tahap"]').val();
        if (proses === 'Kirim Gudang') {
            proses = 'jumlah kirim';
        } else {
            proses = 'mesin dan operator';
        }
        if ($(this).valid()) {
            swal({
                text: "Apakah data " + proses + " sudah benar?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxAddTrackData($(this));
                }
            });
        }
    });
    //Delete Riwayat Kirim
    function ajaxDeleteRiwayatKirim(element, id) {
        $.ajax({
            url: window.location.origin + "/produksi/proses/cetak/ajax/delete-riwayat-track",
            type: 'POST',
            data: {
                id: id
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
                    var i = 0;
                    //remove post on table
                    element.closest("tr").remove();
                    // $(`#modalTrackProduksi #index_`+id).remove();
                    $('#tableRiwayatKirim').find('tbody tr').each(function (index) {
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
    $('#form_Tracking').on('click', '#btnDeleteRiwayatKirim', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        swal({
            text: "Apakah Anda yakin ingin menghapus?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((confirm_) => {
            if (confirm_) {
                ajaxDeleteRiwayatKirim($(this), id);
            }
        });
    });
    //Edit Riwayat Kirim
    $('#modalEditRiwayatKirim').on('shown.bs.modal', function (e) {
        let jml = $(e.relatedTarget).data('jumlah'),
            cardWrap = $("#modalEditRiwayatKirim");
        $(this).find('[name="edit_jml_dikirim"]').val(jml).change();
        var jmlDikirim = document.getElementById('editJmlDikirim');
        var maskjmlDikirim = {
            mask: '0000000000000'
        };
        var mask = IMask(jmlDikirim, maskjmlDikirim, reverse = true);
        cardWrap.removeClass('modal-progress')
    });
    $("#modalEditRiwayatKirim").on("hidden.bs.modal", function (e) {
        // let d = $(e.relatedTarget).attr('id','statusJob');
        // console.log(d);
        $(this).find("form").trigger("reset");
        $(this).addClass("modal-progress");
        $(this).modal('hide');
    });
    $('#form_EditRiwayat').on('submit', function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                text: "Apakah Anda yakin ingin mengubah data?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxDeleteRiwayatKirim($(this));
                }
            });
        }
    });
})
