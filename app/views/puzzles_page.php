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

    h2 {
        text-align: center;
    }

    button {
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
        cursor: pointer;
    }

    button:hover {
        cursor: pointer;
        background-color: #000;
        color: #fff;
    }

    #hint:hover {
        cursor: pointer;
        background-color: #000;
        color: #fff;
    }

    #player {
        font-size: 3rem;
        color: #000;

    }

    .loading {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        font-weight: bold;
        color: #000;
    }

    .dot {
        margin: 0 5px;
        opacity: 0;
        animation-name: dots;
        animation-duration: 1.5s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }

    .dot-1,
    .dot-2,
    .dot-3 {
        opacity: 0;
        animation-name: dots;
        animation-duration: 1.5s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }

    .dot-2 {
        animation-delay: 0.5s;
    }

    .dot-3 {
        animation-delay: 1s;
    }

    .remove {
        display: none;
    }

    @keyframes dots {
        0% {
            opacity: 0;
        }

        25% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        75% {
            opacity: 0;
        }

        100% {
            opacity: 0;
        }
    }
</style>
</head>

<body>
    <h2 id="title">Daily puzzle</h2>
    <div>
        <div id="board"></div>
    </div>
    <div id="player" st></div>
    <div id="state"></div>
    <button id="hint">Mostra soluzione</button>
    <h2>Scegli una categoria di problemi</h2>
    <div class="button-group">
        <div>
            <button id="opening">Apertura</button>
            <button id="endgame">Finale</button>
        </div>
        <div>
            <button id="middlegame">Mediogioco</button>
        </div>
    </div>
    <a href="home">Torna al menu</a>
    <div id="loading" class="loading remove">
        <span>Loading</span>
        <span class="dot-1">.</span>
        <span class="dot-2">.</span>
        <span class="dot-3">.</span>
    </div>

</body>
<script>
</script>

</html>