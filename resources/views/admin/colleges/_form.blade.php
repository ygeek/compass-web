<college-create-form></college-create-form>
<template id="create_form">
<div class="block block-bordered">
    <div class="block-header bg-gray-lighter">
        <ul class="block-options">
            <li>
                <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
            </li>
            <li>
                <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
            </li>
        </ul>
        <h3 class="block-title">新增院校</h3>
    </div>
    <div class="block-content" id="new_college_form">
      @if( Route::getCurrentRoute()->getName() == 'admin.colleges.edit')
        <form class="form-horizontal push-10-t push-10" action="{{ route('admin.colleges.update', $college->id) }}" method="POST">
        <input name="_method" type="hidden" value="PATCH">
      @else
        <form class="form-horizontal push-10-t push-10" action="{{ route('admin.colleges.store') }}" method="POST">
      @endif
          {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-7">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label for="chinese_name">中文名</label>
                            <input class="form-control input-lg" type="text" value="{{$college->chinese_name}}" id="chinese_name" name="chinese_name" placeholder="输入院校的中文名称..">
                        </div>
                        <div class="col-xs-6">
                            <label for="english_name">英文名</label>
                            <input class="form-control input-lg" type="text" value="{{$college->english_name}}" id="english_name" name="english_name" placeholder="输入院校的英文名称..">
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="founded_in">创始年份</label>
                            <input class="form-control input-lg" type="text" value="{{$college->founded_in}}" id="founded_in" name="founded_in" placeholder="输入院校的创始年份..">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-7">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label for="telephone_number">电话</label>
                            <input class="form-control input-lg" type="text" id="telephone_number" value="{{$college->telephone_number}}" name="telephone_number" placeholder="输入院校的电话..">
                        </div>
                        <div class="col-xs-6">
                            <label for="address">地址</label>
                            <input class="form-control input-lg" type="text" id="address" name="address" value="{{$college->address}}" placeholder="输入院校的地址..">
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="website">网站</label>
                            <input class="form-control input-lg" type="text" id="website" value="{{$college->website}}" name="website" placeholder="输入院校的网站..">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label for="qs_ranking">QS排名</label>
                            <input class="form-control input-lg" type="text" id="qs_ranking" value="{{$college->qs_ranking}}" name="qs_ranking" placeholder="输入院校的电话..">
                        </div>
                        <div class="col-xs-6">
                            <label for="us_new_ranking">US New排名</label>
                            <input class="form-control input-lg" type="text" id="us_new_ranking" value="{{$college->us_new_ranking}}" name="us_new_ranking" placeholder="输入院校的地址..">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label for="times_ranking">Times排名</label>
                            <input class="form-control input-lg" type="text" id="times_ranking" value="{{$college->times_ranking}}" name="times_ranking" placeholder="输入院校的电话..">
                        </div>
                        <div class="col-xs-6">
                            <label for="domestic_ranking">国内排名</label>
                            <input class="form-control input-lg" type="text" id="domestic_ranking" value="{{$college->domestic_ranking}}" name="domestic_ranking" placeholder="输入院校的地址..">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-7">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="description">简介</label>
                            <textarea class="form-control input-lg" id="description" name="description" rows="22" placeholder="输入院校的简介..">{{$college->description}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="badge_path">院校Logo</label>
                            <input class="form-control input-lg" type="file" id="badge_path" name="badge_path" placeholder="Enter your location..">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="background_image_path">院校背景图</label>
                            <input class="form-control input-lg" type="file" id="background_image_path" name="background_image_path" placeholder="Enter your location..">
                        </div>
                    </div>

                    <div class="form-group">
                      <label class="col-xs-12" for="country_select">院校地区</label>
                      <input type="hidden" name="administrative_area_id" v-model="administrative_area_id"/>
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="col-sm-4" v-show="!!countries">
                              <select v-model="selected_country"  class="form-control" id="country_select">
                                <option v-for="option in countries" v-bind:value="option.value">
                                  @{{ option.name }}
                                </option>
                              </select>
                            </div>
                            <div class="col-sm-4" v-show="!!states">
                              <select class="form-control" v-model="selected_state">
                                <option v-for="option in states" v-bind:value="option.value">
                                  @{{ option.name }}
                                </option>
                              </select>
                            </div>
                            <div class="col-sm-4" v-show="!!cities">
                              <select class="form-control" v-model="selected_city">
                                <option v-for="option in cities" v-bind:value="option.value">
                                  @{{ option.name }}
                                </option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-check push-5-r"></i>
                          @if( Route::getCurrentRoute()->getName() == 'admin.colleges.edit')
                          修改院校
                          @else
                          新增院校
                          @endif
                        </button>
                    </div>
                </div>
              </form>
            </div>
    </div>
</div>
</template>
<!-- Select组件  -->
<template id="select-tag">

</template>

<!-- 连选组件 -->
<template id="selece-manager">

</template>
<script>
Vue.component('select-tag', {
  template: '#select-tag',
  props: ['list', 'selected_id']
});

Vue.component('select-manager', {
  template: '#select-manager',
  props: ['data', 'selected_id'],
  computed: {
    selects: function(){

    }
  }
});

Vue.component('college-create-form', {
  template: '#create_form',
  data: function(){
    return {
      areas: {!! $areas !!},
      selected_country: {{$country or 'null'}},
      selected_state: {{$state or 'null'}},
      selected_city: {{$city or 'null'}}
    }
  },
  computed: {
    administrative_area_id: function(){
      if(this.selected_city){
        return this.selected_city;
      }else if(this.selected_state){
        return this.selected_state;
      }else if(this.selected_country){
        return this.selected_country;
      }
      return null;
    },

    countries: function(){
      return this.areas.map(function(item){
        return {
          value: item.id,
          name: item.name
        };
      });
    },
    states: function(){
      // country change should call this method
      // this.selected_state = null;
      // this.selected_city = null;

      var identity = this.selected_country;
      // return identity;
      for (var i = 0; i < this.areas.length; i++) {
        if(this.areas[i].id == identity){
          var children = this.areas[i].children.map(function(_item){
            return {
              value: _item.id,
              name: _item.name
            };
          });
          return children;
        }
      }

      // this.areas.forEach(function(item){
      //   if(item.id == identity){
      //     var children = item.children.map(function(_item){
      //       return {
      //         value: item.id,
      //         name: item.name
      //       };
      //     });
      //     console.log(children);
      //     return identity;
      //   }
      // });

      // $.each(this.areas, function(item){
      //   console.log(identity);
      //   console.log(item.id);
      //   if(item.id == identity){
      //     return identity;
      //   }
      // });
      // this.areas.forEach(function(item){
      //   if(item.id == this.selected_country){
      //     return item.children;
      //   }
      // });
    },
    cities: function(){
      // this.selected_city = null;

      var state = this.selected_state;
      if(!state){
        return null;
      }

      for (var i = 0; i < this.areas.length; i++) {
        for (var j = 0; j < this.areas[i].children.length; j++) {
          if(this.areas[i].children[j].id == state){

            var children = this.areas[i].children[j].children.map(function(_item){
              return {
                value: _item.id,
                name: _item.name
              };
            });
            if(children.length > 0){
              return children;
            }
          }
        }

      }
      return null;
    }
  }
});
</script>
