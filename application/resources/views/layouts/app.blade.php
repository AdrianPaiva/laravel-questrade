<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
<body class="remove-margin">
    <div id="app">
        <el-container>
                @guest
                    <el-main class="remove-padding">
                        <el-row :gutter="0">
                            <el-col :span="24">
                                @yield('content')
                            </el-col>
                        </el-row>
                    </el-main>
                @else
                    <el-main class="remove-padding">
                        <el-row :gutter="0" class="fixed-sidebar-block">
                            <el-col :span="24">
                                <side-nav-component :is-collapse="!toggleMenu" username="{{ Auth::user()->full_name }}" companyname="Test name"></side-nav-component>
                            </el-col>
                        </el-row>
                        <el-row :gutter="0" class="app-main-content-block" :class="{'app-main-content-block-push' : toggleMenu}">
                            <el-col :span="24">
                                <el-row :gutter="0">
                                    <el-col :span="24" class="page-title-block">
                                        <el-checkbox v-model="toggleMenu" id="toggleMenuCheckbox">
                                            <i class="material-icons menu-icons" v-show="!toggleMenu">menu</i>
                                            <i class="material-icons menu-icons" v-show="toggleMenu">close</i>
                                        </el-checkbox>
                                        <h1 class="remove-margin" style="display: inline-block;">@yield('pageTitle')</h1>
                                    </el-col>
                                    <el-col :span="24">
                                        @yield('content')
                                    </el-col>
                                </el-row>
                            </el-col>
                        </el-row>
                    </el-main>
                @endguest
        </el-container>
    </div>

<script>
    window.Laravel = {!! json_encode([
        'user' => Auth::user(),
    ]) !!};
    let user_data = window.Laravel;
</script>
</body>
</html>
