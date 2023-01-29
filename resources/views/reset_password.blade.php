@extends('layouts.app_auth')
@section('cssNeeded')
<style>
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
                                @if ($errors->any())
                                    <div class="alert alert-danger" style="width: 100%" role="alert">
                                        <h4 class="alert-heading">Error!</h4>
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('reset.password.post') }}" class="needs-validation" novalidate="">
                                    @CSRF
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="hidden" class="form-control" name="token" value="{{$token}}">
                                        <input id="email" type="email" class="form-control"
                                            name="email" tabindex="1" value="{{$email}}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">New Password <span class="text-danger">*</span></label>
                                        <div class="password-container">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" tabindex="2" required>
                                                <i class="fas fa-eye" id="eyeNP"></i>
                                            <div class="invalid-feedback">
                                                @error('password')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="password-container">
                                            <input id="password_confirmation" type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" tabindex="3" required>
                                                <i class="fas fa-eye" id="eyeCFP"></i>
                                            <div class="invalid-feedback">
                                                @error('password_confirmation')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block"
                                            tabindex="4">Update</button>
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
@endsection
@section('jsNeeded')
    <script>
        const newPass = document.querySelector("#password")
        const confirmPass = document.querySelector("#password_confirmation")
        const eyeNP = document.querySelector("#eyeNP")
        const eyeCFP = document.querySelector("#eyeCFP")
        eyeNP.addEventListener("click", function() {
            this.classList.toggle("fa-eye-slash")
            const type = newPass.getAttribute("type") === "password" ? "text" : "password"
            newPass.setAttribute("type", type)
        })
        eyeCFP.addEventListener("click", function() {
            this.classList.toggle("fa-eye-slash")
            const type = confirmPass.getAttribute("type") === "password" ? "text" : "password"
            confirmPass.setAttribute("type", type)
        })
    </script>
@endsection
