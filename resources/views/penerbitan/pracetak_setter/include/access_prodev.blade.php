@if (!is_null($data->mulai_proof) && is_null($data->selesai_proof))
    @if ($data->proses == '1')
        <div class="col-auto mr-auto">
            <div class="mb-4">
                <button type="submit" class="btn btn-success" id="btn-approve-despro" data-id="{{ $data->id }}"
                    data-kode="{{ $data->kode }}">
                    <i class="fas fa-check"></i>&nbsp;Setujui</button>
                <button type="button" class="btn btn-danger" id="btn-revision" data-id="{{ $data->id }}"
                    data-kode="{{ $data->kode }}" data-judul="{{ $data->judul_final }}"
                    data-status="{{ $data->status }}"><i class="fas fa-tools"></i>&nbsp;Revisi</button>
            </div>
        </div>
    @endif
@else
    <div class="col-auto mr-auto">
        <div class="mb-4">
            <div class="user-item">
                <div class="user-details">
                    <div class="user-name">Yogyakarta,
                        {{ is_null($data->action_gm) ? Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l d F Y') : Carbon\Carbon::parse($data->action_gm)->translatedFormat('l d F Y') }}
                    </div>
                    @if ($data->status == 'Acc')
                        <div class="text-job text-success">
                            <i class="fas fa-check-circle"></i>&nbsp;Telah Disetujui
                        </div>
                    @elseif($data->status == 'Revisi')
                        <div class="text-job text-danger">
                            <a href="javascript:void(0)" id="btn-revision" class="text-danger"
                                data-id="{{ $data->id }}" data-kode="{{ $data->kode }}"
                                data-judul_asli="{{ $data->judul_asli }}" data-status="Revisi"
                                data-action_gm="{{ Carbon\Carbon::parse($data->action_gm)->translatedFormat('l, d F Y, H:i') }}"
                                data-alasan_revisi="{{ $data->alasan_revisi }}"
                                data-deadline_revisi="{{ Carbon\Carbon::parse($data->deadline_revisi)->translatedFormat('l, d F Y, H:i') }}">
                                <i class="fas fa-tools"></i>&nbsp;Direvisi</a>
                        </div>
                    @else
                        <div class="text-job text-muted">
                            &nbsp;
                        </div>
                    @endif
                    <div class="user-cta">
                        <span><u>GM. Penerbitan</u></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
