<?php

namespace App\Http\Controllers;
session_start();
use Illuminate\Http\Request;


class CSRF1 extends Controller
{

    public function generateCSRFToken(Request $request){
        if(isset($_SESSION['CSRF_TOKEN']))
            return $_SESSION['CSRF_TOKEN'];

        if($_SESSION['phpsessionid'] = $request['phpsessionid']){
            $_SESSION['CSRF_TOKEN'] = strtolower(md5(str_random(32)));
            return $_SESSION['CSRF_TOKEN'];
        }
        else
            return "CSRF Failure!";
    }



    public function submit(Request $request){
        $text = $request->get('text1');
        $csrf_token = $request->get('csrf_token_');


        if(strcmp($csrf_token,$_SESSION['CSRF_TOKEN']) == 0){
            return "<script>alert('Submit Successful!"." CSRF token:".$_SESSION['CSRF_TOKEN']."'); window.location.href='/csrf-main'</script>";
        }
        else{
            return "<script>alert('Failed!')</script>";
        }

    }

}
