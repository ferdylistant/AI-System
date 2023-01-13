@extends('layouts.app')
@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/flipbook/min_version/ipages.min.css') }}">
@endsection

@section('cssNeeded')
    <style>
        .tb-naskah {
            font-size: 12px;
            border: 1px solid #ced4da;
        }

        .tb-naskah,
        .tb-naskah th,
        .tb-naskah td {
            height: auto !important;
            padding: 10px 15px 10px 15px !important;
        }

        .tb-naskah th {
            width: 35%;
            color: #868ba1;
            background-color: #E9ECEF;
        }

        .accordion-body {
            overflow: auto;
            max-height: 500px;
        }

        .stamp {
            /* transform: rotate(12deg); */
            color: #555;
            font-size: 1rem;
            font-weight: 700;
            border: 0.25rem solid #555;
            display: inline-block;
            padding: 0.25rem 1rem;
            text-transform: uppercase;
            border-radius: 1rem;
            font-family: 'Courier';
            -webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
            -webkit-mask-size: 944px 604px;
            mix-blend-mode: multiply;
        }

        .is-nope {
            color: #D23;
            border: 0.5rem double #D23;
            transform: rotate(3deg);
            -webkit-mask-position: 2rem 3rem;
            font-size: 2rem;
        }

        .is-approved {
            color: #0A9928;
            border: 0.5rem double #0A9928;
            -webkit-mask-position: 13rem 6rem;
            transform: rotate(-14deg);
            -webkit-mask-position: 2rem 3rem;
            border-radius: 0;
        }

        .is-draft {
            color: #C4C4C4;
            border: 1rem double #C4C4C4;
            transform: rotate(-5deg);
            font-size: 6rem;
            font-family: "Open sans", Helvetica, Arial, sans-serif;
            border-radius: 0;
            padding: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('cetak.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Detail Penerbitan Order Cetak Buku</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-primary">
                        <div class="card-header justify-content-between">
                            <div class="col-auto d-flex">
                                <h4>Form Penerbitan Order Cetak</h4>
                                @switch($data->status)
                                    @case('Antrian')
                                        <span class="badge" style="background:#34395E;color:white">Antrian</span>
                                    @break

                                    @case('Pending')
                                        <span class="badge badge-danger">Pending</span>
                                    @break

                                    @case('Proses')
                                        <span class="badge badge-success">Proses</span>
                                    @break

                                    @case('Selesai')
                                        <span class="badge badge-light">Selesai</span>
                                    @break
                                @endswitch
                            </div>
                            <div class="col-auto">
                                <span class="bullet text-danger"></span> Kode order: <b>{{ $data->kode_order }}</b>
                                (@foreach (json_decode($data->pilihan_terbit) as $i => $pt)
                                    @if (count(json_decode($data->pilihan_terbit)) == 2)
                                        @if ($i == 0)
                                            {{ $pt }} &
                                        @else
                                            {{ $pt }}
                                        @endif
                                    @else
                                        {{ $pt }}
                                    @endif
                                @endforeach)
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="fadd_Approval">
                                {{-- ! PERSETUJUAN --}}
                                @include('penerbitan.order_cetak.include.persetujuan')
                                {{-- ! PERSETUJUAN BATAS --}}
                            </form>
                            <div class="row mb-4">
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kode Order</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">{{ $data->kode_order }}</p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Status Cetak</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            @switch($data->status_cetak)
                                                @case(1)
                                                    Buku Baru
                                                @break

                                                @case(2)
                                                    Cetak Ulang Revisi
                                                @break

                                                @case(3)
                                                    Cetak Ulang
                                                @break

                                                @default
                                                    Data Error
                                            @endswitch
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Pilihan Terbit</h6>
                                        </div>
                                        @foreach (json_decode($data->pilihan_terbit) as $pt)
                                            <p class="mb-1 text-monospace">
                                                <span class="bullet"></span>
                                                <span>{{ $pt }}</span>
                                            </p>
                                        @endforeach
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Platform Ebook</h6>
                                        </div>
                                        @foreach (json_decode($data->platform_digital_ebook_id) as $pDigital)
                                            <p class="mb-1 text-monospace">
                                                <span class="bullet"></span>
                                                <span>{{ $pDigital }}</span>
                                            </p>
                                        @endforeach
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Judul Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->judul_final) ? '-' : $data->judul_final }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Sub Judul</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->sub_judul_final) ? '-' : $data->sub_judul_final }}
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
                                            <h6 class="mb-1">ISBN</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->isbn) ? '-' : $data->isbn }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">E-ISBN</h6>
                                        </div>
                                        <p class="mb-1 text-monospace text-danger">
                                            Masih Kosong
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Penerbit - Imprint</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            Andi Offset - {{ is_null($data->imprint) ? '-' : $data->imprint }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kelompok Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->nama) ? '-' : $data->nama }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Edisi / Cetakan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->edisi_cetak) ? '-' : $data->edisi_cetak }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Format Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->format_buku) ? '-' : $data->format_buku . ' cm' }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jumlah Halaman Final</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->jml_hal_final) ? '-' : $data->jml_hal_final . ' Halaman' }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Bending</h6>
                                        </div>
                                        <p class="mb-1 text-monospace text-danger">
                                            Masih Kosong
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kertas Isi</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->kertas_isi) ? '-' : $data->kertas_isi }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kertas Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace text-danger">
                                            Masih Kosong
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Efek Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->efek_cover) ? '-' : $data->efek_cover }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jenis Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->jenis_cover) ? '-' : $data->jenis_cover }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tanggal Terbit</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->tahun_terbit) ? '-' : $data->tahun_terbit }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Buku Jadi</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->buku_jadi) ? '-' : $data->buku_jadi }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Status Buku</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            Masih Kosong
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jumlah Cetak</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->jumlah_cetak) ? '-' : $data->jumlah_cetak }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Buku Contoh</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->buku_contoh) ? '-' : $data->buku_contoh }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Keterangan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->keterangan) ? '-' : $data->keterangan }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Perlengkapan</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->perlengkapan) ? '-' : $data->perlengkapan }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jahit Kawat</h6>
                                        </div>
                                        <p class="mb-1 text-monospace text-danger">
                                            Masih Kosong
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jahit Benang</h6>
                                        </div>
                                        <p class="mb-1 text-monospace text-danger">
                                            Masih Kosong
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Warna Isi</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->isi_warna) ? '-' : $data->isi_warna }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Warna Cover</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->isi_warna) ? '-' : $data->isi_warna }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Tahun Terbit</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->tahun_terbit) ? '-' : $data->tahun_terbit }}
                                        </p>
                                    </div>
                                    <div class="list-group-item flex-column align-items-start">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">SPP</h6>
                                        </div>
                                        <p class="mb-1 text-monospace">
                                            {{ is_null($data->spp) ? '-' : $data->spp }}
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

    @include('penerbitan.order_cetak.include.modal_decline')
    @include('penerbitan.order_cetak.include.modal_decline_detail')
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
    <script src="{{ url('js/pending_order_cetak.js') }}"></script>
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
                if (this.checked) {
                    // showDetail(this.value);
                    $('#eB').show('slow');
                } else {
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
