<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8>
    <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <title>front-end</title>
    <link rel=stylesheet href=/static/font/iconfont.css>
    <link rel=stylesheet href=/static/index.css>
    <link rel=stylesheet href=/src/assets/css/index.css>
    <link href=/static/css/app.css rel=stylesheet>
    <script>
        data = {
            username: 'Er kang',
            userAvatar: 'xxx',
            messages: [
                {image: '', title: 'test', content: 'test', time: +new Date()},
                {image: '', title: 'test', content: 'test', time: +new Date()},
                {image: '', title: 'test', content: 'test', time: +new Date()}
            ],
            collections: [
                {
                    image: 'http://www.w3school.com.cn/tiy/loadtext.asp?f=html_image_test',
                    cnName: '悉尼大学',
                    enName: 'The University of Sydney'
                },
                {image: '', cnName: '悉尼大学', enName: 'The University of Sydney'},
                {image: '', cnName: '悉尼大学', enName: 'The University of Sydney'}

            ],
            willings: [{
                college: {
                    image: 'http://www.w3school.com.cn/tiy/loadtext.asp?f=html_image_test',
                    cnName: '悉尼大学',
                    enName: 'The University of Sydney',
                    demand: {
                        IELTS: 7.0,
                        TOEFL: 90,
                        listening: 7.5,
                        speaking: 7.5,
                        reading: 8.5,
                        writing: 8.5,
                        majorGrade: 90,
                        majorWork: 2
                    }
                },
                selectedMajors: []
            }, {
                college: {
                    image: 'http://www.w3school.com.cn/tiy/loadtext.asp?f=html_image_test',
                    cnName: '悉尼大学',
                    enName: 'The University of Sydney',
                    demand: {
                        IELTS: 7.0,
                        TOEFL: 90,
                        listening: 7.5,
                        speaking: 7.5,
                        reading: 8.5,
                        writing: 8.5,
                        majorGrade: 90,
                        majorWork: 2
                    }
                },
                selectedMajors: [{
                    name: '计算机',
                    IELTS: 5.5,
                    TOEFL: 70,
                    listening: 7.5,
                    speaking: 7.0,
                    reading: 6.0,
                    writing: 7.5,
                    majorGrade: 80,
                    majorWork: 2
                }, {
                    name: '会计',
                    IELTS: 6.5,
                    TOEFL: 80,
                    listening: 8.0,
                    speaking: 7.0,
                    reading: 9.0,
                    writing: 7.5,
                    majorGrade: 80,
                    majorWork: 2
                }, {
                    name: '机械制造',
                    IELTS: 5.5,
                    TOEFL: 70,
                    listening: 7.5,
                    speaking: 7.0,
                    reading: 6.0,
                    writing: 7.5,
                    majorGrade: 80,
                    majorWork: 2
                }]

            }]
        }
    </script>
</head>
<body>
<app></app>
<script type=text/javascript src=/static/js/manifest.js></script>
<script type=text/javascript src=/static/js/vendor.js></script>
<script type=text/javascript src=/static/js/app.js></script>
</body>
</html>