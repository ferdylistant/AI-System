<!-- Modal AddFormatBuku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_AddJMesin">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_addJMesin">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">Tambah Jenis Mesin</h5>
                    <div class="form-group">
                        <label class="col-form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" placeholder="Jenis Mesin">
                        <div id="err_nama"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_addJMesin">Simpan</button>
            </div>
        </div>
    </div>
</div>
