<!-- Modal Edit Format Buku -->
<div class="modal fade" tabindex="-1" role="dialog" id="md_EditRackList">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

            </div>
            <div class="modal-body">
                <form id="fm_EditRackList">
                    {!! csrf_field() !!}
                    <h5 class="modal-title mb-3">#Ubah Rack List</h5>
                    <div class="form-group mb-4">
                        <label class="col-form-label">Nama</label>
                        <input type="text" name="edit_nama" class="form-control" placeholder="Nama Rak">
                        <input type="hidden" name="edit_id" class="form-control" value="">
                        <div id="err_edit_nama"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Location</label>
                        <input type="text" name="edit_location" class="form-control" placeholder="Lokasi Rak">
                        <div id="err_edit_location"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-success" form="fm_EditRackList">Simpan</button>
            </div>
        </div>
    </div>
</div>
