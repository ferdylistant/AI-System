<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login &mdash; AI System</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ url('vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/font-awesome/css/all.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-social/bootstrap-social.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ url('vendors/stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/stisla/css/components.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ url('images/logo.png') }}">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <img src="{{ url('images/logo.png') }}" alt="logo" class="shadow-light rounded-circle">
                            AI System
                        </div>

                        <div class="card card-primary">
                            <div class="card-header">
                                @if ($errors->any())
                                    <div class="alert alert-danger" role="alert">
                                        <h4 class="alert-heading">Error!</h4>
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ url('do-login') }}" class="needs-validation"
                                    novalidate="">@CSRF
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            tabindex="1" required autofocus>
                                        <div class="invalid-feedback">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            <div class="float-right">
                                                <a href="#" class="text-small">Forgot Password?</a>
                                            </div>
                                        </div>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            tabindex="2" required>
                                        <div class="invalid-feedback">
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block"
                                            tabindex="4">Login</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="simple-footer">Copyright &copy; {{ date('Y') }}
                            <div class="bullet"></div> Andi Global Soft
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- General JS Scripts -->
    <script src="{{ url('vendors/jquery/dist/jquery.js') }}"></script>
    <script src="{{ url('vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="{{ url('vendors/jquery.nicescroll/jquery.nicescroll.js') }}"></script>
    <!-- <script src="{{ url('vendors/moment/moment.js') }}"></script> -->
    <script src="{{ url('vendors/stisla/js/stisla.js') }}"></script>


    <!-- JS Libraies -->

    <!-- Template JS File -->
    <script type="module">
        // import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});
        document.addEventListener("DOMContentLoaded", function(event) {
            Echo.channel('channel-tes' )
                .listen('TestWebsocketEvent', (e) => {
                    console.log(e);
                });
        });
    </script>
    <script src="{{ url('vendors/stisla/js/scripts.js') }}"></script>
    <script src="{{ url('vendors/stisla/js/custom.js') }}"></script>
    <script>
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
    </script>

    <!-- Page Specific JS File -->
</body>

</html>
