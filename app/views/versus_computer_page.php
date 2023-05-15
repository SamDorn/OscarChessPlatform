<link rel="stylesheet" href="styles/chessboard/cm-chessboard.css">
<link rel="stylesheet" href="styles/chessboard/promotion-dialog.css">
<link rel="stylesheet" href="styles/chessboard/arrows.css">
<script src="js/scripts/gameLogic/vsComputer.js" type="module"></script>
<style>
    body{
        background-color: #262626;
    }
    /* INIZIO MODAL */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 50% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* FINE MODAL */
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

    #hint:hover, #menu:hover {
        cursor: pointer;
        box-shadow: 0 0 10px #fff000;
    }

    #finish {
        color: #000;
        font-size: x-large;
    }

    .prova {
        display: inline-block;
    }
    #board {
        width: 45%;
        display: inline-grid;
    }
    @media only screen and (min-width: 601px) {
        #board {
            width: 100%;
            max-width: 800px;
        }
    }
    @media only screen and (max-width: 600px) {
        #board {
            width: 55%;
            min-width: 320px;
        }
    }
    a#hint, a#menu {
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
        text-align: center;
        transition: all 0.15s;
        letter-spacing: 2px;
        background-color: #fff000;
        font-family: 'sans-serif';
        cursor: pointer;
    }
    textarea{
        background-color: #000;
        border: 2px solid black;
        color: #fff000;
        font-size: 23px;
        margin-left: 40px;
    }
</style>
</head>

<body>
    <div class="prova">
        <div id="board"></div>
        <textarea name="" id="pgn" cols="10" rows="30"></textarea>
 
    </div>
    <div id="finish"></div>
    <a id="menu">Torna al menu</a>
    <a id="hint">Mostra mossa migliore</a>
    </div>
    <div>
</body>
<script>
    var sessionId = "<?= uniqid() ?>"
    var jwt = "<?= $_COOKIE['jwt'] ?? null ?>"
</script>

</html>