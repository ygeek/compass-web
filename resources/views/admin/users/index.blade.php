@extends('layouts.admin')
@section('content')
<div class="block">
    <div class="block-header">
        <h3 class="block-title">用户列表</h3>
    </div>
    <div class="block-content">
        <table class="table table-striped table-borderless table-header-bg">
            <thead>
                <tr>
                    <th>手机号</th>
                    <th>基础信息</th>
                    <th class="text-center" style="width: 100px;">操作</th>
                </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
              <tr>
                  <td>{{$user->phone_number}}</td>
                  <td>
                      姓名：{{ $user->name }}<br/>
                      email：{{ $user->email }}<br/>
                      头像地址：{{ $user->avatar_path }}<br/>
                      用户名：{{ $user->username }}<br/>
                  </td>
                  <td class="text-center">
                      <div class="btn-group">
                          <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-xs btn-default" >
                              修改
                          </a>
                      </div>
                  </td>
              </tr>
              @endforeach
            </tbody>
        </table>
        {{ $users->appends(app('request')->except('page'))->render() }}
    </div>
</div>
@endsection
