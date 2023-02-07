@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/SpinKit/spinkit.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Penulis</h1>
            @if (Gate::allows('do_create', 'tambah-data-penulis'))
                <div class="section-header-button">
                    <a href="{{ url('penerbitan/penulis/membuat-penulis') }}" class="btn btn-success">Tambah</a>
                </div>
                <div class="section-header-button">
                    <a href="{{ route('penulis.telah_dihapus') }}" class="btn btn-danger">Penulis Telah Dihapus</a>
                </div>
            @endif
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="col-12 table-responsive">
                                <table id="tb_Penulis" class="table table-striped dt-responsive" style="width: 100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal Format Buku -->
    @include('penerbitan.penulis.modal_history_penulis')
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/penulis.js') }}"></script>
@endsection
