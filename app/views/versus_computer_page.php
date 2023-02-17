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
    <script src="js/scripts/vsComputer.js"></script>
</head>

<body>
    <?php if (isset($_SESSION["username"])) : ?>
        <div style="text-align: center;">
            <h1>CIAO <?= $_SESSION["username"] ?></h1>
        </div>
    <?php endif; ?>
    <div style="display:flex; justify-content:center;">
        <div id="myBoard" style="width: 600px; position:relative;"></div>
    </div>
    <div>
        <input type="number" name="skill" id="skill">
        <button id="selectSkill">Select level difficulty</button>
        <button id="quit">Quit game</button>
        <button id="restart">Restart</button>
    </div>
</body>
<script>
    var sessionId = undefined
    var skill = undefined
    var color = undefined
    var colorOpp = undefined

    $(document).ready(function() {

        var localFen = localStorage.getItem('fen')

        var localSkill = localStorage.getItem('skill')

        var localColorOpp = localStorage.getItem('colorOpp')


        $('#quit').hide()
        $('#restart').hide();
        $('#restart').click(function() {
            $('#restart').hide()
            removeQuit()
            resetGame()



        });

        $('#selectSkill').click(function() {

            skill = $('#skill').val()

            removeSkill()

            board.position("rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1")

            localStorage.setItem("skill", skill)

            color = Math.floor(Math.random() * 2)

            if (color == 0) {
                color = 'w'
            } else
                color = 'b'

            if (color == 'w') {
                colorOpp = 'b'
            } else {
                colorOpp = 'w'

            }

            if (colorOpp == 'w') {
                board.orientation('black')
                localStorage.setItem("colorOpp", colorOpp)
                sendAjax(game.fen(), sessionId, skill)

            }

        })

        $('#quit').click(function() {

            removeLocalStorage()

            resetGame()

            board.orientation('white')

            removeQuit()
        })
        sessionId = <?php echo "'" . session_id() . "'\n" ?>
        board = Chessboard("myBoard", config)

        board.clear(false)

        if (localStorage.getItem('fen') != null) {
            const response = confirm("Cliccare su OK se si vuole continuare la partita, cliccando su ANNULLA se ne potra avviare un'altra")
            if (response) {

                colorOpp = localStorage.getItem("colorOpp")

                if (colorOpp == 'w') {
                    board.orientation('black')

                }



                game.load(localFen)
                board.position(localFen)

                skill = localSkill

                removeSkill()
            } else {

                removeLocalStorage()

                resetGame()

                removeQuit()
            }

        }


        function resetGame() {
            game.reset()
            board.clear(false)
        }

        function removeLocalStorage() {

            localStorage.removeItem('fen')
            localStorage.removeItem('skill')
            localStorage.removeItem('colorOpp')
        }

        function removeQuit() {

            $('#skill').show()
            $('#selectSkill').show()
            $('#quit').hide()
        }

        function removeSkill() {

            $('#skill').hide()
            $('#selectSkill').hide()
            $('#quit').show()
        }

    });
</script>

</html>