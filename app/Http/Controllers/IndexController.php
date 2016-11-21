<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\AdministrativeArea;
use App\Degree;
use App\SpecialityCategory;
use Illuminate\Support\Facades\Auth;
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
        $speciality_categories = collect(SpecialityCategory::get())->map(function($item){
            $item['name']=$item['chinese_name'];
            return $item;
        });
        $estimate_checked = false;
        $user = Auth::user();
        if ($user!=null && $user->estimate!=null){
            $estimate_checked = true;
        }
        return $this->view('index.index', compact('countries', 'degrees', 'years', 'speciality_categories', 'estimate_checked'));
    }

    public function getSpeciality(Request $request){
        $speciality_categories = collect(SpecialityCategory::with('specialities')->get())->map(function($item){
            $item['name'] = $item['chinese_name'];
            $item['specialities'] = collect($item['specialities'])->unique()->values()->all();
            return $item;
        });
        return $speciality_categories;
    }
}
