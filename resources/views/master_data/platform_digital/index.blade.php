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
        <h1>Data Platform Digital</h1>
        @if(Gate::allows('do_create', 'buat-platform-digital'))
        <div class="section-header-button">
            <a href="{{ route('platform.create')}}" class="btn btn-success">Tambah</a>
        </div>
        @endif
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id="tb_Platform" style="width:100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="md_PlatformHistory" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="titleModal"><i class="fas fa-history"></i>&nbsp;History Data Perubahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <div class="tickets-list" id="dataHistory">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
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
<script src="{{url('js/delete_platform.js')}}"></script>
<script>
    $(function() {
         $('#tb_Platform').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            ajax: {
                url: "{{ url('master/platform-digital') }}",
                data: {"request_": "table-platform"}
            },
            columns: [
                { data: 'no', name: 'no', title: 'No' },
                { data: 'nama_platform', name: 'nama_platform', title: 'Nama Platform' },
                { data: 'tgl_dibuat', name: 'tgl_dibuat', title: 'Tanggal Dibuat' },
                { data: 'dibuat_oleh', name: 'dibuat_oleh', title: 'Dibuat Oleh'},
                { data: 'diubah_terakhir', name: 'diubah_terakhir', title: 'Diubah Terakhir' },
                { data: 'diubah_oleh', name: 'diubah_oleh', title: 'Diubah Oleh' },
                { data: 'history', name: 'history', title: 'History Data' },
                { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
            ]
        });
    })
</script>
<script>
    $(function(){
        $('#tb_Platform').on('click','.btn-history',function(e){
            var data = $(this).data('id');
            $.post("{{route('platform.history')}}", {id: data}, function(data){
                $('#dataHistory').empty();
                $.each(data, function(k, v) {
                $('#dataHistory').append(`<span class="ticket-item">
                        <div class="ticket-title">
                            <h6><span class="bullet"></span> Platform '`+v.platform_history+`' diubah menjadi '`+v.platform_new+`'.</h6>
                        </div>
                        <div class="ticket-info">
                            <div class="text-muted">Modified by <a href="{{url('/manajemen-web/user/`+v.author_id+`')}}">`+v.nama+`</a></div>
                            <div class="bullet"></div>
                            <div>`+v.modified_at+` (`+v.format_tanggal+`)</div>
                        </div>
                    </span>`);
                });
            });
        });
    });
    </script>
@endsection
