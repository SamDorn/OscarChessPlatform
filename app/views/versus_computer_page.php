<link rel="stylesheet" href="styles/chessboard/cm-chessboard.css">
<link rel="stylesheet" href="styles/chessboard/promotion-dialog.css">
<link rel="stylesheet" href="styles/chessboard/arrows.css">
<script src="js/scripts/gameLogic/vsComputer.js" type="module"></script>
<style>
    a {  

        text-align: center;
        display: inline-block;
        width: 140px;
        padding: 10px 20px;
        margin: 10px;
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        border: 2px solid black;
        border-radius: 10px;
        color: #333335;
    }

    a:hover {
        background-color: #000;
        color: #fff;
    }

    #hint {
        text-align: center;
        display: inline-block;
        width: 140px;
        padding: 10px 20px;
        margin: 10px;
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        border: 2px solid black;
        border-radius: 10px;
        color: #333335;
        background-color: #fff;
    }

    #hint:hover {
        cursor: pointer;
        background-color: #000;
        color: #fff;
    }

    #finish {
        color: #000;
        font-size: x-large;
    }

    .prova {
        display: grid;
    }
    #board {
        width: 45%;
        display: inline-grid;
    }
    @media only screen and (min-width: 601px) {
        #board {
            width: 45%;
        }
    }
    @media only screen and (max-width: 600px) {
        #board {
            width: 80%;
        }
    }
</style>
</head>

<body>
    <div class="prova">
        <div id="board"></div>
        <textarea name="" id="pgn" cols="30" rows="10"></textarea>
    <button style="font-size:3rem" id="previous"> < </button>
    <button style="font-size:3rem" id="next"> > </button>
 
    </div>
    <div id="finish"></div>
    <a id="menu">Torna al menu</a>
        <button id="hint">Mostra mossa migliore</button>
    </div>
    <div>
</body>
<script>
    var sessionId = "<?= uniqid() ?>"
    var jwt = "<?= $_COOKIE['jwt'] ?? null ?>"
</script>

</html>