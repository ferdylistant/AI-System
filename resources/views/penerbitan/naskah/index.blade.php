@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/SpinKit/spinkit.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/jquery-magnify/dist/jquery.magnify.min.css') }}">
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
        .scrollbar-deep-red::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        .scrollbar-deep-red::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        .scrollbar-deep-red::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: rgb(252, 84, 75);
        }

        .scrollbar-deep-red {
            scrollbar-color: rgb(252, 84, 75) #F5F5F5;
        }

        .bordered-deep-red::-webkit-scrollbar-track {
            -webkit-box-shadow: none;
            border: 1px solid rgb(252, 84, 75);
        }

        .bordered-deep-red::-webkit-scrollbar-thumb {
            -webkit-box-shadow: none;
        }
        .scrollbar-deep-success::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        .scrollbar-deep-success::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        .scrollbar-deep-success::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: rgb(99, 237, 122);
        }

        .scrollbar-deep-success {
            scrollbar-color: rgb(99, 237, 122) #F5F5F5;
        }

        .bordered-deep-success::-webkit-scrollbar-track {
            -webkit-box-shadow: none;
            border: 1px solid rgb(99, 237, 122);
        }

        .bordered-deep-success::-webkit-scrollbar-thumb {
            -webkit-box-shadow: none;
        }
        .scrollbar-deep-info::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        .scrollbar-deep-info::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        .scrollbar-deep-info::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: rgb(58, 186, 244);
        }

        .scrollbar-deep-info {
            scrollbar-color: rgb(58, 186, 244) #F5F5F5;
        }

        .bordered-deep-info::-webkit-scrollbar-track {
            -webkit-box-shadow: none;
            border: 1px solid rgb(58, 186, 244);
        }

        .bordered-deep-info::-webkit-scrollbar-thumb {
            -webkit-box-shadow: none;
        }
        .scrollbar-deep-light::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        .scrollbar-deep-light::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        .scrollbar-deep-light::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: rgb(227, 234, 239);
        }

        .scrollbar-deep-light {
            scrollbar-color: rgb(227, 234, 239) #F5F5F5;
        }

        .bordered-deep-light::-webkit-scrollbar-track {
            -webkit-box-shadow: none;
            border: 1px solid rgb(227, 234, 239);
        }

        .bordered-deep-light::-webkit-scrollbar-thumb {
            -webkit-box-shadow: none;
        }
        .scrollbar-deep-secondary::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        .scrollbar-deep-secondary::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        .scrollbar-deep-secondary::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
            background-color: rgb(52, 57, 94);
        }

        .scrollbar-deep-secondary {
            scrollbar-color: rgb(52, 57, 94) #F5F5F5;
        }

        .bordered-deep-secondary::-webkit-scrollbar-track {
            -webkit-box-shadow: none;
            border: 1px solid rgb(52, 57, 94);
        }

        .bordered-deep-secondary::-webkit-scrollbar-thumb {
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
        .example-2 {
            position: relative;
            overflow-y: scroll;
            height: 400px;
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
                                        <select data-column="3" name="status_filter_jb" id="status_filter_jb"
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
                                        <select data-column="6" name="status_filter" id="status_filter"
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
                                    <span class="badge badge-warning"
                                        style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"><i
                                            class="fas fa-database"></i> Total data naskah
                                        masuk: <b id="totalNaskah">0</b></span>
                                </div>
                            </div>
                            <div class="col-auto mb-3" id="countPenilaian" hidden>
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
    <div class="modal fade" id="modalPenilaian" tabindex="-1" role="dialog" aria-labelledby="titleModalPenilaian"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" id="boxContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModalPenilaian"></h5>&nbsp;&ndash;&nbsp;
                    <sup id="badgeTotal"><span id="totalNaskahDinilai"></span></sup>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="scrollBar">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="list-unstyled list-unstyled-border">
                                    <div id="contentModal"></div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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
        src="{{ url('vendors/datatables.net-bs4/js/dataTables.input.plugin.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
        <script src="{{ url('vendors/jquery-magnify/dist/jquery.magnify.min.js') }}"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/tandai_naskah_lengkap.js') }}"></script>
@endsection
