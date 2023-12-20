<!-- Modal Edit Format Buku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_EditOperatorMesin">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_EditOMesin">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">#Ubah Operator Mesin</h5>
                    <div class="form-group row mb-4">
                        <label class="col-form-label">Nama</label>
                        <input type="text" name="edit_nama" class="form-control" placeholder="Operator Mesin">
                        <input type="hidden" name="edit_id" class="form-control" value="">
                        <div id="err_edit_nama"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_EditOMesin">Simpan</button>
            </div>
        </div>
    </div>
</div>
