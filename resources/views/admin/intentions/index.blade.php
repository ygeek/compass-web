@extends('layouts.admin')
@section('content')
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">意向列表</h3>
        </div>
        <div class="block-content">
            <table class="table table-striped table-borderless table-header-bg">
                <thead>
                <tr>
                    <th>用户姓名</th>
                    <th>状态</th>
                    <th>提交时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($intentions as $intention)
                    <tr>
                        <td>
                            {{$intention->name}}
                        </td>
                        <td>
                            {{$intention->state}}
                        </td><td>
                            {{$intention->created_at}}
                        </td>
                        <td>
                            <button class="btn btn-primary">分配</button>
                            <button class="btn btn-primary">导出Excel</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $intentions->render() }}
        </div>
    </div>
@endsection