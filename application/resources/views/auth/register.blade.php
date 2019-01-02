@extends('layouts.app')

@section('content')
<el-row :gutter="0" class="login-box">
    <el-col :span="6" :offset="9">
        <!-- content -->
        <register-component></register-component>
    </el-col>
</el-row>
@endsection
