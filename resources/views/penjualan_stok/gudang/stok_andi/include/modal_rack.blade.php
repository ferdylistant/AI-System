<div id="modalRack" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalRackTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRackTitle"><i class="fas fa-border-all"></i>&nbsp;Rack Position</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body scrollbar-deep-purple" style="max-height: 500px; overflow-y: auto;">
                <div class="section">
                    <div class="section-body">
                        <h6 class="section-title" id="sectionTitle"></h6>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-info">
                                        <i class="fas fa-hand-holding"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Total Stok</h4>
                                        </div>
                                        <div class="card-body counter" id="totalStok">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-warning">
                                        <i class="fas fa-hand-holding-medical"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Total Masuk Rak</h4>
                                        </div>
                                        <div class="card-body counter" id="totalMasuk">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label>Penempatan Rak</label>
                            <div id="contentRack"></div>
                        </div>
                        <form id="fm_addRak">
                            <div class="form-group">
                                <label>Form Input Rak</label>
                                <input type="hidden" name="stok_id" value="">
                                <input type="hidden" name="total_stok" value="">
                                <div id="contentForm" class="input_fields_wrap example-1 scrollbar-deep-purple thin"></div>
                            </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="add_field_button btn btn-dark">
                    <i class="fas fa-plus-circle"></i> Tambah Rak</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-import"></i> Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>
