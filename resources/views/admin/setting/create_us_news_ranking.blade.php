@extends('layouts.admin')

@section('content')

@if(isset($value))
    @include('admin.setting.ranking_setting', ['value' => $value, 'name' => 'U.S.News'])
@else
    @include('admin.setting.ranking_setting', ['name' => 'U.S.News'])
@endif

@endsection