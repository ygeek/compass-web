@extends('layouts.app')

@section('content')
    <phone-panel v-bind:show-change-phone-panel.sync="showChangePhonePanel"></phone-panel>
    <template id="phone-panel-template">
        <div id="phone-panel" v-show="showChangePhonePanel">
            <div class="mask"></div>
            <div class="panel">
                <div class="title">
                    <p>绑定新手机</p>
                </div>

                <div class="phone-form form">
                    <div class="row">
                        <label for="phone_number">手机号</label>
                        <input placeholder="" v-model="phone_number"/>
                        <p id="phone-warning" class="warning" v-show="phone_warning == true">手机号码格式不正确</p>
                    </div>
                    <div class="row">
                        <label for="phone_number">验证码</label>
                        <div class="code">
                            <input placeholder="" v-model="verify_code"/>
                            <button
                              @click="getVerifyCode"
                              v-bind:disabled="countDown"
                            >
                            <template v-if="countDown==null">
                              获取验证码
                            </template>
                            <template v-else>
                              @{{countDown}}s后重试
                            </template>
                            </button>
                        </div>
                        <p id="code-warning" class="warning" v-show="code_warning == true">验证码错误</p>
                    </div>
                    <div class="row clear">
                        <button class="change" @click="sendChangeRequest">绑&nbsp;定</button>
                        <span class="close" v-on:click="close">取&nbsp;消</span>
                    </div>
                </div>

            </div>
        </div>
    </template>

    <script>
        Vue.component('phone-panel', {
            template: '#phone-panel-template',
            props: ['showChangePhonePanel'],
            data: function(){
                return {
                    phone_warning: false,
                    code_warning: false,
                    phone_number: '',
                    verify_code: '',
                    countDown: null,
                    countDownInterval: null,
                };
            },
            methods: {
                close: function(e){
                    this.showChangePhonePanel = false;
                },
                togglePanel: function(panel){
                    this.panel = panel;
                },
                getVerifyCode: function(){
                    this.$http.post("{{route('auth.verifyCode.store')}}", {
                        phone_number: this.phone_number
                    }).then(function(response){
                        if(response.data.data.code){
                            console.log('验证码为：' + response.data.data.code);
                            this.verify_code = response.data.data.code;
                        }else{

                        }

                        var that = this;
                        that.countDown = 60;
                        that.countDownInterval = setInterval(function() {
                          that.countDown--;
                          if(that.countDown == 0) {
                            clearInterval(that.countDownInterval);
                            that.countDown = null;
                          }
                        }, 1000);

                    });
                },
                sendChangeRequest: function(){
                    var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
                    if (!reg.test(this.phone_number)) {
                        this.phone_warning = true;
                        return;
                    };
                    this.phone_warning = false;
                    this.code_warning = false;

                    this.$http.post("{{ route('home.change_phone') }}", {
                        phone_number: this.phone_number,
                        code: this.verify_code
                     }).then(function(response){
                        alert('修改成功');
                        window.location.reload();
                     }, function(response){
                        if(response.data.data.message=='验证码验证失败'){
                            this.code_warning = true;
                        }
                     });
                }
            },
        })
    </script>

    <div class="home-page">
        <div class="app-content">
            @include('shared.top_bar')

            <div class="page-content">
                @include('home.slider', ['active' => 'index'])

                <div class="home-content">
                    <div class="title">我的资料</div>
                    <div class="content">
                    {!!Form::open(['route' => 'home.store_profile', 'enctype' => 'multipart/form-data']) !!}
                        <div class="form-group">
                            <label>用户名</label>
                            <input name="username" type="text" value="{{ $user->username }}" />
                        </div>

                        <div class="form-group">
                            <label>邮箱</label>
                            <input name="email" type="email" value="{{ $user->email }}" />
                        </div>

                        <div class="form-group">
                            <label>头像</label>
                            <input type="file" name="avatar"/>
                        </div>
                        <button class="estimate-button">修改资料</button>
                    {!!Form::close() !!}
                    </div>

                    <div class="content">
                        <div class="form-group">
                            <label>手机号</label>
                            <span>{{$user->phone_number}}</span>
                        </div>
                        <button class="estimate-button" v-on:click="showChangePhonePanel=true">修改手机号</button>
                    </div>

                    <div class="content">
                        {!! Form::open(['route' => 'home.change_password']) !!}
                        <div class="form-group">
                            <label>当前密码</label>
                            <input type="password" name="old_password" required/>
                        </div>
                        <div class="form-group">
                            <label>新密码</label>
                            <input type="password" name="password" required/>
                        </div>
                        <div class="form-group">
                            <label>确认新密码</label>
                            <input type="password" name="password_confirmation" required/>
                        </div>

                        <button type="submit" class="estimate-button">修改密码</button>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')

@endsection
