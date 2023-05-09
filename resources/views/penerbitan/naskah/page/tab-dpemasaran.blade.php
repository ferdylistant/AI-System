@php
    $arrDSTB = ['DS', 'TB'];
@endphp
@if($form == 'view')
<div class="row">
    <div class="col-12 mb-3">
            <h5 class="section-title">Penilaian: <b>{{$pn_pemasaran->nama_direktur}}</b></h5>
        </div>
    <div class="form-group col-12 mb-4">
        <label>Prospek Pasar: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn3_prospek_pasar" placeholder="Prospek Pasar" readonly>{{$pn_pemasaran->prospek_pasar}}</textarea>
        <div id="err_pn3_prospek_pasar"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Potensi Dana: <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="pn3_potensi_dana" value="{{$pn_pemasaran->potensi_dana}}" placeholder="Potensi Dana" readonly>
        <div id="err_pn3_potensi_dana"></div>
    </div>
    <div class="form-group col-12">
        <label class="d-block">DS / TB: <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="cb_{{$pn_pemasaran->ds_tb}}" value="{{$pn_pemasaran->ds_tb}}" checked='' disabled>
                <label class="form-check-label" for="cb_{{$pn_pemasaran->ds_tb}}">{{$pn_pemasaran->ds_tb}}</label>
            </div>
    </div>
    <div class="form-group col-12">
        <label class="d-block">Pilar: <span class="text-danger">*</span></label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" checked='' disabled>
            <label class="form-check-label" >{{$pn_pemasaran->pilar}}</label>
        </div>
    </div>
</div>


@elseif($form == 'add')
<form id="fpn6">
<div class="row">
    <input type="hidden" name="pn6_naskah_id" value="{{$naskah->id}}">
    <input type="hidden" name="pn6_form" value="Add">
    <div class="form-group col-12">
        <label>Tanggapan Penilaian Prospek Pasar: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn6_prospek_pasar" placeholder="Uraian" required></textarea>
        <div id="err_pn6_prospek_pasar"></div>
    </div>
    <div class="form-group col-12">
        <label>Tanggapan Penilaian Potensi Dana: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn6_potensi_dana" placeholder="Uraian" required></textarea>
        <div id="err_pn6_potensi_dana"></div>
    </div>
    <div class="form-group col-12">
        <label>Tanggapan Penilaian DS / TB: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn6_dstb" placeholder="Uraian" required></textarea>
        <div id="err_pn6_dstb"></div>
    </div>
    <div class="form-group col-12">
        <label>Tanggapan Penilaian Pilar: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn6_pilar" placeholder="Uraian" required></textarea>
        <div id="err_pn6_pilar"></div>
    </div>
</div>
<div class="card-footer text-right p-0">
    <button type="submit" class="btn btn-success">Simpan</button>
</div>
</form>


@elseif($form == 'edit')
<form id="fpn6">
<div class="row">
    <input type="hidden" name="pn6_naskah_id" value="{{$naskah->id}}">
    <input type="hidden" name="pn6_form" value="Edit">
    <input type="hidden" name="pn6_id" value="{{$pn_pemasaran->id}}">
    <div class="form-group col-12 mb-4">
        <label>Tanggapan Penilaian Prospek Pasar: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn6_prospek_pasar" placeholder="Uraian" disabled>{{$pn_pemasaran->prospek_pasar}}</textarea>
        <div id="err_pn6_prospek_pasar"></div>
    </div>
    <div class="form-group col-12">
        <label>Tanggapan Penilaian Potensi Dana: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn6_potensi_dana" placeholder="Uraian" disabled>{{$pn_pemasaran->potensi_dana}}</textarea>
        <div id="err_pn6_potensi_dana"></div>
    </div>
    <div class="form-group col-12">
        <label>Tanggapan Penilaian DS / TB: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn6_dstb" placeholder="Uraian" disabled>{{$pn_pemasaran->ds_tb}}</textarea>
        <div id="err_pn6_dstb"></div>
    </div>
    <div class="form-group col-12">
        <label>Tanggapan Penilaian Pilar: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn6_pilar" placeholder="Uraian" disabled>{{$pn_pemasaran->pilar}}</textarea>
        <div id="err_pn6_pilar"></div>
    </div>
</div>
<div class="card-footer text-right p-0">
    <button type="submit" class="btn btn-warning" disabled>Simpan</button>
</div>
</form>


@endif
