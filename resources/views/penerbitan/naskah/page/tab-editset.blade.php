@php
    $arrPilihan_ = ["Baik", "Cukup", "Kurang"];
@endphp

@if($form == 'view')
<div class="row">
    <h3 class="col-12">#Penilaian Editor</h3>
    <div class="form-group col-12 mb-4">
        <label>Penilaian Umum: </label>
        <textarea class="form-control" name="pn2_penilaian_editor_umum" placeholder="" readonly>{{$pn_editset->penilaian_editor_umum}}</textarea>
        <div id="err_pn2_penilaian_editor_umum"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Bahasa: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn2_penilaian_bahasa" disabled>
            <option label="Pilih"></option>
            @foreach($arrPilihan_ as $ap)
            <option value="{{$ap}}" {{$pn_editset->penilaian_bahasa==$ap?'Selected':''}}>{{$ap}}</option>
            @endforeach
        </select>
        <div id="err_pn2_penilaian_bahasa"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Sistematika: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn2_penilaian_sistematika" disabled>
            <option label="Pilih"></option>
            @foreach($arrPilihan_ as $ap)
            <option value="{{$ap}}" {{$pn_editset->penilaian_sistematika==$ap?'Selected':''}}>{{$ap}}</option>
            @endforeach
        </select>
        <div id="err_pn2_penilaian_sistematika"></div>
    </div>
    <div class="form-group col-12 col-md-4 mb-4">
        <label>Konsistensi: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn2_penilaian_konsistensi" disabled>
            <option label="Pilih"></option>
            @foreach($arrPilihan_ as $ap)
            <option value="{{$ap}}" {{$pn_editset->penilaian_konsistensi==$ap?'Selected':''}}>{{$ap}}</option>
            @endforeach
        </select>
        <div id="err_pn2_penilaian_konsistensi"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Catatan Bahasa: </label>
        <textarea class="form-control" name="pn2_catatan_bahasa" placeholder="" readonly>{{$pn_editset->catatan_bahasa}}</textarea>
        <div id="err_pn2_catatan_bahasa"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Catatan Sistematika: </label>
        <textarea class="form-control" name="pn2_catatan_sistematika" placeholder="" readonly>{{$pn_editset->catatan_sistematika}}</textarea>
        <div id="err_pn2_catatan_sistematika"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Catatan Konsistensi: </label>
        <textarea class="form-control" name="pn2_catatan_konsistensi" placeholder="" readonly>{{$pn_editset->catatan_konsistensi}}</textarea>
        <div id="err_pn2_catatan_konsistensi"></div>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Perlu Proses Edit: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn2_perlu_proses_edit" disabled>
            <option label="Pilih"></option>
            <option value="Perlu" {{$pn_editset->perlu_proses_edit=='Perlu'?'Selected':''}}>Perlu</option>
            <option value="Tidak" {{$pn_editset->perlu_proses_edit=='Tidak'?'Selected':''}}>Tidak</option>
        </select>
        <div id="err_pn2_perlu_proses_edit"></div>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Proses Editor: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn2_proses_editor" disabled>
            <option label="Pilih"></option>
            <option value="Ringan" {{$pn_editset->proses_editor=='Ringan'?'Selected':''}}>Ringan</option>
            <option value="Sedang" {{$pn_editset->proses_editor=='Sedang'?'Selected':''}}>Sedang</option>
            <option value="Berat" {{$pn_editset->proses_editor=='Berat'?'Selected':''}}>Berat</option>
        </select>
        <div id="err_pn2_proses_editor"></div>
    </div>

    <h3 class="mt-3 col-12">#Penilaian Setter</h3>

    <div class="form-group col-12 col-md-6 mb-4">
        <label>Perlu Proses Setting: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn2_perlu_proses_setting" disabled>
            <option label="Pilih"></option>
            <option value="Perlu" {{$pn_editset->perlu_proses_setting=='Perlu'?'Selected':''}}>Perlu</option>
            <option value="Tidak" {{$pn_editset->perlu_proses_setting=='Tidak'?'Selected':''}}>Tidak</option>
        </select>
        <div id="err_pn2_perlu_proses_setting"></div>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Proses Setting: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn2_proses_setting" disabled>
            <option label="Pilih"></option>
            <option value="Ringan" {{$pn_editset->proses_setting=='Ringan'?'Selected':''}}>Ringan</option>
            <option value="Sedang" {{$pn_editset->proses_setting=='Sedang'?'Selected':''}}>Sedang</option>
            <option value="Berat" {{$pn_editset->proses_setting=='Berat'?'Selected':''}}>Berat</option>
        </select>
        <div id="err_pn2_proses_setting"></div>
    </div>
