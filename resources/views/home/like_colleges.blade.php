@extends('layouts.app')

@section('content')
    <div class="home-page">
        <div class="app-content">
            @include('shared.top_bar')

            <div class="page-content">
                @include('home.slider', ['active' => 'like_college'])
                <div class="home-content">
                    <div class="title">我的收藏</div>
                    <div class="colleges">
                       @foreach($colleges as $college)
                           <div class="college">
                               <img class="college-badge" src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}" />
                               <div class="college-info">
                                   <header>
                                       <h1><a href="{{route('colleges.show', $college->key)}}">{{$college->chinese_name}}</a></h1>
                                       <h2>{{$college->english_name}}</h2>

                                       <div class="ielts-and-toelf-requirement">
                                           <span class="toelf-requirement">托福: {{ $college->toeflRequirement('本科') }}</span>
                                           <span class="ielts-requirement">托福: {{ $college->ieltsRequirement('本科') }}</span>
                                       </div>

                                       <div class="address-container">
                                           <div class="location">
                                               <img src="/images/location-identity.png" alt="location-identity" />
                                               {{$college->administrativeArea->name}}
                                               @if($college->administrativeArea->parent)
                                                   , {{$college->administrativeArea->parent->name}}
                                                   @if($college->administrativeArea->parent->parent)
                                                       , {{$college->administrativeArea->parent->parent->name}}
                                                   @endif
                                               @endif
                                           </div>

                                           <div class="address">
                                               {{ $college->address }}
                                           </div>
                                       </div>
                                   </header>

                                   <div class="college-rank-info">
                                       <table>
                                           <tr>
                                               <td>U.S.New排名:</td>
                                               <td>{{$college->us_new_ranking}}</td>
                                           </tr>
                                           <tr>
                                               <td>Times排名:</td>
                                               <td>{{$college->times_ranking}}</td>
                                           </tr>
                                           <tr>
                                               <td>QS排名:</td>
                                               <td>{{$college->qs_ranking}}</td>
                                           </tr>
                                           <tr>
                                               <td>本国排名:</td>
                                               <td>{{$college->domestic_ranking}}</td>
                                           </tr>
                                       </table>

                                       <a href="{{route('estimate.step_first')}}">测试录取几率-></a>
                                   </div>

                               </div>
                           </div>
                       @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')
@endsection
