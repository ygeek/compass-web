<?php

namespace App\Http\Controllers\Admin;

use App\College;
use App\Degree;
use App\Speciality;
use App\SpecialityCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SpecialitiesController extends Controller
{

    public function index($college_id){
        $college = College::with('specialities')->find($college_id);
        return view('admin.specialities.index', compact('college'));
    }

    public function create($college_id){
        $college = College::with('specialities')->find($college_id);
        $degrees = Degree::all();
        $categories = SpecialityCategory::all();
        $speciality = new Speciality();

        return view('admin.specialities.create', compact('college', 'degrees', 'categories', 'speciality'));
    }

    public function store($college_id, Request $request){
        $college = College::with('specialities')->find($college_id);
        $speciality = new Speciality($request->all());
        $speciality->college_id = $college_id;
        if($speciality->save()){
            return redirect()->route('admin.colleges.specialities.index', $college_id);
        }
    }

    public function edit($college_id, $speciality_id){
        $college = College::with('specialities')->find($college_id);
        $speciality = Speciality::find($speciality_id);
        $degrees = Degree::all();
        $categories = SpecialityCategory::all();
        return view('admin.specialities.edit', compact('speciality', 'college', 'degrees', 'categories'));
    }

    public function update($college_id, $speciality_id, Request $request){
        $speciality = Speciality::find($speciality_id);
        $speciality->update($request->all());
        return redirect()->route('admin.colleges.specialities.index', $college_id);
    }
}
