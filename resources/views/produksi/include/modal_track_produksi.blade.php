<!-- Modal -->
<div class="modal fade" id="modalTrackProduksi" tabindex="-1" role="dialog" aria-labelledby="judul_final" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form method="post" id="form_Tracking">
            <div class="modal-header" id="statusJob">
                <h5 class="modal-title" id="judul_final"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="section">
                    <div class="section-body">

                        <h6 class="section-title" id="sectionTitle"></h6>
                        @csrf
                        <input type="hidden" name="id" id="id_" value="">
                        <input type="hidden" name="judul_final" value="">
                            <div id="contentData"></div>
                    </div>

                </div>
            </div>
            <div class="modal-footer" id="footerModal">

            </div>
        </form>
    </div>
  </div>
</div>
