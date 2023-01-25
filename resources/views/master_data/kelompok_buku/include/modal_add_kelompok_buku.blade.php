<!-- Modal AddKelompokBuku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_AddKelompokBuku">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <form id="fm_addKBuku">
                <div class="modal-body">
                    <h5 class="modal-title mb-3">Tambah Kelompok Buku</h5>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Kelompok Buku</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" name="nama_kelompok_buku" class="form-control"
                                placeholder="Nama Kelompok Buku">
                            <div id="err_nama_kelompok_buku"></div>
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
