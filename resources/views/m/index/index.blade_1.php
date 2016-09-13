<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8>
    <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <title>front-end</title>
    <link rel=stylesheet href=/static/font/iconfont.css>
    <link rel=stylesheet href=/static/index.css>
    <script src=//cdn.bootcss.com/jquery/2.2.4/jquery.min.js></script>
    <link href=/static/css/app.css rel=stylesheet>
    <script>
        var data={
            countries:{!! json_encode($countries->toArray()) !!},
            majors:[
                {name:'法学',link:'{{ route('colleges.index', ['selected_speciality_cateogry_id' => 9]) }}'},
                {name:'医学',link:'{{ route('colleges.index', ['selected_speciality_cateogry_id' => 6]) }}'},
                {name:'工科',link:'{{ route('colleges.index', ['selected_speciality_cateogry_id' => 3]) }}'},
                {name:'人文艺术',link:'{{ route('colleges.index', ['selected_speciality_cateogry_id' => 4]) }}'},
                {name:'商科',link:'{{ route('colleges.index', ['selected_speciality_cateogry_id' => 2]) }}'},
                {name:'经济金融',link:'{{ route('colleges.index', ['selected_speciality_cateogry_id' => 2]) }}'}
            ],
        <?php
        $articles = App\Article::whereHas('category', function($q){
            return $q->where('key', 'yu-yan-xue-xi');
        })->orderBy('articles.order_weight')->limit(7)->get();
        foreach($articles as $a){
            $a->l=html_entity_decode($a->link());
        }
        ?>
            yuyanxuexi:{!! json_encode($articles->toArray()) !!},
        <?php
        $articles = App\Article::whereHas('category', function($q){
            return $q->where('key', 'liu-xue-gong-lue');
        })->whereNull('college_id')->orderBy('articles.order_weight')->limit(7)->get();
        foreach($articles as $a){
            $a->l=$a->link();
        }
        ?>
            liuxuegonglue:{!! json_encode($articles->toArray()) !!},
        <?php
        $articles = App\Article::whereHas('category', function($q){
            return $q->where('key', 'yi-min-gong-lue');
        })->orderBy('articles.order_weight')->limit(7)->get();
        foreach($articles as $a){
            $a->l=$a->link();
        }
        ?>
            yimingonglue:{!! json_encode($articles->toArray()) !!},

        <?php
        $more = App\Setting::get('index_more', ['#','#','#']);
        ?>
            more:{!! json_encode($more) !!}
        }
    </script>
</head>
<body>
<app page=Home ></app>
<script type=text/javascript src=/static/js/manifest.js></script>
<script type=text/javascript src=/static/js/vendor.js></script>
<script type=text/javascript src=/static/js/app.js></script>
</body>
</html>