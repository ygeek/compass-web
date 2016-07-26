<college-list></college-list>
<template id="college-list-template">
    <div class="college-recommend">
        <h3>本月重点推荐院校<a v-on:click="change" href="javascript:void(0)">换一批</a></h3>
        <college :college="colleges[0]"></college>
        <college :college="colleges[1]"></college>
        <college :college="colleges[2]"></college>
        <college :college="colleges[3]"></college>
    </div>
</template>

<template id="college-template">
    <div class="college-single">
        <img src=""/>
        <div class="separator"></div>
        <div class="row">
            <span class="name left">@{{ college.chinese_name }}</span>
            <span class="right">本国排名：@{{ college.domestic_ranking }}</span>
        </div>
        <div class="row">
            <span>@{{ college.english_name }}</span>
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
        props: ['colleges'],
        data: function(){
            this.$http.post("{{route('colleges.hot_colleges')}}",{
                'number': 4
            }).then(function(response){
                this.colleges=[];
                var i = 0;
                for (var x in response.data){
                    this.colleges[i++]=response.data[x];
                }
            });
        },
        methods: {
            change: function(e){
                this.$http.post("{{route('colleges.hot_colleges')}}",{
                    'number': 4
                }).then(function(response){
                    this.colleges=[];
                    var i = 0;
                    for (var x in response.data){
                        this.colleges[i++]=response.data[x];
                    }
                });
            }
        }
    });
    Vue.component('college', {
        template: '#college-template',
        props: ['college'],
        methods: {
            change: function(e){
                alert(this.college.chinese_name)
            }
        }
    });
</script>