@include('m.public.header')
<style>
#header{ display: none;}
</style>
<link type="text/css" href="/css/m-intentions.css" rel="stylesheet" />
<script src="/js/vue.js"></script>
<script src="/js/vue-resource.min.js"></script>

<div class="clear"></div>
<div class="main02" style="background: #ebebeb;" id="app">

    <div class="header">
        <a href="/home"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">我的意向单</div>
    </div>

    <intentions
      :intentions='{!! json_encode($intentions) !!}'
      :categories='{!! json_encode($speciality_categories) !!}'
      :intention-colleges='{!! json_encode($intention_colleges) !!}'
      :commited-intention-ids='{!! json_encode($commited_intention_ids) !!}'>
    </intentions>

    <template id="intentions">

      <div class="mask" v-if="show_pop">
        <div class="add-speciality-pop">
            <div class="close" @click="show_pop=false">×</div>
            <div class="form">
              <div class="form-group">
                  <label>专业方向</label>
                  <select v-model="selected_category_id" style="width: 250px;">
                      <template  v-for="category in show_data.categories">
                          <option v-bind:value="category.id" v-if="category == show_data.categories[0]" selected>
                              @{{ category.chinese_name }}
                          </option>
                          <option v-bind:value="category.id" v-else>
                              @{{ category.chinese_name }}
                          </option>
                      </template>
                  </select>
              </div>
              <div class="form-group">
                  <label>专业</label>
                  <select v-model="selected_speciality_name" style="width: 250px;">
                      <template v-for="speciality in select_specialities">
                          <option v-bind:value="speciality.name" v-if="speciality == select_specialities[0]" selected>
                              @{{ speciality.name }}
                          </option>
                          <option v-bind:value="speciality.name" v-else>
                              @{{ speciality.name }}
                          </option>
                      </template>
                  </select>
              </div>
            </div>
          </div>
      </div>
    <div class="content" style="background:none; padding:0;">
        <div class="intention" v-for="intentionCollege in intentionColleges">
            <div class="college">
                <div class="college-info">
                    <div class="line">
                      <div class="left college-badge">
                        <img v-bind:src="intentionCollege.badge_path" />
                      </div>
                      <header class="right college-name">
                        <h1><a v-bind:href="intentionCollege.redirect_url" target="_blank">@{{intentionCollege.chinese_name}}</a></h1>
                        <h2>@{{intentionCollege.english_name}}</h2>
                      </header>
                    </div>

                    <div class="line">
                      <div class="college-ranking left">
                        本国排名： @{{intentionCollege.domestic_ranking}}
                      </div>
                    </div>

                    <div class="line">
                      <div class="address-container left">
                          <div class="location">
                              <img src="/images/location-identity.png" alt="location-identity" />
                              @{{intentionCollege.area}}
                          </div>

                          <div class="address">
                              @{{intentionCollege.address}}
                          </div>
                      </div>

                      <div class="estimate-button-container right">
                        <button class="estimate-button add-speciality-button" @click="addSpeciality(intention)">添加专业</button>
                      </div>
                    </div>
                </div>


            </div>

            <div class="college-intentions">
              <table>
                  <tr v-for="intention in intentions[intentionCollege.id]">

                    <td style="text-align: left; padding-left: 10px;">
                      @{{ intention.speciality_name }}
                    </td>

                    <td>
                      @{{ intention.degree.name }}
                    </td>

                    <td @click="displayIntentionDetail(intention)" style="cursor: pointer;">
                      查看详情
                    </td>

                    <td
                      style="padding-right: 10px; cursor: pointer;"
                      @click="deleteSpeciality(intention)"
                      v-bind:class="{disabled: commitedIntentionIds.indexOf(intention._id) != -1}"
                    >
                      x
                    </td>

                  </tr>
              </table>


              <div id="estimate-detail-pop" class="mask" v-if="showIntentionDetail">
                  <div class="estimate-detail">
                      <span @click="showIntentionDetail=false" class="close">×</span>
                      <p class="title">@{{ showIntentionDetail.college.chinese_name }}的@{{ showIntentionDetail.speciality_name }}专业匹配如下：</p>
                      <table>
                          <tr>
                            <th style="padding-left: 1em; text-align: left;">
                              专业
                            </th>
                            <th>
                              您的成绩
                            </th>
                            <th>
                              @{{ showIntentionDetail.speciality_name }}
                            </th>
                          </tr>
                          <tr v-for="contrast in showIntentionDetail.requirement_contrast" v-if="contrast['name']  != '备注'">
                            <td style="padding-left: 1em; text-align: left;">
                              @{{ contrast['name'] }}
                            </td>
                            <td>
                              @{{ contrast['user_score'] }}
                            </td>
                            <td>
                              @{{ contrast['require'] }}
                            </td>
                          </tr>

                          <tr class="comment">
                              <td
                                style="text-align: left;line-height: 26px;padding: .2em; padding-top: 1em; color: #6c6c6c; background: #fff;"
                                v-bind:colspan="showIntentionDetail.requirement_contrast.length"
                                v-for="contrast in showIntentionDetail.requirement_contrast"
                                v-if="contrast['name']  == '备注'"
                              >
                                    @{{{ contrast['require'] }}}
                              </td>
                          </tr>

                      </table>
                  </div>
              </div>

            </div>
        </div>
    </div>
    </template>
