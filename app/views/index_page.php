<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OscarChessPlatform</title>
    <style>
        body {
            
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
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
            color: #000;
        }

        a:hover {
            background-color: #000;
            color: #fff;
        }
    </style>
</head>

<body>
    <a href="vsComputer">Gioca contro il PC</a>
    <?php if (isset($_SESSION["username"])) : ?>
        <a href="vsPlayer">Gioca online</a>
    <?php endif; ?>
    <?php if (!isset($_SESSION["username"])) : ?>
    <a href="login">Effettua il login</a>
    <?php endif; ?>

    <a href="puzzle">Resolve puzzles</a>
</body>

</html>