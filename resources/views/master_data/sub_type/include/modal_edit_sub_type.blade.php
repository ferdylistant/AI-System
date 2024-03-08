<!-- Modal Edit SType -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_EditSType">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_EditSType">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">#Ubah Sub Type</h5>
                    <div class="form-group">
                        <label class="col-form-label">Type <span class="text-danger">*</span></label>
                        <select id="edit_type" name="edit_nama_type" class="form-control select-type">
                            <option label="Pilih type"></option>
                        </select>
                        <div id="err_edit_nama_type"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Sub Type <span class="text-danger">*</span></label>
                        <input type="text" name="edit_nama" class="form-control" placeholder="Nama Sub Type">
                        <input type="hidden" name="edit_id" class="form-control" value="">
                        <div id="err_edit_nama"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_EditSType">Simpan</button>
            </div>
        </div>
    </div>
</div>
