@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ url('vendors/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/fullcalendar/lib/main.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ url('vendors/jquery.timeline-master/dist/css/timeline.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/gh/ycodetech/horizontal-timeline-2.0@2/css/horizontal_timeline.2.0.min.css">
    {{-- <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}"> --}}
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

        .tb-detail-naskah th,
        .tb-detail-naskah td {
            height: auto !important;
            padding: 10px 15px 10px 15px !important;
        }

        .tb-detail-naskah th {
            width: 30%;
            color: #868ba1;
            background-color: #E9ECEF;
            text-align: right;
        }

        .scrollbar-deep-purple::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        .scrollbar-deep-purple::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        .scrollbar-deep-purple::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #6777EF;
        }

        .scrollbar-deep-purple {
            scrollbar-color: #6777EF #F5F5F5;
        }

        .bordered-deep-purple::-webkit-scrollbar-track {
            -webkit-box-shadow: none;
            border: 1px solid #6777EF;
        }

        .bordered-deep-purple::-webkit-scrollbar-thumb {
            -webkit-box-shadow: none;
        }

        .square::-webkit-scrollbar-track {
            border-radius: 0 !important;
        }

        .square::-webkit-scrollbar-thumb {
            border-radius: 0 !important;
        }

        .thin::-webkit-scrollbar {
            width: 6px;
        }

        .example-1 {
            position: relative;
            overflow-y: auto;
            height: 360px;
        }

        .example-2 {
            position: relative;
            overflow-y: auto;
            max-height: 300px;
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
                <div class="col-12 mb-4">
                    <div class="hero text-white hero-bg-image hero-bg-parallax"
                        style="background-image: image-set(url('images/background-home.avif') 2x)">
                        <div class="hero-inner">
                            <h2>Selamat datang, {{ $userdata->nama }}!</h2>
                            <p class="lead">You almost arrived, complete the information about your account to complete
                                registration. </p>
                            <div class="mt-4">
                                <a href="{{ url('manajemen-web/user/' . $userdata->id) }}"
                                    class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>
                                    Setup Account</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="section-title">My To-Do List</h4>
                        </div>
                        <div id="todoList_data" class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist"
                                style="box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;">
                                <li class="nav-item">
                                    <a class="nav-link active" id="semua-tab" data-toggle="tab" href="#semua"
                                        role="tab" aria-controls="semua" data-typeget="semua"
                                        aria-selected="true">Semua</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="belum-selesai-tab" data-toggle="tab" href="#belum-selesai"
                                        role="tab" aria-controls="belum-selesai" data-typeget="belum-selesai"
                                        aria-selected="false">Belum Selesai</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="selesai-tab" data-toggle="tab" href="#selesai" role="tab"
                                        aria-controls="selesai" data-typeget="selesai" aria-selected="false">Selesai</a>
                                </li>
                            </ul>
                            <div class="tab-content example-2 scrollbar-deep-purple bordered-deep-purple square thin"
                                id="myTabContent">
                                <div class="tab-pane fade show active" id="semua" role="tabpanel"
                                    aria-labelledby="semua-tab"></div>
                                <div class="tab-pane fade" id="belum-selesai" role="tabpanel"
                                    aria-labelledby="belum-selesai-tab"></div>
                                <div class="tab-pane fade" id="selesai" role="tabpanel" aria-labelledby="selesai-tab">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($timeline == true)
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                        <div id="container_tb_naskah" class="card pb-5">
                            <div class="card-header">
                                <h4 class="section-title">Timeline</h4>
                            </div>
                            <div class="card-body" style="height: 360px">
                                <div class="col-12 offset-2">
                                    <div class="row">
                                        <div class="col-8">
                                            <select class="form-control select2-timeline" name="tb_naskah_tahapan">
                                                <option label="Pilih timeline"></option>
                                                @foreach ($naskah_kode_timeline as $nas_timeline)
                                                    <option value="{{ $nas_timeline->id }}">{{ $nas_timeline->kode }}
                                                        &#9903; {{ $nas_timeline->judul_asli }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="showTimeline">
                                    <div class="col-12 offset-3 mt-5">
                                        <div class="row">
                                            <div class="col-4 offset-1">
                                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png"
                                                    width="100%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                        <div id="card_recent_activity" class="card pb-5">
                            <div class="card-header">
                                <h4 class="section-title">Recent Activities</h4>
                            </div>
                            <div class="card-body example-1 scrollbar-deep-purple bordered-deep-purple square thin"
                                id="loadScroll">
                                <ul class="list-unstyled list-unstyled-border" id="recentActivity">

                                </ul>
                            </div>
                            {{-- <div class="text-center pt-1 pb-1">
                            </div> --}}
                        </div>
                    </div>
                </div>
        </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card-header bg-white">
                    <h4 class="section-title">
                        Total Data
                        <small><samp class="text-danger">(*Data sesuai akses role)</samp></small>
                    </h4>
                </div>
            </div>
            @if (Gate::allows('do_update', 'ubah-data-user'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total User</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $users->count() }}
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
                            <div class="card-body counter">
                                {{ $divisi->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-penulis'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon" style="background: #34395e">
                            <i class="fas fa-pen"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Penulis</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $penulis->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-imprint'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-stamp"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Imprint</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $imprint->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-naskah'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-file-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Naskah</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $naskah->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-deskripsi-produk'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-secondary">
                            <i class="far fa-file-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Deskripsi Produk</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $desprod->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-deskripsi-final'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="far fa-file-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Deskripsi Final</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $desfin->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-deskripsi-cover'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-file-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Deskripsi Cover</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $desfin->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-editing'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="far fa-edit"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Editing</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $editing->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-pracetak-setter'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon" style="background: #34395e">
                            <i class="far fa-edit"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pracetak Setter</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $praset->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-pracetak-designer'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-edit"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pracetak Designer</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $prades->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-deskripsi-turuncetak'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-print"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Deskripsi Turun Cetak</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $desturcet->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-order-cetak'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-secondary">
                            <i class="fas fa-print"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Order Cetak</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $or_ce->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-order-ebook'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-atlas"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Order E-Book</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $or_eb->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-proses-produksi'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Proses Produksi Cetak</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $proses_cetak->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (Gate::allows('do_read_raw', 'lihat-ebook-multimedia'))
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon" style="background: #34395e">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Proses Upload E-Book</h4>
                            </div>
                            <div class="card-body counter">
                                {{ $upload_ebook->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    {{-- <script src="{{ url('vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('vendors/fullcalendar/lib/main.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/gh/ycodetech/horizontal-timeline-2.0@2/JavaScript/horizontal_timeline.2.0.min.js">
    </script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
@endsection



@section('jsNeeded')
    <script>
        $(function() {
            $('.counter').each(function() {
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 3000,
                    easing: 'swing',
                    step: function(now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });
            $('[name="tb_naskah_tahapan"]').val('').change();
            $(".select2-timeline")
                .select2({
                    placeholder: "Pilih Timeline",
                });
            $('#container_tb_naskah').on('change', '[name="tb_naskah_tahapan"]', function(e) {
                // console.log($(this).val());
                let id = $(this).val();
                let cardWrap = $(".section-body").find(this).closest(".card");
                $.ajax({
                    url: window.location.origin + "/timeline/show",
                    type: 'POST',
                    data: {
                        id: id,
                    },
                    cache: false,
                    dataType: 'json',
                    beforeSend: function() {
                        cardWrap.addClass('card-progress');
                    },
                    success: function(result) {
                        // console.log(result);

                        if (result.status == 'error') {
                            notifToast(result.error, 'Belum ada progress!')
                            $('#showTimeline').html(`<div class="col-12 offset-3 mt-5">
                                <div class="row">
                                    <div class="col-4 offset-1">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" width="100%">
                                    </div>
                                </div>
                            </div>`);
                        } else {
                            $('#showTimeline').html(result.data);
                            timelineFunction();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                        notifToast('error', 'Terjadi kesalahan!')
                    },
                    complete: function() {
                        cardWrap.removeClass('card-progress');
                    }
                });
            });

            function timelineFunction() {
                $('#timelineNaskah').horizontalTimeline({
                    // ! Deprecated in favour of the object options. //
                    desktopDateIntervals: 200, //************\\
                    tabletDateIntervals: 150, // Minimum: 120 \\
                    mobileDateIntervals: 120, //****************\\
                    minimalFirstDateInterval: true,
                    dateIntervals: {
                        "desktop": 200, //************\\
                        "tablet": 150, // Minimum: 120 \\
                        "mobile": 120, //****************\\
                        "minimal": true
                    },

                    /* End new object options */

                    dateDisplay: "dateTime", // dateTime, date, time, dayMonth, monthYear, year
                    dateOrder: "normal", // normal, reverse

                    autoplay: false,
                    autoplaySpeed: 8, // Sec
                    autoplayPause_onHover: false,

                    useScrollWheel: false,
                    useTouchSwipe: true,
                    useKeyboardKeys: true,
                    addRequiredFile: true,
                    useFontAwesomeIcons: true,
                    useNavBtns: true,
                    useScrollBtns: true,

                    // ! Deprecated in favour of the object options. //
                    iconBaseClass: "fas fa-3x", // Space separated class names
                    scrollLeft_iconClass: "fa-chevron-circle-left",
                    scrollRight_iconClass: "fa-chevron-circle-right",
                    prev_iconClass: "fa-arrow-circle-left",
                    next_iconClass: "fa-arrow-circle-right",
                    pause_iconClass: "fa-pause-circle",
                    play_iconClass: "fa-play-circle",

                    animation_baseClass: "animationSpeed", // Space separated class names
                    enter_animationClass: {
                        "left": "enter-left",
                        "right": "enter-right"
                    },
                    exit_animationClass: {
                        "left": "exit-left",
                        "right": "exit-right"
                    },
                    iconClass: {
                        "base": "fas fa-3x", // Space separated class names
                        "scrollLeft": "fa-chevron-circle-left",
                        "scrollRight": "fa-chevron-circle-right",
                        "prev": "fa-arrow-circle-left",
                        "next": "fa-arrow-circle-right",
                        "pause": "fa-pause-circle",
                        "play": "fa-play-circle"
                    },
                    animationClass: {
                        "base": "animationSpeed", // Space separated class names,
                        "enter": {
                            "left": "enter-left",
                            "right": "enter-right"
                        },
                        "exit": {
                            "left": "exit-left",
                            "right": "exit-right"
                        }
                    }

                    /* End new object options */
                });
            }
            loadTodoList($('#todoList_data .nav-link.active').data('typeget'));
            // Each change tab form penilaian
            $(document).on('show.bs.tab', function(e) {
                let tab = $(e.delegateTarget.activeElement).data('typeget');
                loadTodoList(tab);
            });
            var page = 1;
            let timeline = "{{ $timeline }}";
            if (timeline == true) {
                loadRecentData(page);
            }
            $("#loadScroll").scroll(function() {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    page++;
                    loadRecentData(page);
                }
            });

            function loadTodoList(tab) {
                if (tab) {
                    // console.log(tab);
                    $.ajax({
                        type: "POST",
                        url: window.location.origin + "/api/home/" + tab,
                        beforeSend: function() {
                            $('#todoList_data').parent().addClass('card-progress')
                        },
                        success: function(result) {
                            // console.log(tab)
                            $('#' + tab).html(result);
                            $('[data-toggle="popover"]').popover();
                        },
                        error: function(err) {
                            // console.log(err)
                            notifToast('error', 'Gagal memuat todo-list!');
                        },
                        complete: function() {
                            $('#todoList_data').parent().removeClass('card-progress')
                        }
                    });
                }

            }

            function loadRecentData(page) {
                cardWrap = $(".section-body").find('#card_recent_activity').closest(".card");
                $.ajax({
                    url: window.location.origin + "/api/home/recent-activity?page=" + page,
                    type: "get",
                    datatype: "html",
                    beforeSend: function() {
                        cardWrap.addClass('card-progress');
                    }
                }).done(function(data) {
                    // console.log(data);
                    if (data == "") {
                        notifToast('error', 'Tidak ada data lagi!');
                        return;
                    }
                    cardWrap.removeClass('card-progress');
                    $("#recentActivity").append(data);
                }).fail(function(jqXHR, ajaxOptions, thrownError) {
                    notifToast('error', 'Terjadi kesalahan!')
                });
            }
            $('#todoList_data').on('click', '.delete-todo', function() {
                var id = $(this).data("id");
                $.ajax({
                    type: "POST",
                    url: window.location.origin + "/api/home/delete-todo",
                    data: ({
                        id: id
                    }),
                    success: function(html) {
                        // console.log(html);
                        $(".delete_list_" + id).fadeOut(300, function() {
                            $(".delete_list_" + id).remove();
                        });
                    },
                    error: function(err) {
                        notifToast('error', 'Terjadi kesalahan!')
                    }
                });
            });
        });
    </script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            Echo.channel(`hello-channel`).listen('TesWebsocketEvent', (e) => {
                console.log(e);
            });
        });
    </script> --}}
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
