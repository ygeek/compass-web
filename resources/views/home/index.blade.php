@extends('layouts.app')

@section('content')
    <div class="home-page">
        <div class="app-content">
            @include('shared.top_bar')

            <div class="page-content">
                @include('home.slider')

                <div class="home-content">
                    <div class="title">我的资料</div>
                    <div class="content">
                    {!!Form::open(['route' => 'home.store_profile', 'enctype' => 'multipart/form-data']) !!}
                        <div class="form-group">
                            <label>用户名</label>
                            <input name="username" type="text" value="{{ $user->username }}" />
                        </div>

                        <div class="form-group">
                            <label>邮箱</label>
                            <input name="email" type="email" value="{{ $user->email }}" />
                        </div>

                        <div class="form-group">
                            <label>头像</label>
                            <input type="file" name="avatar"/>
                        </div>
                        <button class="estimate-button">修改资料</button>
                    {!!Form::close() !!}
                    </div>

                    <div class="content">
                        <div class="form-group">

                            <label>手机号</label>
                            <span>{{$user->phone_number}}</span>
                        </div>
                        <button class="estimate-button">修改手机号</button>
                    </div>

                    <div class="content">
                        {!! Form::open(['route' => 'home.change_password']) !!}
                        <div class="form-group">
                            <label>当前密码</label>
                            <input type="password" name="old_password" required/>
                        </div>
                        <div class="form-group">
                            <label>新密码</label>
                            <input type="password" name="password" required/>
                        </div>
                        <div class="form-group">
                            <label>确认新密码</label>
                            <input type="password" name="password_confirmation" required/>
                        </div>

                        <button type="submit" class="estimate-button">修改密码</button>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')
@endsection