@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
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
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Penerbitan Editing Proses</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="form-group col-auto col-md-3">
                                <div class="input-group">
                                    <select data-column="9" name="status_filter" id="status_filter" class="form-control select-filter status_filter" style="width: 200px">
                                        <option label="Pilih Filter Status"></option>
                                        @foreach ($status_progress as $val)
                                        <option value="{{ $val }}">{{ $val }}&nbsp;&nbsp;</option>
                                        @endforeach

                                    </select>
                                    <button type="button" class="btn btn-outline-danger clear_field text-danger align-self-center" data-toggle="tooltip" title="Reset" hidden><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <div class="col-auto">
                                <span class="badge badge-warning"><i class="fas fa-database"></i> Total data editing
                                    naskah: <b>{{ $count }}</b></span>
                            </div>
                        </div>
                        <div class="col-12 table-responsive">
                            <table id="tb_Editing" class="table table-striped dt-responsive" style="width:100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="md_UpdateStatusEditing" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Update Status Progress Editing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="fm_UpdateStatusEditing">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="kode" id="kode" value="">
                        <input type="hidden" name="judul_final" id="judulFinal" value="">
                        <label for="statusE">Status: <span class="text-danger">*</span></label>
                        <select name="status" class="form-control select-status" id="statusE" required>
                            <option label="Pilih Status"></option>
                            @foreach ($status_progress as $sp)
                            <option value="{{ $sp }}">{{ $sp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- <div class="form-group" style="display:none" id="ketStatusPending">
                        <label for="ketPEnd">Keterangan Pending: <span class="text-danger">*</span></label>
                        <textarea name="ket_pending" class="form-control" id="ketPEnd" cols="30" rows="10" required></textarea>
                    </div> -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="md_EditingHistory" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModalEditing" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="titleModalEditing"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body example-1 scrollbar-deep-purple bordered-deep-purple thin">
                <div class="tickets-list" id="dataHistoryEditing">

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
<script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ url('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
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
        let tableDesProduk = $('#tb_Editing').DataTable({
            "bSort": false,
            "responsive": true,
            "autoWidth": true,
            processing: true,
            serverSide: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            ajax: "{{ route('editing.view') }}",
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'No', orderable: false, searchable: false, "width": "5%" },
                {
                    data: 'kode',
                    name: 'kode',
                    title: 'Kode'
                },
                {
                    data: 'judul_final',
                    name: 'judul_final',
                    title: 'Judul Final'
                },
                {
                    data: 'penulis',
                    name: 'penulis',
                    title: 'Penulis',
                },
                {
                    data: 'nama_pena',
                    name: 'nama_pena',
                    title: 'Nama Pena',
                },
                {
                    data: 'jalur_buku',
                    name: 'jalur_buku',
                    title: 'Jalur Buku'
                },
                {
                    data: 'tgl_masuk_editing',
                    name: 'tgl_masuk_editing',
                    title: 'Tgl Masuk Editing'
                },
                {
                    data: 'pic_prodev',
                    name: 'pic_prodev',
                    title: 'PIC Prodev'
                },
                {
                    data: 'editor',
                    name: 'editor',
                    title: 'Editor'
                },
                // {
                //     data: 'copy_editor',
                //     name: 'copy_editor',
                //     title: 'Copy Editor'
                // },
                {
                    data: 'history',
                    name: 'history',
                    title: 'History Progress'
                },
                {
                    data: 'action',
                    name: 'action',
                    title: 'Action',
                    orderable: false
                },
            ],

        });
        $('[name="status_filter"]').on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            tableDesProduk.column($(this).data('column'))
                .search(val ? val : '', true, false)
                .draw();
        });
    });
</script>
<script>
    $('#tb_Editing').on('click', '.btn-history', function(e) {
        var id = $(this).data('id');
        var judul = $(this).data('judulfinal');
        $.post("{{ route('editing.history') }}", {
            id: id
        }, function(data) {
            // console.log(data);
            $('#titleModalEditing').html(
                '<i class="fas fa-history"></i>&nbsp;History Perubahan Naskah "' + judul + '"');
            $('#load_more').data('id', id);
            $('#dataHistoryEditing').html(data);
            $('#md_EditingHistory').modal('show');
        });
    });
</script>
<script src="{{ url('js/update_progress_editing.js') }}"></script>
@endsection
