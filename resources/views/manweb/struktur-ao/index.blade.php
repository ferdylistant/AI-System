@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Struktur AO</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Data Cabang</h4>
                            <div class="card-header-action">
                                <button id="btn_AddCabang" class="btn btn-success" data-toggle="modal"
                                    data-target="#md_AddCabang" data-backdrop="static">Tambah</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 table-responsive">
                                <table id="tb_Cabang" class="table table-striped" style="width: 100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Data Level Pekerjaan</h4>
                            <div class="card-header-action">
                                <button id="btn_AddJobLevel" class="btn btn-success" data-toggle="modal"
                                    data-target="#md_AddJobLevel" data-backdrop="static">Tambah</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 table-responsive">
                                <table id="tb_JobLevel" class="table table-striped" style="width: 100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Data Divisi</h4>
                            <div class="card-header-action">
                                <a href="#" class="btn btn-success" data-tipe="Divisi" data-toggle="modal"
                                    data-target="#md_AddDivJab" data-backdrop="static">Tambah</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 table-responsive">
                                <table id="tb_Divisi" class="table table-striped" style="width: 100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Data Jabatan</h4>
                            <div class="card-header-action">
                                <a href="#" class="btn btn-success" data-tipe="Jabatan" data-toggle="modal"
                                    data-target="#md_AddDivJab" data-backdrop="static">Tambah</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 table-responsive">
                                <table id="tb_Jabatan" class="table table-striped" style="width: 100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal Cabang -->
    <div class="modal fade" tabindex="-1" role="dialog" id="md_AddCabang">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">

                </div>
                <div class="modal-body">
                    <form id="fm_AddCabang">
                        {!! csrf_field() !!}
                        <h5 class="modal-title mb-3">#Tambah Cabang</h5>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Kode</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" name="add_kode" class="form-control" placeholder="Kode Cabang">
                                <div id="err_add_kode"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Nama</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" name="add_nama" class="form-control" placeholder="Nama Cabang">
                                <div id="err_add_nama"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Telepon</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" name="add_telp" class="form-control" placeholder="Telepon Cabang">
                                <div id="err_add_telp"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Alamat</label>
                            <div class="col-sm-12 col-md-9">
                                <textarea class="form-control" name="add_alamat" placeholder="Alamat lengkap Cabang"></textarea>
                                <div id="err_add_alamat"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-success" form="fm_AddCabang">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-progress" tabindex="-1" role="dialog" id="md_EditCabang">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">

                </div>
                <div class="modal-body">
                    <form id="fm_EditCabang">
                        {!! csrf_field() !!}
                        <h5 class="modal-title mb-3">#Ubah Cabang</h5>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Kode</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" name="edit_kode" class="form-control" placeholder="Kode Cabang"
                                    disabled>
                                <input type="hidden" name="edit_id" class="form-control" value="">
                                <div id="err_edit_kode"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Nama</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" name="edit_nama" class="form-control" placeholder="Nama Cabang">
                                <input type="hidden" name="edit_oldnama" class="form-control"
                                    placeholder="Nama Cabang">
                                <div id="err_edit_nama"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Telepon</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" name="edit_telp" class="form-control"
                                    placeholder="Telepon Cabang">
                                <div id="err_edit_telp"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Alamat</label>
                            <div class="col-sm-12 col-md-9">
                                <textarea class="form-control" name="edit_alamat" placeholder="Alamat lengkap Cabang"></textarea>
                                <div id="err_edit_alamat"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-warning" form="fm_EditCabang">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Level Pekerjaan -->
    <div class="modal fade" tabindex="-1" role="dialog" id="md_AddJobLevel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">

                </div>
                <div class="modal-body">
                    <form id="fm_AddJobLevel">
                        {!! csrf_field() !!}
                        <h5 class="modal-title mb-3">#Tambah Level Pekerjaan</h5>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Nama <span class="text-danger">*</span></label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" name="add_nama" class="form-control" placeholder="Nama Level Pekerjaan">
                                <div id="err_add_nama"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-success" form="fm_AddJobLevel">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-progress" tabindex="-1" role="dialog" id="md_EditJobLevel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">

                </div>
                <div class="modal-body">
                    <form id="fm_EditJobLevel">
                        {!! csrf_field() !!}
                        <input type="hidden" name="edit_id" class="form-control" value="">
                        <h5 class="modal-title mb-3">#Ubah Level Pekerjaan</h5>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Nama <span class="text-danger">*</span></label>
                            <div class="col-sm-12 col-md-9">
                                <input type="hidden" name="edit_oldnama" class="form-control"
                                    placeholder="Nama Level Pekerjaan">
                                <input type="text" name="edit_nama" class="form-control" placeholder="Nama Level Pekerjaan">
                                <div id="err_edit_nama"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-warning" form="fm_EditJobLevel">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Divisi -->
    <div class="modal fade modal-progress" tabindex="-1" role="dialog" id="md_AddDivJab">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success"></div>
                <div class="modal-body">
                    <form id="fm_AddDivJab">
                        {!! csrf_field() !!}
                        <h5 class="modal-title mb-3"></h5>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Nama</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="hidden" name="add_type" value="">
                                <input type="text" name="add_djnama" class="form-control" placeholder="">
                                <div id="err_add_djnama"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-success" form="fm_AddDivJab">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-progress" tabindex="-1" role="dialog" id="md_EditDivJab">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning"></div>
                <div class="modal-body">
                    <form id="fm_EditDivJab">
                        {!! csrf_field() !!}
                        <h5 class="modal-title mb-3"></h5>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3">Nama</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="hidden" name="edit_type" value="">
                                <input type="hidden" name="edit_djid" value="">
                                <input type="hidden" name="edit_djoldnama" value="">
                                <input type="text" name="edit_djnama" class="form-control" placeholder="">
                                <div id="err_edit_djnama"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-warning" form="fm_EditDivJab">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    {{-- <script src="{{url('vendors/datatables.net-select-bs4/js/select.bootstrap4.min.js')}}"></script> --}}
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
@endsection