</div>


@elseif($form == 'add-edit')
@if($userActive == 'Setter')
    @if($pnEdit)
    <form id="fpn2">
    <div class="row">
        <input type="hidden" name="pn2_naskah_id" value="{{$naskah->id}}">
        <input type="hidden" name="pn2_id" value="{{$pn_editset->id}}">
        <input type="hidden" name="pn2_pic" value="{{$userActive}}"> <!-- Editor/Setter -->
        <input type="hidden" name="pn2_form" value="Edit">
        <h3 class="col-12">#Penilaian Editor</h3>
        <div class="form-group col-12 mb-4">
            <label>Penilaian Umum: </label>
            <textarea class="form-control" name="pn2_penilaian_editor_umum" placeholder="" {{$userActive=='Editor'?'':'readonly'}}>{{$pn_editset->penilaian_editor_umum}}</textarea>
            <div id="err_pn2_penilaian_editor_umum"></div>
        </div>
        <div class="form-group col-12 col-md-4 mb-4">
            <label>Bahasa: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_penilaian_bahasa" {{$userActive=='Editor'?'':'disabled'}}>
                <option label="Pilih"></option>
                @foreach($arrPilihan_ as $ap)
                <option value="{{$ap}}" {{$pn_editset->penilaian_bahasa==$ap?'Selected':''}}>{{$ap}}</option>
                @endforeach
            </select>
            <div id="err_pn2_penilaian_bahasa"></div>
        </div>
        <div class="form-group col-12 col-md-4 mb-4">
            <label>Sistematika: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_penilaian_sistematika" {{$userActive=='Editor'?'':'disabled'}}>
                <option label="Pilih"></option>
                @foreach($arrPilihan_ as $ap)
                <option value="{{$ap}}" {{$pn_editset->penilaian_sistematika==$ap?'Selected':''}}>{{$ap}}</option>
                @endforeach
            </select>
            <div id="err_pn2_penilaian_sistematika"></div>
        </div>
        <div class="form-group col-12 col-md-4 mb-4">
            <label>Konsistensi: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_penilaian_konsistensi" {{$userActive=='Editor'?'':'disabled'}}>
                <option label="Pilih"></option>
                @foreach($arrPilihan_ as $ap)
                <option value="{{$ap}}" {{$pn_editset->penilaian_konsistensi==$ap?'Selected':''}}>{{$ap}}</option>
                @endforeach
            </select>
            <div id="err_pn2_penilaian_konsistensi"></div>
        </div>
        <div class="form-group col-12 mb-4">
            <label>Catatan Bahasa: </label>
            <textarea class="form-control" name="pn2_catatan_bahasa" placeholder="" {{$userActive=='Editor'?'':'readonly'}}>{{$pn_editset->catatan_bahasa}}</textarea>
            <div id="err_pn2_catatan_bahasa"></div>
        </div>
        <div class="form-group col-12 mb-4">
            <label>Catatan Sistematika: </label>
            <textarea class="form-control" name="pn2_catatan_sistematika" placeholder="" {{$userActive=='Editor'?'':'readonly'}}>{{$pn_editset->catatan_sistematika}}</textarea>
            <div id="err_pn2_catatan_sistematika"></div>
        </div>
        <div class="form-group col-12 mb-4">
            <label>Catatan Konsistensi: </label>
            <textarea class="form-control" name="pn2_catatan_konsistensi" placeholder="" {{$userActive=='Editor'?'':'readonly'}}>{{$pn_editset->catatan_konsistensi}}</textarea>
            <div id="err_pn2_catatan_konsistensi"></div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Perlu Proses Edit: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_perlu_proses_edit" {{$userActive=='Editor'?'':'disabled'}}>
                <option label="Pilih"></option>
                <option value="Perlu" {{$pn_editset->perlu_proses_edit=='Perlu'?'Selected':''}}>Perlu</option>
                <option value="Tidak" {{$pn_editset->perlu_proses_edit=='Tidak'?'Selected':''}}>Tidak</option>
            </select>
            <div id="err_pn2_perlu_proses_edit"></div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Proses Editor: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_proses_editor" {{$userActive=='Editor'?'':'disabled'}}>
                <option label="Pilih"></option>
                <option value="Ringan" {{$pn_editset->proses_editor=='Ringan'?'Selected':''}}>Ringan</option>
                <option value="Sedang" {{$pn_editset->proses_editor=='Sedang'?'Selected':''}}>Sedang</option>
                <option value="Berat" {{$pn_editset->proses_editor=='Berat'?'Selected':''}}>Berat</option>
            </select>
            <div id="err_pn2_proses_editor"></div>
        </div>

        <h3 class="mt-3 col-12">#Penilaian Setter</h3>

        <div class="form-group col-12 col-md-6 mb-4">
            <label>Perlu Proses Setting: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_perlu_proses_setting" {{$userActive=='Setter'?'':'disabled'}}>
                <option label="Pilih"></option>
                <option value="Perlu" {{$pn_editset->perlu_proses_setting=='Perlu'?'Selected':''}}>Perlu</option>
                <option value="Tidak" {{$pn_editset->perlu_proses_setting=='Tidak'?'Selected':''}}>Tidak</option>
            </select>
            <div id="err_pn2_perlu_proses_setting"></div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Proses Setting: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_proses_setting" {{$userActive=='Setter'?'':'disabled'}}>
                <option label="Pilih"></option>
                <option value="Ringan" {{$pn_editset->proses_setting=='Ringan'?'Selected':''}}>Ringan</option>
                <option value="Sedang" {{$pn_editset->proses_setting=='Sedang'?'Selected':''}}>Sedang</option>
                <option value="Berat" {{$pn_editset->proses_setting=='Berat'?'Selected':''}}>Berat</option>
            </select>
            <div id="err_pn2_proses_setting"></div>
        </div>
    </div>
    <div class="card-footer text-right p-0">
        <button type="submit" class="btn btn-warning">Simpan</button>
    </div>
    </form>
    
    @else
    <h5 class="ml-2">Editor belum membuat penilian.</h5>
    @endif

