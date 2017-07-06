<login-panel v-bind:show-login-and-register-panel.sync="showLoginAndRegisterPanel"></login-panel>
<template id="login-panel-template">
  <div id="login-panel" v-show="showLoginAndRegisterPanel" style="z-index: 999999;">
    <div class="mask"></div>
    <div class="panel">
      <div class="close" v-on:click="close">
        X
      </div>
      <div class="form-nav">
        <ul>
          <li v-bind:class="{'active': panel == 'login'}" v-on:click="togglePanel('login')">
            登录
          </li>
          <li v-bind:class="{'active': panel == 'register'}" v-on:click="togglePanel('register')">
            注册
          </li>
        </ul>
      </div>
      <div class="login-form form" v-show="panel == 'login'">
        <input placeholder="手机号码" v-model="phone_number"/>
        <input placeholder="密码" type="password" v-model='password'/>
        <button class="button" @click="sendLoginRequest" v-bind:disabled="!canLogin">登录</button>

          <a href="{{route('auth.reset_passwrod')}}">
            <p class="tips" style="text-align:right; padding-right: 20px; cursor: pointer;">
              忘记密码？找回密码
            </p>
          </a>
      </div>

      <div class="register-form form" v-show="panel == 'register'">
        <p>
          <select v-model="phone_country" style="width: 20%;float: left;height: 44px;border: none;background: #fff;margin-left: 22px;">
            <option value="china">中国</option>
            <option value="aus">澳洲</option>
            <option value="nzl">新西兰</option>
          </select>
          <input style="float: left; width: 58%;" placeholder="手机号码" v-model="phone_number"/>
        </p>
        <p>
          <input placeholder="密码" type="password" v-model="password"/>
        </p>
        <p class='sms-container'>
          <input placeholder="短信验证" class="sms-input" v-model="verify_code"/>
          <button
            class='grab-verify-code'
            @click="getVerifyCode"
            v-bind:disabled="countDown"
          >
            <template v-if="countDown==null">
              <template v-if="onRequest">
                获取中
              </template>
              <template v-else>
                获取验证码
              </template>
            </template>
            <template v-else>
              @{{countDown}}s后重试
            </template>
          </button>
        </p>
        <p class="tips">
          注册既同意<a href="/caluse" target="_blank">《指南针用户协议》</a>
        </p>
        <button class="button" @click="sendRegisterRequest" v-bind:disabled="!canRegist">注册</button>
      </div>
    </div>
  </div>
</template>

<script>
  Vue.component('login-panel', {
    template: '#login-panel-template',
    props: ['showLoginAndRegisterPanel'],
    data: function(){
      return {
        panel: 'login',
        phone_number: '',
        password: '',
        verify_code: '',
        countDown: null,
        countDownInterval: null,
        phone_country: 'china',
        onRequest: false,
      };
    },
    computed: {
      validatePhoneNumber: function() {
        return this.phone_number.length > 5;
      },
      validatePassword: function() {
        return this.password.length > 0;
      },
      validateVerifyCode: function() {
        return this.verify_code.length == 4;
      },
      canRegist: function() {
        return this.validatePhoneNumber && this.validatePassword && this.validateVerifyCode;
      },
      canLogin: function() {
        return this.validatePhoneNumber && this.validatePassword;
      },
    },
    methods: {
      close: function(e){
        this.showLoginAndRegisterPanel = false;
      },
      togglePanel: function(panel){
        this.panel = panel;
      },
      getVerifyCode: function(){
        if(!this.validatePhoneNumber) {
          return alert('请填写正确的手机号码');
        }
        if(this.onRequest) {
          return;
        }

        this.onRequest = true;

        this.$http.post("{{route('auth.verifyCode.store')}}", {
          phone_number: this.phone_number,
          phone_country: this.phone_country,
        }).then(function(response){
            console.log(response);
            this.onRequest = false;
            if(response.data.data.code){
              console.log('验证码为：' + response.data.data.code);
              this.verify_code = response.data.data.code;
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
      sendRegisterRequest: function(){
        this.$http.post("{{ route('auth.users.store') }}", {
          phone_number: this.phone_number,
          code: this.verify_code,
          password: this.password
        }).then(function(response){
            console.log(response)
              alert('注册成功');
              window.location.reload();
        }).catch(function (error) {
            console.log("后台登录出错，报错" + error);
        });
      },
      sendLoginRequest: function(){
        this.$http.post("{{ route('auth.login') }}", {
          phone_number: this.phone_number,
          password: this.password
        }).then(function(response){
          if(window.parent == window) {

          } else {
            // in frame
            // set login status to parent window
            window.parent.app.$dispatch('setCurrentUser', response.data.data);
          }
          window.location.reload();
        }, function(response){
          alert('登录失败');
        });
      }
    },
  })
</script>
