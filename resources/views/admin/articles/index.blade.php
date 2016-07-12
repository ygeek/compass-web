@extends('layouts.admin')
@section('content')
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">@if($college){{$college->chinese_name}}@else系统@endif文章列表</h3>
            <a class="btn btn-primary" href="{{route('admin.articles.create', ['college_id' => $college_id])}}">新增文章</a>
        </div>
        <div class="block-content">
            <table class="table table-striped table-borderless table-header-bg">
                <thead>
                <tr>
                    <th >标题</th>
                    <th >分类</th>
                    <th >排序权重</th>
                    <th class="text-center" style="width: 100px;">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($articles as $article)
                    <tr>
                        <td>{{$article->title}}</td>
                        <td>{{$article->category->name}}</td>
                        <td>{{$article->order_weight}}</td>
                        <td class="text-center">
                            <a class="btn btn-primary" href="{{route('admin.articles.edit', ['id' => $article->id, 'college_id' => $college_id])}}">修改文章</a>
                            <form action="{{ URL::route('admin.articles.destroy', $article->id) }}" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-danger">删除文章</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $articles->render() }}
        </div>
    </div>
@endsection
