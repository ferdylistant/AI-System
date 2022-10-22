<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="refresh" content="3600" />
    <title>{{$title}} &mdash; Andi Intelligent System</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{url('vendors/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('vendors/font-awesome/css/all.min.css')}}" >

    <!-- CSS Libraries -->
    @yield('cssRequired')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{url('vendors/stisla/css/style.css')}}">
    <link rel="stylesheet" href="{{url('vendors/stisla/css/components.css')}}">
    <link rel="icon" type="image/x-icon" href="{{url('images/logo.png')}}">
    <!-- Specific JS File -->
    <style>
        .loadingoverlay {
            position: absolute;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0,0,0,0.2);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .input-group > .select2 {
            width: auto !important;
            flex: 1 1 auto !important;
        }
        .section .section-header .section-header-back a {
            color: #6777ef;
        }
        .search-element button {
            line-height: 24.4px;
        }

        .form-control.is-invalid + .select2 {
            border: 1px solid #dc3545 !important;
        }
        #overlay{
  position: fixed;
  top: 0;
  z-index: 1000;
  width: 100%;
  height:100%;
  display: none;
  background: rgba(0,0,0,0.6);
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
.is-hide{
  display:none;
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
                Copyright &copy; {{date("Y")}} <div class="bullet"></div> Andi Global Soft
                </div>
                <div class="footer-right">
                2.3.0
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{url('vendors/jquery/dist/jquery.js')}}"></script>
    <script src="{{url('vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{url('vendors/bootstrap/dist/js/bootstrap.js')}}"></script>
    <script src="{{url('vendors/jquery.nicescroll/jquery.nicescroll.js')}}"></script>
    <script src="{{url('vendors/moment/moment.js')}}"></script>
    <script src="{{url('vendors/stisla/js/stisla.js')}}"></script>

    <!-- JS Libraies -->
    @yield('jsRequired')

    <!-- Template JS File -->
    <script src="{{url('vendors/stisla/js/scripts.js')}}"></script>
    <script src="{{url('vendors/stisla/js/custom.js')}}"></script>

    <!-- Specific JS File -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function jqueryValidation_(element, rules) {
            let _rules = rules === undefined ? {} : rules;
            return $(element).validate({
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    console.log(element)
                    let name = element.attr('name');
                    name = name.replace('[]', '');
                    $('#err_'+name).addClass('invalid-feedback').append(error)
                },
                highlight: function (element) {
                    if($(element).parent().hasClass('image-preview')) {
                        $(element).parent().css('border-color', '#dc3545')
                    } else {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    }

                },
                unhighlight: function (element) {
                    if($(element).parent().hasClass('image-preview')) {
                        $(element).parent().css('border-color', '#ddd')
                    } else {
                        $(element).removeClass('is-invalid');
                    }

                },
                rules: _rules,
                ignore: ".note-editor *",
            })
        }

        function notifToast(stts, msg, reload = false) {
            if(stts == 'success') {
                iziToast.success({
                    title: 'Berhasil,',
                    message: msg,
                    position: 'topRight',
                    timeout: 2000,
                    onClosing: function(){
                        if(reload) { location.reload(); }
                    }
                });
            } else if (stts == 'error') {
                iziToast.error({
                    title: 'Gagal,',
                    message: msg,
                    position: 'topRight',
                    timeout: 2000,
                });
            }
        }

        let resizeImage = function (settings) {
            let file = settings.file;
            let maxSize = settings.maxSize;
            let reader = new FileReader();
            let image = new Image();
            let canvas = document.createElement('canvas');
            let dataURItoBlob = function (dataURI) {
                let bytes = dataURI.split(',')[0].indexOf('base64') >= 0 ?
                    atob(dataURI.split(',')[1]) :
                    unescape(dataURI.split(',')[1]);
                let mime = dataURI.split(',')[0].split(':')[1].split(';')[0];
                let max = bytes.length;
                let ia = new Uint8Array(max);
                for (let i = 0; i < max; i++)
                    ia[i] = bytes.charCodeAt(i);
                return new Blob([ia], { type: mime });
            };
            let resize = function () {
                let width = image.width;
                let height = image.height;
                if (width > height) {
                    if (width > maxSize) {
                        height *= maxSize / width;
                        width = maxSize;
                    }
                } else {
                    if (height > maxSize) {
                        width *= maxSize / height;
                        height = maxSize;
                    }
                }
                canvas.width = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(image, 0, 0, width, height);
                let dataUrl = canvas.toDataURL('image/jpeg');
                return dataURItoBlob(dataUrl);
            };
            return new Promise(function (ok, no) {
                if (!file.type.match(/image.*/)) {
                    no(new Error("Not an image"));
                    return;
                }
                reader.onload = function (readerEvent) {
                    image.onload = function () { return ok(resize()); };
                    image.src = readerEvent.target.result;
                };
                reader.readAsDataURL(file);
            });
        };

        $(function(){
            $.ajax({
                type: "POST",
                url: "{{url('notification')}}",
                data: {},
                success: function(result) {
                    if(result != '') {
                        $('#containerNotf').children('a').addClass('beep');
                        $('#containerNotf').children('div').children().eq(1).append(result);
                    }
                },
                error: function(err) {
                    $('#containerNotf').children('div').children().eq(1).append(err);
                    // console.log(err)
                }
            })
        })
    </script>
    @yield('jsNeeded')

</body>
</html>
