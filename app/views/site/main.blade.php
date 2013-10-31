<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name='yandex-verification' content='65d29bfe2194b660' />
    <meta name="description" content=""></meta>
    <link rel="shortcut icon" href="/favicon.ico" /> 
    <script src="//code.jquery.com/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/all.css">
</head>
<body>
    <div class="wrapper">
        @include('site.top')
        
        @yield('content')
        
        @include('site.footer')
    </div>
</body>
</html>