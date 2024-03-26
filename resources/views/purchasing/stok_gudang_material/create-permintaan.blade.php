@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{asset('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
@endsection

@section('cssNeeded')
<style>
    .scrollbar-deep-purple::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #F5F5F5;
        border-radius: 10px;
    }

    .scrollbar-deep-purple::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5;
    }

    .scrollbar-deep-purple::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #6777EF;
    }

    .scrollbar-deep-purple {
        scrollbar-color: #6777EF #F5F5F5;
    }

    .bordered-deep-purple::-webkit-scrollbar-track {
        -webkit-box-shadow: none;
        border: 1px solid #6777EF;
    }

    .bordered-deep-purple::-webkit-scrollbar-thumb {
        -webkit-box-shadow: none;
    }

    .square::-webkit-scrollbar-track {
        border-radius: 0 !important;
    }

    .square::-webkit-scrollbar-thumb {
        border-radius: 0 !important;
    }

    .thin::-webkit-scrollbar {
        width: 6px;
    }

    .scroll-container-1 {
        position: relative;
        overflow-y: scroll;
        max-height: 500px;
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
                            <div class="col-12 col-md-4">
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
                                        <form id="formAvailable" class="scroll-container-1 scrollbar-deep-purple square thin mb-2">
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
                                            <div class="form-group mb-4">
                                                <label>Kuantitas: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="add_kuantitas_available" placeholder="Kuantitas">
                                                    <div class="input-group-append">
                                                        <div id="unitAvailable" class="input-group-text">Unit</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div id="errorMessages"></div>
                                        <button type="submit" form="formAvailable" class="btn btn-dark">Tambahkan</button>
                                    </div>
                                    <div class="tab-pane fade" id="unavailable" role="tabpanel" aria-labelledby="unavailable-tab">
                                        <form id="formUnavailable" class="scroll-container-1 scrollbar-deep-purple square thin mb-2">
                                            <div class="form-group mb-4">
                                                <label>Tipe: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="type" class="form-control select2" name="add_type_unavailable">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_type_unavailable"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Subtipe: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="subType" class="form-control select2" name="add_subtype_unavailable">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_subtype_unavailable"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Golongan: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="golongan" class="form-control select2" name="add_golongan_unavailable">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_golongan_unavailable"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Subgolongan: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="subGolongan" class="form-control select2" name="add_subgolongan_unavailable">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_subgolongan_unavailable"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Nama Barang: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-box"></i></div>
                                                    </div>
                                                    <input type="text" class="form-control" name="add_nama_barang_unavailable" placeholder="Nama Barang">
                                                    <div id="err_add_nama_barang_unavailable"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Kuantitas: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <input type="number" class="form-control" name="add_kuantitas_unavailable" placeholder="Kuantitas">
                                                    <div id="err_add_kuantitas_unavailable"></div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label>Unit: <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><i class="fas fa-flag"></i></div>
                                                    </div>
                                                    <select id="unitUnavailable" class="form-control select2" name="add_unit_unavailable">
                                                        <option label="Pilih"></option>
                                                    </select>
                                                    <div id="err_add_unit_unavailable"></div>
                                                </div>
                                            </div>
                                        </form>
                                        <button type="submit" form="formUnavailable" class="btn btn-dark">Tambahkan</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <form id="formPermintaan">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="rows" value="0">
                                    <input type="hidden" name="kode_" value="{{ $kode }}">
                                    <input type="hidden" name="dataIndex[]" value="[]">
                                    <input type="hidden" name="dataKode[]" value="[]">
                                    <input type="hidden" name="dataPermintaan[]" value="[]">
                                    <table id="tb_Permintaan" class="table table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Kode</th>
                                                <th scope="col">Nama Barang</th>
                                                <th scope="col">Kuantitas</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody"></tbody>
                                    </table>
                                    <div id="submitElement" class="card-footer text-right" hidden>
                                        <button type="submit" class="btn btn-success">Ajukan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
        let storageRows = getData("rows");
        let storageKode = getData("kode_");
        let storageDataIndex = getData("dataIndex");
        let storageDataKode = getData("dataKode");
        let storageDataPermintaan = getData("dataPermintaan");

        if (storageDataPermintaan !== null) {
            $('[name="rows"]').val(storageRows);
            if (storageKode !== null) {
                $('[name="kode_"]').val(storageKode);
            }
            $('[name="dataIndex[]"]').val(JSON.stringify(storageDataIndex));
            $('[name="dataKode[]"]').val(JSON.stringify(storageDataKode));
            $('[name="dataPermintaan[]"]').val(JSON.stringify(storageDataPermintaan));
            html_ = '';
            storageDataPermintaan.forEach((item, index) => {
                html_ += addTableRow(item.itemType, item.type, item.stype, item.golongan, item.sgolongan, storageDataKode[index], item.nama, item.qty, item.unit, storageDataIndex[index]);
            });
            $('#tb_Permintaan').find('tbody').append(html_);
        }
        checkTable();

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
        ajaxSelect('unitUnavailable', 'satuan', 'nama', 'nama');
        $("#namaBarangAvailable").select2({
            placeholder: "Pilih",
            ajax: {
                url: "{{ route('sgm.ajax', 'select-barang') }}",
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
        }).on('change', function (e) {
            if (this.value) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('sgm.ajax', 'get-barang') }}",
                    data: { id: this.value },
                    success: function(res) {
                        if (res.status == 'success') {
                            $("#unitAvailable").text(res.data.satuan);
                        } else {
                            notifToast('error', res.message);
                        }
                    },
                    error: function (err) { notifToast('error', err) }
                });
            }
        });

        editPermintaan('dataNama', 'nama');
        editPermintaan('dataQty', 'qty');

        // Add Table Row Start
        let availableValidate = jqueryValidation_("#formAvailable", {
                add_nama_barang_available: {
                    required: true
                },
                add_kuantitas_available: {
                    required: true
                }
            }
        );
        $("#formAvailable").on('submit', function (e) {
            e.preventDefault();
            if ($("#formAvailable").valid()) {
                let id = $('[name="add_nama_barang_available"]').val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('sgm.ajax', 'get-barang') }}",
                    data: { id },
                    success: function(res) {
                        if (res.status == 'success') {
                            if ($('#formAvailable').valid()) {
                                let i = Number($('[name="rows"]').val());
                                let plus = i + 1;
                                $('[name="rows"]').val(plus);
                                setData("rows", plus);

                                let kodeBarang = res.data.kode;
                                let kodeArray = kodeBarang.split("-");
                                let kodeUrut = kodeArray[4];

                                let type = kodeArray[0];
                                let stype = kodeArray[1];
                                let golongan = kodeArray[2];
                                let sgolongan = kodeArray[3];
                                let nama = res.data.nama_stok;
                                let qty = $('[name="add_kuantitas_available"]').val();
                                let unit = res.data.satuan;
                                let increment = ('00000' + kodeUrut).slice(-5);
                                let itemType = 'itemAvailable';

                                addDataPermintaan(itemType, type, stype, golongan, sgolongan, nama, qty, unit);
                                html_ = addTableRow(itemType, type, stype, golongan, sgolongan, increment, nama, qty, unit, i);
                                $('#tb_Permintaan').find('tbody').append(html_);
                                addDataArray('index', 'dataIndex');
                                addDataArray('kode', 'dataKode');
                                checkTable();
                                resetForm();
                            }
                        } else {
                            notifToast('error', res.message);
                        }
                    },
                    error: function (err) { notifToast('error', err) }
                });
            }
        });

        let unavailableValidate = jqueryValidation_("#formUnavailable", {
                add_type_unavailable: {
                    required: true
                },
                add_subtype_unavailable: {
                    required: true
                },
                add_golongan_unavailable: {
                    required: true
                },
                add_subgolongan_unavailable: {
                    required: true
                },
                add_nama_barang_unavailable: {
                    required: true
                },
                add_kuantitas_unavailable: {
                    required: true
                },
                add_unit_unavailable: {
                    required: true
                },
            }
        );
        $("#formUnavailable").on('submit', function (e) {
            e.preventDefault();
            if ($("#formUnavailable").valid()) {
                let i = Number($('[name="rows"]').val());
                let plus = i + 1;
                $('[name="rows"]').val(plus);
                setData("rows", plus);

                let kodeAwal = Number($('[name="kode_"]').val());
                let kodePlus = kodeAwal + 1;
                $('[name="kode_"]').val(kodePlus);
                setData("kode_", kodePlus);

                let type = $('[name="add_type_unavailable"]').val();
                let stype = $('[name="add_subtype_unavailable"]').val();
                let golongan = $('[name="add_golongan_unavailable"]').val();
                let sgolongan = $('[name="add_subgolongan_unavailable"]').val();
                let nama = $('[name="add_nama_barang_unavailable"]').val();
                let qty = $('[name="add_kuantitas_unavailable"]').val();
                let unit = $('[name="add_unit_unavailable"]').val();
                let increment = ('00000' + kodePlus).slice(-5);
                let itemType = 'itemUnavailable';

                addDataPermintaan(itemType, type, stype, golongan, sgolongan, nama, qty, unit, i);
                html_ = html_ = addTableRow(itemType, type, stype, golongan, sgolongan, increment, nama, qty, unit, i);;
                $('#tb_Permintaan').find('tbody').append(html_);
                addDataArray('index', 'dataIndex');
                addDataArray('kode', 'dataKode');
                checkTable();
                resetForm();
            }
        });
        // Add Table Row End

        // Add Permintaan Pembelian Start
        $("#formPermintaan").on("submit", function (e) {
            e.preventDefault();
            swal({
                text: "Yakin melakukan permintaan pembelian?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm_) => {
                if (confirm_) {
                    ajaxCreatePermintaan($(this));
                }
            });
        });

        function ajaxCreatePermintaan(data) {
            let el = data.get(0);
            $.ajax({
                type: "POST",
                url: "{{ route('sgm.create') }}",
                data:  new FormData(el),
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('button[type="submit"]')
                        .prop("disabled", true)
                        .addClass("btn-progress");
                },
                success: function (result) {
                    if (result.status == "success") {
                        console.log(result.message);
                        notifToast(result.status, result.message);
                        resetTable();
                        resetForm();
                    } else {
                        console.log(result.message);
                        notifToast(result.status, result.message);
                    }
                },
                error: function (err) {
                    rs = err.responseJSON.errors;
                    if (rs != undefined) {
                        err = {};
                        Object.entries(rs).forEach((entry) => {
                            let [key, value] = entry;
                            err[key] = value;
                        });
                        availableValidate.showErrors(err);
                    }
                    notifToast("error", err.statusText);
                },
                complete: function () {
                    $('button[type="submit"]')
                        .prop("disabled", false)
                        .removeClass("btn-progress");
                },
            });
        }
        // Add Permintaan Pembelian End
    });

    // Edit Permintaan Start
    function editPermintaan(input, field) {
        $(document).on('change', `input[name="${input}[]"]`, function() {
            if ($(this).val().trim() !== '') {
                let index = $(this).closest('tr').find('input[name="index[]"]').val();
                let item = getData("dataPermintaan");
                item[index] = {...item[index], [field]: $(this).val()};
                $('[name="dataPermintaan[]"]').val(JSON.stringify(item));
                setData("dataPermintaan", item);
            }
        });
    }
    // Edit Permintaan End

    // Add Data Array Start
    function addDataArray(data, array) {
        let datas = $(`[name="${data}[]"]`).map(function() {
            return $(this).val();
        }).get();
        $(`[name="${array}[]"]`).val(JSON.stringify(datas));
        setData(array, datas);
    }
    // Add Data Array End

    // Add Data Permintaan Start
    function addDataPermintaan(itemType, type, stype, golongan, sgolongan, nama, qty, unit) {
        let inputValue = {itemType, type, stype, golongan, sgolongan, nama, qty, unit}
        let hiddenInput = $('[name="dataPermintaan[]"]');
        let hiddenValues = JSON.parse(hiddenInput.val());
        hiddenValues.push(inputValue);
        hiddenInput.val(JSON.stringify(hiddenValues));
        setData("dataPermintaan", hiddenValues);
    }
    // Add Data Permintaan End

    // Add Table Row Func Start
    function addTableRow(itemType, type, stype, golongan, sgolongan, kode, nama, qty, unit, index) {
    return `<tr id="${itemType}">
            <input type="hidden" name="index[]" value="${index}">
            <input type="hidden" name="kode[]" value="${kode}">
            <td>${type}-${stype}-${golongan}-${sgolongan}-${kode}</td>
            <td><input type="text" name="dataNama[]" value="${nama}" class="form-control" ${itemType === 'itemAvailable' && 'readonly'}></td>
            <td>
                <div class="input-group">
                    <input type="number" class="form-control" name="dataQty[]" value="${qty}">
                    <div class="input-group-append">
                        <div class="input-group-text">${unit}</div>
                    </div>
                </div>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm" data-type="${itemType}" onclick="removeRow(this)"><i class="fa fa-trash"></i></button></td>
        </tr>`;
    }
    // Add Table Row Func End

    // Reset Form Start
    function resetForm() {
        $("#type").val(null).trigger('change');
        $("#subType").val(null).trigger('change');
        $("#golongan").val(null).trigger('change');
        $("#subGolongan").val(null).trigger('change');
        $("[name='add_nama_barang_unavailable']").val('');
        $("[name='add_kuantitas_unavailable']").val('');
        $("#unitUnavailable").val(null).trigger('change');
        $("[name='add_kuantitas_available']").val('');
    }
    // Reset Form End

    // Reset Table Start
    function resetTable() {
        localStorage.removeItem("rows");
        localStorage.removeItem("kode_");
        localStorage.removeItem("dataIndex");
        localStorage.removeItem("dataKode");
        localStorage.removeItem("dataPermintaan");
        $('#tb_Permintaan').find('tbody').empty();
        getLastCode();
    }
    // Reset Table End

    // Get Last Code Start
    function getLastCode() {
        $.ajax({
            type: "GET",
            url: "{{ route('sgm.ajax', 'get-last-code') }}",
            success: function(data) {
                $("[name='kode_']").val(data);
            },
            error: function (err) { notifToast('error', err) }
        });
    }
    // Get Last Code End

    // Remove Row Start
    function removeRow(element) {
        let rows = $('[name="rows"]');
        rows.val(rows.val() - 1);
        setData("rows", rows.val());

        $(element).closest('tr').remove();
        if ($(element).attr('data-type') == 'itemUnavailable') {
            let kodes = $('[name="kode_"]');
            kodes.val(kodes.val() - 1);
            setData("kode_", kodes.val());
        }
        if ($('#tbody').html().length === 0) {
            localStorage.removeItem("kode_");
            getLastCode();
        }

        $('#tb_Permintaan').find('tbody tr').each(function (index) {
            $(this).find('input[name="index[]"]').val(index);
            $(this).find('td button').attr('onclick', 'removeRow(this)');
        });
        let k = Number({{ $kode + 1 }});
        $("#tb_Permintaan").find('tbody tr#itemUnavailable').each(function (index) {
            let kode = $(this).find('td').first().text();
            let val = kode.substring(0, 11);
            $(this).find('input[name="kode[]"]').val(('00000' + k).slice(-5));
            $(this).find('td').first().text(val + '-' + ('00000' + k).slice(-5));
            k = k + 1;
        });
        deleteArrayItem('dataPermintaan', $(element).closest('tr').find('input[name="index[]"]').val());
        addDataArray('index', 'dataIndex');
        addDataArray('kode', 'dataKode');
        checkTable();
    }
    // Remove Row End

    // Delete Array Item Start
    function deleteArrayItem(data, index) {
        let hiddenInput = $(`[name="${data}[]"]`);
        let hiddenValues = JSON.parse(hiddenInput.val());
        hiddenValues.splice(index, 1);
        hiddenInput.val(JSON.stringify(hiddenValues));
        setData(data, hiddenValues);
    }
    // Delete Array Item End

    // Check Table Start
    function checkTable() {
        let tbodyLength = $('#tbody').html().length;
        if (tbodyLength === 0) {
            $('#submitElement').attr('hidden', true);
        } else {
            $('#submitElement').attr('hidden', false);
        }
    }
    // Check Table End

    // Ajax Select Start
    function ajaxSelect(id, item_, oId, oText) {
        $(`#${id}`).select2({
            placeholder: "Pilih",
            ajax: {
                url: "{{ route('sgm.ajax', 'select') }}",
                type: "GET",
                data: function (params) {
                    var queryParameters = {
                        item_: item_ ?? null,
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
    // Ajax Select End

    // Set Storage Data Start
    function setData(key, value) {
        let data = {
            value,
            timestamp: new Date().getTime()
        };
        localStorage.setItem(key, JSON.stringify(data));
    }
    // Set Storage Data End

    // Get Storage Data Start
    function getData(key) {
        let dataString = localStorage.getItem(key);
        if (!dataString) return null;
        let data = JSON.parse(dataString);
        if (new Date().getTime() - data.timestamp > 7 * 24 * 60 * 60 * 1000) {
            resetTable();
            return null;
        }
        return data.value;
    }
    // Get Storage Data End
</script>
@endsection
