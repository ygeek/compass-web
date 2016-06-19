@extends('layouts.app')

@section('content')
<div class="index-page">
  <div class="header">
    <div class="app-content">
      @include('shared.top_bar')

      <div class='evaluate-nav'>
        <h1>留学评估</h1>
        <ul>
          <li>
              <p>
                选择国家 <span>&gt;</span>
              </p>
          </li>
          <li>
            <p>
              选择学历 <span>&gt;</span>
            </p>
          </li>
          <li class="aboard-time">
              <p>
                计划留学时间<span>&gt;</span>
              </p>
              <p>2015.9-2018.6</p>
          </li>
        </ul>
        <button class='orange-btn'>我要评估</button>
      </div>
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
        <button>我要咨询</button>
      </div>
    </div>
  </div>

  <div class="footer"></div>
</div>
@endsection
