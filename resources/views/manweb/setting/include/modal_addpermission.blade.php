<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleMenu" aria-hidden="true" id="md_Permission">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="titleMenu">Tambah Data Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="fm_AddPermission">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="col-form-label">Nama</label>
                        <input type="text" name="add_name" class="form-control" placeholder="Nama Permission">
                        <div id="err_add_name"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Bagian</label>
                            <select id="add_bagianPermission" name="add_bagian"
                                class="form-control select-bagian-permission"></select>
                            <div id="err_add_bagian"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Menu</label>
                            <select id="add_menuPermission" name="add_menu"
                                class="form-control select-menu-permission"></select>
                            <div id="err_add_menu"></div>
                        </div>
                    </div>
                    <div class="form-row mb-2">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Action Type</label>
                            <select id="add_type" name="add_type" class="form-control select-type-permission"></select>
                            <div id="err_add_type"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Kata Kunci</label>
                            <input id="add_raw" name="add_raw" class="form-control">
                            <div id="err_add_raw"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_AddPermission">Simpan</button>
            </div>
        </div>
    </div>
</div>
