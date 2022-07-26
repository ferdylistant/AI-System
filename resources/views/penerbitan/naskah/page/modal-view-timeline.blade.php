<div class="modal-header bg-primary"></div>
<form id="f_dtl">
<div class="modal-body">
    <h5 class="modal-title mb-3">#Timeline</h5>
    <div class="form-group col-12 mb-4">
        <label>Judul Timeline: </label>
        <input type="text" class="form-control" name="edit_tl_nama" value="{{$timeline->nama}}" placeholder="Judul Timeline" disabled>
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
                <th width="16%">#</th>
                @foreach($arrProses as $p)
                <th width="21%">{{$p}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $arrKey = 0; @endphp
            @foreach($arrBagian as $kb => $b)
            <tr>
                <th>{{$b}} <br> <span class="font-italic text-primary">{{$pic[$b]}}</span></th>
                @foreach($arrProses as $kp => $p)
                <td><input type="text" class="form-control {{$arrIn[$b][$p]['dateInput']?'daterange-cus':''}}" 
                    value="{{$arrIn[$b][$p]['date']}}" readonly></td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal-footer bg-whitesmoke br">
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
</div>
</form>