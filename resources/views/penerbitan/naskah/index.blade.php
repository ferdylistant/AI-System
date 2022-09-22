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
                        <div class="form-group col-12 col-md-3 mb-4">
                            <div class="input-group">
                                <select data-column="2" name="status_jb" id="status_jb" class="form-control select-filter-jb status_filter-_jb" style="width: 200px">
                                    <option label="Pilih Filter Status"></option>
                                    <option value="Reguler">Reguler</option>
                                        <option value="MoU-Reguler">MoU-Reguler</option>

                                </select>
                                    <button type="button" class="btn btn-outline-danger clear_field_jb text-danger align-self-center" data-toggle="tooltip" title="Reset" hidden><i class="fas fa-times"></i></button>

                            </div>


                        </div>
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id="tb_Naskah">
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
<script src="{{url('js/upload_link.js')}}"></script>
<script>
    $(function() {
        let tableNaskah = $('#tb_Naskah').DataTable({
            "bSort": true,
            "responsive": true,
            processing: true,
            serverSide: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            ajax: "{{ route('naskah.view') }}",
            columns: [
                { data: 'kode', name: 'kode', title: 'Kode' },
                { data: 'judul_asli', name: 'judul_asli', title: 'Judul Asli' },
                { data: 'jalur_buku', name: 'jalur_buku', title: 'Jalur Buku' },
                { data: 'stts_penilaian', name: 'stts_penilaian', title: 'Penilaian'},
                { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
            ]
        });
        $('[name="status_jb"]').on('change', function(){
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            tableNaskah.column( $(this).data('column') )
            .search( val ? val : '', true, false )
            .draw();
        });
    });
</script>
@endsection
