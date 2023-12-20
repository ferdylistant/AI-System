<!-- Modal -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="titleModalEditing" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form method="post" id="fadd_Decline">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalEditing"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! csrf_field() !!}
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="kode" id="kode" value="">
                <input type="hidden" name="judul_final" id="judul_final" value="">
                    <div id="contentData"></div>
            </div>
            <div class="modal-footer" id="footerDecline">

            </div>
        </form>
    </div>
  </div>
</div>
