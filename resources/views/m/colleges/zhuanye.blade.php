<div class="main04">
    <div class="yxpaiming01" style="margin:0 0 1px 0;"><a href="#">测试录取率</a></div>
    <div class="yuanxiao_cx_main">
        <div class="yx_chaxun" style="margin:2% auto;">
            <form action="" method="get">
                <input type="hidden" name="article_type" value="specialities" />
                <input type="text" class="chax_input" name="speciality_name" placeholder="专业">
                <input type="submit" class="chax_so" value="">
            </form>
        </div>

    </div>
    <div class="chaxun10"><h2><a href="#">· 硕士 · 商科 ·</a></h2><h1>您找到<span>{{ $articles->total() }} </span>所相关学校</h1></div>

    <div class="yuanxiao_gzy">
        <ul>
            @foreach($articles as $speciality)
                <?php
                $tmp = $college->administrativeArea->id;
                if ($college->administrativeArea->parent){
                    $tmp = $college->administrativeArea->parent->id;
                    if ($college->administrativeArea->parent->parent){
                        $tmp = $college->administrativeArea->parent->parent->id;
                    }
                }
                $estimate_url = route('estimate.step_second', ['selected_country_id' => $tmp, 'selected_degree_id' => $speciality->degree->id, 'speciality_category_id' => $speciality->category->id, 'speciality_name' => $speciality->name, 'cpm' => true, 'college_id' => $college->id]);
                ?>
            <li>
                <h1>{{ $speciality->name }}</h1>
                <p>学术类型：{{ $speciality->degree->name }}<br>专业方向：{{ $speciality->category->chinese_name }}</p>
                <a href="{{$estimate_url}}">测试录取率</a>
            </li>
            @endforeach
           
        </ul>
    </div>
    <div class="clear"></div>
    <div class="page">
        {{ $articles->appends(app('request')->except('page'))->render() }}
    </div>
</div>

