@if ($data->status == 'Proses')
    @if (!is_null($data->mulai_proof) && is_null($data->selesai_proof))
        @if ($data->proses == '1')
            <div class="col-auto mr-auto">
                <div class="mb-4">
                    <button type="submit" class="btn btn-success" id="btn-approve-prodev"
                        data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                        data-kode="{{ $data->kode }}">
                        <i class="fas fa-check"></i>&nbsp;Setujui</button>
                    <button type="button" class="btn btn-danger" id="btn-prodev-revision" data-id="{{ $data->id }}"
                        data-kode="{{ $data->kode }}" data-judul="{{ $data->judul_final }}"
                        data-status="{{ $data->status }}"><i class="fas fa-tools"></i>&nbsp;Revisi</button>
                </div>
            </div>
        @endif
    @else
    @endif
@endif
