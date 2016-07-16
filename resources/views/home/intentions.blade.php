@extends('layouts.app')

@section('content')
    <div class="home-page">
        <div class="app-content">
            @include('shared.top_bar')

            <div class="page-content">
                @include('home.slider')
                <div class="home-content">
                    <div class="title">我的意向单</div>
                    <div class="content">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection