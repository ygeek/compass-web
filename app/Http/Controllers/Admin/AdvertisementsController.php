<?php

namespace App\Http\Controllers\Admin;

use App\Advertisement;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdvertisementsController extends BaseController
{
    public function pictureUpload(Request $request){
        $image = $request->file('image');
        if($image){
            $result = app('qiniu_uploader')->upload_file($image);
            return $this->okResponse($result);
        }else{
            return $this->errorResponse('没有上传图片');
        }
    }

    public function index(Request $request){
        $advertisements = Advertisement::paginate(15);
        return view('admin.advertisements.index', compact('advertisements'));
    }

    public function create(Request $request){
        $advertisement = new Advertisement();
        return view('admin.advertisements.create', compact('advertisement'));
    }

    public function edit($advertisement_id, Request $request){
        $advertisement = Advertisement::find($advertisement_id);
        return view('admin.advertisements.edit', compact('advertisement'));
    }

    public function update($advertisement_id, Request $request){
        $this->validate($request, [
            'link' => 'required'
        ]);

        $data = Advertisement::find($advertisement_id);
        $data->link = $request->input('link');
        $data->priority = $request->input('priority', 0);

        $background_image_path = $request->file('background_image_path');
        if($background_image_path){
            $result = app('qiniu_uploader')->upload_file($background_image_path);
            $key = $result['key'];
            $data->background_image_path = $key;
        }

        if($request->input('page_colleges_index', null) == 'on'){
            $data->page_colleges_index = true;
        }else{
            $data->page_colleges_index = false;
        }

        if($request->input('page_colleges_show', null) == 'on'){
            $data->page_colleges_show = true;
        }else{
            $data->page_colleges_show = false;
        }

        if($request->input('page_colleges_rank', null) == 'on'){
            $data->page_colleges_rank = true;
        }else{
            $data->page_colleges_rank = false;
        }

        if($request->input('page_estimate_index', null) == 'on'){
            $data->page_estimate_index = true;
        }else{
            $data->page_estimate_index = false;
        }

        if($data->save()){
            return redirect()->route('admin.advertisements.index');
        }
    }

    public function store(Request $request){
        $this->validate($request, [
            'link' => 'required'
        ]);

        $data = new Advertisement();
        $data->link = $request->input('link');
        $data->priority = $request->input('priority', 0);

        $background_image_path = $request->file('background_image_path');
        if($background_image_path){
            $result = app('qiniu_uploader')->upload_file($background_image_path);
            $key = $result['key'];
            $data->background_image_path = $key;
        }

        if($request->input('page_colleges_index', null) == 'on'){
            $data->page_colleges_index = true;
        }else{
            $data->page_colleges_index = false;
        }

        if($request->input('page_colleges_show', null) == 'on'){
            $data->page_colleges_show = true;
        }else{
            $data->page_colleges_show = false;
        }

        if($request->input('page_colleges_rank', null) == 'on'){
            $data->page_colleges_rank = true;
        }else{
            $data->page_colleges_rank = false;
        }

        if($request->input('page_estimate_index', null) == 'on'){
            $data->page_estimate_index = true;
        }else{
            $data->page_estimate_index = false;
        }

        if($data->save()){
            return redirect()->route('admin.advertisements.index');
        }
    }

    public function destroy($article_id){
        $data= Advertisement::find($article_id);
        $data->delete();
        return redirect()->route('admin.advertisements.index');
    }
}
