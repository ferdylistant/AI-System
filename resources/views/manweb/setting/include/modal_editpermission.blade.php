<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleMenu" aria-hidden="true" id="md_EditPermission">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="titleMenu">Edit Data Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="fm_EditPermission">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Nama</label>
                            <input type="hidden" name="edit_id" class="form-control" value="">
                            <input type="text" name="edit_name" class="form-control" placeholder="Nama Permission">
                            <div id="err_edit_name"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Bagian</label>
                                <select id="edit_bagianPermission" name="edit_bagian" class="form-control select-bagian-permission"></select>
                                <div id="err_edit_bagian"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Menu</label>
                                <select id="edit_menuPermission" name="edit_menu" class="form-control select-menu-permission"></select>
                                <div id="err_edit_menu"></div>
                        </div>
                    </div>
                    <div class="form-row mb-2">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Action Type</label>
                                <select id="edit_type" name="edit_type" class="form-control select-type-permission"></select>
                                <div id="err_edit_type"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Kata Kunci</label>
                                <input id="edit_raw" name="edit_raw" class="form-control">
                                <div id="err_edit_raw"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
