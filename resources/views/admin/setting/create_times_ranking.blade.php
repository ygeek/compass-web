@extends('layouts.admin')

@section('content')

@if(isset($value))
    @include('admin.setting.ranking_setting', ['value' => $value, 'name' => 'Times'])
@else
    @include('admin.setting.ranking_setting', ['name' => 'Times'])
@endif

@endsection