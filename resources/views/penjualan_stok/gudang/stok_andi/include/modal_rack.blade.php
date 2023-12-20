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
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <h6 class="section-title" id="sectionTitle"></h6>
                            </div>
                            <div class="col-auto">
                                <a class="text-default font-weight-bolder" href="javascript:void(0)" data-toggle="modal" data-target="#modalPermohonanRekondisi"><i class="fas fa-recycle text-danger"></i> Permohonan
                                    Rekondisi</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card card-statistic-2 card-primary pb-3">
                                    <div class="card-icon shadow-primary bg-primary">
                                        <i class="fas fa-archive"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Total Stok</h4>
                                        </div>
                                        <div class="card-body counter" id="totalStok">

                                        </div>
                                    </div>
                                    <div class="card-stats">
                                        <div class="card-stats-items">
                                            <div class="card-stats-item">
                                                <div class="card-stats-item-count counter" id="totalBelum"></div>
                                                <div class="card-stats-item-label">Belum Masuk Rak</div>
                                            </div>
                                            <div class="card-stats-item">
                                                <div class="card-stats-item-count counter" id="totalMasuk"></div>
                                                <div class="card-stats-item-label">Sudah Masuk Rak <a href="javascript:void(0)" class="text-primary" tabindex="0" role="button"
                                                    data-toggle="popover" data-trigger="focus" title="Informasi"
                                                    data-content="Total stok yang ada di rak.">
                                                    <abbr title="">
                                                    <i class="fas fa-info-circle me-3"></i>
                                                    </abbr>
                                                    </a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card card-statistic-2 card-danger pb-3">
                                    <div class="card-icon bg-danger">
                                        <i class="fas fa-plane-arrival"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Total Keluar Rak</h4>
                                        </div>
                                        <div class="card-body counter" id="totalKeluar">

                                        </div>
                                    </div>
                                    <div class="card-stats">
                                        <div class="card-stats-items">
                                            <div class="card-stats-item">
                                                <div class="card-stats-item-count counter" id="totalPenjualan"></div>
                                                <div class="card-stats-item-label">Penjualan</div>
                                            </div>
                                            <div class="card-stats-item">
                                                <div class="card-stats-item-count counter" id="totalLain"></div>
                                                <div class="card-stats-item-label">Lain-lain</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal"
                                data-target="#modalAktivitasRak"><i class="fas fa-history"></i> Riwayat Aktivitas
                                Rak</button>
                        </div>
                        <div class="form-group">
                            <label>Penempatan Rak</label>
                            <div id="contentRack"></div>
                        </div>
                        <form id="fm_addRak">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>Form Input Rak</label>
                                <input type="hidden" name="stok_id" value="">
                                <input type="hidden" name="total_stok" value="">
                                <div id="contentForm" class="input_fields_wrap example-1 scrollbar-deep-purple thin">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="add_field_button btn btn-dark">
                    <i class="fas fa-plus-circle"></i> Tambah Rak</button>
                <button type="submit" class="btn btn-primary" form="fm_addRak">
                    <i class="fas fa-file-import"></i> Submit</button>
            </div>
        </div>
    </div>
</div>
