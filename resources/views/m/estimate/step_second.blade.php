@include('m.public.header')
<div class="clear"></div>
<div class="main">
    <div class="login_resgister">
        <form action="" method="get">
            <input type="text" class="login_resgister_input" name="name" placeholder="姓名" value="">
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
            <input type="text" class="login_resgister_input" name="related_length_of_working" placeholder="工作年限" value="">
            @endif
            @if($selected_degree->name == '本科')
            <div class="select_text">
                <select name="cee_province" class="select02">
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
                <input type="text" class="login_resgister_input01" name="cee" placeholder="高考成绩">
            </div>
            @endif
            
            <input type="text" class="login_resgister_input" name="score" placeholder="平均成绩(0~100)" value="">
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
                 //print_r($groups);
                    ?>
            <?php foreach($groups as $key=>$val){  ?>
            <div class="select_text">
               
                <select name="" class="select02 yt{{ $key }}" onchange="choseInput($(this),{{ $key }})" >
                    <?php foreach($val['examinations'] as $k=>$v){  ?>
                  
                    <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                    <?php } ?>
                   
                 
                </select>
                <?php foreach($val['examinations'] as $k=>$v){  ?>
                <input type="text" @if($k>0) style="display:none" @endif class="login_resgister_input01 st{{ $key }}  yt{{ $key }}{{ $v['id'] }}" value="<?php if(isset($v['score'])) echo $v['score']; ?>"  placeholder="">
                <?php } ?>
            </div>
            
            <?php foreach($val['examinations'] as $k=>$v){  ?>
            <div class="select_radio st{{ $key }} yt{{ $key }}{{ $v['id'] }}" @if($k>0) style="display:none" @endif>

                    <?php foreach($v['sections'] as $jian=>$zhi){  ?>
                    <input type="text" class="login_resgister_input" name="score" value="{{ $zhi['score'] }}" placeholder="{{ $zhi['name'] }}" @if($jian==3) style="float:right; margin:0;" @endif >
                    <?php } ?>



            </div>   
            <?php } ?>
            
            <?php } ?>
              
            
            <input type="submit" value="生成选校方案" class="select_button" >
            <input type="button" value="返回" onclick="history.go(-1)" class="select_button01">
        </form>
    </div>
    <div class="clear"></div>
</div>
</body>
</html>
