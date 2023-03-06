<?php require_once "pages.php" ?>

<?php htmlHead() ?>
<script src="js/scripts/vsComputer.js"></script>
</head>

<body>
    <?php if (isset($_SESSION["username"])) : ?>
        <div style="text-align: center;">
            <h1>HELLO <?= $_SESSION["username"] ?></h1>
        </div>
    <?php endif; ?>
    <div style="display:flex; justify-content:center;">
        <div id="myBoard" style="width: 600px; position:relative;"></div>
    </div>
    <div style="display:flex; justify-content:center;">
        <input type="text" inputmode="numeric" pattern="[0-9]+" id="skill">
        <button id="selectSkill">Select level difficulty</button>
        <button id="quit">Quit game</button>
        <button id="restart">Restart</button>
        <button id="menu">Back to menu</button>
    </div>
</body>
<script>
    var sessionId = undefined
    var skill = undefined
    var color = undefined
    var colorOpp = undefined

    $(document).ready(function() {

        $("#skill").keydown(function(event) {
            // Allow only backspace, delete, tab, and arrow keys
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39) {
                return true;
            } else {
                // Ensure that it is a number and check the input length
                if ((event.keyCode < 48 || event.keyCode > 57)) {
                    event.preventDefault();
                }
            }
        });

        var localFen = localStorage.getItem('fen')

        var localSkill = localStorage.getItem('skill')

        var localColorOpp = localStorage.getItem('colorOpp')

        $('#quit').hide()
        $('#restart').hide();
        $('#restart').click(function() {
            $('#restart').hide()
            removeQuit()
            resetGame()

        })

        $('#menu').click(function() {
            location.href = "index.php"
        })

        $('#selectSkill').click(function() {
            if($("#skill").val() <=20 && $("#skill").val() >=0  && $("#skill").val().length > 0)
            {
                $('#menu').hide()

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
            }
            else if($("#skill").val().length === 0){
                alert("Type a level between 0 and 20")
            }
            else{
                alert("Skill value not valid")
            }
            
        

        })

        $('#quit').click(function() {

            $('#menu').show()
            removeLocalStorage()

            resetGame()

            board.orientation('white')

            removeQuit()
        })
        sessionId = <?php echo "'" . session_id() . "'\n" ?>
        board = Chessboard("myBoard", config)

        board.clear(false)

        if (localStorage.getItem('fen') != null) {
            const response = confirm("Do you want to continue the match from where you left? yes(OK) no(Annulla)")
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