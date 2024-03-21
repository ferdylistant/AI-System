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
                                        <form>
                                            <div class="form-group mb-4">
                                                <label>Nama Barang: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="namaBarangAvailable" class="form-control select2" name="add_nama_barang_available">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_nama_barang_available"></div>
                                                </div>
                                            </div>
                                            <div id="addValue"></div>
                                            <button type="button" id="addAvailable" class="btn btn-dark">Tambahkan</button>
                                        </form>
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
                                <form id="formPermintaan" action="/purchasing/test" method="POST">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="rows" value="0">
                                    <input type="hidden" name="kode" value="{{ $kode }}">
                                    <input type="hidden" name="dataKode[]">
                                    <input type="hidden" name="dataPermintaan[]">
                                    <table id="tb_Permintaan" class="table table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Kode</th>
                                                <th scope="col">Nama Barang</th>
                                                <th scope="col">Kuantitas</th>
                                                <th scope="col"></th>
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
    console.log({{ $kode }})
    function ajaxSelect(id, item_, oId, oText) {
        $(`#${id}`).select2({
            placeholder: "Pilih",
            ajax: {
                url: window.location.origin + "/purchasing/stok-gudang-material/ajax/select",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        item_,
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item[oText],
                                id: item[oId],
                            };
                        }),
                    };
                },
            },
        });
    }

    $(function () {
        $(".select2").select2({
            placeholder: 'Pilih',
        }).on('change', function(e) {
            if (this.value) {
                $(this).valid();
            }
        });
        ajaxSelect('type', 'type', 'kode', 'nama');
        ajaxSelect('subType', 'type_sub', 'kode', 'nama');
        ajaxSelect('golongan', 'type_sub', 'kode', 'nama');
        ajaxSelect('subGolongan', 'golongan_sub', 'kode', 'nama');
        ajaxSelect('unit', 'satuan', 'nama', 'nama');
        $("#namaBarangAvailable").select2({
            placeholder: "Pilih",
            ajax: {
                url: window.location.origin + "/purchasing/stok-gudang-material/ajax/select-barang",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        term: params.term,
                    };
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama_stok,
                                id: item.id,
                            };
                        }),
                    };
                },
            },
        }).on('change', function(e) {
            if (this.value) {
                $(this).valid();
                let id = this.value;
                html_ = `<div class="form-group mb-4">
                    <label>Kuantitas: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-flag"></i></div>
                        </div>
                        <input type="number" class="form-control" name="add_kuantitas_available" placeholder="Kuantitas">
                        <div id="err_add_kuantitas_available"></div>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label>Unit: <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-flag"></i></div>
                        </div>
                        <select id="unitAvailable" class="form-control select2" name="add_unit_available">
                            <option label="Pilih"></option>
                        </select>
                        <div id="err_add_unit_available"></div>
                    </div>
                </div>
                `;
                $('#addValue').append(html_);
                ajaxSelect('unitAvailable', 'satuan', 'nama', 'nama');
                $("#addAvailable").click(function () {
                    getBarang(id);
                });
            }
        });
    });

    $("#addUnavailable").click(function () {
        let i = Number($('[name="rows"]').val());
        let plus = i + 1;
        $('[name="rows"]').val(plus);
        let kodeAwal = Number($('[name="kode"]').val());
        let kodePlus = kodeAwal + 1;
        $('[name="kode"]').val(kodePlus);

        let type = $('[name="add_type"]').val();
        let stype = $('[name="add_subtype"]').val();
        let golongan = $('[name="add_golongan"]').val();
        let sgolongan = $('[name="add_subgolongan"]').val();
        let nama = $('[name="add_nama"]').val();
        let qty = $('[name="add_kuantitas"]').val();
        let unit = $('[name="add_unit"]').val();
        let increment = ('00000' + kodePlus).slice(-5);

        addArray(type, stype, golongan, sgolongan, unit);
        addKode(increment);

        html_ = `<tr id="row[${i}]">
            <td>${type}-${stype}-${golongan}-${sgolongan}-${increment}</td>
            <td><input type="text" name="dataNama[]" value="${nama}" class="form-control"></td>
            <td><input type="number" name="dataQty[]" value="${qty}" class="form-control"></td>
            <td>${unit}</td>
            <td><button type="button" class="btn btn-danger btn-sm" data-id="${i}" onclick="removeRow(this, ${i})"><i class="fa fa-trash"></i></button></td>
        </tr>`;
        $('#tb_Permintaan').find('tbody').append(html_);
        resetForm();
    });

    $('[name="dataPermintaan[]"]').val(JSON.stringify([]))
    $('[name="dataKode[]"]').val(JSON.stringify([]))
    function addKode(kode) {
        let inputKode = kode;
        let hiddenInput_ = $('[name="dataKode[]"]');
        let hiddenValues_ = JSON.parse(hiddenInput_.val());
        hiddenValues_.push(inputKode);
        hiddenInput_.val(JSON.stringify(hiddenValues_));
        console.log(hiddenValues_);
    }
    function addArray(type, stype, golongan, sgolongan, unit) {
        let inputValue = {
            type,
            stype,
            golongan,
            sgolongan,
            unit
        }
        let hiddenInput = $('[name="dataPermintaan[]"]');
        let hiddenValues = JSON.parse(hiddenInput.val());
        hiddenValues.push(inputValue);
        hiddenInput.val(JSON.stringify(hiddenValues));
        console.log(hiddenValues);
    }

    function resetForm() {
        $("#type").val(null).trigger('change');
        $("#subType").val(null).trigger('change');
        $("#golongan").val(null).trigger('change');
        $("#subGolongan").val(null).trigger('change');
        $("[name='add_nama']").val('');
        $("[name='add_kuantitas']").val('');
        $("#unit").val(null).trigger('change');
        $("[name='add_kuantitas_available']").val('');
        $("#unitAvailable").val(null).trigger('change');
    }

    function removeRow(element, elementIndex) {
        let rows = $('[name="rows"]');
        rows.val(rows.val() - 1);
        let kodes = $('[name="kode"]');
        kodes.val(kodes.val() - 1);
        $(element).closest('tr').remove();

        let k = Number({{ $kode + 1 }});
        $('#tb_Permintaan').find('tbody tr').each(function (index) {
            let kode = $(this).find('td').first().text();
            let val = kode.substring(0, 11);
            $(this).attr('id', 'row[' + index + ']');
            $(this).find('td button').attr('data-id', index);
            $(this).find('td button').attr('onclick', 'removeRow(this, ' + index + ')');
            $(this).find('td').first().text(val + '-' + ('00000' + k).slice(-5));
            k = k + 1;
        });
        deleteArrayItem('dataKode', elementIndex);
        deleteArrayItem('dataPermintaan', elementIndex);

        let i = Number({{ $kode + 1 }});
        let kodeInput = $('[name="dataKode[]"]');
        let kodeValue = JSON.parse(kodeInput.val());
        kodeValue.forEach((item, index) => {
            kodeValue[index] = ('00000' + i).slice(-5);
            i = i + 1
        });
        kodeInput.val(JSON.stringify(kodeValue));
    }

    function deleteArrayItem(data, index) {
        let hiddenInput = $(`[name="${data}[]"]`);
        let hiddenValues = JSON.parse(hiddenInput.val());
        hiddenValues.splice(index, 1);
        console.log(hiddenValues);
        hiddenInput.val(JSON.stringify(hiddenValues));
    }

    function getBarang(id) {
        $.ajax({
            url: window.location.origin + "/purchasing/stok-gudang-material/ajax/get-barang",
            data: {
                id: id
            },
            success: function(data) {
                let i = Number($('[name="rows"]').val());
                let plus = i + 1;
                $('[name="rows"]').val(plus);
                let kodeAwal = Number($('[name="kode"]').val());
                let kodePlus = kodeAwal + 1;
                $('[name="kode"]').val(kodePlus);
                let kodeBarang = data.kode;
                let kodeArray = kodeBarang.split("-");

                let type = kodeArray[0];
                let stype = kodeArray[1];
                let golongan = kodeArray[2];
                let sgolongan = kodeArray[3];
                let nama = data.nama_stok;
                let qty = $('[name="add_kuantitas_available"]').val();
                let unit = $('[name="add_unit_available"]').val();
                let increment = ('00000' + kodePlus).slice(-5);

                addArray(type, stype, golongan, sgolongan, unit);
                addKode(increment);

                html_ = `<tr id="row[${i}]">
                    <td>${type}-${stype}-${golongan}-${sgolongan}-${increment}</td>
                    <td><input type="text" name="dataNama[]" value="${nama}" class="form-control" readonly></td>
                    <td><input type="number" name="dataQty[]" value="${qty}" class="form-control"></td>
                    <td>${unit}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" data-id="${i}" onclick="removeRow(this, ${i})"><i class="fa fa-trash"></i></button></td>
                </tr>`;
                $('#tb_Permintaan').find('tbody').append(html_);
                resetForm();
            },
            error: function(err) {}
        });
    }

    // $('#formPermintaan').on('submit', function (e) {
    //     e.preventDefault();
    // });
</script>
@endsection
