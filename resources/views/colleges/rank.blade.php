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
                    @include('shared.advertisements', ['tag' => 'page_colleges_rank', 'test_rate' => false, 'rank' => false])
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
                        @if(isset($college['world_ranking']) && ($college['world_ranking'] > 0 || $college['world_ranking'] == '-'))
                        <td class="english_name">世界排名: {{$college['world_ranking']}}</td>
                        @endif
                        <td class="actions">@if($college['key'])
                                <?php
                                    $college = \App\College::where('key', \App\College::generateKey($college['key']))->first();
                                    $tmp = "";
                                    if ($college!=null){
                                        $tmp = $college->administrativeArea->id;
                                        if ($college->administrativeArea->parent){
                                            $tmp = $college->administrativeArea->parent->id;
                                            if ($college->administrativeArea->parent->parent){
                                                $tmp = $college->administrativeArea->parent->parent->id;
                                            }
                                        }
                                    }
                                ?>
                                @if($college != null)
                                    <a href="javascript:void(0)" v-on:click="setEstimatePanel('{{route('estimate.step_first', ['selected_country_id' => $tmp, 'cpm' => true, 'college_id' => $college['id']])}}')"><button class="estimate-button">测试录取率 -></button></a>@endif</td>
                                @endif
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

    @include('shared.estimate')

    @include('shared.footer')
@endsection
