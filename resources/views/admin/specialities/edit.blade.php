@extends('layouts.admin')
@section('content')

    <div class="row">
        <!-- Bootstrap Register -->
        <div class="block block-themed">
            <div class="block-header bg-success">
                <ul class="block-options">
                    <li>
                        <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                    </li>
                    <li>
                        <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">修改 {{$college->chinese_name}} 专业</h3>
            </div>
            <div class="block-content">
                {!! Form::open(['route' => ['admin.colleges.specialities.update', $college->id, $speciality->id], 'method' => 'PATCH', 'class' => 'form-horizontal push-5-t']) !!}
                @include('admin.specialities._form', ['speciality' => $speciality, 'degrees' => $degrees, 'categories' => $categories])
                <div class="form-group">
                    <div class="col-xs-12">
                        <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-plus push-5-r"></i> 修改</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
