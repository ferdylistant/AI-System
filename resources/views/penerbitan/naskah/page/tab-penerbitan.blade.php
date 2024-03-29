@if($form == 'view')
<div class="row">
    <div class="col-12 mb-3">
        <h5 class="section-title">Penilaian oleh: <a class="text-primary text-decoration-none" href="{{url('manajemen-web/user/' . $pn_penerbitan->created_by)}}"><b>{{\Str::ucfirst($pn_penerbitan->nama)}}</b></a></h5>
        @if ($history)
        <a class="btn btn-sm btn-dark float-right btn-history-penilaian" href="javascript:void(0)" data-toggle="modal" data-target="#modalHistoryPenilaian"
        data-pic="MPN" data-naskahid="{!!$naskah->id!!}"><i class="fas fa-folder-open"></i> Riwayat Penilaian</a>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Saran: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn4_saran" disabled>
            <option label="Pilih"></option>
            <option value="Diterima" {{$pn_penerbitan->saran=='Diterima'?'selected':''}}>Diterima</option>
            <option value="Ditolak" {{$pn_penerbitan->saran=='Ditolak'?'selected':''}}>Ditolak</option>
            <option value="Revisi" {{$pn_penerbitan->saran=='Revisi'?'selected':''}}>Revisi</option>
            <option value="eBook" {{$pn_penerbitan->saran=='eBook'?'selected':''}}>eBook</option>
        </select>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Potensi: <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="pn4_potensi" value="{{$pn_penerbitan->potensi}}" readonly>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Penilaian Umum: </label>
        <textarea class="form-control" name="pn4_penilaian_umum" readonly>{{$pn_penerbitan->penilaian_umum}}</textarea>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Catatan: </label>
        <textarea class="form-control" name="pn4_catatan" readonly>{{$pn_penerbitan->catatan}}</textarea>
    </div>
    <div class="form-group col-12 mb-4">
        <div class="custom-control custom-switch">
            <input type="checkbox" name="pn4_urgent" class="custom-control-input" id="urgentNaskah" readonly {{$naskah->urgent == '1' ? 'checked':''}}>
            <label class="custom-control-label mr-3 text-dark" for="urgentNaskah">
                Naskah Urgent
            </label>
        </div>
    </div>
</div>


@elseif($form == 'add')
<form id="fpn4">
<div class="row">
    @if ($history)
    <div class="col-12 mb-3">
        <a class="btn btn-sm btn-dark float-right btn-history-penilaian" href="javascript:void(0)" data-toggle="modal" data-target="#modalHistoryPenilaian"
        data-pic="MPN" data-naskahid="{!!$naskah->id!!}"><i class="fas fa-folder-open"></i> Riwayat Penilaian</a>
    </div>
    @endif
    <input type="hidden" name="pn4_naskah_id" value="{{$naskah->id}}">
    <input type="hidden" name="pn4_form" value="Add">
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Saran: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn4_saran" required>
            <option label="Pilih"></option>
            <option value="Diterima">Diterima</option>
            <option value="Ditolak">Ditolak</option>
            <option value="Revisi">Revisi</option>
            <option value="eBook">eBook</option>
        </select>
        <div id="err_pn4_saran"></div>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Potensi: </label>
        <input type="text" class="form-control" name="pn4_potensi" placeholder="Potensi Pasar">
        <div id="err_pn4_potensi"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Penilaian Umum: </label>
        <textarea class="form-control" name="pn4_penilaian_umum" placeholder="Penilaian Umum"></textarea>
        <div id="err_pn4_penilaian_umum"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Catatan: </label>
        <textarea class="form-control" name="pn4_catatan" placeholder="Catatan Penerbitan"></textarea>
        <div id="err_pn4_catatan"></div>
    </div>
</div>
<div class="card-footer text-right p-0">
    <div class="custom-control custom-switch">
        <input type="checkbox" name="pn4_urgent" class="custom-control-input" id="urgentNaskah" {{$naskah->urgent == '1' ? 'checked':''}}>
        <label class="custom-control-label mr-3 text-dark" for="urgentNaskah">
            Naskah Urgent
        </label>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</div>
</form>


@elseif($form == 'edit')
<form id="fpn4">
<div class="row">
    @if ($history)
    <div class="col-12 mb-3">
        <a class="btn btn-sm btn-dark float-right btn-history-penilaian" href="javascript:void(0)" data-toggle="modal" data-target="#modalHistoryPenilaian"
        data-pic="MPN" data-naskahid="{!!$naskah->id!!}"><i class="fas fa-folder-open"></i> Riwayat Penilaian</a>
    </div>
    @endif
    <input type="hidden" name="pn4_naskah_id" value="{{$naskah->id}}">
    <input type="hidden" name="pn4_form" value="Edit">
    <input type="hidden" name="pn4_id" value="{{$pn_penerbitan->id}}">
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Saran: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn4_saran">
            <option label="Pilih"></option>
            <option value="Diterima" {{$pn_penerbitan->saran=='Diterima'?'selected':''}}>Diterima</option>
            <option value="Ditolak" {{$pn_penerbitan->saran=='Ditolak'?'selected':''}}>Ditolak</option>
            <option value="Revisi" {{$pn_penerbitan->saran=='Revisi'?'selected':''}}>Revisi</option>
            <option value="eBook" {{$pn_penerbitan->saran=='eBook'?'selected':''}}>eBook</option>
        </select>
        <div id="err_pn4_saran"></div>
    </div>
    <div class="form-group col-12 col-md-6 mb-4">
        <label>Potensi: </label>
        <input type="text" class="form-control" name="pn4_potensi" value="{{$pn_penerbitan->potensi}}" placeholder="Potensi Pasar">
        <div id="err_pn4_potensi"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Penilaian Umum: </label>
        <textarea class="form-control" name="pn4_penilaian_umum" placeholder="Penilaian Umum">{{$pn_penerbitan->penilaian_umum}}</textarea>
        <div id="err_pn4_penilaian_umum"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Catatan: </label>
        <textarea class="form-control" name="pn4_catatan" placeholder="Catatan Penerbitan">{{$pn_penerbitan->catatan}}</textarea>
        <div id="err_pn4_catatan"></div>
    </div>
</div>
<div class="card-footer text-right p-0">
    <div class="custom-control custom-switch">
        <input type="checkbox" name="pn4_urgent" class="custom-control-input" id="urgentNaskah" {{$naskah->urgent == '1' ? 'checked':''}}>
        <label class="custom-control-label mr-3 text-dark" for="urgentNaskah">
            Naskah Urgent
        </label>
    </div>
    <button type="submit" class="btn btn-warning">Update</button>
</div>
</form>


@endif
