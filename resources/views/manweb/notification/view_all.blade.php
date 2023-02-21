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
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h6 class="panel-title">
                                <a data-toggle="collapse" href="#collapse-{{ $loop->iteration }}">{{ $notif->type }}</a>
                            </h6>
                        </div>
                        <div id="collapse-{{ $loop->iteration }}" class="panel-collapse collapse">
                            <div class="card">
                                <div class="panel-body">Panel Body Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                    Unde
                                    sint, distinctio nobis similique fugiat, iure neque nostrum perferendis dolor accusamus
                                    minima,
                                    expedita vero! Cumque asperiores porro magni incidunt voluptatibus molestiae.
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </section>
@endsection
