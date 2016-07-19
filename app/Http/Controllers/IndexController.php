<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\AdministrativeArea;
use App\Degree;
class IndexController extends Controller
{
    public function index(){
        $countries = AdministrativeArea::countries()->with('children')->get();
        $degrees = Degree::estimatable()->get();

        $now_year = date("Y");
        $years = collect([$now_year, $now_year + 1, $now_year + 2, $now_year + 3])->map(function($item){
            return [
                'name' => $item,
                'id' => $item
            ];
        });
        return $this->view('index.index', compact('countries', 'degrees', 'years'));
    }
}
