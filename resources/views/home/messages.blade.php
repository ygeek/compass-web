@extends('layouts.app')

@section('content')
    <div class="home-page">
        <div class="app-content">
            @include('shared.top_bar')

            <div class="page-content">
                @include('home.slider', , ['active' => 'messages'])
                <div class="home-content">
                    <div class="title">我的消息</div>
                    <div class="content">
                        <table class="message-table">
                            @foreach($messages['data'] as $message)
                                <tr class="message">
                                    <td class="message-title">{{$message['title']}}</td>
                                    <td class="message-content">{{$message['content']}}</td>
                                    <td class="created_at">{{$message['created_at']}}</td>
                                    <td class="actions">
                                        {!! Form::open(['url' => route('home.messages.read', $message['id']), 'method' => 'PATCH']) !!}
                                            <button style="border: none; background: none">x</button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')
@endsection
