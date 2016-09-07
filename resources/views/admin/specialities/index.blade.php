@extends('layouts.admin')
@section('content')
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">{{$college->chinese_name}} 专业列表</h3>
            <a class="btn btn-primary" href="{{route('admin.colleges.index')}}">返回</a>
            <a class="btn btn-primary" href="{{route('admin.colleges.specialities.create', ['college' => $college->id])}}">新增专业</a>
        </div>
        <div class="block-content">
            <form>
                <input type="text" name="speciality_name" value="{{ $speciality_name }}" placeholder="专业名称" />
                <select name="category_id">
                    <option value="">所有方向</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" @if($category_id == $category->id) selected @endif>{{$category->english_name}} {{$category->chinese_name}} </option>
                    @endforeach
                </select>
                <select name="degree_id">
                    <option value="">所有层次</option>
                    @foreach($degrees as $degree)
                        <option value="{{$degree->id}}" @if($degree_id == $degree->id) selected @endif>{{$degree->name}}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">查询</button>
                <span>查询到{{ $specialities->total() }}条记录</span>
            </form>
            <table class="table table-striped table-borderless table-header-bg">
                <thead>
                <tr>
                    <th>专业名称</th>
                    <th>专业方向</th>
                    <th>专业层次</th>
                    <th class="text-center" style="width: 150px;">操作</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($specialities as $speciality)
                        <tr>
                            <td>
                                {{$speciality->name}}
                            </td>
                            <td>
                                {{$speciality->category->english_name}}
                                {{$speciality->category->chinese_name}}
                            </td>
                            <td>
                              {{$speciality->degree->name}}
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{route('admin.colleges.specialities.edit', ['college' => $college->id, 'specialities' => $speciality->id])}}">修改</a>

                                <a class="btn btn-xs btn-primary" href="{{ route('admin.requirement.index', ['type' => get_class($speciality), 'id' => $speciality->id ]) }}">设置申请要求</a>

                                <form action="{{ URL::route('admin.colleges.specialities.destroy', ['colleges' => $college->id, 'specialities' => $speciality->id]) }}" method="POST" onsubmit="return delete_confirm()">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-xs btn-danger">删除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $specialities->appends(app('request')->except('page'))->render() }}
        </div>
    </div>
    <script>
        function delete_confirm() {
            return confirm("确认删除该专业？");
        }
    </script>
@endsection
