<college-list></college-list>
<template id="college-list-template">
    <div class="college-recommend" v-if="colleges.length > 0">
        <h3>本月重点推荐院校<a style="cursor:pointer" @click="change">换一批</a></h3>
        <college :college="college" v-for="college in colleges"></college>
    </div>
</template>

<template id="college-template">
    <div class="college-single">
        <a href="@{{ college.link }}" target="_blank"><div class="cover"></div></a>
        <img v-bind:src="college.badge_path"/>
        <div class="separator"></div>

        <div class="row">
            <span class="name left">@{{ college.chinese_name }}</span>
            <span class="right">本国排名：@{{ college.domestic_ranking }}</span>
        </div>

        <div class="row">
            <span class="english_name">@{{ college.english_name }}</span>
        </div>
        <div class="row">
            <span class="left">托福：@{{ college.toefl_score }}</span>
            <span class="right">雅思：@{{ college.ielts_score }}</span>
        </div>
    </div>
</template>

<script>
    Vue.component('college-list', {
        template: '#college-list-template',
        data: function(){
          return {
            colleges: [],
            page: 1
          }
        },
        created: function(){
          this.loadColleges()
        },
        methods: {
            loadColleges: function(){
              this.$http.get("{{route('colleges.hot_colleges')}}",{
                  'page': this.page
              }).then(function(response){
                  if (response.data.length==0 && this.page!=1) {
                      this.page = 1;
                     this.loadColleges();
                  }
                  else {
                      this.colleges = response.data;
                      this.page += 1;
                  }
              });
            },
            change: function(){
                this.loadColleges()
            }
        }
    });
    Vue.component('college', {
        template: '#college-template',
        props: ['college']
    });
</script>
