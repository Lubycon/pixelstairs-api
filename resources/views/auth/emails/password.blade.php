<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css?family=Inconsolata" />
</head>
<body style="
    width: 100%;
    min-width: 320px;
    max-width: 720px;
    margin: 20px auto;
    color: #383838;
">
    <div id="header" style="
        width: 100%;
        height: auto;
        background: #fff;
        text-align: center;
    ">
        <img src="https://s3-ap-northeast-1.amazonaws.com/pixelstairsdev/assets/logo/text_b.png" align="middle" alt="pixel-logo" style="
            width: 40%;
            min-width: 120px;
            margin-bottom: 20px;
        ">
        <p style="
            margin: 0 0 20px 0;
            font-size: 14px;
        ">Connect your Creativity with the World</p>
    </div>
    <div id="body" style="
        padding: 20px;
        background: #fff;
    ">
        <div class="card" style="
            text-align: center;
        ">
            <p class="intro" style="
                text-align: center;
                font-weight: bolder;
                font-size: 30px;
            ">
                Your Password will be reset - !
            </p>
            <div class="desc" style="
                line-height: 170%;
                background: #fff;
                color: #444;
                padding: 20px;
                margin: 20px 0 40px;
                text-align: center;
                box-sizing: border-box;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
            ">
                Hi, <span class="username">{{ $user->nickname }}</span>.<br>
                당신의 패스워드는 무시무시한 저주에 걸렸습니다.<br>
                당장 리셋을 하지 않으면 당신의 패스워드는 영영 돌아오지 않습니다.<br>
                아래 쪽 버튼을 눌러 패스워드를 지키세요.
            </div>
        </div>
        <div class="card button-wrapper">
            <a class="btn" href="{{ env('WEB_URL') }}/certs/password/landing/{{ $token }}" style="
                display: inline-block;
                width: 100%;
                height: 50px;
                text-align: center;
                background: #383838;
                color: #fff;
                font-size: 18px;
                padding: 15px 30px;
                box-sizing: border-box;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                cursor: pointer;
                text-decoration: none;
            ">
                Reset
            </a>
        </div>
    </div>
    <div id="footer" style="
        width: 100%;
        margin-top: 50px;
    ">
        <div class="copyrights" style="
            text-align: center;
        ">Powered by 2017 Lubycon.co</div>
    </div>
</body>
</html>
