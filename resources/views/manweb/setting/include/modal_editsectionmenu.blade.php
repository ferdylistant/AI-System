<div class="modal fade modal-progress" tabindex="-1" role="dialog" aria-labelledby="titleSectionMenu" aria-hidden="true" id="md_EditSectionMenu">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleSectionMenu">Edit Data Section Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="fm_EditSectionMenu">
                <div class="modal-body">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Nama</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="hidden" name="edit_id" class="form-control" value="">
                            <input type="text" name="edit_name" class="form-control" placeholder="Nama Section">
                            <input type="hidden" name="edit_oldnama" class="form-control" placeholder="Nama Section">
                            <div id="err_edit_name"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Order Urutan</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="number" name="edit_order_ab" class="form-control" placeholder="Order Urutan">
                            <div id="err_edit_order_ab"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-warning">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
