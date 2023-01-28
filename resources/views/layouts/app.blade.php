<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title }} &mdash; Andi Intelligent System</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ url('vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/font-awesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    @yield('cssRequired')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ url('vendors/stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/stisla/css/components.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ url('images/logo.png') }}">
    <!-- Specific JS File -->
    <style>
        .loadingoverlay {
            position: absolute;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.2);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .input-group>.select2 {
            width: auto !important;
            flex: 1 1 auto !important;
        }

        .section .section-header .section-header-back a {
            color: #6777ef;
        }

        .search-element button {
            line-height: 24.4px;
        }

        .form-control.is-invalid+.select2 {
            border: 1px solid #dc3545 !important;
        }

        #overlay {
            position: fixed;
            top: 0;
            z-index: 1000;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }

        .is-hide {
            display: none;
        }

        .beep-primary {
            position: relative;
        }

        .beep-primary:after {
            content: '';
            position: absolute;
            top: 2px;
            right: 8px;
            width: 7px;
            height: 7px;
            background-color: #6777ef;
            border-radius: 50%;
            animation: pulsate 1s ease-out;
            animation-iteration-count: infinite;
            opacity: 1;
        }

        .beep-primary.beep-primary-sidebar:after {
            position: static;
            margin-left: 10px;
        }

        .beep-success {
            position: relative;
        }

        .beep-success:after {
            content: '';
            position: absolute;
            top: 2px;
            right: 8px;
            width: 7px;
            height: 7px;
            background-color: #63ed7a;
            border-radius: 50%;
            animation: pulsate 1s ease-out;
            animation-iteration-count: infinite;
            opacity: 1;
        }

        .beep-success.beep-success-sidebar:after {
            position: static;
            margin-left: 10px;
        }

        .beep-danger {
            position: relative;
        }

        .beep-danger:after {
            content: '';
            position: absolute;
            top: 2px;
            right: 8px;
            width: 7px;
            height: 7px;
            background-color: #fc544b;
            border-radius: 50%;
            animation: pulsate 1s ease-out;
            animation-iteration-count: infinite;
            opacity: 1;
        }

        .beep-danger.beep-danger-sidebar:after {
            position: static;
            margin-left: 10px;
        }
    </style>
    @yield('cssNeeded')
</head>

<body>
    <div id="app">
        <div id="overlay">
            <div class="cv-spinner">
                <span class="spinner"></span>
            </div>
        </div>
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            @include('layouts.topbar')
            <div class="main-sidebar">
                @include('layouts.leftbar')
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>

            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; {{ date('Y') }} <div class="bullet"></div> Andi Global Soft
                </div>
                <div class="footer-right">
                    2.3.0
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ url('vendors/jquery/dist/jquery.js') }}"></script>
    <script src="{{ url('vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="{{ url('vendors/jquery.nicescroll/jquery.nicescroll.js') }}"></script>
    <script src="{{ url('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ url('vendors/moment/moment.js') }}"></script>
    <script src="{{ url('vendors/stisla/js/stisla.js') }}"></script>
    <!-- JS Libraies -->
    @yield('jsRequired')

    <!-- Template JS File -->
    <script src="{{ url('vendors/stisla/js/scripts.js') }}"></script>
    <script src="{{ url('vendors/stisla/js/custom.js') }}"></script>

    <!-- Specific JS File -->
    <script>
        $(document).on('click','#logout',function(e) {
            e.preventDefault();
            swal({
                title: "Apakah anda yakin ingin keluar?",
                // text: "Data akan terhapus",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((result) => {
                if (result) {
                    $('#logout-form').submit() // this submits the form
                }
            })
        });
    </script>
    <script src="{{ url('js/main.js') }}"></script>
    {{-- <script src="js/app.js"></script> --}}
    {{-- <script>
        // Reload the page when the user's internet connection is restored
        setInterval(function() {
            if (navigator.onLine) {
                console.log(navigator.onLine);
                location.reload();
            }
            console.log(navigator.onLine);
        }, 120000);
    </script> --}}
    @yield('jsNeeded')

</body>

</html>
