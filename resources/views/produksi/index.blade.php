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
        <h1>Data Produksi</h1>
        <div class="section-header-button">
            <a href="{{ route('produksi.create')}}" class="btn btn-success">Tambah</a>
        </div>

    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped" id="tb_Produksi" style="width:100%">
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
<script src="{{url('vendors/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('jsNeeded')
<script>
    $(function() {
        let tableNaskah = $('#tb_Produksi').DataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            processing: true,
            serverSide: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            },
            columnDefs: [
			{ orderable: false, className: 'control', targets: 0 },
            { orderable: false, className: 'reorder', targets: 1 },
            { orderable: false, targets: '_all' }
        ],
            ajax: {
                url: "{{ route('produksi.view') }}",
                data: {"request_": "table-produksi"}
            },
            columns: [
                { data: 'no_order', name: 'no_order', title: 'Kode Order' },
                { data: 'tipe_order', name: 'tipe_order', title: 'Tipe Order' },
                { data: 'status_cetak', name: 'status_cetak', title: 'Status Cetak' },
                { data: 'judul_buku', name: 'judul_buku', title: 'Judul Buku'},
                { data: 'sub_judul_buku', name: 'sub_judul_buku', title: 'Sub Judul Buku'},
                { data: 'urgent', name: 'urgent', title: 'Urgent'},
                { data: 'penulis', name: 'penulis', title: 'Penulis'},
                { data: 'isbn', name: 'isbn', title: 'ISBN'},
                { data: 'eisbn', name: 'eisbn', title: 'E-ISBN'},
                { data: 'penerbit', name: 'penerbit', title: 'Penerbit'},
                { data: 'imprint', name: 'imprint', title: 'Imprint'},
                { data: 'platform_digital', name: 'platform_digital', title: 'Platform Digital'},
                { data: 'status_buku', name: 'status_buku', title: 'Status Buku'},
                { data: 'kelompok_buku', name: 'kelompok_buku', title: 'Kelompok Buku'},
                { data: 'edisi_cetakan', name: 'edisi_cetakan', title: 'Edisi Cetakan'},
                { data: 'jumlah_halaman', name: 'jumlah_halaman', title: 'Jumlah Halaman'},
                { data: 'kertas_isi', name: 'kertas_isi', title: 'Kertas Isi'},
                { data: 'warna_isi', name: 'warna_isi', title: 'Warna Isi'},
                { data: 'kertas_cover', name: 'kertas_cover', title: 'Kertas Cover'},
                { data: 'warna_cover', name: 'warna_cover', title: 'Warna Cover'},
                { data: 'efek_cover', name: 'efek_cover', title: 'Jenis Cover'},
                { data: 'jahit_kawat', name: 'jahit_kawat', title: 'Jahit Kawat'},
                { data: 'jahit_benang', name: 'jahit_benang', title: 'Jahit Benang'},
                { data: 'bending', name: 'bending', title: 'Bending'},
                { data: 'tanggal_terbit', name: 'tanggal_terbit', title: 'Tanggal Terbit'},
                { data: 'buku_jadi', name: 'buku_jadi', title: 'Buku Jadi'},
                { data: 'jumlah_cetak', name: 'jumlah_cetak', title: 'Jumlah Cetak'},
                { data: 'spp', name: 'spp', title: 'SPP'},
                { data: 'buku_contoh', name: 'buku_contoh', title: 'Buku Contoh'},
                { data: 'keterangan', name: 'keterangan', title: 'Keterangan'},
                { data: 'perlengkapan', name: 'perlengkapan', title: 'Perlengkapan'},
                { data: 'created_at', name: 'created_at', title: 'Created At'},
                { data: 'created_by', name: 'created_by', title: 'Created By'},
                { data: 'action', name: 'action', title: 'Action', searchable: false, orderable: false},
            ]
        });

    })
</script>
@endsection
