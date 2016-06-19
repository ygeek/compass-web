<login-panel v-bind:show-login-and-register-panel.sync="showLoginAndRegisterPanel"></login-panel>
<template id="login-panel-template">
<div id="login-panel" v-show="showLoginAndRegisterPanel">
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
      <button class="button" @click="sendLoginRequest">登录</button>
    </div>

    <div class="register-form form" v-show="panel == 'register'">
      <p>
        <input placeholder="手机号码" v-model="phone_number"/>
      </p>
      <p>
        <input placeholder="密码" type="password" v-model="password"/>
      </p>
      </p>
      <p class='sms-container'>
        <input placeholder="短信验证" class="sms-input" v-model="verify_code"/>
        <button class='grab-verify-code' @click="getVerifyCode">获取验证码</button>
      </p>
      <p class="tips">
        注册既同意《指南针用户协议》
      </p>
      <button class="button" @click="sendRegisterRequest">注册</button>
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
        verify_code: ''
      };
    },
    methods: {
      close: function(e){
          this.showLoginAndRegisterPanel = false;
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
        });
      },
      sendRegisterRequest: function(){
        this.$http.post("{{ route('auth.users.store') }}", {
          phone_number: this.phone_number,
          code: this.verify_code,
          password: this.password
        }).then(function(response){
          console.log('注册成功');
        });
      },
      sendLoginRequest: function(){
        this.$http.post("{{ route('auth.login') }}", {
          phone_number: this.phone_number,
          password: this.password
        }).then(function(response){
          window.location.reload();
        }, function(response){
          alert('登录失败');
        });
      }
    },
  })
</script>
