@extends('layouts.admin')
@section('content')
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">意向列表</h3>
        </div>
        <div class="block-content">
            <form method="GET">
                <input type="text" name="name" placeholder="姓名" value="{{ $name }}" />
                <input type="text" class="date" name="start_date" placeholder="起始日期" value="{{ $start_date}}"/>
                <input type="text" class="date" name="end_date" placeholder="结束日期" value="{{ $end_date }}"/>
                <select name="state">
                    <option value="">所有状态</option>
                    <option value="unassigned" @if($state == 'unassigned') selected='selected' @endif>未分配</option>
                    <option value="assigned" @if($state == 'assigned') selected='selected' @endif>已分配</option>
                </select>
                <button>查询</button>
            </form>
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
                            @if($intention->state == 'unassigned')
                                未分配
                            @else
                                已分配
                            @endif
                        </td><td>
                            {{$intention->created_at}}
                        </td>
                        <td>
                            @if($intention->state == 'unassigned')
                            <form style="display:inline-block" action="{{ URL::route('admin.intentions.update', $intention->id) }}" method="POST">
                                <input type="hidden" name="_method" value="PATCH">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-primary">分配</button>
                            </form>
                            @endif

                            <form style="display:inline-block" action="{{ URL::route('admin.intentions.export_to_excel', $intention->id) }}" method="POST">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-primary">导出Excel</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $intentions->render() }}
        </div>
    </div>

    <script type="text/javascript">
        $('.date').datepicker({
            format: "yyyy-mm-dd",
            startView: 1,
            language: "zh_cn"
        });
    </script>
@endsection