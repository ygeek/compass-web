<?php

namespace App\Http\Controllers\Admin;

use App\College;
use App\Degree;
use App\Speciality;
use App\SpecialityCategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SpecialitiesController extends BaseController
{

    public function index($college_id, Request $request){
        $speciality_name = $request->input('speciality_name');
        $degree_id = $request->input('degree_id');
        $category_id = $request->input('category_id');
        $degrees = Degree::all();
        $categories = SpecialityCategory::all();

        $college = College::with('specialities.degree')->with('specialities.category')->find($college_id);
        $specialities = collect($college->specialities)->filter(function($item) use ($speciality_name, $degree_id, $category_id) {
            if($speciality_name && strpos($item->name, $speciality_name)===false){
                return false;
            }
            if ($degree_id && $item->degree->id != $degree_id){
                return false;
            }
            if ($category_id && $item->category->id != $category_id){
                return false;
            }
            return true;
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $current_rank_items = $specialities->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $specialities= new LengthAwarePaginator($current_rank_items, count($specialities), $perPage, null, [
            'path' => route('admin.colleges.specialities.index', [ 'college' => $college->id ])
        ]);

        return view('admin.specialities.index', compact('college', 'specialities', 'speciality_name', 'degree_id', 'degrees', 'category_id', 'categories'));
    }

    public function create($college_id){
        $college = College::with('specialities')->find($college_id);
        $degrees = Degree::all();
        $categories = SpecialityCategory::all();
        $speciality = new Speciality();

        return view('admin.specialities.create', compact('college', 'degrees', 'categories', 'speciality'));
    }

    public function store($college_id, Request $request){
        $this->validate($request, [
            'degree_id' => 'required',
            'name' => 'required',
            'category_id' => 'required'
        ]);

        if (count(Speciality::where('name', $request->input('name'))->where('degree_id', $request->input('degree_id'))->where('category_id', $request->input('category_id'))->where('college_id', $college_id)->get())!=0) {
            return redirect()->back()->withInput()->withErrors('专业名已存在');
        }

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
        $this->validate($request, [
            'degree_id' => 'required',
            'name' => 'required',
            'category_id' => 'required'
        ]);

        if (count(Speciality::where('name', $request->input('name'))->where('degree_id', $request->input('degree_id'))->where('category_id', $request->input('category_id'))->where('college_id', $college_id)->get())!=0) {
            return redirect()->back()->withInput()->withErrors('专业名已存在');
        }

        $speciality = Speciality::find($speciality_id);
        $speciality->update($request->all());
        return redirect()->route('admin.colleges.specialities.index', $college_id);
    }

    public function destroy($college_id, $speciality_id){
        $speciality = Speciality::find($speciality_id);
        $college_id = $speciality->college_id;
        $speciality->delete();
        return redirect()->route('admin.colleges.specialities.index', $college_id);
    }
}
