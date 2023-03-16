@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
@endsection

@section('cssNeeded')
    <style>

.time-line-box {
  width: 100%;
  /* background-color: #dadefe; */
}

.time-line-box .timeline {
  list-style-type: none;
  display: flex;
  padding: 0;
  text-align: center;
}

.time-line-box .timestamp {
  margin: auto;
  margin-bottom: 5px;
  padding: 0px 4px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.time-line-box .status {
  padding: 0px 10px;
  display: flex;
  justify-content: center;
  border-top: 3px solid #455EFC;
  position: relative;
  transition: all 200ms ease-in;
}
.time-line-box .status span {
  padding-top: 8px;
}
.time-line-box .status span:before {
  content: '';
  width: 12px;
  height: 12px;
  background-color: #455EFC;
  border-radius: 12px;
  border: 2px solid #455EFC;
  position: absolute;
  left: 50%;
  top: 0%;
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  transition: all 200ms ease-in;
}

.swiper-container {
  width: 95%;
  margin: auto;
  overflow-y: auto;
}
.swiper-wrapper{
  display: inline-flex;
  flex-direction: row;
  overflow-y:auto;
  justify-content: center;
}
.swiper-container::-webkit-scrollbar-track{
   background:#a8a8a8b6;
}
.swiper-container::-webkit-scrollbar{
  height: 2px;
}
.swiper-container::-webkit-scrollbar-thumb{
   background: #4F4F4F !important;
}
.swiper-slide {
  text-align: center;
  font-size: 12px;
  width: 200px;
  height: 100%;
  position: relative;
}

    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Jasa Cetak Order Buku</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-warning">
                        <div class="row card-header justify-content-between">
                            <div class="col-auto d-flex">
                                <h4>Form Otorisasi Kabag</h4>
                                <span class="status_proses"></span>
                            </div>
                            <div class="col-auto">
                                <span class="bullet text-danger"></span> Nomor order: <b class="no_order"></b>
                            </div>
                            <div class="col-auto">
                                <span class="bullet text-danger"></span> Dibuat oleh: <b id="createdBy"></b>-<b id="createdAt"></b>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="timelineProgress"></div>
                            </div>
                        </div>
                        <form id="f_OrderBukuJasaCetak" name="id" data-id="" data-no_order="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Jalur Proses:</th>
                                                        <td class="table-active text-right jalur_proses"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Status Cetak:</th>
                                                        <td class="table-active text-right status_cetak"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Nama Pemesan:</th>
                                                        <td class="table-active text-right nama_pemesan"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Judul Buku:</th>
                                                        <td class="table-active text-right judul_buku"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Pengarang:</th>
                                                        <td class="table-active text-right pengarang"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Format:</th>
                                                        <td class="table-active text-right format"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Jumlah Halaman:</th>
                                                        <td class="table-active text-right jml_halaman"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Kertas Isi / Tinta:</th>
                                                        <td class="table-active text-right"><span class="kertas_isi"></span> <b>/</b> <span class="kertas_isi_tinta"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Kertas Cover / Tinta:</th>
                                                        <td class="table-active text-right"><span class="kertas_cover"></span> <b>/</b> <span class="kertas_cover_tinta"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Jumlah & Harga Final:</th>
                                                        <td class="table-active text-right harga_final"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Tanggal Order:</th>
                                                        <td class="table-active text-right tgl_order"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Tanggal Permintaan Selesai:</th>
                                                        <td class="table-active text-right tgl_permintaan_selesai"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Keterangan:</th>
                                                        <td class="table-active text-right keterangan"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Desainer & Setter:</th>
                                                        <td class="table-active text-left">
                                                            <small id="nonRegulerInfo"></small>
                                                            <select name="desain[]" class="form-control select-2 select-desain" multiple="multiple" required disabled>
                                                                <option label="Pilih Desain"></option>
                                                                @foreach ($data_des_set as $val)
                                                                    <option value="{{ $val->id }}">{{ $val->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Korektor:</th>
                                                        <td class="table-active text-right">
                                                            <select name="korektor[]" class="form-control select-2" multiple="multiple">
                                                                <option label="Pilih Korektor"></option>
                                                                @foreach ($data_kor as $val)
                                                                    <option value="{{ $val->id }}">{{ $val->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Pracetak:</th>
                                                        <td class="table-active text-right">
                                                            <select name="pracetak[]" class="form-control select-2" multiple="multiple">
                                                                <option label="Pilih Pracetak"></option>
                                                                @foreach ($data_pra as $val)
                                                                    <option value="{{ $val->id }}">{{ $val->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="proses" class="custom-control-input proses_kerja_input" id="prosesKerja" data-id="" data-no_order="" data-author_db="" data-label_db="">
                                    <label class="custom-control-label mr-3 text-dark" for="prosesKerja" id="labelProses"></label>
                                </div>
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('jsRequired')
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    {{-- <script src="https://unpkg.com/imask"></script> --}}
    <script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.0/raphael-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowchart/1.17.1/flowchart.min.js"></script> --}}
@endsection


@section('jsNeeded')
    <script src="{{ url('js/otorisasi_kabag_orderbuku_jc.js') }}"></script>
@endsection
