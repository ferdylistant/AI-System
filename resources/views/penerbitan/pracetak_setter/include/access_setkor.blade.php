<div class="row">
    @if ($data->proses == '1')
    @switch($data->jalur_buku)
    @case('Reguler')
    @foreach (json_decode($dataRole, true) as $edt)
    @if (auth()->id() == $edt)
    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-praset-reguler'))
    @if ($done_proses == FALSE)
    <div class="col-auto">
        <div class="mb-4">
            <button type="submit" class="btn btn-success" id="btn-done-praset" data-id="{{ $data->id }}" data-kode="{{ $data->kode }}" data-autor="{{$label}}">
                <i class="fas fa-check"></i>&nbsp;Selesai</button>
        </div>
    </div>
    @endif

    @endif
    @endif
    @endforeach
    @break

    @case('MoU')
    @foreach (json_decode($dataRole) as $edt)
    @if (auth()->id() == $edt)
    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-praset-mou'))
    @if ($done_proses == FALSE)
    <div class="col-auto">
        <div class="mb-4">
            <button type="submit" class="btn btn-success" id="btn-done-praset" data-id="{{ $data->id }}" data-kode="{{ $data->kode }}" data-autor="{{$label}}">
                <i class="fas fa-check"></i>&nbsp;Selesai</button>
        </div>
    </div>
    @endif
    @endif
    @endif
    @endforeach
    @break

    @case('MoU-Reguler')
    @foreach (json_decode($dataRole) as $edt)
    @if (auth()->id() == $edt)
    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-praset-reguler') ||
    Gate::allows('do_create', 'otorisasi-'.$label.'-praset-mou'))
    @if ($done_proses == FALSE)
    <div class="col-auto">
        <div class="mb-4">
            <button type="submit" class="btn btn-success" id="btn-done-praset" data-id="{{ $data->id }}" data-kode="{{ $data->kode }}" data-autor="{{$label}}">
                <i class="fas fa-check"></i>&nbsp;Selesai</button>
        </div>
    </div>
    @endif
    @endif
    @endif
    @endforeach
    @break

    @case('SMK/NonSMK')
    @foreach (json_decode($dataRole) as $edt)
    @if (auth()->id() == $edt)
    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-praset-smk'))
    @if ($done_proses == FALSE)
    <div class="col-auto">
        <div class="mb-4">
            <button type="submit" class="btn btn-success" id="btn-done-praset" data-id="{{ $data->id }}" data-kode="{{ $data->kode }}" data-autor="{{$label}}">
                <i class="fas fa-check"></i>&nbsp;Selesai</button>
        </div>
    </div>
    @endif
    @endif
    @endif
    @endforeach
    @break

    @case('Pro Literasi')
    @foreach (json_decode($dataRole) as $edt)
    @if (auth()->id() == $edt)
    @if (Gate::allows('do_create', 'otorisasi-'.$label.'-praset-proliterasi'))
    @if ($done_proses == FALSE)
    <div class="col-auto">
        <div class="mb-4">
            <button type="submit" class="btn btn-success" id="btn-done-praset" data-id="{{ $data->id }}" data-kode="{{ $data->kode }}" data-autor="{{$label}}">
                <i class="fas fa-check"></i>&nbsp;Selesai</button>
        </div>
    </div>
    @endif
    @endif
    @endif
    @endforeach
    @break
    @endswitch
    @endif
</div>
