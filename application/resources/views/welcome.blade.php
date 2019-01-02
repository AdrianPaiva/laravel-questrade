@extends('layouts.app')
@section('title', 'Welcome')
@section('content')

<el-row>
    <el-col :span="24">
    	<div>
			<img src="{{ asset('images/teamwork.svg') }}" alt="a picture" style="">
    	</div>
    </el-col>
</el-row>

@endsection
