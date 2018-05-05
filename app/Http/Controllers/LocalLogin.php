<?php

namespace App\Http\Controllers;
session_start();
use Illuminate\Http\Request;

class LocalLogin extends Controller
{
    public function index(Request $request){
        $loggedin = false;
        $string = file_get_contents(__DIR__."\users.json");
        $json = json_decode($string, true);

        foreach ($json as $person_name => $person_a) {
            if($person_a['username'] = $request->get('username')){
                if($person_a['password'] == md5($request->get('password'))){
                    $loggedin = true;
                }
            }
        }

        if($loggedin){
            setcookie('phpsessionid',session_id(),0,"/");
            $_SESSION['phpsessionid'] = session_id();
            return redirect('/csrf-main');
        }
        else{
            return "<script>alert('Login Failed!')</script>";
        }

    }
}
