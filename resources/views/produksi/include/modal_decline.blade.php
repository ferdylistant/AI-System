<!-- Modal -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form method="post" id="fadd_Keterangan">
            <div class="modal-header">
                <h5 class="modal-title" id="judulBuku"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" name="id" id="id_" value="">
                <input type="hidden" name="kode_order" id="kode_Order" value="">
                <input type="hidden" name="judul_buku" id="judul_Buku" value="">
                <div class="form-group">
                    {{-- <p class="lead" id="judulBuku"></p> --}}
                    <label for="keterangan">Alasan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Konfirmasi</button>
            </div>
        </form>
    </div>
  </div>
</div>
