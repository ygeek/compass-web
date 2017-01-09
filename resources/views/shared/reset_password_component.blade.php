<script type="text/x-template" id="reset-password-form-template">
  <div class="reset-form form">
    <p>
      <select class="country-select" v-model="phone_country">
        <option value="china">中国</option>
        <option value="aus">澳洲</option>
        <option value="nzl">新西兰</option>
      </select>
      <input class="phone-input" placeholder="手机号码" v-model="phone_number"/>
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
    <p>
      <input placeholder="密码" type="password" v-model="password"/>
    </p>
    <p>
      <input placeholder="确认密码" type="password" v-model="password_confirm"/>
    </p>
    <button class="button" @click="sendRestPasswordRequest" :disabled="reseting">确定重置</button>
  </div>
</script>

<script>
Vue.component('reset-password-form', {
  template: '#reset-password-form-template',
  data: function() {
    return {
      phone_country: 'china',
      onRequest: false,
      countDown: null,
      phone_number: '',
      password: '',
      password_confirm: '',
      verify_code: '',
      countDownInterval: null,
      reseting: false,
    }
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
  },
  methods: {
    sendRestPasswordRequest: function() {
      if(!this.validatePhoneNumber) {
        return alert('请填写正确的手机号码');
      }

      if(!this.validateVerifyCode) {
        return alert('请填写正确的手机验证码');
      }

      if(!this.validatePassword) {
        return alert('请填写密码');
      }

      if(this.password != this.password_confirm) {
        return alert('两次密码输入不一致');
      }

      var that = this;
      this.reseting = true;
      this.$http.post("{{route('auth.reset_passwrod')}}", {
        phone_number: this.phone_number,
        password: this.password,
        code: this.verify_code,
      }).then(function(response){
        alert('重置成功');
        window.location.href = '/';
      }).catch(function(err) {
        alert(err.data.data.message);
      }).then(function(){
        that.reseting = false;
      });
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
        this.onRequest = false;
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
  }
});
</script>
