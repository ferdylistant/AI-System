@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{asset('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
@endsection

@section('cssNeeded')
<style>
    .divider {
        border-top: 1px solid #dee2e6;
        margin: 10px 0 20px;
        width: 100%;
        display: block;
        height: 0;
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
        </div>
        <h1>Tambah Permintaan</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="{{ url('/') }}">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('sgm.view') }}">Stok Gudang Material</a>
            </div>
            <div class="breadcrumb-item">
                Tambah Permintaan
            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="section-title">Form Permintaan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="available-tab" data-toggle="tab" data-typeget="available-menu" href="#available"
                                            role="tab" aria-controls="available" aria-selected="true">Stok Tersedia</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="unavailable-tab" data-toggle="tab" data-typeget="unavailable-menu" href="#unavailable" role="tab" aria-controls="unavailable" aria-selected="true">Input Baru</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="available" role="tabpanel" aria-labelledby="available-tab">
                                        isi tab 1
                                    </div>
                                    <div class="tab-pane fade" id="unavailable" role="tabpanel" aria-labelledby="unavailable-tab">
                                        <form>
                                            <div class="form-group mb-4">
                                                <label>Tipe: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="type" class="form-control select2" name="add_type">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_type"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Subtipe: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="subType" class="form-control select2" name="add_subtype">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_subtype"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Golongan: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="golongan" class="form-control select2" name="add_golongan">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_golongan"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Subgolongan: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="subGolongan" class="form-control select2" name="add_subgolongan">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_subgolongan"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Nama Barang: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-box"></i></div>
                                                    </div>
                                                    <input type="text" class="form-control" name="add_nama" placeholder="Nama Barang">
                                                    <div id="err_add_nama"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Kuantitas: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <input type="number" class="form-control" name="add_kuantitas" placeholder="Kuantitas">
                                                    <div id="err_add_kuantitas"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Unit: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="unit" class="form-control select2" name="add_unit">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_unit"></div>
                                                </div>
                                            </div>
                                            <button type="button" id="addUnavailable" class="btn btn-dark">Tambahkan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <form id="formPermintaan">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="test[]">
                                    <table id="tb_Permintaan" class="table table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Kode</th>
                                                <th scope="col">Nama Barang</th>
                                                <th scope="col">Kuantitas</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success" form="formPermintaan">Ajukan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('jsRequired')
<script src="{{asset('vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/dist/additional-methods.min.js')}}"></script>
<script src="{{asset('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
@endsection


@section('jsNeeded')
<script>
    $(function () {
        $(".select2").select2({
            placeholder: 'Pilih',
        }).on('change', function(e) {
            if (this.value) {
                $(this).valid();
            }
        });
        $("#type").select2({
            placeholder: "Pilih",
            ajax: {
                url: window.location.origin + "/purchasing/stok-gudang-material/ajax/select",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        item_: 'type',
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode,
                            };
                        }),
                    };
                },
            },
        });
        $("#subType").select2({
            placeholder: "Pilih",
            ajax: {
                url: window.location.origin + "/purchasing/stok-gudang-material/ajax/select",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        item_: 'type_sub',
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode,
                            };
                        }),
                    };
                },
            },
        });
        $("#golongan").select2({
            placeholder: "Pilih",
            ajax: {
                url: window.location.origin + "/purchasing/stok-gudang-material/ajax/select",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        item_: 'golongan',
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode,
                            };
                        }),
                    };
                },
            },
        });
        $("#subGolongan").select2({
            placeholder: "Pilih",
            ajax: {
                url: window.location.origin + "/purchasing/stok-gudang-material/ajax/select",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        item_: 'golongan_sub',
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode,
                            };
                        }),
                    };
                },
            },
        });
        $("#unit").select2({
            placeholder: "Pilih",
            ajax: {
                url: window.location.origin + "/purchasing/stok-gudang-material/ajax/select",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        item_: 'satuan',
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.nama,
                            };
                        }),
                    };
                },
            },
        });
    });

    let no = 0;
    $("#addUnavailable").click(function () {
        no = no + 1;
        let type = $('[name="add_type"]').val();
        let stype = $('[name="add_subtype"]').val();
        let golongan = $('[name="add_golongan"]').val();
        let sgolongan = $('[name="add_subgolongan"]').val();
        let nama = $('[name="add_nama"]').val();
        let qty = $('[name="add_kuantitas"]').val();
        let unit = $('[name="add_unit"]').val();
        let increment = ('00000' + no).slice(-5);

        html_ = `<tr>
            <td><input type="text" value="${type}-${stype}-${golongan}-${sgolongan}-${increment}" class="form-control" readonly></td>
            <td><input type="text" value="${nama}" class="form-control"></td>
            <td><input type="text" value="${qty} ${unit}" class="form-control"></td>
            <td><button class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fa fa-trash"></i></button></td>
        </tr>`
        $('#tb_Permintaan').find('tbody').append(html_)
    });

    function removeRow(e) {
        $(e).closest('tr').remove();
    }
</script>
@endsection
