<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home.index');
    }

    public function messages(){
        $message_client = new Mail();
        $messages = $message_client->getUnreadMessage(Auth::user()->id);

        return view('home.messages', compact('messages'));
    }

    public function readMessage($message_id){
        $message_client = new Mail();
        $message_client->readMessage(Auth::user()->id, $message_id);
        return redirect()->route('home.messages');
    }
}
