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

        .example-2 {
            position: relative;
            overflow-y: scroll;
            height: 400px;
        }
    </style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Proses Produksi Order Cetak</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id="tb_prosesProduksi" style="width:100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
@endsection

@section('jsNeeded')
<script src="{{ url('js/update_progress_produksi_cetak.js') }}"></script>
<script>
    $(function() {
         $('#tb_prosesProduksi').DataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            processing: true,
            serverSide: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            ajax: {
                url: "{{ url('produksi/proses/cetak') }}",
                data: {"request_": "table-proses-produksi-cetak"}
            },
            columns: [
                // { data: 'no', name: 'no', title: 'No' },
                { data: 'no_order', name: 'no_order', title: 'Kode Order' },
                { data: 'tipe_order', name: 'tipe_order', title: 'Tipe Order' },
                { data: 'status_cetak', name: 'status_cetak', title: 'Status Cetak' },
                { data: 'judul_buku', name: 'judul_buku', title: 'Judul Buku'},
                // { data: 'penulis', name: 'penulis', title: 'Penulis'},
                { data: 'edisi_cetakan', name: 'edisi_cetakan', title: 'Edisi Cetakan'},
                { data: 'jenis_jilid', name: 'jenis_jilid', title: 'Jenis Jilid'},
                { data: 'buku_jadi', name: 'buku_jadi', title: 'Buku Jadi'},
                { data: 'tracking', name: 'tracking', title: 'Tracking' },
                { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
            ]
        });
    })
</script>
@endsection
