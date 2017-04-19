<?php

namespace App\Http\Controllers;

use App\AdministrativeArea;
use App\CoreRangeSetting;
use App\CountryDegreeExaminationMap;
use App\Degree;
use App\College;
use App\Estimate;
use App\Examination;
use App\Setting;
use App\SpecialityCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Uuid;
use File;

use Cookie;
use App\Http\Requests;

class EstimateController extends Controller
{
    public function getCollegesForSelect() {
      $path = storage_path() . '/colleges.json';
      $content = File::get($path);
      $colleges_data = json_decode($content, true)['colleges'];
      $areas = [];
      $colleges = [];

      foreach ($colleges_data as $college_data) {
        array_push($areas, $college_data['college_area']);
        array_push($colleges, [
          'name' => $college_data['college_name'],
          'major' => collect($college_data['college_major'])->reject(function ($name) {
                          return empty($name);
                      })->unique()->values()->all(),
          'area' => $college_data['college_area'],
        ]);
      }
      $areas = collect(array_unique($areas))->sortBy(function ($product, $key) {
          if ($product==="重庆")
              return iconv('UTF-8', 'GBK//IGNORE', "崇庆");
          return iconv('UTF-8', 'GBK//IGNORE', $product);
      });

      return $this->responseJson('ok', [
        'areas' => array_values($areas->toArray()),
        'colleges' => $colleges
      ]);
    }

    public function stepFirst(Request $request)
    {

        $cpm = (bool)($request->input('cpm', false));
        $selected_country_id = $request->input('selected_country_id', null);
        $selected_degree_id = $request->input('selected_degree_id', null);
        $selected_year = $request->input('selected_year', null);
        $selected_category_id = $request->input('selected_category_id', null);
        $selected_speciality_name = $request->input('selected_speciality_name');
        $college_id = $request->input('college_id', false);

        $countries = AdministrativeArea::countries()->get();
        $degrees = Degree::estimatable()->get();

        $now_year = date("Y");
        $years = [
            $now_year, $now_year + 1, $now_year + 2, '三年以后'
        ];

        $speciality_categories = SpecialityCategory::get();

        try {
          if($college_id){
            $college = College::find($college_id);
            $college->examinationScoreMap->map;
          }
        } catch (\Exception $e) {
          return "当前院校无法进行评估，请选择其他院校";
        }

        return $this->view('estimate.step_first', compact('countries', 'degrees', 'years', 'speciality_categories', 'selected_country_id', 'selected_degree_id', 'selected_year', 'selected_category_id', 'selected_speciality_name', 'cpm', 'college_id'));
    }

    public function getSpeciality(Request $request)
    {
        $college_id = $request->input('college_id', false);
        $degree_id = $request->input('degree_id', false);
        $category_id = $request->input('category_id', false);
        $country_id = $request->input('country_id', false);

        $speciality_categories_query = SpecialityCategory::with(['specialities' => function($query) use ($college_id, $degree_id, $category_id, $country_id){
          if($college_id){
            $query = $query->where('specialities.college_id', $college_id);
          }

          if($degree_id){
            $query = $query->where('specialities.degree_id', $degree_id);
          }

          if($category_id){
            $query = $query->where('specialities.category_id', $category_id);
          }

          if($country_id){
            $query = $query->where('specialities.country_id', $country_id)
                    ->orderByRaw('CONVERT( name USING gbk ) COLLATE gbk_chinese_ci ASC');
          }

        }]);

        $result = $speciality_categories_query->get();
        return $result;
    }

    public function stepSecond(Request $request)
    {
        $cpm = (bool)($request->input('cpm', false));
        $disable_pre_button = (bool)($request->input('disable_pre_button', false));
        $college_id = $request->input('college_id', false);

        if($college_id){
          $selected_college = College::find($college_id);
          $selected_country = $selected_college->country;
        }else{
          $selected_country = AdministrativeArea::find($request->input('selected_country_id'));
        }

        $selected_degree = Degree::find($request->input('selected_degree_id'));
        $selected_category_id = $request->input('speciality_category_id');
        $selected_speciality_name = $request->input('speciality_name');
        $selected_year = $request->input('selected_year', null);
        $estimate_checked = false;

        $user = Auth::user();
        if ($user!=null && $user->estimate!=null){
            $estimate_checked = true;
        }

        if($college_id){
          $college = College::find($college_id);
          $college_current_source_weight = $college->examinationScoreWeight()->where('college_degree.degree_id', $selected_degree->id)->first();
          //未设置当前专业对应的得分比例表
          if(is_null($college_current_source_weight)){
            return "当前院校该专业无法进行评估，请选择其他专业";
          }
        }

        return $this->view('estimate.step_second', compact('selected_degree', 'disable_pre_button', 'selected_country', 'selected_speciality_name', 'estimate_checked', 'selected_year', 'selected_category_id', 'cpm', 'college_id'));
    }

