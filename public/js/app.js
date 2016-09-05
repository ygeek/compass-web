Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#_token').getAttribute('value');

new Vue({
  el: '#app',
  data: {
    showLoginAndRegisterPanel: false,
    showChangePhonePanel: false,
    showEstimatePanel: false,
    estimatePanelPath: null
  },
  events: {
    'changeLikeDispatch': function (collegeid,like) {
      this.$broadcast('changeCollegeLike',collegeid,like)
    }
  },
  methods: {
    'setEstimatePanel': function (path) {
      this.showEstimatePanel = true;
      this.estimatePanelPath = path;
    }
  }
});
