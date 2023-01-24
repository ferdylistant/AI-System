@extends('layouts.app')
@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/flipbook/min_version/ipages.min.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Detail Deskripsi Turun Cetak</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Data Deskripsi Turun Cetak&nbsp;
                                -
                            </h4>
                            @if ($data->status == 'Antrian')
                                <span class="badge" style="background:#34395E;color:white">{{ $data->status }}</span>
                            @elseif ($data->status == 'Pending')
                                <span class="badge badge-danger">{{ $data->status }}</span>
                            @elseif ($data->status == 'Proses')
                                <span class="badge badge-success">{{ $data->status }}</span>
                            @elseif ($data->status == 'Selesai')
                                <span class="badge badge-light">{{ $data->status }}</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <?php $gate = Gate::allows('do_approval', 'pilih-penerbitan-turcet'); ?>
                            @if (($data->status == 'Selesai') && ($gate) && (is_null($pilihan_terbit)))
                                <form id="fadd_Pilihan_Terbit">
                                    <h6>Pilihan Terbit :</h6>
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="add_pilihan_terbit[]"
                                                id="Cetak" value="cetak">
                                            <label class="form-check-label" for="Cetak">Cetak</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="add_pilihan_terbit[]"
                                                id="eBook" value="ebook">
                                            <label class="form-check-label" for="eBook">E-book</label>
                                        </div>
                                        <div id="err_pilihan_terbit"></div>
                                    </div>
                                    <div class="form-group" style="display:none" id="eB"> 
                                        <h6>Platform Ebook :</h6>
                                        @foreach ($platform_ebook as $ebook)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="add_platform_ebook[]"
                                                    id="{{ $ebook->nama }}" value="{{ $ebook->id }}">
                                                <label class="form-check-label"
                                                    for="{{ $ebook->nama }}">{{ $ebook->nama }}</label>
                                            </div>
                                        @endforeach
                                        <div id="err_pilihan_terbit"></div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Order</button>
                                    </div>

                                </form>
                            @endif

                            <div class="row mb-4">
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kode</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">{{ $data->kode }}</p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Judul Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->judul_final))
                                                -
                                            @else
                                                {{ $data->judul_final }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Penulis</h6>
                                        </div>
                                        <ul class="list-unstyled list-inline">
                                            <li class="list-inline-item">
                                                @foreach ($penulis as $pen)
                                                    <p class="mb-1 text-monospace">
                                                        <span class="bullet"></span>
                                                        <span>{{ $pen->nama }}</span>
                                                    </p>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Nama Pena</h6>
                                        </div>
                                        @if ((is_null($data->nama_pena)))
                                        <p class="mb-1 text-monospace">
                                                -
                                        </p>
                                        @else
                                        <ul class="list-unstyled list-inline">
                                            @foreach (json_decode($data->nama_pena) as $pen)
                                                <li class="list-inline-item">
                                                <p class="mb-1 text-monospace">
                                                    <span class="bullet"></span>
                                                    <span>{{ $pen }}</span>
                                                </p>
                                                </li>
                                            @endforeach
    
                                        </ul>
                                        @endif
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tipe Order</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->tipe_order))
                                                -
                                            @else
                                                {{ $data->tipe_order == '1'?'Umum':'Rohani' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Edisi Cetak Tahun</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->edisi_cetak))
                                                -
                                            @else
                                                {{ $data->edisi_cetak }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">ISBN</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->isbn))
                                                -
                                            @else
                                                {{ $data->isbn }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Format Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->format_buku))
                                                -
                                            @else
                                                {{ $data->format_buku }}&nbsp;cm
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kelompok Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->nama))
                                                -
                                            @else
                                                {{ $data->nama }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jumlah Halaman Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->jml_hal_final))
                                                -
                                            @else
                                                {{ $data->jml_hal_final }} Halaman
                                            @endif
                                        </p>
                                    </div>

                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Masuk</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->tgl_masuk))
                                                -
                                            @else
                                                {{ Carbon\Carbon::parse($data->tgl_masuk)->translatedFormat('l d F Y, H:i') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Sasaran Pasar</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($sasaran_pasar))
                                                -
                                            @else
                                                {{ $sasaran_pasar }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Bulan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->bulan))
                                                -
                                            @else
                                                {{ $data->bulan }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">PIC Prodev</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($pic))
                                                -
                                            @else
                                                {{ $pic }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Contoh Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @if (is_null($data->url_file))
                                                -
                                            @else
                                                <a href="{{ $data->url_file }}" target="blank">{{ $data->url_file }}</a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
    <style>
        #md_updateSubtimeline table {
            width: 100%;
        }

        #md_updateSubtimeline table tr th {
            text-align: center;
        }
    </style>
