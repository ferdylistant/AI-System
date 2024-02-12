@if($form == 'view')
<div class="row">
    <div class="col-12 mb-3">
        <h5 class="section-title">Penilaian oleh: <a class="text-primary text-decoration-none" href="{{url('manajemen-web/user/' . $pn_direksi->created_by)}}"><b>{{\Str::ucfirst($pn_direksi->nama_direksi)}}</b></a></h5>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Keputusan Direksi: <span class="text-danger">*</span></label>
        <input type="text" class="form-control" value="{{$pn_direksi->keputusan_final}}" placeholder="Judul Final" readonly>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Judul Final: </label>
        <input type="text" class="form-control" value="{{$pn_direksi->judul_final}}" placeholder="Judul Final" readonly>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Sub Judul Final: </label>
        <input type="text" class="form-control" value="{{$pn_direksi->sub_judul_final}}" placeholder="Sub Judul Final" readonly>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Catatan Direksi: </label>
        <textarea class="form-control" placeholder="Catatan Direksi" readonly>{{$pn_direksi->catatan}}</textarea>
    </div>
</div>


@elseif($form == 'add')
<form id="fpn5">
<div class="row">
    <input type="hidden" name="pn5_naskah_id" value="{{$naskah->id}}">
    <div class="form-group col-12 mb-4">
        <label>Keputusan Direksi: <span class="text-danger">*</span></label>
        <select class="form-control select2" name="pn5_keputusan" required>
            <option label="Pilih"></option>
            @foreach ($list_keputusan as $list)
            <option value="{!!$list!!}">{{$list}}</option>
            @endforeach
        </select>
        <div id="err_pn5_keputusan"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Judul Final: </label>
        <input type="text" class="form-control" name="pn5_judul_final" placeholder="Judul Final" required>
        <div id="err_pn5_judul_final"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Sub Judul Final: </label>
        <input type="text" class="form-control" name="pn5_sub_judul_final" placeholder="Sub Judul Final" required>
        <div id="err_pn5_sub_judul_final"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Catatan: </label>
        <textarea class="form-control" name="pn5_catatan" placeholder="Catatan Penerbitan" required></textarea>
        <div id="err_pn5_catatan"></div>
    </div>
</div>
<div class="card-footer text-right p-0">
    <button type="submit" class="btn btn-success">Simpan</button>
</div>
</form>
@endif
