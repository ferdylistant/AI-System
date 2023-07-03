$(function() {
    // Initialize
    let pn1_Prodev, pn2_EditSet, pn3_Pemasaran, pn4_Penerbitan, pn5_Direksi, pn6_DPemasaran, f_tl, f_dtl;
    // First load form penilaian
    loadPenilaian($('#detailPenilaianCollapseCard #form_penilaian .nav-link.active').data('penilaian'),
        $('#detailPenilaianCollapseCard #form_penilaian .nav-link.active').data('naskahid'));
    loadTimeline($('#timeline').data('id'));

    // Each change tab form penilaian
    $(document).on('show.bs.tab', function(e) {
        let tab = $(e.delegateTarget.activeElement).data('penilaian');
        let naskahid = $(e.delegateTarget.activeElement).data('naskahid');
        loadPenilaian(tab, naskahid);
    });

    function reinitialSelect2() {
        $(".select2").select2({
            placeholder: 'Pilih',
            minimumResultsForSearch: Infinity
        }).on('change', function(e) {
            if (this.value) {
                $(this).valid();
            }
        });

        $(".select2ws").select2({
            placeholder: 'Pilih',
        }).on('change', function(e) {
            if (this.value) {
                $(this).valid();
            }
        });
    }
    // Function to load form penilaian
    function loadPenilaian(tab, naskahid) {
        if (tab) {
            $.ajax({
                type: "POST",
                url: window.location.origin + "/penerbitan/naskah/penilaian/form-" + tab.toLowerCase(),
                data: {
                    naskah_id: naskahid
                },
                beforeSend: function() {
                    // $("#"+tab).attr('hidden',false);
                    new Loader(tab).render([
                        // avatar shape: round, line, drawline
                        // ['line'],
                        // number of text lines
                        ['line*3',
                        {
                            // styles
                            style: [{
                            borderRadius: "5px",
                            width: "100%"
                            }]
                        }]
                        ],
                    );
                    // $("#detailPenilaianCollapseCard #form_penilaian #"+tab).fadeIn(300);
                },
                success: function(result) {
                    // console.log(tab)
                    $('#pn_' + tab).empty();
                    $('#pn_' + tab).append(result)
                    reinitialSelect2();
                    if (tab.toLowerCase() == 'prodev') {
                        pn1_Prodev = jqueryValidation_('#fpn1', {
                            pn1_file_tambahan: {
                                extension: "rar|zip",
                                maxsize: 500000,
                            }
                        });
                    } else if (tab.toLowerCase() == 'editset') {
                        pn2_EditSet = jqueryValidation_('#fpn2');
                    } else if (tab.toLowerCase() == 'mpemasaran') {
                        pn3_Pemasaran = jqueryValidation_('#fpn3');
                    } else if (tab.toLowerCase() == 'mpenerbitan') {
                        pn4_Penerbitan = jqueryValidation_('#fpn4');
                    } else if (tab.toLowerCase() == 'direksi') {
                        pn5_Direksi = jqueryValidation_('#fpn5');
                    } else if (tab.toLowerCase() == 'dpemasaran') {
                        pn6_DPemasaran = jqueryValidation_('#fpn6');
                    }
                },
                error: function(err) {
                    // console.log(err);
                    notifToast('error', 'Terjadi Kesalahan Form Penilaian Gagal Dibuka!');
                },
                // complete: function() {
                //     $('#detailPenilaianCollapseCard #form_penilaian').parent().removeClass('card-progress')
                // }
            }).done(function () {
                setTimeout(function () {
                        $("#detailPenilaianCollapseCard #form_penilaian #"+tab).fadeOut(300);
                }, 500);
            });
        }

    }

    function loadTimeline(id) {
        if (id) {
            $.ajax({
                type: "POST",
                url: "{{ url('penerbitan/naskah/timeline/content-subtimeline') }}",
                data: {
                    id: id
                },
                beforeSend: function() {
                    $('#timeline').children().addClass('card-progress')
                },
                success: function(result) {
                    $('#timeline .card-body').empty();
                    $('#timeline .card-body').append(result);

                },
                error: function(err) {
                    notifToast('error', 'Terjadi Kesalahan Form Penilaian Gagal Dibuka!');
                },
                complete: function() {
                    $('#timeline').children().removeClass('card-progress')
                }
            });
        }
    }

    // Submit Penilaian Prodev
    function ajaxPenProdev(data,param) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/naskah/penilaian/prodev?simpan="+param,
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                addClass('btn-progress')
            },
            success: function(result) {
                // console.log(result)
                notifToast(result.status, result.message);
                if (result.status == 'success') {
                    location.reload();
                }
            },
            error: function(err) {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    pn1_Prodev.showErrors(err);
                }
                notifToast('error', 'Penilaian gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                removeClass('btn-progress')
            }
        });
    }
    $('#form_penilaian').on('submit', '#fpn1', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                    text: 'Simpan Penilaian Naskah?',
                    icon: 'warning',
                    buttons: {
                        cancel: true,
                        draf: {
                            text: "Simpan Sebagai Draf",
                            value: "draf",
                        },
                        confirm: "Selesai",
                    },
                    dangerMode: true,
                })
                .then((confirm_) => {
                    if (confirm_) {
                        // console.log(confirm_);
                        switch (confirm_) {
                            case "draf":
                                ajaxPenProdev($(this),'draf')
                                break;

                            case true:
                                ajaxPenProdev($(this),'done')
                                break;

                        }
                    }
                    // console.log(confirm_);
                });

        }

        function createButton(text, cb) {
            return $('<button>' + text + '</button>').on('click', cb);
        }
    });
    // Submit Penilaian Editor/Setter
    function ajaxPenEditSet(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/naskah/penilaian/editset",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                addClass('btn-progress')
            },
            success: function(result) {
                notifToast('success', 'Data naskah berhasil disimpan!', true);
            },
            error: function(err) {
                // console.log(err.responseJSON)
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    // console.log(err)
                    pn2_EditSet.showErrors(err);
                }
                notifToast('error', 'Data naskah gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                removeClass('btn-progress')
            }
        });
    }
    $('#form_penilaian').on('submit', '#fpn2', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                    text: 'Simpan Penilaian Naskah?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm_) => {
                    if (confirm_) {
                        ajaxPenEditSet($(this))
                    }
                });

        }
    });
    // Submit Penilaian Manager Pemasaran
    function ajaxPenPemasaran(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/naskah/penilaian/mpemasaran",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                addClass('btn-progress')
            },
            success: function(result) {
                notifToast('success', 'Penilaian berhasil disimpan!', true);
            },
            error: function(err) {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    pn3_Pemasaran.showErrors(err);
                }
                notifToast('error', 'Penilaian gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                removeClass('btn-progress')
            }
        });
    }
    $('#form_penilaian').on('submit', '#fpn3', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                    text: 'Simpan Penilaian Naskah?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm_) => {
                    if (confirm_) {
                        ajaxPenPemasaran($(this))
                    }
                });
        }
    });
    // Submit Penilaian Penerbitan
    function ajaxPenPenerbitan(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/naskah/penilaian/penerbitan",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                addClass('btn-progress')
            },
            success: function(result) {
                // console.log(result);
                notifToast('success', 'Penilaian berhasil disimpan!', true);
            },
            error: function(err) {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    pn4_Penerbitan.showErrors(err);
                }
                notifToast('error', 'Penilaian gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                removeClass('btn-progress')
            }
        });
    }
    $('#form_penilaian').on('submit', '#fpn4', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                    text: 'Simpan Penilaian Naskah?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm_) => {
                    if (confirm_) {
                        ajaxPenPenerbitan($(this))
                    }
                });
        }
    });
    // Submit Penilaian Direksi
    function ajaxPenDireksi(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/naskah/penilaian/direksi",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                addClass('btn-progress')
            },
            success: function(result) {
                notifToast('success', 'Penilaian berhasil disimpan!', true);
            },
            error: function(err) {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    pn5_Direksi.showErrors(err);
                }
                notifToast('error', 'Penilaian gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                removeClass('btn-progress')
            }
        });
    }
    $('#form_penilaian').on('submit', '#fpn5', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                    text: 'Simpan Penilaian Naskah?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm_) => {
                    if (confirm_) {
                        ajaxPenDireksi($(this))
                    }
                });
        }
    });
    // Submit Penilaian Direktur Pemasaran
    function ajaxPenDPemasaran(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: window.location.origin + "/penerbitan/naskah/penilaian/dpemasaran",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                addClass('btn-progress')
            },
            success: function(result) {
                notifToast('success', 'Penilaian berhasil disimpan!', true);
            },
            error: function(err) {
                rs = err.responseJSON.errors;
                if (rs != undefined) {
                    err = {};
                    Object.entries(rs).forEach(entry => {
                        let [key, value] = entry;
                        err[key] = value
                    })
                    pn6_DPemasaran.showErrors(err);
                }
                notifToast('error', 'Penilaian gagal disimpan!');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).
                removeClass('btn-progress')
            }
        })
    }
    $('#form_penilaian').on('submit', '#fpn6', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            swal({
                    text: 'Simpan Penilaian Naskah?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((confirm_) => {
                    if (confirm_) {
                        ajaxPenDPemasaran($(this))
                    }
                });
        }
    });
});
