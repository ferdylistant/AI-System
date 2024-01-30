<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset='UTF-8'">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="AI System (Andi Intelligent System)">
    <meta name="author" content="Andi Global Soft">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title }} &mdash; Andi Intelligent System</title>
    <!-- Google Tag Manager -->

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-J3486GFGFF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-J3486GFGFF');
</script>

    <!-- End Google Tag Manager -->
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@8.0.0/themes/satellite-min.css"
        integrity="sha256-p/rGN4RGy6EDumyxF9t7LKxWGg6/MZfGhJM/asKkqvA=" crossorigin="anonymous">


    <!-- CSS Libraries -->
    @yield('cssRequired')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('vendors/stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/stisla/css/components.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    {{-- <link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css"> --}}
    {{-- <link href="https://unpkg.com/placeholdifier/placeholdifier.css" rel="stylesheet" /> --}}
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
    {{-- <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NRG27JKQ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> --}}
    <!-- End Google Tag Manager (noscript) -->
    <header class="header">
        <div id="autocomplete"></div>
    </header>
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
    <script type="text/javascript" src="{{ asset('vendors/jquery/dist/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/jquery.nicescroll/jquery.nicescroll.js') }}"></script>
    <script type="text/javascript" src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendors/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/stisla/js/stisla.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.14.3/dist/algoliasearch-lite.umd.js"
        integrity="sha256-dyJcbGuYfdzNfifkHxYVd/rzeR6SLLcDFYEidcybldM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.50.3/dist/instantsearch.production.min.js"
        integrity="sha256-VIZm35iFB4ETVstmsxpzZrlLm99QKqIzPuQb1T0ooOc=" crossorigin="anonymous"></script> --}}

    <!-- JS Libraies -->
    @yield('jsRequired')

    <!-- Template JS File -->
    <script type="text/javascript" src="{{ asset('vendors/stisla/js/scripts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/stisla/js/custom.js') }}"></script>

    <!-- Specific JS File -->
    <script type="text/javascript">
        $(document).on('click', '#logout', function(e) {
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
    <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
    {{-- <script src="{{asset('js/app.js')}}"></script> --}}
    <script type="text/javascript">
        // Reload the page when the user's internet connection is restored
        $(document).ready(function() {
            var awayTimeout;
            var statusDB = '{{ auth()->user()->status_activity }}';
            var id = '{{ auth()->user()->id }}';
            checkAuth(id);

            function checkAuth(id) {
                $.ajax({
                    url: window.location.origin + '/check-authentication',
                    data: {
                        id: id
                    },
                    method: 'GET',
                    error: function(xhr, status, error) {
                        // console.error('Error checking authentication: ' + xhr);
                        // console.error('Error checking authentication: ' + status);
                        // console.error('Error checking authentication: ' + error);
                    }
                });
            }
            $(window).on('mousemove keydown', function(e) {
                if (statusDB == 'online') {
                    clearTimeout(awayTimeout);

                    awayTimeout = setTimeout(function() {
                        updateStatus('away', id);
                    }, 300000);
                } else {
                    updateStatus('online', id);
                }

            });
            $(window).on({
                beforeUnload: function() {
                    updateStatus('offline', id);
                },
                visibilitychange: function() {
                    if (document.hidden) {
                        clearTimeout(awayTimeout);
                        awayTimeout = setTimeout(function() {
                            updateStatus('away', id);
                        }, 300000);
                    } else {
                        updateStatus('online', id);
                    }
                }
            });
            $(window).on('online offline', function() {
                var status = navigator.onLine ? 'online' : 'offline';
                updateStatus(status, id);
            });
            //! Function Process
            function updateStatus(status, id) {
                $.ajax({
                    url: window.location.origin + '/update-status-activity',
                    method: 'POST',
                    data: {
                        status: status,
                        id: id
                    },
                    async: false,
                    // success: function (response) {
                    //     console.log(response);
                    // },
                    error: function(xhr, status, error) {
                        if (error == 'unknown status') {
                            checkAuth(id);
                        }
                        // console.log(xhr);
                        // console.log(status);
                        // console.log(error);
                    }
                });
            }
        });
    </script>
    @yield('jsNeeded')
    {{-- <script>
        const searchClient = algoliasearch('48PYOPQ4UX', '0d0db6942609a9c8667323100a037bdb');

        const INSTANT_SEARCH_INDEX_NAME = 'instant_search';
        // const instantSearchRouter = historyRouter();

        const search = instantsearch({
            searchClient,
            indexName: INSTANT_SEARCH_INDEX_NAME,
            // routing: instantSearchRouter,
        });

        // Mount a virtual search box to manipulate InstantSearch's `query` UI
        // state parameter.
        const virtualSearchBox = connectSearchBox(() => {});

        search.addWidgets([
            virtualSearchBox({}),
            hierarchicalMenu({
                container: '#categories',
                attributes: ['hierarchicalCategories.lvl0', 'hierarchicalCategories.lvl1'],
            }),
            hits({
                container: '#hits',
                templates: {
                    item(hit, {
                        html,
                        components
                    }) {
                        return html`
          <div>
            ${components.Highlight({ attribute: 'name', hit })}
          </div>
        `;
                    },
                },
            }),
            pagination({
                container: '#pagination',
            }),
        ]);

        search.start();
    </script> --}}
</body>

</html>
