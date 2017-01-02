<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body style="
    width: 100%;
    min-width: 320px;
    max-width: 720px;
    margin: 0 auto;
    border-top: none;
    font-family: Helvetica, sans-serif;
    box-shadow: 0 0 10px 0 rgba(0,0,0,0.4);
">
    <div id="header" style="width: 100%; height: 70px; line-height: 60px; background: #444; text-align: center;">
        <img src="http://api.lubycon.com/image/logo/header_logo.png" style="width: 20%; min-width: 120px;" align="middle" alt="lubycon-logo-title">
    </div>
    <div id="body" style="padding: 20px; background: #77e4c9; color: #fff;">
        <div class="card" style="text-align: center;">
            <img src="http://api.lubycon.com/image/logo/logo.png" style="width: 20%; margin: 20px 0;" align="middle" alt="lubycon-logo">
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 20px;">
                 HELLO! <span class="username" style="font-size: 35px; font-weight: 600;">"{{ $user->nickname }}"</span>!<br>
            </div>
            <div style="
                background: #fff;
                color: #444;
                padding: 20px;
                margin: 20px 0 40px;
                box-shadow: 0 15px 0 -8px rgba(0,0,0,0.1);
            ">
                <span class="username">{{ $user->nickname }}</span>님의 계정 생성은 아래의 '메일 인증하기'버튼을 눌러 완료할 수 있습니다.<br>
                간편하게 이메일 등록을 마치고 Lubycon 서비스에서 더욱 다양한 각 종 게임 커뮤니티를 즐겨보세요!
            </div>
        </div>
        <div class="card">
            <a
                style="
                    display: inline-block;
                    width: 100%;
                    height: 50px;
                    text-align: center;
                    background: #48cfad;
                    color: #fff;
                    font-size: 18px;
                    padding: 15px 30px;
                    box-sizing: border-box;
                    -webkit-box-sizing: border-box;
                    -moz-box-sizing: border-box;
                    cursor: pointer;
                    box-shadow: 0 3px 0 0 rgba(0,0,0,0.2);
                "
                href="{{ $_SERVER['HTTP_ORIGIN'] or 'http://localhost:3000' }}{{ '/certs/code/signup?code='.$token }}"
            >
                메일 인증하기
            </a>
        </div>
    </div>
    <div id="footer" style="width: 100%; height: 100px; background: #444;">

    </div>
</body>
</html>
