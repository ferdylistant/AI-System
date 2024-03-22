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

    .example-1 {
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
                                        <form id="formAvailable" class="example-1 scrollbar-deep-purple square thin mb-2">
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
                                        </form>
                                        <button type="button" id="addAvailable" form="formAvailable" class="btn btn-dark">Tambahkan</button>
                                    </div>
                                    <div class="tab-pane fade" id="unavailable" role="tabpanel" aria-labelledby="unavailable-tab">
                                        <form id="formUnavailable" class="example-1 scrollbar-deep-purple square thin mb-2">
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
                                        <button type="button" id="addUnavailable" form="formUnavailable" class="btn btn-dark">Tambahkan</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <form id="formPermintaan">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="rows" value="0">
                                    <input type="hidden" name="kode" value="{{ $kode }}">
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
                                        <button type="submit" class="btn btn-success" form="formPermintaan">Ajukan</button>
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
    // Init Start
    $(function () {
        $('[name="rows"]').val(0);
        $('[name="kode"]').val('{{ $kode }}');
        $('[name="dataPermintaan[]"]').val(JSON.stringify([]));

        let storageRows = localStorage.getItem("rows");
        let storageKode = localStorage.getItem("kode");
        let storageDataPermintaan = localStorage.getItem("dataPermintaan");

        if (storageDataPermintaan !== null) {
            $('[name="rows"]').val(storageRows);
            $('[name="kode"]').val(storageKode);
            $('[name="dataPermintaan[]"]').val(storageDataPermintaan);
            let items = JSON.parse(storageDataPermintaan);
            items.forEach(item => {
                html_ = `<tr id="${item.itemType}">
                    <input type="hidden" name="dataKode[]" value="${item.increment}">
                    <td>${item.type}-${item.stype}-${item.golongan}-${item.sgolongan}-${item.increment}</td>
                    <td><input type="text" name="dataNama[]" value="${item.nama}" class="form-control" ${item.itemType === 'itemAvailable' && 'readonly'}></td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control" name="dataQty[]" value="${item.qty}">
                            <div class="input-group-append">
                                <div class="input-group-text">${item.unit}</div>
                            </div>
                        </div>
                    </td>
                    <td><button type="button" class="btn btn-danger btn-sm" data-type="${item.itemType}" onclick="removeRow(this, ${item.index})"><i class="fa fa-trash"></i></button></td>
                </tr>`;
                $('#tb_Permintaan').find('tbody').append(html_);
            });
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
        ajaxSelect('unitAvailable', 'satuan', 'nama', 'nama');
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
            }
        });
    });
    // Init End

    // Add Table Row Start
    $("#addAvailable").click(function () {
        let id = $('[name="add_nama_barang_available"]').val();
        $.ajax({
            url: window.location.origin + "/purchasing/stok-gudang-material/ajax/get-barang",
            data: {
                id
            },
            success: function(data) {
                let i = Number($('[name="rows"]').val());
                let plus = i + 1;
                $('[name="rows"]').val(plus);
                localStorage.setItem("rows", plus);

                let kodeBarang = data.kode;
                let kodeArray = kodeBarang.split("-");
                let kodeUrut = kodeArray[4];

                let type = kodeArray[0];
                let stype = kodeArray[1];
                let golongan = kodeArray[2];
                let sgolongan = kodeArray[3];
                let nama = data.nama_stok;
                let qty = $('[name="add_kuantitas_available"]').val();
                let unit = $('[name="add_unit_available"]').val();
                let increment = ('00000' + kodeUrut).slice(-5);
                let itemType = 'itemAvailable';

                addArray(type, stype, golongan, sgolongan, unit, nama, qty, increment, i, itemType);

                html_ = `<tr>
                    <input type="hidden" name="dataKode[]" value="${increment}">
                    <td>${kodeBarang}</td>
                    <td><input type="text" name="dataNama[]" value="${nama}" class="form-control" readonly></td>
                    <td><input type="number" name="dataQty[]" value="${qty}" class="form-control"></td>
                    <td>${unit}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" data-type="${itemType}" onclick="removeRow(this, ${i})"><i class="fa fa-trash"></i></button></td>
                </tr>`;
                $('#tb_Permintaan').find('tbody').append(html_);
                checkTable();
                resetForm();
            },
            error: function(err) {}
        });
    });

    $("#addUnavailable").click(function () {
        let i = Number($('[name="rows"]').val());
        let plus = i + 1;
        $('[name="rows"]').val(plus);
        localStorage.setItem("rows", plus);

        let kodeAwal = Number($('[name="kode"]').val());
        let kodePlus = kodeAwal + 1;
        $('[name="kode"]').val(kodePlus);
        localStorage.setItem("kode", kodePlus);

        let type = $('[name="add_type_unavailable"]').val();
        let stype = $('[name="add_subtype_unavailable"]').val();
        let golongan = $('[name="add_golongan_unavailable"]').val();
        let sgolongan = $('[name="add_subgolongan_unavailable"]').val();
        let nama = $('[name="add_nama_barang_unavailable"]').val();
        let qty = $('[name="add_kuantitas_unavailable"]').val();
        let unit = $('[name="add_unit_unavailable"]').val();
        let increment = ('00000' + kodePlus).slice(-5);
        let itemType = 'itemUnavailable';

        addArray(type, stype, golongan, sgolongan, unit, nama, qty, increment, i, itemType);
        // addKode(increment);

        html_ = `<tr id="${itemType}">
            <input type="hidden" name="dataKode[]" value="${increment}">
            <td>${type}-${stype}-${golongan}-${sgolongan}-${increment}</td>
            <td><input type="text" name="dataNama[]" value="${nama}" class="form-control"></td>
            <td><input type="number" name="dataQty[]" value="${qty}" class="form-control"></td>
            <td>${unit}</td>
            <td><button type="button" class="btn btn-danger btn-sm" data-type="${itemType}" onclick="removeRow(this, ${i})"><i class="fa fa-trash"></i></button></td>
        </tr>`;
        $('#tb_Permintaan').find('tbody').append(html_);
        checkTable();
        resetForm();
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

    // let availableValidate = jqueryValidation_(
    //     "#formPermintaan",
    //     {
    //         nama_type: {
    //             required: true,
    //             remote:
    //                 window.location.origin +
    //                 "/master/type/ajax/check-duplicate-type",
    //         },
    //     },
    //     {
    //         nama_type: {
    //             remote: "Type sudah terdaftar!",
    //         },
    //     }
    // );

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
                    resetForm();
                    // tableType.ajax.reload();
                    // data.trigger("reset");
                } else {
                    console.log(result.message);
                    // notifToast(result.status, result.message);
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

    // Add Array Start
    function addArray(type, stype, golongan, sgolongan, unit, nama, qty, increment, index, itemType) {
        let inputValue = {
            type,
            stype,
            golongan,
            sgolongan,
            unit,
            nama,
            qty,
            increment,
            index,
            itemType
        }
        let hiddenInput = $('[name="dataPermintaan[]"]');
        let hiddenValues = JSON.parse(hiddenInput.val());
        hiddenValues.push(inputValue);
        hiddenInput.val(JSON.stringify(hiddenValues));
        localStorage.setItem("dataPermintaan", JSON.stringify(hiddenValues));
    }
    // Add Array End

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
        $("#unitAvailable").val(null).trigger('change');
    }
    // Reset Form End

    // Remove Row Start
    function removeRow(element, elementIndex) {
        let rows = $('[name="rows"]');
        rows.val(rows.val() - 1);
        localStorage.setItem("rows", rows.val());

        if ($(element).attr('data-type') == 'itemUnavailable') {
            let kodes = $('[name="kode"]');
            kodes.val(kodes.val() - 1);
            localStorage.setItem("kode", kodes.val());
        }
        $(element).closest('tr').remove();

        $('#tb_Permintaan').find('tbody tr').each(function (index) {
            $(this).find('td button').attr('onclick', 'removeRow(this, ' + index + ')');
        });
        let k = Number({{ $kode + 1 }});
        $("#tb_Permintaan").find('tbody tr#itemUnavailable').each(function (index) {
            let kode = $(this).find('td').first().text();
            let val = kode.substring(0, 11);
            $(this).find('input[type="hidden"]').val(('00000' + k).slice(-5));
            $(this).find('td').first().text(val + '-' + ('00000' + k).slice(-5));
            k = k + 1;
        });
        deleteArrayItem('dataPermintaan', elementIndex);
        checkTable();
    }
    // Remove Row End

    // Delete Array Item Start
    function deleteArrayItem(data, index) {
        let hiddenInput = $(`[name="${data}[]"]`);
        let hiddenValues = JSON.parse(hiddenInput.val());
        hiddenValues.splice(index, 1);
        hiddenInput.val(JSON.stringify(hiddenValues));
        localStorage.setItem(data, JSON.stringify(hiddenValues));
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
    // Ajax Select End
</script>
@endsection
