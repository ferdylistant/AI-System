<div id="md_PindahRak" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalPindahRak" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPindahRak"><i class="fas fa-exchange-alt"></i>&nbsp;Pindah Rak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="section">
                    <div class="section-body">
                        <h6 class="section-title" id="sectionTitle"></h6>
                        <form id="fm_PindahRak">
                            <input type="hidden" name="stok_id" value="">
                            <input type="hidden" name="rack_data_id" value="">
                            <input type="hidden" name="current_rack_id" value="">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="selRak">Pindah ke rak (<span class="text-danger">*</span>)</label>
                                    <select class="form-control select-rack-move" name="rack_id" id="selRak"></select>
                                    <div id="err_rack_id"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jml_stok_rak">Jumlah item yang dipindah (<span class="text-danger">*</span>)</label>
                                    <input type="text" class="form-control" name="jml_stok_rak" id="jml_stok_rak" placeholder="Jumlah yang akan dipindah">
                                    <div id="err_jml_stok_rak"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="tglPindah">Tanggal Masuk Gudang (<span class="text-danger">*</span>)</label>
                                    <input type="text" class="form-control datepicker-pindah" name="tgl_pindah" id="tglPindah" placeholder="DD/MM/YYYY">
                                    <div id="err_tgl_pindah"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="optGudang">Operator Gudang (<span class="text-danger">*</span>)</label>
                                    <select class="form-control select-optgudang" name="users_id[]" multiple="multiple" id="optGudang"></select>
                                    <div id="err_users_id"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-primary" form="fm_PindahRak">Pindah</button>
            </div>
        </div>
    </div>
</div>
