@extends('layouts.admin')

@section('content')
@if(isset($value))
    @include('admin.setting.ranking_setting', ['value' => $value, 'name' => 'QS'])
@else
    @include('admin.setting.ranking_setting', ['name' => 'QS'])
@endif

@endsection