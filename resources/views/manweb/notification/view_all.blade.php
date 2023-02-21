@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
            </div>
            <h1>All Notification</h1>
        </div>

        <div class="section-body">
            <div class="panel-group">
                @foreach ($notification as $notif)
                    <div class="card panel panel-default">
                        <div class="panel-heading d-flex justify-content-between">
                            <h6 class="panel-title">
                                <a data-toggle="collapse" href="#collapse-{{ $loop->iteration }}">{{ $notif->section }} -
                                    {{ $notif->type }}</a>
                            </h6>
                            <span
                                class="text-muted">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notif->created_at)->format('d M Y, H:i') }}</span>
                        </div>
                        <div id="collapse-{{ $loop->iteration }}" class="panel-collapse collapse">
                            <div class="card">
                                <div class="card-body">
                                    <div class="panel-body">
                                        {{-- {{ $notif->judul_asli }} --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </section>
@endsection
