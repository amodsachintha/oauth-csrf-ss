<?php

namespace App\Http\Controllers;
session_start();
use Facebook\Facebook;
use Illuminate\Http\Request;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;


class FacebookController extends Controller
{

    public function show()
    {
        $fb = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => env('API_VERSION'),
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'public_profile', 'user_posts', 'user_friends', 'publish_actions',];
        $loginUrl = $helper->getLoginUrl('https://www.oauthtest.lk/fbcallback', $permissions);
        $url = htmlspecialchars($loginUrl);
        return view('index')->with('url', $url);

    }

    public function fbcallback()
    {

        $fb = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => env('API_VERSION'),
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $_SESSION['FBRLH_state'] = $_GET['state'];

        try {
            $accessToken = $helper->getAccessToken();
//            return var_dump($accessToken);
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        $oAuth2Client = $fb->getOAuth2Client();
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        $tokenMetadata->validateAppId(env('FB_APP_ID'));
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }

        }

        $_SESSION['fb_access_token'] = (string)$accessToken;
        setcookie('fb_access_token', (string)$accessToken, time() + (86400 * 30), "/");
        $_SESSION['app_id'] = env('FB_APP_ID');
        $_SESSION['app_secret'] = env('FB_APP_SECRET');


        return redirect('/logged-in');

    }


    public function getUserDetails()
    {
        if (!isset($_SESSION['app_id']) || !isset($_SESSION['app_secret'])) {
            exit();
        }

        $fb = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => env('API_VERSION'),
        ]);

        try {
            $response = $fb->get('/me?fields=id,name,posts.limit(2)', $_SESSION['fb_access_token']);
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        try {
            $user = $response->getGraphUser();
            $graph_node = $response->getGraphNode();
        } catch (FacebookSDKException $e) {
            echo "Error";
            exit();
        }

        return view('member')
            ->with('userObject', $user)
            ->with('posts', $graph_node->asArray()['posts'])
            ->with('userFieldNames', $user->getFieldNames())
            ->with('propic', $this->getUserProfilePic($fb, $user->getId()));

    }


    public static function getPost($post_id)
    {
        $fb = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => env('API_VERSION'),
        ]);

        try {
            $request = '/' . $post_id . '?fields=full_picture,picture,caption,id';
            $res = $fb->get($request, $_SESSION['fb_access_token']);
        } catch (FacebookSDKException $exception) {
            echo "FAIL!";
        }
        return $graph_node = $res->getGraphNode()->asArray();
    }


    public function getUserProfilePic($fb, $user_id)
    {
        try {
            $response = $fb->get(
                '/' . $user_id . '/picture?redirect=0&type=normal',
                $_SESSION['fb_access_token']
            );
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        return $graphNode = $response->getGraphNode()->asArray();
    }


    public function postStatus(Request $request)
    {
        $fb = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => env('API_VERSION'),
        ]);

        try {
            $response = $fb->post(
                '/me/feed',
                array(
                    'message' => $request->message,
                ),
                $_SESSION['fb_access_token']
            );
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return redirect('/logged-in')->with('success', $response->getGraphNode()->asArray());
    }


    public function logout()
    {
        $fb = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => env('API_VERSION'),
        ]);
        try {
            session_destroy();
            return redirect($fb->getRedirectLoginHelper()->getLogoutUrl($_SESSION['fb_access_token'], 'https://www.oauthtest.lk'));
        } catch (FacebookSDKException $exception) {
            echo "FAIL";
        }
        return "https://www.oauthtest.lk";
    }


}
