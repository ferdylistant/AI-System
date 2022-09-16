@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
@endsection

@section('cssNeeded')
<style>

</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('despro.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit/Buat Penerbitan Deskripsi Produk</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <form id="fup_deskripsiProduk">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Kode naskah:</th>
                                                    <td class="table-active text-right">{{$data->kode}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Judul Asli:</th>
                                                    <input type="hidden" name="judul_asli" value="{{$data->judul_asli}}">
                                                    <td class="table-active text-right">{{$data->judul_asli}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Kelompok Buku:</th>
                                                    <td class="table-active text-right">{{$data->nama}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Penulis:</th>
                                                    <td class="table-active text-right">
                                                        @foreach ($penulis as $p)
                                                            {{$p->nama}}-<br>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Format Buku: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->format_buku))

                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="format_buku" class="form-control select-format-buku" required>
                                                            <option label="Pilih format buku"></option>
                                                            @foreach ($format_buku as $fb)
                                                                <option value="{{$fb->jenis_format}}">{{$fb->jenis_format}}&nbsp;cm&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Jumlah halaman perkiraan: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->format_buku))

                                                    @else
                                                    <td class="table-active text-left">
                                                        <input type="number" name="jml_hal_perkiraan" class="form-control" min="1">
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Kelengkapan:</th>
                                                    @if (!is_null($data->kelengkapan))

                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="kelengkapan" class="form-control select-kelengkapan" required>
                                                            <option label="Pilih kelengkapan"></option>
                                                            @foreach ($kelengkapan as $k)
                                                                <option value="{{$k['value']}}">{{$k['value']}}&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Catatan:</th>
                                                    @if (!is_null($data->imprint))

                                                    @else
                                                    <td class="table-active text-left">
                                                        <textarea name="catatan" class="form-control" cols="30" rows="10"></textarea>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Imprint: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->imprint))

                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="imprint" class="form-control select-imprint" required>
                                                            <option label="Pilih imprint"></option>
                                                            @foreach ($imprint as $i)
                                                                <option value="{{$i->nama}}">{{$i->nama}}&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Judul Final:</th>
                                                    @if (!is_null($data->imprint))

                                                    @else
                                                    <td class="table-active text-left">
                                                        <textarea name="judul_final" class="form-control" cols="30" rows="10"></textarea>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Bulan: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->imprint))

                                                    @else
                                                    <td class="table-active text-left">
                                                        <input name="bulan" class="form-control datepicker" required>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 30%">Alt Judul</th>
                                                    <td class="table-active text-left">
                                                        <div class="input_fields_wrap">
                                                            <button class="add_field_button btn btn-outline-primary mb-1"><i class="fas fa-plus-circle"></i> More Fields</button>
                                                            <div class="input-group-append"><input type="text" name="alt_judul[]" placeholder="Alternatif judul" class="form-control" required></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="id" value="{{$data->id}}">


                            </div>
                        </div>
                        <div class="card-footer text-right">
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
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/additional-methods.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
@endsection


@section('jsNeeded')
<script src="{{ url('js/edit_deskripsi_produk.js') }}"></script>
@endsection
