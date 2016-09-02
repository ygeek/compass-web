<college-create-form></college-create-form>
<?php $degree_ids = $college->degrees->map(function($item){return $item->id;})->toArray() ?>
<template id="create_form">
<a class="btn btn-primary" href="{{route('admin.colleges.index')}}">返回</a>
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
        <form class="form-horizontal push-10-t push-10" action="{{ route('admin.colleges.update', $college->id) }}" method="POST" enctype="multipart/form-data">
        <input name="_method" type="hidden" value="PATCH">
      @else
        <form class="form-horizontal push-10-t push-10" action="{{ route('admin.colleges.store') }}" method="POST" enctype="multipart/form-data">
      @endif
          {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-7">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label for="chinese_name">中文名<span class="text-danger">*</span></label>
                            <input class="form-control input-lg" type="text" value="{{ $college->chinese_name or old('chinese_name') }}" id="chinese_name" name="chinese_name" placeholder="输入院校的中文名称..">
                        </div>
                        <div class="col-xs-6">
                            <label for="english_name">英文名<span class="text-danger">*</span></label>
                            <input class="form-control input-lg" type="text" value="{{$college->english_name or old('english_name')}}" id="english_name" name="english_name" placeholder="输入院校的英文名称..">
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="founded_in">创始年份</label>
                            <input class="form-control input-lg" type="text" value="{{$college->founded_in or old('founded_in')}}" id="founded_in" name="founded_in" placeholder="输入院校的创始年份..">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-7">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label for="telephone_number">电话</label>
                            <input class="form-control input-lg" type="text" id="telephone_number" value="{{$college->telephone_number or old('telephone_number')}}" name="telephone_number" placeholder="输入院校的电话..">
                        </div>
                        <div class="col-xs-6">
                            <label for="address">地址</label>
                            <input class="form-control input-lg" type="text" id="address" name="address" value="{{$college->address or old('address')}}" placeholder="输入院校的地址..">
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="website">网站</label>
                            <input class="form-control input-lg" type="text" id="website" value="{{$college->website or old('website')}}" name="website" placeholder="输入院校的网站..">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-7">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label for="average_enrollment">平均录取率</label>
                            <input class="form-control input-lg" type="text" id="average_enrollment" value="{{$college->average_enrollment or old('average_enrollment')}}" name="average_enrollment" number>
                        </div>
                        <div class="col-xs-6">
                            <label for="international_ratio">国际学生比例</label>
                            <input class="form-control input-lg" type="text" id="international_ratio" value="{{$college->international_ratio or old('international_ratio')}}" name="international_ratio" number>
                        </div>
                        <div class="col-xs-12">
                            <label for="description">简介</label>
                            <textarea class="form-control input-lg" id="description" name="description" rows="22" placeholder="输入院校的简介..">{{$college->description or old('description')}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="badge_path">院校Logo</label>
                            <input @change="checkinfo" class="form-control input-lg" type="file" id="badge_path" name="badge_path" placeholder="Enter your location..">
                        </div>
                        @if($college->badge_path)
                            <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}" height="40px;"/>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="background_image_path">院校背景图</label>
                            <input @change="checkinfo" class="form-control input-lg" type="file" id="background_image_path" name="background_image_path">
                        </div>
                        @if($college->background_image_path)
                            <img src="{{app('qiniu_uploader')->pathOfKey($college->background_image_path)}}" height="40px;"/>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="background_image_path">小图标</label>
                            <input @change="checkinfo" class="form-control input-lg" type="file" id="icon_path" name="icon_path">
                        </div>
                        @if($college->icon_path)
                            <img src="{{app('qiniu_uploader')->pathOfKey($college->icon_path)}}" height="40px;"/>
                        @endif
                    </div>

                    <div class="form-group">
                      <label class="col-xs-12" for="country_select">院校地区<span class="text-danger">*</span></label>
                      <input type="hidden" name="administrative_area_id" v-model="administrative_area_id"/>
                      <div class="row">
                        <div class="col-xs-12">
                          <div class="col-sm-4" v-show="!!countries">
                              <select v-model="selected_country"  class="form-control" name="country_select" id="country_select">
                                <option v-for="option in countries" v-bind:value="option.value">
                                  @{{ option.name }}
                                </option>
                              </select>
                            </div>
                            <div class="col-sm-4" v-show="!!states">
                              <select class="form-control" v-model="selected_state" name="state_select">
                                <option v-for="option in states" v-bind:value="option.value">
                                  @{{ option.name }}
                                </option>
                              </select>
                            </div>
                            <div class="col-sm-4" v-show="!!cities">
                              <select class="form-control" v-model="selected_city" name="city_select">
                                <option v-for="option in cities" v-bind:value="option.value">
                                  @{{ option.name }}
                                </option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>

                    <div class="form-group">
                        <label class="col-xs-12">所拥有学位<span class="text-danger">*</span></label>
                        <div class="col-xs-12">
                            @foreach($degrees as $degree)
                                <div class="checkbox">
                                    <label for="degrees-{{$degree->id}}">
                                        <input type="checkbox"
                                               id="degrees-{{$degree->id}}"
                                               name="degree_ids[]"
                                               @if(in_array($degree->id, $degree_ids))
                                               checked
                                               @endif
                    <?php if (old('degree_ids')!=null && in_array($degree->id, old('degree_ids'))) echo 'checked'; ?>
                                               value="{{$degree->id}}"> {{$degree->name}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group" v-if="is_australia">

                        <label class="col-xs-12">八大院校</label>
                        <div class="col-xs-12">
                            <label class="css-input css-radio css-radio-warning push-10-r">
                                <input name="go8" type="radio" value="1" @if($college->go8) checked @endif <?php if(old('go8')!=null && old('go8')) echo 'checked'; ?>><span></span> 八大
                            </label>
                            <label class="css-input css-radio css-radio-warning">
                                <input name="go8" type="radio" value="0" <?php if((old('go8')!=null && (!old('go8')))) echo 'checked'; else if(($college->go8!=null) && (!$college->go8)) echo 'checked'; ?>><span></span> 非八大
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-12">院校类型</label>
                        <div class="col-xs-12">
                            <label class="css-input css-radio css-radio-warning push-10-r">
                                <input name="type" type="radio" value="public" @if($college->type == 'public' || old('type')=='public') checked @endif><span></span> 公立
                            </label>
                            <label class="css-input css-radio css-radio-warning">
                                <input name="type" type="radio" value="private" @if($college->type == 'private' || old('type')=='private') checked @endif><span></span> 私立
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-12">热门院校</label>
                        <div class="col-xs-12">
                          <label class="css-input css-checkbox css-checkbox-success">
                              <input name="hot" type="checkbox" @if($college->hot || old('hot')==true) checked @endif><span></span>
                          </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-12">推荐院校</label>
                        <div class="col-xs-12">
                          <label class="css-input css-checkbox css-checkbox-success">
                              <input name="recommendatory" type="checkbox" @if($college->recommendatory || old('recommendatory')==true) checked @endif)><span></span>
                          </label>
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
        selected_country: <?php if(old('country_select')!=null) echo "'".old('country_select')."'"; else if($country!=null) echo $country; else echo 'null';?>,
        selected_state: <?php if(old('state_select')!=null) echo "'".old('state_select')."'"; else if($state!=null) echo $state; else echo 'null';?>,
        selected_city: <?php if(old('city_select')!=null) echo "'".old('city_select')."'"; else if($city!=null) echo $city; else echo 'null';?>,
        areas: {!! $areas !!}
    }
  },
  methods: {
    checkinfo: function(e){
      var obj = e.target
      var len = obj.files.length;

      var text="";
      var empty = false;
      for (var i =0 ; i < len ; i++){
        var file_size = (obj.files[i].size / 1024 / 1024).toFixed(2);
        if(file_size * 100 > 200){
          text += "文件:"+obj.files[i].name+" ,大小:"+ file_size +"M\n";
          text += "图片大小请勿太大（2M以下）否则将导致用户加载困难"
          empty = true;
          alert(text);
        }
      }

      if(empty){
        e.target.value = "";
      }
    }
  },
  computed: {
    //是否是澳洲
    is_australia: function(){
      return this.selected_country == 1;
    },

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
      }).sort(function (a, b) {
          return a.name.localeCompare(b.name);
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
          return children.sort(function (a, b) {
              return a.name.localeCompare(b.name);
          });
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
              return children.sort(function (a, b) {
                  return a.name.localeCompare(b.name);
              });
            }
          }
        }

      }
      return null;
    }
  }
});
</script>
