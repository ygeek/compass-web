@extends('layouts.app')

@section('content')
  <div class="index-page">
    <div class="header">
      <div class="app-content">
        @include('shared.top_bar', ['page' => 'index'])

        <estimate-nav></estimate-nav>
        <template id='estimate-nav'>
        <form method="GET" action="{{ route('estimate.step_second') }}" v-on:mouseleave="selecting=null">
          <div class='evaluate-nav'>
          <h1>留学评估</h1>
          <ul class="select-item">
            <li class="estimate-val" v-bind:class="{'active': selecting=='countries'}" v-on:mouseenter="selecting='countries'">
              <p>
                选择国家 <span><img src="/images/right-arrow.png" /></span>
              </p>
              <p>@{{ selected_countries.name }}</p>
              <input type="hidden" name="selected_country_id" v-model='selected_countries.id'>
            </li>
            <li class="estimate-val" v-bind:class="{'active': selecting=='degrees'}" v-on:mouseenter="selecting='degrees'">
              <p>
                选择学历 <span><img src="/images/right-arrow.png" /></span>
              </p>
              <p>@{{ selected_degrees.name }}</p>
              <input type="hidden" name="selected_degree_id" v-model='selected_degrees.id'>
            </li>
            <li class="estimate-val" v-bind:class="{'active': selecting=='years'}" v-on:mouseenter="selecting='years'">
              <p>
                计划留学时间<span><img src="/images/right-arrow.png" /></span>
              </p>
              <p>@{{ selected_years.name }}</p>
              <input type="hidden" name="selected_year" v-model='selected_years.id'>
            </li>
            <li class="estimate-val" v-bind:class="{'active': selecting=='speciality_categories'}" v-on:mouseenter="selecting='speciality_categories'">
              <p>
                期望就读专业<span><img src="/images/right-arrow.png" /></span>
              </p>
              <p>@{{ selected_speciality_categories.name }}</p>
              <input type="hidden" name="speciality_category_id" v-model='selected_speciality_categories.id'>
            </li>
            <li class="estimate-val" v-bind:class="{'active': selecting=='speciality_name'}" v-on:mouseenter="selecting='speciality_name'">
              <p>
                细分专业<span><img src="/images/right-arrow.png" /></span>
              </p>
              <p>@{{ selected_speciality_name.name }}</p>
              <input type="hidden" name="speciality_name" v-model='selected_speciality_name.name'>
            </li>
          </ul>
          <button class='orange-btn' v-on:click="onSubmit">我要评估</button>

          <div class="select-area" v-show="selecting">
            <ul>
              <li v-for="select_item in selectes" @click="select(select_item)">
                @{{ select_item.name }}
              </li>
            </ul>
          </div>
        </div>
        </form>
        </template>
        <script type="text/javascript">
          Vue.component('estimate-nav', {
            template: '#estimate-nav',
            data: function(){
              return {
                countries: {!! json_encode($countries->toArray()) !!},
                degrees: {!! json_encode($degrees->toArray()) !!},
                years: {!! json_encode($years)!!},
                speciality_categories: {!! json_encode($speciality_categories->toArray()) !!},

                selected_countries: {name: null, id: null},
                selected_degrees: {name: null, id: null},
                selected_years: {name: null, id: null},
                selected_speciality_categories: {name: null, id: null},
                selected_speciality_name: null,
                selecting: null
              }
            },
            computed: {
              selectes: function(){
                if(this.selecting=="speciality_name"){
                  var values = this.children;
                  return values;
                } else if(this.selecting){
                  var values = this[this.selecting];
                  return values;
                }
                else{
                  return [];
                }
              },
              children: function () {
                this.selected_speciality_name = null;
                var that = this;
                for(var i=0; i<this.speciality_categories.length; i++){
                  if(this.speciality_categories[i].id == this.selected_speciality_categories.id){
                    var res = this.speciality_categories[i].specialities.filter(function (speciality) {
                      return speciality.degree_id == that.selected_degrees.id && speciality.country_id == that.selected_countries.id;
                    });
                    return res;
                  }
                }
                return [];
              }
            },
            methods: {
              select: function(selected){
                var key = 'selected_' + this.selecting;
                console.log(key);
                this[key] = selected;
              },
              onSubmit: function (msg) {
                if (this.selected_countries.id==null||this.selected_degrees.id==null||this.selected_years.id==null||this.selected_speciality_categories.id==null||this.selected_speciality_name==null){
                  alert('有选项未选择。');
                  event.preventDefault();
                }
              }
            }
          })
        </script>

      </div>

      <div class='slogan-word'></div>
    </div>

    <div class="aboard-flow">
      <h1>留学流程</h1>
      <ul>
        <li>
          <div class='flow-detail'>
            <img src="/images/flow1.png" alt='flow1'></img>
            <p>
            <span class='index'>
              1.
            </span>
              在线留学评估
            </p>
          </div>
        </li>
        <li>
          <div class='flow-detail'>
            <img src="/images/flow2.png" alt='flow2'></img>
            <p>
            <span class='index'>
              2.
            </span>
              专家电话复核
            </p>
          </div>
        </li>
        <li>
          <div class='flow-detail'>
            <img src="/images/flow3.png" alt='flow3'></img>
            <p>
            <span class='index'>
              3.
            </span>
              办公室签约
            </p>
          </div>
        </li>
        <li>

          <div class='flow-detail'>
            <img src="/images/flow4.png" alt='flow4'></img>
            <p>
            <span class='index'>
              4.
            </span>
              线下顾问为您院校申请
            </p>
          </div>
        </li>
        <li>
          <div class='flow-detail'>
            <img src="/images/flow5.png" alt='flow5'></img>
            <p>
            <span class='index'>
              5.
            </span>
              线下顾问为您办理签证
            </p>
          </div>
        </li>
        <li>
          <div class='flow-detail'>
            <img src="/images/flow6.png" alt='flow6'></img>
            <p>
            <span class='index'>
              6.
            </span>
              海外公司为您提供后续服务
            </p>
          </div>
        </li>
      </ul>
    </div>

    <div class="middle">
      <div class='app-content'>
        <div class='consult'>
          <img src="/images/msg_ic.png" alt="msg_ic" />
          <button onclick='easemobim.bind({tenantId: 21250})'>我要咨询</button>
          <div class="questions">
            <p>常见问题：</p>
            <?php
              $articles = App\Article::whereHas('category', function($q){
                return $q->where('key', 'chang-jian-wen-ti');
            })->orderBy('articles.order_weight')->limit(6)->get();
            ?>
            @foreach($articles as $article)
            <a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a>
            @endforeach
          </div>
        </div>
        <div class="consult-academy clear">

          <div class="location">
            @foreach($countries as $country)
            <div class="clear">
              <h3>{{ $country->name }}</h3>
              <?php
                $index = 0;
              ?>
              @foreach($country->children as $state)
                <?php if($index ==8){
                ?>
                  <a target="_blank" href="{{ route('colleges.index', ['selected_country_id' => $country->id]) }}">更多</a>
                <?php
                break;}?>
                <a target="_blank" href="{{ route('colleges.index', ['selected_country_id' => $country->id, 'selected_state_id' => $state->id]) }}">{{ $state->name }}</a>
                <?php $index++; ?>
              @endforeach
            </div>
            @endforeach
          </div>
          <div class="academy">
            <div class="search clear">
              <form method="GET" action="{{ route('colleges.index') }}">
                <input type="text" placeholder="院校查询" class="search-text" name="college_name"/>
                <button type="submit" class="search-button"></button>
              </form>
            </div>
            <div class="subjects clear">
              <a target="_blank" href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 2]) }}" style="background: url('/images/p1.png');">商&nbsp;科</a>
              <a target="_blank" href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 3]) }}" style="background: url('/images/p2.png')">工&nbsp;科</a>
              <a target="_blank" href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 4]) }}" style="background: url('/images/p3.png')">人文艺术</a>
              <a target="_blank" href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 2]) }}" style="background: url('/images/p4.png')">经济金融</a>
              <a target="_blank" href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 6]) }}" style="background: url('/images/p5.png')">医&nbsp;学</a>
              <a target="_blank" href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 9]) }}" style="background: url('/images/p6.png')">法&nbsp;学</a>
              <a target="_blank" href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 8]) }}" style="background: url('/images/p7.png')">理&nbsp;科</a>
              <a target="_blank" href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 1]) }}" style="background: url('/images/p8.png')">人文艺术</a>
              <a target="_blank" href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 2]) }}" style="background: url('/images/p9.png')">经济金融</a>
            </div>
          </div>
        </div>
        <div class="link">
          <?php
            $more = App\Setting::get('index_more', ['#','#','#']);
          ?>
          <div class="text-box lang-study">
            <div class="clear">
              <h3>语言学习</h3>
              <a href="{{ $more[0] }}" class="more" target="_blank">更多></a>
            </div>
            <?php
              $articles = App\Article::whereHas('category', function($q){
                return $q->where('key', 'yu-yan-xue-xi');
            })->orderBy('articles.order_weight')->limit(7)->get();
            ?>
            @foreach($articles as $article)
            <a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a>
            @endforeach
          </div>
          <img class="img-1" src="/images/i1.jpg" alt="img" />
          <div class="text-box study-abroad">
            <div class="clear">
              <h3>留学攻略</h3>
              <a href="{{ $more[1] }}" class="more" target="_blank">更多></a>
            </div>
            <?php
              $articles = App\Article::whereHas('category', function($q){
                return $q->where('key', 'liu-xue-gong-lue');
            })->whereNull('college_id')->orderBy('articles.order_weight')->limit(7)->get();
            ?>
            @foreach($articles as $article)
            <a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a>
            @endforeach
          </div>
          <img class="img-2" src="/images/i2.jpg" alt="img" />
          <div class="text-box immigrant">
            <div class="clear">
              <h3>移民攻略</h3>
              <a href="{{ $more[2] }}" class="more" target="_blank">更多></a>
            </div>
            <?php
              $articles = App\Article::whereHas('category', function($q){
                return $q->where('key', 'yi-min-gong-lue');
            })->orderBy('articles.order_weight')->limit(7)->get();
            ?>
            @foreach($articles as $article)
            <a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="assess">
      <h1>已有<span>{{\App\Setting::get('abroad_people_nums', 0)}}</span>人使用过指南针出国</h1>
      <h1>有<span>{{\App\Setting::get('use_people_nums', 0)}}</span>人使用了指南针智能评估系统</h1>
      <a style='color:#fff' href="{{route('estimate.step_first')}}"><button>开始免费评估</button></a>
    </div>
    @include('shared.footer')
  </div>
@endsection
