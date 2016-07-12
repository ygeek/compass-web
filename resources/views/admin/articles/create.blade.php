@extends('layouts.admin')
@section('content')

    {!! Form::open(['url' => route('admin.articles.store', ['college_id' => $college_id]), 'method' => 'POST', 'class' => 'form-horizontal push-5-t']) !!}
    @include('admin.articles._form', ['article' => $article, 'categories' => $article_categories])
    <div class="form-group">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-plus push-5-r"></i>增加</button>
        </div>
    </div>
    {!! Form::close() !!}

@endsection
