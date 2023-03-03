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
                @foreach ($unique as $n)
                    <div class="card panel panel-default">
                        <div class="panel-heading">
                            <h6 class="panel-title text-uppercase p-3">
                                {{ $n->notif_id }}
                                <a class="text-decoration-none" data-toggle="collapse"
                                    href="#collapse-{{ $n->notif_id }}-{{ $n->form_id }}">{{ $n->section }}</a>
                            </h6>
                        </div>
                        <div id="collapse-{{ $n->notif_id }}-{{ $n->form_id }}" class="panel-collapse collapse">
                            <div class="card">
                                <div class="card-body">
                                    <div class="panel-body">
                                        @foreach ($notification as $nt)
                                            {{ $nt->type }}<br>
                                        @endforeach
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
