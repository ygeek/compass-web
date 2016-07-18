<html>
<head>
<meta charset="UTF-8" /> 
</head>
<body>
    <?php 
        $score_keys = collect(array_keys($intention->data['user_scores']))->filter(function($key){
            return $key != '备注';
        });
    ?>

    <table>
    <tr>
        <td>姓名：</td>
        <td>{{ $intention->name }}</td>
        <td>Email：</td>
        <td>{{ $intention->email }}</td>
        <td>手机号：</td>
        <td>{{ $intention->phone_number }}</td>
        <td>申请国家：</td>
        <td>{{ \App\AdministrativeArea::find($intention->data['country_id'])->name }}</td>
        <td>申请学历：</td>
        <td>{{ \App\Degree::find($intention->data['degree_id'])->name }}</td>
    </tr>

    <tr>
        <td>学校</td>
        <td>专业</td>
        @foreach($score_keys as $key)
            <td>{{ $key }}</td>
        @endforeach
    </tr>
    @foreach($intention->data['intentions'] as $intention_college)
        <?php $college = \App\College::find($intention_college['college_id']);?>
        @foreach($intention_college['specialities'] as $speciality)
            <tr>
                <td>{{ $college->chinese_name }}</td>
                <td>{{ $speciality['speciality_name'] }}</td>
                @foreach($score_keys as $key)
                    <td>{{ $speciality['require'][$key] }}</td>
                @endforeach
            </tr>
        @endforeach
    @endforeach

    <tr>
    <td></td>
    <td>客户成绩</td>
    @foreach($score_keys as $key)
        <td>{{ $intention->data['user_scores'][$key] }}</td>
    @endforeach
    </tr>
    </table>
    </body>
</html>
