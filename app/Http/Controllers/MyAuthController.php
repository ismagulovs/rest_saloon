<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;

class MyAuthController extends Controller
{

    public function getLogin(){
        return view('auth.login');
    }

    public function postLogin(AuthRequest $request){

      //  dd($request);
        $user = Admin::where('email', $request->input('email'))->first();
        if($user->password == $request->input('password')){
            session(['user' => $user->id]);
            return redirect('/home');
        }else{
            return redirect('/login')
                ->withErrors('неверный пароль')
                ->withInput();
        }
    }


    public function getLogout(){
        session()->put('user', null);

        //  dd(session()->all());
        return redirect('/admin/login');

    }
}
