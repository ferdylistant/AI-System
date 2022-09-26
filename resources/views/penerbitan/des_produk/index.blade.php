@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/SpinKit/spinkit.css')}}">
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
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
                        <div class="form-group col-12 col-md-3 mb-4">
                            <div class="input-group">

                                <select data-column="8" name="status_filter" id="status_filter" class="form-control select-filter status_filter" style="width: 200px">
                                    <option label="Pilih Filter Status"></option>
                                    @foreach ($status_progress as $val)
                                        <option value="{{$val['value']}}">{{$val['value']}}</option>
                                    @endforeach

                                </select>
                                    <button type="button" class="btn btn-outline-danger clear_field text-danger align-self-center" data-toggle="tooltip" title="Reset" hidden><i class="fas fa-times"></i></button>

                            </div>


                        </div>
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
<div id="md_UpdateStatusDesProduk" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Update Status Progress Deskripsi Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="fm_UpdateStatusDespro">
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="kode" id="kode" value="">
                    <input type="hidden" name="judul_asli" id="judulAsli" value="">
                    <label for="adduser_name">Status: <span class="text-danger">*</span></label>
                    <select name="status" class="form-control select-status"required>
                        <option label="Pilih Status"></option>
                        @foreach ($status_progress as $sp)
                            <option value="{{$sp['value']}}">{{$sp['value']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div id="md_DesproHistory" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="titleModal"><i class="fas fa-history"></i>&nbsp;History Perubahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <div class="tickets-list" id="dataHistory">

                </div>
                <button class="btn btn-primary load-more" data-paginate="2">Load more...</button>
                {{-- <p class="invisible">No more posts...</p> --}}
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
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('jsNeeded')
<script>
    $(function() {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        let tableDesProduk = $('#tb_DesProduk').DataTable({
            "bSort": false,
            "responsive": true,
            processing: true,
            serverSide: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            ajax: "{{ route('despro.view') }}"
            ,
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'No', orderable: false, searchable: false, "width": "5%" },
                { data: 'kode', name: 'kode', title: 'Kode' },
                { data: 'judul_asli', name: 'judul_asli', title: 'Judul Asli' },
                { data: 'penulis', name: 'penulis', title: 'Penulis',"width":"15%" },
                { data: 'imprint', name: 'imprint', title: 'Imprint'},
                { data: 'judul_final', name: 'judul_final', title: 'Judul Final'},
                { data: 'tgl_deskripsi', name: 'tgl_deskripsi', title: 'Tgl Deskripsi'},
                { data: 'pembuat_deskripsi', name: 'pembuat_deskripsi', title: 'Pembuat Deskripsi',orderable: false},
                { data: 'history', name: 'history', title: 'History Progress'},
                { data: 'action', name: 'action', title: 'Action', orderable: false},
            ],

        });
        $('[name="status_filter"]').on('change', function(){
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            tableDesProduk.column( $(this).data('column') )
            .search( val ? val : '', true, false )
            .draw();
        });
    });
</script>
<script>
    $('#tb_DesProduk').on('click','.btn-history',function(e){
        var data = $(this).data('id');
        $.post("{{route('despro.history')}}", {id: data}, function(data){
            $('#dataHistory').html(data);
            // $.each(data, function(k, v) {
            //     // console.log(v);
            //     if (v.type_history == 'Status'){
            //         $('#dataHistory').append(`<span class="ticket-item">
            //             <div class="ticket-title">
            //                 <h6><span class="bullet"></span> Status deskripsi produk '`+v.status_his+`' diubah menjadi '`+v.status_new+`'.</h6>
            //             </div>
            //             <div class="ticket-info">
            //                 <div class="text-muted">Modified by <a href="{{url('/manajemen-web/user/`+v.author_id+`')}}">`+v.nama+`</a></div>
            //                 <div class="bullet"></div>
            //                 <div>`+v.modified_at+` (`+v.format_tanggal+`)</div>
            //             </div>
            //         </span>`);
            //     }
            // });
        });
    });
</script>
<script src="{{url('js/update_progress_despro.js')}}"></script>
@endsection
