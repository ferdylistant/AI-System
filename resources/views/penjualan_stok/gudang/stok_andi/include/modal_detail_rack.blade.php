<div id="modalDetailRack" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalDetailRackTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailRackTitle"><i class="fas fa-history"></i>&nbsp;Rack Detail History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body scrollbar-deep-purple" style="max-height: 500px; overflow-y: auto;">
                <div class="section">
                    <div class="section-body">
                        <h6 class="section-title" id="sectionTitle"></h6>
                        <p class="section-lead">Detail proses peletakan ke dalam rak.</p>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-header-action">
                                            <button type="button" class="btn btn-light" data-rack_data_id="" data-stok_id="" data-toggle="modal" data-target="#md_PindahRak" id="btn_PindahRak"><i class="fas fa-exchange-alt"></i> Pindah Rak</button>
                                            <button type="button" class="btn btn-dark" data-rack_data_id="" data-stok_id="" data-toggle="modal" data-target="#md_HistoryRack" id="btn_HistoryRak"><i class="fas fa-history"></i> History</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="contentDetailRack"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
