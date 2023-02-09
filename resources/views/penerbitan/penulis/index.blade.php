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
    {{-- <div class="tickets-list" id="dataHistory">
        <span class="ticket-item" id="newAppend">
            <div class="ticket-title">
                <span>
                    <span class="bullet"></span> Alamat domisili <b class="text-dark">Jl. Anugerah Sejahtera No.999</b>
                    ditambahkan.
                </span>
            </div>
            <div class="ticket-title">
                <span>
                    <span class="bullet"></span> Sosmed facebook <b class="text-dark">FB</b> ditambahkan.
                </span>
            </div>
            <div class="ticket-title">
                <span>
                    <span class="bullet"></span> Sosmed instagram <b class="text-dark">IG</b> ditambahkan.
                </span>
            </div>
            <div class="ticket-title">
                <span>
                    <span class="bullet"></span> Sosmed twitter <b class="text-dark">TW</b>
                </span>
                ditambahkan.
            </div>
            <div class="ticket-info">
                <div class="text-muted pt-2">Modified by <a
                        href="http://127.0.0.1:8000/manajemen-web/user/be8d42fa88a14406ac201974963d9c1b">Super
                        Admin</a></div>
                <div class="bullet pt-2"></div>
                <div class="pt-2">55 menit yang lalu (Selasa 07 Feb 2023, 14:59)</div>
            </div>
        </span>
        <span class="ticket-item" id="newAppend">
            <div class="ticket-title">
                <span>
                    <span class="bullet"></span> Penulis <b class="text-dark">Jalil</b>
                    diubah menjadi <b class="text-dark">Jalil Eksotis</b>
                </span>
            </div>
            <div class="ticket-title">
                <span><span class="bullet"></span> Email <b class="text-dark">jalil@gmail.com</b>
                    ditambahkan.<div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a
                                href="http://127.0.0.1:8000/manajemen-web/user/be8d42fa88a14406ac201974963d9c1b">Super
                                Admin</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">1 jam yang lalu (Selasa 07 Feb 2023,
                            14:25)</div>
                    </div>
                </span>
            </div>
        </span>
        <span class="ticket-item" id="newAppend">
            <div class="ticket-title">
                <span><span class="bullet"></span> Tanggal lahir <b class="text-dark">01 Feb 2023</b> diubah menjadi <b
                        class="text-dark">20 Feb 2023</b>
                    <div class="ticket-title">
                        <span><span class="bullet"></span> Ponsel domisili <b class="text-dark">09</b> diubah menjadi <b
                                class="text-dark">09-00</b>
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Telepon domisili <b class="text-dark">09</b> diubah
                                    menjadi <b class="text-dark">09-00</b>
                                    <div class="ticket-info">
                                        <div class="text-muted pt-2">Modified by <a
                                                href="http://127.0.0.1:8000/manajemen-web/user/be8d42fa88a14406ac201974963d9c1b">Super
                                                Admin</a></div>
                                        <div class="bullet pt-2"></div>
                                        <div class="pt-2">1 jam yang lalu (Selasa 07 Feb 2023, 14:10)</div>
                                    </div>
                                </span>
                            </div>
                        </span>
                    </div>
                </span>
            </div>
        </span>

        <span class="ticket-item" id="newAppend">
            <div class="ticket-title">
                <span>
                    <span class="bullet"></span> Tanggal lahir <b class="text-dark">01 Feb
                        2023</b> ditambahkan.
                    <div class="ticket-title">
                        <span>
                            <span class="bullet"></span> Ponsel domisili <b class="text-dark">12</b>
                            diubah menjadi <b class="text-dark">09</b>
                            <div class="ticket-title">
                                <span><span class="bullet"></span> Telepon domisili <b class="text-dark">12</b>
                                    diubah menjadi <b class="text-dark">09</b>
                                    <div class="ticket-info">
                                        <div class="text-muted pt-2">Modified by <a
                                                href="http://127.0.0.1:8000/manajemen-web/user/be8d42fa88a14406ac201974963d9c1b">Super
                                                Admin</a></div>
                                        <div class="bullet pt-2"></div>
                                        <div class="pt-2">1 jam yang lalu (Selasa 07 Feb 2023,
                                            14:03)</div>
                                    </div>
                                </span>
                            </div>
                        </span>
                    </div>
                </span>
            </div>
        </span>
        <span class="ticket-item" id="newAppend">
            <div class="ticket-title">
                <span>
                    <span class="bullet"></span> Penulis dengan nama <b class="text-dark">Jalil</b> ditambahkan.
                    <div class="ticket-info">
                        <div class="text-muted pt-2">Modified by <a
                                href="http://127.0.0.1:8000/manajemen-web/user/be8d42fa88a14406ac201974963d9c1b">Super
                                Admin</a></div>
                        <div class="bullet pt-2"></div>
                        <div class="pt-2">1 jam yang lalu (Selasa 07 Feb 2023, 14:02)</div>
                    </div>
                </span>
            </div>
        </span>
    </div> --}}




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
