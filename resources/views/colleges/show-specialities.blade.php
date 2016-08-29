<div class="specialities">
    @foreach($articles as $speciality)
        <div class="speciality">
            <h1>{{ $speciality->name }}</h1>
            <p>学术类型：{{ $speciality->degree->name }} · 专业方向：{{ $speciality->category->chinese_name }}</p>

            <?php
            $tmp = $college->administrativeArea->id;
            if ($college->administrativeArea->parent){
                $tmp = $college->administrativeArea->parent->id;
                if ($college->administrativeArea->parent->parent){
                    $tmp = $college->administrativeArea->parent->parent->id;
                }
            }
            $estimate_url = route('estimate.step_second', ['selected_country_id' => $tmp, 'selected_degree_id' => $speciality->degree->id, 'speciality_category_id' => $speciality->category->id, 'speciality_name' => $speciality->name]);
            ?>
            <a href="{{ $estimate_url }}"><button class="estimate-button">测试录取率 -></button></a>
        </div>
    @endforeach
</div>

{{ $articles->appends(app('request')->except('page'))->render() }}