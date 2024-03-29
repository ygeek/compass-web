<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Pagination\LengthAwarePaginator;
use App\AdministrativeArea;
use App\College;
use App\Setting;
use App\SpecialityCategory;
use App\ArticleCategory;
use Illuminate\Http\Request;
use Overtrue\Pinyin\Pinyin;

use App\Http\Requests;
use Input;
use DB;

class CollegesController extends Controller
{
    public function index(Request $request){
        $areas = AdministrativeArea::get()->toTree();
        $speciality_categories = SpecialityCategory::all();
        $selected_country_id = $request->input('selected_country_id', 1);
        $selected_state_id = $request->input('selected_state_id');
        $selected_city_id = $request->input('selected_city_id');
        $college_name = $request->input('college_name');
        $selected_speciality_cateogry_id = $request->input('selected_speciality_cateogry_id');
        $rank_start = $request->input('rank_start', "");
        $rank_end = $request->input('rank_end', "");
        $selected_order = $request->input('selected_order', "domestic_ranking");

        $selected_go8 = $request->input('selected_go8');

        $selected_property = $request->input('selected_property');

        $colleges_query = College::with('administrativeArea.parent.parent');

        if($college_name){
            $colleges_query = $colleges_query->where(function ($query) use ($college_name) {
                $query->where('english_name', 'like', "%{$college_name}%")
                    ->orWhere('chinese_name', 'like', '%'.$college_name.'%');
            });
        }


        //区域查询
        $node_id = null;

        if($selected_city_id){
            $node_id = $selected_city_id;
        }else if($selected_state_id){
            $node_id = $selected_state_id;
        }else if($selected_country_id){
            $node_id = $selected_country_id;
        }

        if($node_id && $node_id!=-1){
            $area = AdministrativeArea::find($node_id);
            $descendants = $area->descendants()->lists('id');
            $descendants[] = $area->getKey();

            $colleges_query = $colleges_query->whereIn('administrative_area_id', $descendants);
        }

        //专业分类查询
        if($selected_speciality_cateogry_id){
            $colleges_query = $colleges_query->whereHas('specialities', function ($q) use ($selected_speciality_cateogry_id){
                $q->where('specialities.category_id', $selected_speciality_cateogry_id);
            });
        }

        //查询go8
        if($selected_go8){
          $condition = false;
          if($selected_go8 == 1){
            $condition = true;
          }

          $colleges_query = $colleges_query->where('go8', $condition);
        }

        //查询性质
        if($selected_property){
            $condition = 'private';
            if($selected_property == 1){
                $condition = 'public';
            }

            $colleges_query = $colleges_query->where('type', $condition);
        }

        $rankings = Setting::get('rankings');
        $tag = AdministrativeArea::find($selected_country_id);
        if ($rank_start!=="" || $rank_end!=="") {
            $rank_start_tmp = $rank_start;
            $rank_end_tmp = $rank_end;
            if ($rank_start===null || $rank_start==="") $rank_start_tmp=1;
            if ($rank_end===null || $rank_end==="") $rank_end_tmp=99999;
            $ranking_college = [];
            foreach ($rankings['rankings'] as $ranking) {
                $ranking_tag = $ranking['tag'];
                if (strpos($ranking_tag, $tag['name']) !== false) {
                    for ($i=intval($rank_start_tmp)-1;$i<intval($rank_end_tmp)&&$i<count($ranking['rank']);$i++){
                        array_push($ranking_college,$ranking['rank'][$i]['english_name']);
                    }
                }
            }
            $colleges_query = $colleges_query->whereIn('english_name',$ranking_college);
        }

        $colleges = [];
        if(strpos($selected_order, "_order")!== false){
            $tmp_tag = str_replace("_order", "", $selected_order);

            if($tmp_tag == 'international_ratio')
                $colleges = $colleges_query->orderBy($tmp_tag)->paginate(10);
            else
                $colleges = $colleges_query->orderBy($tmp_tag, 'desc')->paginate(10);
        }
        else {
            $colleges_english_name = collect($colleges_query->select('english_name')->get()->map(function($item){
                return $item->english_name;
            }));
            $tmp_tag = str_replace("_ranking", "", $selected_order);
            if($selected_order == 'domestic_ranking')
                $tmp_tag = $tag['name'];
            foreach ($rankings['rankings'] as $ranking) {
                $ranking_tag = $ranking['tag'];
                if (strpos($ranking_tag, $tmp_tag) !== false) {
                    $now_rank = collect($ranking['rank'])->map(function($item){
                        return $item['english_name'];
                    })->sortBy('rank')->toArray();
                    $colleges_english_name = $colleges_english_name->sortBy(function ($product, $key) use($now_rank) {
                        $count = count($now_rank);
                        for($index = 0;$index<$count;$index++) {
                            if(trim($product) == trim($now_rank[$index])){
                                return $index;
                            }
                        }
                        return 9999;
                    })->values()->all();
                    break;
                }
            }

            $colleges = $colleges_query->select('*')->whereIn('english_name',$colleges_english_name)->get();

            $all_array_colleges = $colleges->sortBy(function ($product, $key) use($colleges_english_name) {
                $count = count($colleges_english_name);
                for($index = 0;$index<$count;$index++) {
                    if(trim($product->english_name) == trim($colleges_english_name[$index])){
                        return $index;
                    }
                }
                return 9999;
            })->values()->all();

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $current_rank_items = collect($all_array_colleges)->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            $colleges= new LengthAwarePaginator($current_rank_items, count($all_array_colleges), $perPage, null, [
                'path' => route('colleges.index')
            ]);
        }

        $selected_country_id = $selected_country_id==-1?1:$selected_country_id;

        $res = compact(
            'areas',
            'currentPage',
            'speciality_categories',
            'colleges',
            'selected_speciality_cateogry_id',
            'selected_city_id',
            'selected_state_id',
            'selected_country_id',
            'college_name',
            'selected_go8',
            'selected_property',
            'rank_start',
            'rank_end',
            'selected_order'
        );
       
        if($request->input('ajax', false)) {
            $str = '';
            
            foreach($colleges as $k=>$college){
                $num1 = '';$num2 = '';   
                if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){$num1 = 1;} else {$num1 = 2;}}else{$num1= 2;}
                if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){$num2= 1;} else {$num2= 2;}}else{$num2= 3;}
                $pub = ($college->type=="public")?'公立':'私立';
                if($college->administrativeArea->parent)
                {
                    $name = $college->administrativeArea->parent->name;

                    if($college->administrativeArea->parent->parent){
                        $name = $college->administrativeArea->parent->parent->name;
                    }
                }

                $str .= '<div class="pinggu_xx50" >
                            <div class="pinggu_xx_name50">
                                <a href="'.route('colleges.show', $college->key).'" ><h2>
                                    <img src="'.app('qiniu_uploader')->pathOfKey($college->badge_path).'"><br />
                                    
                                    <div class="clear"></div>
                                    
                                    </h2></a>
                                    <h1>本国排名：'.$college->domestic_ranking.'<br><span style="background:url(/static/images/icon21.jpg) left no-repeat; background-size:20px; padding:0 0 0 20px;  font-size: 0.8em; text-align: right;">'.$college->administrativeArea->name.$name.'
                                                        </span><br>
                                        <a href="/estimate/step-1?selected_country_id='.$college->country_id.'&college_id='.$college->id.'" style="font-size:1.2em; line-height: 40px; color: #0000FF">测试录取率>></a><br>

                                        
                                   </h1>
                                   <div class="clear"></div>
                            </div> 
                            <div class="bot">
                                <div class="left" style=" float: left; width: 85%; height: 14px; line-height: 14px; color: #2b426e; text-align: left;">
                                    <span style="display:block; float:left;">'.$college->chinese_name.'</span><span style="background:#23e6bb;display:block; float:left; color:#fff; border-radius:3px; padding:1% 2%; font-size:0.8em; margin:0 0 0 5px;line-height: 10px;">'. $pub .'</span><br /><br />
                                    <span style="font-size:1.0em;width: 100%; text-align: left;">'.$college->english_name.'</span>
                                </div>
                                <div class="right" style=" float: right; width: 15%; text-align: right; margin-top: -7px;">
                                    <img src="/static/images/xin'.$num1.'.png" width="30" style=" cursor: pointer;" likeid="'.$num2.'" onclick="setLike(\''.$college->id.'\',$(this))" ><span id="shuzi'. $college->id .'">'. $college->like_nums .'</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>';

            }
            if($str){
                return $this->responseJson('ok', $str);
            }
            else
            {
                return $this->responseJson('error', '');
            }
          
        }else {
          return $this->view('colleges.index', $res);
        }

    }

    public function show($key, Request $request){
        $college = College::where('key', $key)->first();
        $college->read_count++;
        $college->save();

        $article_type = $request->input('article_type', '学校概况');

        $college->badge_url=app('qiniu_uploader')->pathOfKey($college->badge_path);
        $college->background_image_url=app('qiniu_uploader')->pathOfKey($college->background_image_path);

        $pinyin = new Pinyin();
        $article_key = $pinyin->permalink($article_type);

        if(in_array($article_key, ['xue-xiao-gai-kuang', 'lu-qu-qing-kuang', 'liu-xue-gong-lue', 'tu-pian'])){
            $category = ArticleCategory::where('key', $article_key)->first();

            $article_college = $college;
            if ($article_key == 'liu-xue-gong-lue'){
                if ($college->country->name == '澳洲'){
                    $article_college = College::where('chinese_name', '悉尼大学')->first();
                }
                if ($college->country->name == '美国'){
                    $article_college = College::where('chinese_name', '普林斯顿大学')->first();
                }
                if ($college->country->name == '英国'){
                    $article_college = College::where('chinese_name', '牛津大学')->first();
                }
                if ($college->country->name == '新西兰'){
                    $article_college = College::where('chinese_name', '奥克兰大学')->first();
                }
            }

            if ($article_college==null)
                $articles = [];
            else {
                $articles_query = $article_college->articles()->whereHas('category', function($q) use ($article_key){
                    return $q->where('key', $article_key);
                })->orderBy('articles.order_weight');

                if($request->input('desc', false)){
                    $articles_query = $articles_query->orderBy('articles.created_at', 'desc');
                }

                $articles = $articles_query->paginate(15);
            }

        }else{
            //学校专业
            $specialities_query = $college->specialities()->with('category', 'degree');
            $selected_degree_id = $request->input('selected_degree_id');
            $selected_category_id = $request->input('selected_category_id');
            $speciality_name = $request->input('speciality_name');

            if($selected_degree_id){
                $specialities_query = $specialities_query->where('degree_id', $selected_degree_id);
            }

            if($selected_category_id){
                $specialities_query = $specialities_query->where('category_id', $selected_category_id);
            }

            if($speciality_name){
                $specialities_query = $specialities_query->where('name', 'like', '%'.$speciality_name.'%');
            }

            $articles = $specialities_query->paginate(10);
        }

        $res = compact('college', 'article_key', 'articles');

        if($request->input('ajax', false)) {
            $str = '';
            
            foreach($articles as $speciality){
                $tmp = $college->administrativeArea->id;
                if ($college->administrativeArea->parent){
                    $tmp = $college->administrativeArea->parent->id;
                    if ($college->administrativeArea->parent->parent){
                        $tmp = $college->administrativeArea->parent->parent->id;
                    }
                }
                $estimate_url = route('estimate.step_second', ['selected_country_id' => $tmp, 'selected_degree_id' => $speciality->degree->id, 'speciality_category_id' => $speciality->category->id, 'speciality_name' => $speciality->name, 'cpm' => true, 'college_id' => $college->id]);
                
              

                $str .= '<li>
                            <h1>'. $speciality->name .'</h1>
                            <p>学术类型：'. $speciality->degree->name .'<br>专业方向：'. $speciality->category->chinese_name .'</p>
                            <a href="'.$estimate_url.'" style="top:60%; margin-top: 0px;">测试录取率</a>
                        </li>';

            }
            if($str){
                return $this->responseJson('ok', $str);
            }
            else
            {
                return $this->responseJson('error', '');
            }
        }else {
          return $this->view('colleges.show', $res);
        }
    }

    //院校排名
    public function rank(Request $request){
        $rank = $request->input('rank', 'us_new_ranking');
        if(!in_array($rank, ['us_new_ranking', 'times_ranking', 'qs_ranking'])){
            abort(404);
        }

        $selected_category_id = $request->input('category_id');
        $selected_ranking_id = $request->input('ranking_id');
        //如果没有传此参数 可以去取一个排行榜的分类id

        $rankings = Setting::get('rankings');
        
        if(!$selected_category_id){  $selected_category_id = '5acbe389-966f-f916-2a24-a96827a9d9fe'; } //add at 20170310 暂时写死了
        
        if(!$selected_category_id){
          if(count($rankings['rankings']) > 0){
            $first_ranking = $rankings['rankings'][0];
            $selected_category_id = $first_ranking['category_id'];
            $selected_ranking_id = $first_ranking['_id'];
          }
        }

        $ranking_categories = $rankings['categories'];

        $rankings_for_show = [];

        //当前显示的排行榜
        $activing_ranking = null;

        foreach ($rankings['rankings'] as $ranking) {
          if($ranking['category_id'] == $selected_category_id){
            $rankings_for_show[] = $ranking;
          }
        }


        if(!$selected_ranking_id && count($rankings_for_show) > 0){
          $selected_ranking_id = $rankings_for_show[0]['_id'];
        }

        foreach ($rankings_for_show as $ranking) {
          if($ranking['_id'] == $selected_ranking_id){
            $activing_ranking = $ranking;
            break;
          }
        }
        $index = 0;
        for ($index=0; $index < count($activing_ranking['rank']); $index++) {
          $activing_ranking['rank'][$index]['rank'] = $index + 1;
        }

        //取出系统中所有院校的英文名
        $exist_colleges_english_name = collect(DB::select('select english_name from colleges'))->map(function($item){
            return $item->english_name;
        });

        $rank_items = collect($activing_ranking['rank']);
        //先排序 再take
        $rank_items = $rank_items->sortBy('rank');

        $rank_items = $rank_items->map(function($rank_item) use ($exist_colleges_english_name){
            $rank_item['key'] = false;
            foreach ($exist_colleges_english_name as $tmp){
                if (trim($tmp)==trim($rank_item['english_name'])){
                    $rank_item['key'] = $tmp;
                    break;
                }
            }
            return $rank_item;
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $current_rank_items = $rank_items->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginated_rank_items= new LengthAwarePaginator($current_rank_items, count($rank_items), $perPage, null, [
                'path' => route('colleges.rank')
            ]);

        $res = [
          'colleges' => $paginated_rank_items,
          'rank' => $rank,
            'rankings' => $rankings,
          'ranking_categories' => $ranking_categories,
          'selected_category_id' => $selected_category_id,
          'selected_ranking_id' => $selected_ranking_id,
          'rankings_for_show' => $rankings_for_show
        ];
       
        if($request->input('ajax', false)) {
            $str = '';
            
            foreach($paginated_rank_items as $key=>$college){
                $class='';
                $sjpm = '';
                if(isset($college['world_ranking'])&&$college['world_ranking']) { $sjpm = $college['world_ranking']; } else{ $sjpm='-'; };
                if($key%2==1){ $class = 'class="yuanxiao_white"'; }
                $str .= '<li '.$class.'><h1>'. $college['rank'] .'</h1><h2>'. $college['chinese_name'] .'<br/>'. $college['english_name'] .'</h2><h3>'.$sjpm.'</h3><span><a href="'.route('colleges.show', ['key' => \App\College::generateKey($college['key']) ]).'">排名</a></span><div class="clear"></div></li>';

            }
            if($str){
                return $this->responseJson('ok', $str);
            }
            else
            {
                return $this->responseJson('error', '');
            }
        }else {
          return $this->view('colleges.rank', $res);
        }
    }

    public function getRandomHotColleges(Request $request){
        $number = $request->input('number', 4);
        $res = College::where('recommendatory', true)->paginate(4)->map(function($item){
            $item->toefl_score = $item->toeflRequirement('本科');
            $item->ielts_score = $item->ieltsRequirement('本科');
            $item->badge_path = app('qiniu_uploader')->pathOfKey($item->badge_path);
            $item->link = route('colleges.show', $item->key);
            return $item;
        });
        return $res;
    }
}
