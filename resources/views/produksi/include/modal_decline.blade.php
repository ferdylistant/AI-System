<!-- Modal -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titleModal"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="formDecline">
          @csrf
          <input type="hidden" name="id" id="id" value="">
          <div class="form-group">
            <label for="keterangan">Alasan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>