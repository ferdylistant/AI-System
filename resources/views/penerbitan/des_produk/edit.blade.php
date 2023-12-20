@extends('layouts.app')

@section('cssRequired')
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"
        integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg=="
        crossorigin="anonymous" />
@endsection

@section('cssNeeded')
<style type="text/css">

    .bootstrap-tagsinput{

        width: 100%;

    }

</style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>Edit/Buat Penerbitan Deskripsi Produk</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{url('/')}}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{url('/penerbitan/deskripsi/produk')}}">Data Deskripsi Produk</a>
                </div>
                <div class="breadcrumb-item">
                    Edit/Buat Penerbitan Deskripsi Produk
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="section-title">Form Deskripsi Produk</h4>
                            <div class="card-header-action">
                                @if ($data->status == 'Antrian')
                                    <i class="far fa-circle" style="color:#34395E;"></i>
                                    Status Progress:
                                    <span class="badge" style="background:#34395E;color:white">{{ $data->status }}</span>
                                @elseif ($data->status == 'Pending')
                                    <i class="far fa-circle text-danger"></i>
                                    Status Progress:
                                    <span class="badge badge-danger">{{ $data->status }}</span>
                                @elseif ($data->status == 'Proses')
                                    <i class="far fa-circle text-success"></i>
                                    Status Progress:
                                    <span class="badge badge-success">{{ $data->status }}</span>
                                @elseif ($data->status == 'Selesai')
                                    <i class="far fa-circle text-dark"></i>
                                    Status Progress:
                                    <span class="badge badge-light">{{ $data->status }}</span>
                                @elseif ($data->status == 'Revisi')
                                    <i class="far fa-circle text-info"></i>
                                    Status Progress:
                                    <span class="badge badge-info">{{ $data->status }}</span>
                                @elseif ($data->status == 'Acc')
                                    <i class="far fa-circle text-dark"></i>
                                    Status Progress:
                                    <span class="badge badge-light">{{ $data->status }}</span>
                                @endif
                            </div>
                        </div>
                        @if (
                            $data->status == 'Proses' ||
                                $data->status == 'Revisi' ||
                                ($data->status == 'Acc' && Gate::allows('do_approval', 'approval-deskripsi-produk')))
                            <form id="fup_deskripsiProduk">
                                {!! csrf_field() !!}
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
                                                            <th class="table-secondary" style="width: 25%">Judul Asli:</th>
                                                            <input type="hidden" name="judul_asli"
                                                                value="{{ $data->judul_asli }}">
                                                            <td class="table-active text-right">{{ $data->judul_asli }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="table-secondary" style="width: 25%">Kelompok Buku:
                                                            </th>
                                                            <input type="hidden" name="kelompok_id"
                                                                value="{{ $data->kelompok_buku_id }}">
                                                            <td class="table-active text-right">{{ $data->nama }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="table-secondary" style="width: 25%">Sub-Kelompok Buku: <span class="text-danger">*</span>
                                                            </th>
                                                            <td class="table-active text-right">
                                                                @if (!is_null($data->sub_kelompok_buku_id))
                                                                {{ $data->nama_skb }}
                                                                @else
                                                                <select id="sKelBuku" name="sub_kelompok_buku"
                                                                        class="form-control select-sub" required>
                                                                        <option label="Pilih"></option>
                                                                    </select>
                                                                @endif
                                                            </td>
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
                                                            <th class="table-secondary" style="width: 25%">Nama Pena: <span
                                                                    class="text-danger">*Kosongkan jika sama dengan nama
                                                                    asli penulis</span></th>
                                                            @if (!is_null($data->nama_pena))
                                                                <td class="table-active text-right" id="namaPenaCol">
                                                                    @foreach (json_decode($data->nama_pena) as $np)
                                                                    {{ $np }}-<br>
                                                                    @endforeach
                                                                    <p class="text-small">
                                                                        <a href="javascript:void(0)" id="namaPenaButton"><i
                                                                                class="fa fa-pen"></i>&nbsp;Edit</a>
                                                                    </p>
                                                                </td>
                                                                <td class="table-active text-left" id="namaPenaColInput"
                                                                    hidden>
                                                                    <div class="input-group">
                                                                        <input type="text" name="nama_pena"
                                                                            class="form-control input-tag"
                                                                            placeholder="Nama pena / Alias"
                                                                            data-role="tagsinput"
                                                                            value="{{ $nama_pena }}">
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger batal_edit_nama_pena text-danger align-self-center"
                                                                                data-toggle="tooltip" title="Batal Edit"><i
                                                                                    class="fas fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td class="table-active text-left">
                                                                    <input type="text" name="nama_pena"
                                                                        class="form-control input-tag"
                                                                        placeholder="Nama pena / Alias"
                                                                        data-role="tagsinput">
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th class="table-secondary" style="width: 25%">Format Buku:
                                                                <span class="text-danger">*</span></th>
                                                            @if (!is_null($data->format_buku))
                                                                <td class="table-active text-right" id="formatBukuCol">
                                                                    {{ $format_buku }} cm
                                                                    <p class="text-small">
                                                                        <a href="javascript:void(0)"
                                                                            id="formatBukuButton"><i
                                                                                class="fa fa-pen"></i>&nbsp;Edit</a>
                                                                    </p>
                                                                </td>
                                                                <td class="table-active text-left" id="formatBukuColInput"
                                                                    hidden>
                                                                    <div class="input-group">
                                                                        <select name="format_buku"
                                                                            class="form-control select-format-buku"
                                                                            required>
                                                                            <option label="Pilih format buku"></option>
                                                                            @foreach ($format_buku_list as $fb)
                                                                                <option value="{{ $fb->id }}"
                                                                                    {{ $data->format_buku == $fb->id ? 'Selected' : '' }}>
                                                                                    {{ $fb->jenis_format }}&nbsp;cm&nbsp;&nbsp;
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger batal_edit_format text-danger align-self-center"
                                                                                data-toggle="tooltip"
                                                                                title="Batal Edit"><i
                                                                                    class="fas fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td class="table-active text-left">
                                                                    <select name="format_buku"
                                                                        class="form-control select-format-buku" required>
                                                                        <option label="Pilih format buku"></option>
                                                                        @foreach ($format_buku_list as $fb)
                                                                            <option value="{{ $fb->id }}">
                                                                                {{ $fb->jenis_format }}&nbsp;cm&nbsp;&nbsp;
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th class="table-secondary" style="width: 25%">Jumlah halaman
                                                                perkiraan: <span class="text-danger">*</span></th>
                                                            @if (!is_null($data->jml_hal_perkiraan))
                                                                <td class="table-active text-right" id="jmlHalCol">
                                                                    {{ $data->jml_hal_perkiraan }}
                                                                    <p class="text-small">
                                                                        <a href="javascript:void(0)" id="jmlHalButton"><i
                                                                                class="fa fa-pen"></i>&nbsp;Edit</a>
                                                                    </p>
                                                                </td>
                                                                <td class="table-active text-left" id="jmlHalColInput"
                                                                    hidden>
                                                                    <div class="input-group">
                                                                        <input type="number" name="jml_hal_perkiraan"
                                                                            value="{{ $data->jml_hal_perkiraan }}"
                                                                            class="form-control" min="1" required>
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger batal_edit_jml text-danger align-self-center"
                                                                                data-toggle="tooltip"
                                                                                title="Batal Edit"><i
                                                                                    class="fas fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td class="table-active text-left">
                                                                    <input type="number" name="jml_hal_perkiraan"
                                                                        class="form-control" min="1" required>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th class="table-secondary" style="width: 25%">Kelengkapan:
                                                            </th>
                                                            @if (!is_null($data->kelengkapan))
                                                                <td class="table-active text-right" id="kelCol">
                                                                    {{ $data->kelengkapan }}
                                                                    <p class="text-small">
                                                                        <a href="javascript:void(0)" id="kelButton"><i
                                                                                class="fa fa-pen"></i>&nbsp;Edit</a>
                                                                    </p>
                                                                </td>
                                                                <td class="table-active text-left" id="kelColInput"
                                                                    hidden>
                                                                    <div class="input-group">
                                                                        <select name="kelengkapan"
                                                                            class="form-control select-kelengkapan">
                                                                            <option label="Pilih kelengkapan"></option>
                                                                            @foreach ($kelengkapan as $k)
                                                                                <option value="{{ $k }}"
                                                                                    {{ $data->kelengkapan == $k ? 'Selected' : '' }}>
                                                                                    {{ $k }}&nbsp;&nbsp;
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger batal_edit_kel text-danger align-self-center"
                                                                                data-toggle="tooltip"
                                                                                title="Batal Edit"><i
                                                                                    class="fas fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td class="table-active text-left">
                                                                    <select name="kelengkapan"
                                                                        class="form-control select-kelengkapan">
                                                                        <option label="Pilih kelengkapan"></option>
                                                                        @foreach ($kelengkapan as $k)
                                                                            <option value="{{ $k }}">
                                                                                {{ $k }}&nbsp;&nbsp;</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th class="table-secondary" style="width: 25%">Usulan Editor:
                                                            </th>
                                                            @if (!is_null($data->editor))
                                                                <td class="table-active text-right" id="eCol">
                                                                    {{ $nama_editor }}
                                                                    <p class="text-small">
                                                                        <a href="javascript:void(0)" id="eButton"><i
                                                                                class="fa fa-pen"></i>&nbsp;Edit</a>
                                                                    </p>
                                                                </td>
                                                                <td class="table-active text-left" id="eColInput" hidden>
                                                                    <div class="input-group">
                                                                        <select name="editor"
                                                                            class="form-control select-editor">
                                                                            <option label="Pilih editor"></option>
                                                                            @foreach ($editor as $e)
                                                                                <option value="{{ $e->id }}"
                                                                                    {{ $data->editor == $e->id ? 'Selected' : '' }}>
                                                                                    {{ $e->nama }}&nbsp;&nbsp;
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger batal_edit_editor text-danger align-self-center"
                                                                                data-toggle="tooltip"
                                                                                title="Batal Edit"><i
                                                                                    class="fas fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td class="table-active text-left">
                                                                    <select name="editor"
                                                                        class="form-control select-editor">
                                                                        <option label="Pilih editor"></option>
                                                                        @foreach ($editor as $e)
                                                                            <option value="{{ $e->id }}">
                                                                                {{ $e->nama }}&nbsp;&nbsp;</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th class="table-secondary" style="width: 25%">Catatan:</th>
                                                            @if (!is_null($data->catatan))
                                                                <td class="table-active text-right" id="catCol">
                                                                    {{ $data->catatan }}
                                                                    <p class="text-small">
                                                                        <a href="javascript:void(0)" id="catButton"><i
                                                                                class="fa fa-pen"></i>&nbsp;Edit</a>
                                                                    </p>
                                                                </td>
                                                                <td class="table-active text-left" id="catColInput"
                                                                    hidden>
                                                                    <div class="input-group">
                                                                        <textarea name="catatan" class="form-control" cols="30" rows="10">{{ $data->catatan }}</textarea>
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger batal_edit_cat text-danger"
                                                                                data-toggle="tooltip"
                                                                                title="Batal Edit"><i
                                                                                    class="fas fa-times"></i></button>
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
                                                            <th class="table-secondary" style="width: 25%">Imprint: <span
                                                                    class="text-danger">*</span></th>
                                                            @if (!is_null($data->imprint))
                                                                <td class="table-active text-right" id="imprintCol">
                                                                    {{ $nama_imprint }}
                                                                    <p class="text-small">
                                                                        <a href="javascript:void(0)" id="imprintButton"><i
                                                                                class="fa fa-pen"></i>&nbsp;Edit</a>
                                                                    </p>
                                                                </td>
                                                                <td class="table-active text-left" id="imprintColInput"
                                                                    hidden>
                                                                    <div class="input-group">
                                                                        <select name="imprint"
                                                                            class="form-control select-imprint" required>
                                                                            <option label="Pilih imprint"></option>
                                                                            @foreach ($imprint as $i)
                                                                                <option value="{{ $i->id }}"
                                                                                    {{ $data->imprint == $i->id ? 'Selected' : '' }}>
                                                                                    {{ $i->nama }}&nbsp;&nbsp;
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger batal_edit_imprint text-danger align-self-center"
                                                                                data-toggle="tooltip"
                                                                                title="Batal Edit"><i
                                                                                    class="fas fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td class="table-active text-left">
                                                                    <select name="imprint"
                                                                        class="form-control select-imprint" required>
                                                                        <option label="Pilih imprint"></option>
                                                                        @foreach ($imprint as $i)
                                                                            <option value="{{ $i->id }}">
                                                                                {{ $i->nama }}&nbsp;&nbsp;</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th class="table-secondary" style="width: 25%">Bulan: <span
                                                                    class="text-danger">*</span></th>
                                                            @if (!is_null($data->bulan))
                                                                <td class="table-active text-right" id="bulanCol">
                                                                    {{ Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y') }}
                                                                    <p class="text-small">
                                                                        <a href="javascript:void(0)" id="bulanButton"><i
                                                                                class="fa fa-pen"></i>&nbsp;Edit</a>
                                                                    </p>
                                                                </td>
                                                                <td class="table-active text-left" id="bulanColInput"
                                                                    hidden>
                                                                    <div class="input-group">
                                                                        <input name="bulan"
                                                                            class="form-control datepicker"
                                                                            value="{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->bulan, 'Asia/Jakarta')->format('F Y') }}"
                                                                            placeholder="Bulan proses" readonly required>
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger batal_edit_bulan text-danger align-self-center"
                                                                                data-toggle="tooltip"
                                                                                title="Batal Edit"><i
                                                                                    class="fas fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td class="table-active text-left">
                                                                    <input name="bulan" class="form-control datepicker"
                                                                        placeholder="Bulan proses" readonly required>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th class="table-secondary" style="width: 25%">Alt Judul</th>
                                                            @if (!is_null($data->alt_judul))
                                                                <td class="table-active text-right" id="altCol">
                                                                    @foreach (json_decode($data->alt_judul, true) as $key => $aj)
                                                                        @if ($key < 1)
                                                                            <i class="fas fa-check text-danger"></i><span
                                                                                class="text-danger">(Usulan Penulis)</span>
                                                                        @endif
                                                                        {{ $aj }}-<br>
                                                                    @endforeach
                                                                    <p class="text-small">
                                                                        <a href="javascript:void(0)" id="altButton"><i
                                                                                class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                                                    </p>
                                                                </td>
                                                                <td class="table-active text-left" id="altColInput"
                                                                    hidden>
                                                                    <div class="input_fields_wrap">
                                                                        <button
                                                                            class="add_field_button btn btn-outline-primary mb-1"><i
                                                                                class="fas fa-plus-circle"></i> More
                                                                            Fields</button>
                                                                        <button
                                                                            class="btn btn-outline-danger batal_edit_alt text-danger align-self-center mb-1"
                                                                            data-toggle="tooltip" title="Batal Edit"><i
                                                                                class="fas fa-times"></i></button>
                                                                        @foreach (Illuminate\Support\Arr::whereNotNull(json_decode($data->alt_judul, true)) as $k => $alt_judul)
                                                                            <div>
                                                                                <div class="input-group">
                                                                                    @if ($k < 1)
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text text-danger font-weight-bold"><span
                                                                                                    class="bullet"></span>Usulan
                                                                                                Penulis</span>
                                                                                        </div>
                                                                                    @endif
                                                                                    <textarea
                                                                                        name="alt_judul[]"
                                                                                        placeholder="Alternatif judul"
                                                                                        class="form-control">{{ $alt_judul }}</textarea>

                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td class="table-active text-left">
                                                                    <div class="input_fields_wrap">
                                                                        <button
                                                                            class="add_field_button btn btn-outline-primary mb-1"><i
                                                                                class="fas fa-plus-circle"></i> More
                                                                            Fields</button>
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <span
                                                                                    class="input-group-text text-danger font-weight-bold"><span
                                                                                        class="bullet"></span>Usulan
                                                                                    Penulis</span>
                                                                            </div>
                                                                            <textarea name="alt_judul[]"
                                                                                placeholder="Alternatif judul"
                                                                                class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
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
                                                        <th class="table-secondary" style="width: 25%">Judul Asli:</th>
                                                        <input type="hidden" name="judul_asli"
                                                            value="{{ $data->judul_asli }}">
                                                        <td class="table-active text-right">{{ $data->judul_asli }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Kelompok Buku:</th>
                                                        <td class="table-active text-right">{{ $data->nama }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Sub-Kelompok Buku: <span class="text-danger">*</span></th>
                                                        <td class="table-active text-right">
                                                            @if (!is_null($data->sub_kelompok_buku_id))
                                                                {{ $data->nama_skb }}
                                                            @else
                                                            <span class="text-danger text-small">Belum diinput</span>
                                                            @endif
                                                        </td>
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
                                                        <th class="table-secondary" style="width: 25%">Nama Pena: <span
                                                                class="text-danger">*Kosongkan jika sama dengan nama asli
                                                                penulis</span></th>
                                                        <td class="table-active text-right">
                                                            @if (!is_null($data->nama_pena))
                                                                {{ $nama_pena }}
                                                            @else
                                                                <span class="text-danger text-small">Belum diinput</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Format Buku: <span
                                                                class="text-danger">*</span></th>
                                                        <td class="table-active text-right">
                                                            @if (!is_null($data->format_buku))
                                                                {{ $format_buku }} cm
                                                            @else
                                                                <span class="text-danger text-small">Belum diinput</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Jumlah halaman
                                                            perkiraan: <span class="text-danger">*</span></th>
                                                        <td class="table-active text-right">
                                                            @if (!is_null($data->jml_hal_perkiraan))
                                                                {{ $data->jml_hal_perkiraan }}
                                                            @else
                                                                <span class="text-danger text-small">Belum diinput</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Kelengkapan:</th>
                                                        <td class="table-active text-right">
                                                            @if (!is_null($data->kelengkapan))
                                                                {{ $data->kelengkapan }}
                                                            @else
                                                                <span class="text-danger text-small">Belum diinput</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Usulan Editor:</th>
                                                        <td class="table-active text-right">
                                                            @if (!is_null($data->editor))
                                                                {{ $nama_editor }}
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
                                                        <th class="table-secondary" style="width: 25%">Imprint: <span
                                                                class="text-danger">*</span></th>
                                                        <td class="table-active text-right">
                                                            @if (!is_null($data->imprint))
                                                                {{ $nama_imprint }}
                                                            @else
                                                                <span class="text-danger text-small">Belum diinput</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Bulan: <span
                                                                class="text-danger">*</span></th>
                                                        <td class="table-active text-right">
                                                            @if (!is_null($data->bulan))
                                                                {{ Carbon\Carbon::parse($data->bulan)->translatedFormat('F Y') }}
                                                            @else
                                                                <span class="text-danger text-small">Belum diinput</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-secondary" style="width: 25%">Alt Judul</th>
                                                        <td class="table-active text-right">
                                                            @if (!is_null($data->alt_judul))
                                                                @foreach (json_decode($data->alt_judul, true) as $key => $aj)
                                                                    @if ($key < 1)
                                                                        <i class="fas fa-check text-danger"></i><span
                                                                            class="text-danger">(Usulan Penulis)</span>
                                                                    @endif
                                                                    {{ $aj }}-<br>
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
    <script src="{{ url('vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"
        integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg=="
        crossorigin="anonymous"></script>
@endsection


@section('jsNeeded')
    <script src="{{ url('js/penerbitan/edit_deskripsi_produk.js') }}" defer></script>
@endsection
