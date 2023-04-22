<style>
    .prova{
        
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    #play-buttons {
        display: none;
        flex-direction: row;
        margin: 10px auto;
        border: 0px;
    }

    a.play-button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 24px;
        text-align: center;
        text-decoration: none;
        border-radius: 10px;
        border: 2px solid #222;
        color: #222;
        background-color: #fff;
        transition: all 0.3s ease;
        margin: 10px;
    }

    a.play-button:hover {
        cursor: pointer;
        background-color: #222;
        color: #fff;
    }

    #learn-buttons {
        display: none;
        flex-direction: row;
        margin: 10px auto;
    }

    a.learn-button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 24px;
        text-align: center;
        text-decoration: none;
        border-radius: 10px;
        border: 2px solid #4CAF50;
        color: #4CAF50;
        background-color: #fff;
        transition: all 0.3s ease;
        margin: 10px;
    }

    a.learn-button:hover {
        cursor: pointer;
        background-color: #4CAF50;
        color: #fff;
    }

    a.login-button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 24px;
        text-align: center;
        text-decoration: none;
        border-radius: 10px;
        border: 2px solid #2196F3;
        color: #2196F3;
        background-color: #fff;
        transition: all 0.3s ease;
        margin: 10px;
    }

    a.login-button:hover {
        cursor: pointer;
        background-color: #2196F3;
        color: #fff;
    }
</style>
</head>

<body>
    <div class="prova">
        <a class="play-button" id="play-button">Play</a>
        <div id="play-buttons">
            <a class="play-button" id="computer-button">Vs Computer</a>
            <a class="play-button" id="online-button">Vs Player</a>
        </div>
        <a class="learn-button" id="learn-button">Learn</a>
        <div id="learn-buttons">
            <a class="learn-button" id="puzzle-button">Resolve puzzles</a>
            </a>
        </div>
        <a class="login-button" id="login">Login</a>
    </div>
</body>

<script>
    $("#play-button").click(function(e) {
        $("#play-buttons").css("display") === "block" ? $("#play-buttons").css("display", "none") : $("#play-buttons").css("display", "block")

    })
    $("#learn-button").click(function(e) {
        $("#learn-buttons").css("display") === "block" ? $("#learn-buttons").css("display", "none") : $("#learn-buttons").css("display", "block")

    })
    $("#login").click(function() {
        location.href = "login"
    })
    $("#puzzle-button").click(function(e) {
        location.href = "puzzles"
    })
    $("#computer-button").click(function(e) {
        location.href = "vsComputer"
    })
    $("#online-button").click(function(e) {
        location.href = "vsPlayer"

    });
</script>

</html>