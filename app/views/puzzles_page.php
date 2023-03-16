<?php
require_once "pages.php";
htmlHead();
?>
<link rel="stylesheet" href="styles/chessboard/arrows.css">
<script src="js/scripts/puzzles.js" type="module"></script>
<style>
    @media only screen and (max-width: 600px) {
        #board {
            width: 350px;
        }
        .button-group {
            flex-direction: column;
        }
    }

    /* For PCs */
    @media only screen and (min-width: 601px) {
        #board {
             width: 600px; 
        }
        .button-group {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
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
</style>
</head>

<body>
    <h2 style="text-align: center;">Daily puzzle</h2>
    <div>
        <div id="board"></div>
    </div>
    <div id="state"></div>
    <h2 style="text-align: center;">Scegli una categoria di problemi</h2>
    <div class="button-group">
        <div>
            <a href="#">Apertura</a>
            <a href="#">Finale</a>
        </div>
        <div>
            <a href="#">Mediogioco</a>
            <a href="#">Finale di torre</a>
        </div>
        <div>
            <a href="#">Finale di donna</a>
            <a href="#">Finale di donna e torre</a>
        </div>
        <div>
            <a href="#">Finale di cavallo</a>
            <a href="#">Finale d'alfiere</a>
        </div>
        <div>
            <a href="#">Finale di pedoni</a>
        </div>
    </div>
    <a href="index">Torna al menu</a>
</body>
<script>
</script>

</html>