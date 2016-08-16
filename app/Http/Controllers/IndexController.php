<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\AdministrativeArea;
use App\Degree;
use App\SpecialityCategory;
class IndexController extends Controller
{
    public function index(){
        $countries = AdministrativeArea::countries()->with('children')->get();
        $degrees = Degree::estimatable()->get();

        $now_year = date("Y");
        $years = collect([$now_year, $now_year + 1, $now_year + 2, '三年以后'])->map(function($item){
            return [
                'name' => $item,
                'id' => $item
            ];
        });
        $speciality_categories = collect(SpecialityCategory::with('specialities')->get())->map(function($item){
            $item['name']=$item['chinese_name'];
            return $item;
        });
        return $this->view('index.index', compact('countries', 'degrees', 'years', 'speciality_categories'));
    }
}
