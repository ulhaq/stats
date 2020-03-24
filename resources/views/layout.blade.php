<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env("APP_NAME", "Laravel") }}</title>

        <link href="{{ asset('css/app.css')}}" rel="stylesheet">
    </head>
    <body>
        <div id="app" class="flex-center position-ref full-height">
        </div>

        <script src="{{ asset('js/app.js')}}"></script>
        <script src="https://www.gstatic.com/charts/loader.js"></script>
    </body>
</html>
