<div class="modal fade" tabindex="-1" role="dialog" id="md_EditTimeline">
    <div class="modal-dialog modal-xl" role="document" style="margin-bottom: 200px !important;">
        <div class="modal-content">
            <div class="modal-header bg-warning"></div>
            <form id="fadd_tl">
            <div class="modal-body">
                <h5 class="modal-title mb-3">#Timeline</h5>
                <div class="form-group col-12 mb-4">
                    <label>Judul Timeline: </label>
                    <input type="text" class="form-control" name="add_tl_nama" placeholder="Judul Timeline" required>
                    <input type="hidden" class="form-control" name="add_tl_naskah_id" value="" required>
                    <div id="err_add_tl_nama"></div>
                </div>
                <div class="form-group col-12 mb-4">
                    <label>Keterangan Timeline: </label>
                    <textarea class="form-control" name="add_tl_keterangan" placeholder="Keterangan untuk timeline" required></textarea>
                    <div id="err_add_tl_keterangan"></div>
                </div>
                <style>
                    #edit_tableTimeline td {
                        padding: 0 5px;
                    }
                </style>
                <table id="edit_tableTimeline" class="table table-hover" style="text-align: center; ">
                    <thead>
                        <tr>
                            <th width="12%">#</th>
                            <th width="22%">Naskah Masuk</th>
                            <th width="22%">Proses Penerbitan</th>
                            <th width="22%">Turun Cetak</th>
                            <th width="22%">Buku Jadi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Prodev</th>
                            <td><input type="text" class="form-control daterange-cus" placeholder=""></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                        </tr>
                        <tr>
                            <th>Produksi</th>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                        </tr>
                        <tr>
                            <th>Marketing</th>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                        </tr>
                        <tr>
                            <th>Direksi</th>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                            <td><input type="text" class="form-control daterange-cus"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>