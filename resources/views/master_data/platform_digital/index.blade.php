@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css"> --}}
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/SpinKit/spinkit.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Platform Digital</h1>
            @if (Gate::allows('do_create', 'buat-platform-digital'))
                <div class="section-header-button">
                    <a href="{{ route('platform.create') }}" class="btn btn-success">Tambah</a>
                </div>
                <div class="section-header-button">
                    <a href="{{ route('platform.telah_dihapus') }}" class="btn btn-danger">Platform Telah Dihapus</a>
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
    <div id="md_PlatformHistory" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleModalPlatform"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="titleModalPlatform"><i class="fas fa-history"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body example-1 scrollbar-deep-purple bordered-deep-purple thin">
                    <div class="tickets-list" id="dataHistoryPlatform">

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="d-block btn btn-sm btn-outline-primary btn-block load-more" id="load_more"
                        data-paginate="2" data-id="">Load more</button>
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
    <script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/delete_platform.js') }}"></script>
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
                    data: {
                        "request_": "table-platform"
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                        title: 'No'
                    },
                    {
                        data: 'nama_platform',
                        name: 'nama_platform',
                        title: 'Nama Platform'
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
                        data: 'diubah_terakhir',
                        name: 'diubah_terakhir',
                        title: 'Diubah Terakhir'
                    },
                    {
                        data: 'diubah_oleh',
                        name: 'diubah_oleh',
                        title: 'Diubah Oleh'
                    },
                    {
                        data: 'history',
                        name: 'history',
                        title: 'History Data'
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
        })
    </script>
    <script>
        $('#tb_Platform').on('click', '.btn-history', function(e) {
            var id = $(this).data('id');
            var judul = $(this).data("nama");
            $.post(window.location.origin +
                "/master/platform-digital/lihat-history", {
                    id: id
                },
                function(data) {
                    $("#titleModalPlatform").html(
                        '<i class="fas fa-history"></i>&nbsp;History Perubahan Platform Ebook "' +
                        judul +
                        '"'
                    );
                    $("#load_more").data("id", id);
                    $("#dataHistoryPlatform").html(data);
                    $("#md_PlatformHistory").modal("show");
                });
        });
        $(function() {
            $(".load-more").click(function(e) {
                e.preventDefault();
                var page = $(this).data("paginate");
                var id = $(this).data("id");
                $(this).data("paginate", page + 1);

                $.ajax({
                    url: window.location.origin +
                        "/master/platform-digital/lihat-history",
                    data: {
                        id: id,
                        page: page,
                    },
                    type: "post",
                    beforeSend: function() {
                        $(".load-more").text("Loading...");
                    },
                    success: function(response) {
                        // console.log(response);
                        if (response.length == 0) {
                            notifToast("error", "Tidak ada data lagi");
                        }
                        $("#dataHistoryPlatform").append(response);
                        // Setting little delay while displaying new content
                        // setTimeout(function() {
                        //     // appending posts after last post with class="post"
                        //     $("#dataHistory:last").htnl(response).show().fadeIn("slow");
                        // }, 2000);
                    },
                    complete: function(params) {
                        $(".load-more").text("Load more").fadeIn("slow");
                    },
                });
            });
        });
    </script>
@endsection
