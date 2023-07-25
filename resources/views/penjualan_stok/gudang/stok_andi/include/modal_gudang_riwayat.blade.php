<!-- Modal -->
<div class="modal fade" id="modalRiwayatPengirimanGudang" tabindex="-1" role="dialog" aria-labelledby="judul_final" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <form method="post" id="form_Tracking">
              <div class="modal-header">
                  <h5 class="modal-title" id="judul_final"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body scrollbar-deep-purple"  style="max-height: 500px; overflow-y: auto;">
                  <div class="section">
                      <div class="section-body">
                          <div class="d-flex justify-content-between">
                              <div class="col-auto">
                                  <h6 class="section-title"> Riwayat Pengiriman</h6>
                              </div>
                              <div class="col-auto mt-4">
                                  <small class="badge" id="statusJob" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"></small>
                              </div>
                          </div>
                          @csrf
                          <input type="hidden" name="produksi_id" id="id_" value="">
                          <input type="hidden" name="proses_tahap" value="">
                              <div id="contentData"></div>
                      </div>

                  </div>
              </div>
          </form>
      </div>
    </div>
  </div>
