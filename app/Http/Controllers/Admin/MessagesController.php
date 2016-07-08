<?php

namespace App\Http\Controllers\Admin;

use App\Mail;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{

    protected $client;
    public function __construct()
    {
        $this->client = new Mail();
    }

    public function index(){
        $messages = $this->client->getAllMessage();
        return view('admin.messages.index', compact('messages'));
    }

    public function create(){
        return view('admin.messages.create');
    }

    public function destroy($message_id){
        $this->client->deleteMessage($message_id);
        return redirect()->route('admin.messages.index');
    }

    public function store(Request $request){
        $title = $request->input('title');
        $content = $request->input('content');

        $sender_id = Auth::guard('admin')->user()->id;
        $params = [
            'title' => $title,
            'content' => $content,
            'target_type' => 'global',
            'sender_id' => $sender_id
        ];

        $this->client->createMessage($params);
        return redirect()->route('admin.messages.index');
    }
}
