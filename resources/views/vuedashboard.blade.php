<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>App | Goldberg Resources</title>
        <!-- Favicon -->
        <link rel="icon" href="{{ URL::asset('/images/favicon.png') }}" type="image/x-icon"/>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">

        <!-- Material-Design-Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    </head>

    <body class="font-sans antialiased">

      <div id="app"></div>


      <script src="{{ asset('js/app.js') }}"></script>

    </body>

</html>
