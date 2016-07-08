@extends('layouts.admin')
@section('content')
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">消息列表</h3>
            <a class="btn btn-primary" href="{{route('admin.messages.create')}}">创建新消息</a>
        </div>
        <div class="block-content">
            <table class="table table-striped table-borderless table-header-bg">
                <thead>
                <tr>
                    <th>消息ID</th>
                    <th>消息标题</th>
                    <th>消息内容</th>
                    <th>发送时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($messages['data'] as $message)
                    <tr>
                        <td>
                            {{$message['id']}}
                        </td>    <td>
                            {{$message['title']}}
                        </td><td>
                            {{$message['content']}}
                        </td><td>
                            {{$message['created_at']}}
                        </td>
                        <td>
                            <form action="{{ URL::route('admin.messages.destroy', $message['id']) }}" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button>删除消息</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection