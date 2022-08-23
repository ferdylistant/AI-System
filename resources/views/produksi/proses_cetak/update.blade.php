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
            <a href="{{ route('proses.cetak.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit Proses Produksi Order Cetak</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <form id="fup_prosesProduksi">
                        <div class="card-header">
                            <h4>Form Order Cetak</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Kode Order:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                                    </div>
                                        <input type="text" class="form-control" name="kode_order" value="{{$data->kode_order}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Tipe Order:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                                    </div>
                                    @if ($data->tipe_order == '1')
                                        <input type="text" class="form-control" value="Umum" disabled>
                                    @else
                                        <input type="text" class="form-control" value="Rohani" disabled>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Status Cetak:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                                    </div>
                                    @if ($data->status_cetak == '1')
                                        <input type="text" class="form-control" value="Buku Baru" disabled>
                                    @elseif ($data->status_cetak == '2')
                                        <input type="text" class="form-control" value="Cetak Ulang Revisi" disabled>
                                    @elseif ($data->status_cetak == '3')
                                        <input type="text" class="form-control" value="Cetak Ulang" disabled>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Judul Buku:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="judul_buku" value="{{$data->judul_buku}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Sub Judul Buku:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->sub_judul}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Penulis: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->penulis}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Edisi Cetakan:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-bookmark"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->edisi_cetakan.'/'.$data->tahun_terbit}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Tanggal Order:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{Carbon\Carbon::parse($data->tanggal_order)->translatedFormat('d F Y')}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-4 mb-4">
                                <label>Tanggal Permintaan Jadi:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{Carbon\Carbon::parse($data->tgl_permintaan_jadi)->translatedFormat('d F Y')}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Efek Cover:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-swatchbook"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->efek_cover}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Format Buku:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-ruler-combined"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->format_buku}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4" id="formJilid">
                                <label>Jilid:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-hand-spock"></i></div>
                                    </div>
                                    @if ($data->jenis_jilid == '1')
                                        <input type="text" class="form-control" value="Bending {{$data->ukuran_bending}}" disabled>
                                    @elseif ($data->jenis_jilid == '2')
                                        <input type="text" class="form-control" value="Jahit Kawat" disabled>
                                    @elseif ($data->jenis_jilid == '3')
                                        <input type="text" class="form-control" value="Jahit Benang" disabled>
                                    @elseif ($data->jenis_jilid == '4')
                                        <input type="text" class="form-control" value="Hardcover" disabled>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Jumlah Cetak:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-copy"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->jumlah_cetak}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Jumlah Halaman:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-book-open"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->jumlah_halaman}}"disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text">eks</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Buku Jadi:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-atlas"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->buku_jadi}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Kertas Isi:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-scroll"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->kertas_isi}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Warna Cover:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-palette"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->warna_cover}}" disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Katern:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-map"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="katern" placeholder="Ukuran katern" value="{{$data->katern}}">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Mesin:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-cash-register"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="mesin" placeholder="No. Mesin" value="{{$data->mesin}}">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Jadi Plat:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="plat" placeholder="Tanggal jadi plat" value="{{is_null($data->plat)?'':$data->plat}}">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Jadi Cetak Isi:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="cetak_isi" placeholder="Tanggal jadi cetak isi" value="{{is_null($data->cetak_isi)?'':$data->cetak_isi}}">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Jadi Cover:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="cover" placeholder="Tanggal jadi cover" value="{{is_null($data->cover)?'':$data->cover}}">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Jadi Lipat Isi:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="lipat_isi" placeholder="Tanggal jadi lipat isi" value="{{is_null($data->lipat_isi)?'':$data->lipat_isi}}">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Jadi Jilid:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="jilid" placeholder="Tanggal jadi jilid" value="{{is_null($data->jilid)?'':$data->jilid}}">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Jadi Potong 3 Sisi:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="potong_3_sisi" placeholder="Tanggal jadi potong 3 sisi" value="{{is_null($data->potong_3_sisi)?'':$data->potong_3_sisi}}">
                                </div>
                            </div>
                            @if ($data->buku_jadi == 'Wrapping')
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Jadi Wrapping:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="wrapping" placeholder="Tanggal jadi wrapping" value="{{is_null($data->wrapping)?'':$data->wrapping}}">
                                </div>
                            </div>
                            @endif
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Kirim Gudang:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="kirim_gudang" placeholder="Tanggal kirim gudang" value="{{is_null($data->kirim_gudang)?'':$data->kirim_gudang}}">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Harga:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-barcode"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="harga" placeholder="Total harga" value="{{$data->harga}}">
                                </div>
                            </div>
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
<script src="{{ url('js/edit_proses_produksi.js') }}"></script>
@endsection
