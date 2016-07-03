@extends('layouts.admin')
@section('content')
<div class="block">
    <div class="block-header">
        <h3 class="block-title">院校列表</h3>
    </div>
    <div class="block-content">
        <table class="table table-striped table-borderless table-header-bg">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th >院校名称</th>
                    <th class="hidden-xs" style="width: 15%;">Access</th>
                    <th class="text-center" style="width: 100px;">操作</th>
                </tr>
            </thead>
            <tbody>
              @foreach($colleges as $college)
              <tr>
                  <td class="text-center">1</td>
                  <td>{{$college->chinese_name}}</td>
                  <td class="hidden-xs">
                      <span class="label label-primary">Personal</span>
                  </td>
                  <td class="text-center">
                      <div class="btn-group">
                          <a href="{{ route('admin.colleges.edit', $college->id) }}" class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="修改院校">
                            <i class="fa fa-pencil"></i>
                          </a>
                          <a href="{{ route('admin.colleges.examination_score_map.index', $college->id) }}" class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="查看分数映射表">
                            <i class="fa fa-pencil"></i>
                          </a>
                          <a href="{{ route('admin.requirement.index', ['type' => get_class($college), 'id' => $college->id ]) }}" class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="设置申请要求">
                              <i class="fa fa-pencil"></i>
                          </a>
                          <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Remove Client">
                            <i class="fa fa-times"></i>
                          </button>
                      </div>
                  </td>
              </tr>
              @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
