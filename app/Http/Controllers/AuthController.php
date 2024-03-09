<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Auditrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login() {
        return view('login'); // login.blade.php
    }

    public function dologin(Request $request) {
        //Log::debug('u: '.$request->input('email').', p: '.$request->input('password'));

//        if (Auth::attempt($request->only('email', 'password'))) {
//
//            return redirect('/');
//        }

        $clientGroupId = env('APP_CLIENT_GROUP_ID', '');
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'privilege' => 'ROOT']) ||
            Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'privilege' => 'B2B_RESELLER'])) {

            $request->session()->put("reseller_id", DB::table('users')->select('client_id')->where('email', '=', $request->input('email'))->get()[0]->client_id);
            $request->session()->put("email", $request->input('email'));
            $request->session()->put("privilege", DB::table('users')->select('privilege')->where('email', '=', $request->input('email'))->get()[0]->privilege);
            Auditrail::save_auditrail($request->session()->get('email'), $request->ip(), "LOGIN", "DO LOGIN || SUCCESS", $request->getHttpHost());

            return redirect('/');
        }

        Auditrail::save_auditrail($request->input('email'), $request->ip(), "LOGIN", "DO LOGIN || FAILED -> BAD USERNAME PASSWORD", $request->getHttpHost());
        //return redirect('/login');
        return redirect()->back()->with('message', 'Wrong username and or password.');
    }

    public function logout(Request $request) {
        Auditrail::save_auditrail($request->session()->get('email'), $request->ip(), "LOGOUT", "DO LOGOUT || SUCCESS", $request->getHttpHost());
        Auth::logout();
        return redirect(url()->previous());
    }

    public function doSignUp(Request $request) {

    }
}