    /*
     * 生成评估结果
     */
    public function store(Request $request) {
        // TODO 获取cookie
        $estimate_id = $request->input('estimate_id');
        $college_id = $request->input('college_id', false);
        $cpm = (bool)($request->input('cpm', false));

        if ($estimate_id!=null){
            $data = Setting::get($estimate_id);
        }
        else{
            $data = $request->input('data');
        }

        if(is_string($data)){
            $data = json_decode($data, true);
        }

        $examinations = $data['examinations'];
        // 手机端
        if(isset($examinations['高考']['tag'])) {
            $student_temp_data['province'] = $examinations['高考']['tag'];
            $student_temp_data['score_without_tag'] = $examinations['高考']['score_without_tag'];
            $student_temp_data['high_school_average'] = $examinations['高中平均成绩']['score'];
            $student_temp_data['ielts'] = $examinations['雅思']['score'];
            $student_temp_data['ielts_average'] = $examinations['雅思']['sections'];
        }

        // 电脑端
        if(isset($examinations['大学平均成绩']['score'])) {
            $student_temp_data['college_name'] = isset($data['recently_college_name']) ? $data['recently_college_name'] : "-";
            $student_temp_data['recently_speciality_name'] = isset($data['recently_speciality_name']) ? $data['recently_speciality_name'] : "-";
            $student_temp_data['college_average'] = $examinations['大学平均成绩']['score'];
            $student_temp_data['ielts'] = $examinations['雅思']['score'];
            $student_temp_data['ielts_average'] = $examinations['雅思']['sections'];
        }
        $selected_country = AdministrativeArea::find($data['selected_country']);
        $selected_degree = Degree::find($data['selected_degree']);
        $selected_speciality_name = $data['selected_speciality_name'];

        $examinations = $data['examinations'];//需要将前端提交的数据修改为ArrayOfObject的形式 Object包含两个值 examination_id和score

        //需要计算院校性质
        if($selected_degree->name == '硕士'){
            $recently_college_name = $data['recently_college_name'];
            $recently_college_type = Estimate::getRecentlyCollegeType($recently_college_name);

            //补充院校性质考试类型到examinations里面
            $examinations['院校性质'] = [
                'score' => $recently_college_type
            ];

            //修改大学平均成绩 增加ta
            $examinations['大学平均成绩']['tag'] = $recently_college_type;
            $examinations['大学平均成绩']['score_without_tag'] = $examinations['大学平均成绩']['score'];
            $data['examinations'] = $examinations;
        }

        $data['examinations'] = collect($data['examinations'])->filter(function($item){
            return !!$item['score'];
        });

        $student_scores = Estimate::grabStudentScoreFromEstimateData($data);

        if($college_id){
          $college = College::find($college_id);

          $res = [
              'college_id' => $college->id,
              'score' => $college->calculateWeightScore($student_scores, $selected_degree)
          ];

          $requirement_info = Estimate::grabCollegeRequirementInfo($college, $data, $selected_speciality_name, $selected_degree);
          $res = array_merge($res, $requirement_info);

          if ($estimate_id == null){
              $user = Auth::user();
              if ($user!=null){
                  $estimate_id = Uuid::generate(4);
                  Setting::set('estimate-'.$estimate_id, $data);
                  $user->estimate = 'estimate-'.$estimate_id;
                  foreach ($data as $key => $value) {
                    if($key == 'examinations'){
                      foreach ($data['examinations'] as $examination_name => $examination_content) {
                        $user->setEstimateInput('examinations.' . $examination_name, $examination_content);
                      }
                    }else{
                      $user->setEstimateInput($key, $value);
                    }
                  }
                  $user->save();
              }
          } else {
              $estimate_id = str_replace('estimate-', '', $estimate_id);
          }

            $reduce_colleges = [$res];
            return $this->view('estimate.index',
                compact(
                    'reduce_colleges',
                    'examinations',
                    'selected_degree',
                    'selected_speciality_name',
                    'estimate_id',
                    'data',
                    'college_id',
                    'res',
                    'cpm',
                    'student_temp_data',
                    'res'
                )
            );
        }else{

          $colleges = $this->estimateColleges($selected_degree, $selected_speciality_name);

          $res = [];
          foreach ($colleges as $college){
              try{
                  $res[] = [
                      'college_id' => $college->id,
                      'score' => $college->calculateWeightScore($student_scores, $selected_degree)
                  ];
              }catch (\Exception $e){
                  continue;
              }
          }
          //Reduce结果
          $core_range_setting = (new CoreRangeSetting())->getCountryDegreeSetting($selected_country->id, $selected_degree->id);
          $reduce_result = Estimate::reduceScoreResult($res, $core_range_setting);
          //生成院校信息
          $reduce_colleges = Estimate::mapCollegeInfo($reduce_result, $selected_speciality_name, $selected_degree, $data);

          if ($estimate_id == null){
              $user = Auth::user();
              if ($user!=null){
                  $estimate_id = Uuid::generate(4);
                  Setting::set('estimate-'.$estimate_id, $data);
                  $user->estimate = 'estimate-'.$estimate_id;

                  foreach ($data as $key => $value) {
                    if($key == 'examinations'){
                      foreach ($data['examinations'] as $examination_name => $examination_content) {
                        $user->setEstimateInput('examinations.' . $examination_name, $examination_content);
                      }
                    }else{
                      $user->setEstimateInput($key, $value);
                    }
                  }

                  $user->save();
              }
          }
          else{
              $estimate_id = str_replace('estimate-', '', $estimate_id);
          }

          // TODO
          return $this->view('estimate.index',
              compact(
                'reduce_colleges',
                'examinations',
                'selected_degree',
                'selected_speciality_name',
                'estimate_id',
                'data',
                'cpm',
                'college_id',
                'student_temp_data',
                'res'
                )
          );
        }

    }


