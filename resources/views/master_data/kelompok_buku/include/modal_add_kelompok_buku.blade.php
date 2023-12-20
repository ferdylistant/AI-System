<!-- Modal AddKelompokBuku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_AddKelompokBuku">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_addKBuku">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">#Tambah Kelompok Buku</h5>
                    <div class="form-group">
                        <label class="col-form-label">Kelompok Buku <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelompok_buku" class="form-control"
                            placeholder="Nama Kelompok Buku">
                        <div id="err_nama_kelompok_buku"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_addKBuku">Simpan</button>
            </div>
        </div>
    </div>
</div>
