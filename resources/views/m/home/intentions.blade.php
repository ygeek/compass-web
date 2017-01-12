@include('m.public.header')
<style>
#header{ display: none;}
</style>
<link type="text/css" href="/css/m-intentions.css" rel="stylesheet" />
<script src="/js/vue.js"></script>
<script src="/js/vue-resource-1.0.3.min.js"></script>
<script src="/js/lodash.js"></script>

<div class="clear"></div>

<div class="main02" style="background: #ebebeb;" id="app">
  <script type="text/x-template" id="select-options">
    <div class="add-speciality-pop" style="z-index: 8888899;">
        <div class="title">
          <div class="header">
            <div class="header_l" @click="closeClick"><img src="/static/images/back.png" height="20"></div>
            <div class="header_c">@{{title}}选择</div>
          </div>
        </div>
        <div class="content">
          <ul class="select-list">
            <li v-for="option in options" @click="selectOption(option)">
              <span class="list-item speciality" style="color: #2d313a">@{{option.name}}</span>
              <span class="selected-item dot" v-if="option.id == selectedId">●</span>
            </li>
          </ul>
        </div>
    </div>
  </script>

  <script type="text/x-template" id="add-speciality-pop">
    <select-options
      v-if="showOptions"
      :type.sync="optionsType"
      :selected-id.sync="optionsSelected"
      :options.sync="options"
    >
    </select-options>

    <div class="add-speciality-pop">
        <div style="display:none">
          @{{ degreeCategories }}
          @{{ degreeCategorySpecialities }}
        </div>
        <div class="title">
          <div class="header">
            <div class="header_l" @click="closeClick"><img src="/static/images/back.png" height="20"></div>
            <div class="header_c">添加专业</div>
            <div class="header_r" @click="commitAddSpeciality">确定</div>
          </div>
        </div>
        <div class="content">
          <ul class="select-list">
            <li
              @click="select({
                type: 'degree'
              })"
            >
              <span class="list-item">学位</span>
              <span class="selected-item">@{{selectedDegree.name}} <img src="/images/right-arrow.png"></span>
            </li>
            <li
              @click="select({
                type: 'category'
              })"
            >
              <span class="list-item">专业方向</span>
              <span class="selected-item">@{{selectedCategory.chinese_name}} <img src="/images/right-arrow.png"></span>
            </li>
            <li
              @click="select({
                type: 'speciality'
              })"
            >
              <span class="list-item">专业</span>
              <span class="selected-item">@{{selectedSpeciality}} <img src="/images/right-arrow.png"></span>
            </li>
          </ul>
        </div>
    </div>
  </script>


  <script type="text/x-template" id="intentions">
    <div class="intentions-container">
    <add-speciality-pop
      v-if="show_pop"
      :specialities.sync="show_data_specialities"
      :intentions-group-by-degree.sync="show_data_intentionsGroupByDegree"
    >
    </add-speciality-pop>
    <button class="commit-button" @click="commit">提交审核 @{{ selected_specialities_count }}/@{{ raw_intentions.length - commitedIntentionIds.length }}</button>


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

                      <like-college
                        :college_id="intentionCollege.id"
                        :liked="intentionCollege.liked"
                        :like_nums="intentionCollege.like_nums"
                      ></like-college>
                    </div>

                    <div class="line">
                      <div class="address-container left">
                          <div class="location">
                              <img src="/images/location-identity.png" alt="location-identity" />
                              @{{intentionCollege.area}}
                          </div>

                          <div class="address">
                            @{{intentionCollege.location}}, @{{intentionCollege.parent_location}}
                          </div>
                      </div>

                      <div class="estimate-button-container right">
                        <button class="estimate-button add-speciality-button" @click="addSpeciality(intentionCollege)">添加专业</button>
                      </div>
                    </div>
                </div>


            </div>

            <div class="college-intentions">
              <table>
                  <tr v-for="intention in intentions[intentionCollege.id]">
                    <td>
                      <input
                        type="checkbox"
                        v-model="intention.checked"
                        v-bind:disabled="commitedIntentionIds.indexOf(intention._id) != -1"
                      />
                    </td>

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
                                    @{{ contrast['require'] }}
                              </td>
                          </tr>

                      </table>
                  </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </script>

  <script type="text/x-template" id="like-college">
      <span
        v-if="liked == 0"
        class="right"
        style="margin-left: 20px;cursor: pointer;"
        @click="likeCollege">
          <span class="gray-heart"></span>
          @{{like_nums}}
      </span>
      <span
        v-if="liked == 1"
        class="right"
        style="margin-left: 20px;cursor: pointer;"
        @click="dislikeCollege">
          <span class="heart"></span>@{{like_nums}}
      </span>
  </script>
  @include('shared.like_college', ['template_name' => 'like-college'])

    <div class="header">
        <a href="/home"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">我的意向单</div>
    </div>

    <intentions></intentions>


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

    Vue.component('select-options', {
      template: '#select-options',
      props: ['type', 'options', 'selectedId'],
      data: function() {
        return {}
      },
      computed: {
        title: function() {
          switch (this.type) {
            case 'degree':
              return '学位'
              break;
            case 'category':
              return '专业方向';
            default:
              return '专业';
          }
        }
      },
      methods: {
        closeClick: function() {
          this.$dispatch('select-options-close');
        },
        selectOption: function(option) {
          this.$dispatch('select-option', option);
        },
      }
    });

    Vue.component("add-speciality-pop", {
      template: '#add-speciality-pop',
      props: ['specialities', 'intentionsGroupByDegree'],
      data: function() {
        var degrees = {!! json_encode(\App\Degree::estimatable()->get()) !!};
        return {
          degrees: degrees,
          categories: {!! json_encode(\App\SpecialityCategory::all()) !!},
          selected_degree_id: Object.keys(this.intentionsGroupByDegree)[0],
          selected_speciality_name: null,
          selected_category_id: null,
          showOptions: false,
          optionsSelected: null,
          options: null,
          optionsType: null,
        }
      },
      events: {
        'select-options-close': function() {
          this.showOptions = false;
        },
        'select-option': function(option) {
          var type = this.optionsType;
          switch (type) {
            case 'degree':
              this.selected_degree_id = option.id;
              break;
            case 'category':
              this.selected_category_id = option.id;
              break;
            default:
              this.selected_speciality_name = option.name;
          }
          this.showOptions = false;
        }
      },
      computed: {
        selectedAbleDegreeId: function() {
          return Object.keys(this.intentionsGroupByDegree);
        },
        degreeSpecialities: function() {
          var selected_degree_id = this.selected_degree_id;
          if(!selected_degree_id) {
            return [];
          }else {
            var degreeSpecialities = this.specialities.filter(function(speciality) {
              return speciality.degree_id == selected_degree_id;
            });

            return degreeSpecialities;
          }
        },
        degreeCategories: function() {
          var degreeSpecialities = this.degreeSpecialities;

          var categorie_ids = degreeSpecialities.map(function(item){
              return item.category_id;
          }).unique();

          var degreeCategories = this.categories.filter(function(item){
              return categorie_ids.contains(item.id);
          });

          if(degreeCategories.length > 0){
            this.selected_category_id = degreeCategories[0].id;
          }
          return degreeCategories;
        },
        degreeCategorySpecialities: function() {
          var self = this;

          var degreeCategorySpecialities = this.specialities.filter(function(item){
              return (item.category_id == self.selected_category_id && item.degree_id == self.selected_degree_id);
          }).sort(function (a, b) {
              return a.name.localeCompare(b.name);
          });

          if(degreeCategorySpecialities.length > 0) {
            this.selected_speciality_name = degreeCategorySpecialities[0].name;
          }
          return degreeCategorySpecialities;
        },
        selectedDegree: function() {
          var selected_degree_id = this.selected_degree_id;
          if(selected_degree_id) {
            return this.degrees.find(function(degree) {
              return selected_degree_id == degree.id;
            });
          }
        },
        selectedCategory: function() {
          var selected_category_id = this.selected_category_id;
          if(selected_category_id) {
            return this.categories.find(function(category) {
              return category.id == selected_category_id;
            });
          }
        },
        selectedSpeciality: function() {
          return this.selected_speciality_name;
        },
      },
      methods: {
        select: function(obj) {
          var type = obj.type;
          this.optionsType = type;
          switch (type) {
            case 'degree':
              var selectedAbleDegreeId = this.selectedAbleDegreeId;
              this.options = this.degrees.filter(function(degree) {
                return selectedAbleDegreeId.indexOf(degree.id.toString()) != -1
              }).map(function(degree) {
                return {
                  name: degree.name,
                  id: degree.id
                }
              });

              this.optionsSelected = this.selected_degree_id;
              break;
            case 'category':
              this.options = this.degreeCategories.map(function(category) {
                return {
                  name: category.chinese_name,
                  id: category.id,
                }
              });
              this.optionsSelected = this.selected_category_id;
              break;
            default:
              this.options = this.degreeCategorySpecialities.map(function(speciality) {
                return {
                  name: speciality.name,
                  id: speciality.name
                }
              });
              this.optionsSelected = this.selected_speciality_name;
          }
          this.showOptions = true;
        },
        closeClick: function() {
          this.$dispatch('add-speciality-pop-close');
        },
        commitAddSpeciality: function() {
          var selected_degree_id = this.selected_degree_id;
          if(!selected_degree_id) {
            alert('未选择专业层次');
            return;
          }

          var selected_category_id = this.selected_category_id;
          if(!selected_category_id) {
            alert('未选择专业方向');
            return;
          }

          var selected_speciality_name = this.selected_speciality_name;
          if(!selected_speciality_name) {
            alert('未选择专业');
            return;
          }

          var exists_degree_speciality = _.get(this.intentionsGroupByDegree, selected_degree_id);
          var exist = _.find(exists_degree_speciality, function(speciality) {
            return speciality.speciality_name == selected_speciality_name;
          });
          if(exist) {
            alert('已经添加过该专业了');
            return ;
          }


          var intentionsOfDegree = this.intentionsGroupByDegree[parseInt(selected_degree_id)];
          var needIntention = _.last(intentionsOfDegree);

          var estimate_id = needIntention.estimate_id;
          var college_id = needIntention.college_id;
          var speciality_name = selected_speciality_name;
          var degree_id = selected_degree_id;

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
          });

        }
      },
    });

    Vue.component("intentions", {
        template: "#intentions",
        data: function(){
            return {
                show_pop: false,
                showIntentionDetail: null,
                show_data_specialities: [],
                show_data_intentionsGroupByDegree: {},
                selected_category_id: null,
                selected_speciality_name: null,
                intentions: {!! json_encode($intentions) !!},
                categories: {!! json_encode($speciality_categories) !!},
                intentionColleges: {!! json_encode($intention_colleges) !!},
                commitedIntentionIds: {!! json_encode($commited_intention_ids) !!},
            }
        },
        events: {
          'add-speciality-pop-close': function() {
            this.show_pop = false;
          },
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
            addSpeciality: function(intentionCollege){
              var intentionsOfColleges = this.intentions[intentionCollege['id']];
              var specialities = intentionCollege['specialities'];
              this.show_data_specialities = specialities;
              var intentionsGroupByDegree = _.groupBy(intentionsOfColleges, 'degree_id');
              this.show_data_intentionsGroupByDegree = intentionsGroupByDegree;
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
                }).then(function() {
                  alert('提交审核成功');
                  window.location.reload();
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
