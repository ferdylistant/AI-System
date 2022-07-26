@if($method=='add')
<div class="modal-header bg-success"></div>
<form id="f_tl">
<div class="modal-body row">
    <h5 class="modal-title col-12 mb-3">#Buat Timeline</h5>
    <div class="form-group col-12 mb-4">
        <label>Kode Naskah: </label>
        <input type="text" class="form-control" placeholder="Kode Naskah" value="{{$naskah->kode}}" readonly required>
        <input type="hidden" class="form-control" name="request_" value="submit" required>
        <input type="hidden" class="form-control" name="method_" value="add" required>
        <input type="hidden" class="form-control" name="add_tl_naskah_id" value="{{$naskah->id}}" required>
        <div id="err_add_tl_nama"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Naskah Masuk: </label>
        <input type="text" class="form-control" name="add_naskah_masuk" value="{{$naskah->tanggal_masuk_naskah}}" readonly>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Proses Penerbitan: </label>
        <input type="text" class="form-control drp-timeline" name="add_proses_penerbitan" placeholder="Tanggal">
        <!-- <input type="text" class="form-control datepicker" name="add_proses_penerbitan" required> -->
    </div>
    <div class="form-group col-12 mb-4">
        <label>Proses Produksi: </label>
        <input type="text" class="form-control drp-timeline" name="add_proses_penerbitan" placeholder="Tanggal">
    </div>
    <div class="form-group col-12 mb-4">
        <label>Buku Jadi: </label>
        <input type="text" class="form-control datepicker" name="add_buku_jadi" required>
    </div>
</div>
<div class="modal-footer bg-whitesmoke br">
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-sm btn-success" data-submit="add">Simpan</button>
</div>
</form>
@elseif($method=='edit')
<div class="modal-header bg-warning"></div>
<form id="f_tl">
<div class="modal-body row">
    <h5 class="modal-title col-12 mb-3">#Ubah Timeline</h5>
    <div class="form-group col-12 mb-4">
        <label>Kode Naskah: </label>
        <input type="text" class="form-control" placeholder="Kode Naskah" value="{{$naskah->kode}}" readonly required>
        <input type="hidden" class="form-control" name="request_" value="submit" required>
        <input type="hidden" class="form-control" name="method_" value="add" required>
        <input type="hidden" class="form-control" name="add_tl_naskah_id" value="{{$naskah->id}}" required>
        <div id="err_add_tl_nama"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Naskah Masuk: </label>
        <input type="text" class="form-control" name="add_naskah_masuk" value="{{$naskah->tanggal_masuk_naskah}}" readonly>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Proses Penerbitan: </label>
        <input type="text" class="form-control daterange-cus" name="add_proses_penerbitan" value="{{$timeline->penerbitan}}" required>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Proses Produksi: </label>
        <input type="text" class="form-control daterange-cus" name="add_proses_produksi" value="{{$timeline->produksi}}" required>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Buku Jadi: </label>
        <input type="text" class="form-control daterange-single" name="add_buku_jadi" value="{{$timeline->tgl_buku_jadi}}" required>
    </div>
</div>
<div class="modal-footer bg-whitesmoke br">
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-sm btn-warning" data-submit="add">Simpan</button>
</div>
</form>
@else

@endif