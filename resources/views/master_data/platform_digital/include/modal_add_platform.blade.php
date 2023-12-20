<!-- Modal AddPlatform -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_AddPlatformEbook">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_addPDigital">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">Tambah Platform Digital</h5>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Nama Platform</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" name="nama_platform" class="form-control" placeholder="Nama Platform">
                            <div id="err_nama_platform"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_addPDigital">Simpan</button>
            </div>
        </div>
    </div>
</div>
