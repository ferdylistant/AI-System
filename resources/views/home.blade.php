@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{url('vendors/fullcalendar/lib/main.min.css')}}">
@endsection

@section('cssNeeded')
<style>
    /* n => Naskah */
    .fc-event-title {
        color: #343a40;
    }
    .nMasuk .fc-daygrid-event-dot {
        border-color: #007bff;
    }
    .nJadi .fc-daygrid-event-dot {
        border-color: #28a745;
    }
    /* .fc-event-title {
        color: #1a252f;
    } */

    .tb-detail-naskah {
        font-size: 12px;
        border: 1px solid #ced4da;
    }
    .tb-detail-naskah th, .tb-detail-naskah td {
        height: auto !important;
        padding: 10px 15px 10px 15px !important;
    }
    .tb-detail-naskah th {
        width: 30%;
        color: #868ba1;
        background-color: #E9ECEF;
        text-align: right;
    }
</style>
@endsection

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>
    <div class="section-body">
        <div class="row mb-3">
        @if (Gate::allows('do_update','ubah-data-user'))
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                    <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total User</h4>
                    </div>
                    <div class="card-body">
                        {{$users->count()}}
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Divisi</h4>
                        </div>
                        <div class="card-body">
                        {{$divisi->count()}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Gate::allows('do_read_raw','lihat-penulis'))
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon" style="background: #34395e">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Penulis</h4>
                        </div>
                        <div class="card-body">
                        {{$penulis->count()}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Gate::allows('do_read_raw','lihat-imprint'))
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-stamp"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Imprint</h4>
                        </div>
                        <div class="card-body">
                        {{$imprint->count()}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Gate::allows('do_read_raw','lihat-naskah'))
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="far fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Naskah</h4>
                        </div>
                        <div class="card-body">
                        {{$naskah->count()}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Gate::allows('do_read_raw','lihat-order-cetak'))
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-print"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Order Cetak</h4>
                        </div>
                        <div class="card-body">
                        {{$or_ce->count()}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Gate::allows('do_read_raw','lihat-order-ebook'))
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-atlas"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Order E-Book</h4>
                        </div>
                        <div class="card-body">
                        {{$or_eb->count()}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Gate::allows('do_read_raw','lihat-proses-produksi'))
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Proses Produksi Cetak</h4>
                        </div>
                        <div class="card-body">
                        {{$proses_cetak->count()}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Gate::allows('do_read_raw','lihat-ebook-multimedia'))
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon" style="background: #34395e">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                        <h4>Proses Upload E-Book</h4>
                        </div>
                        <div class="card-body">
                        {{$upload_ebook->count()}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        </div>
        <div class="row">
            <div class="col-12 mb-4">
                <div class="hero text-white hero-bg-image hero-bg-parallax" style="background-image: url('images/background-home.avif');">
                    <div class="hero-inner">
                        <h2>Selamat datang, {{ $userdata->nama}}!</h2>
                        <p class="lead">You almost arrived, complete the information about your account to complete registration. {{$id}}</p>
                        <div class="mt-4">
                        <a href="{{url('manajemen-web/user/'.$userdata->id)}}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i> Setup Account</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-12">
                <div id="container_tb_naskah" class="card card-primary">
                    <div class="card-body">
                        <div class="col-8 offset-2">
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-control select2" name="tb_naskah_tahapan">
                                        <option value="all">Semua</option>
                                        <option value="naskah-masuk">Naskah Masuk</option>
                                        <option value="penerbitan">Proses Penerbitan</option>
                                        <option value="produksi">Proses Produksi</option>
                                        <option value="buku-jadi">Buku Jadi</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control drp-naskah" name="tb_naskah_tgl" placeholder="Tanggal">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 table-responsive mt-3">
                            <table class="table table-striped" id="tb_Naskah">
                            </table>
                        </div>
                    </div>
                </div>
            </div> --->
        </div>
    </div>
</section>


<div id="md_EventDetail" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            </div>

            <div class="modal-body">
                <h5>#Detail Naskah / Buku</h5>
                <div class="table-responsive">
                    <table class="table tb-detail-naskah">
                        <tr>
                            <th>Kode Naskah:</th>
                            <td>NA20220523003</td>
                        </tr>
                        <tr>
                            <th>Judul Asli:</th>
                            <td>Harry Plotter</td>
                        </tr>
                        <tr>
                            <th>Jalur Buku:</th>
                            <td>MoU-Reguler</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a href="" class="btn" target="_blank">Selengkapnya</a>
            </div>
        </div>
    </div>
</div>


@endsection

@section('jsRequired')
<script src="{{url('vendors/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{url('vendors/fullcalendar/lib/main.min.js')}}"></script>
@endsection



@section('jsNeeded')
{{-- <script>
    $(document).ready(function() {
        const url = window.location.href;
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const calendarEl = document.getElementById('calendar');

        let tableNaskah;
        tableNaskah = $('#tb_Naskah').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bDestroy: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                },
                ajax: {
                    type : "POST",
                    url: "{{ url('home/penerbitan/getNaskahData') }}",
                    data: {tahapan: null, tanggal: null}
                },
                columns: [
                    { data: 'kode', name: 'kode', title: 'Kode' },
                    { data: 'judul_asli', name: 'judul_asli', title: 'Judul Asli' },
                    { data: 'jalur_buku', name: 'jalur_buku', title: 'Jalur Buku' },
                    { data: 'tgl_masuk', name: 'tgl_masuk', title: 'Naskah Masuk'},
                    { data: 'tgl_penerbitan', name: 'tgl_penerbitan', title: 'Penerbitan'},
                    { data: 'tgl_produksi', name: 'tgl_produksi', title: 'Produksi'},
                    { data: 'tgl_jadi', name: 'tgl_jadi', title: 'Buku Jadi'},
                    { data: 'action', name: 'action', title: 'Detail', searchable: false, orderable: false},
                ]
            });

        $('.drp-naskah').daterangepicker({
            autoUpdateInput: false,
            locale: { format: 'DD MMM YYYY' }
        });
        $('input[name="tb_naskah_tgl"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD MMM YYYY') + ' - '
                        + picker.endDate.format('DD MMM YYYY'));
            reloadTbNaskah();
        });
        $('input[name="tb_naskah_tgl"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            reloadTbNaskah();
        });
        $('.select2').select2();
        $('select[name="tb_naskah_tahapan"]').on('change', function(e) {
            e.preventDefault();
            reloadTbNaskah();
        })

        function reloadTbNaskah() {
            let tahapan = $('select[name="tb_naskah_tahapan"]').val(),
                tanggal = $('input[name="tb_naskah_tgl"]').val(),
                elmnt = $('#container_tb_naskah');

            tableNaskah = $('#tb_Naskah').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bDestroy: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                },
                ajax: {
                    type: "POST",
                    url: "{{ url('home/penerbitan/getNaskahData') }}",
                    data: {tahapan: tahapan, tanggal: tanggal}
                },
                columns: [
                    { data: 'kode', name: 'kode', title: 'Kode' },
                    { data: 'judul_asli', name: 'judul_asli', title: 'Judul Asli' },
                    { data: 'jalur_buku', name: 'jalur_buku', title: 'Jalur Buku' },
                    { data: 'tgl_masuk', name: 'tgl_masuk', title: 'Naskah Masuk'},
                    { data: 'tgl_penerbitan', name: 'tgl_penerbitan', title: 'Penerbitan'},
                    { data: 'tgl_produksi', name: 'tgl_produksi', title: 'Produksi'},
                    { data: 'tgl_jadi', name: 'tgl_jadi', title: 'Buku Jadi'},
                    { data: 'action', name: 'action', title: 'Detail', searchable: false, orderable: false},
                ]
            });
        }


        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            eventDisplay: 'list-item',
            dayMaxEventRows: true,
            events: function(info, successCallback, failureCallback) {
                fetch(`${url}penerbitan/fullcalendar/events`, {
                    method: 'POST', // or 'PUT'
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        start: moment(info.startStr).format('YYYY-MM-DD'),
                        end: moment(info.endStr).format('YYYY-MM-DD')
                    }),
                }).then((res) => {
                    if (res.ok) {
                        return res.json();
                    }
                    throw res;
                })
                .then((res) => {
                    console.log(res)
                    successCallback(res)
                })
                .catch((err) => {
                    console.log(err)
                })
            },
            eventClick: function(info) {
                // console.log(info.event.classNames.includes('nJadi'))
                console.log()
                if(info.event.classNames.includes('nJadi')) {
                    $('#md_EventDetail').find('.modal-header').removeClass('bg-primary')
                    $('#md_EventDetail').find('.modal-footer a').removeClass('btn-primary')
                    $('#md_EventDetail').find('.modal-header').addClass('bg-success')
                    $('#md_EventDetail').find('.modal-footer a').addClass('btn-success')
                } else if(info.event.classNames.includes('nMasuk')) {
                    $('#md_EventDetail').find('.modal-header').removeClass('bg-success')
                    $('#md_EventDetail').find('.modal-footer a').removeClass('btn-success')
                    $('#md_EventDetail').find('.modal-header').addClass('bg-primary')
                    $('#md_EventDetail').find('.modal-footer a').addClass('btn-primary')
                }
                $('#md_EventDetail').data('id', info.event.id).modal('show');
            },
        });

        $('#md_EventDetail').on('shown.bs.modal', function (e) {
            let id = $(this).data('id');
            let elmnt = $(this);

            $.ajax({
                type: "POST",
                url: "{{url('penerbitan/fullcalendar/details')}}",
                data: {id: id},
                beforeSend: function() {
                    elmnt.addClass('modal-progress');
                },
                success: function(result) {
                    let naskah = result.naskah;
                    let timeline = result.timeline;
                    let html_ = '';

                    elmnt.find('.modal-footer a').attr('href', '{{url("penerbitan/naskah/melihat-naskah")}}/'+naskah.id)
                    html_ +=    `<tr><th>Kode Naskah:</th><td>${naskah.kode}</td></tr>`;
                    html_ +=    `<tr><th>Judul Asli:</th><td>${naskah.judul_asli}</td></tr>`;
                    html_ +=    `<tr><th>Jalur Buku:</th><td>${naskah.jalur_buku}</td></tr>`;
                    html_ +=    `<tr><th>Tgl Naskah Masuk:</th><td>${naskah.tanggal_masuk_naskah}</td></tr>`;

                    if(timeline) {
                        html_ +=    `<tr><th>Mulai Penerbitan:</th><td>${timeline.tgl_mulai_penerbitan} (${timeline.ttl_hari_penerbitan} Hari)</td></tr>`;
                        html_ +=    `<tr><th>Mulai Produksi:</th><td>${timeline.tgl_mulai_produksi} (${timeline.ttl_hari_produksi} Hari)</td></tr>`;
                        html_ +=    `<tr><th>Tgl Buku Jadi:</th><td>${timeline.tgl_buku_jadi}</td></tr>`;
                    }

                    elmnt.find('.modal-body .tb-detail-naskah').empty();
                    elmnt.find('.modal-body .tb-detail-naskah').append(html_);

                    console.log(result)
                },
                error: function(err) {
                    console.log(err);
                },
                complete: function() {
                    elmnt.removeClass('modal-progress');
                }
            })
        })

        calendar.render();
    })
</script> --}}
{{-- <style>

  #calendar a.fc-event {
    color: #fff; /* bootstrap default styles make it black. undo */
  }

</style> --}}
@endsection
