<?php
require_once "pages.php";
htmlHead()
?>
<script src="js/scripts/vsComputer.js" type="module"></script>
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
        <!-- <input type="text" inputmode="numeric" pattern="[0-9]+" id="skill"> 
        <button id="selectSkill">Select level difficulty</button>
        <button id="quit">Quit game</button>
        <button id="restart">Restart</button>
        <button id="menu">Back to menu</button>-->
    </div>
</body>
<script>
    var sessionId = "<?= session_id() ?>"
</script>

</html>