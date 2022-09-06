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
        <h1>Data Proses Upload E-book Multimedia</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id="tb_prosesEbook" style="width:100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@include('produksi.proses_ebook_multimedia.modal_upload_link')
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
{{-- <script src="{{url('js/modal_upload_link.js')}}"></script> --}}
<script>
    $(function() {
         $('#tb_prosesEbook').DataTable({
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
                url: "{{ url('produksi/proses/ebook-multimedia') }}",
                data: {"request_": "table-proses-produksi-ebook"}
            },
            columns: [
                // { data: 'no', name: 'no', title: 'No' },
                { data: 'no_order', name: 'no_order', title: 'Kode Order' },
                { data: 'judul_buku', name: 'judul_buku', title: 'Judul Buku'},
                // { data: 'penulis', name: 'penulis', title: 'Penulis'},
                { data: 'edisi_cetakan', name: 'edisi_cetakan', title: 'Edisi Cetakan'},
                { data: 'platform_digital', name: 'platform_digital', title: 'Platform Digital'},
                { data: 'tanggal_order', name: 'tanggal_order', title: 'Tgl Order'},
                { data: 'tgl_upload', name: 'tgl_upload', title: 'Tgl Harus Upload'},
                { data: 'bukti_upload', name: 'bukti_upload', title: 'Link Bukti Upload' },
                { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
            ]
        });
    })
</script>

@endsection
