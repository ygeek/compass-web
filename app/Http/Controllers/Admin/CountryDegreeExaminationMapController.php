<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CountryDegreeExaminationMapController extends BaseController
{
    public function index(Request $request){
        $country = $request->input('country');
        $degree = $request->input('degree');

        $map = \App\CollegeDegreeExaminationMap::getExaminationsWith($country, $degree);
        return $this->responseJson('ok', $map);
    }
}
