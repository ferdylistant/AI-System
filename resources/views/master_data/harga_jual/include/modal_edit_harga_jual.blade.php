<!-- Modal Edit Format Buku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_EditHargaJual">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_EditHargaJual">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">#Ubah Harga Jual</h5>
                    <div class="form-group mb-4">
                        <label class="col-form-label">Nama</label>
                        <input type="text" name="edit_nama" class="form-control" placeholder="Nama Harga Jual">
                        <input type="hidden" name="edit_id" class="form-control" value="">
                        <div id="err_edit_nama"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_EditHargaJual">Simpan</button>
            </div>
        </div>
    </div>
</div>