@section('jsNeeded')
    <script>
        $(function() {
            let tableCabang = $('#tb_Cabang').DataTable({
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
                ajax: {
                    url: "{{ url('manajemen-web/struktur-ao') }}",
                    data: {
                        "request_": "table-cabang"
                    }
                },
                columns: [{
                        data: 'kode',
                        name: 'kode',
                        title: 'Kode'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        title: 'Nama'
                    },
                    {
                        data: 'telp',
                        name: 'telp',
                        sDefaultContent: '-',
                        title: 'Telepon'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
                        sDefaultContent: '-',
                        title: 'Alamat'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Aksi',
                        searchable: false,
                        orderable: false
                    },
                ],
            });
            let tableJobLevel = $('#tb_JobLevel').DataTable({
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
                ajax: {
                    url: "{{ url('manajemen-web/struktur-ao') }}",
                    data: {
                        "request_": "table-job-level"
                    }
                },
                columns: [
                    {
                        data: 'nama',
                        name: 'nama',
                        title: 'Nama'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Aksi',
                        searchable: false,
                        orderable: false
                    },
                ],
            });

            let tableDivisi = $('#tb_Divisi').DataTable({
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
                ajax: {
                    url: "{{ url('manajemen-web/struktur-ao') }}",
                    data: {
                        "request_": "table-divisi"
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        title: 'No',
                        orderable: false,
                        searchable: false,
                        "width": "10%"
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        title: 'Nama'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Aksi',
                        searchable: false,
                        orderable: false
                    },
                ],
            });

            let tableJabatan = $('#tb_Jabatan').DataTable({
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
                ajax: {
                    url: "{{ url('manajemen-web/struktur-ao') }}",
                    data: {
                        "request_": "table-jabatan"
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        title: 'No',
                        orderable: false,
                        searchable: false,
                        "width": "10%"
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        title: 'Nama'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: 'Aksi',
                        searchable: false,
                        orderable: false
                    },
                ],
            });

            let addCabang = jqueryValidation_('#fm_AddCabang', {
                add_kode: {
                    required: true,
                    number: true,
                    maxlength: 4,
                    minlength: 4
                },
                add_nama: {
                    required: true,
                },
                add_telp: {
                    number: true
                }
            });
            let editCabang = jqueryValidation_('#fm_EditCabang', {
                edit_nama: {
                    required: true,
                },
                edit_telp: {
                    number: true
                }
            });
            let addJobLevel = jqueryValidation_('#fm_AddJobLevel', {
                add_nama: {
                    required: true,
                },
            });
            let editJobLevel = jqueryValidation_('#fm_EditJobLevel', {
                edit_nama: {
                    required: true,
                },
            });
            let addDivJab = jqueryValidation_('#fm_AddDivJab', {
                add_djnama: {
                    required: true,
                }
            });
            let editDivJab = jqueryValidation_('#fm_EditDivJab', {
                edit_djnama: {
                    required: true,
                }
            });

            function ajaxAddCabang(data) {
                let el = data.get(0);
                console.log(data)
                $.ajax({
                    type: "POST",
                    url: "{{ url('manajemen-web/struktur-ao/create/cabang') }}",
                    data: new FormData(el),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true).
                        addClass('btn-progress')
                    },
                    success: function(result) {
                        tableCabang.ajax.reload();
                        notifToast('success', 'Data cabang berhasil disimpan!');
                        data.trigger('reset');
                        $("#md_AddCabang").modal("hide");

                    },
                    error: function(err) {
                        rs = err.responseJSON.errors;
                        if (rs !== undefined) {
                            err = {};
                            Object.entries(rs).forEach(entry => {
                                let [key, value] = entry;
                                err[key] = value
                            })
                            addCabang.showErrors(err);
                        }
                        notifToast('error', 'Data cabang gagal disimpan!');
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).
                        removeClass('btn-progress')
                    }
                })
            }

            function ajaxEditCabang(data) {
                let el = data.get(0);

                $.ajax({
                    type: "POST",
                    url: "{{ url('manajemen-web/struktur-ao/update/cabang') }}",
                    data: new FormData(el),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true).
                        addClass('btn-progress')
                    },
                    success: function(result) {
                        tableCabang.ajax.reload();
                        notifToast('success', 'Data cabang berhasil disimpan!');
                    },
                    error: function(err) {
                        rs = err.responseJSON.errors;
                        if (rs !== undefined) {
                            err = {};
                            Object.entries(rs).forEach(entry => {
                                let [key, value] = entry;
                                err[key] = value
                            })
                            editCabang.showErrors(err);
                        }
                        notifToast('error', 'Data cabang gagal disimpan!');
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).
                        removeClass('btn-progress')
                    }
                })
            }

            function ajaxDeleteCabang(data) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('manajemen-web/struktur-ao/delete/cabang') }}",
                    data: data,
                    beforeSend: function() {
                        $('.btn_DelCabang').prop('disabled', true).addClass('btn-progress')
                    },
                    success: function(result) {
                        tableCabang.ajax.reload();
                        notifToast('success', 'Data cabang berhasil dihapus!');
                    },
                    error: function(err) {},
                    complete: function() {
                        $('.btn_DelCabang').prop('disabled', true).removeClass('btn-progress')
                    }
                })
            }

            function ajaxAddJobLevel(data) {
                let el = data.get(0);
                console.log(data)
                $.ajax({
                    type: "POST",
                    url: "{{ url('manajemen-web/struktur-ao/create/job-level') }}",
                    data: new FormData(el),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true).
                        addClass('btn-progress')
                    },
                    success: function(result) {
                        tableJobLevel.ajax.reload();
                        notifToast('success', 'Data level pekerjaan berhasil disimpan!');
                        data.trigger('reset');
                        $("#md_AddJobLevel").modal("hide");

                    },
                    error: function(err) {
                        rs = err.responseJSON.errors;
                        if (rs !== undefined) {
                            err = {};
                            Object.entries(rs).forEach(entry => {
                                let [key, value] = entry;
                                err[key] = value
                            })
                            addJobLevel.showErrors(err);
                        }
                        notifToast('error', 'Data level pekerjaan gagal disimpan!');
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).
                        removeClass('btn-progress')
                    }
                })
            }

            function ajaxEditJobLevel(data) {
                let el = data.get(0);

                $.ajax({
                    type: "POST",
                    url: "{{ url('manajemen-web/struktur-ao/update/job-level') }}",
                    data: new FormData(el),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true).
                        addClass('btn-progress')
                    },
                    success: function(result) {
                        tableJobLevel.ajax.reload();
                        notifToast('success', 'Data level pekerjaan berhasil disimpan!');
                    },
                    error: function(err) {
                        rs = err.responseJSON.errors;
                        if (rs !== undefined) {
                            err = {};
                            Object.entries(rs).forEach(entry => {
                                let [key, value] = entry;
                                err[key] = value
                            })
                            editJobLevel.showErrors(err);
                        }
                        notifToast('error', 'Data level pekerjaan gagal disimpan!');
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).
                        removeClass('btn-progress')
                    }
                })
            }
            function ajaxDeleteJobLevel(data) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('manajemen-web/struktur-ao/delete/cabang') }}",
                    data: data,
                    beforeSend: function() {
                        $('.btn_DelCabang').prop('disabled', true).addClass('btn-progress')
                    },
                    success: function(result) {
                        tableCabang.ajax.reload();
                        notifToast('success', 'Data cabang berhasil dihapus!');
                    },
                    error: function(err) {},
                    complete: function() {
                        $('.btn_DelCabang').prop('disabled', true).removeClass('btn-progress')
                    }
                })
            }

            function ajaxAddDivJab(data, cat, type) {
                let el = data.get(0);
                type = type.toLowerCase();

                $.ajax({
                    type: "POST",
                    url: "{!! url('manajemen-web/struktur-ao/"+cat+"/"+type+"') !!}",
                    data: new FormData(el),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true).
                        addClass('btn-progress')
                    },
                    success: function(result) {
                        notifToast('success', 'Data ' + type + ' berhasil disimpan!');
                        if (type == 'divisi') {
                            tableDivisi.ajax.reload();
                        } else if (type == 'jabatan') {
                            tableJabatan.ajax.reload();
                        }
                    },
                    error: function(err) {
                        rs = err.responseJSON.errors;
                        if (rs !== undefined) {
                            err = {};
                            Object.entries(rs).forEach(entry => {
                                let [key, value] = entry;
                                err[key] = value
                            })
                            addDivJab.showErrors(err);
                        }
                        notifToast('error', 'Data ' + type + ' gagal disimpan!');
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).
                        removeClass('btn-progress')
                    }
                })
            }

            function ajaxEditDivJab(data, cat, type) {
                let el = data.get(0);
                type = type.toLowerCase();

                $.ajax({
                    type: "POST",
                    url: "{!! url('manajemen-web/struktur-ao/"+cat+"/"+type+"') !!}",
                    data: new FormData(el),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true).
                        addClass('btn-progress')
                    },
                    success: function(result) {
                        notifToast('success', 'Data ' + type + ' berhasil diubah!');
                        if (type == 'divisi') {
                            tableDivisi.ajax.reload();
                        } else if (type == 'jabatan') {
                            tableJabatan.ajax.reload();
                        }
                    },
                    error: function(err) {
                        rs = err.responseJSON.errors;
                        if (rs !== undefined) {
                            err = {};
                            Object.entries(rs).forEach(entry => {
                                let [key, value] = entry;
                                err[key] = value
                            })
                            editDivJab.showErrors(err);
                        }
                        notifToast('error', 'Data ' + type + ' gagal diubah!');
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).
                        removeClass('btn-progress')
                    }
                })
            }

            function ajaxDeleteDivJab(data, type) {
                type = type.toLowerCase();

                $.ajax({
                    type: "POST",
                    url: "{!! url('manajemen-web/struktur-ao/delete/"+type+"') !!}",
                    data: data,
                    beforeSend: function() {
                        $('.btn_DelDivJab').prop('disabled', true).addClass('btn-progress')
                    },
                    success: function(result) {
                        notifToast('success', 'Data ' + type + ' berhasil dihapus!');
                        if (type == 'divisi') {
                            tableDivisi.ajax.reload();
                        } else if (type == 'jabatan') {
                            tableJabatan.ajax.reload();
                        }
                    },
                    error: function(err) {},
                    complete: function() {
                        $('.btn_DelDivJab').prop('disabled', true).removeClass('btn-progress')
                    }
                })
            }

            // On Submit //
            $('#fm_AddCabang').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    let cabang = $(this).find('[name="add_nama"]').val();
                    swal({
                            text: 'Tambah data Cabang (' + cabang + ')?',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((confirm_) => {
                            if (confirm_) {
                                ajaxAddCabang($(this))
                            }
                        });
                }
            })
            $('#fm_AddJobLevel').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    let jobLevel = $(this).find('[name="add_nama"]').val();
                    swal({
                            text: 'Tambah data level pekerjaan (' + jobLevel + ')?',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((confirm_) => {
                            if (confirm_) {
                                ajaxAddJobLevel($(this))
                            }
                        });
                }
            })

            $('#fm_AddDivJab').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    let divisi = $(this).find('[name="add_djnama"]').val(),
                        type = $(this).find('[name="add_type"]').val();
                    swal({
                            text: 'Tambah data ' + type + ' (' + divisi + ')?',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((confirm_) => {
                            if (confirm_) {
                                ajaxAddDivJab($(this), 'create', type);
                            }
                        });
                }
            });

            $('#fm_EditCabang').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    let cabang = $(this).find('[name="edit_oldnama"]').val();
                    swal({
                            text: 'Ubah data Cabang (' + cabang + ')?',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((confirm_) => {
                            if (confirm_) {
                                ajaxEditCabang($(this))
                            }
                        });

                }
            })
            $('#fm_EditJobLevel').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    let cabang = $(this).find('[name="edit_oldnama"]').val();
                    swal({
                            text: 'Ubah data level pekerjaan (' + cabang + ')?',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((confirm_) => {
                            if (confirm_) {
                                ajaxEditJobLevel($(this))
                            }
                        });

                }
            })

            $('#fm_EditDivJab').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    let divisi = $(this).find('[name="edit_djoldnama"]').val(),
                        type = $(this).find('[name="edit_type"]').val();
                    swal({
                            text: 'Ubah data ' + type + ' (' + divisi + ')?',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((confirm_) => {
                            if (confirm_) {
                                ajaxEditDivJab($(this), 'update', type);
                            }
                        });
                }
            });

            // On Modal Open //
            $('#md_EditCabang').on('shown.bs.modal', function(e) {
                let id = $(e.relatedTarget).data('id'),
                    form_ = $(this);
                $.ajax({
                    url: "{{ url('manajemen-web/struktur-ao/') }}",
                    data: {
                        request_: 'cabang',
                        id: id
                    },
                    success: function(result) {
                        Object.entries(result).forEach(entry => {
                            let [key, value] = entry;
                            form_.find('[name="edit_' + key + '"]').val(value);
                        });
                        form_.removeClass('modal-progress');
                    },
                })
            })
            $('#md_EditJobLevel').on('shown.bs.modal', function(e) {
                let id = $(e.relatedTarget).data('id'),
                    form_ = $(this);
                $.ajax({
                    url: "{{ url('manajemen-web/struktur-ao/') }}",
                    data: {
                        request_: 'job-level',
                        id: id
                    },
                    success: function(result) {
                        Object.entries(result).forEach(entry => {
                            let [key, value] = entry;
                            form_.find('[name="edit_' + key + '"]').val(value);
                        });
                        form_.removeClass('modal-progress');
                    },
                })
            })
            $('#md_EditCabang').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $(this).addClass('modal-progress');
            })
            $('#md_EditJobLevel').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $(this).addClass('modal-progress');
            })

            $('#md_AddDivJab').on('shown.bs.modal', function(e) {
                let type = $(e.relatedTarget).data('tipe');
                $(this).find('.modal-body h5').html('#Tambah ' + type)
                $(this).find('[name="add_type"]').val(type);
                $(this).find('[name="add_djnama"]').attr('placeholder', 'Tambah ' + type);
                $(this).removeClass('modal-progress');
            });
            $('#md_AddDivJab').on('hidden.bs.modal', function(e) {
                $(this).find('[name="add_djnama"]').attr('placeholder', '').removeClass('is-invalid');
                $(this).find('form').trigger('reset');
                $(this).addClass('modal-progress');
            });

            $('#md_EditDivJab').on('shown.bs.modal', function(e) {
                let type = $(e.relatedTarget).data('tipe'),
                    nama = $(e.relatedTarget).data('nama'),
                    id = $(e.relatedTarget).data('id');

                $(this).find('.modal-body h5').html('#Ubah ' + type)
                $(this).find('[name="edit_type"]').val(type);
                $(this).find('[name="edit_djid"]').val(id);
                $(this).find('[name="edit_djoldnama"]').val(nama);
                $(this).find('[name="edit_djnama"]').val(nama).attr('placeholder', 'Tambah ' + type);
                $(this).removeClass('modal-progress');
            })
            $('#md_EditDivJab').on('hidden.bs.modal', function() {
                $(this).find('[name="edit_djnama"]').attr('placeholder', '').removeClass('is-invalid');
                $(this).find('form').trigger('reset');
                $(this).addClass('modal-progress');
            })

            // Proses Hapus //
            $('#tb_Cabang').on('click', '.btn_DelCabang', function(e) {
                let cabang = $(this).data('nama'),
                    id = $(this).data('id');

                swal({
                        text: 'Hapus data Cabang (' + cabang + ')?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((confirm_) => {
                        if (confirm_) {
                            ajaxDeleteCabang({
                                id: id
                            });
                        }
                    });
            })
            $('#tb_JobLevel').on('click', '.btn_DelJobLevel', function(e) {
                let cabang = $(this).data('nama'),
                    id = $(this).data('id');

                swal({
                        text: 'Hapus data level pekerjaan (' + cabang + ')?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((confirm_) => {
                        if (confirm_) {
                            ajaxDeleteJobLevel({
                                id: id
                            });
                        }
                    });
            })

            $('#tb_Divisi, #tb_Jabatan').on('click', '.btn_DelDivJab', function(e) {
                e.preventDefault();
                let nama = $(this).data('nama'),
                    id = $(this).data('id'),
                    type = $(this).data('tipe');

                swal({
                        text: 'Hapus data ' + type + ' (' + nama + ')?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((confirm_) => {
                        if (confirm_) {
                            ajaxDeleteDivJab({
                                id: id
                            }, type);
                        }
                    });
            })

        });
    </script>
@endsection
