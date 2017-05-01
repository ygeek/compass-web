<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

    $ordered_keys = ['雅思','托福IBT','听','说','读','写','高中平均成绩', '大学平均成绩', '高考','ACT','作文','SAT', 'GRE', 'GMAT','阅读','数学','语文','写作','相关专业工作年限','备注'];

    function ordered_score_keys($user_scores, $ordered_keys)
    {
        return collect(array_keys($user_scores))->sort(function ($a, $b) use ($ordered_keys) {
            if (strpos($a, '高考') !== false) {
                $a = '高考';
            }

            if (strpos($b, '高考') !== false) {
                $b = '高考';
            }
            return array_search($a, $ordered_keys) - array_search($b, $ordered_keys);
        })->filter(function ($key) {
            return $key != '备注';
        });
    }

    $intentions_group_by_country = $intentions->groupBy('country_id');
    //var_dump($intentions_group_by_country->toArray());die;

    $ielts = $estimate_input['examinations.雅思'];
    // foreach ($ielts['sections'] as $value) {
    //     var_dump($value["score"]);
    // }
    // die;
    $data = [];
    $data[] = $ielts['score'];
    $data[] = $ielts['sections'][0]['score'];
    $data[] = $ielts['sections'][1]['score'];
    $data[] = $ielts['sections'][2]['score'];
    $data[] = $ielts['sections'][3]['score'];
    $data[] = $estimate_input["examinations.高中平均成绩"]["score"];
    $data[] = $estimate_input["examinations.高考"]["score_without_tag"];
    // var_dump($data);die;
?>

@foreach($intentions_group_by_country as $country_id => $intentions_by_country)
<?php
  $intentions_group_by_degree = collect($intentions_by_country)->groupBy('degree_id');
?>
@foreach($intentions_group_by_degree as $degree_id => $intentions_by_degree)
<table>
  <tr>
      <td>姓名：</td>
      <td>{{ $intention->name }}</td>
      <td>Email：</td>
      <td>{{ $intention->email }}</td>
      <td>手机号：</td>
      <td>{{ $intention->phone_number }}</td>
      <td>申请国家：</td>
      <td>{{ \App\AdministrativeArea::find($country_id)->name }}</td>
      <td>申请学历：</td>
      <td>{{ \App\Degree::find($degree_id)->name }}</td>
  </tr>
  <tr>
      <td>学校</td>
      <td>专业</td>
      <?php
        $score_keys = ordered_score_keys($intentions_by_degree->first()['user_scores'], $ordered_keys);
      ?>
      @foreach($score_keys as $key)
          <td>{{ $key }}</td>
      @endforeach
  </tr>

    @foreach($intentions_by_degree as $an_intention)
        <tr>
            <td>{{ \App\College::find($an_intention['college_id'])->chinese_name }}</td>
            <td>{{ $an_intention['speciality_name'] }}</td>
            @foreach($score_keys as $key)
                <td>{{ $an_intention['require'][$key] }}</td>
            @endforeach
        </tr>
    @endforeach

    <tr>
    <td></td>
    <td>客户成绩</td>
    @foreach($data as $item)
        <td>{{ $item }}</td>
    @endforeach
    </tr>

</table>
@endforeach
@endforeach
