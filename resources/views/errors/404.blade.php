<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>404 &mdash; Andi Intelligent System</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="icon" type="image/x-icon" href="{{url('images/logo.png')}}">
    <style>
        body {
            background: #fff;
        }

        .error-container {
            height: 100vh !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: montserrat, sans-serif;

        }

        .big-text {
            font-size: 200px;
            font-weight: 1000;
            font-family: sans-serif;
            background: url(https://c4.wallpaperflare.com/wallpaper/973/361/134/space-artwork-wallpaper-preview.jpg) no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: cover;
            background-position: center;
        }

        .small-text {
            font-family: montserrat, sans-serif;
            color: rgb(0, 0, 0);
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .form-group {
            display: flex;
        }

        .form-control {
            overflow: hidden;
            background: #fff;
            border: 1px #53a0e4 solid;
            border-right: none;
            border-radius: 25px 0px 0px 25px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #61b045;
            box-shadow: none;
        }

        .button {
            color: #fff;
            padding: 4px 26px;
            font-weight: 300;
            border: none;
            position: relative;
            font-family: 'Raleway', sans-serif;
            display: inline-block;
            text-transform: uppercase;
            -webkit-border-radius: 90px;
            -moz-border-radius: 90px;
            border-radius: 90px;
            margin: 2px;
            margin-top: 2px;
            background-image: linear-gradient(to right, #09b3ef 0%, #1e50e2 51%, #09b3ef 100%);
            background-size: 200% auto;
            flex: 1 1 auto;
            text-decoration: none;

        }


        .button:hover,
        .button:focus {
            color: #ffffff;
            background-position: right center;
            -webkit-box-shadow: 0px 5px 15px 0px rgba(0, 0, 0, 0.1);
            -moz-box-shadow: 0px 5px 15px 0px rgba(0, 0, 0, 0.1);
            box-shadow: 0px 5px 15px 0px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }
    </style>
    <!-- /END GA -->
</head>

<body cz-shortcut-listen="true" class="sidebar-gone">
    <div class="container error-container">
        <div class="row  d-flex align-items-center justify-content-center">
            <div class="col-md-12 text-center">
                <h1 class="big-text">404</h1>
                <h2 class="small-text">PAGE NOT FOUND</h2>

            </div>
            <div class="col-md-6  text-center">
                <p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>


                <button class="button button-dark-blue iq-mt-15 text-center" onclick="history.back()">Back to previous</button>

            </div>

        </div>


    </div>


    <!-- General JS Scripts -->
    <script src="{{url('/vendors/jquery/dist/jquery.js')}}"></script>
    <script src="{{url('/vendors/popper.js/dist/umd/popper.js')}}"></script>
    <script src="{{url('/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{url('/vendors/stisla/js/stisla.js')}}"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="{{url('/vendors/stisla/js/scripts.js')}}"></script>
    <script src="{{url('/vendors/stisla/js/custom.js')}}"></script>


</body>

</html>
