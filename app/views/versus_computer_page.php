<link rel="stylesheet" href="styles/chessboard/cm-chessboard.css">
<link rel="stylesheet" href="styles/chessboard/promotion-dialog.css">
<link rel="stylesheet" href="styles/chessboard/arrows.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="js/scripts/gameLogic/vsComputer.js" type="module"></script>
<style>
    body {
        background-color: #262626;
    }

    /* INIZIO MODAL */
    .modal {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        z-index: 1;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        /* margin-top: 50% auto; */
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

    #hint:hover,
    #menu:hover {
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

    a#hint,
    a#menu,
    a#black,
    a#white,
    a#random,
    a#play,
    a#review-game,
    a#new-game {
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

    textarea {
        background-color: #000;
        border: 2px solid black;
        color: #fff000;
        font-size: 23px;
        margin-left: 40px;
    }

    .range-slider {
        position: relative;
        width: 200px;
        /* Set the width as per your requirement */
    }

    .range-slider input[type="range"] {
        width: 100%;
        -webkit-appearance: none;
        appearance: none;
        height: 10px;
        /* Set the height as per your requirement */
        border-radius: 5px;
        /* Rounded corners */
        background: #e9e9e9;
        /* Background color of the slider track */
        outline: none;
        /* Remove outline on focus */
        margin: 0;
        padding: 0;
    }

    .range-slider input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        /* Set the width of the thumb */
        height: 16px;
        /* Set the height of the thumb */
        border-radius: 50%;
        /* Rounded corners for the thumb */
        background: #171717;
        /* Color of the thumb */
        cursor: pointer;
        transition: 0.15s ease-in-out;
    }

    .range-slider input[type="range"]::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #171717;
        cursor: pointer;
        transition: 0.15s ease-in-out;
    }

    #black {
        margin-left: auto;
    }

    span {
        font-size: 20px;
    }

    .selected {
        box-shadow: 10px 10px 8px #888888;
    }

    .red {
        color: red;
        ;
    }

    .hidden {
        display: none;
    }
</style>
</head>

<body>
    <div id="modal" class="modal">
        <div class="modal-content">
            <h2 id="title-modal" style="text-align: center;">Before you start</h2>
            <span>Choose a color:</span>
            <a id="white" class="button" style="text-align:center">White<i class="fa-regular fa-chess-king"></i></a>
            <a id="black" class="button" id="review-game" style="text-align:center">Black<i class="fa-solid fa-chess-king"></i></a>
            <a id="random" class="button" id="review-game" style="text-align:center">Random<i class="fa-regular fa-chess-king"></i><i class="fa-solid fa-chess-king"></i></a>
            <div class="range-slider">
                <span>Choose the level of the computer:<span id="slider-value"></span><br><br></span><input type="range" min="0" max="20" step="1" id="slider">
                <span class="hidden red" id="error"></span>
                <a id="play" class="button" style="text-align:center">Play</a>
            </div>
        </div>
    </div>
    <div id="modal-finish" class="modal hidden">
        <div class="modal-content">
            <h2 id="title-modal" style="text-align: center;">Before you start</h2>
            <a href="vsComputer" class="button" id="new-game" style="text-align:center">New game</a>
            <a class="button" id="review-game" style="text-align:center">Review the game</a>

        </div>
    </div>
    <div id="board"></div>


    <div id="finish"></div>
    <a id="menu">Back to menu</a>
    <a id="hint">Show best move</a>
    </div>
    <div>
</body>
<script>
    var sessionId = "<?= uniqid() ?>"
    var jwt = "<?= $_COOKIE['jwt'] ?? null ?>"
    const slider = document.getElementById('slider');
    const sliderValue = document.getElementById('slider-value');
    var color = null
    var skill = null

    slider.addEventListener('input', function() {
        sliderValue.textContent = slider.value;
        skill = slider.value

    });
    $("#black").click(function(e) {
        e.preventDefault();
        color = "black"
        $("#white").removeClass("selected");
        $("#black").addClass("selected");
        $("#random").removeClass("selected");
    });
    $("#white").click(function(e) {
        e.preventDefault();
        color = "white"
        $("#black").removeClass("selected");
        $("#white").addClass("selected");
        $("#random").removeClass("selected");
    });
    $("#random").click(function(e) {
        e.preventDefault();
        color = "random"
        $("#white").removeClass("selected");
        $("#random").addClass("selected");
        $("#black").removeClass("selected");
    });
</script>

</html>