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
            <a href="{{ route('desfin.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit/Buat Penerbitan Deskripsi Final</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="card-header-action">
                            @if ($data->status == 'Antrian')
                                <i class="far fa-circle" style="color:#34395E;"></i>
                                Status Progress:
                                <span class="badge" style="background:#34395E;color:white">{{$data->status}}</span>
                            @elseif ($data->status == 'Pending')
                                <i class="far fa-circle text-danger"></i>
                                Status Progress:
                                <span class="badge badge-danger">{{$data->status}}</span>
                            @elseif ($data->status == 'Proses')
                                <i class="far fa-circle text-success"></i>
                                Status Progress:
                                <span class="badge badge-success">{{$data->status}}</span>
                            @elseif ($data->status == 'Selesai')
                                <i class="far fa-circle text-dark"></i>
                                Status Progress:
                                <span class="badge badge-light">{{$data->status}}</span>
                            @elseif ($data->status == 'Revisi')
                                <i class="far fa-circle text-info"></i>
                                Status Progress:
                                <span class="badge badge-info">{{$data->status}}</span>
                            @elseif ($data->status == 'Acc')
                                <i class="far fa-circle text-dark"></i>
                                Status Progress:
                                <span class="badge badge-light">{{$data->status}}</span>
                            @endif
                        </div>
                    </div>
                @if ($data->status == 'Proses'  || ($data->status == 'Selesai' && Gate::allows('do_approval','approval-deskripsi-produk')))
                    <form id="fup_deskripsiFinal">
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
                                                    <th class="table-secondary" style="width: 25%">Sub-Judul Final:</th>
                                                    @if (!is_null($data->sub_judul_final))
                                                    <td class="table-active text-right" id="subJudulFinalCol">
                                                        {{$data->sub_judul_final}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="subJudulFinalButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="subJudulFinalColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="sub_judul_final" class="form-control" cols="30" rows="10">{{$data->sub_judul_final}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_sub_judul_final text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="subJudulFinalCol">
                                                        <a href="javascript:void(0)" id="subJudulFinalButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="subJudulFinalColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="sub_judul_final" class="form-control" cols="30" rows="10"></textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_sub_judul_final text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
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
                                                    <th class="table-secondary" style="width: 25%">Imprint:</th>
                                                    <td class="table-active text-right">{{$data->imprint}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Jumlah halaman perkiraan:</th>
                                                    <td class="table-active text-right">{{$data->jml_hal_perkiraan}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Jumlah halaman asli: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->jml_hal_asli))
                                                    <td class="table-active text-right" id="jmlHalCol">
                                                        {{$data->jml_hal_asli}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="jmlHalButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="jmlHalColInput" hidden>
                                                        <div class="input-group">
                                                            <input type="number" name="jml_hal_asli" value="{{$data->jml_hal_asli}}" class="form-control" min="1" required>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_jml text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    @else
                                                    <td class="table-active text-left">
                                                        <input type="number" name="jml_hal_asli" class="form-control" min="1" required>
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
                                                                    <option value="{{$k}}" {{$data->kelengkapan==$k?'Selected':''}}>{{$k}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_kel text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="kelCol">
                                                        <a href="javascript:void(0)" id="kelButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="kelColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="kelengkapan" class="form-control select-kelengkapan">
                                                                <option label="Pilih kelengkapan"></option>
                                                                @foreach ($kelengkapan as $k)
                                                                    <option value="{{$k}}" {{$data->kelengkapan==$k?'Selected':''}}>{{$k}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_kel text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
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
                                                    <td class="table-active text-right" id="catCol">
                                                        <a href="javascript:void(0)" id="catButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="catColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="catatan" class="form-control" cols="30" rows="10"></textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_cat text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
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
                                                    <th class="table-secondary" style="width: 25%">Kertas Isi:</th>
                                                    @if (!is_null($data->kertas_isi))
                                                    <td class="table-active text-right" id="kertasIsiCol">
                                                        {{$data->kertas_isi}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="kertasIsiButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="kertasIsiColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="kertas_isi" class="form-control select-kertas-isi">
                                                                <option label="Pilih kertas isi"></option>
                                                                @foreach ($kertas_isi as $k)
                                                                    <option value="{{$k}}" {{$data->kertas_isi==$k?'Selected':''}}>{{$k}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_kertas_isi text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="kertas_isi" class="form-control select-kertas-isi">
                                                            <option label="Pilih kertas isi"></option>
                                                            @foreach ($kertas_isi as $k)
                                                                <option value="{{$k}}">{{$k}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Ukuran Asli:</th>
                                                    @if (!is_null($data->ukuran_asli))
                                                    <td class="table-active text-right" id="ukuranAsliCol">
                                                        {{$data->ukuran_asli}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="ukuranAsliButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="ukuranAsliColInput" hidden>
                                                        <div class="input-group">
                                                            <input type="text" name="ukuran_asli" class="form-control" value="{{$data->ukuran_asli}}" placeholder="Ukuran asli">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_ukuran_asli text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <input type="text" name="ukuran_asli" class="form-control" placeholder="Ukuran asli">
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Isi Warna:</th>
                                                    @if (!is_null($data->isi_warna))
                                                    <td class="table-active text-right" id="isiWarnaCol">
                                                        {{$data->isi_warna}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="isiWarnaButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="isiWarnaColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="isi_warna" class="form-control select-isi-warna">
                                                                <option label="Pilih isi warna"></option>
                                                                @foreach ($isi_warna as $i)
                                                                    <option value="{{$i}}" {{$data->isi_warna==$i?'Selected':''}}>{{$i}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_isi_warna text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="isi_warna" class="form-control select-isi-warna">
                                                            <option label="Pilih isi warna"></option>
                                                            @foreach ($isi_warna as $i)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Isi Huruf:</th>
                                                    @if (!is_null($data->isi_huruf))
                                                    <td class="table-active text-right" id="isiHurufCol">
                                                        {{$data->isi_huruf}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="isiHurufButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="isiHurufColInput" hidden>
                                                        <div class="input-group">
                                                            <input type="text" name="isi_huruf" class="form-control" value="{{$data->isi_huruf}}" placeholder="Isi huruf">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_isi_huruf text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <input type="text" name="isi_huruf" class="form-control" placeholder="Isi huruf">
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Bullet</th>
                                                    @if ((is_null($data->bullet)) || ($data->bullet == '[]'))
                                                    <td class="table-active text-left">
                                                        <div class="input_fields_wrap">
                                                            <button class="add_field_button btn btn-outline-primary mb-1"><i class="fas fa-plus-circle"></i> More Fields</button>
                                                            <div class="input-group">
                                                                <input type="text" name="bullet[]" placeholder="Bullet" class="form-control">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="bulletCol">
                                                        @foreach (json_decode($data->bullet,true) as $key => $aj)
                                                            <span class="bullet"></span>{{$aj}}<br>
                                                        @endforeach
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="bulletButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="bulletColInput" hidden>
                                                        <div class="input_fields_wrap">
                                                            <button class="add_field_button btn btn-outline-primary mb-1"><i class="fas fa-plus-circle"></i> More Fields</button>
                                                            <button class="btn btn-outline-danger batal_edit_bullet text-danger align-self-center mb-1" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            @foreach (Illuminate\Support\Arr::whereNotNull(json_decode($data->bullet,true)) as $k => $bullet)
                                                            <div>
                                                                <div class="input-group">
                                                                    <input type="text" name="bullet[]" value="{{$bullet}}" placeholder="Bullet" class="form-control">
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Usulan Setter:</th>
                                                    @if (!is_null($data->setter))
                                                    <td class="table-active text-right" id="setterCol">
                                                        {{$nama_setter->nama}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="setterButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="setterColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="setter" class="form-control select-setter">
                                                                <option label="Pilih setter"></option>
                                                                @foreach ($setter as $s)
                                                                    <option value="{{$s->id}}" {{$data->setter==$s->id?'Selected':''}}>{{$s->nama}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_setter text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="setter" class="form-control select-setter">
                                                            <option label="Pilih setter"></option>
                                                            @foreach ($setter as $s)
                                                                <option value="{{$s->id}}">{{$s->nama}}&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Usulan Korektor:</th>
                                                    @if (!is_null($data->korektor))
                                                    <td class="table-active text-right" id="korektorCol">
                                                        {{$nama_korektor->nama}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="korektorButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="korektorColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="korektor" class="form-control select-korektor">
                                                                <option label="Pilih korektor"></option>
                                                                @foreach ($korektor as $s)
                                                                    <option value="{{$s->id}}" {{$data->korektor==$s->id?'Selected':''}}>{{$s->nama}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_korektor text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <select name="korektor" class="form-control select-korektor">
                                                            <option label="Pilih korektor"></option>
                                                            @foreach ($korektor as $s)
                                                                <option value="{{$s->id}}">{{$s->nama}}&nbsp;&nbsp;</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Sinopsis:</th>
                                                    @if (!is_null($data->sinopsis))
                                                    <td class="table-active text-right" id="sinopsisCol">
                                                        {{$data->sinopsis}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="sinopsisButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="sinopsisColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="sinopsis" class="form-control">{{$data->sinopsis}}</textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_sinopsis text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="sinopsisCol">
                                                        <a href="javascript:void(0)" id="sinopsisButton"><i class="fa fa-plus"></i>&nbsp;Tambahkan</a>
                                                    </td>
                                                    <td class="table-active text-left" id="sinopsisColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="sinopsis" class="form-control"></textarea>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_sinopsis text-danger" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
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
                                                            <input name="bulan" class="form-control datepicker" value="{{Carbon\Carbon::createFromFormat('Y-m-d',$data->bulan,'Asia/Jakarta')->format('F Y')}}" placeholder="Bulan proses" readonly required>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_bulan text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <input name="bulan" class="form-control datepicker" placeholder="Bulan proses" readonly required>
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
                                                <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                                <input type="hidden" name="judul_final" value="{{$data->judul_final}}">
                                                <td class="table-active text-right">{{$data->judul_final}}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                                <input type="hidden" name="judul_final" value="{{$data->judul_final}}">
                                                <td class="table-active text-right">{{$data->judul_final}}</td>
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
                                                <th class="table-secondary" style="width: 25%">Imprint:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->imprint))
                                                        {{$data->imprint}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Jumlah halaman perkiraan:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->jml_hal_perkiraan))
                                                        {{$data->jml_hal_perkiraan}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Jumlah halaman asli: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->jml_hal_asli))
                                                        {{$data->jml_hal_asli}}
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
                                                <th class="table-secondary" style="width: 25%">Kertas Isi:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->kertas_isi))
                                                        {{$data->kertas_isi}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Ukuran Asli:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->ukuran_asli))
                                                        {{$data->ukuran_asli}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Isi Warna:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->isi_warna))
                                                        {{$data->isi_warna}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Isi Huruf:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->isi_huruf))
                                                        {{$data->isi_huruf}}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Bullet</th>
                                                <td class="table-active text-right">
                                                    @if ((is_null($data->bullet)) || ($data->bullet == '[]'))
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @else
                                                        @foreach (json_decode($data->bullet,true) as $key => $aj)
                                                            <span class="bullet"></span>{{$aj}}<br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Usulan Setter:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->setter))
                                                        {{$nama_setter->nama}}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Usulan Korektor:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->korektor))
                                                        {{$nama_korektor->nama}}
                                                    @else
                                                        <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Sinopsis:</th>
                                                <td class="table-active text-left">
                                                    @if (!is_null($data->sinopsis))
                                                        {{$data->sinopsis}}
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
<script src="{{ url('js/edit_deskripsi_final.js') }}"></script>
@endsection
