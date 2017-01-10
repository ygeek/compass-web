@include('m.public.header')
<?php $user = Auth::user();?>
<div class="clear"></div>
<div class="main" id="estimate-app">
    <div class="login_resgister">
        <form action="/estimate/stepSecondPost" id="stepSecondPost" onsubmit="return checkSecond()" method="post">
            <label for="name">姓名<span style="color: red">*</span></label>
            <input type="text" class="login_resgister_input" ismust='1' errormsg="姓名未填写!" name="name" placeholder="姓名"  @if(Auth::check())value="{{$user->getEstimateInput('name')}}"@endif>
            @if($selected_degree->name == '硕士')

            <template id="college-select-pop">
              <div class="college-select-pop" v-show="show">
                <div class="close" @click="closeButtonClick">X</div>

                <div class="search-bar">
                  <span>搜索</span><input v-model="searchKeyWord"/>
                </div>

                <template v-if="showSearchResult.length > 0">
                  <div class="search-result">
                    <ul>
                      <li v-for="college in showSearchResult">
                        <span @click="selectCollege(college)">@{{college.name}}</span>
                      </li>
                    </ul>
                  </div>
                </template>



                <div class="provinces">
                  <ul>
                    <li v-for="province in provinces" :class="{ active: province == selectedProvince }">
                      <span @click="selectProvince(province)">@{{province}}</span>
                    </li>
                  </ul>
                </div>

                <div class="colleges">
                  <ul>
                    <li v-for="college in showColleges">
                      <span @click="selectCollege(college)">@{{college.name}}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </template>

            <college-select-pop
              :show.sync="showCollegeSelect"
            >
            </college-select-pop>

            <label for="recently_college_name">最近就读院校<span style="color: red">*</span></label>
            <input type="text" @click="displayCollegeSelect" v-model="data.recently_college_name" class="login_resgister_input" ismust='1' errormsg="就读院校未填写!" name="name" placeholder="最近就读院校" >

            <?php
              $index = 0;
              $user = Auth::user();
              if($user){
                $user_recently_college_name = $user->getEstimateInput('recently_college_name');
              }else{
                $user_recently_college_name = false;
              }
            ?>


            <label for="recently_speciality_name">最近就读专业</label>
            <select id="recently_speciality_name" v-model="data.recently_speciality_name" name="recently_speciality_name" class="select01">
              <option
                v-for="major in majorList"
                :value="major"
                track-by="$index"
              >
                @{{major}}
              </option>
            </select>
            <label for="related_length_of_working">相关工作年限</label>
            <input type="number" class="login_resgister_input" name="related_length_of_working" placeholder="工作年限" @if(Auth::check())value="<?php $nx = $user->getEstimateInput('related_length_of_working'); echo $nx; ?>"@endif>
            @endif
            @if($selected_degree->name == '本科')
            <label for="cee">高考<span style="color: red">*</span></label>
            <div class="select_text">

                <select name="examinations[高考][tag]" class="select02 gktag">
                    <?php
                    $provinces = collect(config('provinces'))->sortBy(function ($product, $key) {
                        if ($product==="重庆")
                            return iconv('UTF-8', 'GBK//IGNORE', "崇庆");
                        return iconv('UTF-8', 'GBK//IGNORE', $product);
                    });
                    ?>
                    <?php
                      $index = 0;
                      $user = Auth::user();
                      if($user){
                        $user_gaokao_input = $user->getEstimateInput('examinations.高考');
                      }else{
                        $user_gaokao_input = false;
                      }
                    ?>

                    @foreach($provinces as $province)
                        <option value="{{ $province }}" @if($index++ == 0 and !$user_gaokao_input) selected @endif>{{$province}}</option>
                    @endforeach
                </select>
                <input type="number" class="login_resgister_input01 gkwithout" ismust='1' @if(Auth::check())value="<?php $gk = $user->getEstimateInput('examinations.高考') ; echo $gk['score_without_tag']; ?>"@endif errormsg="高考成绩未填写!" name="examinations[高考][score_without_tag]" placeholder="高考成绩">
                <input type="hidden" class="gkscore" name="examinations[高考][score]" errormsg="高考成绩未填写!" placeholder="高考成绩">
            </div>
            @endif


            <label for="mean">平均成绩<span style="color: red">*</span></label>
            @if($selected_degree->name == '本科')
                <input class="login_resgister_input" type="number" id="mean" ismust='1' errormsg="平均成绩未填写!" @if(Auth::check())value="<?php  $arr = $user->getEstimateInput('examinations.高中平均成绩'); echo $arr['score']; ?>"@endif name="examinations[高中平均成绩][score]" placeholder="0~100"/>
            @else
                <input class="login_resgister_input" type="number" id="mean" ismust='1' errormsg="平均成绩未填写!" name="examinations[大学平均成绩][score]" @if(Auth::check())value="<?php  $arr = $user->getEstimateInput('examinations.大学平均成绩'); echo $arr['score']; ?>"@endif placeholder="0~100"/>
            @endif
            <?php
                    $groups = [
                            ['雅思', '托福IBT']
                    ];

                    $user = Auth::user();

                    if($selected_country->name == '美国'){
                        if($selected_degree->name == '硕士'){
                            $groups[] = ['GRE', 'GMAT'];
                        }else if($selected_degree->name == '本科'){
                            $groups[] = ['ACT', 'SAT'];
                        }
                    }

                    $groups = collect($groups)->map(function($items) use ($selected_degree, $user){
                        $examinations = collect($items)->map(function($item) use ($selected_degree, $user){
                            $examination = \App\Examination::where('name', $item)->select(['id', 'name', 'sections', 'multiple_degree'])->first();
                            $res = $examination->toArray();
                            if($examination->multiple_degree){
                                $res['degree'] = $selected_degree->id;
                            }

                            $res['sections'] = collect($res['sections'])->map(function($item) use ($user, $examination){

                                $score = '';
                                if($user){
                                  $key = 'examinations.' . $examination->name;
                                  if($user->getEstimateInput($key)){
                                    $ex = $user->getEstimateInput($key);
                                    foreach ($ex['sections'] as $section) {
                                      if($section['name'] == $item){
                                        $score = $section['score'];
                                      }
                                    }
                                  }
                                }

                                return [
                                        'name' => $item,
                                        'score' => $score
                                ];
                            });

                            if($user){
                              $key = 'examinations.' . $examination->name;
                              if($user->getEstimateInput($key)){
                                $res['score'] = $user->getEstimateInput($key)['score'];
                              }
                            }

                            return $res;
                        });

                        $title = collect($items)->implode("/");
                        $selects = collect($items);

                        return [
                                'examinations' => $examinations,
                                'title' => $title,
                                'selects' => $selects
                        ];
                    });

                    $groups = objToArr($groups);
                    foreach($groups as $gkey=>$gval){
                    ?>
            <div class="choseInputs{{$gkey}}" ></div>

                    <?php } ?>
            <input type="hidden" name="selected_country" value="{{$selected_country['id']}}">
            <input type="hidden" name="selected_degree" value="{{$selected_degree['id']}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="selected_speciality_name" value="{{$selected_speciality_name}}">
            @if($college_id)
              <input type="hidden" name="college_id" value="{{ $college_id }}">
            @endif
            @if($cpm)
              <input type="hidden" name="cpm" value="{{ $cpm }}">
            @endif

            <input type="submit" tijiao='1' name="makePlan" value="生成选校方案" class="select_button makePlan" >
            <input type="button" value="返回" onclick="history.go(-1)" class="select_button01">
        </form>

    </div>
    <div class="clear"></div>
