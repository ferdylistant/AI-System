<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleEditRiwayat" aria-hidden="true"
    id="modalEditRiwayatKirim">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titleEditRiwayat"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_EditRiwayat">
                    <div class="form-group">
                        @csrf
                        <input type="hidden" name="id_" value="">
                        <input type="hidden" name="track_id" value="">
                        <label for="editJmlDikirim">Jumlah Kirim (<span class="text-danger">*</span>)</label>
                        <input type="text" class="form-control" name="edit_jml_dikirim" id="editJmlDikirim">
                        <span id="err_edit_jml_dikirim"></span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Simpan perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
