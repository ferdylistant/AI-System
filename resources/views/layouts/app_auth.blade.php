<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{$title}} &mdash; AI System</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ url('vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/font-awesome/css/all.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-social/bootstrap-social.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ url('vendors/stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('vendors/stisla/css/components.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ url('images/logo.png') }}">
    @yield('cssNeeded')
</head>

<body>
    @yield('content')
    <!-- General JS Scripts -->
    <script src="{{ url('vendors/jquery/dist/jquery.js') }}"></script>
    <script src="{{ url('vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('vendors/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="{{ url('vendors/jquery.nicescroll/jquery.nicescroll.js') }}"></script>
    <!-- <script src="{{ url('vendors/moment/moment.js') }}"></script> -->
    <script src="{{ url('vendors/stisla/js/stisla.js') }}"></script>


    <!-- JS Libraies -->

    <!-- Template JS File -->

    <script src="{{ url('vendors/stisla/js/scripts.js') }}"></script>
    <script src="{{ url('vendors/stisla/js/custom.js') }}"></script>
    @yield('scriptAddon')
    <script>
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
    </script>
    @yield('jsNeeded')
    <!-- Page Specific JS File -->
</body>

</html>
