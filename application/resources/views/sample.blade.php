<!DOCTYPE html>
<html lang = "{{ app()->getLocale() }}">
<head>
    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name = "csrf-token" content = "{{ csrf_token() }}">
    
    <title>ACTO - @yield('title')</title>
    
    <!-- Fonts -->
    <link rel = "dns-prefetch" href = "https://fonts.gstatic.com">
    <link href = "https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel = "stylesheet" type = "text/css">
    
    <!-- Styles -->
    <link href = "{{ mix('/css/app.css') }}" rel = "stylesheet">
    
    @yield('styles')
    
    <style></style>
</head>
<body>

<div id = "app">
    <el-container class = "wrapper">
        <el-header
            height = "80px"
            :style = "{ 'background-color': colors.primary }">
        </el-header>
        <el-container>
            <el-main class = "content">
            </el-main>
        </el-container>
    </el-container>
</div>

<!-- Scripts -->
<script src = "{{ mix('/js/app.js') }}"></script>
@yield('scripts.footer')
</body>
</html>
