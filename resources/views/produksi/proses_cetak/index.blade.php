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

        .scroll-riwayat {
            position: relative;
            overflow: auto;
            height: 250px;
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
        .scroll-riwayat thead {
        position: sticky; /* make the table heads sticky */
        top: 0; /* table head will be placed from the top of the table and sticks to it */
      }
        .scroll-riwayat tfoot {
        position: sticky; /* make the table heads sticky */
        bottom: 0; /* table head will be placed from the top of the table and sticks to it */
      }
    </style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Proses Produksi</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped dt-responsive" id="tb_prosesProduksi" style="width:100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('produksi.include.modal_track_produksi')
@include('produksi.include.modal_edit_riwayatkirim')
@include('tracker_modal')
@endsection

@section('jsRequired')
<script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="{{ url('vendors/datatables.net-bs4/js/dataTables.input.plugin.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
        <script src="https://unpkg.com/imask"></script>
@endsection

@section('jsNeeded')
<script src="{{ url('js/produksi/update_progress_produksi_cetak.js') }}"></script>
@endsection
