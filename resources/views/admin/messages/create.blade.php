@extends('layouts.admin')

@section('content')
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">创建新消息</h3>
        </div>
        <div class="block-content">
            {!! Form::open(['route' => 'admin.messages.store', 'class' => 'form-horizontal']) !!}
            <div class="form-group">
                <div class="col-sm-9">
                    <div class="form-material floating">
                        <input class="form-control" type="text" id="material-text2" name="title" required>
                        <label for="material-text2">消息标题</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <div class="form-material floating">
                        <textarea required class="form-control" id="material-textarea-small2" name="content" rows="3"></textarea>
                        <label for="material-textarea-small2">消息内容</label>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary">发送消息</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
