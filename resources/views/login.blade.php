<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login &mdash; AI System</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{url('vendors/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('vendors/font-awesome/css/all.css')}}" >

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{url('vendors/bootstrap-social/bootstrap-social.css')}}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{url('vendors/stisla/css/style.css')}}">
    <link rel="stylesheet" href="{{url('vendors/stisla/css/components.css')}}">
    <link rel="icon" type="image/x-icon" href="{{url('images/logo.png')}}">
</head>

<body>
    <div id="app">
        <section class="section">
        <div class="container mt-5">
            <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="login-brand">
                    <img src="{{url('images/logo.png')}}" alt="logo" class="shadow-light rounded-circle">
                    Andi Offset
                </div>

                <div class="card card-primary">
                    <div class="card-header"><h4>Login</h4></div>

                    <div class="card-body">
                        <form method="POST" action="{{url('do-login')}}" class="needs-validation" novalidate="">@CSRF
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                            <div class="invalid-feedback">Please fill in your email</div>
                        </div>

                        <div class="form-group">
                            <div class="d-block">
                                <label for="password" class="control-label">Password</label>
                                <div class="float-right">
                                    <a href="#" class="text-small">Forgot Password?</a>
                                </div>
                            </div>
                            <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                            <div class="invalid-feedback">please fill in your password</div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">Login</button>
                        </div>
                        </form>
                    </div>
                </div>

                <div class="simple-footer">Copyright &copy; Stisla 2018</div>
            </div>
            </div>
        </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="{{url('vendors/jquery/dist/jquery.js')}}"></script>
    <script src="{{url('vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{url('vendors/bootstrap/dist/js/bootstrap.js')}}"></script>
    <script src="{{url('vendors/jquery.nicescroll/jquery.nicescroll.js')}}"></script>
    <!-- <script src="{{url('vendors/moment/moment.js')}}"></script> -->
    <script src="{{url('vendors/stisla/js/stisla.js')}}"></script>


    <!-- JS Libraies -->

    <!-- Template JS File -->
    <script src="{{url('vendors/stisla/js/scripts.js')}}"></script>
    <script src="{{url('vendors/stisla/js/custom.js')}}"></script>

    <!-- Page Specific JS File -->
</body>
</html>
