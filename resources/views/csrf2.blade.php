<html>
<head>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <title>OAuth Test</title>
</head>

<body class="container">
<?php
//phpsessionid is already set, therefore setting csrf token again!
if (!isset($_COOKIE['csrfTokenCookie'])) {
    $cookie_val = \App\Http\Controllers\CSRF2::generateCSRFToken();
    setcookie('csrfTokenCookie', $cookie_val, 0, "/");
}

echo isset($_COOKIE['csrfTokenCookie']) ? $_COOKIE['csrfTokenCookie'] : "";
?>

<script type="text/javascript">

    function C(k) {
        return (document.cookie.match('(^|; )' + k + '=([^;]*)') || 0)[2]
    }

    function getCsrfCookie() {
        var hname = "csrfTokenCookie",
            hvalue = C('csrfTokenCookie');

        $("#csrf2_form").on("submit", function () {
            $(this).append("<input type='hidden' name='" + hname + " ' value=' " + hvalue + " '/><br/>");
        });

    }

    window.onload = getCsrfCookie;
</script>

<div class="row" style="margin-top: 50px">
    <div class="col-md-offset-5 col-md-3">
        <div class="form-login">
            <h4 style="text-align: center">CSRF 2 - Double Submit Cookie</h4>

            <form action="/submitWithCSRF2" method="POST" id="csrf2_form">
                <input type="text" name="text1" class="form-control input-sm chat-input" required placeholder="type here.."/>
                </br>
                <div class="wrapper" align="center">
                    <input class="btn btn-success" type="submit" value="Submit">
                </div>
            </form>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <a href="/csrf-main">CSRF 1 - Synchronizer Token Pattern</a>
    </div>
</div>
</body>

</html>