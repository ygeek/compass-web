<?php

namespace App\Http\Controllers\Admin;

use App\College;
use App\Degree;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class CollegesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colleges = College::paginate(20);
        return view('admin.colleges.index', compact('colleges'));
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
        DB::transaction(function() use ($request){
            $college = new College($request->all());
            $college->save();
            $degree_ids = $request->input('degree_ids');
            $college->degrees()->attach($degree_ids);
            $college->save();
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
        $college = College::find($id);
        $college->update($request->all());

        $degree_ids = $request->input('degree_ids');
        $college->degrees()->sync($degree_ids);

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
