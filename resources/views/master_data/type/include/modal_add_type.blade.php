<!-- Modal AddType -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_AddType">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_addType">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">#Tambah Type</h5>
                    <div class="form-group">
                        <label class="col-form-label">Type <span class="text-danger">*</span></label>
                        <input type="text" name="nama_type" class="form-control"
                            placeholder="Nama Type">
                        <div id="err_nama_type"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_addType">Simpan</button>
            </div>
        </div>
    </div>
</div>
