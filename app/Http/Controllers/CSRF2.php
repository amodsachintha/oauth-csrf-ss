<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CSRF2 extends Controller
{
    static $csrf_token;

    public static function generateCSRFToken()
    {
        $csrf_token = strtolower(md5(base64_encode(openssl_random_pseudo_bytes(30))));
        return $csrf_token;
    }

    public function submit(Request $request)
    {
        $cookies_from_header = $request->server->get('HTTP_COOKIE');
        $cookie_from_request = $request->request->get('csrfTokenCookie_');
        $all_cookies_from_header = explode(';', $cookies_from_header);

        foreach ($all_cookies_from_header as $cookie) {
            $cookie_name = trim(explode('=', $cookie)[0]);
            $cookie_value = trim(explode('=', $cookie)[1]);

            if (strcmp($cookie_name, 'csrfTokenCookie') == 0) {
                if (strcmp($cookie_value, $cookie_from_request) == 0) {
                    return "<script>alert('Submit Successful!" . " CSRF token(header):" . $cookie_value . "'); window.location.href='/csrf2'</script>";
                } else {
                    return "<script>alert('Failed!')</script>";
                }
            }

        }
        return "<script>alert('Cookie not Set!')</script>";
    }
}
