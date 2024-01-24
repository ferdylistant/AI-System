<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset='UTF-8'">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="AI System (Andi Intelligent System)">
    <meta name="author" content="Andi Global Soft">
    <title>{{$title}} &mdash; AI System</title>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NRG27JKQ');</script>
    <!-- End Google Tag Manager -->
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
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NRG27JKQ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @yield('content')
    <!-- General JS Scripts -->
    <script type="text/javascript" src="{{ url('vendors/jquery/dist/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ url('vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('vendors/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ url('vendors/jquery.nicescroll/jquery.nicescroll.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ url('vendors/moment/moment.js') }}"></script> -->
    <script type="text/javascript" src="{{ url('vendors/stisla/js/stisla.js') }}"></script>


    <!-- JS Libraies -->

    <!-- Template JS File -->

    <script type="text/javascript" src="{{ url('vendors/stisla/js/scripts.js') }}"></script>
    <script type="text/javascript" src="{{ url('vendors/stisla/js/custom.js') }}"></script>
    <script type="text/javascript" src="{{ url('vendors/jquery-validation/dist/jquery.validate.js') }}"></script>
    @yield('scriptAddon')
    <script type="text/javascript">
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
    </script>
    @yield('jsNeeded')
    <!-- Page Specific JS File -->
</body>

</html>