    //获取需要遍历的院校列表
    //条件为有对应学历的专业
    private function estimateColleges($degree, $speciality_name){
        return College::whereHas('specialities', function($query) use ($degree, $speciality_name){
            $query->where('specialities.degree_id', $degree->id)
                  ->where('specialities.name', $speciality_name);
        })->get();
    }

    public function stepSecondPost()
    {
        $data = $_POST;
        $fields = '';

        if(isset($data['college_id'])) {
          $fields .=  '<input type="hidden" name="college_id" value="'. $data['college_id'] .'">';
        }

        if(isset($data['cpm'])) {
          $fields .=  '<input type="hidden" name="cpm" value="'. $data['cpm'] .'">';
        }

        $str = '<form action="/estimate" id="formid" method="post">';
        $str .= '<input type="hidden" name="data" value=\''.  json_encode($_POST).'\' ><input type="hidden" name="_token" value="'.  csrf_token().'" >' ;
        $str .= $fields;
        $str .= '</form><script>document.getElementById("formid").submit();</script>';
        echo $str;

    }

    public function stepSecondForm()
    {
        $value = $_POST['value'];
        $groups = $_POST['groups'];
        $key = $_POST['key'];
        $str = '';
        $groups = $this->object_to_array(json_decode($groups));

        $val = $groups[$key];

        if(is_object($val)) $val = get_object_vars ($val);
        $leixing = $val['selects'][$value];
        $leixingOption = '';
        foreach($val['selects'] as $k=>$option)
        {
            $seled = '';
            if($k==$value) $seled = 'selected="selected"';
            $leixingOption .= '<option value="'.$k.'"   '.$seled.'>'.$option.'</option>';
        }

        $leixingAddhtml = '';

        foreach($val['examinations'][$value]['sections'] as $k=>$v)
        {
            $style = '';

            if($k==3) $style = 'style="margin-right:0px;"';
            $leixingAddhtml .= '<input type="hidden"  name="examinations['.$leixing.'][sections]['.$k.'][name]" value="'.$v['name'].'"  >
                <input type="number" class="login_resgister_input" name="examinations['.$leixing.'][sections]['.$k.'][score]"  value="'.$v['score'].'" placeholder="'.$v['name'].'"  '.$style.'  >
                ';
        }
        $leixingHidden = '';
        foreach($val['examinations'][$value] as $k=>$v)
        {
            if(!is_array($v)&&!is_object($v)) $leixingHidden .= '<input type="hidden"  name="examinations['.$leixing.']['.$k.']" value="'.$v.'"  >
                ';
        }
        $placed = '';
        if($leixing=='雅思') $placed = '0~9';
        if($leixing=='托福IBT') $placed = '0~120';
        if($leixing=='ACT') $placed = '0~36';
        if($leixing=='SAT') $placed = '0~2400';
        if($leixing=='GRE') $placed = '260~340';
        if($leixing=='GMAT') $placed = '200~800';

        $score = '';
        if(isset($val['examinations'][$value]['score']))
        {
            $score = $val['examinations'][$value]['score'];
        }

        $str .=
        '<div class="select_text">

            <select name="examinations['.$leixing.']" class="select02" onchange="choseInputs($(this).val(),'.$key.')" >
                '.$leixingOption.'
            </select>
            <input name="examinations['.$leixing.'][score]" type="number" ismust="1" errormsg="'.$leixing.'成绩未填写!" class="login_resgister_input01 " value="'.$score.'"  placeholder="'.$placed.'">
        </div>
        <!--听说读写-->
        <div class="select_radio " >
            '.$leixingAddhtml.'
        </div>

        '.$leixingHidden;


        return json_encode($str);
    }

    function object_to_array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val) {
            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

}
