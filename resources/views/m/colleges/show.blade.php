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
        var dataS={
            'college':{!! json_encode($college->toArray()) !!},
            'article_key':{!! json_encode($article_key) !!},
            'articles':{!! json_encode($articles->toArray()) !!}
        }

        var data={
            schoolPhoto: dataS.college.background_image_url,
            schoolBadge:dataS.college.badge_url,
            cname: dataS.college.chinese_name,
            ename: dataS.college.english_name,
            buildTime: 'since ' +
            dataS.college.founded_in,
            schoolTele: dataS.college.telephone_number,
            schoolWebsite: dataS.college.website,
            schoolKey:dataS.college.key,
            page:dataS.article_key
        }

    </script>
</head>
<body>
<?php
$map=["xue-xiao-gai-kuang"=>"SchoolHome","lu-qu-qing-kuang"=>"SchoolNews","liu-xue-gong-lue"=>"SchoolNews","tu-pian"=>"Picture","specialities"=>"Major"]
?>
<app page=<?php echo $map[$article_key]?>></app>
<script type=text/javascript src=/static/js/manifest.js></script>
<script type=text/javascript src=/static/js/vendor.js></script>
<script type=text/javascript src=/static/js/app.js></script>
</body>
</html>