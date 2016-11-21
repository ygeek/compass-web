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
            $estimate_url = route('estimate.step_second', ['disable_pre_button' => true, 'selected_country_id' => $tmp, 'selected_degree_id' => $speciality->degree->id, 'speciality_category_id' => $speciality->category->id, 'speciality_name' => $speciality->name, 'cpm' => true, 'college_id' => $college->id]);
            ?>
            <a href="javascript:void(0)" v-on:click="setEstimatePanel('{{$estimate_url}}')"><button class="estimate-button">测试录取率 -></button></a>
        </div>
    @endforeach
</div>
@include('shared.estimate')

{{ $articles->appends(app('request')->except('page'))->render() }}
