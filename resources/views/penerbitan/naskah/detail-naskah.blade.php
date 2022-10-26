@extends('layouts.app')
<?php
    /* #################### Catatan #####################
    keyword [
        pn1 = Penilaian Prodev,
        pn2 = Penilaian Editor/Setter (if requried)
        pn3 = Penilaian Pemasaran,
        pn4 = Penilaian Operasional
    ]
    ##################################################### */

    // $arrPilihan_ = ["Baik", "Cukup", "Kurang"];
?>
@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{url('vendors/flipbook/min_version/ipages.min.css')}}">
@endsection

@section('cssNeeded')
<style>
    .tb-naskah {
        font-size: 12px;
        border: 1px solid #ced4da;
    }
    .tb-naskah, .tb-naskah th, .tb-naskah td {
        height: auto !important;
        padding: 10px 15px 10px 15px !important;
    }
    .tb-naskah th {
        width: 35%;
        color: #868ba1;
        background-color: #E9ECEF;
    }
    .accordion-body {
        overflow: auto;
        max-height: 500px;
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ url('penerbitan/naskah') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Naskah</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Data Naskah</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table tb-naskah">
                            <tbody>
                                <tr>
                                    <th>Kode naskah:</th>
                                    <td>{{$naskah->kode}}</td>
                                </tr>
                                <tr>
                                    <th>Judul Asli:</th>
                                    <td>{{$naskah->judul_asli}}</td>
                                </tr>
                                <tr>
                                    <th>Naskah Masuk:</th>
                                    <td>{{$naskah->tanggal_masuk_naskah}}</td>
                                </tr>
                                <tr>
                                    <th>Kelompok Buku:</th>
                                    <td>{{$naskah->kelompok_buku}}</td>
                                </tr>
                                <tr>
                                    <th>Jalur Buku:</th>
                                    <td>{{$naskah->jalur_buku}}</td>
                                </tr>
                                <tr>
                                    <th>Tentang Penulis:</th>
                                    <td>{{$naskah->tentang_penulis}}</td>
                                </tr>
                                <tr>
                                    <th>Hard - Soft Copy:</th>
                                    <td>{{$naskah->hard_copy}} - {{$naskah->soft_copy}}</td>
                                </tr>
                                <tr>
                                    <th>Rencana CD/QR Code:</th>
                                    <td>{{$naskah->cdqr_code}}</td>
                                </tr>
                                <tr>
                                    <th>PIC Prodev</th>
                                    <td>
                                        <a href="{{url('manajemen-web/user/'.$naskah->pic_prodev)}}" target="_blank">
                                            {{$naskah->npic_prodev}}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Penulis</th>
                                    <td><ol style="padding-left: 15px !important;">
                                        @foreach($penulis as $p)
                                        <li><a href="{{url('penerbitan/penulis/detail-penulis/'.$p->id)}}" target="_blank">
                                        {{$p->nama}}</a></li>
                                        @endforeach
                                    </ol></td>
                                </tr>
                                <tr>
                                    <th>URL File</th>
                                    <td>
                                        @if($naskah->url_file)
                                        <a href="{{$naskah->url_file}}"
                                        class="text-primary" target="_blank">{{$naskah->url_file}}</a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($naskah->pic_prodev)
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h4>Penilaian Naskah</h4>
                    </div>
                    <div id="form_penilaian" class="card-body">
                    <ul class="nav nav-tabs"role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{$startPn=='guest'?'active':($startPn=='prodev'?'active':'')}}" id="prodev-tab" data-toggle="tab"
                            data-penilaian="Prodev" data-naskahid="{{$naskah->id}}" href="#pn_Prodev" role="tab" aria-controls="prodev">Prodev</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{in_array($startPn, ['editor', 'setter'])?'active':''}}" id="editset-tab" data-toggle="tab"
                            data-penilaian="EditSet" data-naskahid="{{$naskah->id}}" href="#pn_EditSet" role="tab" aria-controls="editset">Editor/Setter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$startPn=='mpenerbitan'?'active':''}}" id="mpenerbitan-tab" data-toggle="tab"
                            data-penilaian="mPenerbitan" data-naskahid="{{$naskah->id}}" href="#pn_mPenerbitan" role="tab" aria-controls="mpenerbitan">M.Penerbitan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$startPn=='mpemasaran'?'active':''}}" id="mpemasaran-tab" data-toggle="tab"
                            data-penilaian="mPemasaran" data-naskahid="{{$naskah->id}}" href="#pn_mPemasaran" role="tab" aria-controls="mpemasaran">M.Pemasaran</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$startPn=='dpemasaran'?'active':''}}" id="dpemasaran-tab" data-toggle="tab"
                            data-penilaian="dPemasaran" data-naskahid="{{$naskah->id}}" href="#pn_dPemasaran" role="tab" aria-controls="dpemasaran">D.Pemasaran</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{$startPn=='direksi'?'active':''}}" id="direksi-tab"data-toggle="tab"
                            data-penilaian="Direksi" data-naskahid="{{$naskah->id}}" href="#pn_Direksi" role="tab" aria-controls="direksi">Direksi</a>
                        </li>
                    </ul>

                    <div class="tab-content" style="max-height: 750px; overflow-y: auto; overflow-x: hidden;">
                        <div class="tab-pane fade {{$startPn=='guest'?'show active':($startPn=='prodev'?'show active':'')}}" id="pn_Prodev" role="tabpanel" aria-labelledby="prodev-tab"></div>
                        <div class="tab-pane fade {{in_array($startPn, ['editor', 'setter'])?'show active':''}}" id="pn_EditSet" role="tabpanel" aria-labelledby="editset-tab"></div>
                        <div class="tab-pane fade {{$startPn=='mpenerbitan'?'show active':''}}" id="pn_mPenerbitan" role="tabpanel" aria-labelledby="mpenerbitan-tab"></div>
                        <div class="tab-pane fade {{$startPn=='mpemasaran'?'show active':''}}" id="pn_mPemasaran" role="tabpanel" aria-labelledby="mpemasaran-tab"></div>
                        <div class="tab-pane fade {{$startPn=='dpemasaran'?'show active':''}}" id="pn_dPemasaran" role="tabpanel" aria-labelledby="dpemasaran-tab"></div>
                        <div class="tab-pane fade {{$startPn=='direksi'?'show active':''}}" id="pn_Direksi" role="tabpanel" aria-labelledby="direksi-tab"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>

