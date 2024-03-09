<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EncContoller extends Controller
{
    function prosesGoPost(Request $request, $data) {
        $decryptedPath = Crypt::decrypt($data);

        if ($decryptedPath === 'dologin') {


            return redirect()->action('App\Http\Controllers\AuthController@dologin');
        } else {
            return redirect('/');
        }

    }
}