</div>


<script>
Vue.component('college-select-pop', {
  template: '#college-select-pop',
  props: ['show', 'defaultCollege', 'defaultSpeciality', 'majorList'],
  data: function() {
    return {
      colleges: [],
      provinces: [],
      loading: true,
      selectedProvince: null,
      searchKeyWord: null,
    }
  },
  methods: {
    closeButtonClick: function() {
      this.$dispatch('close-college-select-pop');
    },
    selectProvince: function(province_name) {
      this.selectedProvince = province_name;
    },
    selectCollege: function(college) {
      this.$dispatch('close-college-select-pop');
      this.$dispatch('select-college', college);
    }
  },
  computed: {
    showSearchResult: function() {
      var that = this;
      if(!this.searchKeyWord) {
        return null;
      }else {
        return this.colleges.filter(function(college) {
          return college.name.indexOf(that.searchKeyWord) !== -1;
        });
      }
    },
    showColleges: function() {
      var that = this;
      if(!that.selectedProvince) {
        return []
      }else {
        return this.colleges.filter(function(college) {
          return college.area == that.selectedProvince;
        });
      }
    },
  },
  created: function() {
    var that = this;
    this.$http.get("{{ route('estimate.select_colleges') }}").then(function(response) {
      that.loading = false;
      that.colleges = response.data.data['colleges'];
      that.provinces = response.data.data['areas'];

      if(that.defaultCollege && that.defaultSpeciality) {
        var college = that.colleges.find(function(college) {
          return college.name == that.defaultCollege;
        });
        that.majorList = college.major.concat('其它');
      }
    });


  },
});

var app = new Vue({
  el: '#estimate-app',
  data: function(){
    var data = {};
    <?php $user = Auth::user(); ?>
    @if($user)
      @if($user->getEstimateInput('recently_college_name'))
        data['recently_college_name'] =  {!! json_encode($user->getEstimateInput('recently_college_name')) !!};
      @endif
      @if($user->getEstimateInput('recently_speciality_name'))
        data['recently_speciality_name'] =  {!! json_encode($user->getEstimateInput('recently_speciality_name')) !!};
      @endif
    @endif

    return {
      data: data,
      majorList: [],
      showCollegeSelect: false,
    }
  },
  methods: {
    displayCollegeSelect: function() {
      this.showCollegeSelect = true;
    }
  },
  events: {
    'close-college-select-pop': function() {
      this.showCollegeSelect = false;
    },
    'select-college': function(college) {
      this.data.recently_college_name = college.name;

      if(!college.major) {
        this.majorList = ['其它'];
      } else {
        this.majorList = college.major.concat(["其它"]);
      }
    }
  },
});

function choseInputs(v,key)
{
    var groups = '<?php echo json_encode($groups); ?>';

    $.ajax({
        type:'POST',
        url:'/estimate/stepSecondForm',
        data:'value='+v+'&groups='+groups+'&key='+key,
        async:false,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        dataType:'json',
        success:function(e){

           //console.log(e);
            $('.choseInputs'+key).html(e)
        }
    });


}
<?php foreach($groups as $gkey=>$gval){ ?>
choseInputs('0',{{$gkey}});
<?php } ?>

function checkSecond()
{
    $(".makePlan").attr('tijiao',"1");
    $("#stepSecondPost").find("input").each(function(i) {

        if($(this).attr("ismust")=="1")
        {
            if($(this).val()=="")
            {
                $(".makePlan").attr('tijiao',"0");
                alert($(this).attr('errormsg'));
                return false;

            }
        };
    });
    if($(".makePlan").attr('tijiao')=="0")
    {
        return false;
    }

    
    else
    {
        return true;
    }


}

</script>
