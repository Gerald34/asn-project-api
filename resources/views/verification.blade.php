
<!-- Stored in resources/views/login.blade.php -->
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <script> window.Laravel = { csrfToken: '{{ csrf_token()  }}' } </script>

    <title>ASN Development API</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link href="{{ asset('css/toastr.css') }}" rel="stylesheet">
    <style>

        #app {
            width: 600px;
            margin: 10% auto 0;
        }

        .container {
            background-color: #fff;
            -webkit-box-shadow: 0 0 12px 0 rgba(0,0,0,0.1);
            box-shadow: 0 0 12px 0 rgba(0,0,0,0.1);
            border-radius: 4px;
            border: 1px solid #f0f0f0;
        }

        .brand-logo {
            width: 70px;
            display: block;
        }

        .mail-header {
            background-color: #fff;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            padding: 25px;
        }

        .mail-body {
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
            padding: 25px;
        }

        .mail-body h1 {
            color: #7c8190;
            font-weight: 300;
        }

        .message-body {
            margin: 25px 0 35px;
        }

        .message-body p, .message-body h5 {
            color: #7c8190;
            font-size: 12px;
            line-height: 5px;
        }

        .mail-footer {
            padding: 25px 0;
        }

        .verification-button {
            padding: 15px 30px;
            background-color: #fb8b25;
            color: #fff;
            font-weight: 600;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .verification-button:hover {
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>
<body>
<div id="app">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 mail-header">
                <img class="brand-logo" src="{{asset('images/main_primary@3x.svg')}}"/>
            </div>

            <div class="col-lg-12 mail-body">
                <div class="mail-intro">
                    <h1>Email Confirmation</h1>
                </div>

                <div class="message-body">
                    <p>Hey {{$name}},</p>
                    <p>Welcome to Amatuer Sports Network!</p>
                    <p>Please click the button below to confirm your email address</p>
                    <p>If you did not sign up to ASN, please ignore this email or contact us at <a href="mailto:accounts@asn.com">accounts@asn.com</a></p>
                </div>

                <div class="mail-footer">
                    <h4>Verification Code: {{ $body }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
