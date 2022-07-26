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
        <h1>Data Naskah</h1>
        @if(Gate::allows('do_create', 'tambah-data-naskah'))
        <div class="section-header-button">
            <a href="{{url('penerbitan/naskah/membuat-naskah')}}" class="btn btn-success">Tambah</a>
        </div>
        @endif
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id="tb_Naskah" style="width:100%">
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
<script src="{{url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('jsNeeded')
<script>
    $(function() {
        let tableNaskah = $('#tb_Naskah').DataTable({
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
            columnDefs: [
			{ orderable: false, className: 'control', targets: 0 },
            { orderable: false, className: 'reorder', targets: 1 },
            { orderable: false, targets: '_all' }
        ],
            ajax: {
                url: "{{ url('penerbitan/naskah') }}",
                data: {"request_": "table-naskah"}
            },
            columns: [
                { data: 'kode', name: 'kode', title: 'Kode' },
                { data: 'judul_asli', name: 'judul_asli', title: 'Judul Asli' },
                { data: 'jalur_buku', name: 'jalur_buku', title: 'Jalur Buku' },
                { data: 'penilaian_editor_setter', name: 'penilaian_editor_setter', title: 'Penilaian Editor/Setter'},
                { data: 'penilaian_m_penerbitan', name: 'penilaian_m_penerbitan', title: 'Penilaian M.Penerbitan'},
                { data: 'penilaian_m_pemasaran', name: 'penilaian_m_pemasaran', title: 'Penilaian M.Pemasaran'},
                { data: 'penilaian_d_pemasaran', name: 'penilaian_d_pemasaran', title: 'Penilaian D.Pemasaran'},
                { data: 'penilaian_prodev', name: 'penilaian_prodev', title: 'Penilaian Prodev'},
                { data: 'penilaian_direksi', name: 'penilaian_direksi', title: 'Penilaian Direksi'},
                { data: 'stts_penilaian', name: 'stts_penilaian', title: 'Selesai Penilaian'},
                { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
            ]
        });

    })
</script>
@endsection
