<!DOCTYPE html>
<html lang="en">
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
    <div style="display:flex; justify-content:center;">
        <div id="myBoard" style="width: 600px; position:relative;"></div>
    </div>
    <div>
        <input type="number" name="skill" id="skill">
        <button id="selectSkill">Select level difficulty</button>
    </div>
</body>
<script>
var btnSelectSkill = document.getElementById("selectSkill")
var skill = undefined
btnSelectSkill.addEventListener("click", function(){
skill = document.getElementById("skill").value
$('#skill').hide()
$('#selectSkill').hide()
localStorage.setItem("skill", skill)
})
var sessionId = <?php echo "'". session_id(). "'\n"?>
board = Chessboard("myBoard", config)
getFileContent()
</script>
</html>