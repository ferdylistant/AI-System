@if($type=='add')
<div class="modal-header bg-success"></div>
<form id="f_dtl">
<div class="modal-body">
    <h5 class="modal-title mb-3">#Timeline</h5>
    <div class="form-group col-12 mb-4">
        <label>Judul Timeline: </label>
        <input type="text" class="form-control" name="edit_tl_nama" value="{{$timeline->nama}}" placeholder="Judul Timeline" disabled>
        <input type="hidden" class="form-control" name="request_" value="submit" required>
        <input type="hidden" class="form-control" name="method_" value="add" required>
        <input type="hidden" class="form-control" name="edit_tl_timeline_id" value="{{$timeline->id}}" required>
        <input type="hidden" class="form-control" name="edit_tl_naskah_id" value="{{$timeline->naskah_id}}" required>
        <div id="err_edit_tl_nama"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Keterangan Timeline: </label>
        <textarea class="form-control" name="edit_tl_keterangan" placeholder="Keterangan untuk timeline" disabled>{{$timeline->keterangan}}</textarea>
        <div id="err_edit_tl_keterangan"></div>
    </div>
    <style>
        #edit_tableTimeline td {
            padding: 0 5px;
        }
    </style>
    <table id="edit_tableTimeline" class="table table-hover" style="text-align: center; ">
        <thead>
            <tr>
                <th width="12%">#</th>
                @foreach($arrProses as $p)
                <th width="22%">{{$p}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $arrKey = 0; @endphp
            @foreach($arrBagian as $kb => $b)
            <tr>
                <th>{{$b}}</th>
                @foreach($arrProses as $kp => $p)
                <td><input type="text" class="form-control {{$arrIn[$b][$p]['dateInput']?'daterange-cus':''}}" name="edit_tl_date[{{$b}}][{{$p}}]" 
                    value="{{$arrIn[$b][$p]['date']}}" {{$arrIn[$b][$p]['dateInput']?'':'disabled'}}></td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal-footer bg-whitesmoke br">
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-sm btn-success">Simpan</button>
</div>
</form>
@elseif($type=='edit')
<div class="modal-header bg-warning"></div>
<form id="f_dtl">
<div class="modal-body">
    <h5 class="modal-title mb-3">#Timeline</h5>
    <div class="form-group col-12 mb-4">
        <label>Judul Timeline: </label>
        <input type="text" class="form-control" name="edit_tl_nama" value="{{$timeline->nama}}" placeholder="Judul Timeline" disabled>
        <input type="hidden" class="form-control" name="request_" value="submit" required>
        <input type="hidden" class="form-control" name="method_" value="edit" required>
        <input type="hidden" class="form-control" name="edit_tl_timeline_id" value="{{$timeline->id}}" required>
        <div id="edit_edit_tl_nama"></div>
    </div>
    <div class="form-group col-12 mb-4">
        <label>Keterangan Timeline: </label>
        <textarea class="form-control" name="edit_tl_keterangan" placeholder="Keterangan untuk timeline" disabled>{{$timeline->keterangan}}</textarea>
        <div id="err_edit_tl_keterangan"></div>
    </div>
    <style>
        #edit_tableTimeline td {
            padding: 0 5px;
        }
    </style>
    <table id="edit_tableTimeline" class="table table-hover" style="text-align: center; ">
        <thead>
            <tr>
                <th width="12%">#</th>
                @foreach($arrProses as $p)
                <th width="22%">{{$p}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $arrKey = 0; @endphp
            @foreach($arrBagian as $kb => $b)
            <tr>
                <th>{{$b}}</th>
                @foreach($arrProses as $kp => $p)
                <td><input type="text" class="form-control {{$arrIn[$b][$p]['dateInput']?'daterange-cus':''}}" name="edit_tl_date[{{$b}}][{{$p}}]" 
                    value="{{$arrIn[$b][$p]['date']}}" {{$arrIn[$b][$p]['dateInput']?'':'disabled'}}></td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal-footer bg-whitesmoke br">
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
</div>
</form>
@else

@endif