@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/SpinKit/spinkit.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <style>
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
            overflow-y: scroll;
            height: 200px;
        }
    </style>
@endsection

@section('content')
    <section class="section" id="sectionDataNaskah">
        <div class="section-header">
            <h1>Data Naskah</h1>
            @if (Gate::allows('do_create', 'tambah-data-naskah'))
                <div class="section-header-button">
                    <a href="{{ url('penerbitan/naskah/membuat-naskah') }}" class="btn btn-success">Tambah</a>
                </div>
            @endif
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row justify-content-between">
                                <div class="form-group col-auto col-md-3">
                                    <div class="input-group">
                                        <select data-column="2" name="status_filter_jb" id="status_filter_jb"
                                            class="form-control select-filter-jb status_filter_jb">
                                            <option label="Pilih Filter Data"></option>
                                            @foreach ($jalur_b as $val)
                                                <option value="{{ $val }}">{{ $val }}&nbsp;&nbsp;</option>
                                            @endforeach

                                        </select>
                                        <button type="button"
                                            class="btn btn-outline-danger clear_field_jb text-danger align-self-center"
                                            data-toggle="tooltip" title="Reset" hidden><i
                                                class="fas fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="form-group col-auto col-md-3">
                                    <div class="input-group">
                                        <select data-column="4" name="status_filter" id="status_filter"
                                            class="form-control select-filter status_filter">
                                            <option label="Pilih Filter Data"></option>
                                            @foreach ($data_naskah_penulis as $val)
                                                <option value="{{ $val['value'] }}">{{ $val['value'] }}&nbsp;&nbsp;</option>
                                            @endforeach

                                        </select>
                                        <button type="button"
                                            class="btn btn-outline-danger clear_field text-danger align-self-center"
                                            data-toggle="tooltip" title="Reset" hidden><i
                                                class="fas fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span class="badge badge-warning"><i class="fas fa-database"></i> Total data naskah
                                        masuk: <b>{{ $count }}</b></span>
                                </div>
                            </div>
                            <div class="col-12 table-responsive">
                                <table id="tb_Naskah" class="table table-striped  dt-responsive" style="width: 100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="md_NaskahHistory" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModalNaskah"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="titleModalNaskah"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body example-1 scrollbar-deep-purple bordered-deep-purple thin">
                    <div class="tickets-list" id="dataHistoryNaskah">

                    </div>

                </div>
                <div class="modal-footer">
                    <button class="d-block btn btn-sm btn-outline-primary btn-block load-more" id="load_more"
                        data-paginate="2" data-id="">Load more</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/tandai_naskah_lengkap.js') }}"></script>
    <script>
        $(function() {
            let tableNaskah = $('#tb_Naskah').DataTable({
                "bSort": true,
                "responsive": true,
                'autoWidth': true,
                processing: true,
                serverSide: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                },
                ajax: "{{ route('naskah.view') }}",
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
                ]
            });
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
    </script>
    <script>
        $('#tb_Naskah').on('click', '.btn-history', function(e) {
            var id = $(this).data('id');
            var judul = $(this).data('judulasli');
            $.post("{{ route('naskah.history') }}", {
                id: id
            }, function(data) {
                $('#titleModalNaskah').html(
                    '<i class="fas fa-history"></i>&nbsp;History Perubahan Naskah "' + judul + '"');
                $('#load_more').data('id', id);
                $('#dataHistoryNaskah').html(data);
                $('#md_NaskahHistory').modal('show');
            });
        });
    </script>
    <script>
        $(function() {
            $('.load-more').click(function(e) {
                e.preventDefault();
                var page = $(this).data('paginate');
                var id = $(this).data('id');
                $(this).data('paginate', page + 1);

                $.ajax({
                    url: "{{ route('naskah.history') }}",
                    data: {
                        id: id,
                        page: page
                    },
                    type: 'post',
                    beforeSend: function() {
                        $(".load-more").text("Loading...");
                    },
                    success: function(response) {
                        if (response.length == 0) {
                            notifToast('error', 'Tidak ada data lagi');
                        }
                        $('#dataHistoryNaskah').append(response);
                        // Setting little delay while displaying new content
                        // setTimeout(function() {
                        //     // appending posts after last post with class="post"
                        //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                        // }, 2000);

                    },
                    complete: function(params) {
                        $(".load-more").text("Load more").fadeIn("slow");
                    }
                });
            });
        });
    </script>
@endsection
