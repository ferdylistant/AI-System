@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
<link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
@endsection

@section('cssNeeded')
<style>

</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('setter.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit/Buat Penerbitan Pracetak Setter Proses</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="row card-header justify-content-between">
                        <div class="col-auto">
                            @switch($data->status)
                            @case('Antrian')
                            <i class="far fa-circle" style="color:#34395E;"></i>
                            Status Progress:
                            <span class="badge" style="background:#34395E;color:white">{{ $data->status }}</span>
                            @break

                            @case('Pending')
                            <i class="far fa-circle text-danger"></i>
                            Status Progress:
                            <span class="badge badge-danger">{{ $data->status }}</span>
                            @break

                            @case('Proses')
                            <i class="far fa-circle text-success"></i>
                            Status Progress:
                            <span class="badge badge-success">{{ $data->status }}</span>
                            @break

                            @case('Selesai')
                            <i class="far fa-circle text-dark"></i>
                            Status Progress:
                            <span class="badge badge-light">{{ $data->status }}</span>
                            @break

                            @case('Revisi')
                            <i class="far fa-circle text-info"></i>
                            Status Progress:
                            <span class="badge badge-info">{{ $data->status }}</span>
                            @break
                            @endswitch
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle"></i>&nbsp;Proses Saat Ini:
                            @switch($data->proses_saat_ini)
                                @case('Antrian Koreksi')
                                    <span class="text-dark"> Antri Koreksi</span>
                                    @break
                                @case('Antrian Setting')
                                    <span class="text-dark"> Antrian Setting</span>
                                    @break
                                @case('Setting')
                                    <span class="text-dark" class="text-dark"> Setting</span>
                                    @break
                                @case('Proof Prodev')
                                    <span class="text-dark"> Proof Prodev</span>
                                    @break
                                @case('Koreksi')
                                    <span class="text-dark"> Koreksi</span>
                                    @break
                                @case('Siap Turcet')
                                    <span class="text-dark"> Siap Turcet</span>
                                    @break
                                @case('Turun Cetak')
                                    <span class="text-dark"> Turun Cetak</span>
                                    @break
                                @case('Upload E-Book')
                                    <span class="text-dark"> Upload E-Book</span>
                                    @break
                                @case('Setting Revisi')
                                    <span class="text-dark"> Setting Revisi</span>
                                    @break
                                @default
                                    <span class="text-danger"> Belum ada proses</span>
                                    @break
                            @endswitch
                            @if ($data->status == 'Revisi')
                            <br>
                                <button type="button" class="btn btn-warning" id="done-revision"
                                data-id="{{$data->id}}" data-judul="{{$data->judul_final}}" data-kode="{{$data->kode}}">
                                    <i class="fas fa-check"></i>&nbsp;Selesai Revisi
                                </button>
                            @endif
                        </div>

                    </div>
                    @if ($data->status == 'Proses' || $data->status == 'Revisi' ||
                    ($data->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk')))
                    <form id="fup_pracetakSetter">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Kode Naskah:</th>
                                                    <td class="table-active text-right">{{ $data->kode }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                                    <input type="hidden" name="judul_final" value="{{ $data->judul_final }}">
                                                    <td class="table-active text-right">{{ $data->judul_final }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Sub-Judul Final:
                                                    </th>
                                                    <td class="table-active text-right">
                                                        {{ is_null($data->sub_judul_final) ? '-' : $data->sub_judul_final }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Kelompok Buku:
                                                    </th>
                                                    <td class="table-active text-right">{{ $data->nama }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Penulis:</th>
                                                    <td class="table-active text-right">
                                                        @foreach ($penulis as $p)
                                                        {{ $p->nama }}-<br>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Isi Warna:</th>
                                                    <td class="table-active text-right">
                                                        {{ is_null($data->isi_warna) ? '-' : $data->isi_warna }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Isi Huruf:</th>
                                                    <td class="table-active text-right">
                                                        {{ is_null($data->isi_huruf) ? '-' : $data->isi_huruf }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Sinopsis:</th>
                                                    <td class="table-active text-right">
                                                        {{ is_null($data->sinopsis) ? '-' : $data->sinopsis }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Bullet:</th>
                                                    <td class="table-active text-right">
                                                        @foreach (json_decode($data->bullet, true) as $key => $aj)
                                                        <span class="bullet"></span>{{ $aj }}<br>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Jumlah Halaman
                                                        Final: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->jml_hal_final))
                                                    <td class="table-active text-right" id="jmlHalCol">
                                                        {{ $data->jml_hal_final }}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="jmlHalButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="jmlHalColInput" hidden>
                                                        <div class="input-group">
                                                            <input type="number" name="jml_hal_final" value="{{ $data->jml_hal_final }}" class="form-control" min="1" required>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_jml text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <input type="number" name="jml_hal_final" class="form-control" min="1" required>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Catatan:</th>
                                                    @if (!is_null($data->catatan))
                                                    <td class="table-active text-right" id="catCol">
                                                        {{ $data->catatan }}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="catButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="catColInput" hidden>
                                                        <div class="input-group">
                                                            <textarea name="catatan" class="form-control" cols="30" rows="10">{{ $data->catatan }}</textarea>
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
                                                    <th class="table-secondary" style="width: 25%">Setter: <span class="text-danger">*</span></th>
                                                    @if (is_null($data->setter) || $data->setter == '[]')
                                                    <td class="table-active text-left">
                                                        <select name="setter[]" class="form-control select-setter-editing" multiple="multiple" required>
                                                            <option label="Pilih setter"></option>
                                                            @foreach ($setter as $i => $edList)
                                                            <option value="{{ $edList->id }}">
                                                                {{ $edList->nama }}&nbsp;&nbsp;
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="setterCol">
                                                        @foreach ($nama_setter as $key => $aj)
                                                        <span class="bullet"></span>{{ $aj }}<br>
                                                        @endforeach
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="setterButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                                        </p>

                                                    </td>
                                                    <td class="table-active text-left" id="setterColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="setter[]" class="form-control select-setter" multiple="multiple">
                                                                <option label="Pilih setter"></option>
                                                                @foreach ($setter as $i => $edList)
                                                                {{ $sel = '' }}
                                                                @if (in_array($edList->nama, $nama_setter))
                                                                {{ $sel = ' selected="selected" ' }}
                                                                @endif
                                                                <option value="{{ $edList->id }}" {{ $sel }}>
                                                                    {{ $edList->nama }}&nbsp;&nbsp;
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_setter text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Korektor:
                                                        @if (!is_null($data->selesai_proof))
                                                        <span class="text-danger">*</span>
                                                        @endif
                                                    </th>
                                                    @if (is_null($data->korektor) || $data->korektor == '[]')
                                                    <td class="table-active text-left">
                                                        {{ $dis = '' }}
                                                        @if (is_null($data->selesai_proof))
                                                        <span class="text-danger"><i class="fas fa-exclamation-circle"></i>
                                                            Belum bisa melanjutkan proses koreksi,
                                                            proses setting belum selesai.</span>
                                                        <span hidden>{{ $dis = 'disabled="disabled"' }}</span>
                                                        @endif
                                                        <select name="korektor[]" class="form-control select-korektor" multiple="multiple" {{ $dis }} required>
                                                            <option label="Pilih korektor"></option>
                                                            @if (!is_null($korektor))
                                                            @foreach ($korektor as $cpeList)
                                                            <option value="{{ $cpeList->id }}">
                                                                {{ $cpeList->nama }}&nbsp;&nbsp;
                                                            </option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="korektorCol">
                                                        @foreach ($nama_korektor as $key => $aj)
                                                        <span class="bullet"></span>{{ $aj }}<br>
                                                        @endforeach
                                                        @if (is_null($data->selesai_koreksi))
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="korektorButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                                        </p>
                                                        @endif
                                                    </td>
                                                    <td class="table-active text-left" id="korektorColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="korektor[]" class="form-control select-korektor" multiple="multiple">
                                                                <option label="Pilih korektor"></option>
                                                                @foreach ($korektor as $i => $cpeList)
                                                                {{ $sl = '' }}
                                                                @if (in_array($cpeList->nama, $nama_korektor))
                                                                {{ $sl = ' selected="selected" ' }}
                                                                @endif
                                                                <option value="{{ $cpeList->id }}" {{ $sl }}>
                                                                    {{ $cpeList->nama }}&nbsp;&nbsp;
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_korektor text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Edisi Cetak:</th>
                                                    @if (!is_null($data->edisi_cetak))
                                                    <td class="table-active text-right" id="edCetakCol">
                                                        {{ $data->edisi_cetak }}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="edCetakButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="edCetakColInput" hidden>
                                                        <div class="input-group">
                                                            <input type="text" name="edisi_cetak" value="{{ $data->edisi_cetak }}" class="form-control">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_edisicetak text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <input type="text" name="edisi_cetak" class="form-control">
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Mulai Proses Copyright:</th>
                                                    @if (!is_null($data->mulai_p_copyright))
                                                    <td class="table-active text-right" id="copyrightCol">
                                                        {{ Carbon\Carbon::parse($data->mulai_p_copyright)->translatedFormat('l d F Y, H:i') }}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="copyrightButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="copyrightColInput" hidden>
                                                        <div class="input-group">
                                                            <input name="mulai_p_copyright" class="form-control datepicker-copyright" placeholder="Tanggal mulai proses copyright" readonly>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_copyright text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <input name="mulai_p_copyright" class="form-control datepicker-copyright" placeholder="Tanggal mulai proses copyright" readonly>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Selesai Proses Copyright:</th>
                                                    @if (!is_null($data->selesai_p_copyright))
                                                    <td class="table-active text-right" id="copyrightSelCol">
                                                        {{ Carbon\Carbon::parse($data->selesai_p_copyright)->translatedFormat('l d F Y, H:i') }}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="copyrightSelButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="copyrightSelColInput" hidden>
                                                        <div class="input-group">
                                                            <input name="selesai_p_copyright" class="form-control datepicker-copyright" placeholder="Tanggal selesai proses copyright" readonly>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_selesaicopyright text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <input name="selesai_p_copyright" class="form-control datepicker-copyright" placeholder="Tanggal selesai proses copyright" readonly>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">ISBN: <span class="text-danger">*Wajib ketika ingin menyelesaikan proses</span></th>
                                                    @if (!is_null($data->isbn))
                                                    <td class="table-active text-right" id="isbnCol">
                                                        {{$data->isbn}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="isbnButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="isbnColInput" hidden>
                                                        <div class="input-group">
                                                            <input type="text" name="isbn" class="form-control" value="{{ $data->isbn }}"  placeholder="000000000000" id="ISBN">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_isbn text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <div class="input-group">
                                                            <input type="text" name="isbn" class="form-control"  placeholder="000000000000" id="ISBN">
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Pengajuan Harga: <span class="text-danger">*Wajib ketika ingin menyelesaikan proses</span></th>
                                                    @if (!is_null($data->pengajuan_harga))
                                                    <td class="table-active text-right" id="hargaCol">
                                                        {{$data->pengajuan_harga}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="hargaButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="hargaColInput" hidden>
                                                        <div class="input-group">
                                                            <input type="text" name="pengajuan_harga" class="form-control" value="{{ $data->pengajuan_harga }}"  placeholder="000000000000" id="HARGA">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_pengajuan_harga text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <div class="input-group">
                                                            <input type="text" name="pengajuan_harga" class="form-control" placeholder="000000000000" id="HARGA">
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Proses Saat Ini: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->proses_saat_ini))
                                                    <td class="table-active text-right" id="prosCol">
                                                        {{$data->proses_saat_ini}}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="prosButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="prosColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="proses_saat_ini" class="form-control select-proses" required>
                                                                <option label="Pilih proses saat ini"></option>
                                                                @foreach ($proses_saat_ini as $k)
                                                                    <option value="{{$k}}" {{$data->proses_saat_ini==$k?'Selected':''}}>{{$k}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_proses text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-left">
                                                        <div class="input-group">
                                                            <select name="proses_saat_ini" class="form-control select-proses" required>
                                                                <option label="Pilih proses saat ini"></option>
                                                                @foreach ($proses_saat_ini as $k)
                                                                    <option value="{{$k}}" {{$data->proses_saat_ini==$k?'Selected':''}}>{{$k}}&nbsp;&nbsp;</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->bulan))
                                                    <td class="table-active text-right" id="bulanCol">
                                                        {{ Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y') }}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="bulanButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="bulanColInput" hidden>
                                                        <div class="input-group">
                                                            <input name="bulan" class="form-control datepicker" value="{{Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y')}}" placeholder="Bulan proses" readonly required>
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
                            <input type="hidden" name="id" value="{{ $data->id }}">
                        </div>
                        <div class="card-footer text-right">
                            <div class="custom-control custom-switch">
                                @if ($data->proses == '1')
                                <?php $label = 'Stop'; ?>
                                @else
                                <?php $label = 'Mulai'; ?>
                                @endif
                                <?php $disable = ''; ?>
                                @if (!is_null($data->mulai_proof) && is_null($data->selesai_proof))
                                    <?php
                                    $disable = 'disabled';
                                    $lbl = 'Sedang proses proof prodev';
                                    ?>
                                @elseif (is_null($data->selesai_setting) && is_null($data->selesai_koreksi))
                                    <?php
                                    $lbl = $label.' proses setting';
                                    ?>
                                @elseif (is_null($data->selesai_koreksi) && !is_null($data->selesai_setting))
                                    <?php
                                    $lbl = $label.' proses koreksi';
                                    ?>
                                @elseif ((!is_null($data->selesai_koreksi)) && ($data->proses_saat_ini == 'Setting Revisi') && ($data->status == 'Proses'))
                                    <?php
                                    $lbl = $label.' proses revisi setting';
                                    ?>
                                @else
                                    <?php
                                    $disable = 'disabled';
                                    $lbl = '-';
                                    ?>
                                @endif
                                <input type="checkbox" name="proses" class="custom-control-input" id="prosesKerja" data-id="{{ $data->id }}" {{ $data->proses == '1' ? 'checked' : '' }} {{$disable}}>
                                <label class="custom-control-label mr-3 text-dark" for="prosesKerja">
                                    {{ $lbl }}
                                </label>
                            </div>
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
                                                <td class="table-active text-right">{{ $data->kode }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                                <input type="hidden" name="judul_final" value="{{ $data->judul_final }}">
                                                <td class="table-active text-right">{{ $data->judul_final }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Sub-Judul Final:
                                                </th>
                                                <input type="hidden" name="sub_judul_final" value="{{ $data->sub_judul_final }}">
                                                <td class="table-active text-right">
                                                    {{ is_null($data->sub_judul_final) ? '-' : $data->sub_judul_final }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Kelompok Buku:</th>
                                                <td class="table-active text-right">{{ $data->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Penulis:</th>
                                                <td class="table-active text-right">
                                                    @foreach ($penulis as $p)
                                                    {{ $p->nama }}-<br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Isi Warna:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->isi_warna))
                                                    {{ $data->isi_warna }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Isi Huruf:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->isi_huruf))
                                                    {{ $data->isi_huruf }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Sinopsis:</th>
                                                <td class="table-active text-left">
                                                    @if (!is_null($data->sinopsis))
                                                    {{ $data->sinopsis }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Bullet</th>
                                                <td class="table-active text-right">
                                                    @if (is_null($data->bullet) || $data->bullet == '[]')
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @else
                                                    @foreach (json_decode($data->bullet, true) as $key => $aj)
                                                    <span class="bullet"></span>{{ $aj }}<br>
                                                    @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Jumlah halaman
                                                    final: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->jml_hal_perkiraan))
                                                    {{ $data->jml_hal_perkiraan }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Catatan:</th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->catatan))
                                                    {{ $data->catatan }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Setter: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->setter))
                                                    @foreach ($nama_setter as $ne)
                                                    <span class="bullet"></span>{{ $ne }}<br>
                                                    @endforeach
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Korektor:
                                                </th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->korektor))
                                                    @foreach ($nama_korektor as $nc)
                                                        <span class="bullet"></span>{{ $nc }}<br>
                                                        @endforeach
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Edisi Cetak: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->edisi_cetak))
                                                    {{ $data->edisi_cetak }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Mulai Proses Copyright: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->mulai_p_copyright))
                                                    {{ Carbon\Carbon::parse($data->mulai_p_copyright)->translatedFormat('l d F Y, H:i') }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Selesai Proses Copyright: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->selesai_p_copyright))
                                                    {{ Carbon\Carbon::parse($data->selesai_p_copyright)->translatedFormat('l d F Y, H:i') }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">ISBN: <span class="text-danger">*Wajib ketika ingin menyelesaikan proses</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->isbn))
                                                    {{ $data->isbn }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Pengajuan Harga: <span class="text-danger">*Wajib ketika ingin menyelesaikan proses</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->pengajuan_harga))
                                                    {{ $data->pengajuan_harga }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Proses Saat Ini: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->proses_saat_ini))
                                                    {{ $data->proses_saat_ini }}
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->bulan))
                                                    {{ Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y') }}
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
<script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="https://unpkg.com/imask"></script>
<script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
@endsection


@section('jsNeeded')
<script src="{{ url('js/edit_pracetak_setter.js') }}"></script>
@endsection
