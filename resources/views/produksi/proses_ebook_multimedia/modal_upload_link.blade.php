<!-- Modal -->
<div class="modal fade" id="upload-link" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <form method="post" id="fadd_Keterangan">
              <div class="modal-header">
                  <h5 class="modal-title" id="tessss"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  @csrf
                  <input type="hidden" name="id" id="id_" value="">
                  <input type="hidden" name="kode_order" id="kode_Order" value="">
                  <input type="hidden" name="judul_buku" id="judul_Buku" value="">
                  <input type="hidden" name="status_cetak" id="status_cetak" value="">
                      <div id="contentData"></div>
              </div>
              <div class="modal-footer" id="footerUpload">

              </div>
          </form>
      </div>
    </div>
  </div>
