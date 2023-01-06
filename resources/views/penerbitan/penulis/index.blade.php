@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Penulis</h1>
            @if (Gate::allows('do_create', 'tambah-data-penulis'))
                <div class="section-header-button">
                    <a href="{{ url('penerbitan/penulis/membuat-penulis') }}" class="btn btn-success">Tambah</a>
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
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
@endsection

@section('jsNeeded')
    <script>
        $(function() {
            let tablePenulis = $('#tb_Penulis').DataTable({
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
                    url: "{{ url('penerbitan/penulis') }}",
                    data: {
                        "request_": "table-penulis"
                    }
                },
                columns: [{
                        data: 'ktp',
                        name: 'ktp',
                        title: 'KTP'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        title: 'Nama'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        title: 'Email'
                    },
                    {
                        data: 'ponsel_domisili',
                        name: 'ponsel_domisili',
                        title: 'Ponsel'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Action',
                        searchable: false,
                        orderable: false
                    },
                ]
            })

            function ajaxDeletePenulis(data) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('penerbitan/penulis/hapus-penulis') }}",
                    data: {
                        id: data.id
                    },
                    beforeSend: function() {
                        $('.btn_DelPenulis').prop('disabled', true).
                        addClass('btn-progress')
                    },
                    success: function(result) {
                        console.log(result)
                        tablePenulis.ajax.reload();
                        swal('Data penulis ' + data.nama + ', berhasil dihapus', {
                            icon: 'success'
                        });
                    },
                    error: function(err) {
                        swal('Data penulis ' + data.nama + ' gagal dihapus!', {
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        $('.btn_DelPenulis').prop('disabled', false).
                        removeClass('btn-progress')
                    }
                })
            }

            $('#tb_Penulis').on('click', '.btn_DelPenulis', function(e) {
                e.preventDefault();
                let id = $(e.currentTarget).data('id'),
                    nama = $(e.currentTarget).data('nama');

                swal({
                        text: 'Hapus data Penulis (' + nama + ')?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((confirm_) => {
                        if (confirm_) {
                            ajaxDeletePenulis({
                                id: id,
                                nama: nama
                            });
                        }
                    });
            })
        })
    </script>
@endsection
