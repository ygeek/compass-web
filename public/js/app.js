Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#_token').getAttribute('value');

new Vue({
  el: '#app',
  data: {
    showLoginAndRegisterPanel: false
  }
});
