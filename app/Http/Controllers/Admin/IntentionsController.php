<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Intention;
use App\AdministrativeArea;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Excel;

class IntentionsController extends Controller
{
    public function index(Request $request){
        $name = $request->input('name');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $state = $request->input('state');
        $intentions_query = Intention::whereNotNULL('id');
        if($name){
            $intentions_query = $intentions_query->where('name', 'like', "%{$name}%");
        }

        if($start_date){
            $intentions_query = $intentions_query->whereDate('created_at', '>=', $start_date);
        }

        if($end_date){
            $intentions_query = $intentions_query->whereDate('created_at', '<=', $end_date);
        }

        if ($state) {
            $intentions_query = $intentions_query->where('state', $state);
        }

        $intentions = $intentions_query->paginate(20);
        return view('admin.intentions.index', compact('intentions', 'state', 'name', 'start_date', 'end_date'));
    }

    //分配
    public function update($intention_id){
        $intention = Intention::find($intention_id);
        $intention->state = 'assigned';
        $intention->save();
        Flash::message('分配成功');
        return back();
    }

    //导出Excel
    public function exportToExcel($intention_id){
        $intention = Intention::find($intention_id);
        Excel::create($intention->name . '意向单', function($excel) use ($intention){
            $excel->sheet('New sheet', function($sheet) use ($intention){
                $sheet->loadView('admin.intentions.excel', ['intention' => $intention]);
            });
        })->download();
    }
}
