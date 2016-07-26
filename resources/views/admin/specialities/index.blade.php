@extends('layouts.admin')
@section('content')
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">{{$college->chinese_name}} 专业列表</h3>
            <a class="btn btn-primary" href="{{route('admin.colleges.specialities.create', ['college' => $college->id])}}">新增专业</a>
        </div>
        <div class="block-content">
            <table class="table table-striped table-borderless table-header-bg">
                <thead>
                <tr>
                    <th>专业名称</th>
                    <th>专业层次</th>
                    <th class="text-center" style="width: 150px;">操作</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($college->specialities as $speciality)
                        <tr>
                            <td>
                                {{$speciality->name}}
                            </td>
                            <td>
                              {{$speciality->degree->name}}
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{route('admin.colleges.specialities.edit', ['college' => $college->id, 'specialities' => $speciality->id])}}">修改</a>

                                <a class="btn btn-xs btn-primary" href="{{ route('admin.requirement.index', ['type' => get_class($speciality), 'id' => $speciality->id ]) }}">设置申请要求</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
