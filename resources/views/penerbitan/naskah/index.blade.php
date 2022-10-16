@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css"> --}}
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
                            <table id="tb_Naskah" class="table table-striped  dt-responsive" style="width: 100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="md_NaskahHistory" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModalNaskah" aria-hidden="true">
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
            <button class="d-block btn btn-sm btn-outline-primary btn-block load-more" id="load_more" data-paginate="2" data-id="">Load more</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsRequired')
<script src="{{url('vendors/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.js')}}"></script>
{{-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script> --}}
@endsection

@section('jsNeeded')
<script src="{{url('js/upload_link.js')}}"></script>
<script>
    $(function() {
        let tableNaskah = $('#tb_Naskah').DataTable({
            "bSort": true,
            "responsive": true,
            'autoWidth': true,
            processing: true,
            serverSide: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            ajax: {
                url: "{{ route('naskah.view') }}",
                data: {"request_": "table-naskah"}
            },
            columns: [
                { data: 'kode', name: 'kode', title: 'Kode' },
                { data: 'judul_asli', name: 'judul_asli', title: 'Judul Asli' },
                { data: 'jalur_buku', name: 'jalur_buku', title: 'Jalur Buku' },
                { data: 'stts_penilaian', name: 'stts_penilaian', title: 'Penilaian'},
                { data: 'history', name: 'history', title: 'History'},
                { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
            ]
        });
    });
</script>
<script>
    $('#tb_Naskah').on('click','.btn-history',function(e){
        var id = $(this).data('id');
        var judul = $(this).data('judulasli');
        $.post("{{route('naskah.history')}}", {id: id}, function(data){
            $('#titleModalNaskah').html('<i class="fas fa-history"></i>&nbsp;History Perubahan Naskah "'+judul+'"');
            $('#load_more').data('id',id);
            $('#dataHistoryNaskah').html(data);
            $('#md_NaskahHistory').modal('show');
        });
    });
</script>
@endsection
