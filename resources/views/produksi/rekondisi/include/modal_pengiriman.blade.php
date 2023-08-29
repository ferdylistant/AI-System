<!-- Modal -->
<div class="modal fade" id="modalPengirimanRekondisi" tabindex="-1" role="dialog" aria-labelledby="modalPengirimanTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPengirimanTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body scrollbar-deep-purple" style="max-height: 500px; overflow-y: auto;">
                <form method="post" id="form_Tracking">
                    <div class="section">
                        <div class="section-body">
                            <div class="d-flex justify-content-between">
                                <div class="col-auto">
                                    <h6 class="section-title" id="sectionTitle">Pengiriman buku rekondisi ke gudang</h6>
                                </div>
                                <div class="col-auto mt-4">
                                    <small id="statusJob"
                                        style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;"></small>
                                </div>
                            </div>
                            @csrf
                            <input type="hidden" name="id" id="id" value="">
                            <input type="hidden" name="produksi_id" id="produksiId" value="">
                            <div id="contentData"></div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer" id="footerModal">

            </div>
        </div>
    </div>
</div>
