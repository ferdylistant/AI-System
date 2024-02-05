<div class="tab-pane fade show active" id="access" role="tabpanel" aria-labelledby="access-tab">
    <form id="form_UpdateAccess">
        {!! csrf_field() !!}
        <input type="hidden" name="user_id" value="{!! $user->id !!}">
        <div id="treeview_container" class="hummingbird-treeview overflow-auto mb-3">
        </div>

        <button type="submit" class="btn btn-sm btn-warning float-right">Simpan</button>
    </form>
</div>
