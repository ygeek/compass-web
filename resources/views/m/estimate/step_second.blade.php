@include('m.public.header')
<div class="clear"></div>
<div class="main">
    <div class="login_resgister">
        <form action="/estimate/stepSecondPost" id="stepSecondPost" onsubmit="return checkSecond()" method="post">
            <input type="text" class="login_resgister_input" ismust='1' name="name" placeholder="姓名" value="">
            @if($selected_degree->name == '硕士')
            <select id="recently_college_name" v-model="data.recently_college_name" name="recently_college_name" class="select01">
                <?php $master_colleges = App\Setting::get('master_colleges', []) ?>
                <?php
                  $index = 0;
                  $user = Auth::user();
                  if($user){
                    $user_recently_college_name = $user->getEstimateInput('recently_college_name');
                  }else{
                    $user_recently_college_name = false;
                  }
                ?>
               
               @foreach($master_colleges as $college)
                    <option value="{{ $college }}" @if($index++ == 0 and !$user_recently_college_name) selected @endif>{{$college}}</option>
                @endforeach
              
            </select>
            <select id="recently_speciality_name" v-model="data.recently_speciality_name" name="recently_speciality_name" class="select01">
                <?php $master_speciality = App\Setting::get('master_speciality', []) ?>
                <?php
                  $index = 0;
                  $user = Auth::user();
                  if($user){
                    $user_recently_speciality_name = $user->getEstimateInput('recently_speciality_name');
                  }
                  else{
                    $user_recently_speciality_name = false;
                  }
                ?>
                @foreach($master_speciality as $speciality)
                    <option value="{{ $speciality }}" @if($index++ == 0 and !$user_recently_speciality_name) selected @endif>{{$speciality}}</option>
                @endforeach
            </select>
            <input type="number" class="login_resgister_input" name="related_length_of_working" placeholder="工作年限" value="">
            @endif
            @if($selected_degree->name == '本科')
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
                <input type="number" class="login_resgister_input01 gkwithout" ismust='1' name="examinations[高考][score_without_tag]" placeholder="高考成绩">
                <input type="hidden" class="gkscore" name="examinations[高考][score]" placeholder="高考成绩">
            </div>
            @endif
            @if($selected_degree->name == '本科')
                <input class="login_resgister_input" type="number" id="mean" ismust='1' name="examinations[高中平均成绩][score]" placeholder="0~100"/>
            @else
                <input class="login_resgister_input" type="number" id="mean" ismust='1' name="examinations[大学平均成绩][score]" placeholder="0~100"/>
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
            <input type="submit" tijiao='1' name="makePlan" value="生成选校方案" class="select_button makePlan" >
            <input type="button" value="返回" onclick="history.go(-1)" class="select_button01">
        </form>
       
    </div>
    <div class="clear"></div>
</div>
<script>

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
    console.log('123');
    $("#stepSecondPost").find("input").each(function(i) {
       
        if($(this).attr("ismust")=="1")
        {
            if($(this).val()=="") 
            {
                $(".makePlan").attr('tijiao',"0");
                alert('请填写完整...');
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
