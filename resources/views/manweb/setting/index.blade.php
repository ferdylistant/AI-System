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
            <h1>Settings</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-2">
                                    <div class="list-group" id="list-tab" role="tablist">
                                        <a class="list-group-item list-group-item-action active" id="list-menu-list"
                                            data-toggle="list" href="#list-menu" role="tab" aria-controls="menu">
                                            <i class="fas fa-list"></i>&nbsp;Menu
                                        </a>
                                        <a class="list-group-item list-group-item-action" id="list-menu-bagian-list"
                                            data-toggle="list" href="#list-menu-bagian" role="tab"
                                            aria-controls="menu-bagian">
                                            <i class="fas fa-ellipsis-h"></i>&nbsp;Section
                                        </a>
                                        <a class="list-group-item list-group-item-action" id="list-permission-list"
                                            data-toggle="list" href="#list-permission" role="tab"
                                            aria-controls="permission">
                                            <i class="fas fa-user-shield"></i>&nbsp;Permission
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-10">
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="list-menu" role="tabpanel"
                                            aria-labelledby="list-menu-list">
                                            <div class="card-header">
                                                <h4 class="lead m-3"><i class="fas fa-list"></i>&nbsp;Menu List</h4>
                                                <div class="card-header-action">
                                                    <a href="#modal-menu" class="btn btn-success" data-tipe="Menu"
                                                        data-toggle="modal" data-target="#md_Menu"
                                                        data-backdrop="static">Tambah</a>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="tb_Menu" class="table table-striped" style="width: 100%">
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="list-menu-bagian" role="tabpanel"
                                            aria-labelledby="list-menu-bagian-list">
                                            <div class="card-header">
                                                <h4 class="lead m-3"><i class="fas fa-ellipsis-h"></i>&nbsp;Section Menu
                                                </h4>
                                                <div class="card-header-action">
                                                    <a href="#modal-section" class="btn btn-success" data-tipe="SectionMenu"
                                                        data-toggle="modal" data-target="#md_SectionMenu"
                                                        data-backdrop="static">Tambah</a>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="tb_SectionMenu" class="table table-striped" style="width: 100%">
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="list-permission" role="tabpanel"
                                            aria-labelledby="list-permission-list">
                                            <div class="card-header">
                                                <h4 class="lead m-3"><i class="fas fa-user-shield"></i>&nbsp;Permission List
                                                </h4>
                                                <div class="card-header-action">
                                                    <a href="#modal-permission" class="btn btn-success"
                                                        data-tipe="Permission" data-toggle="modal"
                                                        data-target="#md_Permission" data-backdrop="static">Tambah</a>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="tb_Permission" class="table table-striped" style="width: 100%">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal Menu -->
    @include('manweb.setting.include.modal_addmenu')
    @include('manweb.setting.include.modal_editmenu')
    <!-- Modal Section Menu -->
    @include('manweb.setting.include.modal_addsectionmenu')
    @include('manweb.setting.include.modal_editsectionmenu')
    <!-- Modal Section Menu -->


    <!-- Modal Divisi -->
    <div class="modal fade modal-progress" tabindex="-1" role="dialog" id="md_EditDivJab">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning"></div>
                <form id="fm_EditDivJab">
                    <div class="modal-body">
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
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
@endsection

@section('jsNeeded')
    <script src="{{ url('js/setting.js') }}"></script>
@endsection
