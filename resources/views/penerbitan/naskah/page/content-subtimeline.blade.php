<div class="table-responsive">
    <table class="table table-bordered table-md">
        <tr>
            <td style="background-color: #E9ECEF; color: #868ba1; font-weight :600;">Proses</td>
            <td style="background-color: #E9ECEF; color: #868ba1; font-weight :600;">Mulai / Estimasi</td>
        </tr>
        <tr>
            <td>Naskah Masuk</td>
            <td>{{$timeline->tgl_naskah_masuk}}</td>
        </tr>
        <tr>
            <td>Penerbitan</td>
            <td>{{$timeline->tgl_mulai_penerbitan}} - ({{$timeline->ttl_hari_penerbitan}} hari)</td>
        </tr>
        <tr>
            <td>Produksi</td>
            <td>{{$timeline->tgl_mulai_produksi}} - ({{$timeline->ttl_hari_produksi}} hari)</td>
        </tr>
        <tr>
            <td>Buku Jadi</td>
            <td>{{$timeline->tgl_buku_jadi}}</td>
        </tr>
    </table>
</div>
<div id="accordion">
    <div class="accordion">
        <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-penerbitan" aria-expanded="false">
            <div>Penerbitan <span class="badge badge-warning float-right">4</span></div>
        </div>
        <div class="accordion-body collapse p-0" id="panel-body-penerbitan" data-parent="#accordion">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <tr>
                        <td ><button class="btn btn-sm btn-success btn-block" data-toggle="modal"
                            data-target="#md_updateSubtimeline" data-backdrop="static"
                            data-id="{{$timeline->id}}" data-bagian="Penerbitan">Update</button></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="accordion">
        <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-produksi" aria-expanded="false">
            <div>Produksi <span class="badge badge-warning float-right">4</span></div>
        </div>
        <div class="accordion-body collapse p-0" id="panel-body-produksi" data-parent="#accordion">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <tr>
                        <td ><button class="btn btn-sm btn-success btn-block" data-toggle="modal"
                            data-target="#md_updateSubtimeline" data-backdrop="static"
                            data-id="{{$timeline->id}}" data-bagian="Penerbitan">Update</button></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>