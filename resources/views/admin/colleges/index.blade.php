@extends('layouts.admin')
@section('content')
<div class="block">
    <div class="block-header">
        <h3 class="block-title">院校列表</h3>
    </div>
    <div class="block-content">
        <form>
            <input type="text" name="college_name" value="{{ $college_name }}" placeholder="院校名称" />
            <select name="country_id">
              <option value="">所有国家</option>
              @foreach($countries as $country)
                <option value="{{ $country->id }}" @if($country_id == $country->id) selected="selected" @endif>{{ $country->name }}</option>
              @endforeach
            </select>
            <select name="examination_id">
                <option value="">所有规则</option>
                @foreach($examinations as $weight)
                    <option value="{{ $weight->id }}" @if($examination_id == $weight->id) selected="selected" @endif>[{{$weight->degree->name}} : {{ $weight->name }}]</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">查询</button>
            <a class="btn btn-primary" href="{{route('admin.colleges.create')}}">新增院校</a>
            <span>查询到{{count($colleges)}}条记录</span>
        </form>
        <table class="table table-striped table-borderless table-header-bg">
            <thead>
                <tr>
                    <th>已匹配规则</th>
                    <th>院校名称</th>
                    <th>英文名</th>
                    <th>国家</th>
                    <th>收藏数量</th>
                    <th class="text-center" style="width: 100px;">操作</th>
                </tr>
            </thead>
            <tbody>
              @foreach($colleges as $college)
              <tr>
                  <td>
                  @foreach($college->examinationScoreWeight as $weight)
                    [{{$weight->degree->name}} : {{ $weight->name }}]
                  @endforeach
                  </td>
                  <td>{{$college->chinese_name}}</td>
                  <td>{{ $college->english_name }}</td>
                  <td>{{ $college->country->name }}</td>
                  <td>{{ $college->like_nums }}</td>
                  <td class="text-center">
                      <div class="btn-group">
                          <a href="{{ route('admin.colleges.edit', $college->id) }}" class="btn btn-xs btn-default" >
                            修改院校
                          </a>

                          <a href="{{ route('admin.colleges.examination_score_map.index', $college->id) }}" class="btn btn-xs btn-default">
                            查看分数映射表
                          </a>
                          <a href="{{ route('admin.requirement.index', ['type' => get_class($college), 'id' => $college->id ]) }}" class="btn btn-xs btn-default">
                              设置申请要求
                          </a>
                          <a href="{{ route('admin.colleges.specialities.index', [ 'college' => $college->id ]) }}" class="btn btn-xs btn-default">
                              查看专业设置
                          </a>
                          <a href="{{ route('admin.articles.index', [ 'college_id' => $college->id ]) }}" class="btn btn-xs btn-default">
                              查看文章－页面设置
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
