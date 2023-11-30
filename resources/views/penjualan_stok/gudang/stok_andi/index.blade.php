@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
    {{-- <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css"> --}}
    {{-- <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css"> --}}
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/SpinKit/spinkit.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
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

        .scroll-riwayat {
            position: relative;
            overflow: auto;
            height: 250px;
        }

        .example-1 {
            position: relative;
            overflow-y: scroll;
            max-height: 200px;
        }

        .example-2 {
            position: relative;
            overflow-y: scroll;
            height: 400px;
        }

        .scroll-riwayat thead {
            position: sticky;
            /* make the table heads sticky */
            top: 0;
            /* table head will be placed from the top of the table and sticks to it */
        }

        span.deleteicon {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        span.deleteicon span {
            position: absolute;
            display: block;
            right: 3px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            color: #fff;
            background-color: #ccc;
            font: 13px monospace;
            text-align: center;
            line-height: 1em;
            cursor: pointer;
        }

        span.deleteicon input {
            padding-right: 18px;
            box-sizing: border-box;
        }

  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20rem; }
  .toggle.ios .toggle-handle { border-radius: 20rem; }

    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Stok Buku Andi <small>(Buku Baru)</small></h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="col-12">
                                <table class="table table-bordered dt-responsive" id="tb_stokGudangAndi" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="align-middle" rowspan="2">Kode SKU</th>
                                            <th scope="col" class="text-center align-middle" rowspan="2">Judul Buku</th>
                                            <th scope="col" class="text-center align-middle" rowspan="2">Sub-Judul</th>
                                            <th scope="col" class="text-center align-middle" rowspan="2">Penulis</th>
                                            <th scope="col" class="text-center align-middle" rowspan="2">Imprint</th>
                                            <th scope="col" class="text-center align-middle" rowspan="2">ISBN</th>
                                            <th scope="col" class="text-center align-middle" rowspan="2">Total Stok</th>
                                            <th scope="col" class="text-center align-middle" colspan="3">Harga</th>
                                            <th scope="col" class="text-center align-middle" rowspan="2">Status</th>
                                            <th scope="col" class="text-center align-middle" style="background: #F5F5F5" rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center">Zona 1</th>
                                            <th scope="col" class="text-center">Zona 2</th>
                                            <th scope="col" class="text-center">Zona 3</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('penjualan_stok.gudang.stok_andi.include.modal_rack')
    @include('penjualan_stok.gudang.stok_andi.include.modal_editharga')
    @include('penjualan_stok.gudang.stok_andi.include.modal_aktivitas_rak')
    @include('penjualan_stok.gudang.stok_andi.include.modal_permohonan_rekondisi')
    @include('penjualan_stok.gudang.stok_andi.include.modal_detail_rack')
    @include('penjualan_stok.gudang.stok_andi.include.modal_detail_edit_rack')
    @include('penjualan_stok.gudang.stok_andi.include.modal_detail_pindah_rack')
    @include('penjualan_stok.gudang.stok_andi.include.modal_detail_history_rack')
    @include('tracker_modal')
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="{{ url('vendors/datatables.net-bs4/js/dataTables.input.plugin.js') }}"></script>
    {{-- <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script> --}}
    {{-- <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script> --}}
    <script src="https://unpkg.com/imask"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/penjualan_stok/update_progress_st_andi.js') }}"></script>
@endsection
