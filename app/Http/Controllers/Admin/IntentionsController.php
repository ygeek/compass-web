<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Intention;
use App\AdministrativeArea;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Excel;
use App\User;
use DB;

class IntentionsController extends BaseController
{
    public function index(Request $request)
    {
        $name = $request->input('name');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $state = $request->input('state');
        $intentions_query = Intention::whereNotNULL('id');
        if ($name) {
            $intentions_query = $intentions_query->where('name', 'like', "%{$name}%");
        }

        if ($start_date) {
            $intentions_query = $intentions_query->whereDate('created_at', '>=', $start_date);
        }

        if ($end_date) {
            $intentions_query = $intentions_query->whereDate('created_at', '<=', $end_date);
        }

        if ($state) {
            $intentions_query = $intentions_query->where('state', $state);
        }

        $intentions = $intentions_query->paginate(20);
        return view('admin.intentions.index', compact('intentions', 'state', 'name', 'start_date', 'end_date'));
    }

    //分配
    public function update($intention_id)
    {
        $intention = Intention::find($intention_id);
        $intention->state = 'assigned';
        $intention->save();
        Flash::message('分配成功');
        return back();
    }

    //导出Excel
    public function exportToExcel($intention_id)
    {
        $user_intention = Intention::find($intention_id);
        $intentions = collect($user_intention['data'])->map(function ($intention) {
            $college = \App\College::find($intention['college_id']);
            $country_id = $college->country_id;

            $intention['country_id'] = $country_id;
            return $intention;
        });
        // var_dump($user_intention);
        // die;

        $user_phone_number = $user_intention->phone_number;
        $user_info = DB::table('users')->where('phone_number', $user_phone_number)->first();

        $estimate_input = json_decode($user_info->estimate_input, true);
        // 获取用户最新的意向单
        // $user_newest_intention = Intention::where('phone_number', $user_phone_number)->orderBy('updated_at', 'desc')->first();
        // $user_intention_arr = $user_intention->toArray();
        //
        //
        // foreach ($user_intention_arr as $key => $value) {
        //     // $value['user_scores'] = $user_newest_intention['user_scores'];
        //     var_dump($user_newest_intention->user_scores);
        // }
        // var_dump($user_intention_arr);
        // var_dump($user_newest_intention->user_scores);

        // return view('admin.intentions.excel', ['intention' => $user_intention, 'intentions' => $intentions]);
        Excel::create($user_intention->name . '意向单', function ($excel) use ($user_intention, $intentions, $estimate_input) {
            $excel->sheet('New sheet', function ($sheet) use ($user_intention, $intentions, $estimate_input) {
                $sheet->loadView('admin.intentions.excel', ['intention' => $user_intention, 'intentions' => $intentions, 'estimate_input' => $estimate_input, ]);
            });
        })->download();
    }
}
