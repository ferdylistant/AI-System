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
                    <div class="card-header">
                        <div class="card-header-action lead">
                            @if ($data->status == 'Pending')
                                <i class="far fa-circle text-danger"></i>
                                Status Progress:
                                <span class="badge badge-danger">{{$data->status}}</span>
                            @elseif ($data->status == 'Proses')
                                <i class="far fa-circle" style="color:#34395E;"></i>
                                Status Progress:
                                <span class="badge" style="background:#34395E;color:white">{{$data->status}}</span>
                            @elseif ($data->status == 'Selesai')
                                <i class="far fa-circle text-dark"></i>
                                Status Progress:
                                <span class="badge badge-light">{{$data->status}}</span>
                            @endif
                        </div>
                    </div>
                    @if ($data->status == 'Proses')
                    <form id="fup_deskripsiProduk">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Kode naskah:</th>
                                                    <td class="table-active text-right">{{$data->kode}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Judul Asli:</th>
                                                    <input type="hidden" name="judul_asli" value="{{$data->judul_asli}}">
                                                    <td class="table-active text-right">{{$data->judul_asli}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Kelompok Buku:</th>
                                                    <td class="table-active text-right">{{$data->nama}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Penulis:</th>
                                                    <td class="table-active text-right">
                                                        @foreach ($penulis as $p)
                                                            {{$p->nama}}-<br>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Format Buku: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->format_buku))
                                                    <td class="table-active text-right" id="formatBukuCol">
                                                        {{$data->format_buku}} cm
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="formatBukuButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="formatBukuColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="format_buku" class="form-control select-format-buku" required>
                                                                <option label="Pilih format buku"></option>
                                                                @foreach ($format_buku as $fb)
                                                                    <option value="{{$fb->jenis_format}}" {{$data->format_buku==$fb->jenis_format?'Selected':''}}>{{$fb->jenis_format}}&nbsp;cm&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_format text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
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
                                                    <th class="table-secondary" style="width: 25%">Jumlah halaman perkiraan: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->jml_hal_perkiraan))
                                                    <td class="table-active text-right" id="jmlHalCol">
                                                        {{$data->jml_hal_perkiraan}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="jmlHalButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="jmlHalColInput" hidden>
                                                        <div class="input-group">
                                                            <input type="number" name="jml_hal_perkiraan" value="{{$data->jml_hal_perkiraan}}" class="form-control" min="1" required>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_jml text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    @else
                                                    <td class="table-active text-left">
                                                        <input type="number" name="jml_hal_perkiraan" class="form-control" min="1" required>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Kelengkapan:</th>
                                                    @if (!is_null($data->kelengkapan))
                                                    <td class="table-active text-right" id="kelCol">
                                                        {{$data->kelengkapan}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="kelButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="kelColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="kelengkapan" class="form-control select-kelengkapan">
                                                                <option label="Pilih kelengkapan"></option>
                                                                @foreach ($kelengkapan as $k)
                                                                    <option value="{{$k['value']}}" {{$data->kelengkapan==$k['value']?'Selected':''}}>{{$k['value']}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_kel text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="kelengkapan" class="form-control select-kelengkapan">
                                                            <option label="Pilih kelengkapan"></option>
                                                            @foreach ($kelengkapan as $k)
                                                                <option value="{{$k['value']}}">{{$k['value']}}&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Usulan Editor:</th>
                                                    @if (!is_null($data->editor))
                                                    <td class="table-active text-right" id="eCol">
                                                        {{$data->editor}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="eButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="eColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="editor" class="form-control select-editor">
                                                                <option label="Pilih editor"></option>
                                                                @foreach ($editor as $e)
                                                                    <option value="{{$e['id']}}" {{$data->editor==$e['nama']?'Selected':''}}>{{$e['nama']}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_editor text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="editor" class="form-control select-editor">
                                                            <option label="Pilih editor"></option>
                                                            @foreach ($editor as $e)
                                                                <option value="{{$e['id']}}">{{$e['nama']}}&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Catatan:</th>
                                                    @if (!is_null($data->catatan))
                                                    <td class="table-active text-right" id="catCol">
                                                        {{$data->catatan}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="catButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="catColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="catatan" class="form-control" cols="30" rows="10">{{$data->catatan}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_cat text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <textarea name="catatan" class="form-control" cols="30" rows="10"></textarea>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Imprint: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->imprint))
                                                    <td class="table-active text-right" id="imprintCol">
                                                        {{$data->imprint}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="imprintButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="imprintColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="imprint" class="form-control select-imprint" required>
                                                                <option label="Pilih imprint"></option>
                                                                @foreach ($imprint as $i)
                                                                    <option value="{{$i->nama}}" {{$data->imprint==$i->nama?'Selected':''}}>{{$i->nama}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_imprint text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
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
                                                    <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                                    @if (!is_null($data->judul_final))
                                                    <td class="table-active text-right" id="judulFinalCol">
                                                        {{$data->judul_final}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="judulFinalButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="judulFinalColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="judul_final" class="form-control" cols="30" rows="10">{{$data->judul_final}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_judul_final text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <textarea name="judul_final" class="form-control" cols="30" rows="10"></textarea>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->bulan))
                                                    <td class="table-active text-right" id="bulanCol">
                                                        {{Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y')}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="bulanButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="bulanColInput" hidden>
                                                        <div class="input-group">
                                                            <input name="bulan" value="{{Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y')}}" class="form-control datepicker" required>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_bulan text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <input name="bulan" class="form-control datepicker" required>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Alt Judul</th>
                                                    @if (!is_null($data->alt_judul))
                                                    <td class="table-active text-right" id="altCol">
                                                        @foreach (json_decode($data->alt_judul,true) as $aj)
                                                            {{$aj}}-<br>
                                                        @endforeach
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="altButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="altColInput" hidden>
                                                        <div class="input_fields_wrap">
                                                            <button class="add_field_button btn btn-outline-primary mb-1"><i class="fas fa-plus-circle"></i> More Fields</button>
                                                            <button class="btn btn-outline-danger batal_edit_alt text-danger align-self-center mb-1" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            @foreach (Illuminate\Support\Arr::whereNotNull(json_decode($data->alt_judul,true)) as $alt_judul)
                                                            <div class="input-group-append">
                                                                <input type="text" name="alt_judul[]" value="{{$alt_judul}}" placeholder="Alternatif judul" class="form-control">
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <div class="input_fields_wrap">
                                                            <button class="add_field_button btn btn-outline-primary mb-1"><i class="fas fa-plus-circle"></i> More Fields</button>
                                                            <div class="input-group-append"><input type="text" name="alt_judul[]" placeholder="Alternatif judul" class="form-control"></div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                                <input type="hidden" name="id" value="{{$data->id}}">
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                    @else
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Kode naskah:</th>
                                                <td class="table-active text-right">{{$data->kode}}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Judul Asli:</th>
                                                <input type="hidden" name="judul_asli" value="{{$data->judul_asli}}">
                                                <td class="table-active text-right">{{$data->judul_asli}}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Kelompok Buku:</th>
                                                <td class="table-active text-right">{{$data->nama}}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Penulis:</th>
                                                <td class="table-active text-right">
                                                    @foreach ($penulis as $p)
                                                        {{$p->nama}}-<br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Format Buku: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->format_buku))
                                                        {{$data->format_buku}} cm
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Jumlah halaman perkiraan: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->jml_hal_perkiraan))
                                                        {{$data->jml_hal_perkiraan}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Kelengkapan:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->kelengkapan))
                                                        {{$data->kelengkapan}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Usulan Editor:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->editor))
                                                        {{$data->editor}}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Catatan:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->catatan))
                                                        {{$data->catatan}}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Imprint: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->imprint))
                                                        {{$data->imprint}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->judul_final))
                                                        {{$data->judul_final}}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->bulan))
                                                        {{Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y')}}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Alt Judul</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->alt_judul))
                                                        @foreach (json_decode($data->alt_judul,true) as $aj)
                                                            {{$aj}}-<br>
                                                        @endforeach
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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
