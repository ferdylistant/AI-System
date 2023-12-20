<!-- Modal AddImprint -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_AddImprint">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_addImprint">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">Tambah Imprint</h5>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3">Nama Imprint</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" name="nama_imprint" class="form-control" placeholder="Nama Imprint">
                            <div id="err_nama_imprint"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_addImprint">Simpan</button>
            </div>
        </div>
    </div>
</div>
