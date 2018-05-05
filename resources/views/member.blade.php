@if(!isset($_SESSION['fb_access_token']))
    {{exit()}}
@endif

<html>
<head>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <title>
        Logged in!
    </title>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/"><strong>SSS OAuth Test</strong></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li style="margin-right: 10px; margin-top: 5px"><img src="{{$propic['url']}}" width="40" style="border-radius: 40px"> {{$userObject['name']}}</li>
                <li><a href="/logout">Logout</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-left">
                <li class="active"><a href="/logged-in">Home</a></li>
                <li><a href="#">CSRF 1</a></li>
                <li><a href="#">CSRF 2</a></li>
            </ul>
        </div>
    </nav>

</head>
<body class="container">
<div class="row">
    <div class="col-md-12">

    </div>
</div>

@if(isset($_GET['success']))
    <script type="text/javascript">alert('Status updated successfully!');</script>
@endif


<div class="row">
    <div class="col-md-3">

    </div>
    <div class="col-md-6" style="border: solid #dbdbdb 1px; padding: 20px; border-radius: 10px; align-items: center; background-color: #ffffff; margin-bottom: 30px">
        <form action="/post-status" method="GET">
            <div class="form-group">
                <label for="message-box">Post Status on Facebook!</label>
                <textarea class="form-control" name="message" id="message-box" required></textarea>
            </div>
            <input type="submit" class="btn btn-primary" value="Post">
        </form>
    </div>
    <div class="col-md-3">

    </div>
</div>

<div class="row">
    <div class="col-md-12" align="center" style="margin-bottom: 20px; margin-top: 20px; align-items: center">
        <p class="font-weight-bold">Recent Posts</p>
    </div>
</div>

@foreach($posts as $post)
    <div class="row" style="margin-bottom: 30px">
        <div class="col-md-3">

        </div>
        <div class="col-md-6" style="border: solid #dbdbdb 1px; padding: 20px; border-radius: 10px; align-items: center; background-color: #ffffff">
            <img src="{{$propic['url']}}" width="40" style="border-radius: 40px"> &nbsp;
            @if(isset($post['story']))
                <kbd>{{$post['story']}}</kbd><br><br>
            @endif

            @if(isset($post['message']))
                <code>{{$post['message']}}</code><br>
            @endif

            {{$date = $post['created_time']->format('Y-M-d H:i')}}<br>
            <?php
            $postMedia = \App\Http\Controllers\FacebookController::getPost($post['id']);
            ?>
            @if(isset($postMedia['full_picture']))
                <img src="{{$postMedia['full_picture']}}" width="500">
            @endif
            {{--                        {{var_dump($postMedia)}}--}}
            {{--                    <p>{{$postMedia['caption']}}</p>--}}
        </div>
        <div class="col-md-3">

        </div>
    </div>
@endforeach

</body>


</html>