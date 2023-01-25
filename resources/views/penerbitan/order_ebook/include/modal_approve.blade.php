<!-- Modal -->
<div class="modal fade" id="modalApproval" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" id="fadd_CatatanApproval">
                <div class="modal-header bg-success text-default">
                    <h5 class="modal-title" id="titleModal"><i class="fas fa-book"></i> Form Approval</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" id="_id" value="">
                    <input type="hidden" name="judul_final" id="judul_BukuApproval" value="">
                    <input type="hidden" name="type_departemen" id="type_DepartemenApproval" value="">
                    <div class='form-group'><label for='catatan_action' class='col-form-label'>Catatan</label>
                        <textarea class='form-control' name='catatan_action' id='catatan_action' rows='4'></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button
                        type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
