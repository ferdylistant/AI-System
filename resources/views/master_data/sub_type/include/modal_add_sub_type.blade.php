<!-- Modal AddSType -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_AddSType">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_addSType">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">#Tambah Sub Type</h5>
                    <div class="form-group">
                        <label class="col-form-label">Type <span class="text-danger">*</span></label>
                        <select id="add_type" name="nama_type" class="form-control select-type">
                            <option label="Pilih type"></option>
                        </select>
                        <div id="err_nama_type"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Sub Type <span class="text-danger">*</span></label>
                        <input type="text" name="nama_stype" class="form-control"
                            placeholder="Nama Sub Type">
                        <div id="err_nama_stype"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_addSType">Simpan</button>
            </div>
        </div>
    </div>
</div>
