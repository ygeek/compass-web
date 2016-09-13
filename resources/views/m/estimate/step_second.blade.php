@include('m.public.header')
<div class="clear"></div>
<div class="main">
    <div class="login_resgister">
        <form action="" method="get">
            <input type="text" class="login_resgister_input" name="name" placeholder="姓名" value="">
            @if($selected_degree->name == '硕士')
            <select id="recently_college_name" v-model="data.recently_college_name" class="select01">
                <?php $master_colleges = App\Setting::get('master_colleges', []) ?>
                <?php $index = 0; ?>
                @foreach($master_colleges as $college)
                    <option value="{{ $college }}" @if($index++ == 0 ) selected @endif>{{$college}}</option>
                @endforeach
            </select>
            <select id="recently_speciality_name" v-model="data.recently_speciality_name" class="select01">
                <?php $master_speciality = App\Setting::get('master_speciality', []) ?>
                <?php $index = 0; ?>
                @foreach($master_speciality as $speciality)
                    <option value="{{ $speciality }}" @if($index++ == 0 ) selected @endif>{{$speciality}}</option>
                @endforeach
            </select>
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
                    <?php $index = 0; ?>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}" @if($index++ == 0 ) selected @endif>{{$province}}</option>
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

                    if($selected_country->name == '美国'){
                        if($selected_degree->name == '硕士'){
                            $groups[] = ['GRE', 'GMAT'];
                        }else if($selected_degree->name == '本科'){
                            $groups[] = ['ACT', 'SAT'];
                        }
                    }

                    $groups = collect($groups)->map(function($items) use ($selected_degree){
                        $examinations = collect($items)->map(function($item) use ($selected_degree){
                            $examination = \App\Examination::where('name', $item)->select(['id', 'name', 'sections', 'multiple_degree'])->first();
                            $res = $examination->toArray();
                            if($examination->multiple_degree){
                                $res['degree'] = $selected_degree->id;
                            }

                            $res['sections'] = collect($res['sections'])->map(function($item){
                                return [
                                        'name' => $item,
                                        'score' => ''
                                ];
                            });
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
                    ?>
            <div class="select_text">
                <select name="" class="select02">
                  
                    <option value="雅思">雅思</option>
                    <option value="托福IBT">托福IBT</option>
                 
                </select>
                <input type="text" class="login_resgister_input01"  placeholder="0-9分">
            </div>
            <div class="select_radio">
                
                <a href="#">听</a>
                <a href="#">说</a>
                <a href="#">读</a>
                <a href="#" style="float:right; margin:0;">写</a>
            </div>       
            
            <input type="submit" value="生成选校方案" class="select_button" >
            <input type="button" value="返回" onclick="document.getElementById('return_form').submit();" class="select_button01">
        </form>
    </div>
    <div class="clear"></div>
</div>
</body>
</html>
