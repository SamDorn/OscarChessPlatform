<?php

use App\utilities\Jwt;
?>
<script src="js/scripts/gameLogic/vsPlayer.js" type="module"></script>
<script src="js/scripts/web_socket/connection.js"></script>
<link rel="stylesheet" href="styles/chessboard/cm-chessboard.css">
<link rel="stylesheet" href="styles/chessboard/promotion-dialog.css">
<link rel="stylesheet" href="styles/chessboard/arrows.css">
<style>
    body {
        background: #262626;
        overflow-y: hidden;
    }

    .ring {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 175px;
        height: 175px;
        background: transparent;
        border: 3px solid #3c3c3c;
        border-radius: 50%;
        text-align: center;
        font-family: sans-serif;
        font-size: 20px;
        color: #fff000;
        letter-spacing: 4px;
        text-transform: uppercase;
        text-shadow: 0 0 10px #fff000;
        box-shadow: 0 0 20px rgba(0, 0, 0, .5);
    }

    .ring:before {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        width: 100%;
        height: 100%;
        border: 3px solid transparent;
        border-top: 3px solid #fff000;
        border-right: 3px solid #fff000;
        border-radius: 50%;
        animation: animateC 2s linear infinite;
    }

    span {
        display: block;
        position: absolute;
        top: calc(50% - 2px);
        left: 50%;
        width: 50%;
        height: 4px;
        background: transparent;
        transform-origin: left;
        animation: animate 2s linear infinite;
    }

    span:before {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff000;
        top: -6px;
        right: -8px;
        box-shadow: 0 0 20px #fff000;
    }

    @keyframes animateC {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes animate {
        0% {
            transform: rotate(45deg);
        }

        100% {
            transform: rotate(405deg);
        }
    }

    #board {
        width: 800px;
    }

    .hidden {
        display: none;
    }

    a.button {
        display: inline-block;
        padding: 0.46em 1.6em;
        border: 0.1em solid #000000;
        margin: 0 0.2em 0.2em 0;
        border-radius: 0.12em;
        box-sizing: border-box;
        text-decoration: none;
        font-weight: 300;
        color: #000000;
        text-shadow: 0 0.04em 0.04em rgba(0, 0, 0, 0.35);
        background-color: #fff000;
        text-align: center;
        transition: all 0.15s;
        letter-spacing: 2px;
        background-color: #fff000;
        font-family: 'sans-serif';
        cursor: pointer;
    }

    @media all and (max-width:30em) {
        a.button {
            display: block;
            margin: 0.4em auto;

        }
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    h3 {
        color: #fff000;
    }

    .img-opponent {
        width: 50px;
        height: 50px;
    }

    .img-player {
        width: 50px;
        height: 50px;
    }
</style>
</head>

<body>

    <img src="" alt="" class="img-opponent">
    <div class="opponent">
        <h3 class="username-opponent"></h3>
    </div>

    <div id="board" class="hidden"></div>
    <img src="" alt="" class="hidden img-player">
    <h3 class="hidden username-player"></h3>
    <div class="player hidden"></div>
    <div class="ring">
        <p style="margin-top: 53px;">Searching for a player</p>
        <span></span>
    </div>
    <div class="center" style="display: flex; justify-content:center; align-items:center">
        <div style="font-size:1.5em"> <br><br><br><br><br><br><br><br><br><br><br><br><br>
            <a class="button">BACK TO MENU</a>
        </div>
    </div>


</body>
<script>
    var jwt = "<?= $_COOKIE['jwt'] ?? null ?>"
    var gameId = null
    var idPlayer = "<?= Jwt::getPayload($_COOKIE['jwt'])['user_id']; ?>"
</script>

</html>