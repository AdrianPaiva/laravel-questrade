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
            <el-main style="padding: 0px;">
                @if (Route::has('login'))
                    <el-row>
                        <el-col :span="24">
                            <el-menu
                                :default-active="activeIndex2"
                                class="el-menu-demo"
                                mode="horizontal"
                                background-color="#545c64"
                                text-color="#fff"
                                active-text-color="#ffd04b">

                                <el-menu-item index="1"><a href="{{ url('/') }}">Home</a></el-menu-item>
                                @guest
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"><el-menu-item index="3" style="float:right;">Register</el-menu-item></a>
                                    @endif

                                    <a href="{{ route('login') }}"><el-menu-item index="2" style="float:right;">Login</el-menu-item></a>
                                @endguest
                            </el-menu>
                        </el-col>
                    </el-row>
                @endif

                @guest
                    <el-row :gutter="0">
                        <el-col :span="24">
                            @yield('content')
                        </el-col>
                    </el-row>
                @else
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
                @endguest
            </el-main>
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
