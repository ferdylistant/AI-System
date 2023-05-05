<div class="row">
    @if ($data->proses == '1')
        @switch($data->jalur_buku)
            @case('Reguler')
                @if (Gate::allows('do_create', 'otorisasi-' . $label . '-praset-reguler'))
                    @if (!is_null($dataRole))
                        @if (in_array(auth()->id(), json_decode($dataRole, true)))
                            @if ($done_proses == false)
                                <div class="col-auto">
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-success" id="btn-done-praset"
                                            data-id="{{ $data->id }}" data-kode="{{ $data->kode }}"
                                            data-autor="{{ $label }}">
                                            <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endif
            @break

            @case('MoU')
                @if (Gate::allows('do_create', 'otorisasi-' . $label . '-praset-mou'))
                    @if (!is_null($dataRole))
                        @if (in_array(auth()->id(), json_decode($dataRole, true)))
                            @if ($done_proses == false)
                                <div class="col-auto">
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-success" id="btn-done-praset"
                                            data-id="{{ $data->id }}" data-kode="{{ $data->kode }}"
                                            data-autor="{{ $label }}">
                                            <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endif
            @break

            @case('MoU-Reguler')
                @if (Gate::allows('do_create', 'otorisasi-' . $label . '-praset-reguler') ||
                        Gate::allows('do_create', 'otorisasi-' . $label . '-praset-mou'))
                    @if (!is_null($dataRole))
                        @if (in_array(auth()->id(), json_decode($dataRole, true)))
                            @if ($done_proses == false)
                                <div class="col-auto">
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-success" id="btn-done-praset"
                                            data-id="{{ $data->id }}" data-kode="{{ $data->kode }}"
                                            data-autor="{{ $label }}">
                                            <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endif
            @break

            @case('SMK/NonSMK')
                @if (Gate::allows('do_create', 'otorisasi-' . $label . '-praset-smk'))
                    @if (!is_null($dataRole))
                        @if (in_array(auth()->id(), json_decode($dataRole, true)))
                            @if ($done_proses == false)
                                <div class="col-auto">
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-success" id="btn-done-praset"
                                            data-id="{{ $data->id }}" data-kode="{{ $data->kode }}"
                                            data-autor="{{ $label }}">
                                            <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endif
            @break

            @case('Pro Literasi')
                @if (Gate::allows('do_create', 'otorisasi-' . $label . '-praset-proliterasi'))
                    @if (!is_null($dataRole))
                        @if (in_array(auth()->id(), json_decode($dataRole, true)))
                            @if ($done_proses == false)
                                <div class="col-auto">
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-success" id="btn-done-praset"
                                            data-id="{{ $data->id }}" data-kode="{{ $data->kode }}"
                                            data-autor="{{ $label }}">
                                            <i class="fas fa-check"></i>&nbsp;Selesai</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endif
            @break

        @endswitch
    @endif
</div>
