<?php

namespace App\Http\Controllers;

use App\AdministrativeArea;
use App\College;
use App\SpecialityCategory;
use App\ArticleCategory;
use Illuminate\Http\Request;
use Overtrue\Pinyin\Pinyin;

use App\Http\Requests;

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

        $colleges_query = College::with('administrativeArea.parent.parent');

        if($college_name){
            $colleges_query = $colleges_query->where('chinese_name', 'like', '%'.$college_name.'%');
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
        $colleges = $colleges_query->paginate(15);
        return view('colleges.index', compact('areas',
            'speciality_categories',
            'colleges',
            'selected_speciality_cateogry_id',
            'selected_city_id',
            'selected_state_id',
            'selected_country_id',
            'college_name'
        ));
    }

    public function show($key, Request $request){
        $college = College::where('key', $key)->first();
        $article_type = $request->input('article_type', '学校概况');
        $pinyin = new Pinyin();
        $article_key = $pinyin->permalink($article_type);

        $category = ArticleCategory::where('key', $article_key)->first();

        $articles = $college->articles()->whereHas('category', function($q) use ($article_key){
            return $q->where('key', $article_key);
        })->orderBy('articles.order_weight')->get();

        return view('colleges.show', compact('college', 'article_key', 'articles'));
    }
}
