<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Home') | Listening Wall</title>
        
        <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,600,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://use.typekit.net/rtm5lcc.css">
         
        <link rel="stylesheet" href="{{mix('css/app.css')}}" type="text/css" />
        
        @stack('head')

        <meta name="csrf-token" value="{{csrf_token()}}" />
    </head>

    <body class="overflow-hidden">

        @yield('page')

        @stack('foot')
        
    </body>
</html>
