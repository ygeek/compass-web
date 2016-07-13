@extends('layouts.app')

@section('content')
  <div class="index-page">
    <div class="header">
      <div class="app-content">
        @include('shared.top_bar')

        <estimate-nav></estimate-nav>
        <template id='estimate-nav'>

        <form method="GET" action="{{ route('estimate.step_first') }}">
          <div class='evaluate-nav'>
          <h1>留学评估</h1>
          <ul class="select-item">
            <li class="estimate-val" v-on:mouseover="selecting='countries'">
              <p>
                选择国家 <span>&gt;</span>
              </p>
              <p>@{{ selected_countries.name }}</p>
              <input type="hidden" name="selected_country_id" v-model='selected_countries.id'>
            </li>
            <li class="estimate-val" v-on:mouseover="selecting='degrees'">
              <p>
                选择学历 <span>&gt;</span>
              </p>
              <p>@{{ selected_degrees.name }}</p>
              <input type="hidden" name="selected_degree_id" v-model='selected_degrees.id'>
            </li>
            <li class="estimate-val" v-on:mouseover="selecting='years'">
              <p>
                计划留学时间<span>&gt;</span>
              </p>
              <p>@{{ selected_years.name }}</p>
              <input type="hidden" name="selected_year" v-model='selected_years.id'>
            </li>
          </ul>
          <button class='orange-btn'>我要评估</button>

          <div class="select-area" v-show="selecting">
            <ul>
              <li v-for="select_item in selectes" @click="select(select_item)">
                @{{ select_item.name }}
              </li>
            <ul>
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

                selected_countries: {name: null, id: null},
                selected_degrees: {name: null, id: null},
                selected_years: {name: null, id: null},
                selecting: null
              }
            },
            computed: {
              selectes: function(){
                if(this.selecting){
                  var values = this[this.selecting];
                  return values;
                }else{
                  return [];
                }
              }
            },
            methods: {
              select: function(selected){
                var key = 'selected_' + this.selecting;
                console.log(key);
                this[key] = selected;
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
          <button>我要咨询</button>
          <div class="questions">
            <p>常见问题：</p>
            <a href="#">此处20字此处20字此处20字此处20字此处20字此处20字此处20字此处20字</a>
            <a href="#">此处20字此处20字此处20字此处20字此处20字此处20字此处20字此处20字</a>
            <a href="#">此处20字此处20字此处20字此处20字此处20字此处20字此处20字此处20字</a>
            <a href="#">此处20字此处20字此处20字此处20字此处20字此处20字此处20字此处20字</a>
            <a href="#">此处20字此处20字此处20字此处20字此处20字此处20字此处20字此处20字</a>
            <a href="#">此处20字此处20字此处20字此处20字此处20字此处20字此处20字此处20字</a>
          </div>
        </div>
        <div class="consult-academy clear">
          <div class="location">
            <div class="clear">
              <h3>澳大利亚</h3>
              <a href="#">悉尼</a>
              <a href="#">惠森迪</a>
              <a href="#">墨尔本</a>
              <a href="#">西澳</a>
              <a href="#">布里斯本</a>
              <a href="#">凯恩斯</a>
              <a href="#">堪培拉</a>
            </div>
            <div class="clear">
              <h3>美国</h3>
              <a href="#">芝加哥</a>
              <a href="#">旧金山</a>
              <a href="#">纽约</a>
              <a href="#">费城</a>
              <a href="#">华盛顿</a>
              <a href="#">底特律</a>
              <a href="#">西雅图</a>
              <a href="#">波斯顿</a>
            </div>
            <div class="clear">
              <h3>新西兰</h3>
              <a href="#">芝加哥</a>
              <a href="#">旧金山</a>
              <a href="#">纽约</a>
              <a href="#">费城</a>
              <a href="#">华盛顿</a>
            </div>
            <div class="clear">
              <h3>英国</h3>
              <a href="#">伦敦</a>
              <a href="#">南安普顿</a>
              <a href="#">纽约</a>
              <a href="#">费城</a>
              <a href="#">华盛顿</a>
            </div>
          </div>
          <div class="academy">
            <div class="search clear">
              <input type="text" placeholder="院校查询" class="search-text" />
              <input type="button" class="search-button" />
            </div>
            <div class="subjects clear">
              <a href="#" style="background: url('/images/p1.png');">商&nbsp;科</a>
              <a href="#" style="background: url('/images/p2.png')">工&nbsp;科</a>
              <a href="#" style="background: url('/images/p3.png')">人文艺术</a>
              <a href="#" style="background: url('/images/p4.png')">经济金融</a>
              <a href="#" style="background: url('/images/p5.png')">医&nbsp;学</a>
              <a href="#" style="background: url('/images/p6.png')">法&nbsp;学</a>
              <a href="#" style="background: url('/images/p7.png')">理&nbsp;科</a>
              <a href="#" style="background: url('/images/p8.png')">人文艺术</a>
              <a href="#" style="background: url('/images/p9.png')">经济金融</a>
              <a href="#" style="background: url('/images/p10.jpg')">工&nbsp;科</a>
              <a href="#" class="more">更多专业</a>
            </div>
          </div>
        </div>
        <div class="link">
          <div class="text-box lang-study">
            <div class="clear">
              <h3>语言学习</h3>
            </div>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">更多></a>
          </div>
          <img class="img-1" src="/images/i1.jpg" alt="img" />
          <div class="text-box study-abroad">
            <div class="clear">
              <h3>留学攻略</h3>
              <a href="#" class="more">更多></a>
            </div>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
          </div>
          <img class="img-2" src="/images/i2.jpg" alt="img" />
          <div class="text-box immigrant">
            <div class="clear">
              <h3>移民攻略</h3>
              <a href="#" class="more">更多></a>
            </div>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
            <a href="#">世界大学牙科医学学科排名汇总！</a>
          </div>
        </div>
      </div>
      <div class="assess">
        <h1>已有<span>6513</span>人使用过指南针出国</h1>
        <h1>有<span>542867</span>人使用了指南针智能评估系统</h1>
        <button>开始免费评估</button>
      </div>
    </div>

    <div class="footer"></div>
  </div>
@endsection
