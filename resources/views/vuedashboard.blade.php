<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>App | Goldberg Resources</title>

        <!-- Fonts -->
        <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> -->

        <!-- Styles -->
        <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->

        <!-- Scripts -->
        <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
        <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900"> -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css"> -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vuetify@1.5.12/dist/vuetify.min.css"> -->

        <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet"> -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet"> -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet"> -->
        <link rel="icon" href="{{ URL::asset('/images/favicon.png') }}" type="image/x-icon"/>
        <!-- <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" rel="stylesheet"> -->

        <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900"> -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
        <!-- <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet"> -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
        <!-- <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js" integrity="sha512-BmM0/BQlqh02wuK5Gz9yrbe7VyIVwOzD1o40yi1IsTjriX/NGF37NyXHfmFzIlMmoSIBXgqDiG1VNU6kB5dBbA==" crossorigin="anonymous"></script>
        <script
			  src="https://code.jquery.com/jquery-3.6.0.min.js"
			  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
			  crossorigin="anonymous"></script> -->

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    </head>
    <body class="font-sans antialiased">

      <div id="app">
          <App></App>
      </div>

      <!-- Scripts -->
      <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
      <!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script> -->
      <!-- <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script> -->
      <script src="{{ asset('js/app.js') }}"></script>
      <!-- <script src="{{ asset('js/jquery.min.js') }}j"></script>
      <script src="{{ asset('js/popper.js') }}"></script>
      <script src="{{ asset('js/bootstrap.min.js') }}"></script>
      <script src="{{ asset('js/main.js') }}"></script> -->

      <!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script> -->
      <!-- <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script> -->


    </body>
</html>