@endsection
@section('jsRequired')
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ url('vendors/flipbook/min_version/pdf.min.js') }}"></script>
    <script src="{{ url('vendors/flipbook/min_version/jquery.ipages.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
@endsection

@section('jsNeeded')
    <script>
        (function() {
    const form = document.querySelector('#fadd_Pilihan_Terbit');
    const checkboxes = form.querySelectorAll('input[type=checkbox]');
    const checkboxLength = checkboxes.length;
    const firstCheckbox = checkboxLength > 0 ? checkboxes[0] : null;

    function init() {
        if (firstCheckbox) {
            for (let i = 0; i < checkboxLength; i++) {
                checkboxes[i].addEventListener('change', checkValidity);
            }

            checkValidity();
        }
    }

    function isChecked() {
        for (let i = 0; i < checkboxLength; i++) {
            if (checkboxes[i].checked) return true;
        }

        return false;
    }

    function checkValidity() {
        const errorMessage = !isChecked() ? 'At least one checkbox must be selected.' : '';
        firstCheckbox.setCustomValidity(errorMessage);
    }

    init();
})();

        $('#fadd_Pilihan_Terbit').on('submit', function(e) {
            e.preventDefault();
            if ($(this).valid()) {
                swal({
                    text: 'Tambah data Pilihan Terbit?',
                    icon: 'info',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm_) => {
                    if (confirm_) {
                        ajaxAddPilihanTerbit($(this))
                    }
                });
            } else {
                notifToast("error", "Periksa kembali form Anda!");
            }
        })
        function resetForm(form) {
            form.trigger("reset");
            $('[name^="add_pilihan_terbit"]').val("").trigger("change");
            $('[name^="add_platform_ebook"]').val("").trigger("change");
        }
        function ajaxAddPilihanTerbit(data) {
            let el = data.get(0);
            // console.log(el);
            $.ajax({
                type: "POST",
                url: window.location.origin + "/penerbitan/deskripsi/turun-cetak/detail?id=" +
                    "{{ $data->id }}" + "&type=" + "{{ $data->tipe_order }}",
                data: new FormData(el),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true).
                    addClass('btn-progress')
                },
                success: function(result) {
                    // console.log(result)
                    if (result.status == 'error') {
                        resetForm(data);
                        notifToast(result.status, result.message);
                    } else {
                        notifToast(result.status, result.message);
                        location.href = result.route;
                    }
                },
                error: function(err) {
                    // console.log(err.responseJSON)
                    rs = err.responseJSON.errors;
                    if (rs != undefined) {
                        err = {};
                        Object.entries(rs).forEach(entry => {
                            let [key, value] = entry;
                            err[key] = value
                        })
                        // addNaskah.showErrors(err);
                    }
                    notifToast('error', 'Data pilihan terbit gagal disimpan!');
                },
                complete: function() {
                    $('button[type="submit"]').prop('disabled', false).
                    removeClass('btn-progress')
                }
            })
        }

        $(document).ready(function() {
            $('#eBook').change(function() {
                if (this.checked){
                    // showDetail(this.value);
                    $('#eB').show('slow');
                }
                else {
                    // just_hide(this.value);
                    $('#eB').hide('slow')
                }
            });
        });

        // function showDetail(ele) {
        //     $('#' + ele).show('slow');
        // }

        // function just_hide(ele) {
        //     $('#' + ele).hide('slow');
        // }
    </script>
@endsection

@yield('jsNeededForm')
