<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css?family=Inconsolata');
    * {
        font-family: 'Inconsolata';
        color: #383838;
    }
    body {
        width: 100%;
        min-width: 320px;
        max-width: 720px;
        margin: 20px auto;
    }

    #header {
        width: 100%;
        height: auto;
        background: #fff;
        text-align: center;
    }
    #header img {
        width: 40%;
        min-width: 120px;
        margin-bottom: 20px;
    }
    #header p {
        margin: 0 0 20px 0;
        font-size: 14px;
    }

    #body {
        padding: 20px;
        background: #fff;
    }
    #body card {
        text-align: center;
    }
    #body p.intro {
        text-align: center;
        font-weight: bolder;
        font-size: 30px;
    }
    #body div.desc {
        line-height: 170%;
        background: #fff;
        color: #444;
        padding: 20px;
        margin: 20px 0 40px;
        text-align: center;
        box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    #body .card.button-wrapper .btn {
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
    }
    #body .card.button-wrapper .other-link {
        display: block;
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
    }

    #footer {
        width: 100%;
        margin-top: 50px;
    }
    #footer .copyrights {
        text-align: center;
    }

    @media screen and (max-width: 640px) {
        #body p.intro {
            font-size: 20px;
        }
    }
</style>
<body>
    <div id="header">
        <img src="https://s3-ap-northeast-1.amazonaws.com/pixelstairsdev/assets/logo/text_b.png" align="middle" alt="pixel-logo">
        <p>Connect your Creativity with the World</p>
    </div>
    <div id="body">
        <div class="card">
            <p class="intro">
                WHOO- YOU FINALLY!
            </p>
            <div class="desc">
                Hi, <span class="username">{{ $user->nickname }}</span>.<br>
                Thank you for create account in our service<br>
                From now, we'll always support your awesome creative works and your artistic life.<br>
            </div>
        </div>
        <div class="card button-wrapper">
            <a class="btn" href="{{ $_SERVER['HTTP_ORIGIN'] or 'http://localhost:3000' }}{{ '/certs/code/signup?code='.$token }}">
                Confirm
            </a>
            <a class="other-link" href="">Don't request this link?</a>
        </div>
    </div>
    <div id="footer">
        <div class="copyrights">Powered by 2017 Lubycon.co</div>
    </div>
</body>
</html>
