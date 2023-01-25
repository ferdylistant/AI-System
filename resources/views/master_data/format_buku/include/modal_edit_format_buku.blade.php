<!-- Modal Edit Format Buku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_EditFormatBuku">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <form id="fm_EditFBuku">
                <div class="modal-body">
                    <h5 class="modal-title mb-3">#Ubah Format Buku</h5>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Format Buku</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" name="edit_jenis_format" class="form-control"
                                placeholder="Nama Format Buku">
                            <input type="hidden" name="edit_id" class="form-control" value="">
                            <div id="err_edit_jenis_format"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
