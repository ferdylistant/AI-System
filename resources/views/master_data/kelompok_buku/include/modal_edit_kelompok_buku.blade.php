<!-- Modal Edit Kelompok Buku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_EditKelompokBuku">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_EditKelompokBuku">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">#Ubah Kelompok Buku</h5>
                    <div class="form-group">
                        <label class="col-form-label">Kelompok Buku <span class="text-danger">*</span></label>
                        <input type="text" name="edit_nama" class="form-control" placeholder="Nama Kelompok Buku">
                        <input type="hidden" name="edit_id" class="form-control" value="">
                        <div id="err_edit_nama"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_EditKelompokBuku">Simpan</button>
            </div>
        </div>
    </div>
</div>
