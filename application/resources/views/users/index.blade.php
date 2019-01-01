@extends('layouts.app')

@section('title', 'Users')
@section('pageTitle', 'Users')

@section('content')
<el-row :gutter="0">
	<el-col :span="24">
		<users-component></users-component>
	</el-col>
</el-row>
@endsection