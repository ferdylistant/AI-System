<div class="row justify-content-between">
    <div class="col-auto mr-auto">
        <div class="mb-4">
            @foreach ($jabatan as $j)
                @if (Gate::allows('do_approval', $j))
                    @if (!$act->isEmpty())
                        @foreach ($act as $a)
                            @if (!in_array($j, $act_j))
                                @if ($j == 'Dir. Utama')
                                    @if ($dirop_dirke == true)
                                        {
                                        {{-- APPROVE --}}
                                        <button type="submit" class="btn btn-success" id="btn-approve"
                                            data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                            data-jabatan="{{ $j }}">
                                            <i class="fas fa-check" data-toggle="modal"
                                                data-target="#modalPersetujuan"></i>&nbsp;Setujui</button>
                                        {{-- DECLINE --}}
                                        <button type="button" class="btn btn-danger" id="btn-decline"
                                            data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                            data-jabatan="{{ $j }}" data-act="Decline"><i
                                                class="fas fa-times" data-toggle="modal"
                                                data-target="#modalDecline"></i>&nbsp;Pending</button>
                                        }
                                    @endif
                                @else
                                    {{-- APPROVE --}}
                                    <button type="submit" class="btn btn-success" id="btn-approve"
                                        data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                        data-jabatan="{{ $j }}">
                                        <i class="fas fa-check" data-toggle="modal"
                                            data-target="#modalPersetujuan"></i>&nbsp;Setujui</button>
                                    {{-- DECLINE --}}
                                    <button type="button" class="btn btn-danger" id="btn-decline"
                                        data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                        data-jabatan="{{ $j }}" data-act="Decline"><i class="fas fa-times"
                                            data-toggle="modal" data-target="#modalDecline"></i>&nbsp;Pending</button>
                                @endif
                            @endif
                        @endforeach
                    @else
                        @if ($j == 'GM Penerbitan')
                            {{-- APPROVE --}}
                            <button type="submit" class="btn btn-success" id="btn-approve"
                                data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                data-jabatan="{{ $j }}">
                                <i class="fas fa-check" data-toggle="modal"
                                    data-target="#modalPersetujuan"></i>&nbsp;Setujui</button>
                            {{-- DECLINE --}}
                            <button type="button" class="btn btn-danger" id="btn-decline"
                                data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                data-jabatan="{{ $j }}" data-act="Decline">
                                <i class="fas fa-times" data-toggle="modal"
                                    data-target="#modalDecline"></i>&nbsp;Pending</button>
                        @endif
                    @endif
                @endif
            @endforeach
        </div>
    </div>
    @foreach ($jabatan as $j)
        <div class="col-auto mr-auto">
            <div class="mb-4">
                <div class="user-item">
                    <div class="user-details">
                        <div class="user-name">{{ $j }}:</div>
                        @if (!$act->isEmpty())
                            @foreach ($act as $a)
                                @if (in_array($j, $act_j))
                                    @if ($a->type_action == 'Approval')
                                        <div class="text-job text-success">
                                            <a href="javascript:void(0)" id="btn-detail" class="text-success"
                                                data-toggle="modal" data-target="#modalPersetujuan"><i
                                                    class="fas fa-check-circle"></i>&nbsp;Telah
                                                Disetujui
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-job text-danger">
                                            <a href="javascript:void(0)" id="btn-decline-detail" class="text-danger"
                                                data-judul="{{ $data->judul_final }}" data-id="{{ $data->id }}"
                                                data-jabatan="{{ $j }}"
                                                data-tgl="{{ Carbon\Carbon::parse($a->tgl_action)->translatedFormat('l d F Y, H:i') }}"
                                                data-catatan="{{ $a->catatan_action }}" data-toggle="modal"
                                                data-target="#modalDeclineDetail">
                                                <i class="fas fa-times"></i>&nbsp;Telah Ditolak
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-job text-muted">
                                        &nbsp;Belum diproses
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-job text-muted">
                                &nbsp;Belum diproses
                            </div>
                        @endif

                        <div class="user-cta">
                            <span class="text-underline"><u>
                                    @if (!$act->isEmpty())
                                        @foreach ($act as $a)
                                            @if (in_array($j, $act_j))
                                                {{ \DB::table('users')->where('id', $a->users_id)->first()->nama }}
                                            @else
                                                (nama {{ $j }})
                                            @endif
                                        @endforeach
                                    @else
                                        (nama {{ $j }})
                                    @endif
                                </u></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- @endforeach --}}
    @endforeach

</div>
