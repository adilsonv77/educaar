<!DOCTYPE html>
<html lang="pt-br" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('page-name')</title>

    @yield('style')
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/app.css?v=' . filemtime(public_path('css/app.css'))) }}" rel="stylesheet">

    @yield('script-head')
</head>
<body>
    @yield('content') 
    <script src="/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    @yield('script')
</body>
</html>