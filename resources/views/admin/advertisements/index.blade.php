@extends('layouts.admin')
@section('content')
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">广告列表</h3>
            <a class="btn btn-primary" href="{{route('admin.advertisements.create')}}">新增广告</a>
        </div>
        <div class="block-content">
            <table class="table table-striped table-borderless table-header-bg">
                <thead>
                <tr>
                    <th >图片</th>
                    <th >链接</th>
                    <th >排序权重</th>
                    <th >显示页面</th>
                    <th class="text-center" style="width: 100px;">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($advertisements as $advertisement)
                    <tr>
                        <td><img src="{{app('qiniu_uploader')->pathOfKey($advertisement->background_image_path)}}" width="100px;"/></td>
                        <td>{{$advertisement->link}}</td>
                        <td>{{$advertisement->priority}}</td>
                        <td>
                            {{$advertisement->page_colleges_index?"| 院校查询页面":""}}
                            {{$advertisement->page_colleges_show?"| 院校详情页面":""}}
                            {{$advertisement->page_colleges_rank?"| 院校排行榜页面":""}}
                            {{$advertisement->page_estimate_index?"| 评估结果页面":""}}
                        </td>
                        <td class="text-center">
                            <a class="btn btn-primary" href="{{route('admin.advertisements.edit', ['id' => $advertisement->id])}}">修改广告</a>
                            <form action="{{ URL::route('admin.advertisements.destroy', $advertisement->id) }}" method="POST" onsubmit="return delete_confirm()">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-danger">删除广告</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $advertisements->appends(app('request')->except('page'))->render() }}
        </div>
    </div>
    <script>
        function delete_confirm() {
            return confirm("确认删除该广告？");
        }
    </script>
@endsection
