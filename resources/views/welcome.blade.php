<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ASN Development API</title>
        <link href="/css/main.css" rel="stylesheet" type="text/css">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    </head>
    <body>
    <div class="background">
        <img src="/images/2397.jpg" class="img-fluid"/>
    </div>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">authorise</a>
                    @endauth
                </div>
            @endif

            <div class="content">

                <div class="title m-b-md">
                    <object class="asn-icon" data="/images/main@3x.svg" type="image/svg+xml">
                        ASN Logo
                    </object>
                    Backend Dashboard
                </div>

                <div class="links">
                    <a href="https://github.com/Gerald34/asn-project-api" target="_blank">API Documentation</a>
                    <a href="#" target="_blank">Production Environment</a>
                    <a href="#" target="_blank">Stage Environment</a>
                    <a href="https://www.instagram.com/gerald_codex/" target="_blank">Developer Instagram</a>
                </div>
            </div>
        </div>
    </body>
</html>
