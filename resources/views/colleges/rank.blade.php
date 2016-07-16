@extends('layouts.app')

@section('content')
    <div class="colleges-rank-page">
        <div class="app-content">
            @include('shared.top_bar')
            <ul class="tabs">
                <a href="{{ route('colleges.rank') }}"><li class="tab @if($rank == 'us_new_ranking') active @endif">
                    U.S.News排名
                </li></a>
                <a href="{{ route('colleges.rank', ['rank' => 'qs_ranking']) }}"><li class="tab @if($rank == 'qs_ranking') active @endif">
                    QS排名
                </li></a>
                <a href="{{ route('colleges.rank', ['rank' => 'times_ranking']) }}"><li class="tab @if($rank == 'times_ranking') active @endif">
                    Times排名
                </li></a>
            </ul>
        </div>
    </div>

    <div class="colleges-rank-page-content">
        <div class="app-content">
            <table class="colleges">
                @foreach($colleges as $college)
                    <tr>
                    <td class="rank">{{ $college['rank'] }}</td>
                    <td class="chinese_name">{{ $college['chinese_name'] }}</td>
                    <td class="english_name">{{ $college['english_name'] }}</td>
                    <td class="actions">@if($college['key'])<a href="{{ route('estimate.step_first') }}"><button class="estimate-button">测试录取率 -></button></a>@endif</td>
                    </tr>
                @endforeach
            </table>
            {{ $colleges->appends(app('request')->except('page'))->render() }}
        </div>
    </div>

    @include('shared.footer')
@endsection