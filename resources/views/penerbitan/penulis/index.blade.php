@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/SpinKit/spinkit.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <style>
        .popover {
            /* background: tomato !important; */
            box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px!important;
        }

        .popover.top .arrow:after {
            /* border-bottom-color: tomato!important; */
            box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px!important;
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
            overflow-y: scroll;
            height: 200px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Penulis</h1>
            @if (Gate::allows('do_create', 'tambah-data-penulis'))
                <div class="section-header-button">
                    <a href="{{ url('penerbitan/penulis/membuat-penulis') }}" class="btn btn-success">Tambah</a>
                    <a href="{{ route('penulis.telah_dihapus') }}" class="btn btn-danger">Penulis Telah Dihapus</a>
                </div>
            @endif
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="col-auto mb-3">
                                        <span class="badge badge-warning"><i class="fas fa-database"></i> Total penulis: <b
                                                id="countData">0</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 table-responsive">
                                <table id="tb_Penulis" class="table table-striped dt-responsive" style="width: 100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal Format Buku -->
    @include('penerbitan.penulis.modal_history_penulis')
@endsection

@section('jsRequired')
    <script src="{{ asset('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="{{ asset('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="{{ asset('vendors/datatables.net-bs4/js/dataTables.input.plugin.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
@endsection

@section('jsNeeded')
    <script src="{{ asset('js/penerbitan/penulis.js') }}"></script>
@endsection
