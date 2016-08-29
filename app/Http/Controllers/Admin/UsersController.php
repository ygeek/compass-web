<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Flash;

class UsersController extends BaseController
{
    public function index(Request $request){
        $users= User::whereNotNULL('id')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'phone_number' => 'required'
        ]);

        $user = User::find($id);
        $user->update($request->all());
        $user->save();
        Flash::message('修改成功');
        return redirect()->route('admin.users.edit', $id);
    }
}