</div>

<script type="text/javascript">
    Array.prototype.unique = function() {
        var a = [];
        for (var i=0, l=this.length; i<l; i++)
            if (a.indexOf(this[i]) === -1)
                a.push(this[i]);
        return a;
    }

    Array.prototype.contains = function(obj) {
        var i = this.length;
        while (i--) {
            if (this[i] === obj) {
                return true;
            }
        }
        return false;
    }
    Vue.component("intentions", {
        template: "#intentions",
        props: ['intentions', 'categories', 'intentionColleges', 'commitedIntentionIds'],
        data: function(){
            return {
                show_pop: false,
                showIntentionDetail: null,
                show_data: {
                    categories: [],
                    specialities: [],
                    intention: null
                },
                selected_category_id: null,
                selected_speciality_name: null
            }
        },
        computed: {
            raw_intentions: function() {
              var raw_intentions = [];
              var that = this;
              var keys = Object.keys(this.intentions);

              keys.forEach(function(key) {
                var college_intentions = that.intentions[key];
                college_intentions.forEach(function(college_intention) {
                  if(that.commitedIntentionIds.indexOf(college_intention._id) != -1) {
                    college_intention.commited = true;
                  }

                  raw_intentions.push(college_intention);
                });
              });

              return raw_intentions;
            },
            select_specialities: function(){
                // var self = this;
                //
                // var data = this.show_data.specialities.filter(function(item){
                //     return item.category_id == self.selected_category_id;
                // }).sort(function (a, b) {
                //     return a.name.localeCompare(b.name);
                // });
                //
                // return data;
                return 0;
            },
            selected_specialities: function(){
                return this.raw_intentions.filter(function(intention) {
                  return intention.checked;
                });
            },
            selected_specialities_count: function(){
                return this.selected_specialities.length;
            }
        },
        methods: {
            displayIntentionDetail: function(intentionDetail) {
              this.showIntentionDetail = intentionDetail;
            },
            addSpeciality: function(intention){
                this.show_data.intention = intention;
                var specialities = intention['college']['specialities'];
                var categorie_ids = specialities.map(function(item){
                    return item.category_id;
                }).unique();

                var categories = this.categories.filter(function(item){
                    return categorie_ids.contains(item.id);
                });

                this.show_data.categories = categories;
                this.show_data.specialities = specialities;
                this.show_pop = true;
            },
            postSpeciality: function(){
                var college_id = this.show_data.intention.college.id;
                var degree_id = this.intentions.degree_id;
                var estimate_id = this.intentions.estimate_id;
                var speciality_name = this.selected_speciality_name;

                if(this.selected_speciality_name==null){
                    this.selected_speciality_name = this.select_specialities[0].name;
                    speciality_name = this.selected_speciality_name;
                }

                for(var i in this.show_data.intention.specialities){
                    if(this.show_data.intention.specialities[i].speciality_name==speciality_name){
                        alert("该专业已存在");
                        return;
                    }
                }

                this.$http.post("{{ route('intentions.store') }}", {
                    college_id: college_id,
                    degree_id: degree_id,
                    estimate_id: estimate_id,
                    speciality_name: speciality_name
                }).then(function(response){
                    alert('加入意向单成功');
                    window.location.reload();
                }, function(response){
                    if(response.status == 401){
                        alert('请先登录')
                    };
                })
            },
            deleteSpeciality: function(speciality){
                if(this.commitedIntentionIds.indexOf(speciality._id) != -1) {
                  return;
                }

                var id = speciality._id;
                if(!confirm('确定删除专业？(若当前专业为院校最后一个专业、院校也会被删除)')){
                    return false;
                }
                var url = "{{ route('intentions.destroy', ['id' => 'id']) }}".replace('id', id);
                this.$http.delete(url).then(function(response){
                    alert('删除成功');
                    window.location.reload();
                });
            },
            commit: function(){

                //提交审核
                // var estimate_id = this.intentions.estimate_id;
                var selected_speciality_ids = this.selected_specialities.map(function(speciality){
                    return speciality._id;
                });

                //
                if(selected_speciality_ids.length == 0){
                    alert('未选择审核专业');
                    return;
                }

                var url = "{{ route('intentions.create') }}";
                this.$http.post(url, {
                    selected_speciality_ids: selected_speciality_ids
                }, function(response){
                    alert('提交审核成功');
                });
            },
            countArray: function count(o){
                var t = typeof o;
                if(t == 'string'){
                    return o.length;
                }else if(t == 'object'){
                    var n = 0;
                    for(var i in o){
                        n++;
                    }
                    return n;
                }
                return false;
            }
        }
    });
</script>
<script type="text/javascript">
  Vue.http.headers.common['X-CSRF-TOKEN'] = "{{csrf_token()}}"

  new Vue({
    el: '#app',
    data: {

    }
  });
</script>
