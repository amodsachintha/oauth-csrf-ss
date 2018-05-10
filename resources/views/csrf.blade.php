<html>
<head>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <title>OAuth Test</title>
</head>

<body class="container">

<script type="text/javascript">
    function getCSRFToken() {
        $.post('/csrftokenendpoint', {
                'phpsessionid': C('phpsessionid')
            }, function (response) {
                var hname = "csrf_token",
                    hvalue = response.toString();
                console.log(response);
                $("#csrf1_form").on("submit", function () {
                    $(this).append("<input type='hidden' name='" + hname + " ' value=' " + hvalue + " '/><br/>");
                });
            }
        )
    }

    function C(k) {
        return (document.cookie.match('(^|; )' + k + '=([^;]*)') || 0)[2]
    }

    window.onload = getCSRFToken;
</script>


<div class="row" style="margin-top: 50px">
    <div class="col-md-offset-5 col-md-3">
        <div class="form-login">
            <h4 style="text-align: center">CSRF 1 - Synchronizer Token Pattern</h4>

            <form action="/submitWithCSRF1" method="POST" id="csrf1_form">
                <input type="text" name="text1" class="form-control input-sm chat-input" required placeholder="type here.."/>
                <br>
                <div class="wrapper" align="center">
                    <input class="btn btn-success" type="submit" value="Submit">
                </div>
            </form>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
<a href="/csrf2">CSRF 2 - Double Submit Cookie</a>
    </div>
</div>
</body>

</html>