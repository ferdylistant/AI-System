<!-- Modal Edit Kelompok Buku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_EditSubKelompokBuku">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_EditSubKelompokBuku">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">#Ubah Sub-Kelompok Buku</h5>
                    <div class="form-group">
                        <label class="col-form-label">Kelompok Buku <span class="text-danger">*</span></label>
                        <select id="edit_Kb" name="edit_kelompok_id" class="form-control select-kb">
                            <option label="Pilih kelompok buku"></option>
                        </select>
                        <input type="hidden" name="edit_id" class="form-control" value="">
                        <div id="err_edit_kelompok_id"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Nama Sub-Kelompok Buku <span class="text-danger">*</span></label>
                        <input type="text" name="edit_nama" class="form-control"
                            placeholder="Nama Sub-Kelompok Buku">
                        <div id="err_edit_nama"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_EditSubKelompokBuku">Update</button>
            </div>
        </div>
    </div>
</div>
