<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\User;
use Session;
use Input;
use Validator;
use Response;


class UserManagementController extends Controller
{

    public function index()
    {
        $data = [
            'users' => User::all()
        ];
        return view('admin.user-management',$data);
    }

    public function add(Request $request)
    {
        $v = \Validator::make($request->all(), [    
            'name'                  => 'required',
            'email'                 => 'required|unique:users,email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            ]);
        if ( $v->fails() ) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        else{
        $user = $request->input();
        $user['password'] = bcrypt($user['password']);
        $result = User::create($user);
        Session::put('alert-success', 'User berhasil ditambahkan');
            return Redirect::back();
        }
        
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->forceDelete();
        Session::put('alert-success', 'User berhasil dihapus');
        return Redirect::back();
    }

}