<?php
require_once "pages.php";
htmlHead()
?>
<link rel="stylesheet" href="styles/chessboard/arrows.css">
<script src="js/scripts/vsComputer.js" type="module"></script>
<style>
    @media only screen and (max-width: 600px) {
        #board {
            width: 350px;
        }
    }

    /* For PCs */
    @media only screen and (min-width: 601px) {
        #board {
            width: 600px;
        }
    }

    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
        overflow-y: hidden;
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
    #finish{
        color: #000;
        font-size: x-large;
    }
</style>
</head>
<body>
    <?php if (isset($_SESSION["username"])) : ?>
        <div style="text-align: center;">
            <h1>HELLO <?= $_SESSION["username"] ?></h1>
        </div>
    <?php endif; ?>
    <div style="display:flex; justify-content:center;">
        <div id="board" style="width: 600px; position:relative;"></div>
    </div>
    <div style="display:flex; justify-content:center;">
        <a href="index">Torna al menu</a>
        <button id="hint">Mostra mossa migliore</button>
    </div>
    <div id="finish"></div>
</body>
<script>
    var sessionId = "<?= session_id() ?>"
   
</script>

</html>