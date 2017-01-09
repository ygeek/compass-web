<style>
.reset-form {
  width: 300px;
  margin: 0 auto;
  margin-top: 60px;
  padding-top: 30px;
  text-align: center;
}

.reset-form input {
  width: 100%;
  padding-left: 20px;
  margin-bottom: 10px;
  height: 40px;
}

.country-select {
  float: left; width: 20%; height: 40px;border: none;background: #fff;
}

.phone-input {
  float: right; width: 78% !important;
}

.sms-input {
  width: 55% !important;
  float: left;
}

.grab-verify-code {
  width: 40% !important;
  float: right;
  height: 40px;
}

.button {
  height: 50px;
  width: 100%;
  line-height: 50px;
  display: block;
  margin: 50px auto 0 auto;
  background: #0e2d60;
  color: #fff;
  cursor: pointer;
  border: 0;
}

</style>
@include('m.public.header')
@include('shared.reset_password_component')
<div id="app">
  <reset-password-form>
  </reset-password-form>
</div>


<script type="text/javascript">
  Vue.http.headers.common['X-CSRF-TOKEN'] = "{{csrf_token()}}"

  new Vue({
    el: '#app',
    data: {

    }
  });
</script>
