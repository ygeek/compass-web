<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Http\JsonResponse;

use Jenssegers\Agent\Agent;
use Request;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected function responseJson($type, $content = "", $http_code = null){
        $res = [];
        $http_status = 200;
        if($type == 'error'){
            if(!is_null($http_code)){
                $http_status = $http_code;
            }else{
                $http_status = 200;
            }
        }

        $res["status"] = $type;
        $res["data"] = $content;

        $response = new JsonResponse($res, $http_status);
        if(Request::header('Origin')){
            $response->header('Access-Control-Allow-Origin', \Request::header('Origin'));
        }else{
            $response->header('Access-Control-Allow-Origin', '*');
        }

        return $response;
    }

    protected function errorResponse($message){
        $error =  [
            'message' => $message
        ];
        return $this->responseJson('error', $error, 422);
    }

    protected function okResponse($content=''){
        return $this->responseJson('ok', $content);
    }
    
    protected function view($view = null, $data = [], $mergeData = []){
        //判断是否是移动设备 如果是跳转到移动页面
        $agent = new Agent();
        if($agent->isMobile()){
            $view = "m.{$view}";
        }

        return view($view, $data, $mergeData);
    }
}
