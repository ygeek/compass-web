<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Intention;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class IntentionsController extends Controller
{
    public function index(){
        $intentions = Intention::paginate(20);
        return view('admin.intentions.index', compact('intentions'));
    }
}
