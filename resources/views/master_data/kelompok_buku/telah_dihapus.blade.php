@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
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
            <div class="section-header-back">
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Data Kelompok Buku Yang Telah Dihapus</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-danger">
                        <div class="card-body">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped" id="tb_KelompokBuku" style="width:100%">
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
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('jsNeeded')
    <script>
        $(function() {
            let tableDelKBuku = $('#tb_KelompokBuku').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                },
                ajax: {
                    url: "{{ url('master/kelompok-buku/kelompok-buku-telah-dihapus') }}",
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                        title: 'No'
                    },
                    {
                        data: 'kelompok_buku',
                        name: 'kelompok_buku',
                        title: 'Nama Kelompok Buku'
                    },
                    {
                        data: 'tgl_dibuat',
                        name: 'tgl_dibuat',
                        title: 'Tanggal Dibuat'
                    },
                    {
                        data: 'dibuat_oleh',
                        name: 'dibuat_oleh',
                        title: 'Dibuat Oleh'
                    },
                    {
                        data: 'dihapus_pada',
                        name: 'dihapus_pada',
                        title: 'Dihapus Pada'
                    },
                    {
                        data: 'dihapus_oleh',
                        name: 'dihapus_oleh',
                        title: 'Dihapus Oleh'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Action',
                        searchable: false,
                        orderable: false
                    },
                ]
            });

            // Restore KBuku Start
            $(document).ready(function() {
                function ajaxRestoreKBuku(data) {
                    $.ajax({
                        type: "POST",
                        url: window.location.origin +
                            "/master/kelompok-buku/restore",
                        data: data,
                        beforeSend: function() {
                            $(".btn_ResKBuku")
                                .prop("disabled", true)
                                .addClass("btn-progress");
                        },
                        success: function(result) {
                            if (result.status == "success") {
                                tableDelKBuku.ajax.reload();
                                notifToast(result.status, result.message);
                            }
                        },
                        error: function(err) {},
                        complete: function() {
                            $(".btn_ResKBuku")
                                .prop("disabled", true)
                                .removeClass("btn-progress");
                        },
                    });
                }
                $(document).on("click", ".btn_ResKBuku", function(e) {
                    let nama = $(this).data("nama"),
                        id = $(this).data("id");
                    swal({
                        text: "Kembalikan data kelompok buku (" + nama + ")?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((confirm_) => {
                        if (confirm_) {
                            ajaxRestoreKBuku({
                                id: id
                            })
                        }
                    });
                });
            });
            // Restore KBuku End
        })
    </script>
@endsection
