<link rel="stylesheet" href="styles/chessboard/cm-chessboard.css">
<link rel="stylesheet" href="styles/chessboard/promotion-dialog.css">
<link rel="stylesheet" href="styles/chessboard/arrows.css">
<script src="js/scripts/gameLogic/puzzles.js" type="module"></script>
<style>
    @media only screen and (min-width: 601px) {
        #board {
            width: 100%;
            max-width: 800px;
        }
        .button-group {
            flex-direction: row;
            justify-content: center;
        }
    }
    @media only screen and (max-width: 600px) {
        #board {
            width: 55%;
            min-width: 320px;
        }
        
    }
    #board {
        width: 45%;
        display: inline-grid;
    }
    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
        background-color: #262626;
    }

    .success {
        color: green;
        font-size: x-large;
    }

    .fail {
        color: red;
        font-size: x-large;
    }

    .right {
        color: orange;
        font-size: x-large;
    }

    a {
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


    h2 {
        text-align: center;
        color: white;
        font-size: 30px;
    }



    #player {
        font-size: 3rem;
        color: #000;

    }
    .secondary-button {
        padding: 30px 60px;
        font-size: 24px;
        background-color: #008CBA;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .secondary-button:hover{
        box-shadow: 0 0 10px #1a98c1;
    }
</style>
</head>

<body>
    <h2 id="title">Daily puzzle</h2>
    
        <div id="board"></div>
    
    <div id="player" style="font-size: 30px; color:white"></div>
    <div id="state"></div>
    <a id="hint" class="secondary-button">Mostra soluzione</a>
    <h2>Scegli una categoria di problemi</h2>
    <div class="button-group">
        <div>
            <a id="opening" class="secondary-button" style="font-size: 2rem;">Apertura</a>
            <a id="endgame" class="secondary-button" style="font-size: 2rem;">Finale</a>
        </div>
        <div>
            <a id="middlegame" class="secondary-button" style="font-size: 2rem;">Mediogioco</a><br><br><br>
        </div>
    </div>
    <a href="home" class="secondary-button">Torna al menu</a>

</body>
<script>
</script>

</html>