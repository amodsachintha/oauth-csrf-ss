<html>
<head>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <title>OAuth Test</title>
</head>

<body class="container">


<div class="row" style="margin-top: 100px">
    <div class="col-md-offset-5 col-md-3">
        <div class="form-login">
            <h3 style="text-align: center">OAuth Test Website SSS</h3>
            <h4 style="text-align: center">Welcome back.</h4>


            <form action="/login" method="POST" id="login_form" name="loginform">
                <input type="text" name="username" class="form-control input-sm chat-input" required placeholder="username"/>
                </br>
                <input type="password" name="password" class="form-control input-sm chat-input" required placeholder="password"/>
                </br>
                <div class="wrapper" align="center">
                    <input class="btn btn-primary" type="submit" value="login">
                </div>
            </form>


        </div>
        <p style="text-align: center"><code>- or - </code></p>
        <div align="center">
            <a href="{{$url}}" class="btn btn-info">Login with Facebook</a>
        </div>

    </div>
</div>
</body>

</html>