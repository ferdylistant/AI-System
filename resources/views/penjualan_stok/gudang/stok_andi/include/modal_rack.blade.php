<div id="modalRack" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalRackTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRackTitle"><i class="fas fa-border-all"></i>&nbsp;Rack Position</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="section">
                    <div class="section-body">
                        <h6 class="section-title" id="sectionTitle"></h6>
                        <form id="fm_editRak">
                            <div class="form-group">
                                <label>Penempatan Rak</label>
                                <div id="contentRack"></div>
                            </div>
                        </form>
                        <form id="fm_addRak">
                            <div class="form-group">
                                <label>Form Input Rak</label>
                                <input type="hidden" name="stok_id" value="">
                                <div id="contentForm" class="input_fields_wrap example-1 scrollbar-deep-purple thin"></div>
                            </div>
                            <button type="button" class="add_field_button btn btn-dark">
                                <i class="fas fa-plus-circle"></i> Tambah Rak</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-import"></i> Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
