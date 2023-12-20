<div id="modalPermohonanRekondisi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalPermohonanRekondisiTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPermohonanRekondisiTitle"><i class="fas fa-recycle"></i>&nbsp;Permohonan Rekondisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body scrollbar-deep-purple" style="max-height: 500px; overflow-y: auto;">
                <div class="section">
                    <div class="section-body">
                        <form id="fm_PermohonanRekondisi">
                            {!! csrf_field() !!}
                        <div id="contentPermohonan"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="d-block btn btn-sm btn-outline-primary btn-block submit-permohonan" form="fm_PermohonanRekondisi">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