@else
    @if($pnEdit) <!-- FORM Editor Editor -->
    <form id="fpn2">
    <div class="row">
        <input type="hidden" name="pn2_naskah_id" value="{{$naskah->id}}">
        <input type="hidden" name="pn2_id" value="{{$pn_editset->id}}">
        <input type="hidden" name="pn2_pic" value="{{$userActive}}"> <!-- Editor/Setter -->
        <input type="hidden" name="pn2_form" value="Edit">
        <h3 class="col-12">#Penilaian Editor</h3>
        <div class="form-group col-12 mb-4">
            <label>Penilaian Umum: </label>
            <textarea class="form-control" name="pn2_penilaian_editor_umum" placeholder="" {{$userActive=='Editor'?'':'readonly'}}>{{$pn_editset->penilaian_editor_umum}}</textarea>
            <div id="err_pn2_penilaian_editor_umum"></div>
        </div>
        <div class="form-group col-12 col-md-4 mb-4">
            <label>Bahasa: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_penilaian_bahasa" {{$userActive=='Editor'?'':'disabled'}}>
                <option label="Pilih"></option>
                @foreach($arrPilihan_ as $ap)
                <option value="{{$ap}}" {{$pn_editset->penilaian_bahasa==$ap?'Selected':''}}>{{$ap}}</option>
                @endforeach
            </select>
            <div id="err_pn2_penilaian_bahasa"></div>
        </div>
        <div class="form-group col-12 col-md-4 mb-4">
            <label>Sistematika: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_penilaian_sistematika" {{$userActive=='Editor'?'':'readonly'}}>
                <option label="Pilih"></option>
                @foreach($arrPilihan_ as $ap)
                <option value="{{$ap}}" {{$pn_editset->penilaian_sistematika==$ap?'Selected':''}}>{{$ap}}</option>
                @endforeach
            </select>
            <div id="err_pn2_penilaian_sistematika"></div>
        </div>
        <div class="form-group col-12 col-md-4 mb-4">
            <label>Konsistensi: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_penilaian_konsistensi" {{$userActive=='Editor'?'':'readonly'}}>
                <option label="Pilih"></option>
                @foreach($arrPilihan_ as $ap)
                <option value="{{$ap}}" {{$pn_editset->penilaian_konsistensi==$ap?'Selected':''}}>{{$ap}}</option>
                @endforeach
            </select>
            <div id="err_pn2_penilaian_konsistensi"></div>
        </div>
        <div class="form-group col-12 mb-4">
            <label>Catatan Bahasa: </label>
            <textarea class="form-control" name="pn2_catatan_bahasa" placeholder="" {{$userActive=='Editor'?'':'readonly'}}>{{$pn_editset->catatan_bahasa}}</textarea>
            <div id="err_pn2_catatan_bahasa"></div>
        </div>
        <div class="form-group col-12 mb-4">
            <label>Catatan Sistematika: </label>
            <textarea class="form-control" name="pn2_catatan_sistematika" placeholder="" {{$userActive=='Editor'?'':'readonly'}}>{{$pn_editset->catatan_sistematika}}</textarea>
            <div id="err_pn2_catatan_sistematika"></div>
        </div>
        <div class="form-group col-12 mb-4">
            <label>Catatan Konsistensi: </label>
            <textarea class="form-control" name="pn2_catatan_konsistensi" placeholder="" {{$userActive=='Editor'?'':'readonly'}}>{{$pn_editset->catatan_konsistensi}}</textarea>
            <div id="err_pn2_catatan_konsistensi"></div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Perlu Proses Edit: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_perlu_proses_edit" {{$userActive=='Editor'?'':'readonly'}}>
                <option label="Pilih"></option>
                <option value="Perlu" {{$pn_editset->perlu_proses_edit=='Perlu'?'Selected':''}}>Perlu</option>
                <option value="Tidak" {{$pn_editset->perlu_proses_edit=='Tidak'?'Selected':''}}>Tidak</option>
            </select>
            <div id="err_pn2_perlu_proses_edit"></div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Proses Editor: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_proses_editor" {{$userActive=='Editor'?'':'readonly'}}>
                <option label="Pilih"></option>
                <option value="Ringan" {{$pn_editset->proses_editor=='Ringan'?'Selected':''}}>Ringan</option>
                <option value="Sedang" {{$pn_editset->proses_editor=='Sedang'?'Selected':''}}>Sedang</option>
                <option value="Berat" {{$pn_editset->proses_editor=='Berat'?'Selected':''}}>Berat</option>
            </select>
            <div id="err_pn2_proses_editor"></div>
        </div>

        <h3 class="mt-3 col-12">#Penilaian Setter</h3>

        <div class="form-group col-12 col-md-6 mb-4">
            <label>Perlu Proses Setting: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_perlu_proses_setting" {{$userActive=='Setter'?'':'disabled'}}>
                <option label="Pilih"></option>
                <option value="Perlu" {{$pn_editset->perlu_proses_setting=='Perlu'?'Selected':''}}>Perlu</option>
                <option value="Tidak" {{$pn_editset->perlu_proses_setting=='Perlu'?'Selected':''}}>Tidak</option>
            </select>
            <div id="err_pn2_perlu_proses_setting"></div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Proses Setting: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_proses_setting" {{$userActive=='Setter'?'':'disabled'}}>
                <option label="Pilih"></option>
                <option value="Ringan" {{$pn_editset->proses_setting=='Ringan'?'Selected':''}}>Ringan</option>
                <option value="Sedang" {{$pn_editset->proses_setting=='Sedang'?'Selected':''}}>Sedang</option>
                <option value="Berat" {{$pn_editset->proses_setting=='Berat'?'Selected':''}}>Berat</option>
            </select>
            <div id="err_pn2_proses_setting"></div>
        </div>
    </div>
    <div class="card-footer text-right p-0">
        <button type="submit" class="btn btn-warning">Simpan</button>
    </div>
    </form>

    @else <!-- FORM ADD Editor -->
    <form id="fpn2">
    <div class="row">
        <input type="hidden" name="pn2_naskah_id" value="{{$naskah->id}}">
        <input type="hidden" name="pn2_pic" value="{{$userActive}}"> <!-- Editor/Setter -->
        <input type="hidden" name="pn2_form" value="Add">
        <h3 class="col-12">#Penilaian Editor</h3>
        <div class="form-group col-12 mb-4">
            <label>Penilaian Umum: </label>
            <textarea class="form-control" name="pn2_penilaian_editor_umum" placeholder="" {{$userActive=='Editor'?'':'readonly'}}></textarea>
            <div id="err_pn2_penilaian_editor_umum"></div>
        </div>
        <div class="form-group col-12 col-md-4 mb-4">
            <label>Bahasa: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_penilaian_bahasa" {{$userActive=='Editor'?'':'disabled'}}>
                <option label="Pilih"></option>
                <option value="Baik">Baik</option>
                <option value="Cukup">Cukup</option>
                <option value="Kurang">Kurang</option>
            </select>
            <div id="err_pn2_penilaian_bahasa"></div>
        </div>
        <div class="form-group col-12 col-md-4 mb-4">
            <label>Sistematika: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_penilaian_sistematika" {{$userActive=='Editor'?'':'readonly'}}>
                <option label="Pilih"></option>
                <option value="Baik">Baik</option>
                <option value="Cukup">Cukup</option>
                <option value="Kurang">Kurang</option>
            </select>
            <div id="err_pn2_penilaian_sistematika"></div>
        </div>
        <div class="form-group col-12 col-md-4 mb-4">
            <label>Konsistensi: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_penilaian_konsistensi" {{$userActive=='Editor'?'':'readonly'}}>
                <option label="Pilih"></option>
                <option value="Baik">Baik</option>
                <option value="Cukup">Cukup</option>
                <option value="Kurang">Kurang</option>
            </select>
            <div id="err_pn2_penilaian_konsistensi"></div>
        </div>
        <div class="form-group col-12 mb-4">
            <label>Catatan Bahasa: </label>
            <textarea class="form-control" name="pn2_catatan_bahasa" placeholder="" {{$userActive=='Editor'?'':'readonly'}}></textarea>
            <div id="err_pn2_catatan_bahasa"></div>
        </div>
        <div class="form-group col-12 mb-4">
            <label>Catatan Sistematika: </label>
            <textarea class="form-control" name="pn2_catatan_sistematika" placeholder="" {{$userActive=='Editor'?'':'readonly'}}></textarea>
            <div id="err_pn2_catatan_sistematika"></div>
        </div>
        <div class="form-group col-12 mb-4">
            <label>Catatan Konsistensi: </label>
            <textarea class="form-control" name="pn2_catatan_konsistensi" placeholder="" {{$userActive=='Editor'?'':'readonly'}}></textarea>
            <div id="err_pn2_catatan_konsistensi"></div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Perlu Proses Edit: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_perlu_proses_edit" {{$userActive=='Editor'?'':'readonly'}}>
                <option label="Pilih"></option>
                <option value="Perlu">Perlu</option>
                <option value="Tidak">Tidak</option>
            </select>
            <div id="err_pn2_perlu_proses_edit"></div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Proses Editor: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_proses_editor" {{$userActive=='Editor'?'':'readonly'}}>
                <option label="Pilih"></option>
                <option value="Ringan">Ringan</option>
                <option value="Sedang">Sedang</option>
                <option value="Berat">Berat</option>
            </select>
            <div id="err_pn2_proses_editor"></div>
        </div>

        <h3 class="mt-3 col-12">#Penilaian Setter</h3>

        <div class="form-group col-12 col-md-6 mb-4">
            <label>Perlu Proses Setting: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_perlu_proses_setting" {{$userActive=='Setter'?'':'disabled'}}>
                <option label="Pilih"></option>
                <option value="Perlu">Perlu</option>
                <option value="Tidak">Tidak</option>
            </select>
            <div id="err_pn2_perlu_proses_setting"></div>
        </div>
        <div class="form-group col-12 col-md-6 mb-4">
            <label>Proses Setting: <span class="text-danger">*</span></label>
            <select class="form-control select2" name="pn2_proses_setting" {{$userActive=='Setter'?'':'disabled'}}>
                <option label="Pilih"></option>
                <option value="Ringan">Ringan</option>
                <option value="Sedang">Sedang</option>
                <option value="Berat">Berat</option>
            </select>
            <div id="err_pn2_proses_setting"></div>
        </div>
    </div>
    <div class="card-footer text-right p-0">
        <button type="submit" class="btn btn-success">Simpan</button>
    </div>
    </form>
    @endif
@endif

@endif