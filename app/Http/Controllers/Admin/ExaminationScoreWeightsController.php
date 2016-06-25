<?php

namespace App\Http\Controllers\Admin;

use App\ExaminationScoreWeight;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ExaminationScoreWeightsController extends BaseController
{
    public function index(){

    }

    public function store(Request $request){
        ExaminationScoreWeight::create($request->all());
    }
}
