<!-- Modal Edit Kelompok Buku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_EditKelompokBuku">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <form id="fm_EditKelompokBuku">
                <div class="modal-body">
                    <h5 class="modal-title mb-3">#Ubah Kelompok Buku</h5>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Kelompok Buku</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" name="edit_nama" class="form-control"
                                placeholder="Nama Kelompok Buku">
                            <input type="hidden" name="edit_id" class="form-control" value="">
                            <div id="err_edit_nama"></div>
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
