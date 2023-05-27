<div class="row justify-content-between">
    <div class="col-auto mr-auto">
        <div class="mb-4">
            @foreach ($departemen as $j)
                @if ($data->status == 'Proses')
                    @if (Gate::allows('do_approval', $j))
                        @if (!$act->isEmpty())
                            @foreach ($act as $a)
                                @if (!in_array($j, $act_j))
                                    @switch($j)
                                        @case('Marketing & Ops')
                                            @if (in_array('Penerbitan', $act_j))
                                                @if ($loop->first)
                                                    {{-- APPROVE --}}
                                                    <button type="button" class="btn btn-success" id="btn-approve"
                                                        data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                                        data-departemen="{{ $j }}" data-toggle="modal"
                                                        data-target="#modalApproval">
                                                        <i class="fas fa-check"></i>&nbsp;Setujui</button>
                                                    {{-- DECLINE --}}
                                                    <button type="button" class="btn btn-danger" id="btn-decline"
                                                        data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                                        data-departemen="{{ $j }}" data-toggle="modal"
                                                        data-target="#modalDecline">
                                                        <i class="fas fa-times"></i>&nbsp;Tolak</button>
                                                @endif
                                            @endif
                                        @break

                                        @case('Keuangan')
                                            @if (in_array('Marketing & Ops', $act_j))
                                                @if ($loop->first)
                                                    {{-- APPROVE --}}
                                                    <button type="button" class="btn btn-success" id="btn-approve"
                                                        data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                                        data-departemen="{{ $j }}" data-toggle="modal"
                                                        data-target="#modalApproval">
                                                        <i class="fas fa-check"></i>&nbsp;Setujui</button>
                                                    {{-- DECLINE --}}
                                                    <button type="button" class="btn btn-danger" id="btn-decline"
                                                        data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                                        data-departemen="{{ $j }}" data-toggle="modal"
                                                        data-target="#modalDecline">
                                                        <i class="fas fa-times"></i>&nbsp;Tolak</button>
                                                @endif
                                            @endif
                                        @break

                                        @case('Direktur Utama')
                                            @if (in_array('Keuangan', $act_j))
                                                @if ($loop->first)
                                                    {{-- APPROVE --}}
                                                    <button type="button" class="btn btn-success" id="btn-approve"
                                                        data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                                        data-departemen="{{ $j }}" data-toggle="modal"
                                                        data-target="#modalApproval">
                                                        <i class="fas fa-check"></i>&nbsp;Setujui</button>
                                                    {{-- DECLINE --}}
                                                    <button type="button" class="btn btn-danger" id="btn-decline"
                                                        data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                                        data-departemen="{{ $j }}" data-toggle="modal"
                                                        data-target="#modalDecline">
                                                        <i class="fas fa-times"></i>&nbsp;Tolak</button>
                                                @endif
                                            @endif
                                        @break
                                    @endswitch
                                @endif
                            @endforeach
                        @else
                            @if ($j == 'Penerbitan')
                                {{-- APPROVE --}}
                                <button type="button" class="btn btn-success" id="btn-approve"
                                    data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                    data-departemen="{{ $j }}" data-toggle="modal"
                                    data-target="#modalApproval">
                                    <i class="fas fa-check"></i>&nbsp;Setujui</button>
                                {{-- DECLINE --}}
                                <button type="button" class="btn btn-danger" id="btn-decline"
                                    data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                    data-departemen="{{ $j }}">
                                    <i class="fas fa-times" data-toggle="modal"
                                        data-target="#modalDecline"></i>&nbsp;Tolak</button>
                            @endif
                        @endif
                    @endif
                @endif
            @endforeach
        </div>
    </div>
    @foreach ($departemen as $j)
        <div class="col-auto mr-auto">
            <div class="mb-4">
                <div class="user-item">
                    <div class="user-details">
                        <div class="user-name">{{ $j }}:</div>
                        @if (!$act->isEmpty())
                            @foreach ($act as $a)
                                @if ($j == $a->type_departemen)
                                    @if (in_array($j, $act_j))
                                        @if ($a->type_action == 'Approval')
                                            <div class="text-job text-success">
                                                <a href="javascript:void(0)" id="btn-approve-detail" class="text-success"
                                                data-id="{{ $a->id }}">
                                                    <i class="fas fa-check-circle"></i>&nbsp;Telah
                                                    Disetujui
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-job text-danger">
                                                <a href="javascript:void(0)" id="btn-decline-detail" class="text-danger"
                                                    data-id="{{ $a->id }}">
                                                    <i class="fas fa-times"></i>&nbsp;Telah Ditolak
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-job text-muted">
                                            &nbsp;Belum diproses
                                        </div>
                                    @endif
                                @elseif ($loop->first)
                                    @if (!in_array($j, $act_j))
                                        <div class="text-job text-muted">
                                            &nbsp;Belum diproses
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @else
                            <div class="text-job text-muted">
                                &nbsp;Belum diproses
                            </div>
                        @endif

                        <div class="user-cta">
                            <samp class="text-default">
                                    @if (!$act->isEmpty())
                                        @foreach ($act as $a)
                                            @if ($j == $a->type_departemen)
                                                @if (in_array($j, $act_j))
                                                    ({{ \DB::table('users')->where('id', $a->users_id)->first()->nama }})
                                                @else
                                                    (nama {{ $j }})
                                                @endif
                                            @elseif ($loop->first)
                                                @if (!in_array($j, $act_j))
                                                    (nama {{ $j }})
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        (nama {{ $j }})
                                    @endif
                                </samp>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- @endforeach --}}
    @endforeach

</div>
