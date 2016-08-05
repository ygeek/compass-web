@extends('layouts.app')

@section('content')
    <div class="colleges-rank-page">
        <div class="app-content">
            @include('shared.top_bar')
            <ul class="tabs">
                @foreach($rankings_for_show as $ranking)
                  <a href="{{ route('colleges.rank', ['category_id' => $selected_category_id, 'ranking_id' => $ranking['_id']]) }}">
                    <li class="tab @if($selected_ranking_id == $ranking['_id']) active @endif">
                        {{$ranking['name']}}
                    </li>
                  </a>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="colleges-rank-page-content">
        <div class="app-content">

            <div class="rank-left">
                <div class="rank-list">
                    @foreach($ranking_categories as $category)
                        @include('colleges.rank_category', ['category' => $category, 'level' => 1, 'selected_category_id' => $selected_category_id])
                    @endforeach
                </div>
                <div style="padding-top: 20px;">
                    @include('colleges.sidebar')
                </div>
            </div>

            <div class="rank-content">
                <table class="colleges">
                    @foreach($colleges as $college)
                        <tr>
                        <td class="rank">{{ $college['rank'] }}</td>
                        <td class="chinese_name">
                          @if($college['key'])
                            <a style="color:#000" target="_blank" href="{{route('colleges.show', ['key' => \App\College::generateKey($college['key']) ])}}">
                              {{ $college['chinese_name'] }}
                            </a>
                          @else
                            {{ $college['chinese_name'] }}
                          @endif
                        </td>
                        <td class="english_name">{{ $college['english_name'] }}</td>
                        @if(isset($college['world_ranking']))
                        <td class="english_name">世界排名: {{$college['world_ranking']}}</td>
                        @endif
                        <td class="actions">@if($college['key'])<a href="{{ route('estimate.step_first') }}"><button class="estimate-button">测试录取率 -></button></a>@endif</td>
                        </tr>
                    @endforeach
                </table>

                <div class="pagination">
                  {{ $colleges->appends(app('request')->except('page'))->render() }}
                </div>
            </div>

            <?php unset($college) ?>
        </div>
    </div>

    @include('shared.footer')
@endsection
