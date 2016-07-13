<div class="specialities">
    @foreach($articles as $speciality)
        <div class="speciality">
            <h1>{{ $speciality->name }}</h1>
            <p>学术类型：{{ $speciality->degree->name }} · 专业方向：{{ $speciality->category->chinese_name }}</p>

            <a href="{{ route('estimate.step_first') }}"><button class="estimate-button">测试录取率 -></button></a>
        </div>
    @endforeach
</div>

{{ $articles->appends(app('request')->except('page'))->render() }}