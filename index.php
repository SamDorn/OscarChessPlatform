<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OscarChessPlatform</title>
    <link rel="stylesheet" href="js/libraries/chessboardjs-1.0.0/css/chessboard-1.0.0.css">
    <script src="js/libraries/jQuery/jquery-3.6.3.min.js"></script>
    <script src="js/libraries/chess.js/chess.js"></script>
    <script src="js/libraries/chessboardjs-1.0.0/js/chessboard-1.0.0.js"></script>
    <script src="js/script.js"></script>
</head>

<body>
    <div id="myBoard" style="width: 800px;"></div>
    <label>Status:</label>
    <div id="status"></div>
    <label>FEN:</label>
    <div id="fen"></div>
    <label>PGN:</label>
    <div id="pgn"></div>
</body>
<script>
    var skill = 0
    var sessionId = <?php echo "'". session_id(). "'\n"?>
    board = Chessboard("myBoard", config)
    var $status = $('#status')
    var $fen = $('#fen')
    var $pgn = $('#pgn')
</script>
</html>
