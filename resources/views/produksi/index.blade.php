@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/SpinKit/spinkit.css')}}">
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Penerbitan Order Cetak</h1>
        @if(Gate::allows('do_create', 'tambah-produksi-cetak'))
        <div class="section-header-button">
            <a href="{{ route('cetak.create')}}" class="btn btn-success">Tambah</a>
        </div>
        @endif
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id="tb_Produksi" style="width:100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('jsRequired')
<script src="{{url('vendors/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('jsNeeded')

<script>
    $(function() {
         $('#tb_Produksi').DataTable({
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
                url: "{{ url('penerbitan/order-cetak') }}",
                data: {"request_": "table-produksi"}
            },
            columns: [
                { data: 'no_order', name: 'no_order', title: 'Kode Order' },
                { data: 'tipe_order', name: 'tipe_order', title: 'Tipe Order' },
                { data: 'pilihan_terbit', name: 'pilihan_terbit', title: 'Pilihan Terbit' },
                { data: 'status_cetak', name: 'status_cetak', title: 'Status Cetak' },
                { data: 'judul_buku', name: 'judul_buku', title: 'Judul Buku'},
                { data: 'isbn', name: 'isbn', title: 'ISBN'},
                { data: 'urgent', name: 'urgent', title: 'Urgent'},
                { data: 'status_penyetujuan', name: 'status_penyetujuan', title: 'Penyetujuan' },
                { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
            ]
        });
    })
</script>
@endsection
