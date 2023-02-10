@php
    $arrDSTB = ['DS', 'TB'];
@endphp
@if($form == 'view')
    @foreach($pn_pemasaran as $p)
    <div class="row">
        <div class="col-12">
            <h5>#Penilaian: {{$p->nama_manager}}</h5>
        </div>
        <div class="form-group col-12">
            <label>Prospek Pasar: <span class="text-danger">*</span></label>
            <textarea class="form-control" placeholder="Prospek Pasar" readonly>{{$p->prospek_pasar}}</textarea>
        </div>
        <div class="form-group col-12">
            <label>Potensi Dana: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" value="{{$p->potensi_dana}}" placeholder="Potensi Dana" readonly>
        </div>
        <div class="form-group col-12">
            <label class="d-block">DS / TB: <span class="text-danger">*</span></label>
            @foreach($arrDSTB as $v)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="cb_{{$v}}" value="{{$v}}" {{in_array($v, json_decode($p->ds_tb))?'checked':''}} disabled>
                <label class="form-check-label" for="cb_{{$v}}">{{$v}}</label>
            </div>
            @endforeach
        </div>
        <div class="form-group col-12">
            <label class="d-block">Pilar: <span class="text-danger">*</span></label>
            @foreach($pilar as $pl)

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" {{in_array($pl->options, json_decode($p->pilar))?'checked':''}} disabled>
                <label class="form-check-label" >{{$pl->options}}</label>
            </div>
            @endforeach
        </div>
    </div>
    <hr>
    @endforeach

@elseif($form == 'add')
<form id="fpn3">
<div class="row">
    <input type="hidden" name="pn3_naskah_id" value="{{$naskah->id}}">
    <input type="hidden" name="pn3_form" value="Add">
    <div class="form-group col-12 mb-4">
        <label>Prospek Pasar: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn3_prospek_pasar" placeholder="Prospek Pasar" required></textarea>
        <div id="err_pn3_prospek_pasar"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Potensi Dana: <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="pn3_potensi_dana" placeholder="Potensi Dana" required>
        <div id="err_pn3_potensi_dana"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label class="d-block">DS / TB: <span class="text-danger">*</span></label>
        <!-- <input type="text" class="form-control" name="pn3_dstb" placeholder="DS / TB" required> -->
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="cb_ds" name="pn3_dstb[]" value="DS">
            <label class="form-check-label" for="cb_ds">DS</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="cb_tb" name="pn3_dstb[]" value="TB">
            <label class="form-check-label" for="cb_tb">TB</label>
        </div>
        <div id="err_pn3_dstb"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label class="d-block">Pilar: <span class="text-danger">*</span></label>
        @foreach($pilar as $p)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="P_{{$p->options}}" name="pn3_pilar[]" value="{{$p->options}}">
            <label class="form-check-label" for="P_{{$p->options}}">{{$p->options}}</label>
        </div>
        @endforeach
        <div id="err_pn3_pilar"></div>
    </div>
</div>
<div class="card-footer text-right p-0">
    <button type="submit" class="btn btn-success">Simpan</button>
</div>
</form>


@elseif($form == 'edit')
<form id="fpn3">
<div class="row">
    <input type="hidden" name="pn3_naskah_id" value="{{$naskah->id}}">
    <input type="hidden" name="pn3_form" value="Edit">
    <input type="hidden" name="pn3_id" value="{{$pn_pemasaran->id}}">
    <div class="form-group col-12 mb-4">
        <label>Prospek Pasar: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="pn3_prospek_pasar" placeholder="Prospek Pasar" disabled>{{$pn_pemasaran->prospek_pasar}}</textarea>
        <div id="err_pn3_prospek_pasar"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Potensi Dana: <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="pn3_potensi_dana" value="{{$pn_pemasaran->potensi_dana}}" placeholder="Potensi Dana" disabled>
        <div id="err_pn3_potensi_dana"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label class="d-block">DS / TB: <span class="text-danger">*</span></label>
        @foreach($arrDSTB as $v)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="cb_{{$v}}" name="pn3_dstb[]" value="{{$v}}" {{in_array($v, json_decode($pn_pemasaran->ds_tb))?'checked':''}} disabled>
            <label class="form-check-label" for="cb_{{$v}}">{{$v}}</label>
        </div>
        @endforeach
        <div id="err_pn3_dstb"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label class="d-block">Pilar: <span class="text-danger">*</span></label>
        @foreach($pilar as $key => $p)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="P_{{$p->options}}" name="pn3_pilar[]" value="{{$p->options}}" {{in_array($p->options, json_decode($pn_pemasaran->pilar))?'checked':''}} disabled>
            <label class="form-check-label" for="P_{{$p->options}}">{{$p->options}}</label>
        </div>
        @endforeach
        <div id="err_pn3_pilar"></div>
    </div>
</div>
<div class="card-footer text-right p-0">
    <button type="submit" class="btn btn-warning" disabled>Simpan</button>
</div>
</form>


@endif
