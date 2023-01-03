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
            <a href="{{ route('editing.view') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit/Buat Penerbitan Editing Proses</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="row card-header justify-content-between">
                        <div class="col-auto card-header-action">
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
                            @endswitch
                        </div>
                        @if ($data->proses == '1')
                        <div class="col-auto">
                            <span class="text-danger"><i class="fas fa-exclamation-circle"></i>&nbsp;Sedang proses
                                pengerjaan {{ is_null($data->tgl_selesai_edit) ? 'editor' : 'copy editor' }}</span>
                        </div>
                        @endif

                    </div>
                    @if ($data->status == 'Proses' ||
                    ($data->status == 'Selesai' && Gate::allows('do_approval', 'approval-deskripsi-produk')))
                    <form id="fup_editingProses">
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
                                                    <th class="table-secondary" style="width: 25%">Jumlah halaman
                                                        final: <span class="text-danger">*</span></th>
                                                    @if (!is_null($data->jml_hal_perkiraan))
                                                    <td class="table-active text-right" id="jmlHalCol">
                                                        {{ $data->jml_hal_perkiraan }}
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="jmlHalButton"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="jmlHalColInput" hidden>
                                                        <div class="input-group">
                                                            <input type="number" name="jml_hal_perkiraan" value="{{ $data->jml_hal_perkiraan }}" class="form-control" min="1" required>
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
                                                    <th class="table-secondary" style="width: 25%">Bullet:</th>
                                                    @if (is_null($data->bullet) || $data->bullet == '[]')
                                                    <td class="table-active text-left">
                                                        <div class="input_fields_wrap">
                                                            <button class="add_field_button btn btn-outline-primary mb-1"><i class="fas fa-plus-circle"></i> More
                                                                Fields</button>
                                                            <div class="input-group">
                                                                <input type="text" name="bullet[]" placeholder="Bullet" class="form-control">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="bulletCol">
                                                        @foreach (json_decode($data->bullet, true) as $key => $aj)
                                                        <span class="bullet"></span>{{ $aj }}<br>
                                                        @endforeach
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="bulletButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                                        </p>
                                                    </td>
                                                    <td class="table-active text-left" id="bulletColInput" hidden>
                                                        <div class="input_fields_wrap">
                                                            <button class="add_field_button btn btn-outline-primary mb-1"><i class="fas fa-plus-circle"></i> More
                                                                Fields</button>
                                                            <button class="btn btn-outline-danger batal_edit_bullet text-danger align-self-center mb-1" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            @foreach (Illuminate\Support\Arr::whereNotNull(json_decode($data->bullet, true)) as $k => $bullet)
                                                            <div>
                                                                <div class="input-group">
                                                                    <input type="text" name="bullet[]" value="{{ $bullet }}" placeholder="Bullet" class="form-control">
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Editor: <span class="text-danger">*</span></th>
                                                    @if (is_null($data->editor) || $data->editor == '[]')
                                                    <td class="table-active text-left">
                                                        <select name="editor[]" class="form-control select-editor-editing" multiple="multiple" required>
                                                            <option label="Pilih editor"></option>
                                                            @foreach ($editor as $i => $edList)
                                                            <option value="{{ $edList->id }}">
                                                                {{ $edList->nama }}&nbsp;&nbsp;
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="editorCol">
                                                        @foreach ($nama_editor as $key => $aj)
                                                        <span class="bullet"></span>{{ $aj }}<br>
                                                        @endforeach
                                                        @if (is_null($data->tgl_selesai_edit))
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="editorButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                                        </p>
                                                        @endif

                                                    </td>
                                                    <td class="table-active text-left" id="editorColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="editor[]" class="form-control select-editor-editing" multiple="multiple">
                                                                <option label="Pilih editor"></option>
                                                                @foreach ($editor as $i => $edList)
                                                                {{ $sel = '' }}
                                                                @if (in_array($edList->nama, $nama_editor))
                                                                {{ $sel = ' selected="selected" ' }}
                                                                @endif
                                                                <option value="{{ $edList->id }}" {{ $sel }}>
                                                                    {{ $edList->nama }}&nbsp;&nbsp;
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_editor text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th class="table-secondary" style="width: 25%">Copy Editor:
                                                        @if (!is_null($data->tgl_selesai_edit))
                                                        <span class="text-danger">*</span>
                                                        @endif
                                                    </th>
                                                    @if (is_null($data->copy_editor) || $data->copy_editor == '[]')
                                                    <td class="table-active text-left">
                                                        {{ $dis = '' }}
                                                        @if (is_null($data->tgl_selesai_edit))
                                                        <span class="text-danger"><i class="fas fa-exclamation-circle"></i>
                                                            Belum bisa melanjutkan proses copy editing,
                                                            proses editing belum selesai.</span>
                                                        <span hidden>{{ $dis = 'disabled="disabled"' }}</span>
                                                        @endif
                                                        <select name="copy_editor[]" class="form-control select-copyeditor" multiple="multiple" {{ $dis }} required>
                                                            <option label="Pilih copy editor"></option>
                                                            @if (!is_null($copy_editor))
                                                            @foreach ($copy_editor as $cpeList)
                                                            <option value="{{ $cpeList->id }}">
                                                                {{ $cpeList->nama }}&nbsp;&nbsp;
                                                            </option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    @else
                                                    <td class="table-active text-right" id="copyEditorCol">
                                                        @foreach ($nama_copyeditor as $key => $aj)
                                                        <span class="bullet"></span>{{ $aj }}<br>
                                                        @endforeach
                                                        @if (is_null($data->tgl_selesai_copyeditor))
                                                        <p class="text-small">
                                                            <a href="javascript:void(0)" id="copyEditorButton"><i class="fa fa-pen"></i>&nbsp;Add / Edit</a>
                                                        </p>
                                                        @endif
                                                    </td>
                                                    <td class="table-active text-left" id="copyEditorColInput" hidden>
                                                        <div class="input-group">
                                                            <select name="copy_editor[]" class="form-control select-copyeditor" multiple="multiple">
                                                                <option label="Pilih copy editor"></option>
                                                                @foreach ($copy_editor as $i => $cpeList)
                                                                {{ $sl = '' }}
                                                                @if (in_array($cpeList->nama, $nama_copyeditor))
                                                                {{ $sl = ' selected="selected" ' }}
                                                                @endif
                                                                <option value="{{ $cpeList->id }}" {{ $sl }}>
                                                                    {{ $cpeList->nama }}&nbsp;&nbsp;
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-outline-danger batal_edit_editor text-danger align-self-center" data-toggle="tooltip" title="Batal Edit"><i class="fas fa-times"></i></button>
                                                            </div>
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
                            <input type="hidden" name="id" value="{{ $data->id }}">
                        </div>
                        <div class="card-footer text-right">
                            <div class="custom-control custom-switch">
                                @if ($data->proses == '1')
                                <?php $label = 'Stop'; ?>
                                @else
                                <?php $label = 'Mulai'; ?>
                                @endif
                                <input type="checkbox" name="proses" class="custom-control-input" id="prosesKerja" data-id="{{ $data->id }}" {{ $data->proses == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label mr-3 text-dark" for="prosesKerja">
                                    {{ is_null($data->tgl_selesai_edit) ? $label . ' proses editor' : $label . ' proses copy editor' }}
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
                                                <th class="table-secondary" style="width: 25%">Editor: <span class="text-danger">*</span></th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->editor))
                                                    @foreach ($nama_editor as $ne)
                                                    <span class="bullet"></span>{{ $ne }}<br>
                                                    @endforeach
                                                    @else
                                                    <span class="text-danger text-small">Belum diinput</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table-secondary" style="width: 25%">Copy Editor:
                                                </th>
                                                <td class="table-active text-right">
                                                    @if (!is_null($data->copy_editor))
                                                    @foreach ($nama_copyeditor as $nc)
                                                        <span class="bullet"></span>{{ $nc }}<br>
                                                        @endforeach
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
<script src="{{ url('vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script src="{{ url('vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
@endsection


@section('jsNeeded')
<script src="{{ url('js/edit_editing.js') }}"></script>
<script>
    function resetFrom(form) {
        form.trigger("reset");
        $('[name="proses"]').val("").trigger("change");
    }
    $(function() {
        $('#prosesKerja').click(function() {
            var id = $(this).data('id');
            var editor = $('.select-editor-editing').val();
            var copy_editor = $('.select-copyeditor').val();
            if (this.checked) {
                value = '1';
            } else {
                value = '0';
            }
            let val = value;
            $.ajax({
                url: "{{ route('editing.proses') }}",
                type: 'POST',
                data: {
                    id: id,
                    proses: val,
                    editor: editor,
                    copy_editor: copy_editor
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#overlay").fadeIn(300);
                },
                success: function(result) {
                    // console.log(result);
                    if (result.status == 'error') {
                        notifToast(result.status, result.message);
                        resetFrom($('#fup_editingProses'));
                    } else {
                        notifToast(result.status, result.message);
                        location.reload();
                    }
                }
            }).done(function() {
                setTimeout(function() {
                    $("#overlay").fadeOut(300);
                }, 500);
            });

        });
    });
</script>
@endsection
