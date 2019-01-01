@extends('layouts.app')
@section('title', 'Welcome')
@section('content')

<el-row :gutter="0" style="height: 100vh;">
    <el-col style="height: 100vh;" :span="16">
    	<div style="display: flex; justify-content: center; align-items: center; background: #ecf0f1; height: 100vh;">
			<img src="{{ asset('images/teamwork.svg') }}" alt="a picture" style="">
    	</div>
    </el-col>
    <el-col style="height: 100vh; padding-top: 20px;" :span="8">
    	<welcome-component></welcome-component>
    </el-col>
</el-row>

@endsection
