<?php

namespace App\Http\Controllers;

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
        $selected_country_id = $request->input('selected_country_id');
        $selected_state_id = $request->input('selected_state_id');
        $selected_city_id = $request->input('selected_city_id');
        $college_name = $request->input('college_name');
        $selected_speciality_cateogry_id = $request->input('selected_speciality_cateogry_id');

        $selected_go8 = $request->input('selected_go8');

        $colleges_query = College::with('administrativeArea.parent.parent');

        if($college_name){
            $colleges_query = $colleges_query->where('chinese_name', 'like', '%'.$college_name.'%')
                                             ->orWhere('english_name', 'like', "%{$college_name}%");
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

        if($node_id){
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

        $colleges = $colleges_query->paginate(15);
        return view('colleges.index', compact('areas',
            'speciality_categories',
            'colleges',
            'selected_speciality_cateogry_id',
            'selected_city_id',
            'selected_state_id',
            'selected_country_id',
            'college_name',
            'selected_go8'
        ));
    }

    public function show($key, Request $request){
        $college = College::where('key', $key)->first();
        $article_type = $request->input('article_type', '学校概况');
        $pinyin = new Pinyin();
        $article_key = $pinyin->permalink($article_type);

        if(in_array($article_key, ['xue-xiao-gai-kuang', 'lu-qu-qing-kuang', 'liu-xue-gong-lue', 'tu-pian'])){
            $category = ArticleCategory::where('key', $article_key)->first();

            $articles_query = $college->articles()->whereHas('category', function($q) use ($article_key){
                return $q->where('key', $article_key);
            })->orderBy('articles.order_weight');

            if($request->input('desc', false)){
                $articles_query = $articles_query->orderBy('articles.created_at', 'desc');
            }

            $articles = $articles_query->get();

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

            $articles = $specialities_query->paginate(15);
        }

        return view('colleges.show', compact('college', 'article_key', 'articles'));
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
            if($exist_colleges_english_name->contains($rank_item['english_name'])){
                $rank_item['key'] = $rank_item['english_name'];
            }
            return $rank_item;
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $current_rank_items = $rank_items->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginated_rank_items= new LengthAwarePaginator($current_rank_items, count($rank_items), $perPage, null, [
                'path' => route('colleges.rank')
            ]);

        return view('colleges.rank', [
          'colleges' => $paginated_rank_items,
          'rank' => $rank,
          'ranking_categories' => $ranking_categories,
          'selected_category_id' => $selected_category_id,
          'selected_ranking_id' => $selected_ranking_id,
          'rankings_for_show' => $rankings_for_show
        ]);
    }

    public function getRandomHotColleges(Request $request){
        $number = $request->input('number', 4);
        $res = College::where('recommendatory', true)->orderByRaw('RAND()')->limit(4)->get()->map(function($item){
            $item->toefl_score = $item->toeflRequirement('本科');
            $item->ielts_score = $item->ieltsRequirement('本科');
            $item->badge_path = app('qiniu_uploader')->pathOfKey($item->badge_path);
            return $item;
        });
        return $res;
    }
}
