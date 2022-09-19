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
        <h1>Data Penerbitan Deskripsi Produk</h1>

        {{-- @if(Gate::allows('do_create', 'tambah-produksi-cetak'))
        <div class="section-header-button">
            <a href="{{ route('cetak.create')}}" class="btn btn-success">Tambah</a>
        </div>
        @endif --}}
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id="tb_DesProduk" style="width:100%">
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
         $('#tb_DesProduk').DataTable({
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
                url: "{{ route('despro.view') }}",
                data: {"request_": "table-deskripsi-produk"}
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'No', orderable: false, searchable: false, "width": "5%" },
                { data: 'kode', name: 'kode', title: 'Kode' },
                { data: 'judul_asli', name: 'judul_asli', title: 'Judul Asli' },
                { data: 'penulis', name: 'penulis', title: 'Penulis',"width":"15%" },
                { data: 'imprint', name: 'imprint', title: 'Imprint'},
                { data: 'judul_final', name: 'judul_final', title: 'Judul Final'},
                { data: 'tgl_deskripsi', name: 'tgl_deskripsi', title: 'Tgl Deskripsi'},
                { data: 'pembuat_deskripsi', name: 'pembuat_deskripsi', title: 'Pembuat Deskripsi',orderable: false},
                { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
            ]
        });
    })
</script>
@endsection