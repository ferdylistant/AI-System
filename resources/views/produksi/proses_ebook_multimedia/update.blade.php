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
            <a href="{{ route('proses.ebook.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Upload Bukti Link E-book</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <form id="fup_prosesEbook">
                        <div class="card-header">
                            <h4>Form Proses Upload Bukti Link E-Book Multimedia</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Tipe Order:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-boxes"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->tipe_order==1?'Buku Umum':'Buku Rohani'}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label class="d-block">Platform E-book:</label>
                                @foreach ($platformDigital as $pD)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="{{ $pD['name'] }}"value="{{ $pD['name'] }}" {{$data->platform_digital==[]?'':in_array($pD['name'], json_decode($data->platform_digital,true))?'checked':''}} disabled="true">
                                        <label class="form-check-label" for="{{ $pD['name'] }}">{{ $pD['name'] }}</label>
                                    </div>
                                @endforeach

                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Judul Buku:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->judul_buku}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Sub Judul Buku:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-book-open"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->sub_judul}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penulis:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->penulis}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Penerbit:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->penerbit}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Imprint:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-building"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->imprint}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Edisi Cetakan:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-bookmark"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->edisi_cetakan.'/'.$data->tahun_terbit}}" readonly disabled>
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
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Kelompok Buku:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-table"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->kelompok_buku}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>Status Buku:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-check-circle"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{$data->status_buku==1?'Reguler':'MOU'}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 mb-4">
                                <label>SPP: </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-pen-square"></i></div>
                                    </div>
                                    <input type="text" class="form-control" name="up_spp" value="{{$data->spp}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Order:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{Carbon\Carbon::parse($data->tanggal_order)->translatedFormat('d F Y')}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-3 mb-4">
                                <label>Tanggal Harus Upload:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                    </div>
                                    <input type="text" class="form-control" value="{{Carbon\Carbon::parse($data->tgl_upload)->translatedFormat('d F Y')}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-12 mb-4">
                                <label>Keterangan: </label>
                                <input type="text" class="form-control" value="{{$data->keterangan}}" readonly disabled>
                            </div>

                            <div class="form-group col-12 col-md-12 mb-4">
                                <hr>
                                <label>Bukti Upload Link Platform E-Book: </label>

                                <p>
                                    @foreach (json_decode($data->platform_digital) as $plat_digi)
                                        <button class="btn btn-primary" type="button" data-toggle="collapse"
                                        data-target="#{{Illuminate\Support\Str::slug($plat_digi.'link','-')}}" aria-expanded="false" aria-controls="{{Illuminate\Support\Str::slug($plat_digi.'link','-')}}">
                                        {{$plat_digi}}
                                        </button>
                                    @endforeach
                                </p>
                                <input type="hidden" name="id" value="{{$data->id}}">
                                @foreach (json_decode($data->platform_digital) as $i => $plat_digital)
                                    @if (!is_null($data->bukti_upload))
                                        @foreach (json_decode($data->bukti_upload) as $j => $dt_bukti)
                                            @if ($i == $j)
                                                <div class="collapse" id="{{Illuminate\Support\Str::slug($plat_digital.'link','-')}}">
                                                    <label><span class="text-danger fas fa-link"></span>&nbsp;Bukti link upload {{$plat_digital}}: </label>
                                                    <input type="url" class="form-control mb-4" name="bukti_upload[]"
                                                    value="{{is_null($dt_bukti)?'':$dt_bukti}}"  placeholder="https://{{Illuminate\Support\Str::snake($plat_digital)}}.example/">
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="collapse" id="{{Illuminate\Support\Str::slug($plat_digital.'link','-')}}">
                                            <label><span class="text-danger fas fa-link"></span>&nbsp;Bukti link upload {{$plat_digital}}: </label>
                                            <input type="url" class="form-control mb-4" id="bukti_upload[]" name="bukti_upload[]" placeholder="https://{{Illuminate\Support\Str::snake($plat_digital)}}.example/">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success" >Upload Bukti Link</button>
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
<script src="{{url('js/upload_link.js')}}"></script>
@endsection
