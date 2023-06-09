<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Braintree payment gateway') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://js.braintreegateway.com/web/dropin/1.24.0/js/dropin.min.js"></script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Braintree payment gateway') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @if(isset($token))
    <script>
        var button = document.querySelector('#submit-button');
        braintree.dropin.create({
            authorization: '{{$token}}',
            container: '#dropin-container',
            paypal: {
                flow: 'vault',
            },
            venmo: {
                allowNewBrowserTab: false
            },
            googlePay: {
                googlePayVersion: 2,
                merchantId: '5164272987942086881',
                transactionInfo: {
                    totalPriceStatus: 'FINAL',
                    totalPrice: '123.45',
                    currencyCode: 'USD'
                },
                allowedPaymentMethods: [{
                    type: 'CARD',
                    parameters: {
                        // We recommend collecting and passing billing address information with all Google Pay transactions as a best practice.
                        billingAddressRequired: true,
                        billingAddressParameters: {
                            format: 'FULL'
                        }
                    }
                }],
            },

        }, function (createErr, instance) {
            button.addEventListener('click', function () {
                instance.requestPaymentMethod(function (err, payload) {
                    (function($) {
                        $(function() {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: "{{route('token')}}",
                                data: {nonce : payload.nonce},
                                success: function (data) {
                                    console.log('success',payload.nonce)
                                },
                                error: function (data) {
                                    console.log('error',payload.nonce)
                                }
                            });
                        });
                    })(jQuery);
                });
            });
        });

    </script>
    @endif
</body>
</html>
