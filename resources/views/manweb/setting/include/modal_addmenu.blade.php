<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleMenu" aria-hidden="true" id="md_Menu">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="titleMenu">Tambah Data Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="fm_AddMenu">
                <div class="modal-body">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Nama</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" name="add_name" class="form-control" placeholder="Nama Menu">
                            <div id="err_add_name"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Bagian</label>
                        <div class="col-sm-12 col-md-9">
                            <select id="add_bagian" name="add_bagian" class="form-control select-bagian"></select>
                            <div id="err_add_bagian"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Level</label>
                        <div class="col-sm-12 col-md-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="add_level" value="1" id="add_level_1">
                                <label class="form-check-label mr-4" for="add_level_1">1</label>
                                <input class="form-check-input" type="radio" name="add_level" value="2" id="add_level_2">
                                <label class="form-check-label" for="add_level_2">2</label>
                            </div>
                            <div id="err_add_level"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-4 lvl" style="display:none" id="level2">
                        <label class="col-form-label text-md-right col-12 col-md-3">Parent Level</label>
                        <div class="col-sm-12 col-md-9">
                            <select id="add_parent" name="add_parent" class="form-control select-parent"></select>
                            <div id="err_add_parent"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Order Menu</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="number" class="form-control" min="1" name="add_order_menu" placeholder="Order Menu">
                            <div id="err_add_order_menu"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Path URL</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" class="form-control" name="add_url" placeholder="path/example/name or #">
                            <div id="err_add_url"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Icon</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" class="form-control" name="add_icon" placeholder="fas fa-example">
                            <div id="err_add_icon"></div>
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
