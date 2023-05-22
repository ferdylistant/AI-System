<!-- Modal -->
<div class="modal fade" id="modalApprovalDetail" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-dark">
                <h5 class="modal-title" id="titleModal"><i class="fas fa-book"></i> Form Detail Approval</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled list-unstyled-border">
                    <li class="media">
                        <a href="" data-magnify="gallery" id="avatarHref">
                            <img class="mr-3 rounded-circle" src="" alt="Image" width="50" id="imgAvatar">
                        </a>
                        <div class="media-body">
                            <div class="media-title font-weight-bold" id="namaUser"></div>
                            &mdash; <span class="text-small font-italic" id="jabatanTitle"></span>
                        </div>
                    </li>
                </ul>
                <hr>
                <div class='form-group mb-4'>
                    <label for='tglActionApprove'>Tanggal Approval:</label>
                    <p id='tglActionApprove'></p>
                </div>
                <div class='form-group'>
                    <label for='catatanApprove'>Catatan:</label>
                    <p id='catatanApprove'> </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
