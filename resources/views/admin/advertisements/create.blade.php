@extends('layouts.admin')
@section('content')
    @include('admin.advertisements._form', ['advertisement' => $advertisement])
@endsection
