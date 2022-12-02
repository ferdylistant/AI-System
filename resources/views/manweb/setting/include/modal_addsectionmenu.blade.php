<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titleSectionMenu" aria-hidden="true" id="md_SectionMenu">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="titleSectionMenu">Tambah Data Section Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="fm_AddSectionMenu">
                <div class="modal-body">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3" for="namaSection">Nama</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" name="add_nama" class="form-control" id="namaSection" placeholder="Nama Section">
                            <div id="err_add_nama"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3" for="orderUrutan">Order Urutan</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="number" name="add_order_ab" min="1" class="form-control" id="orderUrutan" placeholder="Order Urutan">
                            <div id="err_add_order_ab"></div>
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