<style>
    #md_updateSubtimeline table {
        width: 100%;
    }
    #md_updateSubtimeline table tr th {
        text-align: center;
    }
</style>



@endsection

@section('jsRequired')
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/additional-methods.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/pdf.min.js')}}"></script>
<script src="{{url('vendors/flipbook/min_version/jquery.ipages.min.js')}}"></script>
<script src="{{url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
<script src="{{url('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
@endsection

@section('jsNeeded')
<script>
$(function() {
    // Initialize
    let pn1_Prodev, pn2_EditSet, pn3_Pemasaran, pn4_Penerbitan, pn5_Direksi, pn6_DPemasaran, f_tl, f_dtl;

    // First load form penilaian
    loadPenilaian($('#form_penilaian .nav-link.active').data('penilaian'),
        $('#form_penilaian .nav-link.active').data('naskahid'));
    loadTimeline($('#timeline').data('id'));

    // Each change tab form penilaian
    $(document).on('show.bs.tab', function(e) {
        let tab = $(e.delegateTarget.activeElement).data('penilaian'),
            naskahid = $(e.delegateTarget.activeElement).data('naskahid');
        loadPenilaian(tab, naskahid);
    });

    $('#md_Timeline').on('show.bs.modal', function(e){
        let id = $(e.relatedTarget).data('id'), // idnaskah / idtimeline
            method = $(e.relatedTarget).data('method'); // add / edit

        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/timeline/timeline')}}",
            data: {request_: 'form', id: id, method_: method},
            success: function(result) {
                $('#md_Timeline .modal-content').empty();
                $('#md_Timeline .modal-content').append(result);
                f_tl = jqueryValidation_('#f_tl');
                reinitialDatePicker();
                // console.log(result)
            },
            error: function(err) {
                console.log('aneh')
                notifToast('error', 'Terjadi Kesalahan Form Penilaian Gagal Dibuka!');
            }
        })
    })

    $('#pn_Prodev').on('click', '.btn_addSaranJudul', function(e) {
        let el = $(e.currentTarget).closest('tr');
        $(e.currentTarget).removeClass('btn-success btn_addSaranJudul').addClass('btn-danger btn_rmvSaranJudul').text('Hapus?')
        el.after('<tr><td><input type="text" class="form-control" name="pn1_usulan_judul[]" required></td>'+
                    '<td><div class="btn btn-sm btn-success btn_addSaranJudul">Tambah</div></td></tr>');
    })
    $('#pn_Prodev').on('click', '.btn_rmvSaranJudul', function(e) {
        let el = $(e.currentTarget).closest('tr').remove();
    })
    $('#md_updateSubtimeline').on('show.bs.modal', function(e) {
        $(e.currentTarget).find('[name="stl_idtl"]').val($(e.relatedTarget).data('id'));
        $(e.currentTarget).find('[name="stl_bagian"]').val($(e.relatedTarget).data('bagian'));
        reinitialSelect2TL();
    })
    $('#md_updateSubtimeline').on('click', '.btn-add-subtl', function(e) {
        e.preventDefault();
        let el = $(e.currentTarget).closest('tr');
        el.after(`<tr>
                    <td>
                        <select class="form-control stl_pic" name="stl_pic[]"></select>
                    </td>
                    <td><input type="text" class="form-control" name="stl_proses[]" placeholder="Proses"></td>
                    <td><input type="number" class="form-control" name="stl_ttl_hari[]" placeholder="Hari"></td>
                    <td>
                        <button class="btn-add-subtl btn btn-sm btn-success">+</button>
                        <button class="btn-rmv-subtl btn btn-sm btn-danger">-</button>
                    </td>
                </tr>`);
        reinitialSelect2TL();
        console.log('tambah subtl');
    })
    $('#md_updateSubtimeline').on('click', '.btn-rmv-subtl', function(e) {
        e.preventDefault();
        let el = $(e.currentTarget).closest('tr').remove();
    })

    function reinitialSelect2() {
        $(".select2").select2({
            placeholder: 'Pilih',
            minimumResultsForSearch: Infinity
        }).on('change', function(e) {
            if(this.value) {
                $(this).valid();
            }
        });

        $(".select2ws").select2({
            placeholder: 'Pilih',
        }).on('change', function(e) {
            if(this.value) {
                $(this).valid();
            }
        });
    }
    function reinitialSelect2TL() {
        $('.stl_pic').select2({
            placeholder: 'PIC',
            dropdownParent: $('#md_updateSubtimeline'),
            minimumInputLength: 2,
            minimumResultsForSearch: 10,
            ajax: {
                url: "{{url('penerbitan/naskah')}}",
                type: "GET",
                delay: 650,
                data: function (params) {
                    var queryParameters = {
                        request_: 'getPICTimeline',
                        term: params.term
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                    text: item.nama,
                                    id: item.id
                                }
                        })
                    };
                }
            }
        });
    }
    function reinitialDatePicker() {
        $('.datepicker').datepicker({
            format: 'dd MM yyyy',
        }).on('show.bs.modal', function(event) {
            event.stopPropagation();
        });
    }

    $('.ipgs-flipbook').ipages({
        toolbarControls: [
            {type:'share',        active:false},
            {type:'sound',        active:false, optional: false},
            {type:'outline',      active:false},
            {type:'thumbnails',   active:true},
            {type:'gotofirst',    active:true},
            {type:'prev',         active:true},
            {type:'pagenumber',   active:true},
            {type:'next',         active:true},
            {type:'gotolast',     active:true},
            {type:'zoom-in',      active:false},
            {type:'zoom-out',     active:false},
            {type:'zoom-default', active:false},
            {type:'optional',     active:false},
            {type:'download',     active:false, optional: false},
            {type:'fullscreen',   active:true, optional: false},
        ],
    });

    // Function to load form penilaian
    function loadPenilaian(tab, naskahid) {
        if(tab) {
            $.ajax({
                type: "POST",
                url: "{{url('penerbitan/naskah/penilaian/form-')}}"+tab.toLowerCase(),
                data: {naskah_id: naskahid},
                beforeSend: function() {
                    $('#form_penilaian').parent().addClass('card-progress')
                },
                success: function(result) {
                    // console.log(tab)
                    $('#pn_'+tab).empty();
                    $('#pn_'+tab).append(result)
                    reinitialSelect2();
                    if(tab.toLowerCase() == 'prodev') {
                        pn1_Prodev = jqueryValidation_('#fpn1', {
                            pn1_file_tambahan: { extension: "rar|zip", maxsize: 500000, }
                        });
                    } else if(tab.toLowerCase() == 'editset') {
                        pn2_EditSet = jqueryValidation_('#fpn2');
                    } else if(tab.toLowerCase() == 'mpemasaran') {
                        pn3_Pemasaran = jqueryValidation_('#fpn3');
                    } else if(tab.toLowerCase() == 'mpenerbitan') {
                        pn4_Penerbitan = jqueryValidation_('#fpn4');
                    } else if(tab.toLowerCase() == 'direksi') {
                        pn5_Direksi = jqueryValidation_('#fpn5');
                    } else if(tab.toLowerCase() == 'dpemasaran') {
                        pn6_DPemasaran = jqueryValidation_('#fpn6');
                    }
                },
                error: function(err) {
                    // console.log(err)
                    notifToast('error', 'Terjadi Kesalahan Form Penilaian Gagal Dibuka!');
                },
                complete: function() {
                    $('#form_penilaian').parent().removeClass('card-progress')
                }
            })
        }

    }

    function loadTimeline(id) {
        if(id) {
            $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/timeline/content-subtimeline')}}",
            data: {id: id},
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
        })
        }
    }

    // Submit Penilaian Prodev
    function ajaxPenProdev(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/penilaian/prodev')}}",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                // console.log(result)
                notifToast('success', 'Penilaian berhasil disimpan!', true);
            },
            error: function(err) {
                rs = err.responseJSON.errors;
                if(rs != undefined) {
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
        })
    }
    $('#form_penilaian').on('submit', '#fpn1', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            swal({
                text: 'Simpan Penilaian Naskah?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxPenProdev($(this))
                }
            });

        }
    })
    // Submit Penilaian Editor/Setter
    function ajaxPenEditSet(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/penilaian/editset')}}",
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
                if(rs != undefined) {
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
        })
    }
    $('#form_penilaian').on('submit', '#fpn2', function(e) {
        e.preventDefault();
        if($(this).valid()) {
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
    })
    // Submit Penilaian Manager Pemasaran
    function ajaxPenPemasaran(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/penilaian/mpemasaran')}}",
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
                if(rs != undefined) {
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
        })
    }
    $('#form_penilaian').on('submit', '#fpn3', function(e) {
        e.preventDefault();
        if($(this).valid()) {
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
    })
    // Submit Penilaian Penerbitan
    function ajaxPenPenerbitan(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/penilaian/penerbitan')}}",
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
                if(rs != undefined) {
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
        })
    }
    $('#form_penilaian').on('submit', '#fpn4', function(e) {
        e.preventDefault();
        if($(this).valid()) {
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
    })
    // Submit Penilaian Direksi
    function ajaxPenDireksi(data) {
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/penilaian/direksi')}}",
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
                if(rs != undefined) {
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
        })
    }
    $('#form_penilaian').on('submit', '#fpn5', function(e) {
        e.preventDefault();
        if($(this).valid()) {
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
    })
    // Submit Penilaian Direktur Pemasaran
    function ajaxPenDPemasaran(data){
        let el = data.get(0);
        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/penilaian/dpemasaran')}}",
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
                if(rs != undefined) {
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
    $('#form_penilaian').on('submit', '#fpn6', function(e){
        e.preventDefault();
        if($(this).valid()) {
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
    })



    function ajaxSubmitTimeline(data) {
        let el = data.get(0),
            method_ = data.find('[name="method_"]').val();

        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/timeline/timeline')}}",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(el).find('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                console.log(result)
                // if(method_=='add') {
                //     $('#md_Timeline').modal('hide');
                //     notifToast('success', 'Timeline berhasil disimpan!', true);
                // } else {
                //     notifToast('success', 'Timeline berhasil disimpan!');
                // }
            },
            error: function(err) {
                notifToast('error', 'Timeline gagal disimpan!');
            },
            complete: function() {
                $(el).find('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }
    $('#md_Timeline').on('submit', '#f_tl', function(e) {
        e.preventDefault();
        let textConfirm = $(e.currentTarget).find('[name="method_"]').val()=='add'?
                            'Buat Timeline Naskah?' : 'Ubah Timeline Naskah?';
        if($(this).valid()) {
            swal({
                text: textConfirm,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxSubmitTimeline($(this))
                }
            });
        }
    })

    function ajaxSubmitDateTimeline(data){
        let el = data.get(0),
            method_ = data.find('[name="method_"]').val();

        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/timeline/date-timeline')}}",
            data: new FormData(el),
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(el).find('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
            },
            success: function(result) {
                console.log(result)
                notifToast('success', 'Timeline berhasil disimpan!', method_=='add'?true:false);
            },
            error: function(err) {
                console.log(err.responseJSON.message)
                notifToast('error', 'Timeline gagal disimpan!');
            },
            complete: function() {
                $(el).find('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
            }
        })
    }
    $('#md_DateTimeline').on('submit', '#f_dtl', function(e) {
        e.preventDefault();
        if($(this).valid()) {
            swal({
                text: 'Update Timeline Naskah?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxSubmitDateTimeline($(this))
                }
            });
        }
    })

    // Newwwww 22 04 2022

    function ajaxSubmitSubTl(data) {
        $.ajax({
            type: "POST",
            url: "{{url('penerbitan/naskah/timeline/updateSubTimeline')}}",
            data: new FormData(data),
            success: function(result) {
                if(result>0){
                    notifToast('success', 'Data berhasil disimpan!', true);
                }else{
                    notifToast('error', 'Sub-Timeline gagal disimpan!');
                }
                console.log(result)

            },
            error: function(err) {
                notifToast('error', 'Sub-Timeline gagal disimpan!');
            },
        })
    }
    $('#md_updateSubtimeline').on('submit', '#f_subtl', function(e) {
        e.preventDefault();
        console.log($(e.currentTarget).get(0))
        if($(this).valid()) {
            swal({
                text: 'Update Sub Timeline?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((confirm_) => {
                if (confirm_) {
                    ajaxSubmitSubTl($(e.currentTarget).get(0));
                }
            });
        }
    })
})
</script>
@endsection

@yield('jsNeededForm')
