@extends('layouts.app_auth')
@section('cssNeeded')
    <link rel="stylesheet" href="{{ url('vendors/izitoast/dist/css/iziToast.min.css') }}">
    <style>
        .forget-pwd>a {
            color: #dc3545;
            font-weight: 500;
        }

        .forget-password .panel-default {
            padding: 31%;
            margin-top: -27%;
        }

        .forget-password .panel-body {
            padding: 15%;
            margin-bottom: -50%;
            background: #fff;
            border-radius: 5px;
            -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .text-center img {
            filter: drop-shadow(8px 8px 10px gray);
            width: 30%;
            margin-bottom: 10%;
        }

        .forget-password .dropdown {
            width: 100%;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }

        .forget-password .dropdown button {
            width: 100%;
        }

        .forget-password .dropdown ul {
            width: 100%;
        }

        .password-container {
            /* width: 400px; */
            position: relative;
        }

        .password-container input[type="password"],
        .password-container input[type="text"] {
            width: 100%;
            padding: 12px 36px 12px 12px;
            box-sizing: border-box;
        }

        .fa-eye {
            position: absolute;
            top: 28%;
            right: 4%;
            cursor: pointer;
            color: lightgray;
        }
    </style>
@endsection
@section('content')
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <img src="{{ url('images/logo.png') }}" alt="logo" class="shadow-light rounded-circle">
                            AI System
                        </div>
                        <div class="card card-primary">
                            <div class="card-header">
                                @if (Session::has('status'))
                                    <div class="alert alert-success" style="width: 100%" role="alert">
                                        <h4 class="alert-heading">Sukses!</h4>
                                        {{ Session::get('status') }}

                                    </div>
                                @elseif ($errors->any())
                                    <div class="alert alert-danger" style="width: 100%" role="alert">
                                        <h4 class="alert-heading">Gagal!</h4>
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ url('do-login') }}" class="needs-validation" novalidate="">
                                    @CSRF
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" name="email" tabindex="1" required autofocus>
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
                                                <a href="javascript:void(0)" class="text-small" data-toggle="modal"
                                                    data-target="#modalForgotPassword">Forgot Password?</a>
                                            </div>
                                        </div>
                                        <div class="password-container">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                tabindex="2" required>
                                            <i class="fas fa-eye" id="eye"></i>
                                            <div class="invalid-feedback">
                                                @error('password')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block"
                                            tabindex="3">Login</button>
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
        @include('modal_forgotpassword')
    </div>
@endsection
@section('scriptAddon')
    <script src="{{ url('vendors/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script>
        function notifToast(stts, msg, reload = false) {
            if (stts == 'success') {
                iziToast.success({
                    title: 'Okay!',
                    icon: 'fas fa-check-circle',
                    message: msg,
                    position: 'topRight',
                    timeout: 2000,
                    overlayColor: 'rgba(0, 0, 0, 0.6)',
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                    transitionInMobile: 'flipInX',
                    transitionOutMobile: 'flipOutX',
                    onClosing: function() {
                        if (reload) {
                            location.reload();
                        }
                    }
                });
            } else if (stts == 'error') {
                iziToast.error({
                    title: 'Oops!',
                    icon: 'fas fa-times-circle',
                    message: msg,
                    position: 'topRight',
                    timeout: 2000,
                    overlayColor: 'rgba(0, 0, 0, 0.6)',
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                    transitionInMobile: 'flipInX',
                    transitionOutMobile: 'flipOutX',
                });
            }
        }
    </script>
@endsection
@section('jsNeeded')
    <script>
        const passwordInput = document.querySelector("#password")
        const eye = document.querySelector("#eye")
        eye.addEventListener("click", function() {
            this.classList.toggle("fa-eye-slash")
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
            passwordInput.setAttribute("type", type)
        })
    </script>
    <script>
        function resetFrom(form) {
            form.trigger("reset");
            $('[name="email"]').val("").trigger("change");
        }
        $('#formResetPasswordModal').on('submit', function(e) {
            e.preventDefault();

            let el = $(this).get(0);
            let form_ = $('#modalForgotPassword');
            $.ajax({
                type: "POST",
                url: window.location.origin + "/forgot-password",
                data: new FormData(el),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    form_.addClass("modal-progress");
                },
                success: function(result) {
                    notifToast(result.status, result.message);
                    if (result.status == "success") {
                        resetFrom($('#formResetPasswordModal'));
                    }
                },
                error: function(err) {
                    rs = err.responseJSON.errors;
                    if (rs !== undefined) {
                        err = {};
                        Object.entries(rs).forEach((entry) => {
                            let [key, value] = entry;
                            err[key] = value;
                        });
                        // err.showErrors(err);
                    }
                    notifToast("error", "Terjadi kesalahan!");
                },
                complete: function() {
                    form_.removeClass("modal-progress");
                },
            });
        });
    </script>
@endsection
