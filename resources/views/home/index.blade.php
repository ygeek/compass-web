@extends('layouts.app')

@section('content')
    <div class="home-page">
        <div class="app-content">
            @include('shared.top_bar')

            <div class="page-content">
                @include('home.slider')

                <div class="home-content">
                    <div class="title">我的资料</div>

                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')
@endsection