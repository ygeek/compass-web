<?php

namespace App\Http\Controllers\Admin;

use App\College;
use App\AdministrativeArea;
use App\Degree;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Flash;
class CollegesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $college_name = $request->input('college_name');
        $country_id = $request->input('country_id');
        $countries = AdministrativeArea::countries()->get();
        $colleges_query = College::whereNotNULL('id')->with('country');
        if($college_name){
            $colleges_query = $colleges_query->where('chinese_name', 'like', "%{$college_name}%");
        }

        if($country_id){
            $colleges_query = $colleges_query->where('country_id', $country_id);
        }

        $colleges = $colleges_query->paginate(20);
        return view('admin.colleges.index', compact('colleges', 'countries', 'college_name', 'country_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas = \App\AdministrativeArea::get()->toTree()->toJson();
        $college = new \App\College;
        $city = null;
        $country = null;
        $state = null;
        $degrees = Degree::all();
        return view('admin.colleges.create', compact('college', 'areas', 'city', 'country', 'state', 'degrees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'administrative_area_id' => 'required',
            'chinese_name' => 'required|unique:colleges',
            'english_name' => 'required|unique:colleges',
            'degree_ids' => 'required'
        ]);

        DB::transaction(function() use ($request){
            $college = new College($request->all());

            if($request->input('hot', null) == 'on'){
              $college->hot = true;
            }else{
              $college->hot = false;
            }

            if($request->input('recommendatory', null) == 'on'){
              $college->recommendatory = true;
            }else{
              $college->recommendatory = false;
            }

            $badge_path = $request->file('badge_path');
            if($badge_path){
                $result = app('qiniu_uploader')->upload_file($badge_path);
                $key = $result['key'];
                $college->badge_path = $key;
            }

            $background_image_path = $request->file('background_image_path');
            if($background_image_path){
                $result = app('qiniu_uploader')->upload_file($background_image_path);
                $key = $result['key'];
                $college->background_image_path = $key;
            }

            $icon_path = $request->file('icon_path');
            if($icon_path){
                $result = app('qiniu_uploader')->upload_file($icon_path);
                $key = $result['key'];
                $college->icon_path = $key;
            }

            $college->save();
            $degree_ids = $request->input('degree_ids');
            $college->degrees()->attach($degree_ids);
            $college->save();
            Flash::message('添加成功');
        });

        return redirect()->route('admin.colleges.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $college = College::find($id);
        return view('admin.colleges.show', compact('college'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $college = College::with('degrees')->find($id);

        $city = null;
        $country = null;
        $state = null;
        $degrees = Degree::all();

        $node = $college->administrativeArea;
        $ancestors = $node->getAncestors();
        if($ancestors->count() == 2){
          $country = $ancestors[0]->id;
          $state = $ancestors[1]->id;
          $city = $node->id;
        }else if($ancestors->count() == 1){
          $country = $ancestors[0]->id;
          $state = $node->id;
        }else{
          $country = $node->id;
        }

        $areas = \App\AdministrativeArea::get()->toTree()->toJson();
        return view('admin.colleges.edit', compact('college', 'areas', 'state', 'country', 'city', 'degrees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'administrative_area_id' => 'required',
            'chinese_name' => 'required|unique:colleges',
            'english_name' => 'required|unique:colleges',
            'degree_ids' => 'required'
        ]);

        $college = College::find($id);
        $college->update($request->all());

        if($request->input('hot', null) == 'on'){
          $college->hot = true;
        }else{
          $college->hot = false;
        }

        if($request->input('recommendatory', null) == 'on'){
          $college->recommendatory = true;
        }else{
          $college->recommendatory = false;
        }

        $badge_path = $request->file('badge_path');
        if($badge_path){
            $result = app('qiniu_uploader')->upload_file($badge_path);
            $key = $result['key'];
            $college->badge_path = $key;
        }

        $background_image_path = $request->file('background_image_path');
        if($background_image_path){
            $result = app('qiniu_uploader')->upload_file($background_image_path);
            $key = $result['key'];
            $college->background_image_path = $key;
        }

        $icon_path = $request->file('icon_path');
        if($icon_path){
            $result = app('qiniu_uploader')->upload_file($icon_path);
            $key = $result['key'];
            $college->icon_path = $key;
        }

        $college->save();

        $degree_ids = $request->input('degree_ids');
        if($degree_ids){
            $college->degrees()->sync($degree_ids);
        }

        Flash::message('修改成功');
        return redirect()->route('admin.colleges.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $college = College::find($id);
        $college->delete();
        return redirect()->route('admin.colleges.index');
    }
}
