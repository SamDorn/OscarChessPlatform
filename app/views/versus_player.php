<?php
require_once "pages.php";
if (!isset($_SESSION["username"])) {
    header('Location: index.php');
}
?>

<?php htmlHead() ?>
<script src="js/scripts/vsPlayer.js"></script>
</head>

<body>
    <div style="display:flex; justify-content:center;">
        <div id="myBoard" style="width: 600px; position:relative;"></div>
    </div>
    <div>
        <h1 id="prova" style="text-align: center;">Searching for a player...</h1>
    </div>
    <div style="display:flex; justify-content:center;">
        <button id="rigioca">Find another opponent</button>
    </div>
    <script>
        board = Chessboard("myBoard", config)
        $("#prova").hide()
        $("#myBoard").hide()
        $("#rigioca").hide()
        var username = '<?= $_SESSION['username'] ?>'
        $(document).ready(function() {
            if (socket.readyState === WebSocket.OPEN) {
                socket.send(JSON.stringify({
                    gameId: gameId,
                    fen: game.fen(),
                    username: username
                }));
            } else {
                socket.addEventListener('open', function(event) {
                    socket.send(JSON.stringify({
                        gameId: gameId,
                        fen: game.fen(),
                        username: username
                    }));
                });
            }
            $("#rigioca").click(function(e) {
                e.preventDefault()
                window.location.href = "index.php?action=vsPlayer"
            })
        });
    </script>
</body>

</html>