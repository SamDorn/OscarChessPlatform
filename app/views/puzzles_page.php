<?php
require_once "pages.php";
htmlHead();
?>
<script src="js/scripts/puzzles.js" type="module"></script>
<style>
    body{
        display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
    .success{
        color: green;
        font-size: x-large;
    }
    .fail{
        color:red;
        font-size: x-large;
    }
    .right{
        color:orange;
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
    <div style="display:flex; justify-content:center; align-items:center">
        <div id="board" style="width: 600px; position:relative;"></div>
    </div>
    <div id="state"></div>
    <a href="index" style="display:flex; justify-content:center;">Torna al menu</a>
</body>
<script>
</script>

</html>