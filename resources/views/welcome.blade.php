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
    </head>
    <body>
        <div id="app">
            <div class="container-fluid">
                <signin></signin>
            </div>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
