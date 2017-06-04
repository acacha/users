<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('htmlheader_title', 'Your title here')</title>
    <link rel="stylesheet" type="text/css" href="/css/all-fullscreen.css" />
    @yield('htmlheader_css')
</head>
<body>
<div id="app">
    @yield('main-content')
</div>
<script src="/js/app-fullscreen.js"></script>
@yield('scripts')
</body>
</html>