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
        <h1>Data Penerbitan Order E-Book</h1>
        {{-- @if(Gate::allows('do_create', 'tambah-produksi-ebook'))
        <div class="section-header-button">
            <a href="{{ route('ebook.create')}}" class="btn btn-success">Tambah</a>
        </div>
        @endif --}}
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
                                <span class="badge badge-warning"><i class="fas fa-database"></i> Total data order e-book: <b>{{ $count }}</b></span>
                            </div>
                        </div>
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id="tb_OrderEbook" style="width:100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="md_UpdateStatusSetter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Update Status Progress Pracetak Setter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="fm_UpdateStatusSetter">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="kode" id="kode" value="">
                        <input type="hidden" name="judul_final" id="judulFinal" value="">
                        <label for="statusSet">Status: <span class="text-danger">*</span></label>
                        <select name="status" class="form-control select-status" id="statusSet" required>
                            <option label="Pilih Status"></option>
                            @foreach ($status_progress as $sp)
                            <option value="{{ $sp }}">{{ $sp }}</option>
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
<div id="md_SetterHistory" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModalSetter" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="titleModalSetter"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body example-1 scrollbar-deep-purple bordered-deep-purple thin">
                <div class="tickets-list" id="dataHistorySetter">

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
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('jsNeeded')

<script src="{{url('js/update_progress_orebook.js')}}"></script>
@endsection