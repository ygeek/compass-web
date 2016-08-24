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
        <?php
        foreach ($array_colleges as $college) {
            $college->imgPath = app('qiniu_uploader')->pathOfKey($college->badge_path);
        }
        app('qiniu_uploader')->pathOfKey($college->badge_path)
        ?>
        var data = {
            country: '澳大利亚',
            province: '新男威尔士洲',
            city: '墨尔本',
            major: '设计 与艺术系统',
            results:{!! json_encode($array_colleges) !!}
        }
    </script>
</head>
<body>
<app page=CollegeQuery></app>
<script type=text/javascript src=/static/js/manifest.js></script>
<script type=text/javascript src=/static/js/vendor.js></script>
<script type=text/javascript src=/static/js/app.js></script>
</body>
</html>