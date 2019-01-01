@extends('layouts.app')

@section('title', 'Login')
@section('content')

<el-row :gutter="0" class="login-box">
    <el-col :span="6" :offset="9">
        <!-- content -->
        <login-component></login-component>
    </el-col>
</el-row>

@endsection
