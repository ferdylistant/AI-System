<!-- Modal AddKelompokBuku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_AddSubKelompokBuku">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <form id="fm_addSKBuku">
                <div class="modal-body">
                    <h5 class="modal-title mb-3">#Tambah Sub-Kelompok Buku</h5>
                    <div class="form-group">
                        <label class="col-form-label">Kelompok Buku <span class="text-danger">*</span></label>
                        <select id="add_Kb" name="nama_kelompok_buku" class="form-control select-kb">
                            <option label="Pilih kelompok buku"></option>
                        </select>
                        <div id="err_nama_kelompok_buku"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Nama Sub-Kelompok Buku <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sub_kelompok_buku" class="form-control"
                            placeholder="Nama Sub-Kelompok Buku">
                        <div id="err_nama_sub_kelompok_buku"></div>
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
