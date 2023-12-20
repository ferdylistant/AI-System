<!-- Modal -->
<div class="modal fade" id="modalRevisiProdev" tabindex="-1" role="dialog" aria-labelledby="titleModalDetPrades" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form method="post" id="fadd_Revisi">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalDetPrades"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! csrf_field() !!}
                <input type="hidden" name="id" id="_id" value="">
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
